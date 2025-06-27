<script setup>
import { ref, computed } from "vue";
import { Handle, Position, useVueFlow } from "@vue-flow/core";

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
});

// Get Vue Flow methods
const { findNode, setNodes } = useVueFlow();

// Node configuration
const config = ref({
  showSettings: false,
  newFieldName: "",
  newFieldSelector: "",
});

// Initialize data if not present
const initializeData = () => {
  const initialData = {
    label: props.data.label || "Scraper",
    url: props.data.url || "",
    container_selector: props.data.container_selector || "",
    field_selectors: props.data.field_selectors || [],
    link_field_name: props.data.link_field_name || "",
    link_selector: props.data.link_selector || "",
    output_variable: props.data.output_variable || "scraped_data",
    type: "scraper",
  };
  // Ensure field_selectors is an array
  if (!Array.isArray(initialData.field_selectors)) {
    initialData.field_selectors = [];
  }
  // Update data if it's different from initial setup
  for (const key in initialData) {
    if (props.data[key] === undefined) {
      updateNodeData(key, initialData[key]);
    }
  }
};

// Call initializeData when the component is mounted or props.data changes
// For simplicity in this context, we assume props.data is initially populated or updated elsewhere
// In a full setup, you might use onMounted and watch
initializeData();

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
      [key]: value,
    },
  };
  setNodes((nodes) => nodes.map((n) => (n.id === props.id ? newNode : n)));
};

// Add a field selector
const addFieldSelector = () => {
  if (!config.value.newFieldName || !config.value.newFieldSelector) return;

  const fieldSelectors = [...(props.data.field_selectors || [])];
  fieldSelectors.push({
    name: config.value.newFieldName,
    selector: config.value.newFieldSelector,
  });

  updateNodeData("field_selectors", fieldSelectors);

  config.value.newFieldName = "";
  config.value.newFieldSelector = "";
};

// Remove a field selector
const removeFieldSelector = (index) => {
  const fieldSelectors = [...(props.data.field_selectors || [])];
  fieldSelectors.splice(index, 1);
  updateNodeData("field_selectors", fieldSelectors);
};

// Computed property for display in summary
const summaryFields = computed(() => {
  if (props.data.field_selectors && props.data.field_selectors.length > 0) {
    return props.data.field_selectors.map((f) => f.name).join(", ");
  }
  return "No fields";
});
</script>

<template>
  <div :class="['scraper-node', { selected: selected }]">
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
    <div class="scraper-node-content">
      <div class="scraper-node-header">
        <i class="fa fa-spider text-success me-1"></i>
        <!-- Changed icon -->
        <span class="scraper-node-label">{{
          data.label || "Scraper Node"
        }}</span>
        <button
          type="button"
          class="scraper-node-toggle"
          @click="toggleSettings"
          :title="config.showSettings ? 'Hide settings' : 'Show settings'"
        >
          <i
            :class="[
              'fa',
              config.showSettings ? 'fa-chevron-up' : 'fa-chevron-down',
            ]"
          ></i>
        </button>
      </div>

      <!-- Setting Panel -->
      <div v-if="config.showSettings" class="scraper-node-settings">
        <!-- URL -->
        <div class="setting-group">
          <label class="setting-label">URL</label>
          <div class="setting-option">
            <input
              type="text"
              class="form-control form-control-sm"
              v-model="data.url"
              placeholder="https://example.com/data"
              @change="updateNodeData('url', data.url)"
            />
          </div>
          <div class="setting-help">
            The URL to scrape. Use {{ variable }} for dynamic values.
          </div>
        </div>

        <!-- Container Selector -->
        <div class="setting-group">
          <label class="setting-label">Container Selector</label>
          <div class="setting-option">
            <input
              type="text"
              class="form-control form-control-sm"
              v-model="data.container_selector"
              placeholder="e.g., div.product-item"
              @change="
                updateNodeData('container_selector', data.container_selector)
              "
            />
          </div>
          <div class="setting-help">
            CSS selector for the parent element of each item.
          </div>
        </div>

        <!-- Field Selectors -->
        <div class="setting-group">
          <label class="setting-label">Field Selectors</label>
          <div class="setting-option">
            <div class="scraper-fields-list">
              <div
                v-for="(field, index) in data.field_selectors"
                :key="index"
                class="scraper-field-item"
              >
                <span class="scraper-field-name">{{ field.name }}</span>
                <span class="scraper-field-selector">{{ field.selector }}</span>
                <button
                  type="button"
                  class="scraper-field-remove"
                  @click="removeFieldSelector(index)"
                  title="Remove field"
                >
                  <i class="fa fa-times"></i>
                </button>
              </div>
              <div
                v-if="
                  !data.field_selectors || data.field_selectors.length === 0
                "
                class="scraper-no-fields"
              >
                No fields defined
              </div>
            </div>
          </div>

          <!-- Add Field Selector -->
          <div class="setting-option mt-1">
            <div class="row g-1">
              <div class="col-5">
                <input
                  type="text"
                  class="form-control form-control-sm"
                  v-model="config.newFieldName"
                  placeholder="Field Name (e.g. title)"
                />
              </div>
              <div class="col-5">
                <input
                  type="text"
                  class="form-control form-control-sm"
                  v-model="config.newFieldSelector"
                  placeholder="CSS Selector (e.g. h2.title)"
                />
              </div>
              <div class="col-2">
                <button
                  type="button"
                  class="btn btn-sm btn-outline-secondary w-100"
                  @click="addFieldSelector"
                  :disabled="!config.newFieldName || !config.newFieldSelector"
                >
                  <i class="fa fa-plus"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="setting-help">
            Define fields to extract from within each container.
          </div>
        </div>

        <!-- Optional Link Extraction -->
        <div class="setting-group">
          <label class="setting-label">Link Extraction (Optional)</label>
          <div class="row g-1">
            <div class="col-6">
              <div class="setting-option">
                <input
                  type="text"
                  class="form-control form-control-sm"
                  v-model="data.link_field_name"
                  placeholder="Link Field Name (e.g. productLink)"
                  @change="
                    updateNodeData('link_field_name', data.link_field_name)
                  "
                />
              </div>
            </div>
            <div class="col-6">
              <div class="setting-option">
                <input
                  type="text"
                  class="form-control form-control-sm"
                  v-model="data.link_selector"
                  placeholder="Link CSS Selector (e.g. a.product-url)"
                  @change="updateNodeData('link_selector', data.link_selector)"
                />
              </div>
            </div>
          </div>
          <div class="setting-help">
            Extract href from an &lt;a&gt; tag within each container.
          </div>
        </div>

        <!-- Output Variable -->
        <div class="setting-group">
          <label class="setting-label">Save Result To</label>
          <div class="setting-option">
            <input
              type="text"
              class="form-control form-control-sm"
              v-model="data.output_variable"
              placeholder="Variable name (e.g. scraped_data)"
              @change="updateNodeData('output_variable', data.output_variable)"
            />
          </div>
          <div class="setting-help">
            Variable to store the array of scraped data.
          </div>
        </div>
      </div>

      <!-- Node Summary (when collapsed) -->
      <div v-else class="scraper-node-summary">
        <div class="scraper-url-summary">
          <span class="scraper-url-label">URL:</span>
          <span class="scraper-url-value">{{ data.url || "Not set" }}</span>
        </div>
        <div class="scraper-details-summary">
          <span class="scraper-fields-count">Fields: {{ summaryFields }}</span>
          <span v-if="data.output_variable" class="scraper-output-var"
            >→ {{ data.output_variable }}</span
          >
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.scraper-node {
  min-width: 220px;
  max-width: 300px;
  background-color: #ffffff;
  border: 2px solid #28a745; /* Green border for scraper */
  border-radius: 5px;
  font-size: 12px;
  overflow: hidden;
}

.scraper-node.selected {
  box-shadow: 0 0 0 2px #28a745;
}

.scraper-node-content {
  padding: 5px;
}

.scraper-node-header {
  display: flex;
  align-items: center;
  font-weight: bold;
  padding: 4px;
  background-color: rgba(40, 167, 69, 0.1); /* Light green background */
  border-bottom: 1px solid rgba(40, 167, 69, 0.2);
}

.scraper-node-label {
  flex-grow: 1;
  margin-right: 5px;
}

.scraper-node-toggle {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  width: 16px;
  height: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #28a745;
}

.scraper-node-settings {
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

.scraper-fields-list {
  max-height: 100px; /* Adjusted height */
  overflow-y: auto;
  border: 1px solid #dee2e6;
  border-radius: 3px;
  padding: 4px;
  background-color: #f8f9fa;
  font-size: 10px;
}

.scraper-field-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 2px 4px;
  border-radius: 3px;
  margin-bottom: 2px;
  background-color: rgba(40, 167, 69, 0.05); /* Very light green */
}

.scraper-field-name {
  font-weight: 500;
  color: #28a745;
  margin-right: 4px;
}

.scraper-field-selector {
  color: #6c757d;
  flex-grow: 1;
  text-align: left; /* Align to left */
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 120px; /* Max width for selector */
}

.scraper-field-remove {
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

.scraper-no-fields {
  font-style: italic;
  color: #6c757d;
  font-size: 10px;
  padding: 2px 4px;
}

.scraper-node-summary {
  padding: 5px;
  font-size: 11px;
}

.scraper-url-summary {
  display: flex;
  align-items: center;
  gap: 4px;
  margin-bottom: 2px;
  font-size: 10px;
}

.scraper-url-label {
  font-weight: bold;
  color: #6c757d;
}

.scraper-url-value {
  flex-grow: 1;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  color: #28a745;
}

.scraper-details-summary {
  display: flex;
  justify-content: space-between;
  font-size: 10px;
  align-items: center;
}

.scraper-fields-count {
  color: #6c757d;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 150px; /* Adjust as needed */
}

.scraper-output-var {
  color: #28a745;
  font-weight: 500;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 70px; /* Adjust as needed */
}
</style>
