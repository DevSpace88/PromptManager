<!--&lt;!&ndash;&lt;!&ndash;// resources/js/views/Pages/Workflows/Nodes/PromptNode.vue&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;<script setup>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { ref, computed, onMounted } from 'vue';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { Handle, Position, useVueFlow } from '@vue-flow/core';&ndash;&gt;&ndash;&gt;-->

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
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Get Vue Flow methods&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const { findNode, setNodes } = useVueFlow();&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Node configuration&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const config = ref({&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  showSettings: false,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  availableProviders: [&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    { value: 'openai', label: 'OpenAI' },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    { value: 'anthropic', label: 'Anthropic' },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    { value: 'google', label: 'Google' },&ndash;&gt;&ndash;&gt;-->
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
<!--&lt;!&ndash;&lt;!&ndash;    ollama: [&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      { value: 'llama3', label: 'Llama 3' },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      { value: 'mistral', label: 'Mistral' },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      { value: 'mixtral', label: 'Mixtral' }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    ]&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Get models based on selected provider&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const availableModels = computed(() => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  return config.value.availableModels[props.data.provider] || [];&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Toggle settings panel&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const toggleSettings = () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  config.value.showSettings = !config.value.showSettings;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Update node data&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const updateNodeData = (key, value) => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  const node = findNode(props.id);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  if (!node) return;&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  const newNode = {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    ...node,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    data: {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      ...node.data,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      [key]: value&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  };&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  setNodes((nodes) => nodes.map(n => n.id === props.id ? newNode : n));&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// When provider changes, update model to the first available model&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const onProviderChange = (provider) => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  updateNodeData('provider', provider);&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  const models = config.value.availableModels[provider] || [];&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  if (models.length > 0) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    updateNodeData('model', models[0].value);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->
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

<!--&lt;!&ndash;&lt;!&ndash;    &lt;!&ndash; Node Content &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    <div class="prompt-node-content">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      <div class="prompt-node-header">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <i class="fa fa-file-alt text-primary me-1"></i>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <span class="prompt-node-label">{{ data.label }}</span>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <button&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          type="button"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          class="prompt-node-toggle"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          @click="toggleSettings"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          :title="config.showSettings ? 'Hide settings' : 'Show settings'"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <i :class="['fa', config.showSettings ? 'fa-chevron-up' : 'fa-chevron-down']"></i>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </button>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;      &lt;!&ndash; Setting Panel &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      <div v-if="config.showSettings" class="prompt-node-settings">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        &lt;!&ndash; Prompt Selection &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div class="setting-group">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <label class="setting-label">Prompt Source</label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="setting-option">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <div class="form-check">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <input&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                type="radio"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                id="prompt-source-saved"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                class="form-check-input"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                :value="true"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                :checked="data.prompt_id !== null"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                @change="updateNodeData('prompt_id', data.prompt_id || '')"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              />&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <label class="form-check-label" for="prompt-source-saved">Use saved prompt</label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <div class="form-check">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <input&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                type="radio"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                id="prompt-source-custom"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                class="form-check-input"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                :value="false"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                :checked="data.prompt_id === null"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                @change="updateNodeData('prompt_id', null)"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              />&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <label class="form-check-label" for="prompt-source-custom">Custom prompt</label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;        &lt;!&ndash; Prompt Content (if custom) &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div v-if="data.prompt_id === null" class="setting-group">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <label class="setting-label">Prompt</label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="setting-option">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <textarea&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              class="form-control form-control-sm"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              v-model="data.content"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              rows="3"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              placeholder="Enter prompt text with {{variables}}"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              @change="updateNodeData('content', data.content)"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            ></textarea>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;        &lt;!&ndash; Provider Selection &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div class="setting-group">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <label class="setting-label">Provider</label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="setting-option">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <select&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              class="form-select form-select-sm"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              v-model="data.provider"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              @change="onProviderChange(data.provider)"&ndash;&gt;&ndash;&gt;-->
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

<!--&lt;!&ndash;&lt;!&ndash;        &lt;!&ndash; Model Selection &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div class="setting-group">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <label class="setting-label">Model</label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="setting-option">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <select&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              class="form-select form-select-sm"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              v-model="data.model"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              @change="updateNodeData('model', data.model)"&ndash;&gt;&ndash;&gt;-->
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

<!--&lt;!&ndash;&lt;!&ndash;        &lt;!&ndash; Temperature &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div class="setting-group">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <label class="setting-label">Temperature</label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="setting-option d-flex align-items-center gap-2">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <input&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              type="range"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              class="form-range"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              min="0"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              max="2"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              step="0.1"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              v-model.number="data.temperature"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              @change="updateNodeData('temperature', data.temperature)"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <span class="badge bg-primary">{{ data.temperature }}</span>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;        &lt;!&ndash; Max Tokens &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div class="setting-group">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <label class="setting-label">Max Tokens</label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="setting-option">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <input&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              type="number"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              class="form-control form-control-sm"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              v-model.number="data.max_tokens"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              min="1"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              max="8192"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              @change="updateNodeData('max_tokens', data.max_tokens)"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;        &lt;!&ndash; Output Variable &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div class="setting-group">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <label class="setting-label">Save To</label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="setting-option">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <input&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              type="text"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              class="form-control form-control-sm"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              v-model="data.output_variable"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              placeholder="Variable name"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              @change="updateNodeData('output_variable', data.output_variable)"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;      &lt;!&ndash; Node Summary (when collapsed) &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      <div v-else class="prompt-node-summary">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div v-if="data.prompt_id">Using saved prompt</div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div v-else class="prompt-content-preview">{{ (data.content || '').substring(0, 30) }}{{ (data.content || '').length > 30 ? '...' : '' }}</div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div class="prompt-node-model">{{ data.provider }} / {{ data.model }}</div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;</template>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;<style scoped>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;.prompt-node {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  min-width: 200px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  max-width: 300px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  background-color: #ffffff;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border: 2px solid #5c80d1;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border-radius: 5px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  font-size: 12px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  overflow: hidden;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.prompt-node.selected {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  box-shadow: 0 0 0 2px #5c80d1;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.prompt-node-content {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  padding: 5px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.prompt-node-header {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  display: flex;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  align-items: center;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  font-weight: bold;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  padding: 4px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  background-color: rgba(92, 128, 209, 0.1);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border-bottom: 1px solid rgba(92, 128, 209, 0.2);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.prompt-node-label {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  flex-grow: 1;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  margin-right: 5px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.prompt-node-toggle {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  background: none;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border: none;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  cursor: pointer;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  padding: 0;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  width: 16px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  height: 16px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  display: flex;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  align-items: center;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  justify-content: center;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  color: #5c80d1;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.prompt-node-settings {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  padding: 8px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  font-size: 11px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.setting-group {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  margin-bottom: 8px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.setting-label {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  font-weight: 600;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  margin-bottom: 2px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  font-size: 10px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  text-transform: uppercase;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  color: #6c757d;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.setting-option {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  margin-bottom: 5px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.prompt-node-summary {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  padding: 5px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  font-size: 11px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  color: #6c757d;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.prompt-content-preview {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  font-style: italic;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  white-space: nowrap;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  overflow: hidden;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  text-overflow: ellipsis;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.prompt-node-model {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  font-size: 10px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  color: #5c80d1;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  margin-top: 3px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;</style>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;<script setup>&ndash;&gt;-->
<!--&lt;!&ndash;import { ref, computed, onMounted, watch } from 'vue';&ndash;&gt;-->
<!--&lt;!&ndash;import { Handle, Position, useVueFlow } from '@vue-flow/core';&ndash;&gt;-->

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

<!--&lt;!&ndash;// Get Vue Flow methods&ndash;&gt;-->
<!--&lt;!&ndash;const { findNode, setNodes } = useVueFlow();&ndash;&gt;-->

<!--&lt;!&ndash;// Node configuration&ndash;&gt;-->
<!--&lt;!&ndash;const config = ref({&ndash;&gt;-->
<!--&lt;!&ndash;  showSettings: false,&ndash;&gt;-->
<!--&lt;!&ndash;  availableProviders: [&ndash;&gt;-->
<!--&lt;!&ndash;    { value: 'openai', label: 'OpenAI' },&ndash;&gt;-->
<!--&lt;!&ndash;    { value: 'anthropic', label: 'Anthropic' },&ndash;&gt;-->
<!--&lt;!&ndash;    { value: 'google', label: 'Google' },&ndash;&gt;-->
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
<!--&lt;!&ndash;    ollama: [&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'llama3', label: 'Llama 3' },&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'mistral', label: 'Mistral' },&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'mixtral', label: 'Mixtral' }&ndash;&gt;-->
<!--&lt;!&ndash;    ]&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Prompt selection state&ndash;&gt;-->
<!--&lt;!&ndash;const useSavedPrompt = ref(props.data.prompt_id !== null);&ndash;&gt;-->
<!--&lt;!&ndash;const selectedPromptId = ref(props.data.prompt_id || '');&ndash;&gt;-->

<!--&lt;!&ndash;// Get models based on selected provider&ndash;&gt;-->
<!--&lt;!&ndash;const availableModels = computed(() => {&ndash;&gt;-->
<!--&lt;!&ndash;  return config.value.availableModels[props.data.provider] || [];&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Toggle settings panel&ndash;&gt;-->
<!--&lt;!&ndash;const toggleSettings = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  config.value.showSettings = !config.value.showSettings;&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Update node data&ndash;&gt;-->
<!--&lt;!&ndash;const updateNodeData = (key, value) => {&ndash;&gt;-->
<!--&lt;!&ndash;  const node = findNode(props.id);&ndash;&gt;-->
<!--&lt;!&ndash;  if (!node) return;&ndash;&gt;-->

<!--&lt;!&ndash;  const newNode = {&ndash;&gt;-->
<!--&lt;!&ndash;    ...node,&ndash;&gt;-->
<!--&lt;!&ndash;    data: {&ndash;&gt;-->
<!--&lt;!&ndash;      ...node.data,&ndash;&gt;-->
<!--&lt;!&ndash;      [key]: value&ndash;&gt;-->
<!--&lt;!&ndash;    }&ndash;&gt;-->
<!--&lt;!&ndash;  };&ndash;&gt;-->

<!--&lt;!&ndash;  setNodes((nodes) => nodes.map(n => n.id === props.id ? newNode : n));&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// When provider changes, update model to the first available model&ndash;&gt;-->
<!--&lt;!&ndash;const onProviderChange = (provider) => {&ndash;&gt;-->
<!--&lt;!&ndash;  updateNodeData('provider', provider);&ndash;&gt;-->

<!--&lt;!&ndash;  const models = config.value.availableModels[provider] || [];&ndash;&gt;-->
<!--&lt;!&ndash;  if (models.length > 0) {&ndash;&gt;-->
<!--&lt;!&ndash;    updateNodeData('model', models[0].value);&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Method to select a prompt&ndash;&gt;-->
<!--&lt;!&ndash;const selectPrompt = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  updateNodeData('prompt_id', selectedPromptId.value);&ndash;&gt;-->

<!--&lt;!&ndash;  // If there's a selected prompt, fetch its content&ndash;&gt;-->
<!--&lt;!&ndash;  if (selectedPromptId.value) {&ndash;&gt;-->
<!--&lt;!&ndash;    const selectedPrompt = props.prompts.find(p => p.id == selectedPromptId.value);&ndash;&gt;-->
<!--&lt;!&ndash;    if (selectedPrompt) {&ndash;&gt;-->
<!--&lt;!&ndash;      updateNodeData('content', selectedPrompt.content);&ndash;&gt;-->
<!--&lt;!&ndash;    }&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Watch for changes in the useSavedPrompt value&ndash;&gt;-->
<!--&lt;!&ndash;watch(useSavedPrompt, (newValue) => {&ndash;&gt;-->
<!--&lt;!&ndash;  if (!newValue) {&ndash;&gt;-->
<!--&lt;!&ndash;    // If switching to custom prompt, clear the prompt_id&ndash;&gt;-->
<!--&lt;!&ndash;    updateNodeData('prompt_id', null);&ndash;&gt;-->
<!--&lt;!&ndash;  } else {&ndash;&gt;-->
<!--&lt;!&ndash;    // If switching to saved prompt, but no prompt selected yet&ndash;&gt;-->
<!--&lt;!&ndash;    if (!selectedPromptId.value && props.prompts.length > 0) {&ndash;&gt;-->
<!--&lt;!&ndash;      // Pre-select the first prompt&ndash;&gt;-->
<!--&lt;!&ndash;      selectedPromptId.value = props.prompts[0].id;&ndash;&gt;-->
<!--&lt;!&ndash;      selectPrompt();&ndash;&gt;-->
<!--&lt;!&ndash;    }&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->
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

<!--&lt;!&ndash;    &lt;!&ndash; Node Content &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;    <div class="prompt-node-content">&ndash;&gt;-->
<!--&lt;!&ndash;      <div class="prompt-node-header">&ndash;&gt;-->
<!--&lt;!&ndash;        <i class="fa fa-file-alt text-primary me-1"></i>&ndash;&gt;-->
<!--&lt;!&ndash;        <span class="prompt-node-label">{{ data.label }}</span>&ndash;&gt;-->
<!--&lt;!&ndash;        <button&ndash;&gt;-->
<!--&lt;!&ndash;          type="button"&ndash;&gt;-->
<!--&lt;!&ndash;          class="prompt-node-toggle"&ndash;&gt;-->
<!--&lt;!&ndash;          @click="toggleSettings"&ndash;&gt;-->
<!--&lt;!&ndash;          :title="config.showSettings ? 'Hide settings' : 'Show settings'"&ndash;&gt;-->
<!--&lt;!&ndash;        >&ndash;&gt;-->
<!--&lt;!&ndash;          <i :class="['fa', config.showSettings ? 'fa-chevron-up' : 'fa-chevron-down']"></i>&ndash;&gt;-->
<!--&lt;!&ndash;        </button>&ndash;&gt;-->
<!--&lt;!&ndash;      </div>&ndash;&gt;-->

<!--&lt;!&ndash;      &lt;!&ndash; Setting Panel &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;      <div v-if="config.showSettings" class="prompt-node-settings">&ndash;&gt;-->
<!--&lt;!&ndash;        &lt;!&ndash; Prompt Selection &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label">Prompt Source</label>&ndash;&gt;-->
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
<!--&lt;!&ndash;            <div v-if="useSavedPrompt" class="mt-2">&ndash;&gt;-->
<!--&lt;!&ndash;              <select&ndash;&gt;-->
<!--&lt;!&ndash;                v-model="selectedPromptId"&ndash;&gt;-->
<!--&lt;!&ndash;                class="form-select form-select-sm"&ndash;&gt;-->
<!--&lt;!&ndash;                @change="selectPrompt"&ndash;&gt;-->
<!--&lt;!&ndash;              >&ndash;&gt;-->
<!--&lt;!&ndash;                <option value="">Prompt auswählen</option>&ndash;&gt;-->
<!--&lt;!&ndash;                <option v-for="prompt in prompts" :key="prompt.id" :value="prompt.id">&ndash;&gt;-->
<!--&lt;!&ndash;                  {{ prompt.name }}&ndash;&gt;-->
<!--&lt;!&ndash;                </option>&ndash;&gt;-->
<!--&lt;!&ndash;              </select>&ndash;&gt;-->
<!--&lt;!&ndash;              <div v-if="prompts.length === 0" class="text-muted small mt-1">&ndash;&gt;-->
<!--&lt;!&ndash;                Keine gespeicherten Prompts verfügbar&ndash;&gt;-->
<!--&lt;!&ndash;              </div>&ndash;&gt;-->
<!--&lt;!&ndash;            </div>&ndash;&gt;-->

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

<!--&lt;!&ndash;        &lt;!&ndash; Prompt Content (only visible if custom prompt is selected) &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div v-if="!useSavedPrompt" class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label" for="prompt-content">Prompt Inhalt</label>&ndash;&gt;-->
<!--&lt;!&ndash;          <div class="setting-option">&ndash;&gt;-->
<!--&lt;!&ndash;            <textarea&ndash;&gt;-->
<!--&lt;!&ndash;              id="prompt-content"&ndash;&gt;-->
<!--&lt;!&ndash;              class="form-control"&ndash;&gt;-->
<!--&lt;!&ndash;              rows="4"&ndash;&gt;-->
<!--&lt;!&ndash;              v-model="data.content"&ndash;&gt;-->
<!--&lt;!&ndash;              @input="updateNodeData('content', $event.target.value)"&ndash;&gt;-->
<!--&lt;!&ndash;              placeholder="Gib deinen Prompt-Text ein..."&ndash;&gt;-->
<!--&lt;!&ndash;            ></textarea>&ndash;&gt;-->
<!--&lt;!&ndash;          </div>&ndash;&gt;-->
<!--&lt;!&ndash;        </div>&ndash;&gt;-->

<!--&lt;!&ndash;        &lt;!&ndash; LLM Provider Selection &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label" for="prompt-provider">Provider</label>&ndash;&gt;-->
<!--&lt;!&ndash;          <div class="setting-option">&ndash;&gt;-->
<!--&lt;!&ndash;            <select&ndash;&gt;-->
<!--&lt;!&ndash;              id="prompt-provider"&ndash;&gt;-->
<!--&lt;!&ndash;              class="form-select form-select-sm"&ndash;&gt;-->
<!--&lt;!&ndash;              v-model="data.provider"&ndash;&gt;-->
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

<!--&lt;!&ndash;        &lt;!&ndash; Model Selection &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label" for="prompt-model">Model</label>&ndash;&gt;-->
<!--&lt;!&ndash;          <div class="setting-option">&ndash;&gt;-->
<!--&lt;!&ndash;            <select&ndash;&gt;-->
<!--&lt;!&ndash;              id="prompt-model"&ndash;&gt;-->
<!--&lt;!&ndash;              class="form-select form-select-sm"&ndash;&gt;-->
<!--&lt;!&ndash;              v-model="data.model"&ndash;&gt;-->
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

<!--&lt;!&ndash;        &lt;!&ndash; Temperature Slider &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label" for="prompt-temperature">&ndash;&gt;-->
<!--&lt;!&ndash;            Temperature: {{ data.temperature }}&ndash;&gt;-->
<!--&lt;!&ndash;          </label>&ndash;&gt;-->
<!--&lt;!&ndash;          <div class="setting-option">&ndash;&gt;-->
<!--&lt;!&ndash;            <input&ndash;&gt;-->
<!--&lt;!&ndash;              id="prompt-temperature"&ndash;&gt;-->
<!--&lt;!&ndash;              type="range"&ndash;&gt;-->
<!--&lt;!&ndash;              class="form-range"&ndash;&gt;-->
<!--&lt;!&ndash;              min="0"&ndash;&gt;-->
<!--&lt;!&ndash;              max="2"&ndash;&gt;-->
<!--&lt;!&ndash;              step="0.1"&ndash;&gt;-->
<!--&lt;!&ndash;              v-model="data.temperature"&ndash;&gt;-->
<!--&lt;!&ndash;              @change="updateNodeData('temperature', parseFloat($event.target.value))"&ndash;&gt;-->
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
<!--&lt;!&ndash;              v-model="data.max_tokens"&ndash;&gt;-->
<!--&lt;!&ndash;              @change="updateNodeData('max_tokens', parseInt($event.target.value))"&ndash;&gt;-->
<!--&lt;!&ndash;              min="1"&ndash;&gt;-->
<!--&lt;!&ndash;              max="100000"&ndash;&gt;-->
<!--&lt;!&ndash;            />&ndash;&gt;-->
<!--&lt;!&ndash;          </div>&ndash;&gt;-->
<!--&lt;!&ndash;        </div>&ndash;&gt;-->

<!--&lt;!&ndash;        &lt;!&ndash; Output Variable &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label" for="prompt-output-variable">Output Variable</label>&ndash;&gt;-->
<!--&lt;!&ndash;          <div class="setting-option">&ndash;&gt;-->
<!--&lt;!&ndash;            <input&ndash;&gt;-->
<!--&lt;!&ndash;              id="prompt-output-variable"&ndash;&gt;-->
<!--&lt;!&ndash;              type="text"&ndash;&gt;-->
<!--&lt;!&ndash;              class="form-control form-control-sm"&ndash;&gt;-->
<!--&lt;!&ndash;              v-model="data.output_variable"&ndash;&gt;-->
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
<!--&lt;!&ndash;</style>&ndash;&gt;-->


<!--&lt;!&ndash;<script setup>&ndash;&gt;-->
<!--&lt;!&ndash;import { ref, computed, onMounted, watch } from 'vue';&ndash;&gt;-->
<!--&lt;!&ndash;import { Handle, Position, useVueFlow } from '@vue-flow/core';&ndash;&gt;-->

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

<!--&lt;!&ndash;// Get Vue Flow methods&ndash;&gt;-->
<!--&lt;!&ndash;const { findNode, setNodes } = useVueFlow();&ndash;&gt;-->

<!--&lt;!&ndash;// Node configuration&ndash;&gt;-->
<!--&lt;!&ndash;const config = ref({&ndash;&gt;-->
<!--&lt;!&ndash;  showSettings: false,&ndash;&gt;-->
<!--&lt;!&ndash;  availableProviders: [&ndash;&gt;-->
<!--&lt;!&ndash;    { value: 'openai', label: 'OpenAI' },&ndash;&gt;-->
<!--&lt;!&ndash;    { value: 'anthropic', label: 'Anthropic' },&ndash;&gt;-->
<!--&lt;!&ndash;    { value: 'google', label: 'Google' },&ndash;&gt;-->
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
<!--&lt;!&ndash;    ollama: [&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'llama3', label: 'Llama 3' },&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'mistral', label: 'Mistral' },&ndash;&gt;-->
<!--&lt;!&ndash;      { value: 'mixtral', label: 'Mixtral' }&ndash;&gt;-->
<!--&lt;!&ndash;    ]&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Lokale Kopie der Node-Daten, um direkte Bindung zu ermöglichen&ndash;&gt;-->
<!--&lt;!&ndash;const nodeData = ref({&ndash;&gt;-->
<!--&lt;!&ndash;  prompt_id: props.data.prompt_id || null,&ndash;&gt;-->
<!--&lt;!&ndash;  content: props.data.content || '',&ndash;&gt;-->
<!--&lt;!&ndash;  provider: props.data.provider || 'openai',&ndash;&gt;-->
<!--&lt;!&ndash;  model: props.data.model || 'gpt-4-turbo',&ndash;&gt;-->
<!--&lt;!&ndash;  temperature: parseFloat(props.data.temperature || 0.7),&ndash;&gt;-->
<!--&lt;!&ndash;  max_tokens: parseInt(props.data.max_tokens || 1000),&ndash;&gt;-->
<!--&lt;!&ndash;  output_variable: props.data.output_variable || 'result'&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Prompt selection state&ndash;&gt;-->
<!--&lt;!&ndash;const useSavedPrompt = ref(props.data.prompt_id !== null);&ndash;&gt;-->
<!--&lt;!&ndash;const selectedPromptId = ref(props.data.prompt_id || '');&ndash;&gt;-->

<!--&lt;!&ndash;// Get models based on selected provider&ndash;&gt;-->
<!--&lt;!&ndash;const availableModels = computed(() => {&ndash;&gt;-->
<!--&lt;!&ndash;  return config.value.availableModels[nodeData.value.provider] || [];&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Toggle settings panel&ndash;&gt;-->
<!--&lt;!&ndash;const toggleSettings = (event) => {&ndash;&gt;-->
<!--&lt;!&ndash;  // Verhindere Event-Bubbling, damit der Node nicht verschoben wird&ndash;&gt;-->
<!--&lt;!&ndash;  event.stopPropagation();&ndash;&gt;-->
<!--&lt;!&ndash;  config.value.showSettings = !config.value.showSettings;&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Update node data&ndash;&gt;-->
<!--&lt;!&ndash;const updateNodeData = (key, value) => {&ndash;&gt;-->
<!--&lt;!&ndash;  // Aktualisiere die lokale Kopie&ndash;&gt;-->
<!--&lt;!&ndash;  nodeData.value[key] = value;&ndash;&gt;-->

<!--&lt;!&ndash;  const node = findNode(props.id);&ndash;&gt;-->
<!--&lt;!&ndash;  if (!node) return;&ndash;&gt;-->

<!--&lt;!&ndash;  const newNode = {&ndash;&gt;-->
<!--&lt;!&ndash;    ...node,&ndash;&gt;-->
<!--&lt;!&ndash;    data: {&ndash;&gt;-->
<!--&lt;!&ndash;      ...node.data,&ndash;&gt;-->
<!--&lt;!&ndash;      [key]: value&ndash;&gt;-->
<!--&lt;!&ndash;    }&ndash;&gt;-->
<!--&lt;!&ndash;  };&ndash;&gt;-->

<!--&lt;!&ndash;  setNodes((nodes) => nodes.map(n => n.id === props.id ? newNode : n));&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// When provider changes, update model to the first available model&ndash;&gt;-->
<!--&lt;!&ndash;const onProviderChange = (provider) => {&ndash;&gt;-->
<!--&lt;!&ndash;  updateNodeData('provider', provider);&ndash;&gt;-->

<!--&lt;!&ndash;  const models = config.value.availableModels[provider] || [];&ndash;&gt;-->
<!--&lt;!&ndash;  if (models.length > 0) {&ndash;&gt;-->
<!--&lt;!&ndash;    updateNodeData('model', models[0].value);&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Method to select a prompt&ndash;&gt;-->
<!--&lt;!&ndash;const selectPrompt = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  updateNodeData('prompt_id', selectedPromptId.value);&ndash;&gt;-->

<!--&lt;!&ndash;  // If there's a selected prompt, fetch its content&ndash;&gt;-->
<!--&lt;!&ndash;  if (selectedPromptId.value) {&ndash;&gt;-->
<!--&lt;!&ndash;    const selectedPrompt = props.prompts.find(p => p.id == selectedPromptId.value);&ndash;&gt;-->
<!--&lt;!&ndash;    if (selectedPrompt) {&ndash;&gt;-->
<!--&lt;!&ndash;      updateNodeData('content', selectedPrompt.content);&ndash;&gt;-->
<!--&lt;!&ndash;    }&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Methode für Temperatur-Änderung&ndash;&gt;-->
<!--&lt;!&ndash;const onTemperatureChange = (event) => {&ndash;&gt;-->
<!--&lt;!&ndash;  const value = parseFloat(event.target.value);&ndash;&gt;-->
<!--&lt;!&ndash;  updateNodeData('temperature', value);&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Watch for changes in the useSavedPrompt value&ndash;&gt;-->
<!--&lt;!&ndash;watch(useSavedPrompt, (newValue) => {&ndash;&gt;-->
<!--&lt;!&ndash;  if (!newValue) {&ndash;&gt;-->
<!--&lt;!&ndash;    // If switching to custom prompt, clear the prompt_id&ndash;&gt;-->
<!--&lt;!&ndash;    updateNodeData('prompt_id', null);&ndash;&gt;-->
<!--&lt;!&ndash;  } else {&ndash;&gt;-->
<!--&lt;!&ndash;    // If switching to saved prompt, but no prompt selected yet&ndash;&gt;-->
<!--&lt;!&ndash;    if (!selectedPromptId.value && props.prompts.length > 0) {&ndash;&gt;-->
<!--&lt;!&ndash;      // Pre-select the first prompt&ndash;&gt;-->
<!--&lt;!&ndash;      selectedPromptId.value = props.prompts[0].id;&ndash;&gt;-->
<!--&lt;!&ndash;      selectPrompt();&ndash;&gt;-->
<!--&lt;!&ndash;    }&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Verhindert Klick-Propagation bei Interaktion mit Steuerelementen&ndash;&gt;-->
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

<!--&lt;!&ndash;    &lt;!&ndash; Node Content &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;    <div class="prompt-node-content">&ndash;&gt;-->
<!--&lt;!&ndash;      <div class="prompt-node-header">&ndash;&gt;-->
<!--&lt;!&ndash;        <i class="fa fa-file-alt text-primary me-1"></i>&ndash;&gt;-->
<!--&lt;!&ndash;        <span class="prompt-node-label">{{ data.label }}</span>&ndash;&gt;-->
<!--&lt;!&ndash;        <button&ndash;&gt;-->
<!--&lt;!&ndash;          type="button"&ndash;&gt;-->
<!--&lt;!&ndash;          class="prompt-node-toggle"&ndash;&gt;-->
<!--&lt;!&ndash;          @click="toggleSettings"&ndash;&gt;-->
<!--&lt;!&ndash;          :title="config.showSettings ? 'Hide settings' : 'Show settings'"&ndash;&gt;-->
<!--&lt;!&ndash;        >&ndash;&gt;-->
<!--&lt;!&ndash;          <i :class="['fa', config.showSettings ? 'fa-chevron-up' : 'fa-chevron-down']"></i>&ndash;&gt;-->
<!--&lt;!&ndash;        </button>&ndash;&gt;-->
<!--&lt;!&ndash;      </div>&ndash;&gt;-->

<!--&lt;!&ndash;      &lt;!&ndash; Setting Panel &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;      <div v-if="config.showSettings" class="prompt-node-settings" @mousedown="preventDrag" @click="preventDrag">&ndash;&gt;-->
<!--&lt;!&ndash;        &lt;!&ndash; Prompt Selection &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label">Prompt Source</label>&ndash;&gt;-->
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
<!--&lt;!&ndash;            <div v-if="useSavedPrompt" class="mt-2">&ndash;&gt;-->
<!--&lt;!&ndash;              <select&ndash;&gt;-->
<!--&lt;!&ndash;                v-model="selectedPromptId"&ndash;&gt;-->
<!--&lt;!&ndash;                class="form-select form-select-sm"&ndash;&gt;-->
<!--&lt;!&ndash;                @change="selectPrompt"&ndash;&gt;-->
<!--&lt;!&ndash;              >&ndash;&gt;-->
<!--&lt;!&ndash;                <option value="">Prompt auswählen</option>&ndash;&gt;-->
<!--&lt;!&ndash;                <option v-for="prompt in prompts" :key="prompt.id" :value="prompt.id">&ndash;&gt;-->
<!--&lt;!&ndash;                  {{ prompt.name }}&ndash;&gt;-->
<!--&lt;!&ndash;                </option>&ndash;&gt;-->
<!--&lt;!&ndash;              </select>&ndash;&gt;-->
<!--&lt;!&ndash;              <div v-if="prompts.length === 0" class="text-muted small mt-1">&ndash;&gt;-->
<!--&lt;!&ndash;                Keine gespeicherten Prompts verfügbar&ndash;&gt;-->
<!--&lt;!&ndash;              </div>&ndash;&gt;-->
<!--&lt;!&ndash;            </div>&ndash;&gt;-->

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

<!--&lt;!&ndash;        &lt;!&ndash; Prompt Content (only visible if custom prompt is selected) &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div v-if="!useSavedPrompt" class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label" for="prompt-content">Prompt Inhalt</label>&ndash;&gt;-->
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

<!--&lt;!&ndash;        &lt;!&ndash; LLM Provider Selection &ndash;&gt;&ndash;&gt;-->
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

<!--&lt;!&ndash;        &lt;!&ndash; Model Selection &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label" for="prompt-model">Model</label>&ndash;&gt;-->
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

<!--&lt;!&ndash;        &lt;!&ndash; Temperature Slider &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label" for="prompt-temperature">&ndash;&gt;-->
<!--&lt;!&ndash;            Temperature: {{ nodeData.temperature }}&ndash;&gt;-->
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
<!--&lt;!&ndash;              @change="onTemperatureChange"&ndash;&gt;-->
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

<!--&lt;!&ndash;        &lt;!&ndash; Output Variable &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        <div class="setting-group">&ndash;&gt;-->
<!--&lt;!&ndash;          <label class="setting-label" for="prompt-output-variable">Output Variable</label>&ndash;&gt;-->
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

<!--&lt;!&ndash;/* Make controls not draggable */&ndash;&gt;-->
<!--&lt;!&ndash;.prompt-node input,&ndash;&gt;-->
<!--&lt;!&ndash;.prompt-node select,&ndash;&gt;-->
<!--&lt;!&ndash;.prompt-node textarea,&ndash;&gt;-->
<!--&lt;!&ndash;.prompt-node button {&ndash;&gt;-->
<!--&lt;!&ndash;  pointer-events: auto;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->
<!--&lt;!&ndash;</style>&ndash;&gt;-->

<!--<script setup>-->
<!--import { ref, computed, onMounted, watch } from 'vue';-->
<!--import { Handle, Position, useVueFlow } from '@vue-flow/core';-->

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

<!--// Get Vue Flow methods-->
<!--const { findNode, setNodes } = useVueFlow();-->

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
<!--const useSavedPrompt = ref(props.data.prompt_id !== null);-->
<!--const selectedPromptId = ref(props.data.prompt_id || '');-->

<!--// Get models based on selected provider-->
<!--const availableModels = computed(() => {-->
<!--  return config.value.availableModels[nodeData.value.provider] || [];-->
<!--});-->

<!--// Computed property für den ausgewählten Prompt-->
<!--const selectedPromptContent = computed(() => {-->
<!--  if (!selectedPromptId.value) return '';-->

<!--  const selectedPrompt = props.prompts.find(p => p.id == selectedPromptId.value);-->
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

<!--  const node = findNode(props.id);-->
<!--  if (!node) return;-->

<!--  const newNode = {-->
<!--    ...node,-->
<!--    data: {-->
<!--      ...node.data,-->
<!--      [key]: value-->
<!--    }-->
<!--  };-->

<!--  setNodes((nodes) => nodes.map(n => n.id === props.id ? newNode : n));-->
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
<!--    const selectedPrompt = props.prompts.find(p => p.id == selectedPromptId.value);-->
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
<!--    if (!selectedPromptId.value && props.prompts.length > 0) {-->
<!--      // Pre-select the first prompt-->
<!--      selectedPromptId.value = props.prompts[0].id;-->
<!--      selectPrompt();-->
<!--    }-->
<!--  }-->
<!--});-->

<!--// Initialisierung und Laden des Prompt-Inhalts, wenn ein Prompt-ID existiert-->
<!--onMounted(() => {-->
<!--  if (props.data.prompt_id) {-->
<!--    selectedPromptId.value = props.data.prompt_id;-->
<!--    const selectedPrompt = props.prompts.find(p => p.id == props.data.prompt_id);-->
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
<!--watch(() => props.prompts, (newPrompts) => {-->
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
<!--                <option v-for="prompt in prompts" :key="prompt.id" :value="prompt.id">-->
<!--                  {{ prompt.name }}-->
<!--                </option>-->
<!--              </select>-->
<!--              <div v-if="prompts.length === 0" class="text-muted small mt-1">-->
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

// Zugriff auf die Prompts, die im data-Objekt übergeben werden
const promptsList = computed(() => {
  console.log("Verfügbare Prompts:", props.prompts);
  return props.prompts || [];
});


// Node configuration
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

// Emit-Funktion, um Änderungen an den Workflow-Editor zu senden
const emit = defineEmits(['update:data']);

// Lokale Kopie der Node-Daten, um direkte Bindung zu ermöglichen
const nodeData = ref({
  prompt_id: props.data.prompt_id || null,
  content: props.data.content || '',
  provider: props.data.provider || 'openai',
  model: props.data.model || 'gpt-4-turbo',
  temperature: parseFloat(props.data.temperature || 0.7),
  max_tokens: parseInt(props.data.max_tokens || 1000),
  output_variable: props.data.output_variable || 'result'
});

// Prompt selection state
const useSavedPrompt = ref(props.data.prompt_id !== null);
const selectedPromptId = ref(props.data.prompt_id || '');

// Get models based on selected provider
const availableModels = computed(() => {
  return config.value.availableModels[nodeData.value.provider] || [];
});

// Computed property für den ausgewählten Prompt
const selectedPromptContent = computed(() => {
  if (!selectedPromptId.value) return '';

  const selectedPrompt = promptsList.value.find(p => p.id == selectedPromptId.value);
  console.log("Selected prompt:", selectedPrompt);
  return selectedPrompt ? selectedPrompt.content : '';
});

// Toggle settings panel
const toggleSettings = (event) => {
  // Verhindere Event-Bubbling, damit der Node nicht verschoben wird
  event.stopPropagation();
  config.value.showSettings = !config.value.showSettings;
};

// Update node data
const updateNodeData = (key, value) => {
  // Aktualisiere die lokale Kopie
  nodeData.value[key] = value;

  // Sendet die aktualisierten Daten an den übergeordneten Workflow-Editor
  emit('update:data', { ...props.data, [key]: value });
};

// When provider changes, update model to the first available model
const onProviderChange = (provider) => {
  updateNodeData('provider', provider);

  const models = config.value.availableModels[provider] || [];
  if (models.length > 0) {
    updateNodeData('model', models[0].value);
  }
};

// Method to select a prompt
const selectPrompt = () => {
  updateNodeData('prompt_id', selectedPromptId.value);

  // If there's a selected prompt, fetch its content
  if (selectedPromptId.value) {
    const selectedPrompt = promptsList.value.find(p => p.id == selectedPromptId.value);
    if (selectedPrompt) {
      updateNodeData('content', selectedPrompt.content);
    }
  }
};

// Methode für Temperatur-Änderung
const onTemperatureChange = (event) => {
  const value = parseFloat(event.target.value);
  updateNodeData('temperature', value);
};

// Watch for changes in the useSavedPrompt value
watch(useSavedPrompt, (newValue) => {
  if (!newValue) {
    // If switching to custom prompt, clear the prompt_id
    updateNodeData('prompt_id', null);
  } else {
    // If switching to saved prompt, but no prompt selected yet
    if (!selectedPromptId.value && promptsList.value.length > 0) {
      // Pre-select the first prompt
      selectedPromptId.value = promptsList.value[0].id;
      selectPrompt();
    }
  }
});

// Initialisierung und Laden des Prompt-Inhalts, wenn ein Prompt-ID existiert
onMounted(() => {
  console.log("Node mounted. Available prompts:", promptsList.value);
  if (props.data.prompt_id) {
    selectedPromptId.value = props.data.prompt_id;
    const selectedPrompt = promptsList.value.find(p => p.id == props.data.prompt_id);
    if (selectedPrompt) {
      updateNodeData('content', selectedPrompt.content);
    }
  }
});

// Watch für Änderungen an der Prompt-Auswahl
watch(selectedPromptId, (newValue) => {
  if (newValue) {
    selectPrompt();
  }
});

// Watch für Änderungen an den verfügbaren Prompts (falls diese nachgeladen werden)
watch(promptsList, (newPrompts) => {
  console.log("Prompts list changed:", newPrompts);
  if (selectedPromptId.value) {
    const selectedPrompt = newPrompts.find(p => p.id == selectedPromptId.value);
    if (selectedPrompt) {
      updateNodeData('content', selectedPrompt.content);
    }
  }
}, { deep: true });

// Verhindert Klick-Propagation bei Interaktion mit Steuerelementen
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

    <!-- Node Content -->
    <div class="prompt-node-content">
      <div class="prompt-node-header">
        <i class="fa fa-file-alt text-primary me-1"></i>
        <span class="prompt-node-label">{{ data.label }}</span>
        <button
          type="button"
          class="prompt-node-toggle"
          @click="toggleSettings"
          :title="config.showSettings ? 'Hide settings' : 'Show settings'"
        >
          <i :class="['fa', config.showSettings ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
        </button>
      </div>

      <!-- Setting Panel -->
      <div v-if="config.showSettings" class="prompt-node-settings" @mousedown="preventDrag" @click="preventDrag">
        <!-- Debug Info (nur während der Entwicklung) -->
        <div class="setting-group bg-light p-2 mb-2">
          <small>Debug Info: {{ promptsList.length }} Prompts verfügbar</small>
        </div>

        <!-- Prompt Selection -->
        <div class="setting-group">
          <label class="setting-label">Prompt Source</label>
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
                <option v-for="prompt in promptsList" :key="prompt.id" :value="prompt.id">
                  {{ prompt.name }}
                </option>
              </select>
              <div v-if="promptsList.length === 0" class="text-muted small mt-1">
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

        <!-- Prompt Content (only visible if custom prompt is selected) -->
        <div v-if="!useSavedPrompt" class="setting-group">
          <label class="setting-label" for="prompt-content">Prompt Inhalt</label>
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

        <!-- LLM Provider Selection -->
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

        <!-- Model Selection -->
        <div class="setting-group">
          <label class="setting-label" for="prompt-model">Model</label>
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

        <!-- Temperature Slider -->
        <div class="setting-group">
          <label class="setting-label" for="prompt-temperature">
            Temperature: {{ nodeData.temperature }}
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
              @change="onTemperatureChange"
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

        <!-- Output Variable -->
        <div class="setting-group">
          <label class="setting-label" for="prompt-output-variable">Output Variable</label>
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

/* Make controls not draggable */
.prompt-node input,
.prompt-node select,
.prompt-node textarea,
.prompt-node button {
  pointer-events: auto;
}
</style>
