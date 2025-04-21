// resources/js/views/Pages/Workflows/Nodes/ConditionNode.vue
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
  showSettings: false
});

// Toggle settings panel
const toggleSettings = () => {
  config.value.showSettings = !config.value.showSettings;
};

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

// Insert variable into condition
const insertVariable = (variable) => {
  const currentCondition = props.data.condition || '';
  updateNodeData('condition', currentCondition + ' {{' + variable + '}} ');
};

// Condition examples for quick start
const conditionExamples = [
  {
    label: 'Contains text',
    condition: '{{variable}}.toLowerCase().includes("text")'
  },
  {
    label: 'Equals value',
    condition: '{{variable}} === "value"'
  },
  {
    label: 'Greater than',
    condition: 'parseFloat({{variable}}) > 10'
  },
  {
    label: 'Is empty',
    condition: '!{{variable}} || {{variable}}.trim() === ""'
  },
  {
    label: 'JSON has property',
    condition: 'typeof JSON.parse({{variable}}).property !== "undefined"'
  }
];

// Apply a condition example
const applyExample = (example) => {
  updateNodeData('condition', example.condition);
};
</script>

<template>
  <div :class="['condition-node', { 'selected': selected }]">
    <!-- Target Handle (Input) -->
    <Handle
      type="target"
      position="top"
      :id="id + '-target'"
      :connectable="isConnectable"
    />

    <!-- Source Handle (True output) -->
    <Handle
      type="source"
      position="bottom"
      :id="id + '-source-true'"
      :connectable="isConnectable"
      class="source-true"
    />

    <!-- Source Handle (False output) -->
    <Handle
      type="source"
      position="right"
      :id="id + '-source-false'"
      :connectable="isConnectable"
      class="source-false"
    />

    <!-- Node Content -->
    <div class="condition-node-content">
      <div class="condition-node-header node-drag-handle
">
        <i class="fa fa-code-branch text-warning me-1"></i>
        <span class="condition-node-label">{{ data.label }}</span>
        <button
          type="button"
          class="condition-node-toggle"
          @click="toggleSettings"
          :title="config.showSettings ? 'Hide settings' : 'Show settings'"
        >
          <i :class="['fa', config.showSettings ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
        </button>
      </div>

      <!-- Setting Panel -->
      <div v-if="config.showSettings" class="condition-node-settings">
        <!-- Condition Expression -->
        <div class="setting-group">
          <label class="setting-label">Condition Expression</label>
          <div class="setting-option">
            <textarea
              class="form-control form-control-sm"
              v-model="data.condition"
              rows="3"
              placeholder="Enter a JavaScript condition"
              @change="updateNodeData('condition', data.condition)"
            ></textarea>
          </div>
          <div class="setting-help">JavaScript expression to evaluate. Use {{variable}} for workflow variables.</div>
        </div>

        <!-- Available Variables -->
        <div class="setting-group">
          <label class="setting-label">Available Variables</label>
          <div class="setting-option">
            <div class="condition-variables-list">
              <button
                v-for="variable in availableVariables"
                :key="variable"
                type="button"
                class="condition-variable-btn"
                @click="insertVariable(variable)"
              >
                {{ variable }}
              </button>
              <div v-if="availableVariables.length === 0" class="condition-no-variables">
                No variables available
              </div>
            </div>
          </div>
        </div>

        <!-- Condition Examples -->
        <div class="setting-group">
          <label class="setting-label">Examples</label>
          <div class="setting-option">
            <div class="condition-examples-list">
              <button
                v-for="(example, index) in conditionExamples"
                :key="index"
                type="button"
                class="condition-example-btn"
                @click="applyExample(example)"
              >
                {{ example.label }}
              </button>
            </div>
          </div>
        </div>

        <!-- Path Labels -->
        <div class="setting-group">
          <div class="row g-1">
            <div class="col-6">
              <label class="setting-label">True Label</label>
              <div class="setting-option">
                <input
                  type="text"
                  class="form-control form-control-sm"
                  v-model="data.true_label"
                  placeholder="Yes"
                  @change="updateNodeData('true_label', data.true_label)"
                />
              </div>
            </div>
            <div class="col-6">
              <label class="setting-label">False Label</label>
              <div class="setting-option">
                <input
                  type="text"
                  class="form-control form-control-sm"
                  v-model="data.false_label"
                  placeholder="No"
                  @change="updateNodeData('false_label', data.false_label)"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Node Summary (when collapsed) -->
      <div v-else class="condition-node-summary">
        <div class="condition-expr">
          {{ data.condition || 'No condition' }}
        </div>
        <div class="condition-paths">
          <div class="condition-path-true">↓ {{ data.true_label || 'True' }}</div>
          <div class="condition-path-false">→ {{ data.false_label || 'False' }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.condition-node {
  min-width: 200px;
  max-width: 280px;
  background-color: #ffffff;
  border: 2px solid #db9038;
  border-radius: 5px;
  font-size: 12px;
  overflow: hidden;
  position: relative;
}

.condition-node.selected {
  box-shadow: 0 0 0 2px #db9038;
}

.source-true {
  background-color: #28a745 !important;
}

.source-false {
  background-color: #dc3545 !important;
}

.condition-node-content {
  padding: 5px;
}

.condition-node-header {
  display: flex;
  align-items: center;
  font-weight: bold;
  padding: 4px;
  background-color: rgba(219, 144, 56, 0.1);
  border-bottom: 1px solid rgba(219, 144, 56, 0.2);
}

.condition-node-label {
  flex-grow: 1;
  margin-right: 5px;
}

.condition-node-toggle {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  width: 16px;
  height: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #db9038;
}

.condition-node-settings {
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

.condition-variables-list {
  display: flex;
  flex-wrap: wrap;
  gap: 4px;
  max-height: 60px;
  overflow-y: auto;
  padding: 4px;
  background-color: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 3px;
}

.condition-variable-btn {
  font-size: 10px;
  padding: 0 6px;
  height: 20px;
  background-color: #e9ecef;
  border: 1px solid #dee2e6;
  border-radius: 3px;
  color: #495057;
  cursor: pointer;
}

.condition-variable-btn:hover {
  background-color: #dee2e6;
}

.condition-no-variables {
  font-style: italic;
  color: #6c757d;
  font-size: 10px;
  padding: 2px 4px;
}

.condition-examples-list {
  display: flex;
  flex-wrap: wrap;
  gap: 4px;
  padding: 4px;
}

.condition-example-btn {
  font-size: 10px;
  padding: 0 6px;
  height: 20px;
  background-color: rgba(219, 144, 56, 0.1);
  border: 1px solid rgba(219, 144, 56, 0.2);
  border-radius: 3px;
  color: #db9038;
  cursor: pointer;
}

.condition-example-btn:hover {
  background-color: rgba(219, 144, 56, 0.2);
}

.condition-node-summary {
  padding: 5px;
  font-size: 11px;
}

.condition-expr {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  font-family: monospace;
  font-size: 10px;
  color: #495057;
  padding: 4px;
  background-color: #f8f9fa;
  border-radius: 3px;
  border: 1px solid #dee2e6;
  margin-bottom: 4px;
}

.condition-paths {
  display: flex;
  justify-content: space-between;
  margin-top: 4px;
}

.condition-path-true,
.condition-path-false {
  font-size: 10px;
  font-weight: 500;
}

.condition-path-true {
  color: #28a745;
}

.condition-path-false {
  color: #dc3545;
}
</style>
