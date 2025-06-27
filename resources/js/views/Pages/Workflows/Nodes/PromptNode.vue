<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { Handle, Position } from "@vue-flow/core";

// Props receiving data from the workflow editor
const props = defineProps({
  id: {
    type: String,
    required: true,
  },
  data: {
    type: Object,
    required: true,
  },
  selected: {
    type: Boolean,
    default: false,
  },
  isConnectable: {
    type: Boolean,
    default: true,
  },
  prompts: {
    type: Array,
    default: () => [],
  },
});

// Emit-Funktion für Datenaktualisierungen
const emit = defineEmits(["update:data"]);

// Node-Konfiguration mit verfügbaren Providern und Modellen
const config = ref({
  showSettings: false,
  availableProviders: [
    { value: "openai", label: "OpenAI" },
    { value: "anthropic", label: "Anthropic" },
    { value: "google", label: "Google" },
    { value: "deepseek", label: "DeepSeek" },
    { value: "ollama", label: "Ollama (Local)" },
  ],
  availableModels: {
    openai: [
      { value: "gpt-4-turbo", label: "GPT-4 Turbo" },
      { value: "gpt-4", label: "GPT-4" },
      { value: "gpt-3.5-turbo", label: "GPT-3.5 Turbo" },
    ],
    anthropic: [
      { value: "claude-3-opus", label: "Claude 3 Opus" },
      { value: "claude-3-sonnet", label: "Claude 3 Sonnet" },
      { value: "claude-3-haiku", label: "Claude 3 Haiku" },
    ],
    google: [
      { value: "gemini-pro", label: "Gemini Pro" },
      { value: "gemini-ultra", label: "Gemini Ultra" },
    ],
    deepseek: [
      { value: "deepseek-coder", label: "DeepSeek Coder" },
      { value: "deepseek-llm", label: "DeepSeek LLM" },
      { value: "deepseek-chat", label: "DeepSeek Chat" },
    ],
    ollama: [
      { value: "llama3", label: "Llama 3" },
      { value: "mistral", label: "Mistral" },
      { value: "mixtral", label: "Mixtral" },
    ],
  },
});

// Initialisierung der Node-Daten mit Defaultwerten aus props
const nodeData = ref({
  prompt_id: props.data.prompt_id || null,
  content: props.data.content || "",
  provider: props.data.provider || "openai",
  model: props.data.model || "gpt-4-turbo",
  temperature: parseFloat(props.data.temperature || 0.7),
  max_tokens: parseInt(props.data.max_tokens || 1000),
  output_variable: props.data.output_variable || "result",
  variables: props.data.variables || {},
});

// Prompt-Auswahl Status
const useSavedPrompt = ref(Boolean(props.data.prompt_id));
const selectedPromptId = ref(props.data.prompt_id || "");

// Verfügbare Modelle basierend auf dem ausgewählten Provider
const availableModels = computed(() => {
  return config.value.availableModels[nodeData.value.provider] || [];
});

// Finde alle Variablen im Prompt-Text ({{variablename}}-Format)
const extractedVariables = computed(() => {
  const content = nodeData.value.content || "";
  const regex = /\{\{([^{}]+)\}\}/g;
  const variables = [];
  let match;

  while ((match = regex.exec(content)) !== null) {
    const varName = match[1].trim();
    if (!variables.includes(varName)) {
      variables.push(varName);
    }
  }

  return variables;
});

// Prompt-Vorschau mit ersetzten Variablen
const promptPreview = computed(() => {
  let content = nodeData.value.content || "";

  // Ersetze alle {{variablename}} mit den tatsächlichen Werten
  Object.entries(nodeData.value.variables || {}).forEach(([key, value]) => {
    const pattern = new RegExp(`\\{\\{${key}\\}\\}`, "g");
    if (value) {
      content = content.replace(pattern, value);
    }
  });

  return content;
});

// Einstellungsmenü umschalten
const toggleSettings = (event) => {
  event.stopPropagation();
  config.value.showSettings = !config.value.showSettings;
};

// Node-Daten aktualisieren und an übergeordnete Komponente senden
const updateNodeData = (key, value) => {
  nodeData.value[key] = value;
  emit("update:data", { ...props.data, [key]: value });
};

// Provider wechseln und Modell aktualisieren
const onProviderChange = (provider) => {
  updateNodeData("provider", provider);

  const models = config.value.availableModels[provider] || [];
  if (models.length > 0) {
    updateNodeData("model", models[0].value);
  }
};

// Prompt auswählen und Inhalt aktualisieren
const selectPrompt = () => {
  updateNodeData("prompt_id", selectedPromptId.value);

  if (selectedPromptId.value) {
    // Versuche den Prompt in props.data.prompts zu finden (originaler Pfad)
    let selectedPrompt = null;

    if (props.data.prompts && props.data.prompts.length > 0) {
      selectedPrompt = props.data.prompts.find(
        (p) => p.id == selectedPromptId.value,
      );
    }

    // Fallback zu props.prompts wenn nicht gefunden
    if (!selectedPrompt && props.prompts && props.prompts.length > 0) {
      selectedPrompt = props.prompts.find(
        (p) => p.id == selectedPromptId.value,
      );
    }

    if (selectedPrompt) {
      updateNodeData("content", selectedPrompt.content);

      // Aktualisiere Variablen nach einer kurzen Verzögerung
      setTimeout(() => {
        updateVariablesFromContent();
      }, 50);
    }
  }
};

// Temperatur-Änderung verarbeiten
const onTemperatureChange = (event) => {
  const value = parseFloat(event.target.value);
  updateNodeData("temperature", value);
};

// Variable-Wert aktualisieren
const updateVariableValue = (varName, value) => {
  const updatedVariables = { ...nodeData.value.variables, [varName]: value };
  updateNodeData("variables", updatedVariables);
};

// Variablen aus Prompt-Inhalt extrahieren und im variables-Objekt speichern
const updateVariablesFromContent = () => {
  if (nodeData.value.content) {
    const currentVars = nodeData.value.variables || {};
    const updatedVars = { ...currentVars };

    // Regex für {{variablename}}-Muster
    const regex = /\{\{([^{}]+)\}\}/g;
    let match;

    while ((match = regex.exec(nodeData.value.content)) !== null) {
      const varName = match[1].trim();
      if (updatedVars[varName] === undefined) {
        updatedVars[varName] = "";
      }
    }

    updateNodeData("variables", updatedVars);
  }
};

// Überwachen des useSavedPrompt-Werts
watch(useSavedPrompt, (newValue) => {
  if (!newValue) {
    // Bei Wechsel zu benutzerdefiniertem Prompt, prompt_id löschen
    updateNodeData("prompt_id", null);
  } else {
    // Wenn Prompts verfügbar sind, wähle den ersten aus
    if (props.data.prompts && props.data.prompts.length > 0) {
      if (!selectedPromptId.value) {
        selectedPromptId.value = props.data.prompts[0].id;
        selectPrompt();
      }
    } else if (props.prompts && props.prompts.length > 0) {
      if (!selectedPromptId.value) {
        selectedPromptId.value = props.prompts[0].id;
        selectPrompt();
      }
    }
  }
});

// Initialisierung und Laden des Prompt-Inhalts
onMounted(() => {
  // Initialisiere Variables-Objekt, falls nicht vorhanden
  if (!nodeData.value.variables) {
    updateNodeData("variables", {});
  }

  // Wenn eine prompt_id existiert, lade den entsprechenden Prompt
  if (props.data.prompt_id) {
    selectedPromptId.value = props.data.prompt_id;
    selectPrompt();
  } else if (nodeData.value.content) {
    // Wenn kein Prompt ausgewählt ist, aber Content existiert, extrahiere Variablen
    updateVariablesFromContent();
  }
});

// Überwachen der Prompt-Auswahl
watch(selectedPromptId, (newValue) => {
  if (newValue) {
    selectPrompt();
  }
});

// Überwachen von Änderungen am Prompt-Inhalt, um neue Variablen zu erkennen
watch(
  () => nodeData.value.content,
  (newContent) => {
    if (newContent) {
      updateVariablesFromContent();
    }
  },
);

// Verhindern von Klick-Propagation
const preventDrag = (event) => {
  event.stopPropagation();
};
</script>

<template>
  <div :class="['prompt-node', { selected: selected }]">
    <!-- Source Handle (Output) -->
    <Handle
      type="source"
      position="bottom"
      :id="id + '-source'"
      :connectable="isConnectable"
    />

    <!-- Target Handle (Input) -->
    <Handle
      type="target"
      position="top"
      :id="id + '-target'"
      :connectable="isConnectable"
    />

    <!-- Node-Inhalt -->
    <div class="prompt-node-content">
      <div class="prompt-node-header">
        <i class="fa fa-file-alt text-primary me-1"></i>
        <span class="prompt-node-label">{{ data.label || "Prompt" }}</span>
        <button
          type="button"
          class="prompt-node-toggle"
          @click="toggleSettings"
          :title="
            config.showSettings
              ? 'Einstellungen ausblenden'
              : 'Einstellungen anzeigen'
          "
        >
          <i
            :class="[
              'fa',
              config.showSettings ? 'fa-chevron-up' : 'fa-chevron-down',
            ]"
          ></i>
        </button>
      </div>

      <!-- Prompt-Inhalt Vorschau (immer sichtbar) mit ersetzten Variablen -->
      <div class="prompt-content-preview">
        <div v-if="nodeData.content" class="prompt-text">
          <div class="preview-label mb-1">
            <small class="text-muted">Vorschau mit Variablen:</small>
          </div>
          <!-- Zeige den Prompt mit ersetzten Variablen -->
          <div class="preview-content">{{ promptPreview }}</div>
        </div>
        <div v-else class="prompt-placeholder">
          Kein Prompt-Inhalt definiert...
        </div>
      </div>

      <!-- Einstellungsbereich -->
      <div
        v-if="config.showSettings"
        class="prompt-node-settings"
        @mousedown="preventDrag"
        @click="preventDrag"
      >
        <!-- Prompt-Auswahl -->
        <div class="setting-group">
          <label class="setting-label">Prompt-Quelle</label>
          <div class="setting-option">
            <div class="form-check">
              <input
                type="radio"
                id="prompt-source-saved"
                class="form-check-input"
                :value="true"
                v-model="useSavedPrompt"
              />
              <label class="form-check-label" for="prompt-source-saved">
                Gespeicherter Prompt
              </label>
            </div>

            <!-- Dropdown für gespeicherte Prompts -->
            <div v-if="useSavedPrompt" class="mt-2">
              <select
                v-model="selectedPromptId"
                class="form-select form-select-sm"
                @change="selectPrompt"
              >
                <option value="">Prompt auswählen</option>
                <!-- Versuche zuerst props.data.prompts (original) -->
                <template
                  v-if="props.data.prompts && props.data.prompts.length > 0"
                >
                  <option
                    v-for="prompt in props.data.prompts"
                    :key="prompt.id"
                    :value="prompt.id"
                  >
                    {{ prompt.name }}
                  </option>
                </template>
                <!-- Fallback zu props.prompts -->
                <template v-else-if="props.prompts && props.prompts.length > 0">
                  <option
                    v-for="prompt in props.prompts"
                    :key="prompt.id"
                    :value="prompt.id"
                  >
                    {{ prompt.name }}
                  </option>
                </template>
              </select>

              <!-- Debugging-Info -->
              <div
                v-if="props.data.prompts && props.data.prompts.length > 0"
                class="text-muted small mt-1"
              >
                {{ props.data.prompts.length }} Prompts verfügbar
              </div>
              <div
                v-else-if="props.prompts && props.prompts.length > 0"
                class="text-muted small mt-1"
              >
                {{ props.prompts.length }} Prompts verfügbar
              </div>
              <div v-else class="text-muted small mt-1">
                Keine gespeicherten Prompts verfügbar
              </div>
            </div>

            <div class="form-check mt-2">
              <input
                type="radio"
                id="prompt-source-custom"
                class="form-check-input"
                :value="false"
                v-model="useSavedPrompt"
              />
              <label class="form-check-label" for="prompt-source-custom">
                Benutzerdefinierter Prompt
              </label>
            </div>
          </div>
        </div>

        <!-- Prompt-Inhalt (nur sichtbar bei benutzerdefiniertem Prompt) -->
        <div v-if="!useSavedPrompt" class="setting-group">
          <label class="setting-label" for="prompt-content"
            >Prompt-Inhalt</label
          >
          <div class="setting-option">
            <textarea
              id="prompt-content"
              class="form-control"
              rows="4"
              v-model="nodeData.content"
              @input="updateNodeData('content', $event.target.value)"
              placeholder="Gib deinen Prompt-Text ein..."
            ></textarea>
            <div class="form-text text-muted mt-1">
              <small
                >Verwende {{ variablename }} für Variablen im Prompt.</small
              >
            </div>
          </div>
        </div>

        <!-- Prompt-Variablen Eingabefelder -->
        <div v-if="extractedVariables.length > 0" class="setting-group">
          <label class="setting-label">Prompt-Variablen</label>
          <div class="setting-option">
            <div class="variable-info mb-2">
              <small class="text-muted">Im Prompt gefundene Variablen:</small>
            </div>
            <div
              v-for="varName in extractedVariables"
              :key="varName"
              class="variable-input mb-2"
            >
              <label :for="`var-${varName}`" class="form-label mb-1">{{
                varName
              }}</label>
              <input
                :id="`var-${varName}`"
                type="text"
                class="form-control form-control-sm"
                :value="nodeData.variables[varName] || ''"
                @input="updateVariableValue(varName, $event.target.value)"
                :placeholder="`Wert für {{${varName}}}`"
              />
            </div>
          </div>
        </div>

        <!-- LLM Provider-Auswahl -->
        <div class="setting-group">
          <label class="setting-label" for="prompt-provider">Provider</label>
          <div class="setting-option">
            <select
              id="prompt-provider"
              class="form-select form-select-sm"
              v-model="nodeData.provider"
              @change="onProviderChange($event.target.value)"
            >
              <option
                v-for="provider in config.availableProviders"
                :key="provider.value"
                :value="provider.value"
              >
                {{ provider.label }}
              </option>
            </select>
          </div>
        </div>

        <!-- Modell-Auswahl -->
        <div class="setting-group">
          <label class="setting-label" for="prompt-model">Modell</label>
          <div class="setting-option">
            <select
              id="prompt-model"
              class="form-select form-select-sm"
              v-model="nodeData.model"
              @change="updateNodeData('model', $event.target.value)"
            >
              <option
                v-for="model in availableModels"
                :key="model.value"
                :value="model.value"
              >
                {{ model.label }}
              </option>
            </select>
          </div>
        </div>

        <!-- Temperatur-Slider -->
        <div class="setting-group">
          <label class="setting-label" for="prompt-temperature">
            Temperatur: {{ nodeData.temperature }}
          </label>
          <div class="setting-option">
            <input
              id="prompt-temperature"
              type="range"
              class="form-range"
              min="0"
              max="2"
              step="0.1"
              :value="nodeData.temperature"
              @input="onTemperatureChange"
            />
          </div>
        </div>

        <!-- Max Tokens -->
        <div class="setting-group">
          <label class="setting-label" for="prompt-max-tokens"
            >Max Tokens</label
          >
          <div class="setting-option">
            <input
              id="prompt-max-tokens"
              type="number"
              class="form-control form-control-sm"
              v-model="nodeData.max_tokens"
              @change="
                updateNodeData('max_tokens', parseInt($event.target.value))
              "
              min="1"
              max="100000"
            />
          </div>
        </div>

        <!-- Ausgabe-Variable -->
        <div class="setting-group">
          <label class="setting-label" for="prompt-output-variable"
            >Ausgabe-Variable</label
          >
          <div class="setting-option">
            <input
              id="prompt-output-variable"
              type="text"
              class="form-control form-control-sm"
              v-model="nodeData.output_variable"
              @change="updateNodeData('output_variable', $event.target.value)"
              placeholder="result"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.prompt-node {
  padding: 10px;
  border-radius: 5px;
  background: white;
  border: 1px solid #ddd;
  width: 250px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.prompt-node.selected {
  border-color: #5e72e4;
  box-shadow: 0 0 0 2px rgba(94, 114, 228, 0.3);
}

.prompt-node-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 8px;
  font-weight: 600;
}

.prompt-node-toggle {
  background: none;
  border: none;
  cursor: pointer;
  color: #495057;
  padding: 2px 5px;
}

.prompt-node-toggle:hover {
  color: #5e72e4;
}

.prompt-content-preview {
  padding: 8px;
  margin-bottom: 10px;
  background-color: #f8f9fa;
  border: 1px solid #e9ecef;
  border-radius: 4px;
  max-height: 120px;
  overflow-y: auto;
}

.preview-content {
  font-size: 0.8rem;
  white-space: pre-wrap;
  word-break: break-word;
  color: #333;
}

.prompt-text {
  font-size: 0.8rem;
  white-space: pre-wrap;
  word-break: break-word;
  color: #333;
}

.prompt-placeholder {
  font-size: 0.8rem;
  font-style: italic;
  color: #adb5bd;
}

.prompt-node-settings {
  margin-top: 10px;
  font-size: 0.85rem;
}

.setting-group {
  margin-bottom: 12px;
}

.setting-label {
  display: block;
  margin-bottom: 4px;
  color: #495057;
  font-weight: 600;
  font-size: 0.8rem;
}

.setting-option {
  margin-bottom: 5px;
}

/* Stilisierung für die Prompt-Vorschau */
.prompt-preview {
  font-size: 0.8rem;
  white-space: pre-wrap;
  max-height: 100px;
  overflow-y: auto;
  background: white;
  padding: 5px;
  border-radius: 3px;
  border: 1px solid #e2e2e2;
}

.variable-input {
  background-color: #f0f4ff;
  padding: 8px;
  border-radius: 4px;
  border: 1px solid #d1deff;
}

/* Kontrollelemente nicht verschiebbar machen */
.prompt-node input,
.prompt-node select,
.prompt-node textarea,
.prompt-node button {
  pointer-events: auto;
}
</style>
