// resources/js/views/Pages/Workflows/Nodes/ApiNode.vue
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
  showSettings: false,
  bodyType: props.data.bodyType || 'json',
  newHeaderKey: '',
  newHeaderValue: '',
  availableMethods: [
    'GET', 'POST', 'PUT', 'PATCH', 'DELETE'
  ]
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

// Add a header
const addHeader = () => {
  if (!config.value.newHeaderKey) return;

  const headers = { ...(props.data.headers || {}) };
  headers[config.value.newHeaderKey] = config.value.newHeaderValue;

  updateNodeData('headers', headers);

  config.value.newHeaderKey = '';
  config.value.newHeaderValue = '';
};

// Remove a header
const removeHeader = (key) => {
  const headers = { ...(props.data.headers || {}) };
  delete headers[key];

  updateNodeData('headers', headers);
};

// Update body type
const updateBodyType = (type) => {
  config.value.bodyType = type;
  updateNodeData('bodyType', type);

  // Initialize body based on type if it doesn't exist
  if (!props.data.body) {
    if (type === 'json') {
      updateNodeData('body', '{}');
    } else if (type === 'form') {
      updateNodeData('body', {});
    } else {
      updateNodeData('body', '');
    }
  }
};
</script>

<template>
  <div :class="['api-node', { 'selected': selected }]">
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
    <div class="api-node-content">
      <div class="api-node-header">
        <i class="fa fa-globe text-danger me-1"></i>
        <span class="api-node-label">{{ data.label }}</span>
        <button
          type="button"
          class="api-node-toggle"
          @click="toggleSettings"
          :title="config.showSettings ? 'Hide settings' : 'Show settings'"
        >
          <i :class="['fa', config.showSettings ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
        </button>
      </div>

      <!-- Setting Panel -->
      <div v-if="config.showSettings" class="api-node-settings">
        <!-- URL -->
        <div class="setting-group">
          <label class="setting-label">URL</label>
          <div class="setting-option">
            <input
              type="text"
              class="form-control form-control-sm"
              v-model="data.url"
              placeholder="https://api.example.com/endpoint"
              @change="updateNodeData('url', data.url)"
            />
          </div>
          <div class="setting-help">API endpoint URL. Use {{variable}} for dynamic values.</div>
        </div>

        <!-- Method -->
        <div class="setting-group">
          <label class="setting-label">Method</label>
          <div class="setting-option">
            <select
              class="form-select form-select-sm"
              v-model="data.method"
              @change="updateNodeData('method', data.method)"
            >
              <option v-for="method in config.availableMethods" :key="method" :value="method">
                {{ method }}
              </option>
            </select>
          </div>
        </div>

        <!-- Headers -->
        <div class="setting-group">
          <label class="setting-label">Headers</label>
          <div class="setting-option">
            <div class="api-headers-list">
              <div
                v-for="(value, key) in data.headers"
                :key="key"
                class="api-header-item"
              >
                <span class="api-header-key">{{ key }}</span>
                <span class="api-header-value">{{ value }}</span>
                <button
                  type="button"
                  class="api-header-remove"
                  @click="removeHeader(key)"
                  title="Remove header"
                >
                  <i class="fa fa-times"></i>
                </button>
              </div>
              <div v-if="!data.headers || Object.keys(data.headers).length === 0" class="api-no-headers">
                No headers defined
              </div>
            </div>
          </div>

          <!-- Add Header -->
          <div class="setting-option mt-1">
            <div class="row g-1">
              <div class="col-5">
                <input
                  type="text"
                  class="form-control form-control-sm"
                  v-model="config.newHeaderKey"
                  placeholder="Key"
                />
              </div>
              <div class="col-5">
                <input
                  type="text"
                  class="form-control form-control-sm"
                  v-model="config.newHeaderValue"
                  placeholder="Value"
                />
              </div>
              <div class="col-2">
                <button
                  type="button"
                  class="btn btn-sm btn-outline-secondary w-100"
                  @click="addHeader"
                  :disabled="!config.newHeaderKey"
                >
                  <i class="fa fa-plus"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Body -->
        <div class="setting-group">
          <label class="setting-label">Body</label>
          <div class="setting-option mb-1">
            <div class="btn-group btn-group-sm w-100">
              <button
                type="button"
                class="btn"
                :class="config.bodyType === 'json' ? 'btn-danger' : 'btn-outline-secondary'"
                @click="updateBodyType('json')"
              >
                JSON
              </button>
              <button
                type="button"
                class="btn"
                :class="config.bodyType === 'text' ? 'btn-danger' : 'btn-outline-secondary'"
                @click="updateBodyType('text')"
              >
                Text
              </button>
              <button
                type="button"
                class="btn"
                :class="config.bodyType === 'form' ? 'btn-danger' : 'btn-outline-secondary'"
                @click="updateBodyType('form')"
              >
                Form
              </button>
            </div>
          </div>
          <div class="setting-option">
            <textarea
              class="form-control form-control-sm"
              v-model="data.body"
              rows="3"
              placeholder="Request body"
              @change="updateNodeData('body', data.body)"
            ></textarea>
          </div>
          <div class="setting-help">Request body. Use {{variable}} for dynamic values.</div>
        </div>

        <!-- Output Variable -->
        <div class="setting-group">
          <label class="setting-label">Save Response To</label>
          <div class="setting-option">
            <input
              type="text"
              class="form-control form-control-sm"
              v-model="data.output_variable"
              placeholder="Variable name"
              @change="updateNodeData('output_variable', data.output_variable)"
            />
          </div>
          <div class="setting-help">Variable to store the API response</div>
        </div>
      </div>

      <!-- Node Summary (when collapsed) -->
      <div v-else class="api-node-summary">
        <div class="api-method-url">
          <span class="api-method" :class="`method-${data.method?.toLowerCase()}`">{{ data.method }}</span>
          <span class="api-url">{{ data.url }}</span>
        </div>
        <div class="api-details">
          <span v-if="data.headers && Object.keys(data.headers).length > 0" class="api-header-count">{{ Object.keys(data.headers).length }} headers</span>
          <span v-if="data.output_variable" class="api-output-var">→ {{ data.output_variable }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.api-node {
  min-width: 200px;
  max-width: 280px;
  background-color: #ffffff;
  border: 2px solid #e84a5f;
  border-radius: 5px;
  font-size: 12px;
  overflow: hidden;
}

.api-node.selected {
  box-shadow: 0 0 0 2px #e84a5f;
}

.api-node-content {
  padding: 5px;
}

.api-node-header {
  display: flex;
  align-items: center;
  font-weight: bold;
  padding: 4px;
  background-color: rgba(232, 74, 95, 0.1);
  border-bottom: 1px solid rgba(232, 74, 95, 0.2);
}

.api-node-label {
  flex-grow: 1;
  margin-right: 5px;
}

.api-node-toggle {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  width: 16px;
  height: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #e84a5f;
}

.api-node-settings {
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

.api-headers-list {
  max-height: 80px;
  overflow-y: auto;
  border: 1px solid #dee2e6;
  border-radius: 3px;
  padding: 4px;
  background-color: #f8f9fa;
  font-size: 10px;
}

.api-header-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 2px 4px;
  border-radius: 3px;
  margin-bottom: 2px;
  background-color: rgba(232, 74, 95, 0.05);
}

.api-header-key {
  font-weight: 500;
  color: #e84a5f;
}

.api-header-value {
  color: #6c757d;
  margin-left: 4px;
  flex-grow: 1;
  text-align: center;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 80px;
}

.api-header-remove {
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

.api-no-headers {
  font-style: italic;
  color: #6c757d;
  font-size: 10px;
  padding: 2px 4px;
}

.api-node-summary {
  padding: 5px;
  font-size: 11px;
}

.api-method-url {
  display: flex;
  align-items: center;
  gap: 4px;
  margin-bottom: 2px;
}

.api-method {
  font-weight: bold;
  padding: 0 4px;
  border-radius: 3px;
  font-size: 10px;
}

.method-get {
  background-color: #cfe2ff;
  color: #0d6efd;
}

.method-post {
  background-color: #d1e7dd;
  color: #198754;
}

.method-put {
  background-color: #fff3cd;
  color: #ffc107;
}

.method-patch {
  background-color: #f8d7da;
  color: #dc3545;
}

.method-delete {
  background-color: #f8d7da;
  color: #dc3545;
}

.api-url {
  flex-grow: 1;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  font-size: 10px;
  color: #6c757d;
}

.api-details {
  display: flex;
  justify-content: space-between;
  font-size: 10px;
}

.api-header-count {
  color: #6c757d;
}

.api-output-var {
  color: #e84a5f;
  font-weight: 500;
}
</style>
