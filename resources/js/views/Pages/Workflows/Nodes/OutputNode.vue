// resources/js/views/Pages/Workflows/Nodes/OutputNode.vue
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
  newVariable: ''
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

      // From transform nodes
      if (node.data.type === 'transform' && node.data.output_variable) {
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

// Add variable to output
const addVariable = () => {
  if (!config.value.newVariable) return;

  const variables = [...(props.data.variables || [])];

  if (!variables.includes(config.value.newVariable)) {
    variables.push(config.value.newVariable);
    updateNodeData('variables', variables);
  }

  config.value.newVariable = '';
};

// Remove variable from output
const removeVariable = (variable) => {
  const variables = [...(props.data.variables || [])];
  const index = variables.indexOf(variable);

  if (index !== -1) {
    variables.splice(index, 1);
    updateNodeData('variables', variables);
  }
};
</script>

<template>
  <div :class="['output-node', { 'selected': selected }]">
    <!-- Target Handle (Input) -->
    <Handle
      type="target"
      position="top"
      :id="id + '-target'"
      :connectable="isConnectable"
    />

    <!-- Node Content -->
    <div class="output-node-content">
      <div class="output-node-header">
        <i class="fa fa-sign-out-alt text-success me-1"></i>
        <span class="output-node-label">{{ data.label }}</span>
        <button
          type="button"
          class="output-node-toggle"
          @click="toggleSettings"
          :title="config.showSettings ? 'Hide settings' : 'Show settings'"
        >
          <i :class="['fa', config.showSettings ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
        </button>
      </div>

      <!-- Setting Panel -->
      <div v-if="config.showSettings" class="output-node-settings">
        <!-- Output Variables -->
        <div class="setting-group">
          <label class="setting-label">Output Variables</label>
          <div class="setting-option">
            <div class="output-variables-list">
              <div
                v-for="variable in data.variables"
                :key="variable"
                class="output-variable-item"
              >
                <span class="output-variable-name">{{ variable }}</span>
                <button
                  type="button"
                  class="output-variable-remove"
                  @click="removeVariable(variable)"
                  title="Remove variable"
                >
                  <i class="fa fa-times"></i>
                </button>
              </div>
              <div v-if="!data.variables || data.variables.length === 0" class="output-no-variables">
                No output variables selected
              </div>
            </div>
          </div>

          <!-- Add Variable -->
          <div class="setting-option mt-2">
            <div class="input-group input-group-sm">
              <select
                class="form-select form-select-sm"
                v-model="config.newVariable"
              >
                <option value="">Select a variable</option>
                <option
                  v-for="variable in availableVariables"
                  :key="variable"
                  :value="variable"
                >
                  {{ variable }}
                </option>
              </select>
              <button
                type="button"
                class="btn btn-sm btn-outline-success"
                @click="addVariable"
                :disabled="!config.newVariable"
              >
                <i class="fa fa-plus"></i>
              </button>
            </div>
          </div>

          <div class="setting-help">These variables will be included in the workflow result</div>
        </div>

        <!-- Format Options -->
        <div class="setting-group">
          <label class="setting-label">Output Format</label>
          <div class="setting-option">
            <select
              class="form-select form-select-sm"
              v-model="data.format"
              @change="updateNodeData('format', data.format)"
            >
              <option value="json">JSON</option>
              <option value="text">Plain Text</option>
              <option value="markdown">Markdown</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Node Summary (when collapsed) -->
      <div v-else class="output-node-summary">
        <div class="output-variables">
          <span v-for="(variable, index) in data.variables" :key="variable">
            {{ variable }}{{ index < data.variables.length - 1 ? ', ' : '' }}
          </span>
          <span v-if="!data.variables || data.variables.length === 0" class="text-muted">No variables</span>
        </div>
        <div class="output-format">Format: {{ data.format || 'JSON' }}</div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.output-node {
  min-width: 180px;
  max-width: 240px;
  background-color: #ffffff;
  border: 2px solid #42b983;
  border-radius: 5px;
  font-size: 12px;
  overflow: hidden;
}

.output-node.selected {
  box-shadow: 0 0 0 2px #42b983;
}

.output-node-content {
  padding: 5px;
}

.output-node-header {
  display: flex;
  align-items: center;
  font-weight: bold;
  padding: 4px;
  background-color: rgba(66, 185, 131, 0.1);
  border-bottom: 1px solid rgba(66, 185, 131, 0.2);
}

.output-node-label {
  flex-grow: 1;
  margin-right: 5px;
}

.output-node-toggle {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  width: 16px;
  height: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #42b983;
}

.output-node-settings {
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

.output-variables-list {
  max-height: 100px;
  overflow-y: auto;
  border: 1px solid #dee2e6;
  border-radius: 3px;
  padding: 4px;
  background-color: #f8f9fa;
}

.output-variable-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 2px 4px;
  border-radius: 3px;
  margin-bottom: 2px;
  background-color: rgba(66, 185, 131, 0.1);
}

.output-variable-name {
  font-weight: 500;
  color: #42b983;
}

.output-variable-remove {
  background: none;
  border: none;
  padding: 0;
  width: 14px;
  height: 14px;
  font-size: 8px;
  color: #dc3545;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
}

.output-no-variables {
  font-style: italic;
  color: #6c757d;
  font-size: 10px;
  padding: 2px 4px;
}

.output-node-summary {
  padding: 5px;
  font-size: 11px;
  color: #6c757d;
}

.output-variables {
  font-weight: 500;
  color: #42b983;
  word-break: break-all;
}

.output-format {
  font-size: 10px;
  margin-top: 3px;
}
</style>
