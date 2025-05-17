<script setup>
import { ref, computed, watch } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { useSortable } from "@/composables/useSortable";

const form = useForm({
  title: "",
  description: "",
  content: "",
  tags: [],
  prompt_sections: [
    { id: 1, type: "text", content: "", is_variable: false, variable_name: "" },
  ],
});

// For handling tag inputs
const newTag = ref("");
const extractedVariables = ref([]);
const isAiGenerating = ref(false);
const aiSuggestion = ref("");

// Section with editor focus
const activeSection = ref(null);

// Element references for drag-and-drop
const sectionsContainer = ref(null);

// Setup sortable for the sections
useSortable(sectionsContainer, {
  onEnd: (event) => {
    const newSections = [...form.prompt_sections];
    const [movedItem] = newSections.splice(event.oldIndex, 1);
    newSections.splice(event.newIndex, 0, movedItem);
    form.prompt_sections = newSections;
    updatePromptContent();
  },
});

// Extract variables from prompt content
const extractVariables = (content) => {
  if (!content) {
    extractedVariables.value = [];
    return;
  }

  const regex = /\{\{(.*?)\}\}/g;
  const matches = [...content.matchAll(regex)];
  extractedVariables.value = [
    ...new Set(matches.map((match) => match[1].trim())),
  ];
};

// Watch for changes in content to extract variables
watch(
  () => form.content,
  (newContent) => {
    extractVariables(newContent);
  },
);

// Add new tag
const addTag = () => {
  if (newTag.value.trim()) {
    if (!form.tags.includes(newTag.value.trim())) {
      form.tags.push(newTag.value.trim());
    }
    newTag.value = "";
  }
};

// Handle Enter key in tag input
const handleTagKeydown = (e) => {
  if (e.key === "Enter") {
    e.preventDefault();
    addTag();
  }
};

// Remove a tag
const removeTag = (index) => {
  form.tags.splice(index, 1);
};

// Add a new prompt section
const addSection = (type = "text") => {
  const newId = Math.max(0, ...form.prompt_sections.map((s) => s.id)) + 1;
  form.prompt_sections.push({
    id: newId,
    type,
    content: "",
    is_variable: false,
    variable_name: "",
  });
};

// Remove a section
const removeSection = (index) => {
  if (form.prompt_sections.length > 1) {
    form.prompt_sections.splice(index, 1);
    updatePromptContent();
  }
};

// Toggle variable status for a section
const toggleVariable = (section) => {
  section.is_variable = !section.is_variable;
  if (section.is_variable && !section.variable_name) {
    // Generate a default variable name
    section.variable_name = `variable_${section.id}`;
  }
  updatePromptContent();
};

// Update the main prompt content from all sections
const updatePromptContent = () => {
  let content = "";

  form.prompt_sections.forEach((section) => {
    if (section.is_variable) {
      content += `{{${section.variable_name}}}`;
    } else {
      content += section.content;
    }
    content += "\n";
  });

  form.content = content.trim();
};

// Watch all sections for changes
watch(
  () => form.prompt_sections,
  () => {
    updatePromptContent();
  },
  { deep: true },
);

// Generate improved prompt with AI
const generateImprovedPrompt = async (section) => {
  if (!section.content.trim()) return;

  isAiGenerating.value = true;
  activeSection.value = section;

  try {
    const response = await fetch("/api/prompts/enhance", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
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
      updatePromptContent();
    } else {
      console.error("Failed to generate improved prompt");
      // For demo, simulate AI response
      setTimeout(() => {
        section.content = improvePromptDemo(section.content);
        updatePromptContent();
        isAiGenerating.value = false;
      }, 1500);
    }
  } catch (error) {
    console.error("Error generating improved prompt:", error);
    // For demo, simulate AI response
    setTimeout(() => {
      section.content = improvePromptDemo(section.content);
      updatePromptContent();
      isAiGenerating.value = false;
    }, 1500);
  } finally {
    isAiGenerating.value = false;
  }
};

// Demo function to simulate AI prompt improvement
const improvePromptDemo = (originalPrompt) => {
  // This is just a demo enhancement - in a real app, this would come from your AI service
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

// Format date for consistency with other components
const formatDate = (dateString) => {
  if (!dateString) return "";

  const date = new Date(dateString);
  return new Intl.DateTimeFormat("de-DE", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  }).format(date);
};

// Submit the form
const submit = () => {
  updatePromptContent(); // Ensure content is updated before submission
  form.post(route("prompts.store"), {
    onSuccess: () => {
      // Reset form after successful submission
      form.reset();
      form.prompt_sections = [
        {
          id: 1,
          type: "text",
          content: "",
          is_variable: false,
          variable_name: "",
        },
      ];
      extractedVariables.value = [];
    },
  });
};
</script>

<template>
  <Head title="Create Prompt" />

  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h2 mb-1">Create Prompt</h1>
        <p class="text-muted">
          Design an AI prompt template with dynamic sections
        </p>
      </div>
      <nav class="breadcrumb push">
        <Link :href="route('dashboard')" class="breadcrumb-item">
          <i class="fa fa-home"></i>
        </Link>
        <Link :href="route('prompts.index')" class="breadcrumb-item">
          Prompts
        </Link>
        <span class="breadcrumb-item active">Create</span>
      </nav>
    </div>

    <!-- Create Prompt Form -->
    <form @submit.prevent="submit">
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Prompt Details</h3>
          <div class="block-options">
            <button
              type="submit"
              class="btn btn-sm btn-alt-primary"
              :disabled="form.processing"
            >
              <i class="fa fa-fw fa-check opacity-50"></i> Save Prompt
            </button>
          </div>
        </div>
        <div class="block-content block-content-full">
          <div class="row items-push">
            <div class="col-lg-4">
              <p class="text-muted">
                Create a prompt template that can be reused in your workflows.
                Add dynamic sections and mark them as variables or use
                <code v-pre>{{ variable_name }}</code> syntax for variables that
                can be dynamically replaced.
              </p>

              <div
                v-if="extractedVariables.length > 0"
                class="block block-rounded bg-body-light"
              >
                <div class="block-header">
                  <h3 class="block-title fs-sm">
                    <i class="fa fa-fw fa-code me-1"></i> Detected Variables
                  </h3>
                </div>
                <div class="block-content">
                  <div class="d-flex flex-wrap gap-1 mb-2">
                    <span
                      v-for="variable in extractedVariables"
                      :key="variable"
                      class="fs-sm fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success"
                    >
                      {{ variable }}
                    </span>
                  </div>
                  <p class="fs-sm text-muted mb-0">
                    These variables will be available for input during prompt
                    testing and workflow execution.
                  </p>
                </div>
              </div>

              <!-- Version Info Block for new users -->
              <div class="block block-rounded bg-body-light mb-4">
                <div class="block-header">
                  <h3 class="block-title fs-sm">
                    <i class="fa fa-fw fa-history me-1"></i> Versioning
                  </h3>
                </div>
                <div class="block-content fs-sm">
                  <p>
                    Your prompt will be versioned automatically. Each time you
                    edit the content, a new version will be created.
                  </p>
                  <p class="mb-0">
                    You'll be able to view and restore previous versions after
                    saving.
                  </p>
                </div>
              </div>

              <!-- Tips Block -->
              <div class="block block-rounded bg-body-light">
                <div class="block-header">
                  <h3 class="block-title fs-sm">
                    <i class="fa fa-fw fa-lightbulb me-1"></i> Prompt
                    Engineering Tips
                  </h3>
                </div>
                <div class="block-content fs-sm">
                  <ul class="mb-0">
                    <li>
                      Be specific about the task you want the AI to perform
                    </li>
                    <li>Define the format you want the output in</li>
                    <li>
                      Provide examples for better results (few-shot learning)
                    </li>
                    <li>Break complex tasks into smaller steps</li>
                    <li>Use variables for dynamic content</li>
                    <li>
                      Use the AI enhancement button to improve your sections
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="col-lg-8">
              <!-- Title -->
              <div class="mb-4">
                <label class="form-label" for="prompt-title">Title</label>
                <input
                  type="text"
                  id="prompt-title"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.title }"
                  v-model="form.title"
                  placeholder="Give your prompt a descriptive name"
                />
                <div v-if="form.errors.title" class="invalid-feedback">
                  {{ form.errors.title }}
                </div>
              </div>

              <!-- Description -->
              <div class="mb-4">
                <label class="form-label" for="prompt-description"
                  >Description (Optional)</label
                >
                <textarea
                  id="prompt-description"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.description }"
                  v-model="form.description"
                  rows="2"
                  placeholder="Describe what this prompt does and how to use it"
                ></textarea>
                <div v-if="form.errors.description" class="invalid-feedback">
                  {{ form.errors.description }}
                </div>
              </div>

              <!-- Tags -->
              <div class="mb-4">
                <label class="form-label" for="prompt-tags"
                  >Tags (Optional)</label
                >
                <div class="d-flex flex-wrap gap-1 mb-2">
                  <span
                    v-for="(tag, index) in form.tags"
                    :key="index"
                    class="fs-sm fw-semibold d-inline-block py-1 px-3 rounded-pill bg-primary-light text-primary"
                  >
                    {{ tag }}
                    <button
                      type="button"
                      class="btn-close btn-close-white fs-sm ms-1"
                      @click="removeTag(index)"
                    ></button>
                  </span>
                </div>
                <div class="input-group">
                  <input
                    type="text"
                    id="prompt-tags"
                    class="form-control"
                    v-model="newTag"
                    @keydown="handleTagKeydown"
                    placeholder="Add a tag and press Enter"
                  />
                  <button
                    type="button"
                    class="btn btn-alt-primary"
                    @click="addTag"
                  >
                    <i class="fa fa-plus me-1"></i> Add
                  </button>
                </div>
                <div class="fs-sm text-muted mt-1">
                  Tags help you organize and find your prompts later.
                </div>
              </div>

              <!-- Dynamic Prompt Sections -->
              <div class="mb-4">
                <div
                  class="d-flex justify-content-between align-items-center mb-2"
                >
                  <label class="form-label mb-0">Prompt Sections</label>
                  <button
                    type="button"
                    class="btn btn-sm btn-alt-success"
                    @click="addSection"
                  >
                    <i class="fa fa-plus me-1"></i> Add Section
                  </button>
                </div>

                <div ref="sectionsContainer" class="sections-container">
                  <div
                    v-for="(section, index) in form.prompt_sections"
                    :key="section.id"
                    class="block block-rounded mb-3 position-relative prompt-section"
                    :class="{ 'block-bordered': section.is_variable }"
                  >
                    <div class="block-header block-header-default">
                      <h3 class="block-title fs-sm">
                        <i
                          class="fa me-1"
                          :class="
                            section.is_variable
                              ? 'fa-code text-info'
                              : 'fa-align-left'
                          "
                        ></i>
                        {{
                          section.is_variable
                            ? `Variable: ${section.variable_name}`
                            : `Section ${index + 1}`
                        }}
                      </h3>
                      <div class="block-options">
                        <!-- Drag handle -->
                        <button
                          type="button"
                          class="btn btn-sm btn-alt-secondary js-tooltip handle"
                          title="Drag to reorder"
                        >
                          <i class="fa fa-fw fa-arrows-alt"></i>
                        </button>

                        <!-- Toggle variable -->
                        <button
                          type="button"
                          class="btn btn-sm"
                          :class="
                            section.is_variable
                              ? 'btn-alt-info'
                              : 'btn-alt-secondary'
                          "
                          @click="toggleVariable(section)"
                          :title="
                            section.is_variable
                              ? 'Convert to text'
                              : 'Convert to variable'
                          "
                        >
                          <i
                            class="fa fa-fw"
                            :class="section.is_variable ? 'fa-font' : 'fa-code'"
                          ></i>
                        </button>

                        <!-- AI enhance button -->
                        <button
                          v-if="!section.is_variable"
                          type="button"
                          class="btn btn-sm btn-alt-warning"
                          @click="generateImprovedPrompt(section)"
                          :disabled="isAiGenerating"
                        >
                          <i
                            class="fa fa-fw"
                            :class="
                              isAiGenerating && activeSection === section
                                ? 'fa-spinner fa-spin'
                                : 'fa-magic'
                            "
                          ></i>
                        </button>

                        <!-- Remove section -->
                        <button
                          type="button"
                          class="btn btn-sm btn-alt-danger"
                          @click="removeSection(index)"
                          :disabled="form.prompt_sections.length <= 1"
                        >
                          <i class="fa fa-fw fa-times"></i>
                        </button>
                      </div>
                    </div>

                    <div class="block-content p-2">
                      <div v-if="section.is_variable" class="mb-2">
                        <div class="input-group input-group-sm">
                          <span class="input-group-text bg-body-light"
                            >Variable Name</span
                          >
                          <input
                            type="text"
                            class="form-control form-control-sm"
                            v-model="section.variable_name"
                            placeholder="Enter variable name"
                          />
                        </div>
                      </div>
                      <textarea
                        v-else
                        class="form-control"
                        v-model="section.content"
                        rows="4"
                        placeholder="Enter prompt text or click the magic wand to enhance with AI"
                      ></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Final Generated Prompt Preview -->
              <div class="mb-4">
                <label class="form-label" for="prompt-content">
                  Final Prompt Preview
                  <small class="text-muted"
                    >(Generated from sections above)</small
                  >
                </label>
                <textarea
                  id="prompt-content"
                  class="form-control bg-body-light"
                  v-model="form.content"
                  rows="6"
                  readonly
                ></textarea>
                <div
                  v-if="form.errors.content"
                  class="invalid-feedback d-block"
                >
                  {{ form.errors.content }}
                </div>
                <div class="fs-sm text-muted mt-1">
                  This is the final prompt that will be saved. It's
                  automatically generated from your sections above.
                </div>
              </div>

              <!-- Submit -->
              <div class="d-flex justify-content-end">
                <Link
                  :href="route('prompts.index')"
                  class="btn btn-alt-secondary me-1"
                  :disabled="form.processing"
                >
                  Cancel
                </Link>
                <button
                  type="submit"
                  class="btn btn-alt-primary"
                  :disabled="form.processing"
                >
                  <i class="fa fa-fw fa-check opacity-50 me-1"></i> Create
                  Prompt
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<style scoped>
.handle {
  cursor: grab;
}
.handle:active {
  cursor: grabbing;
}
.prompt-section {
  transition: all 0.2s ease;
}
.prompt-section:hover {
  box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.05);
}
</style>
