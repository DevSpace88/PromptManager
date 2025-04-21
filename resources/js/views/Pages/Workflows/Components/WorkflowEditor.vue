<!-- resources/js/views/Pages/Workflows/Components/WorkflowEditor.vue -->
<script setup>
import { ref, reactive, onMounted, computed, nextTick, onUnmounted, markRaw, watch } from 'vue';

// Import Vue Flow
import { VueFlow } from '@vue-flow/core';
import { Background } from '@vue-flow/background';
import { Controls } from '@vue-flow/controls';
import { MiniMap } from '@vue-flow/minimap';
import { Panel } from '@vue-flow/core';
import { useVueFlow } from '@vue-flow/core';
import { v4 as uuidv4 } from 'uuid';

// Ensure CSS is imported
import '@vue-flow/core/dist/style.css';
import '@vue-flow/core/dist/theme-default.css';

// Import node components
import PromptNode from '@/views/Pages/Workflows/Nodes/PromptNode.vue';
import ConditionNode from '@/views/Pages/Workflows/Nodes/ConditionNode.vue';
import InputNode from '@/views/Pages/Workflows/Nodes/InputNode.vue';
import OutputNode from '@/views/Pages/Workflows/Nodes/OutputNode.vue';
import ApiNode from '@/views/Pages/Workflows/Nodes/ApiNode.vue';
import TransformNode from '@/views/Pages/Workflows/Nodes/TransformNode.vue';

// Props mit Default-Werten für neue Workflows und existierende Workflows
const props = defineProps({
  initialNodes: {
    type: Array,
    default: () => []
  },
  initialEdges: {
    type: Array,
    default: () => []
  },
  readOnly: {
    type: Boolean,
    default: false
  },
  prompts: {
    type: Array,
    default: () => []
  },
  apiKeys: {
    type: Array,
    default: () => []
  }
});

// Emits für Änderungen
const emit = defineEmits(['update:nodes', 'update:edges']);

// Node types registration - use markRaw to prevent reactivity warnings
const nodeTypes = {
  promptNode: markRaw(PromptNode),
  conditionNode: markRaw(ConditionNode),
  inputNode: markRaw(InputNode),
  outputNode: markRaw(OutputNode),
  apiNode: markRaw(ApiNode),
  transformNode: markRaw(TransformNode)
};

// Use Vue Flow instance
const vueFlow = useVueFlow();

// Create local reactive state that we can sync with Vue Flow
const flowNodes = ref([...props.initialNodes]);
const flowEdges = ref([...props.initialEdges]);

// Sidebar state
const activePanel = ref('nodes');
const initialNodesCreated = ref(false);
const isFullscreen = ref(false);
const workflowBuilder = ref(null);

// Watch for external changes to props
watch(() => props.initialNodes, (newNodes) => {
  if (JSON.stringify(newNodes) !== JSON.stringify(flowNodes.value)) {
    updateNodes(newNodes);
  }
}, { deep: true });

watch(() => props.initialEdges, (newEdges) => {
  if (JSON.stringify(newEdges) !== JSON.stringify(flowEdges.value)) {
    updateEdges(newEdges);
  }
}, { deep: true });

// Method to safely update nodes
const updateNodes = (newNodes) => {
  flowNodes.value = newNodes;
  vueFlow.setNodes(newNodes);
  emit('update:nodes', newNodes);
};

// Method to safely update edges
const updateEdges = (newEdges) => {
  flowEdges.value = newEdges;
  vueFlow.setEdges(newEdges);
  emit('update:edges', newEdges);
};

// Create a new node
const createNode = (type, data = {}) => {
  const id = `node-${uuidv4()}`;
  const nodeData = {
    id,
    type: `${type}Node`,
    position: { x: 250, y: 100 },
    data: {
      ...data,
      label: data.label || `${type.charAt(0).toUpperCase() + type.slice(1)}`,
      type: type
    }
  };

  // Update nodes by creating a new array
  updateNodes([...flowNodes.value, nodeData]);
  return nodeData;
};

// Add different types of nodes
const addPromptNode = () => {
  createNode('prompt', {
    label: 'Prompt',
    content: '',
    prompt_id: null,
    provider: 'openai',
    model: 'gpt-4',
    temperature: 0.7,
    max_tokens: 2000,
    output_variable: 'result'
  });
};

const addConditionNode = () => {
  createNode('condition', {
    label: 'Condition',
    condition: '',
    true_path: null,
    false_path: null
  });
};

const addInputNode = () => {
  createNode('input', {
    label: 'Input',
    variable: '',
    default_value: ''
  });
};

const addOutputNode = () => {
  createNode('output', {
    label: 'Output',
    variables: []
  });
};

const addApiNode = () => {
  createNode('api', {
    label: 'API Call',
    url: '',
    method: 'GET',
    headers: {},
    body: {},
    output_variable: 'api_result'
  });
};

const addTransformNode = () => {
  createNode('transform', {
    label: 'Transform',
    code: '',
    input_variables: [],
    output_variable: 'transformed_data'
  });
};

// Toggle fullscreen
const toggleFullscreen = () => {
  isFullscreen.value = !isFullscreen.value;

  if (isFullscreen.value) {
    document.documentElement.style.overflow = 'hidden';
    workflowBuilder.value.classList.add('workflow-fullscreen');
  } else {
    document.documentElement.style.overflow = '';
    workflowBuilder.value.classList.remove('workflow-fullscreen');
  }

  // Update Vue Flow on next tick to handle resize
  nextTick(() => {
    vueFlow.fitView();
  });
};

// Initialize flow on mount
onMounted(() => {
  // Initialize Vue Flow with nodes and edges
  nextTick(() => {
    // Define the initial state
    vueFlow.setNodes(flowNodes.value);
    vueFlow.setEdges(flowEdges.value);

    // Add missing elements if not in readOnly mode
    if (!props.readOnly && flowNodes.value.length === 0) {
      // Maybe add default nodes for a new workflow
      addInputNode();
      addPromptNode();
      addOutputNode();
      initialNodesCreated.value = true;
    }

    // Fit view to show all nodes
    vueFlow.fitView();
  });

  // Set up event listeners
  const { onNodeDragStop, onConnect, onEdgesChange, onNodesChange } = vueFlow;

  onNodeDragStop(() => {
    updateNodes(vueFlow.getNodes());
  });

  onConnect((params) => {
    // Create a new connection
    const newEdge = {
      id: `edge-${uuidv4()}`,
      source: params.source,
      target: params.target,
      sourceHandle: params.sourceHandle,
      targetHandle: params.targetHandle,
    };

    updateEdges([...flowEdges.value, newEdge]);
  });

  onEdgesChange(({ edgesChange }) => {
    // Handle edges changes (like removals)
    const newEdges = [...flowEdges.value];
    edgesChange.forEach(change => {
      if (change.type === 'remove') {
        const index = newEdges.findIndex(edge => edge.id === change.id);
        if (index !== -1) {
          newEdges.splice(index, 1);
        }
      }
    });
    updateEdges(newEdges);
  });

  onNodesChange(({ nodesChange }) => {
    // Handle node changes (deletions, selections)
    const newNodes = [...flowNodes.value];
    let hasChanges = false;

    nodesChange.forEach(change => {
      if (change.type === 'remove') {
        const index = newNodes.findIndex(node => node.id === change.id);
        if (index !== -1) {
          newNodes.splice(index, 1);
          hasChanges = true;
        }
      } else if (change.type === 'select') {
        const node = newNodes.find(n => n.id === change.id);
        if (node) {
          node.selected = change.selected;
          hasChanges = true;
        }
      }
    });

    if (hasChanges) {
      updateNodes(newNodes);
    }
  });
});

// Clean up when component is unmounted
onUnmounted(() => {
  if (isFullscreen.value) {
    document.documentElement.style.overflow = '';
  }
});

// Expose methods to parent components
defineExpose({
  addPromptNode,
  addConditionNode,
  addInputNode,
  addOutputNode,
  addApiNode,
  addTransformNode,
  toggleFullscreen
});
</script>

<template>
  <div ref="workflowBuilder" class="workflow-builder position-relative" :class="{ 'pe-none': props.readOnly }">
    <!-- Vue Flow instance -->
    <VueFlow
      :nodeTypes="nodeTypes"
      :nodes="flowNodes"
      :edges="flowEdges"
      :default-zoom="1"
      :min-zoom="0.2"
      :max-zoom="4"
      :snapToGrid="true"
      :deleteKeyCode="['Backspace', 'Delete']"
      :selectionKeyCode="['Control', 'Meta']"
      :multiSelectionKeyCode="['Shift']"
      class="workflow-canvas"
      :class="{ 'read-only': props.readOnly }"
    >
      <Background pattern-color="#aaa" gap="16" />
      <MiniMap class="workflow-minimap" />
      <Controls />

      <!-- Node Sidebar - Only show if not readOnly -->
      <template v-if="!props.readOnly">
        <Panel position="top-left" class="workflow-panel">
          <div class="btn-group mb-2">
            <button
              type="button"
              class="btn btn-sm"
              :class="activePanel === 'nodes' ? 'btn-primary' : 'btn-alt-secondary'"
              @click="activePanel = 'nodes'"
            >
              <i class="fa fa-shapes"></i> Nodes
            </button>
            <button
              type="button"
              class="btn btn-sm"
              :class="activePanel === 'info' ? 'btn-primary' : 'btn-alt-secondary'"
              @click="activePanel = 'info'"
            >
              <i class="fa fa-info-circle"></i> Info
            </button>
          </div>

          <div v-if="activePanel === 'nodes'" class="workflow-node-palette p-2 bg-body-extra-light rounded">
            <div class="fs-sm fw-semibold mb-2">Add Nodes</div>
            <div class="btn-group-vertical w-100">
              <button class="btn btn-sm btn-alt-primary mb-1" @click="addInputNode">
                <i class="fa fa-arrow-right me-1"></i> Input
              </button>
              <button class="btn btn-sm btn-alt-info mb-1" @click="addPromptNode">
                <i class="fa fa-comment-alt me-1"></i> Prompt
              </button>
              <button class="btn btn-sm btn-alt-warning mb-1" @click="addConditionNode">
                <i class="fa fa-code-branch me-1"></i> Condition
              </button>
              <button class="btn btn-sm btn-alt-danger mb-1" @click="addApiNode">
                <i class="fa fa-exchange-alt me-1"></i> API Call
              </button>
              <button class="btn btn-sm btn-alt-success mb-1" @click="addTransformNode">
                <i class="fa fa-cogs me-1"></i> Transform
              </button>
              <button class="btn btn-sm btn-alt-secondary mb-1" @click="addOutputNode">
                <i class="fa fa-arrow-left me-1"></i> Output
              </button>
            </div>
          </div>

          <div v-else-if="activePanel === 'info'" class="workflow-info p-2 bg-body-extra-light rounded">
            <div class="fs-sm">
              <p class="mb-1"><strong>Tips:</strong></p>
              <ul class="ps-3 mb-0 fs-xs">
                <li>Drag nodes to position</li>
                <li>Connect nodes by dragging from output to input handle</li>
                <li>Delete nodes/connections with Del key</li>
                <li>Use Ctrl to select multiple nodes</li>
              </ul>
            </div>
          </div>
        </Panel>
      </template>

      <!-- Fullscreen toggle -->
      <Panel position="top-right">
        <button
          type="button"
          class="btn btn-sm btn-alt-secondary"
          @click="toggleFullscreen"
          title="Toggle Fullscreen"
        >
          <i :class="isFullscreen ? 'fa fa-compress' : 'fa fa-expand'"></i>
        </button>
      </Panel>
    </VueFlow>
  </div>
</template>

<style scoped>
.workflow-builder {
  width: 100%;
  height: 60vh;
  border: 1px solid #e4e7ed;
  border-radius: 0.25rem;
}

.workflow-fullscreen {
  position: fixed !important;
  top: 0;
  left: 0;
  width: 100vw !important;
  height: 100vh !important;
  z-index: 1050;
  background: white;
}

.workflow-node-palette {
  min-width: 160px;
}

.workflow-info {
  min-width: 200px;
}

.workflow-minimap {
  width: 160px;
  height: 120px;
}

.read-only :deep(.vue-flow__handle) {
  display: none;
}
</style>
