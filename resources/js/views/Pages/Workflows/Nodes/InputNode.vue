// resources/js/views/Pages/Workflows/Nodes/InputNode.vue
<script setup>
import { ref } from 'vue';
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
  showSettings: false
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
</script>

<template>
  <div :class="['input-node', { 'selected': selected }]">
    <!-- Source Handle (Output) -->
    <Handle
      type="source"
      position="bottom"
      :id="id + '-source'"
      :connectable="isConnectable"
    />

    <!-- Node Content -->
    <div class="input-node-content">
      <div class="input-node-header">
        <i class="fa fa-sign-in-alt text-info me-1"></i>
        <span class="input-node-label">{{ data.label }}</span>
        <button
          type="button"
          class="input-node-toggle"
          @click="toggleSettings"
          :title="config.showSettings ? 'Hide settings' : 'Show settings'"
        >
          <i :class="['fa', config.showSettings ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
        </button>
      </div>

      <!-- Setting Panel -->
      <div v-if="config.showSettings" class="input-node-settings">
        <!-- Variable Name -->
        <div class="setting-group">
          <label class="setting-label">Variable Name</label>
          <div class="setting-option">
            <input
              type="text"
              class="form-control form-control-sm"
              v-model="data.variable"
              placeholder="Enter variable name"
              @change="updateNodeData('variable', data.variable)"
            />
          </div>
          <div class="setting-help">This variable will be available in your workflow</div>
        </div>

        <!-- Default Value -->
        <div class="setting-group">
          <label class="setting-label">Default Value (optional)</label>
          <div class="setting-option">
            <textarea
              class="form-control form-control-sm"
              v-model="data.default_value"
              rows="2"
              placeholder="Enter default value (if any)"
              @change="updateNodeData('default_value', data.default_value)"
            ></textarea>
          </div>
          <div class="setting-help">Used when no input is provided</div>
        </div>

        <!-- Description -->
        <div class="setting-group">
          <label class="setting-label">Description (optional)</label>
          <div class="setting-option">
            <input
              type="text"
              class="form-control form-control-sm"
              v-model="data.description"
              placeholder="Describe this input"
              @change="updateNodeData('description', data.description)"
            />
          </div>
          <div class="setting-help">Helps users understand the purpose</div>
        </div>

        <!-- Required Toggle -->
        <div class="setting-group">
          <div class="setting-option">
            <div class="form-check">
              <input
                type="checkbox"
                class="form-check-input"
                id="input-required"
                v-model="data.required"
                @change="updateNodeData('required', data.required)"
              />
              <label class="form-check-label" for="input-required">Required Input</label>
            </div>
          </div>
        </div>
      </div>

      <!-- Node Summary (when collapsed) -->
      <div v-else class="input-node-summary">
        <div class="input-variable">{{ data.variable || 'unnamed' }}</div>
        <div v-if="data.default_value" class="input-default">Default: {{ data.default_value.substring(0, 15) }}{{ data.default_value.length > 15 ? '...' : '' }}</div>
        <div v-if="data.required" class="input-required">Required</div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.input-node {
  min-width: 180px;
  max-width: 240px;
  background-color: #ffffff;
  border: 2px solid #26a9e0;
  border-radius: 5px;
  font-size: 12px;
  overflow: hidden;
}

.input-node.selected {
  box-shadow: 0 0 0 2px #26a9e0;
}

.input-node-content {
  padding: 5px;
}

.input-node-header {
  display: flex;
  align-items: center;
  font-weight: bold;
  padding: 4px;
  background-color: rgba(38, 169, 224, 0.1);
  border-bottom: 1px solid rgba(38, 169, 224, 0.2);
}

.input-node-label {
  flex-grow: 1;
  margin-right: 5px;
}

.input-node-toggle {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  width: 16px;
  height: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #26a9e0;
}

.input-node-settings {
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

.input-node-summary {
  padding: 5px;
  font-size: 11px;
  color: #6c757d;
}

.input-variable {
  font-weight: bold;
  color: #26a9e0;
}

.input-default {
  font-style: italic;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-top: 2px;
}

.input-required {
  font-size: 10px;
  color: #e83e8c;
  margin-top: 2px;
}
</style>
