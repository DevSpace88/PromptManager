// resources/js/views/Pages/Workflows/Nodes/PromptNode.vue
<script setup>
import { ref, computed, onMounted } from 'vue';
import { Handle, Position, useVueFlow } from '@vue-flow/core';

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
  }
});

// Get Vue Flow methods
const { findNode, setNodes } = useVueFlow();

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

// Get models based on selected provider
const availableModels = computed(() => {
  return config.value.availableModels[props.data.provider] || [];
});

// Toggle settings panel
const toggleSettings = () => {
  config.value.showSettings = !config.value.showSettings;
};

// Update node data
const updateNodeData = (key, value) => {
  const node = findNode(props.id);
  if (!node) return;

  const newNode = {
    ...node,
    data: {
      ...node.data,
      [key]: value
    }
  };

  setNodes((nodes) => nodes.map(n => n.id === props.id ? newNode : n));
};

// When provider changes, update model to the first available model
const onProviderChange = (provider) => {
  updateNodeData('provider', provider);

  const models = config.value.availableModels[provider] || [];
  if (models.length > 0) {
    updateNodeData('model', models[0].value);
  }
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
      <div v-if="config.showSettings" class="prompt-node-settings">
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
                :checked="data.prompt_id !== null"
                @change="updateNodeData('prompt_id', data.prompt_id || '')"
              />
              <label class="form-check-label" for="prompt-source-saved">Use saved prompt</label>
            </div>
            <div class="form-check">
              <input
                type="radio"
                id="prompt-source-custom"
                class="form-check-input"
                :value="false"
                :checked="data.prompt_id === null"
                @change="updateNodeData('prompt_id', null)"
              />
              <label class="form-check-label" for="prompt-source-custom">Custom prompt</label>
            </div>
          </div>
        </div>

        <!-- Prompt Content (if custom) -->
        <div v-if="data.prompt_id === null" class="setting-group">
          <label class="setting-label">Prompt</label>
          <div class="setting-option">
            <textarea
              class="form-control form-control-sm"
              v-model="data.content"
              rows="3"
              placeholder="Enter prompt text with {{variables}}"
              @change="updateNodeData('content', data.content)"
            ></textarea>
          </div>
        </div>

        <!-- Provider Selection -->
        <div class="setting-group">
          <label class="setting-label">Provider</label>
          <div class="setting-option">
            <select
              class="form-select form-select-sm"
              v-model="data.provider"
              @change="onProviderChange(data.provider)"
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
          <label class="setting-label">Model</label>
          <div class="setting-option">
            <select
              class="form-select form-select-sm"
              v-model="data.model"
              @change="updateNodeData('model', data.model)"
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

        <!-- Temperature -->
        <div class="setting-group">
          <label class="setting-label">Temperature</label>
          <div class="setting-option d-flex align-items-center gap-2">
            <input
              type="range"
              class="form-range"
              min="0"
              max="2"
              step="0.1"
              v-model.number="data.temperature"
              @change="updateNodeData('temperature', data.temperature)"
            >
            <span class="badge bg-primary">{{ data.temperature }}</span>
          </div>
        </div>

        <!-- Max Tokens -->
        <div class="setting-group">
          <label class="setting-label">Max Tokens</label>
          <div class="setting-option">
            <input
              type="number"
              class="form-control form-control-sm"
              v-model.number="data.max_tokens"
              min="1"
              max="8192"
              @change="updateNodeData('max_tokens', data.max_tokens)"
            >
          </div>
        </div>

        <!-- Output Variable -->
        <div class="setting-group">
          <label class="setting-label">Save To</label>
          <div class="setting-option">
            <input
              type="text"
              class="form-control form-control-sm"
              v-model="data.output_variable"
              placeholder="Variable name"
              @change="updateNodeData('output_variable', data.output_variable)"
            >
          </div>
        </div>
      </div>

      <!-- Node Summary (when collapsed) -->
      <div v-else class="prompt-node-summary">
        <div v-if="data.prompt_id">Using saved prompt</div>
        <div v-else class="prompt-content-preview">{{ (data.content || '').substring(0, 30) }}{{ (data.content || '').length > 30 ? '...' : '' }}</div>
        <div class="prompt-node-model">{{ data.provider }} / {{ data.model }}</div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.prompt-node {
  min-width: 200px;
  max-width: 300px;
  background-color: #ffffff;
  border: 2px solid #5c80d1;
  border-radius: 5px;
  font-size: 12px;
  overflow: hidden;
}

.prompt-node.selected {
  box-shadow: 0 0 0 2px #5c80d1;
}

.prompt-node-content {
  padding: 5px;
}

.prompt-node-header {
  display: flex;
  align-items: center;
  font-weight: bold;
  padding: 4px;
  background-color: rgba(92, 128, 209, 0.1);
  border-bottom: 1px solid rgba(92, 128, 209, 0.2);
}

.prompt-node-label {
  flex-grow: 1;
  margin-right: 5px;
}

.prompt-node-toggle {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  width: 16px;
  height: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #5c80d1;
}

.prompt-node-settings {
  padding: 8px;
  font-size: 11px;
}

.setting-group {
  margin-bottom: 8px;
}

.setting-label {
  font-weight: 600;
  margin-bottom: 2px;
  font-size: 10px;
  text-transform: uppercase;
  color: #6c757d;
}

.setting-option {
  margin-bottom: 5px;
}

.prompt-node-summary {
  padding: 5px;
  font-size: 11px;
  color: #6c757d;
}

.prompt-content-preview {
  font-style: italic;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.prompt-node-model {
  font-size: 10px;
  color: #5c80d1;
  margin-top: 3px;
}
</style>
