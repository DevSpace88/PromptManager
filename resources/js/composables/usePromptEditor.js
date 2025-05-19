import { ref, watch } from "vue";

// Diese Composable-Funktion kapselt die Logik für den Prompt-Sektionseditor.
// Das 'form'-Objekt (von useForm) wird von der Komponente bereitgestellt und hier manipuliert.
export function usePromptEditor(form) {
  const newTag = ref("");
  const extractedVariables = ref([]);
  const isAiGenerating = ref(false);
  const activeSection = ref(null); // Die Sektion, die gerade von der KI bearbeitet wird

  // Initialisiert die Sektionen basierend auf dem form.content.
  // Diese Funktion sollte in der Komponente aufgerufen werden, nachdem form.content initialisiert wurde.
  const initSectionsFromContent = () => {
    const contentToParse = form.content;
    if (!contentToParse) {
      form.prompt_sections = [
        {
          id: 1,
          type: "text",
          content: "",
          is_variable: false,
          variable_name: "",
        },
      ];
      return;
    }

    const sections = [];
    let idCounter = 1;
    const regex = /\\{\\{(.*?)\\}\\}/g; // Regex für {{variable}}
    let lastIndex = 0;
    let match;

    while ((match = regex.exec(contentToParse)) !== null) {
      if (match.index > lastIndex) {
        const textContent = contentToParse.substring(lastIndex, match.index);
        if (textContent.trim()) {
          // Nur hinzufügen, wenn der Textinhalt nicht nur Leerzeichen ist
          sections.push({
            id: idCounter++,
            type: "text",
            content: textContent,
            is_variable: false,
            variable_name: "",
          });
        }
      }
      sections.push({
        id: idCounter++,
        type: "text",
        content: "",
        is_variable: true,
        variable_name: match[1].trim(),
      });
      lastIndex = match.index + match[0].length;
    }

    if (lastIndex < contentToParse.length) {
      const remainingText = contentToParse.substring(lastIndex);
      if (remainingText.trim()) {
        // Nur hinzufügen, wenn der restliche Text nicht nur Leerzeichen ist
        sections.push({
          id: idCounter++,
          type: "text",
          content: remainingText,
          is_variable: false,
          variable_name: "",
        });
      }
    }

    // Wenn nach dem Parsen keine Sektionen erstellt wurden (z.B. der ursprüngliche Inhalt war leer oder nur Variablen ohne Text drumherum),
    // dann erstelle eine einzelne Text-Sektion mit dem ursprünglichen Inhalt.
    // Dies ist wichtig, falls der Prompt nur aus Text ohne Variablen besteht oder komplett leer ist.
    if (sections.length === 0) {
      sections.push({
        id: idCounter++,
        type: "text",
        content: contentToParse,
        is_variable: false,
        variable_name: "",
      });
    }
    form.prompt_sections = sections;
  };

  // Extrahiert Variablennamen aus dem Prompt-Inhalt
  const extractVariables = (content) => {
    if (!content) {
      extractedVariables.value = [];
      return;
    }
    const regex = /\\{\\{(.*?)\\}\\}/g;
    const matches = [...content.matchAll(regex)];
    extractedVariables.value = [...new Set(matches.map((m) => m[1].trim()))];
  };

  // Beobachtet Änderungen in form.content, um Variablen neu zu extrahieren
  watch(
    () => form.content,
    (newContent) => {
      extractVariables(newContent);
    },
  );

  // Aktualisiert form.content basierend auf den aktuellen Sektionen in form.prompt_sections
  const updatePromptContent = () => {
    let content = "";
    form.prompt_sections.forEach((section) => {
      if (section.is_variable) {
        content += `{{${section.variable_name.trim()}}}`;
      } else {
        content += section.content;
      }
      content += "\\n"; // Füge nach jeder Sektion einen Zeilenumbruch hinzu
    });
    form.content = content.trim(); // Entferne führende/nachfolgende Leerzeichen und Zeilenumbrüche
  };

  // Beobachtet Änderungen in form.prompt_sections, um form.content zu aktualisieren
  watch(() => form.prompt_sections, updatePromptContent, { deep: true });

  // Tag-Management
  const addTag = () => {
    if (newTag.value.trim()) {
      if (!form.tags.includes(newTag.value.trim())) {
        form.tags.push(newTag.value.trim());
      }
      newTag.value = "";
    }
  };

  const handleTagKeydown = (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      addTag();
    }
  };

  const removeTag = (index) => {
    form.tags.splice(index, 1);
  };

  // Sektions-Management
  const addSection = (type = "text") => {
    const newId =
      form.prompt_sections.length > 0
        ? Math.max(0, ...form.prompt_sections.map((s) => s.id)) + 1
        : 1;
    form.prompt_sections.push({
      id: newId,
      type,
      content: "",
      is_variable: false,
      variable_name: "",
    });
  };

  const removeSection = (index) => {
    if (form.prompt_sections.length > 1) {
      form.prompt_sections.splice(index, 1);
      // updatePromptContent(); // Wird durch den Watcher auf form.prompt_sections automatisch ausgelöst
    }
  };

  const toggleVariable = (section) => {
    section.is_variable = !section.is_variable;
    if (section.is_variable && !section.variable_name) {
      section.variable_name = `variable_${section.id}`;
    }
    // updatePromptContent(); // Wird durch den Watcher automatisch ausgelöst
  };

  // KI-Vorschläge (Demo-Implementierung)
  const improvePromptDemo = (originalPrompt) => {
    const enhanced = originalPrompt.trim();
    if (enhanced.toLowerCase().includes("summarize")) {
      return `Please provide a comprehensive summary of the following text. The summary should:
- Capture the main ideas and key points
- Be approximately 25% of the original length
- Maintain the tone and style of the original
- Highlight any crucial statistics or data points
- Organize information logically with clear structure

Text to summarize:
${enhanced.replace(/summarize/i, "")}`;
    } else if (enhanced.toLowerCase().includes("write")) {
      return `Create a well-structured, engaging piece of writing that:
- Addresses the topic: ${enhanced.replace(/write/i, "")}
- Uses a professional, clear tone
- Includes specific examples and evidence
- Follows a logical structure with introduction, main points, and conclusion
- Avoids common clichés and generic statements
- Provides unique insights backed by reasoning`;
    } else {
      return `${enhanced}

Please provide a detailed, step-by-step response that:
- Addresses all aspects of the request thoroughly
- Uses specific examples where appropriate
- Explains reasoning and methodology clearly
- Structures information in a logical sequence
- Considers potential limitations or alternatives
- Focuses on practical, actionable information`;
    }
  };

  const generateImprovedPrompt = async (section) => {
    if (!section.content.trim()) return;

    isAiGenerating.value = true;
    activeSection.value = section;

    try {
      const response = await fetch("/api/prompts/enhance", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
        },
        body: JSON.stringify({
          prompt: section.content,
          context:
            "Turn this into a more detailed, effective AI prompt using best practices",
        }),
      });

      if (response.ok) {
        const data = await response.json();
        section.content = data.improved_prompt;
      } else {
        console.error("Failed to generate improved prompt, using demo.");
        // Fallback zur Demo-Funktion mit Verzögerung
        setTimeout(() => {
          if (activeSection.value === section) {
            // Überprüfen, ob die Sektion noch aktiv ist
            section.content = improvePromptDemo(section.content);
          }
        }, 1500);
      }
    } catch (error) {
      console.error("Error generating improved prompt:", error);
      // Fallback zur Demo-Funktion mit Verzögerung
      setTimeout(() => {
        if (activeSection.value === section) {
          // Überprüfen, ob die Sektion noch aktiv ist
          section.content = improvePromptDemo(section.content);
        }
      }, 1500);
    } finally {
      // Setze isAiGenerating zurück, aber stelle sicher, dass es nicht zu früh passiert, wenn ein Timeout läuft.
      // Wenn die API schnell antwortet (response.ok), wird isAiGenerating bald zurückgesetzt.
      // Wenn ein Timeout verwendet wird, sollte es erst nach dem Timeout zurückgesetzt werden.
      // Die aktuelle Logik im Watcher für form.prompt_sections (der updatePromptContent auslöst) und
      // die UI-Bindung an `isAiGenerating && activeSection === section` sollte das UI korrekt halten.
      // Wir setzen es hier generell zurück. Wenn ein Timeout aktiv ist, bleibt der Spinner ggf. kurz weg
      // und kommt wieder, bis der Timeout die Section aktualisiert.
      // Eine robustere Lösung würde das State-Management um die Timeouts verfeinern.
      // Für den Moment:
      if (activeSection.value === section) {
        // Wenn die API sofort fehlschlägt (nicht ok UND kein Timeout), wird AI generating hier beendet.
        // Wenn die API erfolgreich ist, wird AI generating auch hier beendet.
        // Wenn ein Timeout gestartet wurde, sollte dies erst im Timeout passieren.
        // Die ursprüngliche Logik setzte es immer im finally.
        isAiGenerating.value = false;
        // activeSection.value = null; // Optional: aktive Sektion zurücksetzen
      }
    }
  };

  // Initialisiere prompt_sections, falls form.content bereits existiert, aber prompt_sections leer ist.
  // Dies ist wichtig für Edit.vue, wo form.content von Props kommt.
  // In Create.vue ist form.content initial leer, daher wird die Standardsektion erstellt.
  if (
    form.content &&
    (!form.prompt_sections || form.prompt_sections.length === 0)
  ) {
    initSectionsFromContent();
  } else if (!form.prompt_sections || form.prompt_sections.length === 0) {
    form.prompt_sections = [
      {
        id: 1,
        type: "text",
        content: "",
        is_variable: false,
        variable_name: "",
      },
    ];
  }

  // Gebe alle reaktiven Werte und Methoden zurück, die in der Komponente benötigt werden
  return {
    newTag,
    extractedVariables,
    isAiGenerating,
    activeSection,

    initSectionsFromContent, // Muss in der Komponente nach Initialisierung von form.content aufgerufen werden
    extractVariables, // Wird intern durch Watch aufgerufen, kann aber auch exponiert werden
    // updatePromptContent, // Wird intern durch Watch aufgerufen

    addTag,
    handleTagKeydown,
    removeTag,

    addSection,
    removeSection,
    toggleVariable,

    generateImprovedPrompt,
  };
}
