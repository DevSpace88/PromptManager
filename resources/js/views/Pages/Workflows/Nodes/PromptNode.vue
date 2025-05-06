<!--&lt;!&ndash;&lt;!&ndash;<script setup>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { ref, computed, onMounted, watch } from 'vue';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { Handle, Position } from '@vue-flow/core';&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Props receiving data from the workflow editor&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const props = defineProps({&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  id: {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    type: String,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    required: true&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  data: {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    type: Object,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    required: true&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  selected: {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    type: Boolean,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    default: false&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  isConnectable: {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    type: Boolean,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    default: true&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  prompts: {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    type: Array,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    default: () => []&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Emit-Funktion für Datenaktualisierungen&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const emit = defineEmits(['update:data']);&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Node-Konfiguration mit verfügbaren Providern und Modellen&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const config = ref({&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  showSettings: false,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  availableProviders: [&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    { value: 'openai', label: 'OpenAI' },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    { value: 'anthropic', label: 'Anthropic' },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    { value: 'google', label: 'Google' },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    { value: 'deepseek', label: 'DeepSeek' },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    { value: 'ollama', label: 'Ollama (Local)' },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  ],&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  availableModels: {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    openai: [&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      { value: 'gpt-4-turbo', label: 'GPT-4 Turbo' },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      { value: 'gpt-4', label: 'GPT-4' },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      { value: 'gpt-3.5-turbo', label: 'GPT-3.5 Turbo' }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    ],&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    anthropic: [&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      { value: 'claude-3-opus', label: 'Claude 3 Opus' },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      { value: 'claude-3-sonnet', label: 'Claude 3 Sonnet' },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      { value: 'claude-3-haiku', label: 'Claude 3 Haiku' }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    ],&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    google: [&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      { value: 'gemini-pro', label: 'Gemini Pro' },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      { value: 'gemini-ultra', label: 'Gemini Ultra' }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    ],&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    deepseek: [&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      { value: 'deepseek-coder', label: 'DeepSeek Coder' },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      { value: 'deepseek-llm', label: 'DeepSeek LLM' },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      { value: 'deepseek-chat', label: 'DeepSeek Chat' }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    ],&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    ollama: [&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      { value: 'llama3', label: 'Llama 3' },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      { value: 'mistral', label: 'Mistral' },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      { value: 'mixtral', label: 'Mixtral' }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    ]&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Initialisierung der Node-Daten mit Defaultwerten aus props&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const nodeData = ref({&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  prompt_id: props.data.prompt_id || null,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  content: props.data.content || '',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  provider: props.data.provider || 'openai',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  model: props.data.model || 'gpt-4-turbo',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  temperature: parseFloat(props.data.temperature || 0.7),&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  max_tokens: parseInt(props.data.max_tokens || 1000),&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  output_variable: props.data.output_variable || 'result'&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Prompt-Auswahl Status&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const useSavedPrompt = ref(Boolean(props.data.prompt_id));&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const selectedPromptId = ref(props.data.prompt_id || '');&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Verfügbare Modelle basierend auf dem ausgewählten Provider&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const availableModels = computed(() => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  return config.value.availableModels[nodeData.value.provider] || [];&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Prompt-Inhalt basierend auf der Auswahl&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const selectedPromptContent = computed(() => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  if (!selectedPromptId.value) return '';&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  const selectedPrompt = props.prompts.find(p => p.id == selectedPromptId.value);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  return selectedPrompt ? selectedPrompt.content : '';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Einstellungsmenü umschalten&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const toggleSettings = (event) => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  event.stopPropagation();&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  config.value.showSettings = !config.value.showSettings;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Node-Daten aktualisieren und an übergeordnete Komponente senden&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const updateNodeData = (key, value) => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  nodeData.value[key] = value;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  emit('update:data', { ...props.data, [key]: value });&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Provider wechseln und Modell aktualisieren&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const onProviderChange = (provider) => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  updateNodeData('provider', provider);&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  const models = config.value.availableModels[provider] || [];&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  if (models.length > 0) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    updateNodeData('model', models[0].value);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Prompt auswählen und Inhalt aktualisieren&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const selectPrompt = () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  updateNodeData('prompt_id', selectedPromptId.value);&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  if (selectedPromptId.value) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    const selectedPrompt = props.prompts.find(p => p.id == selectedPromptId.value);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    if (selectedPrompt) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      updateNodeData('content', selectedPrompt.content);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Temperatur-Änderung verarbeiten&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const onTemperatureChange = (event) => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  const value = parseFloat(event.target.value);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  updateNodeData('temperature', value);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Überwachen des useSavedPrompt-Werts&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;watch(useSavedPrompt, (newValue) => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  if (!newValue) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    // Bei Wechsel zu benutzerdefiniertem Prompt, prompt_id löschen&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    updateNodeData('prompt_id', null);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  } else if (props.prompts.length > 0) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    // Bei Wechsel zu gespeichertem Prompt, ersten Prompt vorauswählen&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    if (!selectedPromptId.value) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      selectedPromptId.value = props.prompts[0].id;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      selectPrompt();&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Initialisierung und Laden des Prompt-Inhalts&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;onMounted(() => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  if (props.data.prompt_id) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    selectedPromptId.value = props.data.prompt_id;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    const selectedPrompt = props.prompts.find(p => p.id == props.data.prompt_id);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    if (selectedPrompt) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      updateNodeData('content', selectedPrompt.content);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Überwachen der Prompt-Auswahl&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;watch(selectedPromptId, (newValue) => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  if (newValue) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    selectPrompt();&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Verhindern von Klick-Propagation&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const preventDrag = (event) => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  event.stopPropagation();&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;</script>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;<template>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  <div :class="['prompt-node', { 'selected': selected }]">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    &lt;!&ndash; Source Handle (Output) &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    <Handle&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      type="source"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      position="bottom"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      :id="id + '-source'"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      :connectable="isConnectable"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    />&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;    &lt;!&ndash; Target Handle (Input) &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    <Handle&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      type="target"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      position="top"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      :id="id + '-target'"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      :connectable="isConnectable"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    />&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;    &lt;!&ndash; Node-Inhalt &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    <div class="prompt-node-content">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      <div class="prompt-node-header">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <i class="fa fa-file-alt text-primary me-1"></i>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <span class="prompt-node-label">{{ data.label }}</span>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <button&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          type="button"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          class="prompt-node-toggle"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          @click="toggleSettings"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          :title="config.showSettings ? 'Einstellungen ausblenden' : 'Einstellungen anzeigen'"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <i :class="['fa', config.showSettings ? 'fa-chevron-up' : 'fa-chevron-down']"></i>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </button>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;      &lt;!&ndash; Einstellungsbereich &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      <div v-if="config.showSettings" class="prompt-node-settings" @mousedown="preventDrag" @click="preventDrag">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        &lt;!&ndash; Prompt-Auswahl &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div class="setting-group">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <label class="setting-label">Prompt-Quelle</label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="setting-option">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <div class="form-check">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <input&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                type="radio"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                id="prompt-source-saved"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                class="form-check-input"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                :value="true"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                v-model="useSavedPrompt"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              />&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <label class="form-check-label" for="prompt-source-saved">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                Gespeicherter Prompt&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              </label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;            &lt;!&ndash; Dropdown für gespeicherte Prompts &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <div v-if="useSavedPrompt" class="mt-2">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <select&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                v-model="selectedPromptId"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                class="form-select form-select-sm"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                @change="selectPrompt"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                <option value="">Prompt auswählen</option>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                <option v-for="prompt in props.data.prompts" :key="prompt.id" :value="prompt.id">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  {{ prompt.name }}&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                </option>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              </select>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;              &lt;!&ndash; Debugging &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <div v-if="props.data.prompts && props.data.prompts.length > 0">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                <small class="text-success">{{ props.data.prompts.length }} Prompts gefunden!</small>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <div v-else class="text-muted small mt-1">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                Keine gespeicherten Prompts verfügbar&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;            &lt;!&ndash; Anzeige des ausgewählten Prompt-Inhalts &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <div v-if="useSavedPrompt && selectedPromptId" class="mt-2">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <div class="card p-2 bg-light">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                <small class="text-muted">Prompt-Inhalt:</small>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                <div class="prompt-preview mt-1">{{ selectedPromptContent }}</div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;            <div class="form-check mt-2">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <input&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                type="radio"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                id="prompt-source-custom"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                class="form-check-input"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                :value="false"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                v-model="useSavedPrompt"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              />&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <label class="form-check-label" for="prompt-source-custom">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                Benutzerdefinierter Prompt&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              </label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;        &lt;!&ndash; Prompt-Inhalt (nur sichtbar bei benutzerdefiniertem Prompt) &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div v-if="!useSavedPrompt" class="setting-group">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <label class="setting-label" for="prompt-content">Prompt-Inhalt</label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="setting-option">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <textarea&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              id="prompt-content"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              class="form-control"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              rows="4"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              v-model="nodeData.content"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              @input="updateNodeData('content', $event.target.value)"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              placeholder="Gib deinen Prompt-Text ein..."&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            ></textarea>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;        &lt;!&ndash; LLM Provider-Auswahl &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div class="setting-group">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <label class="setting-label" for="prompt-provider">Provider</label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="setting-option">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <select&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              id="prompt-provider"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              class="form-select form-select-sm"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              v-model="nodeData.provider"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              @change="onProviderChange($event.target.value)"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <option&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                v-for="provider in config.availableProviders"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                :key="provider.value"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                :value="provider.value"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                {{ provider.label }}&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              </option>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            </select>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;        &lt;!&ndash; Modell-Auswahl &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div class="setting-group">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <label class="setting-label" for="prompt-model">Modell</label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="setting-option">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <select&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              id="prompt-model"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              class="form-select form-select-sm"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              v-model="nodeData.model"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              @change="updateNodeData('model', $event.target.value)"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <option&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                v-for="model in availableModels"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                :key="model.value"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                :value="model.value"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                {{ model.label }}&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              </option>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            </select>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;        &lt;!&ndash; Temperatur-Slider &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div class="setting-group">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <label class="setting-label" for="prompt-temperature">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            Temperatur: {{ nodeData.temperature }}&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          </label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="setting-option">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <input&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              id="prompt-temperature"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              type="range"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              class="form-range"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              min="0"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              max="2"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              step="0.1"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              :value="nodeData.temperature"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              @input="onTemperatureChange"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            />&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;        &lt;!&ndash; Max Tokens &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div class="setting-group">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <label class="setting-label" for="prompt-max-tokens">Max Tokens</label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="setting-option">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <input&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              id="prompt-max-tokens"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              type="number"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              class="form-control form-control-sm"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              v-model="nodeData.max_tokens"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              @change="updateNodeData('max_tokens', parseInt($event.target.value))"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              min="1"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              max="100000"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            />&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;        &lt;!&ndash; Ausgabe-Variable &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div class="setting-group">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <label class="setting-label" for="prompt-output-variable">Ausgabe-Variable</label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="setting-option">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <input&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              id="prompt-output-variable"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              type="text"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              class="form-control form-control-sm"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              v-model="nodeData.output_variable"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              @change="updateNodeData('output_variable', $event.target.value)"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              placeholder="result"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            />&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;</template>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;<style scoped>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;.prompt-node {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  padding: 10px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border-radius: 5px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  background: white;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border: 1px solid #ddd;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  width: 250px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.prompt-node.selected {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border-color: #5e72e4;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  box-shadow: 0 0 0 2px rgba(94, 114, 228, 0.3);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.prompt-node-header {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  display: flex;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  align-items: center;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  justify-content: space-between;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  margin-bottom: 8px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  font-weight: 600;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.prompt-node-toggle {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  background: none;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border: none;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  cursor: pointer;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  color: #495057;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  padding: 2px 5px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.prompt-node-toggle:hover {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  color: #5e72e4;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.prompt-node-settings {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  margin-top: 10px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  font-size: 0.85rem;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.setting-group {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  margin-bottom: 12px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.setting-label {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  display: block;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  margin-bottom: 4px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  color: #495057;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  font-weight: 600;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  font-size: 0.8rem;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.setting-option {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  margin-bottom: 5px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;/* Stilisierung für die Prompt-Vorschau */&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;.prompt-preview {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  font-size: 0.8rem;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  white-space: pre-wrap;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  max-height: 100px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  overflow-y: auto;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  background: white;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  padding: 5px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border-radius: 3px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border: 1px solid #e2e2e2;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;/* Kontrollelemente nicht verschiebbar machen */&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;.prompt-node input,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;.prompt-node select,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;.prompt-node textarea,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;.prompt-node button {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  pointer-events: auto;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;</style>&ndash;&gt;&ndash;&gt;-->


<!--&lt;!&ndash;<script setup>&ndash;&gt;-->
<!--&lt;!&ndash;import { ref, computed, onMounted, watch } from 'vue';&ndash;&gt;-->
<!--&lt;!&ndash;import { Handle, Position } from '@vue-flow/core';&ndash;&gt;-->

<!--&lt;!&ndash;// Props receiving data from the workflow editor&ndash;&gt;-->
<!--&lt;!&ndash;const props = defineProps({&ndash;&gt;-->
<!--&lt;!&ndash;  id: {&ndash;&gt;-->
<!--&lt;!&ndash;    type: String,&ndash;&gt;-->
<!--&lt;!&ndash;    required: true&ndash;&gt;-->
<!--&lt;!&ndash;  },&ndash;&gt;-->
<!--&lt;!&ndash;  data: {&ndash;&gt;-->
<!--&lt;!&ndash;    type: Object,&ndash;&gt;-->
<!--&lt;!&ndash;    required: true&ndash;&gt;-->
<!--&lt;!&ndash;  },&ndash;&gt;-->
<!--&lt;!&ndash;  selected: {&ndash;&gt;-->
<!--&lt;!&ndash;    type: Boolean,&ndash;&gt;-->
<!--&lt;!&ndash;    default: false&ndash;&gt;-->
<!--&lt;!&ndash;  },&ndash;&gt;-->
<!--&lt;!&ndash;  isConnectable: {&ndash;&gt;-->
<!--&lt;!&ndash;    type: Boolean,&ndash;&gt;-->
<!--&lt;!&ndash;    default: true&ndash;&gt;-->
<!--&lt;!&ndash;  },&ndash;&gt;-->
<!--&lt;!&ndash;  prompts: {&ndash;&gt;-->
<!--&lt;!&ndash;    type: Array,&ndash;&gt;-->
<!--&lt;!&ndash;    default: () => []&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Emit-Funktion für Datenaktualisierungen&ndash;&gt;-->
<!--&lt;!&ndash;const emit = defineEmits(['update:data']);&ndash;&gt;-->

<!--&lt;!&ndash;// Node-Konfiguration mit verfügbaren Providern und Modellen&ndash;&gt;-->
<!--&lt;!&ndash;const config = ref({&ndash;&gt;-->
<!--&lt;!&ndash;  showSettings: false,&ndash;&gt;-->
<!--&lt;!&ndash;  availableProviders: [&ndash;&gt;-->
<!--&lt;!&ndash;    { value: 'openai', label: 'OpenAI' },&ndash;&gt;-->
<!--&lt;!&ndash;    { value: 'anthropic', label: 'Anthropic' },&ndash;&gt;-->
<!--&lt;!&ndash;    { value: 'google', label: 'Google' },&ndash;&gt;-->
<!--&lt;!&ndash;    { value: 'deepseek', label: 'DeepSeek' },&ndash;&gt;-->
<!--&lt;!&ndash;    { value: 'ollama', label: 'Ollama (Local)' },&ndash;&gt;-->
<!--&lt;!&ndash;  ],&ndash;&gt;-->
<!--&lt;!&ndash;  availableModels: {&ndash;&gt;-->
<!--&lt;!&ndash;    openai: [&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'gpt-4-turbo', label: 'GPT-4 Turbo' },&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'gpt-4', label: 'GPT-4' },&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'gpt-3.5-turbo', label: 'GPT-3.5 Turbo' }&ndash;&gt;-->
<!--&lt;!&ndash;    ],&ndash;&gt;-->
<!--&lt;!&ndash;    anthropic: [&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'claude-3-opus', label: 'Claude 3 Opus' },&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'claude-3-sonnet', label: 'Claude 3 Sonnet' },&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'claude-3-haiku', label: 'Claude 3 Haiku' }&ndash;&gt;-->
<!--&lt;!&ndash;    ],&ndash;&gt;-->
<!--&lt;!&ndash;    google: [&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'gemini-pro', label: 'Gemini Pro' },&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'gemini-ultra', label: 'Gemini Ultra' }&ndash;&gt;-->
<!--&lt;!&ndash;    ],&ndash;&gt;-->
<!--&lt;!&ndash;    deepseek: [&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'deepseek-coder', label: 'DeepSeek Coder' },&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'deepseek-llm', label: 'DeepSeek LLM' },&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'deepseek-chat', label: 'DeepSeek Chat' }&ndash;&gt;-->
<!--&lt;!&ndash;    ],&ndash;&gt;-->
<!--&lt;!&ndash;    ollama: [&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'llama3', label: 'Llama 3' },&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'mistral', label: 'Mistral' },&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'mixtral', label: 'Mixtral' }&ndash;&gt;-->
<!--&lt;!&ndash;    ]&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Initialisierung der Node-Daten mit Defaultwerten aus props&ndash;&gt;-->
<!--&lt;!&ndash;const nodeData = ref({&ndash;&gt;-->
<!--&lt;!&ndash;  prompt_id: props.data.prompt_id || null,&ndash;&gt;-->
<!--&lt;!&ndash;  content: props.data.content || '',&ndash;&gt;-->
<!--&lt;!&ndash;  provider: props.data.provider || 'openai',&ndash;&gt;-->
<!--&lt;!&ndash;  model: props.data.model || 'gpt-4-turbo',&ndash;&gt;-->
<!--&lt;!&ndash;  temperature: parseFloat(props.data.temperature || 0.7),&ndash;&gt;-->
<!--&lt;!&ndash;  max_tokens: parseInt(props.data.max_tokens || 1000),&ndash;&gt;-->
<!--&lt;!&ndash;  output_variable: props.data.output_variable || 'result',&ndash;&gt;-->
<!--&lt;!&ndash;  variables: props.data.variables || {}&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Prompt-Auswahl Status&ndash;&gt;-->
<!--&lt;!&ndash;const useSavedPrompt = ref(Boolean(props.data.prompt_id));&ndash;&gt;-->
<!--&lt;!&ndash;const selectedPromptId = ref(props.data.prompt_id || '');&ndash;&gt;-->

<!--&lt;!&ndash;// Verfügbare Modelle basierend auf dem ausgewählten Provider&ndash;&gt;-->
<!--&lt;!&ndash;const availableModels = computed(() => {&ndash;&gt;-->
<!--&lt;!&ndash;  return config.value.availableModels[nodeData.value.provider] || [];&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Prompt-Inhalt basierend auf der Auswahl&ndash;&gt;-->
<!--&lt;!&ndash;const selectedPromptContent = computed(() => {&ndash;&gt;-->
<!--&lt;!&ndash;  if (!selectedPromptId.value) return '';&ndash;&gt;-->

<!--&lt;!&ndash;  const selectedPrompt = props.prompts.find(p => p.id == selectedPromptId.value);&ndash;&gt;-->
<!--&lt;!&ndash;  return selectedPrompt ? selectedPrompt.content : '';&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Finde alle Variablen im Prompt-Text ({{variablename}}-Format)&ndash;&gt;-->
<!--&lt;!&ndash;const extractedVariables = computed(() => {&ndash;&gt;-->
<!--&lt;!&ndash;  const content = nodeData.value.content || '';&ndash;&gt;-->
<!--&lt;!&ndash;  const regex = /\{\{([^{}]+)\}\}/g;&ndash;&gt;-->
<!--&lt;!&ndash;  const variables = [];&ndash;&gt;-->
<!--&lt;!&ndash;  let match;&ndash;&gt;-->

<!--&lt;!&ndash;  while ((match = regex.exec(content)) !== null) {&ndash;&gt;-->
<!--&lt;!&ndash;    const varName = match[1].trim();&ndash;&gt;-->
<!--&lt;!&ndash;    if (!variables.includes(varName)) {&ndash;&gt;-->
<!--&lt;!&ndash;      variables.push(varName);&ndash;&gt;-->
<!--&lt;!&ndash;    }&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->

<!--&lt;!&ndash;  return variables;&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Einstellungsmenü umschalten&ndash;&gt;-->
<!--&lt;!&ndash;const toggleSettings = (event) => {&ndash;&gt;-->
<!--&lt;!&ndash;  event.stopPropagation();&ndash;&gt;-->
<!--&lt;!&ndash;  config.value.showSettings = !config.value.showSettings;&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Node-Daten aktualisieren und an übergeordnete Komponente senden&ndash;&gt;-->
<!--&lt;!&ndash;const updateNodeData = (key, value) => {&ndash;&gt;-->
<!--&lt;!&ndash;  nodeData.value[key] = value;&ndash;&gt;-->
<!--&lt;!&ndash;  emit('update:data', { ...props.data, [key]: value });&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Provider wechseln und Modell aktualisieren&ndash;&gt;-->
<!--&lt;!&ndash;const onProviderChange = (provider) => {&ndash;&gt;-->
<!--&lt;!&ndash;  updateNodeData('provider', provider);&ndash;&gt;-->

<!--&lt;!&ndash;  const models = config.value.availableModels[provider] || [];&ndash;&gt;-->
<!--&lt;!&ndash;  if (models.length > 0) {&ndash;&gt;-->
<!--&lt;!&ndash;    updateNodeData('model', models[0].value);&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Prompt auswählen und Inhalt aktualisieren&ndash;&gt;-->
<!--&lt;!&ndash;const selectPrompt = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  updateNodeData('prompt_id', selectedPromptId.value);&ndash;&gt;-->

<!--&lt;!&ndash;  if (selectedPromptId.value) {&ndash;&gt;-->
<!--&lt;!&ndash;    const selectedPrompt = props.prompts.find(p => p.id == selectedPromptId.value);&ndash;&gt;-->
<!--&lt;!&ndash;    if (selectedPrompt) {&ndash;&gt;-->
<!--&lt;!&ndash;      updateNodeData('content', selectedPrompt.content);&ndash;&gt;-->
<!--&lt;!&ndash;    }&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Temperatur-Änderung verarbeiten&ndash;&gt;-->
<!--&lt;!&ndash;const onTemperatureChange = (event) => {&ndash;&gt;-->
<!--&lt;!&ndash;  const value = parseFloat(event.target.value);&ndash;&gt;-->
<!--&lt;!&ndash;  updateNodeData('temperature', value);&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Variable-Wert aktualisieren&ndash;&gt;-->
<!--&lt;!&ndash;const updateVariableValue = (varName, value) => {&ndash;&gt;-->
<!--&lt;!&ndash;  const updatedVariables = { ...nodeData.value.variables, [varName]: value };&ndash;&gt;-->
<!--&lt;!&ndash;  updateNodeData('variables', updatedVariables);&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Überwachen des useSavedPrompt-Werts&ndash;&gt;-->
<!--&lt;!&ndash;watch(useSavedPrompt, (newValue) => {&ndash;&gt;-->
<!--&lt;!&ndash;  if (!newValue) {&ndash;&gt;-->
<!--&lt;!&ndash;    // Bei Wechsel zu benutzerdefiniertem Prompt, prompt_id löschen&ndash;&gt;-->
<!--&lt;!&ndash;    updateNodeData('prompt_id', null);&ndash;&gt;-->
<!--&lt;!&ndash;  } else if (props.prompts.length > 0) {&ndash;&gt;-->
<!--&lt;!&ndash;    // Bei Wechsel zu gespeichertem Prompt, ersten Prompt vorauswählen&ndash;&gt;-->
<!--&lt;!&ndash;    if (!selectedPromptId.value) {&ndash;&gt;-->
<!--&lt;!&ndash;      selectedPromptId.value = props.prompts[0].id;&ndash;&gt;-->
<!--&lt;!&ndash;      selectPrompt();&ndash;&gt;-->
<!--&lt;!&ndash;    }&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Initialisierung und Laden des Prompt-Inhalts&ndash;&gt;-->
<!--&lt;!&ndash;onMounted(() => {&ndash;&gt;-->
<!--&lt;!&ndash;  if (props.data.prompt_id) {&ndash;&gt;-->
<!--&lt;!&ndash;    selectedPromptId.value = props.data.prompt_id;&ndash;&gt;-->
<!--&lt;!&ndash;    const selectedPrompt = props.prompts.find(p => p.id == props.data.prompt_id);&ndash;&gt;-->
<!--&lt;!&ndash;    if (selectedPrompt) {&ndash;&gt;-->
<!--&lt;!&ndash;      updateNodeData('content', selectedPrompt.content);&ndash;&gt;-->
<!--&lt;!&ndash;    }&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->

<!--&lt;!&ndash;  // Initialisiere Variables-Objekt, falls nicht vorhanden&ndash;&gt;-->
<!--&lt;!&ndash;  if (!nodeData.value.variables) {&ndash;&gt;-->
<!--&lt;!&ndash;    updateNodeData('variables', {});&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Überwachen der Prompt-Auswahl&ndash;&gt;-->
<!--&lt;!&ndash;watch(selectedPromptId, (newValue) => {&ndash;&gt;-->
<!--&lt;!&ndash;  if (newValue) {&ndash;&gt;-->
<!--&lt;!&ndash;    selectPrompt();&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Überwachen von Änderungen am Prompt-Inhalt, um neue Variablen zu erkennen&ndash;&gt;-->
<!--&lt;!&ndash;watch(() => nodeData.value.content, (newContent) => {&ndash;&gt;-->
<!--&lt;!&ndash;  if (newContent) {&ndash;&gt;-->
<!--&lt;!&ndash;    // Aktualisiere Variables-Objekt mit neuen Variablen aus dem Content&ndash;&gt;-->
<!--&lt;!&ndash;    const currentVars = nodeData.value.variables || {};&ndash;&gt;-->
<!--&lt;!&ndash;    const updatedVars = { ...currentVars };&ndash;&gt;-->

<!--&lt;!&ndash;    extractedVariables.value.forEach(varName => {&ndash;&gt;-->
<!--&lt;!&ndash;      if (updatedVars[varName] === undefined) {&ndash;&gt;-->
<!--&lt;!&ndash;        updatedVars[varName] = '';&ndash;&gt;-->
<!--&lt;!&ndash;      }&ndash;&gt;-->
<!--&lt;!&ndash;    });&ndash;&gt;-->

<!--&lt;!&ndash;    updateNodeData('variables', updatedVars);&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Verhindern von Klick-Propagation&ndash;&gt;-->
<!--&lt;!&ndash;const preventDrag = (event) => {&ndash;&gt;-->
<!--&lt;!&ndash;  event.stopPropagation();&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->
<!--&lt;!&ndash;</script>&ndash;&gt;-->

<!--&lt;!&ndash;<template>&ndash;&gt;-->
<!--&lt;!&ndash;  <div :class="['prompt-node', { 'selected': selected }]">&ndash;&gt;-->
<!--&lt;!&ndash;    &lt;!&ndash; Source Handle (Output) &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;    <Handle&ndash;&gt;-->
<!--&lt;!&ndash;      type="source"&ndash;&gt;-->
<!--&lt;!&ndash;      position="bottom"&ndash;&gt;-->
<!--&lt;!&ndash;      :id="id + '-source'"&ndash;&gt;-->
<!--&lt;!&ndash;      :connectable="isConnectable"&ndash;&gt;-->
<!--&lt;!&ndash;    />&ndash;&gt;-->

<!--&lt;!&ndash;    &lt;!&ndash; Target Handle (Input) &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;    <Handle&ndash;&gt;-->
<!--&lt;!&ndash;      type="target"&ndash;&gt;-->
<!--&lt;!&ndash;      position="top"&ndash;&gt;-->
<!--&lt;!&ndash;      :id="id + '-target'"&ndash;&gt;-->
<!--&lt;!&ndash;      :connectable="isConnectable"&ndash;&gt;-->
<!--&lt;!&ndash;    />&ndash;&gt;-->

<!--&lt;!&ndash;    &lt;!&ndash; Node-Inhalt &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;    <div class="prompt-node-content">&ndash;&gt;-->
<!--&lt;!&ndash;      <div class="prompt-node-header">&ndash;&gt;-->
<!--&lt;!&ndash;        <i class="fa fa-file-alt text-primary me-1"></i>&ndash;&gt;-->
<!--&lt;!&ndash;        <span class="prompt-node-label">{{ data.label || 'Prompt' }}</span>&ndash;&gt;-->
<!--&lt;!&ndash;        <button&ndash;&gt;-->
<!--&lt;!&ndash;          type="button"&ndash;&gt;-->
<!--&lt;!&ndash;          class="prompt-node-toggle"&ndash;&gt;-->
<!--&lt;!&ndash;          @click="toggleSettings"&ndash;&gt;-->
<!--&lt;!&ndash;          :title="config.showSettings ? 'Einstellungen ausblenden' : 'Einstellungen anzeigen'"&ndash;&gt;-->
<!--&lt;!&ndash;        >&ndash;&gt;-->
<!--&lt;!&ndash;          <i :class="['fa', config.showSettings ? 'fa-chevron-up' : 'fa-chevron-down']"></i>&ndash;&gt;-->
<!--&lt;!&ndash;        </button>&ndash;&gt;-->
<!--&lt;!&ndash;      </div>&ndash;&gt;-->

<!--&lt;!&ndash;      &lt;!&ndash; Prompt-Inhalt Vorschau (immer sichtbar) &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;      <div class="prompt-content-preview">&ndash;&gt;-->
<!--&lt;!&ndash;        <div v-if="nodeData.content" class="prompt-text">&ndash;&gt;-->
<!--&lt;!&ndash;          {{ nodeData.content }}&ndash;&gt;-->
<!--&lt;!&ndash;        </div>&ndash;&gt;-->
<!--&lt;!&ndash;        <div v-else class="prompt-placeholder">&ndash;&gt;-->
<!--&lt;!&ndash;          Kein Prompt-Inhalt definiert...&ndash;&gt;-->
<!--&lt;!&ndash;        </div>&ndash;&gt;-->
<!--&lt;!&ndash;      </div>&ndash;&gt;-->

<!--&lt;!&ndash;      &lt;!&ndash; Einstellungsbereich &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;      <div v-if="config.showSettings" class="prompt-node-settings" @mousedown="preventDrag" @click="preventDrag">&ndash;&gt;-->
<!--&lt;!&ndash;        &lt;!&ndash; Prompt-Auswahl &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label">Prompt-Quelle</label>&ndash;&gt;-->
<!--&lt;!&ndash;          <div class="setting-option">&ndash;&gt;-->
<!--&lt;!&ndash;            <div class="form-check">&ndash;&gt;-->
<!--&lt;!&ndash;              <input&ndash;&gt;-->
<!--&lt;!&ndash;                type="radio"&ndash;&gt;-->
<!--&lt;!&ndash;                id="prompt-source-saved"&ndash;&gt;-->
<!--&lt;!&ndash;                class="form-check-input"&ndash;&gt;-->
<!--&lt;!&ndash;                :value="true"&ndash;&gt;-->
<!--&lt;!&ndash;                v-model="useSavedPrompt"&ndash;&gt;-->
<!--&lt;!&ndash;              />&ndash;&gt;-->
<!--&lt;!&ndash;              <label class="form-check-label" for="prompt-source-saved">&ndash;&gt;-->
<!--&lt;!&ndash;                Gespeicherter Prompt&ndash;&gt;-->
<!--&lt;!&ndash;              </label>&ndash;&gt;-->
<!--&lt;!&ndash;            </div>&ndash;&gt;-->

<!--&lt;!&ndash;            &lt;!&ndash; Dropdown für gespeicherte Prompts &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;            <div v-if="useSavedPrompt" class="mt-2">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                            <select&ndash;&gt;-->
<!--&lt;!&ndash;                              v-model="selectedPromptId"&ndash;&gt;-->
<!--&lt;!&ndash;                              class="form-select form-select-sm"&ndash;&gt;-->
<!--&lt;!&ndash;                              @change="selectPrompt"&ndash;&gt;-->
<!--&lt;!&ndash;                            >&ndash;&gt;-->
<!--&lt;!&ndash;                              <option value="">Prompt auswählen</option>&ndash;&gt;-->
<!--&lt;!&ndash;                              <option v-for="prompt in props.data.prompts" :key="prompt.id" :value="prompt.id">&ndash;&gt;-->
<!--&lt;!&ndash;                                {{ prompt.name }}&ndash;&gt;-->
<!--&lt;!&ndash;                              </option>&ndash;&gt;-->
<!--&lt;!&ndash;                            </select>&ndash;&gt;-->

<!--&lt;!&ndash;                            &lt;!&ndash; Debugging &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                            <div v-if="props.data.prompts && props.data.prompts.length > 0">&ndash;&gt;-->
<!--&lt;!&ndash;                              <small class="text-success">{{ props.data.prompts.length }} Prompts gefunden!</small>&ndash;&gt;-->
<!--&lt;!&ndash;                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                            <div v-else class="text-muted small mt-1">&ndash;&gt;-->
<!--&lt;!&ndash;                              Keine gespeicherten Prompts verfügbar&ndash;&gt;-->
<!--&lt;!&ndash;                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                          </div>&ndash;&gt;-->

<!--&lt;!&ndash;            <div class="form-check mt-2">&ndash;&gt;-->
<!--&lt;!&ndash;              <input&ndash;&gt;-->
<!--&lt;!&ndash;                type="radio"&ndash;&gt;-->
<!--&lt;!&ndash;                id="prompt-source-custom"&ndash;&gt;-->
<!--&lt;!&ndash;                class="form-check-input"&ndash;&gt;-->
<!--&lt;!&ndash;                :value="false"&ndash;&gt;-->
<!--&lt;!&ndash;                v-model="useSavedPrompt"&ndash;&gt;-->
<!--&lt;!&ndash;              />&ndash;&gt;-->
<!--&lt;!&ndash;              <label class="form-check-label" for="prompt-source-custom">&ndash;&gt;-->
<!--&lt;!&ndash;                Benutzerdefinierter Prompt&ndash;&gt;-->
<!--&lt;!&ndash;              </label>&ndash;&gt;-->
<!--&lt;!&ndash;            </div>&ndash;&gt;-->
<!--&lt;!&ndash;          </div>&ndash;&gt;-->
<!--&lt;!&ndash;        </div>&ndash;&gt;-->

<!--&lt;!&ndash;        &lt;!&ndash; Prompt-Inhalt (nur sichtbar bei benutzerdefiniertem Prompt) &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div v-if="!useSavedPrompt" class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label" for="prompt-content">Prompt-Inhalt</label>&ndash;&gt;-->
<!--&lt;!&ndash;          <div class="setting-option">&ndash;&gt;-->
<!--&lt;!&ndash;            <textarea&ndash;&gt;-->
<!--&lt;!&ndash;              id="prompt-content"&ndash;&gt;-->
<!--&lt;!&ndash;              class="form-control"&ndash;&gt;-->
<!--&lt;!&ndash;              rows="4"&ndash;&gt;-->
<!--&lt;!&ndash;              v-model="nodeData.content"&ndash;&gt;-->
<!--&lt;!&ndash;              @input="updateNodeData('content', $event.target.value)"&ndash;&gt;-->
<!--&lt;!&ndash;              placeholder="Gib deinen Prompt-Text ein..."&ndash;&gt;-->
<!--&lt;!&ndash;            ></textarea>&ndash;&gt;-->
<!--&lt;!&ndash;          </div>&ndash;&gt;-->
<!--&lt;!&ndash;        </div>&ndash;&gt;-->

<!--&lt;!&ndash;        &lt;!&ndash; Prompt-Variablen Eingabefelder &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div v-if="extractedVariables.length > 0" class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label">Prompt-Variablen</label>&ndash;&gt;-->
<!--&lt;!&ndash;          <div class="setting-option">&ndash;&gt;-->
<!--&lt;!&ndash;            <div class="variable-info mb-2">&ndash;&gt;-->
<!--&lt;!&ndash;              <small class="text-muted">Im Prompt gefundene Variablen ({{variablename}}):</small>&ndash;&gt;-->
<!--&lt;!&ndash;            </div>&ndash;&gt;-->
<!--&lt;!&ndash;            <div&ndash;&gt;-->
<!--&lt;!&ndash;              v-for="varName in extractedVariables"&ndash;&gt;-->
<!--&lt;!&ndash;              :key="varName"&ndash;&gt;-->
<!--&lt;!&ndash;              class="variable-input mb-2"&ndash;&gt;-->
<!--&lt;!&ndash;            >&ndash;&gt;-->
<!--&lt;!&ndash;              <label :for="`var-${varName}`" class="form-label mb-1">{{ varName }}</label>&ndash;&gt;-->
<!--&lt;!&ndash;              <input&ndash;&gt;-->
<!--&lt;!&ndash;                :id="`var-${varName}`"&ndash;&gt;-->
<!--&lt;!&ndash;                type="text"&ndash;&gt;-->
<!--&lt;!&ndash;                class="form-control form-control-sm"&ndash;&gt;-->
<!--&lt;!&ndash;                :value="nodeData.variables[varName] || ''"&ndash;&gt;-->
<!--&lt;!&ndash;                @input="updateVariableValue(varName, $event.target.value)"&ndash;&gt;-->
<!--&lt;!&ndash;                :placeholder="`Wert für {{${varName}}}`"&ndash;&gt;-->
<!--&lt;!&ndash;              />&ndash;&gt;-->
<!--&lt;!&ndash;            </div>&ndash;&gt;-->
<!--&lt;!&ndash;          </div>&ndash;&gt;-->
<!--&lt;!&ndash;        </div>&ndash;&gt;-->

<!--&lt;!&ndash;        &lt;!&ndash; LLM Provider-Auswahl &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label" for="prompt-provider">Provider</label>&ndash;&gt;-->
<!--&lt;!&ndash;          <div class="setting-option">&ndash;&gt;-->
<!--&lt;!&ndash;            <select&ndash;&gt;-->
<!--&lt;!&ndash;              id="prompt-provider"&ndash;&gt;-->
<!--&lt;!&ndash;              class="form-select form-select-sm"&ndash;&gt;-->
<!--&lt;!&ndash;              v-model="nodeData.provider"&ndash;&gt;-->
<!--&lt;!&ndash;              @change="onProviderChange($event.target.value)"&ndash;&gt;-->
<!--&lt;!&ndash;            >&ndash;&gt;-->
<!--&lt;!&ndash;              <option&ndash;&gt;-->
<!--&lt;!&ndash;                v-for="provider in config.availableProviders"&ndash;&gt;-->
<!--&lt;!&ndash;                :key="provider.value"&ndash;&gt;-->
<!--&lt;!&ndash;                :value="provider.value"&ndash;&gt;-->
<!--&lt;!&ndash;              >&ndash;&gt;-->
<!--&lt;!&ndash;                {{ provider.label }}&ndash;&gt;-->
<!--&lt;!&ndash;              </option>&ndash;&gt;-->
<!--&lt;!&ndash;            </select>&ndash;&gt;-->
<!--&lt;!&ndash;          </div>&ndash;&gt;-->
<!--&lt;!&ndash;        </div>&ndash;&gt;-->

<!--&lt;!&ndash;        &lt;!&ndash; Modell-Auswahl &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label" for="prompt-model">Modell</label>&ndash;&gt;-->
<!--&lt;!&ndash;          <div class="setting-option">&ndash;&gt;-->
<!--&lt;!&ndash;            <select&ndash;&gt;-->
<!--&lt;!&ndash;              id="prompt-model"&ndash;&gt;-->
<!--&lt;!&ndash;              class="form-select form-select-sm"&ndash;&gt;-->
<!--&lt;!&ndash;              v-model="nodeData.model"&ndash;&gt;-->
<!--&lt;!&ndash;              @change="updateNodeData('model', $event.target.value)"&ndash;&gt;-->
<!--&lt;!&ndash;            >&ndash;&gt;-->
<!--&lt;!&ndash;              <option&ndash;&gt;-->
<!--&lt;!&ndash;                v-for="model in availableModels"&ndash;&gt;-->
<!--&lt;!&ndash;                :key="model.value"&ndash;&gt;-->
<!--&lt;!&ndash;                :value="model.value"&ndash;&gt;-->
<!--&lt;!&ndash;              >&ndash;&gt;-->
<!--&lt;!&ndash;                {{ model.label }}&ndash;&gt;-->
<!--&lt;!&ndash;              </option>&ndash;&gt;-->
<!--&lt;!&ndash;            </select>&ndash;&gt;-->
<!--&lt;!&ndash;          </div>&ndash;&gt;-->
<!--&lt;!&ndash;        </div>&ndash;&gt;-->

<!--&lt;!&ndash;        &lt;!&ndash; Temperatur-Slider &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label" for="prompt-temperature">&ndash;&gt;-->
<!--&lt;!&ndash;            Temperatur: {{ nodeData.temperature }}&ndash;&gt;-->
<!--&lt;!&ndash;          </label>&ndash;&gt;-->
<!--&lt;!&ndash;          <div class="setting-option">&ndash;&gt;-->
<!--&lt;!&ndash;            <input&ndash;&gt;-->
<!--&lt;!&ndash;              id="prompt-temperature"&ndash;&gt;-->
<!--&lt;!&ndash;              type="range"&ndash;&gt;-->
<!--&lt;!&ndash;              class="form-range"&ndash;&gt;-->
<!--&lt;!&ndash;              min="0"&ndash;&gt;-->
<!--&lt;!&ndash;              max="2"&ndash;&gt;-->
<!--&lt;!&ndash;              step="0.1"&ndash;&gt;-->
<!--&lt;!&ndash;              :value="nodeData.temperature"&ndash;&gt;-->
<!--&lt;!&ndash;              @input="onTemperatureChange"&ndash;&gt;-->
<!--&lt;!&ndash;            />&ndash;&gt;-->
<!--&lt;!&ndash;          </div>&ndash;&gt;-->
<!--&lt;!&ndash;        </div>&ndash;&gt;-->

<!--&lt;!&ndash;        &lt;!&ndash; Max Tokens &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label" for="prompt-max-tokens">Max Tokens</label>&ndash;&gt;-->
<!--&lt;!&ndash;          <div class="setting-option">&ndash;&gt;-->
<!--&lt;!&ndash;            <input&ndash;&gt;-->
<!--&lt;!&ndash;              id="prompt-max-tokens"&ndash;&gt;-->
<!--&lt;!&ndash;              type="number"&ndash;&gt;-->
<!--&lt;!&ndash;              class="form-control form-control-sm"&ndash;&gt;-->
<!--&lt;!&ndash;              v-model="nodeData.max_tokens"&ndash;&gt;-->
<!--&lt;!&ndash;              @change="updateNodeData('max_tokens', parseInt($event.target.value))"&ndash;&gt;-->
<!--&lt;!&ndash;              min="1"&ndash;&gt;-->
<!--&lt;!&ndash;              max="100000"&ndash;&gt;-->
<!--&lt;!&ndash;            />&ndash;&gt;-->
<!--&lt;!&ndash;          </div>&ndash;&gt;-->
<!--&lt;!&ndash;        </div>&ndash;&gt;-->

<!--&lt;!&ndash;        &lt;!&ndash; Ausgabe-Variable &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label" for="prompt-output-variable">Ausgabe-Variable</label>&ndash;&gt;-->
<!--&lt;!&ndash;          <div class="setting-option">&ndash;&gt;-->
<!--&lt;!&ndash;            <input&ndash;&gt;-->
<!--&lt;!&ndash;              id="prompt-output-variable"&ndash;&gt;-->
<!--&lt;!&ndash;              type="text"&ndash;&gt;-->
<!--&lt;!&ndash;              class="form-control form-control-sm"&ndash;&gt;-->
<!--&lt;!&ndash;              v-model="nodeData.output_variable"&ndash;&gt;-->
<!--&lt;!&ndash;              @change="updateNodeData('output_variable', $event.target.value)"&ndash;&gt;-->
<!--&lt;!&ndash;              placeholder="result"&ndash;&gt;-->
<!--&lt;!&ndash;            />&ndash;&gt;-->
<!--&lt;!&ndash;          </div>&ndash;&gt;-->
<!--&lt;!&ndash;        </div>&ndash;&gt;-->
<!--&lt;!&ndash;      </div>&ndash;&gt;-->
<!--&lt;!&ndash;    </div>&ndash;&gt;-->
<!--&lt;!&ndash;  </div>&ndash;&gt;-->
<!--&lt;!&ndash;</template>&ndash;&gt;-->

<!--&lt;!&ndash;<style scoped>&ndash;&gt;-->
<!--&lt;!&ndash;.prompt-node {&ndash;&gt;-->
<!--&lt;!&ndash;  padding: 10px;&ndash;&gt;-->
<!--&lt;!&ndash;  border-radius: 5px;&ndash;&gt;-->
<!--&lt;!&ndash;  background: white;&ndash;&gt;-->
<!--&lt;!&ndash;  border: 1px solid #ddd;&ndash;&gt;-->
<!--&lt;!&ndash;  width: 250px;&ndash;&gt;-->
<!--&lt;!&ndash;  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.prompt-node.selected {&ndash;&gt;-->
<!--&lt;!&ndash;  border-color: #5e72e4;&ndash;&gt;-->
<!--&lt;!&ndash;  box-shadow: 0 0 0 2px rgba(94, 114, 228, 0.3);&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.prompt-node-header {&ndash;&gt;-->
<!--&lt;!&ndash;  display: flex;&ndash;&gt;-->
<!--&lt;!&ndash;  align-items: center;&ndash;&gt;-->
<!--&lt;!&ndash;  justify-content: space-between;&ndash;&gt;-->
<!--&lt;!&ndash;  margin-bottom: 8px;&ndash;&gt;-->
<!--&lt;!&ndash;  font-weight: 600;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.prompt-node-toggle {&ndash;&gt;-->
<!--&lt;!&ndash;  background: none;&ndash;&gt;-->
<!--&lt;!&ndash;  border: none;&ndash;&gt;-->
<!--&lt;!&ndash;  cursor: pointer;&ndash;&gt;-->
<!--&lt;!&ndash;  color: #495057;&ndash;&gt;-->
<!--&lt;!&ndash;  padding: 2px 5px;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.prompt-node-toggle:hover {&ndash;&gt;-->
<!--&lt;!&ndash;  color: #5e72e4;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.prompt-content-preview {&ndash;&gt;-->
<!--&lt;!&ndash;  padding: 8px;&ndash;&gt;-->
<!--&lt;!&ndash;  margin-bottom: 10px;&ndash;&gt;-->
<!--&lt;!&ndash;  background-color: #f8f9fa;&ndash;&gt;-->
<!--&lt;!&ndash;  border: 1px solid #e9ecef;&ndash;&gt;-->
<!--&lt;!&ndash;  border-radius: 4px;&ndash;&gt;-->
<!--&lt;!&ndash;  max-height: 100px;&ndash;&gt;-->
<!--&lt;!&ndash;  overflow-y: auto;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.prompt-text {&ndash;&gt;-->
<!--&lt;!&ndash;  font-size: 0.8rem;&ndash;&gt;-->
<!--&lt;!&ndash;  white-space: pre-wrap;&ndash;&gt;-->
<!--&lt;!&ndash;  word-break: break-word;&ndash;&gt;-->
<!--&lt;!&ndash;  color: #333;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.prompt-placeholder {&ndash;&gt;-->
<!--&lt;!&ndash;  font-size: 0.8rem;&ndash;&gt;-->
<!--&lt;!&ndash;  font-style: italic;&ndash;&gt;-->
<!--&lt;!&ndash;  color: #adb5bd;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.prompt-node-settings {&ndash;&gt;-->
<!--&lt;!&ndash;  margin-top: 10px;&ndash;&gt;-->
<!--&lt;!&ndash;  font-size: 0.85rem;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.setting-group {&ndash;&gt;-->
<!--&lt;!&ndash;  margin-bottom: 12px;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.setting-label {&ndash;&gt;-->
<!--&lt;!&ndash;  display: block;&ndash;&gt;-->
<!--&lt;!&ndash;  margin-bottom: 4px;&ndash;&gt;-->
<!--&lt;!&ndash;  color: #495057;&ndash;&gt;-->
<!--&lt;!&ndash;  font-weight: 600;&ndash;&gt;-->
<!--&lt;!&ndash;  font-size: 0.8rem;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.setting-option {&ndash;&gt;-->
<!--&lt;!&ndash;  margin-bottom: 5px;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;/* Stilisierung für die Prompt-Vorschau */&ndash;&gt;-->
<!--&lt;!&ndash;.prompt-preview {&ndash;&gt;-->
<!--&lt;!&ndash;  font-size: 0.8rem;&ndash;&gt;-->
<!--&lt;!&ndash;  white-space: pre-wrap;&ndash;&gt;-->
<!--&lt;!&ndash;  max-height: 100px;&ndash;&gt;-->
<!--&lt;!&ndash;  overflow-y: auto;&ndash;&gt;-->
<!--&lt;!&ndash;  background: white;&ndash;&gt;-->
<!--&lt;!&ndash;  padding: 5px;&ndash;&gt;-->
<!--&lt;!&ndash;  border-radius: 3px;&ndash;&gt;-->
<!--&lt;!&ndash;  border: 1px solid #e2e2e2;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.variable-input {&ndash;&gt;-->
<!--&lt;!&ndash;  background-color: #f0f4ff;&ndash;&gt;-->
<!--&lt;!&ndash;  padding: 8px;&ndash;&gt;-->
<!--&lt;!&ndash;  border-radius: 4px;&ndash;&gt;-->
<!--&lt;!&ndash;  border: 1px solid #d1deff;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;/* Kontrollelemente nicht verschiebbar machen */&ndash;&gt;-->
<!--&lt;!&ndash;.prompt-node input,&ndash;&gt;-->
<!--&lt;!&ndash;.prompt-node select,&ndash;&gt;-->
<!--&lt;!&ndash;.prompt-node textarea,&ndash;&gt;-->
<!--&lt;!&ndash;.prompt-node button {&ndash;&gt;-->
<!--&lt;!&ndash;  pointer-events: auto;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->
<!--&lt;!&ndash;</style>&ndash;&gt;-->


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

<!--// Emit-Funktion für Datenaktualisierungen-->
<!--const emit = defineEmits(['update:data']);-->

<!--// Node-Konfiguration mit verfügbaren Providern und Modellen-->
<!--const config = ref({-->
<!--  showSettings: false,-->
<!--  availableProviders: [-->
<!--    { value: 'openai', label: 'OpenAI' },-->
<!--    { value: 'anthropic', label: 'Anthropic' },-->
<!--    { value: 'google', label: 'Google' },-->
<!--    { value: 'deepseek', label: 'DeepSeek' },-->
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
<!--    deepseek: [-->
<!--      { value: 'deepseek-coder', label: 'DeepSeek Coder' },-->
<!--      { value: 'deepseek-llm', label: 'DeepSeek LLM' },-->
<!--      { value: 'deepseek-chat', label: 'DeepSeek Chat' }-->
<!--    ],-->
<!--    ollama: [-->
<!--      { value: 'llama3', label: 'Llama 3' },-->
<!--      { value: 'mistral', label: 'Mistral' },-->
<!--      { value: 'mixtral', label: 'Mixtral' }-->
<!--    ]-->
<!--  }-->
<!--});-->

<!--// Initialisierung der Node-Daten mit Defaultwerten aus props-->
<!--const nodeData = ref({-->
<!--  prompt_id: props.data.prompt_id || null,-->
<!--  content: props.data.content || '',-->
<!--  provider: props.data.provider || 'openai',-->
<!--  model: props.data.model || 'gpt-4-turbo',-->
<!--  temperature: parseFloat(props.data.temperature || 0.7),-->
<!--  max_tokens: parseInt(props.data.max_tokens || 1000),-->
<!--  output_variable: props.data.output_variable || 'result',-->
<!--  variables: props.data.variables || {}-->
<!--});-->

<!--// Prompt-Auswahl Status-->
<!--const useSavedPrompt = ref(Boolean(props.data.prompt_id));-->
<!--const selectedPromptId = ref(props.data.prompt_id || '');-->

<!--// Verfügbare Modelle basierend auf dem ausgewählten Provider-->
<!--const availableModels = computed(() => {-->
<!--  return config.value.availableModels[nodeData.value.provider] || [];-->
<!--});-->

<!--// Finde alle Variablen im Prompt-Text ({{variablename}}-Format)-->
<!--const extractedVariables = computed(() => {-->
<!--  const content = nodeData.value.content || '';-->
<!--  const regex = /\{\{([^{}]+)\}\}/g;-->
<!--  const variables = [];-->
<!--  let match;-->

<!--  while ((match = regex.exec(content)) !== null) {-->
<!--    const varName = match[1].trim();-->
<!--    if (!variables.includes(varName)) {-->
<!--      variables.push(varName);-->
<!--    }-->
<!--  }-->

<!--  return variables;-->
<!--});-->

<!--// Einstellungsmenü umschalten-->
<!--const toggleSettings = (event) => {-->
<!--  event.stopPropagation();-->
<!--  config.value.showSettings = !config.value.showSettings;-->
<!--};-->

<!--// Node-Daten aktualisieren und an übergeordnete Komponente senden-->
<!--const updateNodeData = (key, value) => {-->
<!--  nodeData.value[key] = value;-->
<!--  emit('update:data', { ...props.data, [key]: value });-->
<!--};-->

<!--// Provider wechseln und Modell aktualisieren-->
<!--const onProviderChange = (provider) => {-->
<!--  updateNodeData('provider', provider);-->

<!--  const models = config.value.availableModels[provider] || [];-->
<!--  if (models.length > 0) {-->
<!--    updateNodeData('model', models[0].value);-->
<!--  }-->
<!--};-->

<!--// Prompt auswählen und Inhalt aktualisieren-->
<!--const selectPrompt = () => {-->
<!--  updateNodeData('prompt_id', selectedPromptId.value);-->

<!--  if (selectedPromptId.value) {-->
<!--    // Versuche den Prompt in props.data.prompts zu finden (originaler Pfad)-->
<!--    let selectedPrompt = null;-->

<!--    if (props.data.prompts && props.data.prompts.length > 0) {-->
<!--      selectedPrompt = props.data.prompts.find(p => p.id == selectedPromptId.value);-->
<!--    }-->

<!--    // Fallback zu props.prompts wenn nicht gefunden-->
<!--    if (!selectedPrompt && props.prompts && props.prompts.length > 0) {-->
<!--      selectedPrompt = props.prompts.find(p => p.id == selectedPromptId.value);-->
<!--    }-->

<!--    if (selectedPrompt) {-->
<!--      updateNodeData('content', selectedPrompt.content);-->

<!--      // Aktualisiere Variablen nach einer kurzen Verzögerung-->
<!--      setTimeout(() => {-->
<!--        updateVariablesFromContent();-->
<!--      }, 50);-->
<!--    }-->
<!--  }-->
<!--};-->

<!--// Temperatur-Änderung verarbeiten-->
<!--const onTemperatureChange = (event) => {-->
<!--  const value = parseFloat(event.target.value);-->
<!--  updateNodeData('temperature', value);-->
<!--};-->

<!--// Variable-Wert aktualisieren-->
<!--const updateVariableValue = (varName, value) => {-->
<!--  const updatedVariables = { ...nodeData.value.variables, [varName]: value };-->
<!--  updateNodeData('variables', updatedVariables);-->
<!--};-->

<!--// Variablen aus Prompt-Inhalt extrahieren und im variables-Objekt speichern-->
<!--const updateVariablesFromContent = () => {-->
<!--  if (nodeData.value.content) {-->
<!--    const currentVars = nodeData.value.variables || {};-->
<!--    const updatedVars = { ...currentVars };-->

<!--    // Regex für {{variablename}}-Muster-->
<!--    const regex = /\{\{([^{}]+)\}\}/g;-->
<!--    let match;-->

<!--    while ((match = regex.exec(nodeData.value.content)) !== null) {-->
<!--      const varName = match[1].trim();-->
<!--      if (updatedVars[varName] === undefined) {-->
<!--        updatedVars[varName] = '';-->
<!--      }-->
<!--    }-->

<!--    updateNodeData('variables', updatedVars);-->
<!--  }-->
<!--};-->

<!--// Überwachen des useSavedPrompt-Werts-->
<!--watch(useSavedPrompt, (newValue) => {-->
<!--  if (!newValue) {-->
<!--    // Bei Wechsel zu benutzerdefiniertem Prompt, prompt_id löschen-->
<!--    updateNodeData('prompt_id', null);-->
<!--  } else {-->
<!--    // Wenn Prompts verfügbar sind, wähle den ersten aus-->
<!--    if (props.data.prompts && props.data.prompts.length > 0) {-->
<!--      if (!selectedPromptId.value) {-->
<!--        selectedPromptId.value = props.data.prompts[0].id;-->
<!--        selectPrompt();-->
<!--      }-->
<!--    }-->
<!--    else if (props.prompts && props.prompts.length > 0) {-->
<!--      if (!selectedPromptId.value) {-->
<!--        selectedPromptId.value = props.prompts[0].id;-->
<!--        selectPrompt();-->
<!--      }-->
<!--    }-->
<!--  }-->
<!--});-->

<!--// Initialisierung und Laden des Prompt-Inhalts-->
<!--onMounted(() => {-->
<!--  // Initialisiere Variables-Objekt, falls nicht vorhanden-->
<!--  if (!nodeData.value.variables) {-->
<!--    updateNodeData('variables', {});-->
<!--  }-->

<!--  // Wenn eine prompt_id existiert, lade den entsprechenden Prompt-->
<!--  if (props.data.prompt_id) {-->
<!--    selectedPromptId.value = props.data.prompt_id;-->
<!--    selectPrompt();-->
<!--  } else if (nodeData.value.content) {-->
<!--    // Wenn kein Prompt ausgewählt ist, aber Content existiert, extrahiere Variablen-->
<!--    updateVariablesFromContent();-->
<!--  }-->
<!--});-->

<!--// Überwachen der Prompt-Auswahl-->
<!--watch(selectedPromptId, (newValue) => {-->
<!--  if (newValue) {-->
<!--    selectPrompt();-->
<!--  }-->
<!--});-->

<!--// Überwachen von Änderungen am Prompt-Inhalt, um neue Variablen zu erkennen-->
<!--watch(() => nodeData.value.content, (newContent) => {-->
<!--  if (newContent) {-->
<!--    updateVariablesFromContent();-->
<!--  }-->
<!--});-->

<!--// Verhindern von Klick-Propagation-->
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

<!--    &lt;!&ndash; Node-Inhalt &ndash;&gt;-->
<!--    <div class="prompt-node-content">-->
<!--      <div class="prompt-node-header">-->
<!--        <i class="fa fa-file-alt text-primary me-1"></i>-->
<!--        <span class="prompt-node-label">{{ data.label || 'Prompt' }}</span>-->
<!--        <button-->
<!--          type="button"-->
<!--          class="prompt-node-toggle"-->
<!--          @click="toggleSettings"-->
<!--          :title="config.showSettings ? 'Einstellungen ausblenden' : 'Einstellungen anzeigen'"-->
<!--        >-->
<!--          <i :class="['fa', config.showSettings ? 'fa-chevron-up' : 'fa-chevron-down']"></i>-->
<!--        </button>-->
<!--      </div>-->

<!--      &lt;!&ndash; Prompt-Inhalt Vorschau (immer sichtbar) &ndash;&gt;-->
<!--      <div class="prompt-content-preview">-->
<!--        <div v-if="nodeData.content" class="prompt-text">-->
<!--          {{ nodeData.content }}-->
<!--        </div>-->
<!--        <div v-else class="prompt-placeholder">-->
<!--          Kein Prompt-Inhalt definiert...-->
<!--        </div>-->
<!--      </div>-->

<!--      &lt;!&ndash; Einstellungsbereich &ndash;&gt;-->
<!--      <div v-if="config.showSettings" class="prompt-node-settings" @mousedown="preventDrag" @click="preventDrag">-->
<!--        &lt;!&ndash; Prompt-Auswahl &ndash;&gt;-->
<!--        <div class="setting-group">-->
<!--          <label class="setting-label">Prompt-Quelle</label>-->
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
<!--                @change="selectPrompt"-->
<!--              >-->
<!--                <option value="">Prompt auswählen</option>-->
<!--                &lt;!&ndash; Versuche zuerst props.data.prompts (original) &ndash;&gt;-->
<!--                <template v-if="props.data.prompts && props.data.prompts.length > 0">-->
<!--                  <option v-for="prompt in props.data.prompts" :key="prompt.id" :value="prompt.id">-->
<!--                    {{ prompt.name }}-->
<!--                  </option>-->
<!--                </template>-->
<!--                &lt;!&ndash; Fallback zu props.prompts &ndash;&gt;-->
<!--                <template v-else-if="props.prompts && props.prompts.length > 0">-->
<!--                  <option v-for="prompt in props.prompts" :key="prompt.id" :value="prompt.id">-->
<!--                    {{ prompt.name }}-->
<!--                  </option>-->
<!--                </template>-->
<!--              </select>-->

<!--              &lt;!&ndash; Debugging-Info &ndash;&gt;-->
<!--              <div v-if="props.data.prompts && props.data.prompts.length > 0" class="text-muted small mt-1">-->
<!--                {{ props.data.prompts.length }} Prompts verfügbar-->
<!--              </div>-->
<!--              <div v-else-if="props.prompts && props.prompts.length > 0" class="text-muted small mt-1">-->
<!--                {{ props.prompts.length }} Prompts verfügbar-->
<!--              </div>-->
<!--              <div v-else class="text-muted small mt-1">-->
<!--                Keine gespeicherten Prompts verfügbar-->
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

<!--        &lt;!&ndash; Prompt-Inhalt (nur sichtbar bei benutzerdefiniertem Prompt) &ndash;&gt;-->
<!--        <div v-if="!useSavedPrompt" class="setting-group">-->
<!--          <label class="setting-label" for="prompt-content">Prompt-Inhalt</label>-->
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

<!--        &lt;!&ndash; Prompt-Variablen Eingabefelder &ndash;&gt;-->
<!--        <div v-if="extractedVariables.length > 0" class="setting-group">-->
<!--          <label class="setting-label">Prompt-Variablen</label>-->
<!--          <div class="setting-option">-->
<!--            <div class="variable-info mb-2">-->
<!--              <small class="text-muted">Im Prompt gefundene Variablen ({{variablename}}):</small>-->
<!--            </div>-->
<!--            <div-->
<!--              v-for="varName in extractedVariables"-->
<!--              :key="varName"-->
<!--              class="variable-input mb-2"-->
<!--            >-->
<!--              <label :for="`var-${varName}`" class="form-label mb-1">{{ varName }}</label>-->
<!--              <input-->
<!--                :id="`var-${varName}`"-->
<!--                type="text"-->
<!--                class="form-control form-control-sm"-->
<!--                :value="nodeData.variables[varName] || ''"-->
<!--                @input="updateVariableValue(varName, $event.target.value)"-->
<!--                :placeholder="`Wert für {{${varName}}}`"-->
<!--              />-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->

<!--        &lt;!&ndash; LLM Provider-Auswahl &ndash;&gt;-->
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

<!--        &lt;!&ndash; Modell-Auswahl &ndash;&gt;-->
<!--        <div class="setting-group">-->
<!--          <label class="setting-label" for="prompt-model">Modell</label>-->
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

<!--        &lt;!&ndash; Temperatur-Slider &ndash;&gt;-->
<!--        <div class="setting-group">-->
<!--          <label class="setting-label" for="prompt-temperature">-->
<!--            Temperatur: {{ nodeData.temperature }}-->
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

<!--        &lt;!&ndash; Ausgabe-Variable &ndash;&gt;-->
<!--        <div class="setting-group">-->
<!--          <label class="setting-label" for="prompt-output-variable">Ausgabe-Variable</label>-->
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

<!--.prompt-content-preview {-->
<!--  padding: 8px;-->
<!--  margin-bottom: 10px;-->
<!--  background-color: #f8f9fa;-->
<!--  border: 1px solid #e9ecef;-->
<!--  border-radius: 4px;-->
<!--  max-height: 100px;-->
<!--  overflow-y: auto;-->
<!--}-->

<!--.prompt-text {-->
<!--  font-size: 0.8rem;-->
<!--  white-space: pre-wrap;-->
<!--  word-break: break-word;-->
<!--  color: #333;-->
<!--}-->

<!--.prompt-placeholder {-->
<!--  font-size: 0.8rem;-->
<!--  font-style: italic;-->
<!--  color: #adb5bd;-->
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

<!--.variable-input {-->
<!--  background-color: #f0f4ff;-->
<!--  padding: 8px;-->
<!--  border-radius: 4px;-->
<!--  border: 1px solid #d1deff;-->
<!--}-->

<!--/* Kontrollelemente nicht verschiebbar machen */-->
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
    { value: 'deepseek', label: 'DeepSeek' },
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
    deepseek: [
      { value: 'deepseek-coder', label: 'DeepSeek Coder' },
      { value: 'deepseek-llm', label: 'DeepSeek LLM' },
      { value: 'deepseek-chat', label: 'DeepSeek Chat' }
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
  output_variable: props.data.output_variable || 'result',
  variables: props.data.variables || {}
});

// Prompt-Auswahl Status
const useSavedPrompt = ref(Boolean(props.data.prompt_id));
const selectedPromptId = ref(props.data.prompt_id || '');

// Verfügbare Modelle basierend auf dem ausgewählten Provider
const availableModels = computed(() => {
  return config.value.availableModels[nodeData.value.provider] || [];
});

// Finde alle Variablen im Prompt-Text ({{variablename}}-Format)
const extractedVariables = computed(() => {
  const content = nodeData.value.content || '';
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
  let content = nodeData.value.content || '';

  // Ersetze alle {{variablename}} mit den tatsächlichen Werten
  Object.entries(nodeData.value.variables || {}).forEach(([key, value]) => {
    const pattern = new RegExp(`\\{\\{${key}\\}\\}`, 'g');
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
    // Versuche den Prompt in props.data.prompts zu finden (originaler Pfad)
    let selectedPrompt = null;

    if (props.data.prompts && props.data.prompts.length > 0) {
      selectedPrompt = props.data.prompts.find(p => p.id == selectedPromptId.value);
    }

    // Fallback zu props.prompts wenn nicht gefunden
    if (!selectedPrompt && props.prompts && props.prompts.length > 0) {
      selectedPrompt = props.prompts.find(p => p.id == selectedPromptId.value);
    }

    if (selectedPrompt) {
      updateNodeData('content', selectedPrompt.content);

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
  updateNodeData('temperature', value);
};

// Variable-Wert aktualisieren
const updateVariableValue = (varName, value) => {
  const updatedVariables = { ...nodeData.value.variables, [varName]: value };
  updateNodeData('variables', updatedVariables);
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
        updatedVars[varName] = '';
      }
    }

    updateNodeData('variables', updatedVars);
  }
};

// Überwachen des useSavedPrompt-Werts
watch(useSavedPrompt, (newValue) => {
  if (!newValue) {
    // Bei Wechsel zu benutzerdefiniertem Prompt, prompt_id löschen
    updateNodeData('prompt_id', null);
  } else {
    // Wenn Prompts verfügbar sind, wähle den ersten aus
    if (props.data.prompts && props.data.prompts.length > 0) {
      if (!selectedPromptId.value) {
        selectedPromptId.value = props.data.prompts[0].id;
        selectPrompt();
      }
    }
    else if (props.prompts && props.prompts.length > 0) {
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
    updateNodeData('variables', {});
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
watch(() => nodeData.value.content, (newContent) => {
  if (newContent) {
    updateVariablesFromContent();
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
        <span class="prompt-node-label">{{ data.label || 'Prompt' }}</span>
        <button
          type="button"
          class="prompt-node-toggle"
          @click="toggleSettings"
          :title="config.showSettings ? 'Einstellungen ausblenden' : 'Einstellungen anzeigen'"
        >
          <i :class="['fa', config.showSettings ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
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
                @change="selectPrompt"
              >
                <option value="">Prompt auswählen</option>
                <!-- Versuche zuerst props.data.prompts (original) -->
                <template v-if="props.data.prompts && props.data.prompts.length > 0">
                  <option v-for="prompt in props.data.prompts" :key="prompt.id" :value="prompt.id">
                    {{ prompt.name }}
                  </option>
                </template>
                <!-- Fallback zu props.prompts -->
                <template v-else-if="props.prompts && props.prompts.length > 0">
                  <option v-for="prompt in props.prompts" :key="prompt.id" :value="prompt.id">
                    {{ prompt.name }}
                  </option>
                </template>
              </select>

              <!-- Debugging-Info -->
              <div v-if="props.data.prompts && props.data.prompts.length > 0" class="text-muted small mt-1">
                {{ props.data.prompts.length }} Prompts verfügbar
              </div>
              <div v-else-if="props.prompts && props.prompts.length > 0" class="text-muted small mt-1">
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
            <div class="form-text text-muted mt-1">
              <small>Verwende {{variablename}} für Variablen im Prompt.</small>
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
              <label :for="`var-${varName}`" class="form-label mb-1">{{ varName }}</label>
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
