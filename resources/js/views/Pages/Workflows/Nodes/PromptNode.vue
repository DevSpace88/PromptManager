<!--<script setup>-->
<!--import { ref, computed, onMounted, watch } from 'vue';-->
<!--import { Handle, Position } from '@vue-flow/core';-->

<!--// Props receiving data from the workflow editor-->
<!--const props = defineProps({-->
<!--  id: {-->
<!--    type: String,-->
<!--    required: true-->
<!--  },-->
<!--  data: {-->
<!--    type: Object,-->
<!--    required: true-->
<!--  },-->
<!--  selected: {-->
<!--    type: Boolean,-->
<!--    default: false-->
<!--  },-->
<!--  isConnectable: {-->
<!--    type: Boolean,-->
<!--    default: true-->
<!--  },-->
<!--  prompts: {-->
<!--    type: Array,-->
<!--    default: () => []-->
<!--  }-->
<!--});-->

<!--// Zugriff auf die Prompts, die im data-Objekt übergeben werden-->
<!--const promptsList = computed(() => {-->
<!--  console.log("Verfügbare Prompts:", props.prompts);-->
<!--  return props.prompts || [];-->
<!--});-->


<!--// Node configuration-->
<!--const config = ref({-->
<!--  showSettings: false,-->
<!--  availableProviders: [-->
<!--    { value: 'openai', label: 'OpenAI' },-->
<!--    { value: 'anthropic', label: 'Anthropic' },-->
<!--    { value: 'google', label: 'Google' },-->
<!--    { value: 'ollama', label: 'Ollama (Local)' },-->
<!--  ],-->
<!--  availableModels: {-->
<!--    openai: [-->
<!--      { value: 'gpt-4-turbo', label: 'GPT-4 Turbo' },-->
<!--      { value: 'gpt-4', label: 'GPT-4' },-->
<!--      { value: 'gpt-3.5-turbo', label: 'GPT-3.5 Turbo' }-->
<!--    ],-->
<!--    anthropic: [-->
<!--      { value: 'claude-3-opus', label: 'Claude 3 Opus' },-->
<!--      { value: 'claude-3-sonnet', label: 'Claude 3 Sonnet' },-->
<!--      { value: 'claude-3-haiku', label: 'Claude 3 Haiku' }-->
<!--    ],-->
<!--    google: [-->
<!--      { value: 'gemini-pro', label: 'Gemini Pro' },-->
<!--      { value: 'gemini-ultra', label: 'Gemini Ultra' }-->
<!--    ],-->
<!--    ollama: [-->
<!--      { value: 'llama3', label: 'Llama 3' },-->
<!--      { value: 'mistral', label: 'Mistral' },-->
<!--      { value: 'mixtral', label: 'Mixtral' }-->
<!--    ]-->
<!--  }-->
<!--});-->

<!--// Emit-Funktion, um Änderungen an den Workflow-Editor zu senden-->
<!--const emit = defineEmits(['update:data']);-->

<!--// Lokale Kopie der Node-Daten, um direkte Bindung zu ermöglichen-->
<!--const nodeData = ref({-->
<!--  prompt_id: props.data.prompt_id || null,-->
<!--  content: props.data.content || '',-->
<!--  provider: props.data.provider || 'openai',-->
<!--  model: props.data.model || 'gpt-4-turbo',-->
<!--  temperature: parseFloat(props.data.temperature || 0.7),-->
<!--  max_tokens: parseInt(props.data.max_tokens || 1000),-->
<!--  output_variable: props.data.output_variable || 'result'-->
<!--});-->

<!--// Prompt selection state-->
<!--const useSavedPrompt = ref(props.data.prompt.id !== null);-->
<!--const selectedPromptId = ref(props.data.prompt_id || '');-->

<!--// Get models based on selected provider-->
<!--const availableModels = computed(() => {-->
<!--  return config.value.availableModels[nodeData.value.provider] || [];-->
<!--});-->

<!--// Computed property für den ausgewählten Prompt-->
<!--const selectedPromptContent = computed(() => {-->
<!--  if (!selectedPromptId.value) return '';-->

<!--  const selectedPrompt = promptsList.value.find(p => p.id == selectedPromptId.value);-->
<!--  console.log("Selected prompt:", selectedPrompt);-->
<!--  return selectedPrompt ? selectedPrompt.content : '';-->
<!--});-->

<!--// Toggle settings panel-->
<!--const toggleSettings = (event) => {-->
<!--  // Verhindere Event-Bubbling, damit der Node nicht verschoben wird-->
<!--  event.stopPropagation();-->
<!--  config.value.showSettings = !config.value.showSettings;-->
<!--};-->

<!--// Update node data-->
<!--const updateNodeData = (key, value) => {-->
<!--  // Aktualisiere die lokale Kopie-->
<!--  nodeData.value[key] = value;-->

<!--  // Sendet die aktualisierten Daten an den übergeordneten Workflow-Editor-->
<!--  emit('update:data', { ...props.data, [key]: value });-->
<!--};-->

<!--// When provider changes, update model to the first available model-->
<!--const onProviderChange = (provider) => {-->
<!--  updateNodeData('provider', provider);-->

<!--  const models = config.value.availableModels[provider] || [];-->
<!--  if (models.length > 0) {-->
<!--    updateNodeData('model', models[0].value);-->
<!--  }-->
<!--};-->

<!--// Method to select a prompt-->
<!--const selectPrompt = () => {-->
<!--  updateNodeData('prompt_id', selectedPromptId.value);-->

<!--  // If there's a selected prompt, fetch its content-->
<!--  if (selectedPromptId.value) {-->
<!--    const selectedPrompt = promptsList.value.find(p => p.id == selectedPromptId.value);-->
<!--    if (selectedPrompt) {-->
<!--      updateNodeData('content', selectedPrompt.content);-->
<!--    }-->
<!--  }-->
<!--};-->

<!--// Methode für Temperatur-Änderung-->
<!--const onTemperatureChange = (event) => {-->
<!--  const value = parseFloat(event.target.value);-->
<!--  updateNodeData('temperature', value);-->
<!--};-->

<!--// Watch for changes in the useSavedPrompt value-->
<!--watch(useSavedPrompt, (newValue) => {-->
<!--  if (!newValue) {-->
<!--    // If switching to custom prompt, clear the prompt_id-->
<!--    updateNodeData('prompt_id', null);-->
<!--  } else {-->
<!--    // If switching to saved prompt, but no prompt selected yet-->
<!--    if (!selectedPromptId.value && promptsList.value.length > 0) {-->
<!--      // Pre-select the first prompt-->
<!--      selectedPromptId.value = promptsList.value[0].id;-->
<!--      selectPrompt();-->
<!--    }-->
<!--  }-->
<!--});-->

<!--// Initialisierung und Laden des Prompt-Inhalts, wenn ein Prompt-ID existiert-->
<!--onMounted(() => {-->
<!--  console.log("Node mounted. Available prompts:", promptsList.value);-->
<!--  if (props.data.prompt_id) {-->
<!--    selectedPromptId.value = props.data.prompt_id;-->
<!--    const selectedPrompt = promptsList.value.find(p => p.id == props.data.prompt_id);-->
<!--    if (selectedPrompt) {-->
<!--      updateNodeData('content', selectedPrompt.content);-->
<!--    }-->
<!--  }-->
<!--});-->

<!--// Watch für Änderungen an der Prompt-Auswahl-->
<!--watch(selectedPromptId, (newValue) => {-->
<!--  if (newValue) {-->
<!--    selectPrompt();-->
<!--  }-->
<!--});-->

<!--// Watch für Änderungen an den verfügbaren Prompts (falls diese nachgeladen werden)-->
<!--watch(promptsList, (newPrompts) => {-->
<!--  console.log("Prompts list changed:", newPrompts);-->
<!--  if (selectedPromptId.value) {-->
<!--    const selectedPrompt = newPrompts.find(p => p.id == selectedPromptId.value);-->
<!--    if (selectedPrompt) {-->
<!--      updateNodeData('content', selectedPrompt.content);-->
<!--    }-->
<!--  }-->
<!--}, { deep: true });-->

<!--// Verhindert Klick-Propagation bei Interaktion mit Steuerelementen-->
<!--const preventDrag = (event) => {-->
<!--  event.stopPropagation();-->
<!--};-->
<!--</script>-->

<!--<template>-->
<!--  <div :class="['prompt-node', { 'selected': selected }]">-->
<!--    &lt;!&ndash; Source Handle (Output) &ndash;&gt;-->
<!--    <Handle-->
<!--      type="source"-->
<!--      position="bottom"-->
<!--      :id="id + '-source'"-->
<!--      :connectable="isConnectable"-->
<!--    />-->

<!--    &lt;!&ndash; Target Handle (Input) &ndash;&gt;-->
<!--    <Handle-->
<!--      type="target"-->
<!--      position="top"-->
<!--      :id="id + '-target'"-->
<!--      :connectable="isConnectable"-->
<!--    />-->

<!--    &lt;!&ndash; Node Content &ndash;&gt;-->
<!--    <div class="prompt-node-content">-->
<!--      <div class="prompt-node-header">-->
<!--        <i class="fa fa-file-alt text-primary me-1"></i>-->
<!--        <span class="prompt-node-label">{{ data.label }}</span>-->
<!--        <button-->
<!--          type="button"-->
<!--          class="prompt-node-toggle"-->
<!--          @click="toggleSettings"-->
<!--          :title="config.showSettings ? 'Hide settings' : 'Show settings'"-->
<!--        >-->
<!--          <i :class="['fa', config.showSettings ? 'fa-chevron-up' : 'fa-chevron-down']"></i>-->
<!--        </button>-->
<!--      </div>-->

<!--      &lt;!&ndash; Setting Panel &ndash;&gt;-->
<!--      <div v-if="config.showSettings" class="prompt-node-settings" @mousedown="preventDrag" @click="preventDrag">-->
<!--        &lt;!&ndash; Debug Info (nur während der Entwicklung) &ndash;&gt;-->
<!--        <div class="setting-group bg-light p-2 mb-2">-->
<!--          <small>Debug Info: {{ promptsList.length }} Prompts verfügbar</small>-->
<!--        </div>-->

<!--        &lt;!&ndash; Prompt Selection &ndash;&gt;-->
<!--        <div class="setting-group">-->
<!--          <label class="setting-label">Prompt Source</label>-->
<!--          <div class="setting-option">-->
<!--            <div class="form-check">-->
<!--              <input-->
<!--                type="radio"-->
<!--                id="prompt-source-saved"-->
<!--                class="form-check-input"-->
<!--                :value="true"-->
<!--                v-model="useSavedPrompt"-->
<!--              />-->
<!--              <label class="form-check-label" for="prompt-source-saved">-->
<!--                Gespeicherter Prompt-->
<!--              </label>-->
<!--            </div>-->

<!--            &lt;!&ndash; Dropdown für gespeicherte Prompts &ndash;&gt;-->
<!--            <div v-if="useSavedPrompt" class="mt-2">-->
<!--              <select-->
<!--                v-model="selectedPromptId"-->
<!--                class="form-select form-select-sm"-->
<!--              >-->
<!--                <option value="">Prompt auswählen</option>-->
<!--                <option v-for="prompt in promptsList" :key="prompt.id" :value="prompt.id">-->
<!--                  {{ prompt.name }}-->
<!--                </option>-->
<!--              </select>-->
<!--              <div v-if="promptsList.length === 0" class="text-muted small mt-1">-->
<!--                Keine gespeicherten Prompts verfügbar-->
<!--              </div>-->
<!--            </div>-->

<!--            &lt;!&ndash; Anzeige des ausgewählten Prompt-Inhalts &ndash;&gt;-->
<!--            <div v-if="useSavedPrompt && selectedPromptId" class="mt-2">-->
<!--              <div class="card p-2 bg-light">-->
<!--                <small class="text-muted">Prompt-Inhalt:</small>-->
<!--                <div class="prompt-preview mt-1">{{ selectedPromptContent }}</div>-->
<!--              </div>-->
<!--            </div>-->

<!--            <div class="form-check mt-2">-->
<!--              <input-->
<!--                type="radio"-->
<!--                id="prompt-source-custom"-->
<!--                class="form-check-input"-->
<!--                :value="false"-->
<!--                v-model="useSavedPrompt"-->
<!--              />-->
<!--              <label class="form-check-label" for="prompt-source-custom">-->
<!--                Benutzerdefinierter Prompt-->
<!--              </label>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->

<!--        &lt;!&ndash; Prompt Content (only visible if custom prompt is selected) &ndash;&gt;-->
<!--        <div v-if="!useSavedPrompt" class="setting-group">-->
<!--          <label class="setting-label" for="prompt-content">Prompt Inhalt</label>-->
<!--          <div class="setting-option">-->
<!--            <textarea-->
<!--              id="prompt-content"-->
<!--              class="form-control"-->
<!--              rows="4"-->
<!--              v-model="nodeData.content"-->
<!--              @input="updateNodeData('content', $event.target.value)"-->
<!--              placeholder="Gib deinen Prompt-Text ein..."-->
<!--            ></textarea>-->
<!--          </div>-->
<!--        </div>-->

<!--        &lt;!&ndash; LLM Provider Selection &ndash;&gt;-->
<!--        <div class="setting-group">-->
<!--          <label class="setting-label" for="prompt-provider">Provider</label>-->
<!--          <div class="setting-option">-->
<!--            <select-->
<!--              id="prompt-provider"-->
<!--              class="form-select form-select-sm"-->
<!--              v-model="nodeData.provider"-->
<!--              @change="onProviderChange($event.target.value)"-->
<!--            >-->
<!--              <option-->
<!--                v-for="provider in config.availableProviders"-->
<!--                :key="provider.value"-->
<!--                :value="provider.value"-->
<!--              >-->
<!--                {{ provider.label }}-->
<!--              </option>-->
<!--            </select>-->
<!--          </div>-->
<!--        </div>-->

<!--        &lt;!&ndash; Model Selection &ndash;&gt;-->
<!--        <div class="setting-group">-->
<!--          <label class="setting-label" for="prompt-model">Model</label>-->
<!--          <div class="setting-option">-->
<!--            <select-->
<!--              id="prompt-model"-->
<!--              class="form-select form-select-sm"-->
<!--              v-model="nodeData.model"-->
<!--              @change="updateNodeData('model', $event.target.value)"-->
<!--            >-->
<!--              <option-->
<!--                v-for="model in availableModels"-->
<!--                :key="model.value"-->
<!--                :value="model.value"-->
<!--              >-->
<!--                {{ model.label }}-->
<!--              </option>-->
<!--            </select>-->
<!--          </div>-->
<!--        </div>-->

<!--        &lt;!&ndash; Temperature Slider &ndash;&gt;-->
<!--        <div class="setting-group">-->
<!--          <label class="setting-label" for="prompt-temperature">-->
<!--            Temperature: {{ nodeData.temperature }}-->
<!--          </label>-->
<!--          <div class="setting-option">-->
<!--            <input-->
<!--              id="prompt-temperature"-->
<!--              type="range"-->
<!--              class="form-range"-->
<!--              min="0"-->
<!--              max="2"-->
<!--              step="0.1"-->
<!--              :value="nodeData.temperature"-->
<!--              @input="onTemperatureChange"-->
<!--              @change="onTemperatureChange"-->
<!--            />-->
<!--          </div>-->
<!--        </div>-->

<!--        &lt;!&ndash; Max Tokens &ndash;&gt;-->
<!--        <div class="setting-group">-->
<!--          <label class="setting-label" for="prompt-max-tokens">Max Tokens</label>-->
<!--          <div class="setting-option">-->
<!--            <input-->
<!--              id="prompt-max-tokens"-->
<!--              type="number"-->
<!--              class="form-control form-control-sm"-->
<!--              v-model="nodeData.max_tokens"-->
<!--              @change="updateNodeData('max_tokens', parseInt($event.target.value))"-->
<!--              min="1"-->
<!--              max="100000"-->
<!--            />-->
<!--          </div>-->
<!--        </div>-->

<!--        &lt;!&ndash; Output Variable &ndash;&gt;-->
<!--        <div class="setting-group">-->
<!--          <label class="setting-label" for="prompt-output-variable">Output Variable</label>-->
<!--          <div class="setting-option">-->
<!--            <input-->
<!--              id="prompt-output-variable"-->
<!--              type="text"-->
<!--              class="form-control form-control-sm"-->
<!--              v-model="nodeData.output_variable"-->
<!--              @change="updateNodeData('output_variable', $event.target.value)"-->
<!--              placeholder="result"-->
<!--            />-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->
<!--</template>-->

<!--<style scoped>-->
<!--.prompt-node {-->
<!--  padding: 10px;-->
<!--  border-radius: 5px;-->
<!--  background: white;-->
<!--  border: 1px solid #ddd;-->
<!--  width: 250px;-->
<!--  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);-->
<!--}-->

<!--.prompt-node.selected {-->
<!--  border-color: #5e72e4;-->
<!--  box-shadow: 0 0 0 2px rgba(94, 114, 228, 0.3);-->
<!--}-->

<!--.prompt-node-header {-->
<!--  display: flex;-->
<!--  align-items: center;-->
<!--  justify-content: space-between;-->
<!--  margin-bottom: 8px;-->
<!--  font-weight: 600;-->
<!--}-->

<!--.prompt-node-toggle {-->
<!--  background: none;-->
<!--  border: none;-->
<!--  cursor: pointer;-->
<!--  color: #495057;-->
<!--  padding: 2px 5px;-->
<!--}-->

<!--.prompt-node-toggle:hover {-->
<!--  color: #5e72e4;-->
<!--}-->

<!--.prompt-node-settings {-->
<!--  margin-top: 10px;-->
<!--  font-size: 0.85rem;-->
<!--}-->

<!--.setting-group {-->
<!--  margin-bottom: 12px;-->
<!--}-->

<!--.setting-label {-->
<!--  display: block;-->
<!--  margin-bottom: 4px;-->
<!--  color: #495057;-->
<!--  font-weight: 600;-->
<!--  font-size: 0.8rem;-->
<!--}-->

<!--.setting-option {-->
<!--  margin-bottom: 5px;-->
<!--}-->

<!--/* Stilisierung für die Prompt-Vorschau */-->
<!--.prompt-preview {-->
<!--  font-size: 0.8rem;-->
<!--  white-space: pre-wrap;-->
<!--  max-height: 100px;-->
<!--  overflow-y: auto;-->
<!--  background: white;-->
<!--  padding: 5px;-->
<!--  border-radius: 3px;-->
<!--  border: 1px solid #e2e2e2;-->
<!--}-->

<!--/* Make controls not draggable */-->
<!--.prompt-node input,-->
<!--.prompt-node select,-->
<!--.prompt-node textarea,-->
<!--.prompt-node button {-->
<!--  pointer-events: auto;-->
<!--}-->
<!--</style>-->

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Handle, Position } from '@vue-flow/core';

// Props receiving data from the workflow editor
const props = defineProps({
  id: {
    type: String,
    required: true
  },
  data: {
    type: Object,
    required: true
  },
  selected: {
    type: Boolean,
    default: false
  },
  isConnectable: {
    type: Boolean,
    default: true
  },
  prompts: {
    type: Array,
    default: () => []
  }
});

// Emit-Funktion für Datenaktualisierungen
const emit = defineEmits(['update:data']);

// Node-Konfiguration mit verfügbaren Providern und Modellen
const config = ref({
  showSettings: false,
  availableProviders: [
    { value: 'openai', label: 'OpenAI' },
    { value: 'anthropic', label: 'Anthropic' },
    { value: 'google', label: 'Google' },
    { value: 'ollama', label: 'Ollama (Local)' },
  ],
  availableModels: {
    openai: [
      { value: 'gpt-4-turbo', label: 'GPT-4 Turbo' },
      { value: 'gpt-4', label: 'GPT-4' },
      { value: 'gpt-3.5-turbo', label: 'GPT-3.5 Turbo' }
    ],
    anthropic: [
      { value: 'claude-3-opus', label: 'Claude 3 Opus' },
      { value: 'claude-3-sonnet', label: 'Claude 3 Sonnet' },
      { value: 'claude-3-haiku', label: 'Claude 3 Haiku' }
    ],
    google: [
      { value: 'gemini-pro', label: 'Gemini Pro' },
      { value: 'gemini-ultra', label: 'Gemini Ultra' }
    ],
    ollama: [
      { value: 'llama3', label: 'Llama 3' },
      { value: 'mistral', label: 'Mistral' },
      { value: 'mixtral', label: 'Mixtral' }
    ]
  }
});

// Initialisierung der Node-Daten mit Defaultwerten aus props
const nodeData = ref({
  prompt_id: props.data.prompt_id || null,
  content: props.data.content || '',
  provider: props.data.provider || 'openai',
  model: props.data.model || 'gpt-4-turbo',
  temperature: parseFloat(props.data.temperature || 0.7),
  max_tokens: parseInt(props.data.max_tokens || 1000),
  output_variable: props.data.output_variable || 'result'
});

// Prompt-Auswahl Status
const useSavedPrompt = ref(Boolean(props.data.prompt_id));
const selectedPromptId = ref(props.data.prompt_id || '');

// Verfügbare Modelle basierend auf dem ausgewählten Provider
const availableModels = computed(() => {
  return config.value.availableModels[nodeData.value.provider] || [];
});

// Prompt-Inhalt basierend auf der Auswahl
const selectedPromptContent = computed(() => {
  if (!selectedPromptId.value) return '';

  const selectedPrompt = props.prompts.find(p => p.id == selectedPromptId.value);
  return selectedPrompt ? selectedPrompt.content : '';
});

// Einstellungsmenü umschalten
const toggleSettings = (event) => {
  event.stopPropagation();
  config.value.showSettings = !config.value.showSettings;
};

// Node-Daten aktualisieren und an übergeordnete Komponente senden
const updateNodeData = (key, value) => {
  nodeData.value[key] = value;
  emit('update:data', { ...props.data, [key]: value });
};

// Provider wechseln und Modell aktualisieren
const onProviderChange = (provider) => {
  updateNodeData('provider', provider);

  const models = config.value.availableModels[provider] || [];
  if (models.length > 0) {
    updateNodeData('model', models[0].value);
  }
};

// Prompt auswählen und Inhalt aktualisieren
const selectPrompt = () => {
  updateNodeData('prompt_id', selectedPromptId.value);

  if (selectedPromptId.value) {
    const selectedPrompt = props.prompts.find(p => p.id == selectedPromptId.value);
    if (selectedPrompt) {
      updateNodeData('content', selectedPrompt.content);
    }
  }
};

// Temperatur-Änderung verarbeiten
const onTemperatureChange = (event) => {
  const value = parseFloat(event.target.value);
  updateNodeData('temperature', value);
};

// Überwachen des useSavedPrompt-Werts
watch(useSavedPrompt, (newValue) => {
  if (!newValue) {
    // Bei Wechsel zu benutzerdefiniertem Prompt, prompt_id löschen
    updateNodeData('prompt_id', null);
  } else if (props.prompts.length > 0) {
    // Bei Wechsel zu gespeichertem Prompt, ersten Prompt vorauswählen
    if (!selectedPromptId.value) {
      selectedPromptId.value = props.prompts[0].id;
      selectPrompt();
    }
  }
});

// Initialisierung und Laden des Prompt-Inhalts
onMounted(() => {
  if (props.data.prompt_id) {
    selectedPromptId.value = props.data.prompt_id;
    const selectedPrompt = props.prompts.find(p => p.id == props.data.prompt_id);
    if (selectedPrompt) {
      updateNodeData('content', selectedPrompt.content);
    }
  }
});

// Überwachen der Prompt-Auswahl
watch(selectedPromptId, (newValue) => {
  if (newValue) {
    selectPrompt();
  }
});

// Verhindern von Klick-Propagation
const preventDrag = (event) => {
  event.stopPropagation();
};
</script>

<template>
  <div :class="['prompt-node', { 'selected': selected }]">
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
        <span class="prompt-node-label">{{ data.label }}</span>
        <button
          type="button"
          class="prompt-node-toggle"
          @click="toggleSettings"
          :title="config.showSettings ? 'Einstellungen ausblenden' : 'Einstellungen anzeigen'"
        >
          <i :class="['fa', config.showSettings ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
        </button>
      </div>

      <!-- Einstellungsbereich -->
      <div v-if="config.showSettings" class="prompt-node-settings" @mousedown="preventDrag" @click="preventDrag">
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
              >
                <option value="">Prompt auswählen</option>
                <option v-for="prompt in prompts" :key="prompt.id" :value="prompt.id">
                  {{ prompt.name }}
                </option>
              </select>
              <div v-if="prompts.length === 0" class="text-muted small mt-1">
                Keine gespeicherten Prompts verfügbar
              </div>
            </div>

            <!-- Anzeige des ausgewählten Prompt-Inhalts -->
            <div v-if="useSavedPrompt && selectedPromptId" class="mt-2">
              <div class="card p-2 bg-light">
                <small class="text-muted">Prompt-Inhalt:</small>
                <div class="prompt-preview mt-1">{{ selectedPromptContent }}</div>
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
          <label class="setting-label" for="prompt-content">Prompt-Inhalt</label>
          <div class="setting-option">
            <textarea
              id="prompt-content"
              class="form-control"
              rows="4"
              v-model="nodeData.content"
              @input="updateNodeData('content', $event.target.value)"
              placeholder="Gib deinen Prompt-Text ein..."
            ></textarea>
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
          <label class="setting-label" for="prompt-max-tokens">Max Tokens</label>
          <div class="setting-option">
            <input
              id="prompt-max-tokens"
              type="number"
              class="form-control form-control-sm"
              v-model="nodeData.max_tokens"
              @change="updateNodeData('max_tokens', parseInt($event.target.value))"
              min="1"
              max="100000"
            />
          </div>
        </div>

        <!-- Ausgabe-Variable -->
        <div class="setting-group">
          <label class="setting-label" for="prompt-output-variable">Ausgabe-Variable</label>
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

/* Kontrollelemente nicht verschiebbar machen */
.prompt-node input,
.prompt-node select,
.prompt-node textarea,
.prompt-node button {
  pointer-events: auto;
}
</style>
