<script setup>
import { ref, reactive, onMounted, computed, nextTick, onUnmounted, markRaw } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';

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

// Import custom node types - adjust paths as needed
import PromptNode from '@/views/Pages/Workflows/Nodes/PromptNode.vue';
import ConditionNode from '@/views/Pages/Workflows/Nodes/ConditionNode.vue';
import InputNode from '@/views/Pages/Workflows/Nodes/InputNode.vue';
import OutputNode from '@/views/Pages/Workflows/Nodes/OutputNode.vue';
import ApiNode from '@/views/Pages/Workflows/Nodes/ApiNode.vue';
import TransformNode from '@/views/Pages/Workflows/Nodes/TransformNode.vue';
import BasePageHeading from '@/components/BasePageHeading.vue';

// Props
const props = defineProps({
  workflow: {
    type: Object,
    required: true
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

// Form for workflow details
const form = useForm({
  name: props.workflow.name,
  description: props.workflow.description || '',
  nodes: props.workflow.nodes || [],
  edges: props.workflow.edges || [],
  settings: props.workflow.settings || {
    autoSave: true,
    notifyOnCompletion: true
  },
  is_active: props.workflow.is_active
});

// Node types registration - use markRaw to prevent reactivity warnings
const nodeTypes = {
  promptNode: markRaw(PromptNode),
  conditionNode: markRaw(ConditionNode),
  inputNode: markRaw(InputNode),
  outputNode: markRaw(OutputNode),
  apiNode: markRaw(ApiNode),
  transformNode: markRaw(TransformNode)
};

// Use Vue Flow instance but don't immediately destructure it
const vueFlow = useVueFlow();

// Create local reactive state that we can sync with Vue Flow
const flowNodes = ref([]);
const flowEdges = ref([]);

// Sidebar state
const activePanel = ref('nodes');
const initialNodesCreated = ref(false);
const isFullscreen = ref(false);
const workflowBuilder = ref(null);

// Method to safely update nodes
const updateNodes = (newNodes) => {
  flowNodes.value = newNodes;
  vueFlow.setNodes(newNodes);
};

// Method to safely update edges
const updateEdges = (newEdges) => {
  flowEdges.value = newEdges;
  vueFlow.setEdges(newEdges);
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
    input_variable: '',
    output_variable: '',
    transformation: 'json_parse',
    regex: '',
    code: ''
  });
};

// Handle connection between nodes
const onConnect = (params) => {
  const newEdge = {
    id: `edge-${uuidv4()}`,
    source: params.source,
    target: params.target,
    animated: true
  };

  updateEdges([...flowEdges.value, newEdge]);
};

// Track changes to nodes and edges
const onNodesChange = (changes) => {
  const updatedNodes = vueFlow.applyNodeChanges(changes, flowNodes.value);
  flowNodes.value = updatedNodes;

  // Update form data when nodes change
  form.nodes = updatedNodes.map(node => ({
    id: node.id,
    type: node.data?.type || '',
    position: node.position || { x: 0, y: 0 },
    data: node.data || {}
  }));
};

const onEdgesChange = (changes) => {
  const updatedEdges = vueFlow.applyEdgeChanges(changes, flowEdges.value);
  flowEdges.value = updatedEdges;

  // Update form data when edges change
  form.edges = updatedEdges;
};

// Initialize with workflow nodes and edges
const initializeWorkflow = () => {
  if (initialNodesCreated.value) return;

  if (props.workflow.nodes && props.workflow.nodes.length > 0) {
    // Format nodes to match VueFlow requirements
    const nodes = props.workflow.nodes.map(node => ({
      ...node,
      // Make sure node types match component names expected by Vue Flow
      type: `${node.type}Node`,
    }));

    updateNodes(nodes);
  } else {
    // If no nodes, add default nodes
    const inputNodeId = `node-${uuidv4()}`;
    const outputNodeId = `node-${uuidv4()}`;

    const initialNodes = [
      {
        id: inputNodeId,
        type: 'inputNode',
        position: { x: 250, y: 100 },
        data: {
          label: 'Input',
          variable: 'input',
          default_value: '',
          type: 'input'
        }
      },
      {
        id: outputNodeId,
        type: 'outputNode',
        position: { x: 250, y: 300 },
        data: {
          label: 'Output',
          variables: ['result'],
          type: 'output'
        }
      }
    ];

    updateNodes(initialNodes);
  }

  if (props.workflow.edges && props.workflow.edges.length > 0) {
    updateEdges(props.workflow.edges);
  }

  initialNodesCreated.value = true;

  // Update form with initial nodes and edges
  nextTick(() => {
    if (flowNodes.value?.length > 0) {
      form.nodes = flowNodes.value.map(node => ({
        id: node.id,
        type: node.data.type,
        position: node.position,
        data: node.data
      }));

      form.edges = flowEdges.value;

      // Fit view to see all nodes
      setTimeout(() => {
        vueFlow.fitView();
      }, 100);
    }
  });
};

// Fullscreen toggle function
const toggleFullscreen = () => {
  isFullscreen.value = !isFullscreen.value;

  if (isFullscreen.value) {
    if (workflowBuilder.value?.requestFullscreen) {
      workflowBuilder.value.requestFullscreen();
    }
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    }
  }
};

// Handle fullscreen change from browser controls
const handleFullscreenChange = () => {
  if (!document.fullscreenElement) {
    isFullscreen.value = false;
  }
};

// Add event listener for fullscreen change
onMounted(() => {
  document.addEventListener('fullscreenchange', handleFullscreenChange);

  // Use nextTick to ensure Vue Flow is mounted before adding nodes
  nextTick(() => {
    initializeWorkflow();
  });
});

// Clean up event listener
onUnmounted(() => {
  document.removeEventListener('fullscreenchange', handleFullscreenChange);
});

// Handle keyboard events for node/edge deletion
const handleKeyDown = (event) => {
  if (event.key === 'Delete' || event.key === 'Backspace') {
    deleteSelected();
  }
};

// Delete selected nodes and edges
const deleteSelected = () => {
  // Get selected nodes and edges
  const selectedNodes = flowNodes.value.filter(node => node.selected);
  const selectedEdges = flowEdges.value.filter(edge => edge.selected);

  if (selectedNodes.length > 0) {
    // Create a new array without the selected nodes
    const newNodes = flowNodes.value.filter(node => !node.selected);
    updateNodes(newNodes);
  }

  if (selectedEdges.length > 0) {
    // Create a new array without the selected edges
    const newEdges = flowEdges.value.filter(edge => !edge.selected);
    updateEdges(newEdges);
  }
};

// Zoom utilities
const handleZoomIn = () => {
  vueFlow.zoomIn();
};

const handleZoomOut = () => {
  vueFlow.zoomOut();
};

const handleFitView = () => {
  vueFlow.fitView();
};

// Cancel editing and return to workflow list
const cancelEdit = () => {
  router.get(route('workflows.index'));
};

// Submit the workflow
const submit = () => {
  try {
    // Update form data with latest nodes and edges
    form.nodes = flowNodes.value.map(node => ({
      id: node.id,
      type: node.data?.type || '',
      position: node.position || { x: 0, y: 0 },
      data: node.data || {}
    }));

    form.edges = flowEdges.value;

    // Submit the form
    form.put(route('workflows.update', props.workflow.id), {
      onSuccess: () => {
        console.log("Workflow updated successfully");
      },
      onError: (errors) => {
        console.error("Error updating workflow:", errors);
      }
    });
  } catch (error) {
    console.error("Error in submit function:", error);
  }
};

// Check if the workflow is valid
const isValid = computed(() => {
  return form.name.trim() !== '' && flowNodes.value.length > 0;
});
</script>

<template>
  <Head title="Edit Workflow" />

  <BasePageHeading :title="`Edit: ${workflow.name}`" subtitle="Modify your workflow">
    <template #extra>
      <nav class="breadcrumb push">
        <Link :href="route('dashboard')" class="breadcrumb-item">
          <i class="fa fa-home"></i>
        </Link>
        <Link :href="route('workflows.index')" class="breadcrumb-item">
          Workflows
        </Link>
        <Link :href="route('workflows.show', workflow.id)" class="breadcrumb-item">
          {{ workflow.name }}
        </Link>
        <span class="breadcrumb-item active">Edit</span>
      </nav>
    </template>
  </BasePageHeading>

  <div class="content">
    <div class="row">
      <!-- Workflow Details Block -->
      <div class="col-md-12 mb-4">
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Workflow Details</h3>
          </div>
          <div class="block-content">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-4">
                  <label class="form-label" for="workflow-name">Workflow Name</label>
                  <input
                    type="text"
                    id="workflow-name"
                    class="form-control"
                    v-model="form.name"
                    placeholder="Enter workflow name"
                    required
                  >
                  <div v-if="form.errors.name" class="invalid-feedback d-block">
                    {{ form.errors.name }}
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-4">
                  <label class="form-label" for="workflow-description">Description (optional)</label>
                  <textarea
                    id="workflow-description"
                    class="form-control"
                    v-model="form.description"
                    placeholder="Describe what this workflow does"
                    rows="1"
                  ></textarea>
                  <div v-if="form.errors.description" class="invalid-feedback d-block">
                    {{ form.errors.description }}
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-4">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="workflow-active" v-model="form.is_active">
                    <label class="form-check-label" for="workflow-active">
                      Workflow is active
                    </label>
                  </div>
                  <div class="fs-sm text-muted mt-1">
                    When active, this workflow can be executed. Inactive workflows cannot be run.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Workflow Builder -->
      <div class="col-md-12">
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Workflow Builder</h3>
            <div class="block-options">
              <button
                type="button"
                class="btn btn-sm btn-alt-secondary me-1"
                @click="toggleFullscreen"
                title="Toggle Fullscreen"
              >
                <i :class="['fa', isFullscreen ? 'fa-compress' : 'fa-expand']"></i>
              </button>
              <button
                type="button"
                class="btn btn-sm btn-alt-primary"
                @click="submit"
                :disabled="!isValid || form.processing"
              >
                <i class="fa fa-fw fa-save opacity-50 me-1"></i> Save Workflow
              </button>
            </div>
          </div>
          <div class="block-content p-0">
            <div
              class="workflow-builder"
              :class="{'fullscreen': isFullscreen}"
              ref="workflowBuilder"
              style="height: 600px; position: relative;"
            >
              <!-- Sidebar -->
              <div class="workflow-sidebar bg-body-light border-end p-3" style="width: 260px; height: 100%; position: absolute; left: 0; top: 0; overflow-y: auto; z-index: 10;">
                <!-- Sidebar Tabs -->
                <ul class="nav nav-tabs nav-tabs-block" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button
                      class="nav-link"
                      :class="{'active': activePanel === 'nodes'}"
                      @click="activePanel = 'nodes'"
                    >
                      <i class="fa fa-cubes me-1"></i> Nodes
                    </button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button
                      class="nav-link"
                      :class="{'active': activePanel === 'properties'}"
                      @click="activePanel = 'properties'"
                    >
                      <i class="fa fa-cog me-1"></i> Properties
                    </button>
                  </li>
                </ul>

                <!-- Nodes Panel -->
                <div v-if="activePanel === 'nodes'" class="py-3">
                  <div class="fs-sm fw-semibold text-uppercase mb-2">Add Nodes</div>
                  <div class="d-grid gap-2">
                    <!-- Prompt Node -->
                    <button
                      type="button"
                      class="btn btn-alt-primary"
                      @click="addPromptNode"
                    >
                      <i class="fa fa-file-alt me-1"></i> Prompt
                    </button>

                    <!-- Condition Node -->
                    <button
                      type="button"
                      class="btn btn-alt-warning"
                      @click="addConditionNode"
                    >
                      <i class="fa fa-code-branch me-1"></i> Condition
                    </button>

                    <!-- Input Node -->
                    <button
                      type="button"
                      class="btn btn-alt-info"
                      @click="addInputNode"
                    >
                      <i class="fa fa-sign-in-alt me-1"></i> Input
                    </button>

                    <!-- Output Node -->
                    <button
                      type="button"
                      class="btn btn-alt-success"
                      @click="addOutputNode"
                    >
                      <i class="fa fa-sign-out-alt me-1"></i> Output
                    </button>

                    <!-- API Node -->
                    <button
                      type="button"
                      class="btn btn-alt-danger"
                      @click="addApiNode"
                    >
                      <i class="fa fa-globe me-1"></i> API Call
                    </button>

                    <!-- Transform Node -->
                    <button
                      type="button"
                      class="btn btn-alt-secondary"
                      @click="addTransformNode"
                    >
                      <i class="fa fa-exchange-alt me-1"></i> Transform
                    </button>
                  </div>

                  <div class="fs-sm fw-semibold text-uppercase mt-4 mb-2">Instructions</div>
                  <ul class="fs-sm text-muted ps-3">
                    <li>Add nodes to build your workflow</li>
                    <li>Connect nodes by dragging from one node to another</li>
                    <li>Configure nodes by selecting them</li>
                    <li>Drag nodes to move them around the canvas</li>
                    <li>Select an edge and press Delete to remove it</li>
                    <li>Select a node and press Delete to remove it</li>
                  </ul>
                </div>

                <!-- Properties Panel (for future implementation) -->
                <div v-if="activePanel === 'properties'" class="py-3">
                  <div class="alert alert-info d-flex">
                    <div class="flex-shrink-0">
                      <i class="fa fa-info-circle fa-fw"></i>
                    </div>
                    <div class="ms-2">
                      Select a node in the workflow to view and edit its properties
                    </div>
                  </div>

                  <div class="form-check form-switch mb-3">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="workflow-autosave"
                      v-model="form.settings.autoSave"
                    >
                    <label class="form-check-label" for="workflow-autosave">
                      Auto save workflow on execution
                    </label>
                  </div>

                  <div class="form-check form-switch">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="workflow-notify"
                      v-model="form.settings.notifyOnCompletion"
                    >
                    <label class="form-check-label" for="workflow-notify">
                      Notify on workflow completion
                    </label>
                  </div>
                </div>
              </div>

              <!-- Workflow Canvas -->
              <div style="margin-left: 260px; height: 100%">
                <VueFlow
                  v-model:nodes="flowNodes"
                  v-model:edges="flowEdges"
                  :nodeTypes="nodeTypes"
                  :default-zoom="1.5"
                  :min-zoom="0.2"
                  :max-zoom="4"
                  @nodesChange="onNodesChange"
                  @edgesChange="onEdgesChange"
                  @connect="onConnect"
                  @keydown="handleKeyDown"
                  :deleteKeyCode="['Backspace', 'Delete']"
                  class="workflow-canvas"
                >
                  <Background pattern-color="#aaa" gap="24" />
                  <MiniMap
                    height="120"
                    width="240"
                    nodeStrokeColor="#111"
                    nodeColor="#ddd"
                    nodeBorderRadius="3"
                  />
                  <Controls
                    :showZoom="true"
                    :showFitView="true"
                    :showInteractive="true"
                    :fitViewPadding="0.2"
                  />

                  <Panel position="top-right" class="p-2">
                    <div class="btn-group">
                      <button
                        type="button"
                        class="btn btn-sm btn-alt-secondary"
                        title="Zoom In"
                        @click="handleZoomIn"
                      >
                        <i class="fa fa-search-plus"></i>
                      </button>
                      <button
                        type="button"
                        class="btn btn-sm btn-alt-secondary"
                        title="Zoom Out"
                        @click="handleZoomOut"
                      >
                        <i class="fa fa-search-minus"></i>
                      </button>
                      <button
                        type="button"
                        class="btn btn-sm btn-alt-secondary"
                        title="Fit View"
                        @click="handleFitView"
                      >
                        <i class="fa fa-arrows-alt"></i>
                      </button>
                      <button
                        type="button"
                        class="btn btn-sm btn-alt-secondary"
                        title="Delete Selected"
                        @click="deleteSelected"
                      >
                        <i class="fa fa-trash"></i>
                      </button>
                    </div>
                  </Panel>
                </VueFlow>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between">
      <button
        type="button"
        class="btn btn-alt-secondary"
        @click="cancelEdit"
      >
        <i class="fa fa-arrow-left opacity-50 me-1"></i> Cancel
      </button>

      <button
        type="button"
        class="btn btn-alt-primary"
        @click="submit"
        :disabled="!isValid || form.processing"
      >
        <i class="fa fa-save opacity-50 me-1"></i> Save Workflow
      </button>
    </div>
  </div>
</template>

<style>
.workflow-canvas .vue-flow__node {
  border-radius: 5px;
  padding: 10px;
  font-size: 12px;
  text-align: center;
  border: 1px solid #ddd;
}

.workflow-canvas .vue-flow__node.selected,
.workflow-canvas .vue-flow__node.selected:hover {
  box-shadow: 0 0 0 2px #5c80d1;
}

.workflow-canvas .vue-flow__node-promptNode {
  background-color: rgba(92, 128, 209, 0.1);
  border-color: #5c80d1;
}

.workflow-canvas .vue-flow__node-conditionNode {
  background-color: rgba(219, 144, 56, 0.1);
  border-color: #db9038;
}

.workflow-canvas .vue-flow__node-inputNode {
  background-color: rgba(38, 169, 224, 0.1);
  border-color: #26a9e0;
}

.workflow-canvas .vue-flow__node-outputNode {
  background-color: rgba(66, 185, 131, 0.1);
  border-color: #42b983;
}

.workflow-canvas .vue-flow__node-apiNode {
  background-color: rgba(232, 74, 95, 0.1);
  border-color: #e84a5f;
}

.workflow-canvas .vue-flow__node-transformNode {
  background-color: rgba(108, 117, 125, 0.1);
  border-color: #6c757d;
}

.workflow-canvas .vue-flow__edge-path {
  stroke: #b1b1b7;
  stroke-width: 2;
  stroke-dasharray: none;
}

.workflow-canvas .vue-flow__edge.selected .vue-flow__edge-path {
  stroke: #5c80d1;
  stroke-width: 3;
}

.workflow-canvas .vue-flow__handle {
  width: 8px;
  height: 8px;
}

/* Fullscreen mode styles */
.workflow-builder.fullscreen {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 9999;
  height: 100vh !important;
  width: 100vw;
  background-color: white;
}

/* Control panel styles - ensure the icons are visible */
.vue-flow__controls {
  box-shadow: 0 0 2px 1px rgba(0, 0, 0, 0.08);
}

.vue-flow__controls-button {
  background: #fafafa;
  border: 1px solid #eee;
  border-radius: 5px;
  height: 24px;
  width: 24px;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 8px;
}

.vue-flow__controls-button svg {
  fill: #333;
  height: 14px;
  width: 14px;
}

/* MiniMap styling */
.vue-flow__minimap {
  background-color: #f8f9fa;
  border: 1px solid #ddd;
}

/* Selected elements styling */
.vue-flow__node.selected {
  box-shadow: 0 0 0 2px #5c80d1 !important;
}

.vue-flow__edge.selected .vue-flow__edge-path {
  stroke: #5c80d1 !important;
  stroke-width: 3px !important;
}
</style>
