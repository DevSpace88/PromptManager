// resources/js/views/Pages/Workflows/Create.vue
<script setup>
import { ref, onMounted, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

import { VueFlow, useVueFlow } from '@vue-flow/core';
import { Background } from '@vue-flow/background';
import { Controls } from '@vue-flow/controls';
import { MiniMap } from '@vue-flow/minimap';

import '@vue-flow/core/dist/style.css';
import { v4 as uuidv4 } from 'uuid';
import '@vue-flow/core/dist/style.css';
import '@vue-flow/core/dist/theme-default.css';

// Import custom node types
import PromptNode from '@/views/Pages/Workflows/Nodes/PromptNode.vue';
import ConditionNode from '@/views/Pages/Workflows/Nodes/ConditionNode.vue';
import InputNode from '@/views/Pages/Workflows/Nodes/InputNode.vue';
import OutputNode from '@/views/Pages/Workflows/Nodes/OutputNode.vue';
import ApiNode from '@/views/Pages/Workflows/Nodes/ApiNode.vue';
import TransformNode from '@/views/Pages/Workflows/Nodes/TransformNode.vue';

// Props
const props = defineProps({
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
  name: '',
  description: '',
  nodes: [],
  edges: [],
  settings: {
    autoSave: true,
    notifyOnCompletion: true
  }
});

// Node types registration
const nodeTypes = {
  promptNode: PromptNode,
  conditionNode: ConditionNode,
  inputNode: InputNode,
  outputNode: OutputNode,
  apiNode: ApiNode,
  transformNode: TransformNode
};

// Vue Flow instance
const {
  nodes,
  edges,
  addNodes,
  addEdges,
  onConnect,
  onNodesChange,
  onEdgesChange
} = useVueFlow();

// Sidebar state
const activePanel = ref('nodes');

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

  addNodes([nodeData]);
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
onConnect((params) => {
  addEdges([{
    id: `edge-${uuidv4()}`,
    source: params.source,
    target: params.target,
    animated: true
  }]);
});

// Track changes to nodes and edges
onNodesChange((changes) => {
  // Update form data when nodes change
  form.nodes = nodes.value.map(node => ({
    id: node.id,
    type: node.data.type,
    position: node.position,
    data: node.data
  }));
});

onEdgesChange((changes) => {
  // Update form data when edges change
  form.edges = edges.value;
});

// Set default nodes for new workflow
onMounted(() => {
  // Add initial nodes (Input and Output)
  const inputNode = createNode('input', {
    label: 'Input',
    variable: 'input',
    default_value: ''
  });

  const outputNode = createNode('output', {
    label: 'Output',
    variables: ['result']
  });

  // Position output node at the bottom
  outputNode.position.y = 300;

  // Update nodes in the form
  form.nodes = nodes.value.map(node => ({
    id: node.id,
    type: node.data.type,
    position: node.position,
    data: node.data
  }));
});
</script>

<template>
  <Head title="Create Workflow" />

  <BasePageHeading title="Create Workflow" subtitle="Design an AI workflow">
    <template #extra>
      <nav class="breadcrumb push">
        <Link :href="route('dashboard')" class="breadcrumb-item">
          <i class="fa fa-home"></i>
        </Link>
        <Link :href="route('workflows.index')" class="breadcrumb-item">
          Workflows
        </Link>
        <span class="breadcrumb-item active">Create</span>
      </nav>
    </template>
  </BasePageHeading>

  <div class="content">
    <div class="row">
      <!-- Workflow Details Form -->
      <div class="col-md-12 mb-4">
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Workflow Details</h3>
          </div>
          <div class="block-content">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-4">
                  <label class="form-label" for="workflow-name">Name</label>
                  <input type="text" class="form-control" id="workflow-name" v-model="form.name" placeholder="Enter workflow name">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-4">
                  <label class="form-label" for="workflow-description">Description</label>
                  <textarea class="form-control" id="workflow-description" v-model="form.description" rows="1" placeholder="Enter workflow description"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Flow Designer -->
      <div class="col-md-9">
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Flow Designer</h3>
            <div class="block-options">
              <button type="button" class="btn btn-sm btn-alt-primary" @click="form.post(route('workflows.store'))">
                <i class="fa fa-fw fa-save mr-1"></i> Save Workflow
              </button>
            </div>
          </div>
          <div class="block-content p-0">
            <div style="height: 600px; width: 100%;">
              <VueFlow
                v-model="nodes"
                :edges="edges"
                :node-types="nodeTypes"
                @connect="onConnect"
                @nodesChange="onNodesChange"
                @edgesChange="onEdgesChange"
              >
                <Background pattern-color="#aaa" gap="8" />
                <MiniMap />
                <Controls />
                <Panel position="top-right" class="bg-body-light p-2 rounded shadow-sm">
                  <div class="d-flex gap-1">
                    <button type="button" class="btn btn-sm btn-alt-primary" @click="form.post(route('workflows.store'))">
                      <i class="fa fa-fw fa-save"></i> Save
                    </button>
                  </div>
                </Panel>
              </VueFlow>
            </div>
          </div>
        </div>
      </div>

      <!-- Node Palette -->
      <div class="col-md-3">
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Node Palette</h3>
          </div>
          <div class="block-content">
            <div class="mb-3">
              <ul class="nav nav-tabs nav-tabs-block" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" :class="{ active: activePanel === 'nodes' }" @click="activePanel = 'nodes'" role="tab">Nodes</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" :class="{ active: activePanel === 'settings' }" @click="activePanel = 'settings'" role="tab">Settings</button>
                </li>
              </ul>
              <div class="block-content tab-content p-0">
                <div class="tab-pane active show" :class="{ 'active show': activePanel === 'nodes' }" role="tabpanel">
                  <div class="d-grid gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" @click="addInputNode">
                      <i class="fa fa-fw fa-arrow-right-to-bracket me-1"></i> Input
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info" @click="addPromptNode">
                      <i class="fa fa-fw fa-comment-dots me-1"></i> Prompt
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning" @click="addConditionNode">
                      <i class="fa fa-fw fa-code-branch me-1"></i> Condition
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" @click="addApiNode">
                      <i class="fa fa-fw fa-cloud me-1"></i> API Call
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-dark" @click="addTransformNode">
                      <i class="fa fa-fw fa-code me-1"></i> Transform
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success" @click="addOutputNode">
                      <i class="fa fa-fw fa-arrow-right-from-bracket me-1"></i> Output
                    </button>
                  </div>
                </div>
                <div class="tab-pane" :class="{ 'active show': activePanel === 'settings' }" role="tabpanel">
                  <div class="p-2">
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" v-model="form.settings.autoSave" id="workflow-auto-save">
                      <label class="form-check-label" for="workflow-auto-save">
                        Auto-save workflow
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" v-model="form.settings.notifyOnCompletion" id="workflow-notify">
                      <label class="form-check-label" for="workflow-notify">
                        Notify on completion
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
