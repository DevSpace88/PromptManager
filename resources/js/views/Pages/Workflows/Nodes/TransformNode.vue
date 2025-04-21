// resources/js/views/Pages/Workflows/Nodes/TransformNode.vue
<script setup>
import { ref, computed } from 'vue';
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
const { findNode, setNodes, getNodes } = useVueFlow();

// Node configuration
const config = ref({
  showSettings: false,
  transformations: [
    { value: 'json_parse', label: 'Parse JSON' },
    { value: 'json_stringify', label: 'Stringify JSON' },
    { value: 'to_uppercase', label: 'To Uppercase' },
    { value: 'to_lowercase', label: 'To Lowercase' },
    { value: 'trim', label: 'Trim Whitespace' },
    { value: 'extract_text', label: 'Extract Text (Regex)' },
    { value: 'custom_code', label: 'Custom JavaScript' },
  ]
});

// Available variables in the workflow
const availableVariables = computed(() => {
  // Get all nodes in the workflow
  const nodes = getNodes.value;
  const variables = [];

  // Extract variables from nodes
  nodes.forEach(node => {
    if (node.data) {
      // From input nodes
      if (node.data.type === 'input' && node.data.variable) {
        variables.push(node.data.variable);
      }

      // From prompt nodes
      if (node.data.type === 'prompt' && node.data.output_variable) {
        variables.push(node.data.output_variable);
      }

      // From API nodes
      if (node.data.type === 'api' && node.data.output_variable) {
        variables.push(node.data.output_variable);
      }

      // From transform nodes (except this one)
      if (node.data.type === 'transform' && node.data.output_variable && node.id !== props.id) {
        variables.push(node.data.output_variable);
      }
    }
  });

  // Return unique variables
  return [...new Set(variables)];
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

// Get transformation info
const getTransformationInfo = (transformationType) => {
  const info = config.value.transformations.find(t => t.value === transformationType);
  return info || { value: transformationType, label: transformationType };
};

// Show specific fields based on transformation type
const showRegexField = computed(() => props.data.transformation === 'extract_text');
const showCodeField = computed(() => props.data.transformation === 'custom_code');
</script>

<template>
  <div :class="['transform-node', { 'selected': selected }]">
    <!-- Target Handle (Input) -->
    <Handle
      type="target"
      position="top"
      :id="id + '-target'"
      :connectable="isConnectable"
    />

    <!-- Source Handle (Output) -->
    <Handle
      type="source"
      position="bottom"
      :id="id + '-source'"
      :connectable="isConnectable"
    />

    <!-- Node Content -->
    <div class="transform-node-content">
      <div class="transform-node-header">
        <i class="fa fa-exchange-alt text-secondary me-1"></i>
        <span class="transform-node-label">{{ data.label }}</span>
        <button
          type="button"
          class="transform-node-toggle"
          @click="toggleSettings"
          :title="config.showSettings ? 'Hide settings' : 'Show settings'"
        >
          <i :class="['fa', config.showSettings ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
        </button>
      </div>

      <!-- Setting Panel -->
      <div v-if="config.showSettings" class="transform-node-settings">
        <!-- Input Variable -->
        <div class="setting-group">
          <label class="setting-label">Input Variable</label>
          <div class="setting-option">
            <select
              class="form-select form-select-sm"
              v-model="data.input_variable"
              @change="updateNodeData('input_variable', data.input_variable)"
            >
              <option value="">Select input variable</option>
              <option v-for="variable in availableVariables" :key="variable" :value="variable">
                {{ variable }}
              </option>
            </select>
          </div>
          <div class="setting-help">Select the variable to transform</div>
        </div>

        <!-- Transformation Type -->
        <div class="setting-group">
          <label class="setting-label">Transformation</label>
          <div class="setting-option">
            <select
              class="form-select form-select-sm"
              v-model="data.transformation"
              @change="updateNodeData('transformation', data.transformation)"
            >
              <option v-for="transform in config.transformations" :key="transform.value" :value="transform.value">
                {{ transform.label }}
              </option>
            </select>
          </div>
        </div>

        <!-- Regex Pattern (for extract_text) -->
        <div v-if="showRegexField" class="setting-group">
          <label class="setting-label">Regular Expression</label>
          <div class="setting-option">
            <input
              type="text"
              class="form-control form-control-sm"
              v-model="data.regex"
              placeholder="e.g., /pattern(.*?)pattern/i"
              @change="updateNodeData('regex', data.regex)"
            />
          </div>
          <div class="setting-help">Use a capture group () to extract specific text</div>
        </div>

        <!-- Custom Code (for custom_code) -->
        <div v-if="showCodeField" class="setting-group">
          <label class="setting-label">Custom JavaScript</label>
          <div class="setting-option">
            <textarea
              class="form-control form-control-sm"
              v-model="data.code"
              rows="4"
              placeholder="// input contains the variable value
// return the transformed result
return input.toString();"
              @change="updateNodeData('code', data.code)"
            ></textarea>
          </div>
          <div class="setting-help">Write JavaScript code to transform the input value</div>
        </div>

        <!-- Output Variable -->
        <div class="setting-group">
          <label class="setting-label">Output Variable</label>
          <div class="setting-option">
            <input
              type="text"
              class="form-control form-control-sm"
              v-model="data.output_variable"
              placeholder="transformed_result"
              @change="updateNodeData('output_variable', data.output_variable)"
            />
          </div>
          <div class="setting-help">Variable name to store the transformation result</div>
        </div>
      </div>

      <!-- Node Summary (when collapsed) -->
      <div v-else class="transform-node-summary">
        <div class="transform-flow">
          <span class="transform-input">{{ data.input_variable || '?' }}</span>
          <span class="transform-arrow">→</span>
          <span class="transform-type">{{ getTransformationInfo(data.transformation).label }}</span>
          <span class="transform-arrow">→</span>
          <span class="transform-output">{{ data.output_variable || '?' }}</span>
        </div>

        <div v-if="showRegexField" class="transform-details">
          Regex: {{ data.regex || 'Not set' }}
        </div>

        <div v-if="showCodeField" class="transform-details">
          Custom code: {{ data.code ? 'Set' : 'Not set' }}
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.transform-node {
  min-width: 220px;
  max-width: 280px;
  background-color: #ffffff;
  border: 2px solid #6c757d;
  border-radius: 5px;
  font-size: 12px;
  overflow: hidden;
}

.transform-node.selected {
  box-shadow: 0 0 0 2px #6c757d;
}

.transform-node-content {
  padding: 5px;
}

.transform-node-header {
  display: flex;
  align-items: center;
  font-weight: bold;
  padding: 4px;
  background-color: rgba(108, 117, 125, 0.1);
  border-bottom: 1px solid rgba(108, 117, 125, 0.2);
}

.transform-node-label {
  flex-grow: 1;
  margin-right: 5px;
}

.transform-node-toggle {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  width: 16px;
  height: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #6c757d;
}

.transform-node-settings {
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
  margin-bottom: 2px;
}

.setting-help {
  font-size: 10px;
  color: #6c757d;
  font-style: italic;
}

.transform-node-summary {
  padding: 5px;
  font-size: 11px;
}

.transform-flow {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 4px;
}

.transform-input,
.transform-output {
  font-weight: 500;
  color: #6c757d;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 60px;
}

.transform-arrow {
  color: #adb5bd;
  font-size: 10px;
}

.transform-type {
  background-color: rgba(108, 117, 125, 0.1);
  color: #6c757d;
  font-size: 10px;
  padding: 1px 4px;
  border-radius: 3px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 80px;
  text-align: center;
}

.transform-details {
  font-size: 10px;
  color: #6c757d;
  margin-top: 4px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  font-style: italic;
}
</style>
