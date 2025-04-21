<!--<script setup>-->
<!--import { ref, onMounted, computed } from 'vue';-->
<!--import { Head, Link, useForm } from '@inertiajs/vue3';-->

<!--// Import Vue Flow-->
<!--import { VueFlow, useVueFlow } from '@vue-flow/core';-->
<!--import { Background } from '@vue-flow/background';-->
<!--import { Controls } from '@vue-flow/controls';-->
<!--import { MiniMap } from '@vue-flow/minimap';-->

<!--import '@vue-flow/core/dist/style.css';-->
<!--import { v4 as uuidv4 } from 'uuid';-->
<!--import '@vue-flow/core/dist/style.css';-->
<!--import '@vue-flow/core/dist/theme-default.css';-->

<!--// Import custom node types-->
<!--import PromptNode from '@/views/Pages/Workflows/Nodes/PromptNode.vue';-->
<!--import ConditionNode from '@/views/Pages/Workflows/Nodes/ConditionNode.vue';-->
<!--import InputNode from '@/views/Pages/Workflows/Nodes/InputNode.vue';-->
<!--import OutputNode from '@/views/Pages/Workflows/Nodes/OutputNode.vue';-->
<!--import ApiNode from '@/views/Pages/Workflows/Nodes/ApiNode.vue';-->
<!--import TransformNode from '@/views/Pages/Workflows/Nodes/TransformNode.vue';-->

<!--// Props-->
<!--const props = defineProps({-->
<!--  prompts: {-->
<!--    type: Array,-->
<!--    default: () => []-->
<!--  },-->
<!--  apiKeys: {-->
<!--    type: Array,-->
<!--    default: () => []-->
<!--  }-->
<!--});-->

<!--// Form for workflow details-->
<!--const form = useForm({-->
<!--  name: '',-->
<!--  description: '',-->
<!--  nodes: [],-->
<!--  edges: [],-->
<!--  settings: {-->
<!--    autoSave: true,-->
<!--    notifyOnCompletion: true-->
<!--  }-->
<!--});-->

<!--// Node types registration-->
<!--const nodeTypes = {-->
<!--  promptNode: PromptNode,-->
<!--  conditionNode: ConditionNode,-->
<!--  inputNode: InputNode,-->
<!--  outputNode: OutputNode,-->
<!--  apiNode: ApiNode,-->
<!--  transformNode: TransformNode-->
<!--};-->

<!--// Vue Flow instance-->
<!--const {-->
<!--  nodes,-->
<!--  edges,-->
<!--  addNodes,-->
<!--  addEdges,-->
<!--  onConnect,-->
<!--  onNodesChange,-->
<!--  onEdgesChange-->
<!--} = useVueFlow();-->

<!--// Sidebar state-->
<!--const activePanel = ref('nodes');-->

<!--// Create a new node-->
<!--const createNode = (type, data = {}) => {-->
<!--  const id = `node-${uuidv4()}`;-->
<!--  const nodeData = {-->
<!--    id,-->
<!--    type: `${type}Node`,-->
<!--    position: { x: 250, y: 100 },-->
<!--    data: {-->
<!--      ...data,-->
<!--      label: data.label || `${type.charAt(0).toUpperCase() + type.slice(1)}`,-->
<!--      type: type-->
<!--    }-->
<!--  };-->

<!--  addNodes([nodeData]);-->
<!--  return nodeData;-->
<!--};-->

<!--// Add different types of nodes-->
<!--const addPromptNode = () => {-->
<!--  createNode('prompt', {-->
<!--    label: 'Prompt',-->
<!--    content: '',-->
<!--    prompt_id: null,-->
<!--    provider: 'openai',-->
<!--    model: 'gpt-4',-->
<!--    temperature: 0.7,-->
<!--    max_tokens: 2000,-->
<!--    output_variable: 'result'-->
<!--  });-->
<!--};-->

<!--const addConditionNode = () => {-->
<!--  createNode('condition', {-->
<!--    label: 'Condition',-->
<!--    condition: '',-->
<!--    true_path: null,-->
<!--    false_path: null-->
<!--  });-->
<!--};-->

<!--const addInputNode = () => {-->
<!--  createNode('input', {-->
<!--    label: 'Input',-->
<!--    variable: '',-->
<!--    default_value: ''-->
<!--  });-->
<!--};-->

<!--const addOutputNode = () => {-->
<!--  createNode('output', {-->
<!--    label: 'Output',-->
<!--    variables: []-->
<!--  });-->
<!--};-->

<!--const addApiNode = () => {-->
<!--  createNode('api', {-->
<!--    label: 'API Call',-->
<!--    url: '',-->
<!--    method: 'GET',-->
<!--    headers: {},-->
<!--    body: {},-->
<!--    output_variable: 'api_result'-->
<!--  });-->
<!--};-->

<!--const addTransformNode = () => {-->
<!--  createNode('transform', {-->
<!--    label: 'Transform',-->
<!--    input_variable: '',-->
<!--    output_variable: '',-->
<!--    transformation: 'json_parse',-->
<!--    regex: '',-->
<!--    code: ''-->
<!--  });-->
<!--};-->

<!--// Handle connection between nodes-->
<!--onConnect((params) => {-->
<!--  addEdges([{-->
<!--    id: `edge-${uuidv4()}`,-->
<!--    source: params.source,-->
<!--    target: params.target,-->
<!--    animated: true-->
<!--  }]);-->
<!--});-->

<!--// Track changes to nodes and edges-->
<!--onNodesChange((changes) => {-->
<!--  // Update form data when nodes change-->
<!--  form.nodes = nodes.value.map(node => ({-->
<!--    id: node.id,-->
<!--    type: node.data.type,-->
<!--    position: node.position,-->
<!--    data: node.data-->
<!--  }));-->
<!--});-->

<!--onEdgesChange((changes) => {-->
<!--  // Update form data when edges change-->
<!--  form.edges = edges.value;-->
<!--});-->

<!--// Set default nodes for new workflow-->
<!--onMounted(() => {-->
<!--  // Add initial nodes (Input and Output)-->
<!--  const inputNode = createNode('input', {-->
<!--    label: 'Input',-->
<!--    variable: 'input',-->
<!--    default_value: ''-->
<!--  });-->

<!--  const outputNode = createNode('output', {-->
<!--    label: 'Output',-->
<!--    variables: ['result']-->
<!--  });-->

<!--  // Position output node at the bottom-->
<!--  outputNode.position.y = 300;-->

<!--  // Update nodes in the form-->
<!--  form.nodes = nodes.value.map(node => ({-->
<!--    id: node.id,-->
<!--    type: node.data.type,-->
<!--    position: node.position,-->
<!--    data: node.data-->
<!--  }));-->
<!--});-->

<!--// Submit the workflow-->
<!--const submit = () => {-->
<!--  // Update form data with latest nodes and edges-->
<!--  form.nodes = nodes.value.map(node => ({-->
<!--    id: node.id,-->
<!--    type: node.data.type,-->
<!--    position: node.position,-->
<!--    data: node.data-->
<!--  }));-->

<!--  form.edges = edges.value;-->

<!--  // Submit the form-->
<!--  form.post(route('workflows.store'), {-->
<!--    onSuccess: () => {-->
<!--      // Success handling-->
<!--    }-->
<!--  });-->
<!--};-->

<!--// Check if the workflow is valid-->
<!--const isValid = computed(() => {-->
<!--  return form.name.trim() !== '' && nodes.value.length > 0;-->
<!--});-->
<!--</script>-->

<!--<template>-->
<!--  <Head title="Create Workflow" />-->

<!--  <BasePageHeading title="Create Workflow" subtitle="Build your AI automation flow">-->
<!--    <template #extra>-->
<!--      <nav class="breadcrumb push">-->
<!--        <Link :href="route('dashboard')" class="breadcrumb-item">-->
<!--          <i class="fa fa-home"></i>-->
<!--        </Link>-->
<!--        <Link :href="route('workflows.index')" class="breadcrumb-item">-->
<!--          Workflows-->
<!--        </Link>-->
<!--        <span class="breadcrumb-item active">Create</span>-->
<!--      </nav>-->
<!--    </template>-->
<!--  </BasePageHeading>-->

<!--  <div class="content">-->
<!--    <div class="row">-->
<!--      &lt;!&ndash; Workflow Details Block &ndash;&gt;-->
<!--      <div class="col-md-12 mb-4">-->
<!--        <div class="block block-rounded">-->
<!--          <div class="block-header block-header-default">-->
<!--            <h3 class="block-title">Workflow Details</h3>-->
<!--          </div>-->
<!--          <div class="block-content">-->
<!--            <div class="row">-->
<!--              <div class="col-md-6">-->
<!--                <div class="mb-4">-->
<!--                  <label class="form-label" for="workflow-name">Workflow Name</label>-->
<!--                  <input-->
<!--                    type="text"-->
<!--                    id="workflow-name"-->
<!--                    class="form-control"-->
<!--                    v-model="form.name"-->
<!--                    placeholder="Enter workflow name"-->
<!--                    required-->
<!--                  >-->
<!--                  <div v-if="form.errors.name" class="invalid-feedback d-block">-->
<!--                    {{ form.errors.name }}-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
<!--              <div class="col-md-6">-->
<!--                <div class="mb-4">-->
<!--                  <label class="form-label" for="workflow-description">Description (optional)</label>-->
<!--                  <textarea-->
<!--                    id="workflow-description"-->
<!--                    class="form-control"-->
<!--                    v-model="form.description"-->
<!--                    placeholder="Describe what this workflow does"-->
<!--                    rows="1"-->
<!--                  ></textarea>-->
<!--                  <div v-if="form.errors.description" class="invalid-feedback d-block">-->
<!--                    {{ form.errors.description }}-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->

<!--      &lt;!&ndash; Workflow Builder &ndash;&gt;-->
<!--      <div class="col-md-12">-->
<!--        <div class="block block-rounded">-->
<!--          <div class="block-header block-header-default">-->
<!--            <h3 class="block-title">Workflow Builder</h3>-->
<!--            <div class="block-options">-->
<!--              <button-->
<!--                type="button"-->
<!--                class="btn btn-sm btn-alt-primary"-->
<!--                @click="submit"-->
<!--                :disabled="!isValid || form.processing"-->
<!--                v-click-ripple-->
<!--              >-->
<!--                <i class="fa fa-fw fa-save opacity-50 me-1"></i> Save Workflow-->
<!--              </button>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="block-content p-0">-->
<!--            <div class="workflow-builder" style="height: 600px; position: relative;">-->
<!--              &lt;!&ndash; Sidebar &ndash;&gt;-->
<!--              <div class="workflow-sidebar bg-body-light border-end p-3" style="width: 260px; height: 100%; position: absolute; left: 0; top: 0; overflow-y: auto; z-index: 10;">-->
<!--                &lt;!&ndash; Sidebar Tabs &ndash;&gt;-->
<!--                <ul class="nav nav-tabs nav-tabs-block" role="tablist">-->
<!--                  <li class="nav-item" role="presentation">-->
<!--                    <button-->
<!--                      class="nav-link"-->
<!--                      :class="{'active': activePanel === 'nodes'}"-->
<!--                      @click="activePanel = 'nodes'"-->
<!--                    >-->
<!--                      <i class="fa fa-cubes me-1"></i> Nodes-->
<!--                    </button>-->
<!--                  </li>-->
<!--                  <li class="nav-item" role="presentation">-->
<!--                    <button-->
<!--                      class="nav-link"-->
<!--                      :class="{'active': activePanel === 'properties'}"-->
<!--                      @click="activePanel = 'properties'"-->
<!--                    >-->
<!--                      <i class="fa fa-cog me-1"></i> Properties-->
<!--                    </button>-->
<!--                  </li>-->
<!--                </ul>-->

<!--                &lt;!&ndash; Nodes Panel &ndash;&gt;-->
<!--                <div v-if="activePanel === 'nodes'" class="py-3">-->
<!--                  <div class="fs-sm fw-semibold text-uppercase mb-2">Add Nodes</div>-->
<!--                  <div class="d-grid gap-2">-->
<!--                    &lt;!&ndash; Prompt Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-primary"-->
<!--                      @click="addPromptNode"-->
<!--                      v-click-ripple-->
<!--                    >-->
<!--                      <i class="fa fa-file-alt me-1"></i> Prompt-->
<!--                    </button>-->

<!--                    &lt;!&ndash; Condition Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-warning"-->
<!--                      @click="addConditionNode"-->
<!--                      v-click-ripple-->
<!--                    >-->
<!--                      <i class="fa fa-code-branch me-1"></i> Condition-->
<!--                    </button>-->

<!--                    &lt;!&ndash; Input Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-info"-->
<!--                      @click="addInputNode"-->
<!--                      v-click-ripple-->
<!--                    >-->
<!--                      <i class="fa fa-sign-in-alt me-1"></i> Input-->
<!--                    </button>-->

<!--                    &lt;!&ndash; Output Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-success"-->
<!--                      @click="addOutputNode"-->
<!--                      v-click-ripple-->
<!--                    >-->
<!--                      <i class="fa fa-sign-out-alt me-1"></i> Output-->
<!--                    </button>-->

<!--                    &lt;!&ndash; API Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-danger"-->
<!--                      @click="addApiNode"-->
<!--                      v-click-ripple-->
<!--                    >-->
<!--                      <i class="fa fa-globe me-1"></i> API Call-->
<!--                    </button>-->

<!--                    &lt;!&ndash; Transform Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-secondary"-->
<!--                      @click="addTransformNode"-->
<!--                      v-click-ripple-->
<!--                    >-->
<!--                      <i class="fa fa-exchange-alt me-1"></i> Transform-->
<!--                    </button>-->
<!--                  </div>-->

<!--                  <div class="fs-sm fw-semibold text-uppercase mt-4 mb-2">Instructions</div>-->
<!--                  <ul class="fs-sm text-muted ps-3">-->
<!--                    <li>Add nodes to build your workflow</li>-->
<!--                    <li>Connect nodes by dragging from one node to another</li>-->
<!--                    <li>Configure nodes by selecting them</li>-->
<!--                    <li>Input nodes provide variables to your workflow</li>-->
<!--                    <li>Output nodes define the workflow result</li>-->
<!--                  </ul>-->
<!--                </div>-->

<!--                &lt;!&ndash; Properties Panel (for future implementation) &ndash;&gt;-->
<!--                <div v-if="activePanel === 'properties'" class="py-3">-->
<!--                  <div class="alert alert-info d-flex">-->
<!--                    <div class="flex-shrink-0">-->
<!--                      <i class="fa fa-info-circle fa-fw"></i>-->
<!--                    </div>-->
<!--                    <div class="ms-2">-->
<!--                      Select a node in the workflow to view and edit its properties-->
<!--                    </div>-->
<!--                  </div>-->

<!--                  <div class="form-check form-switch mb-3">-->
<!--                    <input-->
<!--                      class="form-check-input"-->
<!--                      type="checkbox"-->
<!--                      id="workflow-autosave"-->
<!--                      v-model="form.settings.autoSave"-->
<!--                    >-->
<!--                    <label class="form-check-label" for="workflow-autosave">-->
<!--                      Auto save workflow on execution-->
<!--                    </label>-->
<!--                  </div>-->

<!--                  <div class="form-check form-switch">-->
<!--                    <input-->
<!--                      class="form-check-input"-->
<!--                      type="checkbox"-->
<!--                      id="workflow-notify"-->
<!--                      v-model="form.settings.notifyOnCompletion"-->
<!--                    >-->
<!--                    <label class="form-check-label" for="workflow-notify">-->
<!--                      Notify on workflow completion-->
<!--                    </label>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->

<!--              &lt;!&ndash; Workflow Canvas &ndash;&gt;-->
<!--              <div style="margin-left: 260px; height: 100%">-->
<!--                <VueFlow-->
<!--                  v-model="nodes"-->
<!--                  v-model:edges="edges"-->
<!--                  :nodeTypes="nodeTypes"-->
<!--                  :default-zoom="1.5"-->
<!--                  :min-zoom="0.2"-->
<!--                  :max-zoom="4"-->
<!--                  @nodesChange="onNodesChange"-->
<!--                  @edgesChange="onEdgesChange"-->
<!--                  @connect="onConnect"-->
<!--                  class="workflow-canvas"-->
<!--                >-->
<!--                  <Background pattern-color="#aaa" gap="24" />-->
<!--                  <MiniMap height="120" width="240" />-->
<!--                  <Controls />-->

<!--                  <Panel position="top-right" class="p-2">-->
<!--                    <div class="btn-group">-->
<!--                      <button-->
<!--                        type="button"-->
<!--                        class="btn btn-sm btn-alt-secondary"-->
<!--                        title="Zoom In"-->
<!--                        @click="() => {}"-->
<!--                      >-->
<!--                        <i class="fa fa-search-plus"></i>-->
<!--                      </button>-->
<!--                      <button-->
<!--                        type="button"-->
<!--                        class="btn btn-sm btn-alt-secondary"-->
<!--                        title="Zoom Out"-->
<!--                        @click="() => {}"-->
<!--                      >-->
<!--                        <i class="fa fa-search-minus"></i>-->
<!--                      </button>-->
<!--                      <button-->
<!--                        type="button"-->
<!--                        class="btn btn-sm btn-alt-secondary"-->
<!--                        title="Fit View"-->
<!--                        @click="() => {}"-->
<!--                      >-->
<!--                        <i class="fa fa-arrows-alt"></i>-->
<!--                      </button>-->
<!--                    </div>-->
<!--                  </Panel>-->
<!--                </VueFlow>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->

<!--    &lt;!&ndash; Action Buttons &ndash;&gt;-->
<!--    <div class="d-flex justify-content-between">-->
<!--      <Link-->
<!--        :href="route('workflows.index')"-->
<!--        class="btn btn-alt-secondary"-->
<!--        v-click-ripple-->
<!--      >-->
<!--        <i class="fa fa-arrow-left opacity-50 me-1"></i> Cancel-->
<!--      </Link>-->

<!--      <button-->
<!--        type="button"-->
<!--        class="btn btn-alt-primary"-->
<!--        @click="submit"-->
<!--        :disabled="!isValid || form.processing"-->
<!--        v-click-ripple-->
<!--      >-->
<!--        <i class="fa fa-save opacity-50 me-1"></i> Save Workflow-->
<!--      </button>-->
<!--    </div>-->
<!--  </div>-->
<!--</template>-->

<!--<style>-->
<!--.workflow-canvas .vue-flow__node {-->
<!--  border-radius: 5px;-->
<!--  padding: 10px;-->
<!--  font-size: 12px;-->
<!--  text-align: center;-->
<!--  border: 1px solid #ddd;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node.selected,-->
<!--.workflow-canvas .vue-flow__node.selected:hover {-->
<!--  box-shadow: 0 0 0 2px #5c80d1;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-promptNode {-->
<!--  background-color: rgba(92, 128, 209, 0.1);-->
<!--  border-color: #5c80d1;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-conditionNode {-->
<!--  background-color: rgba(219, 144, 56, 0.1);-->
<!--  border-color: #db9038;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-inputNode {-->
<!--  background-color: rgba(38, 169, 224, 0.1);-->
<!--  border-color: #26a9e0;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-outputNode {-->
<!--  background-color: rgba(66, 185, 131, 0.1);-->
<!--  border-color: #42b983;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-apiNode {-->
<!--  background-color: rgba(232, 74, 95, 0.1);-->
<!--  border-color: #e84a5f;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-transformNode {-->
<!--  background-color: rgba(108, 117, 125, 0.1);-->
<!--  border-color: #6c757d;-->
<!--}-->

<!--.workflow-canvas .vue-flow__edge-path {-->
<!--  stroke: #b1b1b7;-->
<!--  stroke-width: 2;-->
<!--  stroke-dasharray: none;-->
<!--}-->

<!--.workflow-canvas .vue-flow__edge.selected .vue-flow__edge-path {-->
<!--  stroke: #5c80d1;-->
<!--  stroke-width: 3;-->
<!--}-->

<!--.workflow-canvas .vue-flow__handle {-->
<!--  width: 8px;-->
<!--  height: 8px;-->
<!--}-->
<!--</style>-->

<!--<script setup>-->
<!--import { ref, onMounted, computed } from 'vue';-->
<!--import { Head, Link, useForm } from '@inertiajs/vue3';-->

<!--// Import Vue Flow-->
<!--import { VueFlow, useVueFlow, Panel } from '@vue-flow/core';-->
<!--import { Background } from '@vue-flow/background';-->
<!--import { Controls } from '@vue-flow/controls';-->
<!--import { MiniMap } from '@vue-flow/minimap';-->

<!--import '@vue-flow/core/dist/style.css';-->
<!--import '@vue-flow/core/dist/theme-default.css';-->
<!--import { v4 as uuidv4 } from 'uuid';-->

<!--// Import custom node types-->
<!--import PromptNode from '@/views/Pages/Workflows/Nodes/PromptNode.vue';-->
<!--import ConditionNode from '@/views/Pages/Workflows/Nodes/ConditionNode.vue';-->
<!--import InputNode from '@/views/Pages/Workflows/Nodes/InputNode.vue';-->
<!--import OutputNode from '@/views/Pages/Workflows/Nodes/OutputNode.vue';-->
<!--import ApiNode from '@/views/Pages/Workflows/Nodes/ApiNode.vue';-->
<!--import TransformNode from '@/views/Pages/Workflows/Nodes/TransformNode.vue';-->


<!--// Props-->
<!--const props = defineProps({-->
<!--  prompts: {-->
<!--    type: Array,-->
<!--    default: () => []-->
<!--  },-->
<!--  apiKeys: {-->
<!--    type: Array,-->
<!--    default: () => []-->
<!--  }-->
<!--});-->

<!--// Form for workflow details-->
<!--const form = useForm({-->
<!--  name: '',-->
<!--  description: '',-->
<!--  nodes: [],-->
<!--  edges: [],-->
<!--  settings: {-->
<!--    autoSave: true,-->
<!--    notifyOnCompletion: true-->
<!--  }-->
<!--});-->

<!--// Node types registration-->
<!--const nodeTypes = {-->
<!--  promptNode: PromptNode,-->
<!--  conditionNode: ConditionNode,-->
<!--  inputNode: InputNode,-->
<!--  outputNode: OutputNode,-->
<!--  apiNode: ApiNode,-->
<!--  transformNode: TransformNode-->
<!--};-->

<!--// Vue Flow instance-->
<!--const {-->
<!--  nodes,-->
<!--  edges,-->
<!--  addNodes,-->
<!--  addEdges,-->
<!--  onConnect,-->
<!--  onNodesChange,-->
<!--  onEdgesChange,-->
<!--  zoomIn,-->
<!--  zoomOut,-->
<!--  fitView-->
<!--} = useVueFlow();-->

<!--// Sidebar state-->
<!--const activePanel = ref('nodes');-->

<!--// Create a new node-->
<!--const createNode = (type, data = {}) => {-->
<!--  const id = `node-${uuidv4()}`;-->
<!--  const nodeData = {-->
<!--    id,-->
<!--    type: `${type}Node`,-->
<!--    position: { x: 250, y: 100 },-->
<!--    data: {-->
<!--      ...data,-->
<!--      label: data.label || `${type.charAt(0).toUpperCase() + type.slice(1)}`,-->
<!--      type: type-->
<!--    }-->
<!--  };-->

<!--  addNodes([nodeData]);-->
<!--  return nodeData;-->
<!--};-->

<!--// Add different types of nodes-->
<!--const addPromptNode = () => {-->
<!--  createNode('prompt', {-->
<!--    label: 'Prompt',-->
<!--    content: '',-->
<!--    prompt_id: null,-->
<!--    provider: 'openai',-->
<!--    model: 'gpt-4',-->
<!--    temperature: 0.7,-->
<!--    max_tokens: 2000,-->
<!--    output_variable: 'result'-->
<!--  });-->
<!--};-->

<!--const addConditionNode = () => {-->
<!--  createNode('condition', {-->
<!--    label: 'Condition',-->
<!--    condition: '',-->
<!--    true_path: null,-->
<!--    false_path: null-->
<!--  });-->
<!--};-->

<!--const addInputNode = () => {-->
<!--  createNode('input', {-->
<!--    label: 'Input',-->
<!--    variable: '',-->
<!--    default_value: ''-->
<!--  });-->
<!--};-->

<!--const addOutputNode = () => {-->
<!--  createNode('output', {-->
<!--    label: 'Output',-->
<!--    variables: []-->
<!--  });-->
<!--};-->

<!--const addApiNode = () => {-->
<!--  createNode('api', {-->
<!--    label: 'API Call',-->
<!--    url: '',-->
<!--    method: 'GET',-->
<!--    headers: {},-->
<!--    body: {},-->
<!--    output_variable: 'api_result'-->
<!--  });-->
<!--};-->

<!--const addTransformNode = () => {-->
<!--  createNode('transform', {-->
<!--    label: 'Transform',-->
<!--    input_variable: '',-->
<!--    output_variable: '',-->
<!--    transformation: 'json_parse',-->
<!--    regex: '',-->
<!--    code: ''-->
<!--  });-->
<!--};-->

<!--// Handle connection between nodes-->
<!--onConnect((params) => {-->
<!--  addEdges([{-->
<!--    id: `edge-${uuidv4()}`,-->
<!--    source: params.source,-->
<!--    target: params.target,-->
<!--    animated: true-->
<!--  }]);-->
<!--});-->

<!--// Track changes to nodes and edges-->
<!--onNodesChange((changes) => {-->
<!--  // Update form data when nodes change-->
<!--  form.nodes = nodes.value.map(node => ({-->
<!--    id: node.id,-->
<!--    type: node.data.type,-->
<!--    position: node.position,-->
<!--    data: node.data-->
<!--  }));-->
<!--});-->

<!--onEdgesChange((changes) => {-->
<!--  // Update form data when edges change-->
<!--  form.edges = edges.value;-->
<!--});-->

<!--// Set default nodes for new workflow-->
<!--onMounted(() => {-->
<!--  // Add initial nodes (Input and Output)-->
<!--  const inputNode = createNode('input', {-->
<!--    label: 'Input',-->
<!--    variable: 'input',-->
<!--    default_value: ''-->
<!--  });-->

<!--  const outputNode = createNode('output', {-->
<!--    label: 'Output',-->
<!--    variables: ['result']-->
<!--  });-->

<!--  // Position output node at the bottom-->
<!--  outputNode.position.y = 300;-->

<!--  // Update nodes in the form-->
<!--  form.nodes = nodes.value.map(node => ({-->
<!--    id: node.id,-->
<!--    type: node.data.type,-->
<!--    position: node.position,-->
<!--    data: node.data-->
<!--  }));-->
<!--});-->

<!--// Submit the workflow-->
<!--const submit = () => {-->
<!--  // Update form data with latest nodes and edges-->
<!--  form.nodes = nodes.value.map(node => ({-->
<!--    id: node.id,-->
<!--    type: node.data.type,-->
<!--    position: node.position,-->
<!--    data: node.data-->
<!--  }));-->

<!--  form.edges = edges.value;-->

<!--  // Submit the form-->
<!--  form.post(route('workflows.store'), {-->
<!--    onSuccess: () => {-->
<!--      // Success handling-->
<!--    }-->
<!--  });-->
<!--};-->

<!--// Zoom utilities-->
<!--const handleZoomIn = () => {-->
<!--  zoomIn();-->
<!--};-->

<!--const handleZoomOut = () => {-->
<!--  zoomOut();-->
<!--};-->

<!--const handleFitView = () => {-->
<!--  fitView();-->
<!--};-->

<!--// Check if the workflow is valid-->
<!--const isValid = computed(() => {-->
<!--  return form.name.trim() !== '' && nodes.value.length > 0;-->
<!--});-->
<!--</script>-->

<!--<template>-->
<!--  <Head title="Create Workflow" />-->

<!--  <BasePageHeading title="Create Workflow" subtitle="Build your AI automation flow">-->
<!--    <template #extra>-->
<!--      <nav class="breadcrumb push">-->
<!--        <Link :href="route('dashboard')" class="breadcrumb-item">-->
<!--          <i class="fa fa-home"></i>-->
<!--        </Link>-->
<!--        <Link :href="route('workflows.index')" class="breadcrumb-item">-->
<!--          Workflows-->
<!--        </Link>-->
<!--        <span class="breadcrumb-item active">Create</span>-->
<!--      </nav>-->
<!--    </template>-->
<!--  </BasePageHeading>-->

<!--  <div class="content">-->
<!--    <div class="row">-->
<!--      &lt;!&ndash; Workflow Details Block &ndash;&gt;-->
<!--      <div class="col-md-12 mb-4">-->
<!--        <div class="block block-rounded">-->
<!--          <div class="block-header block-header-default">-->
<!--            <h3 class="block-title">Workflow Details</h3>-->
<!--          </div>-->
<!--          <div class="block-content">-->
<!--            <div class="row">-->
<!--              <div class="col-md-6">-->
<!--                <div class="mb-4">-->
<!--                  <label class="form-label" for="workflow-name">Workflow Name</label>-->
<!--                  <input-->
<!--                    type="text"-->
<!--                    id="workflow-name"-->
<!--                    class="form-control"-->
<!--                    v-model="form.name"-->
<!--                    placeholder="Enter workflow name"-->
<!--                    required-->
<!--                  >-->
<!--                  <div v-if="form.errors.name" class="invalid-feedback d-block">-->
<!--                    {{ form.errors.name }}-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
<!--              <div class="col-md-6">-->
<!--                <div class="mb-4">-->
<!--                  <label class="form-label" for="workflow-description">Description (optional)</label>-->
<!--                  <textarea-->
<!--                    id="workflow-description"-->
<!--                    class="form-control"-->
<!--                    v-model="form.description"-->
<!--                    placeholder="Describe what this workflow does"-->
<!--                    rows="1"-->
<!--                  ></textarea>-->
<!--                  <div v-if="form.errors.description" class="invalid-feedback d-block">-->
<!--                    {{ form.errors.description }}-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->

<!--      &lt;!&ndash; Workflow Builder &ndash;&gt;-->
<!--      <div class="col-md-12">-->
<!--        <div class="block block-rounded">-->
<!--          <div class="block-header block-header-default">-->
<!--            <h3 class="block-title">Workflow Builder</h3>-->
<!--            <div class="block-options">-->
<!--              <button-->
<!--                type="button"-->
<!--                class="btn btn-sm btn-alt-primary"-->
<!--                @click="submit"-->
<!--                :disabled="!isValid || form.processing"-->
<!--              >-->
<!--                <i class="fa fa-fw fa-save opacity-50 me-1"></i> Save Workflow-->
<!--              </button>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="block-content p-0">-->
<!--            <div class="workflow-builder" style="height: 600px; position: relative;">-->
<!--              &lt;!&ndash; Sidebar &ndash;&gt;-->
<!--              <div class="workflow-sidebar bg-body-light border-end p-3" style="width: 260px; height: 100%; position: absolute; left: 0; top: 0; overflow-y: auto; z-index: 10;">-->
<!--                &lt;!&ndash; Sidebar Tabs &ndash;&gt;-->
<!--                <ul class="nav nav-tabs nav-tabs-block" role="tablist">-->
<!--                  <li class="nav-item" role="presentation">-->
<!--                    <button-->
<!--                      class="nav-link"-->
<!--                      :class="{'active': activePanel === 'nodes'}"-->
<!--                      @click="activePanel = 'nodes'"-->
<!--                    >-->
<!--                      <i class="fa fa-cubes me-1"></i> Nodes-->
<!--                    </button>-->
<!--                  </li>-->
<!--                  <li class="nav-item" role="presentation">-->
<!--                    <button-->
<!--                      class="nav-link"-->
<!--                      :class="{'active': activePanel === 'properties'}"-->
<!--                      @click="activePanel = 'properties'"-->
<!--                    >-->
<!--                      <i class="fa fa-cog me-1"></i> Properties-->
<!--                    </button>-->
<!--                  </li>-->
<!--                </ul>-->

<!--                &lt;!&ndash; Nodes Panel &ndash;&gt;-->
<!--                <div v-if="activePanel === 'nodes'" class="py-3">-->
<!--                  <div class="fs-sm fw-semibold text-uppercase mb-2">Add Nodes</div>-->
<!--                  <div class="d-grid gap-2">-->
<!--                    &lt;!&ndash; Prompt Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-primary"-->
<!--                      @click="addPromptNode"-->
<!--                    >-->
<!--                      <i class="fa fa-file-alt me-1"></i> Prompt-->
<!--                    </button>-->

<!--                    &lt;!&ndash; Condition Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-warning"-->
<!--                      @click="addConditionNode"-->
<!--                    >-->
<!--                      <i class="fa fa-code-branch me-1"></i> Condition-->
<!--                    </button>-->

<!--                    &lt;!&ndash; Input Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-info"-->
<!--                      @click="addInputNode"-->
<!--                    >-->
<!--                      <i class="fa fa-sign-in-alt me-1"></i> Input-->
<!--                    </button>-->

<!--                    &lt;!&ndash; Output Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-success"-->
<!--                      @click="addOutputNode"-->
<!--                    >-->
<!--                      <i class="fa fa-sign-out-alt me-1"></i> Output-->
<!--                    </button>-->

<!--                    &lt;!&ndash; API Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-danger"-->
<!--                      @click="addApiNode"-->
<!--                    >-->
<!--                      <i class="fa fa-globe me-1"></i> API Call-->
<!--                    </button>-->

<!--                    &lt;!&ndash; Transform Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-secondary"-->
<!--                      @click="addTransformNode"-->
<!--                    >-->
<!--                      <i class="fa fa-exchange-alt me-1"></i> Transform-->
<!--                    </button>-->
<!--                  </div>-->

<!--                  <div class="fs-sm fw-semibold text-uppercase mt-4 mb-2">Instructions</div>-->
<!--                  <ul class="fs-sm text-muted ps-3">-->
<!--                    <li>Add nodes to build your workflow</li>-->
<!--                    <li>Connect nodes by dragging from one node to another</li>-->
<!--                    <li>Configure nodes by selecting them</li>-->
<!--                    <li>Input nodes provide variables to your workflow</li>-->
<!--                    <li>Output nodes define the workflow result</li>-->
<!--                  </ul>-->
<!--                </div>-->

<!--                &lt;!&ndash; Properties Panel (for future implementation) &ndash;&gt;-->
<!--                <div v-if="activePanel === 'properties'" class="py-3">-->
<!--                  <div class="alert alert-info d-flex">-->
<!--                    <div class="flex-shrink-0">-->
<!--                      <i class="fa fa-info-circle fa-fw"></i>-->
<!--                    </div>-->
<!--                    <div class="ms-2">-->
<!--                      Select a node in the workflow to view and edit its properties-->
<!--                    </div>-->
<!--                  </div>-->

<!--                  <div class="form-check form-switch mb-3">-->
<!--                    <input-->
<!--                      class="form-check-input"-->
<!--                      type="checkbox"-->
<!--                      id="workflow-autosave"-->
<!--                      v-model="form.settings.autoSave"-->
<!--                    >-->
<!--                    <label class="form-check-label" for="workflow-autosave">-->
<!--                      Auto save workflow on execution-->
<!--                    </label>-->
<!--                  </div>-->

<!--                  <div class="form-check form-switch">-->
<!--                    <input-->
<!--                      class="form-check-input"-->
<!--                      type="checkbox"-->
<!--                      id="workflow-notify"-->
<!--                      v-model="form.settings.notifyOnCompletion"-->
<!--                    >-->
<!--                    <label class="form-check-label" for="workflow-notify">-->
<!--                      Notify on workflow completion-->
<!--                    </label>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->

<!--              &lt;!&ndash; Workflow Canvas &ndash;&gt;-->
<!--              <div style="margin-left: 260px; height: 100%">-->
<!--                <VueFlow-->
<!--                  v-model="nodes"-->
<!--                  v-model:edges="edges"-->
<!--                  :nodeTypes="nodeTypes"-->
<!--                  :default-zoom="1.5"-->
<!--                  :min-zoom="0.2"-->
<!--                  :max-zoom="4"-->
<!--                  @nodesChange="onNodesChange"-->
<!--                  @edgesChange="onEdgesChange"-->
<!--                  @connect="onConnect"-->
<!--                  class="workflow-canvas"-->
<!--                >-->
<!--                  <Background pattern-color="#aaa" gap="24" />-->
<!--                  <MiniMap height="120" width="240" />-->
<!--                  <Controls />-->

<!--                  <Panel position="top-right" class="p-2">-->
<!--                    <div class="btn-group">-->
<!--                      <button-->
<!--                        type="button"-->
<!--                        class="btn btn-sm btn-alt-secondary"-->
<!--                        title="Zoom In"-->
<!--                        @click="handleZoomIn"-->
<!--                      >-->
<!--                        <i class="fa fa-search-plus"></i>-->
<!--                      </button>-->
<!--                      <button-->
<!--                        type="button"-->
<!--                        class="btn btn-sm btn-alt-secondary"-->
<!--                        title="Zoom Out"-->
<!--                        @click="handleZoomOut"-->
<!--                      >-->
<!--                        <i class="fa fa-search-minus"></i>-->
<!--                      </button>-->
<!--                      <button-->
<!--                        type="button"-->
<!--                        class="btn btn-sm btn-alt-secondary"-->
<!--                        title="Fit View"-->
<!--                        @click="handleFitView"-->
<!--                      >-->
<!--                        <i class="fa fa-arrows-alt"></i>-->
<!--                      </button>-->
<!--                    </div>-->
<!--                  </Panel>-->
<!--                </VueFlow>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->

<!--    &lt;!&ndash; Action Buttons &ndash;&gt;-->
<!--    <div class="d-flex justify-content-between">-->
<!--      <Link-->
<!--        :href="route('workflows.index')"-->
<!--        class="btn btn-alt-secondary"-->
<!--      >-->
<!--        <i class="fa fa-arrow-left opacity-50 me-1"></i> Cancel-->
<!--      </Link>-->

<!--      <button-->
<!--        type="button"-->
<!--        class="btn btn-alt-primary"-->
<!--        @click="submit"-->
<!--        :disabled="!isValid || form.processing"-->
<!--      >-->
<!--        <i class="fa fa-save opacity-50 me-1"></i> Save Workflow-->
<!--      </button>-->
<!--    </div>-->
<!--  </div>-->
<!--</template>-->

<!--<style>-->
<!--.workflow-canvas .vue-flow__node {-->
<!--  border-radius: 5px;-->
<!--  padding: 10px;-->
<!--  font-size: 12px;-->
<!--  text-align: center;-->
<!--  border: 1px solid #ddd;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node.selected,-->
<!--.workflow-canvas .vue-flow__node.selected:hover {-->
<!--  box-shadow: 0 0 0 2px #5c80d1;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-promptNode {-->
<!--  background-color: rgba(92, 128, 209, 0.1);-->
<!--  border-color: #5c80d1;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-conditionNode {-->
<!--  background-color: rgba(219, 144, 56, 0.1);-->
<!--  border-color: #db9038;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-inputNode {-->
<!--  background-color: rgba(38, 169, 224, 0.1);-->
<!--  border-color: #26a9e0;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-outputNode {-->
<!--  background-color: rgba(66, 185, 131, 0.1);-->
<!--  border-color: #42b983;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-apiNode {-->
<!--  background-color: rgba(232, 74, 95, 0.1);-->
<!--  border-color: #e84a5f;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-transformNode {-->
<!--  background-color: rgba(108, 117, 125, 0.1);-->
<!--  border-color: #6c757d;-->
<!--}-->

<!--.workflow-canvas .vue-flow__edge-path {-->
<!--  stroke: #b1b1b7;-->
<!--  stroke-width: 2;-->
<!--  stroke-dasharray: none;-->
<!--}-->

<!--.workflow-canvas .vue-flow__edge.selected .vue-flow__edge-path {-->
<!--  stroke: #5c80d1;-->
<!--  stroke-width: 3;-->
<!--}-->

<!--.workflow-canvas .vue-flow__handle {-->
<!--  width: 8px;-->
<!--  height: 8px;-->
<!--}-->
<!--</style>-->


<!--<script setup>-->
<!--import { ref, onMounted, computed } from 'vue';-->
<!--import { Head, Link, useForm } from '@inertiajs/vue3';-->

<!--// Import Vue Flow-->
<!--import { VueFlow, useVueFlow } from '@vue-flow/core';-->
<!--import { Panel } from '@vue-flow/core';-->
<!--import { Background } from '@vue-flow/background';-->
<!--import { Controls } from '@vue-flow/controls';-->
<!--import { MiniMap } from '@vue-flow/minimap';-->

<!--import '@vue-flow/core/dist/style.css';-->
<!--import '@vue-flow/core/dist/theme-default.css';-->
<!--import { v4 as uuidv4 } from 'uuid';-->

<!--// Import custom node types-->

<!--import PromptNode from '@/views/Pages/Workflows/Nodes/PromptNode.vue';-->
<!--import ConditionNode from '@/views//Pages/Workflows/Nodes/ConditionNode.vue';-->
<!--import InputNode from '@/views//Pages/Workflows/Nodes/InputNode.vue';-->
<!--import OutputNode from '@/views//Pages/Workflows/Nodes/OutputNode.vue';-->
<!--import ApiNode from '@/views//Pages/Workflows/Nodes/ApiNode.vue';-->
<!--import TransformNode from '@/views//Pages/Workflows/Nodes/TransformNode.vue';-->
<!--// Update path to match your project structure-->
<!--import BasePageHeading from '@/components/BasePageHeading.vue';-->

<!--// Props-->
<!--const props = defineProps({-->
<!--  prompts: {-->
<!--    type: Array,-->
<!--    default: () => []-->
<!--  },-->
<!--  apiKeys: {-->
<!--    type: Array,-->
<!--    default: () => []-->
<!--  }-->
<!--});-->

<!--// Form for workflow details-->
<!--const form = useForm({-->
<!--  name: '',-->
<!--  description: '',-->
<!--  nodes: [],-->
<!--  edges: [],-->
<!--  settings: {-->
<!--    autoSave: true,-->
<!--    notifyOnCompletion: true-->
<!--  }-->
<!--});-->

<!--// Node types registration-->
<!--const nodeTypes = {-->
<!--  promptNode: PromptNode,-->
<!--  conditionNode: ConditionNode,-->
<!--  inputNode: InputNode,-->
<!--  outputNode: OutputNode,-->
<!--  apiNode: ApiNode,-->
<!--  transformNode: TransformNode-->
<!--};-->

<!--// Vue Flow instance with all necessary methods-->
<!--const {-->
<!--  nodes,-->
<!--  edges,-->
<!--  addNodes,-->
<!--  addEdges,-->
<!--  onConnect,-->
<!--  onNodesChange,-->
<!--  onEdgesChange,-->
<!--  zoomIn,-->
<!--  zoomOut,-->
<!--  fitView,-->
<!--  getNode,-->
<!--  getNodes,-->
<!--  findNode,-->
<!--  setNodes-->
<!--} = useVueFlow();-->

<!--// Sidebar state-->
<!--const activePanel = ref('nodes');-->

<!--// Create a new node-->
<!--const createNode = (type, data = {}) => {-->
<!--  const id = `node-${uuidv4()}`;-->
<!--  const nodeData = {-->
<!--    id,-->
<!--    type: `${type}Node`,-->
<!--    position: { x: 250, y: 100 },-->
<!--    data: {-->
<!--      ...data,-->
<!--      label: data.label || `${type.charAt(0).toUpperCase() + type.slice(1)}`,-->
<!--      type: type-->
<!--    }-->
<!--  };-->

<!--  addNodes([nodeData]);-->
<!--  return nodeData;-->
<!--};-->

<!--// Add different types of nodes-->
<!--const addPromptNode = () => {-->
<!--  createNode('prompt', {-->
<!--    label: 'Prompt',-->
<!--    content: '',-->
<!--    prompt_id: null,-->
<!--    provider: 'openai',-->
<!--    model: 'gpt-4',-->
<!--    temperature: 0.7,-->
<!--    max_tokens: 2000,-->
<!--    output_variable: 'result'-->
<!--  });-->
<!--};-->

<!--const addConditionNode = () => {-->
<!--  createNode('condition', {-->
<!--    label: 'Condition',-->
<!--    condition: '',-->
<!--    true_path: null,-->
<!--    false_path: null-->
<!--  });-->
<!--};-->

<!--const addInputNode = () => {-->
<!--  createNode('input', {-->
<!--    label: 'Input',-->
<!--    variable: '',-->
<!--    default_value: ''-->
<!--  });-->
<!--};-->

<!--const addOutputNode = () => {-->
<!--  createNode('output', {-->
<!--    label: 'Output',-->
<!--    variables: []-->
<!--  });-->
<!--};-->

<!--const addApiNode = () => {-->
<!--  createNode('api', {-->
<!--    label: 'API Call',-->
<!--    url: '',-->
<!--    method: 'GET',-->
<!--    headers: {},-->
<!--    body: {},-->
<!--    output_variable: 'api_result'-->
<!--  });-->
<!--};-->

<!--const addTransformNode = () => {-->
<!--  createNode('transform', {-->
<!--    label: 'Transform',-->
<!--    input_variable: '',-->
<!--    output_variable: '',-->
<!--    transformation: 'json_parse',-->
<!--    regex: '',-->
<!--    code: ''-->
<!--  });-->
<!--};-->

<!--// Handle connection between nodes-->
<!--onConnect((params) => {-->
<!--  addEdges([{-->
<!--    id: `edge-${uuidv4()}`,-->
<!--    source: params.source,-->
<!--    target: params.target,-->
<!--    animated: true-->
<!--  }]);-->
<!--});-->

<!--// Track changes to nodes and edges-->
<!--onNodesChange((changes) => {-->
<!--  // Update form data when nodes change-->
<!--  form.nodes = nodes.value.map(node => ({-->
<!--    id: node.id,-->
<!--    type: node.data.type,-->
<!--    position: node.position,-->
<!--    data: node.data-->
<!--  }));-->
<!--});-->

<!--onEdgesChange((changes) => {-->
<!--  // Update form data when edges change-->
<!--  form.edges = edges.value;-->
<!--});-->

<!--// Set default nodes for new workflow-->
<!--onMounted(() => {-->
<!--  // Add initial nodes (Input and Output)-->
<!--  const inputNode = createNode('input', {-->
<!--    label: 'Input',-->
<!--    variable: 'input',-->
<!--    default_value: ''-->
<!--  });-->

<!--  const outputNode = createNode('output', {-->
<!--    label: 'Output',-->
<!--    variables: ['result']-->
<!--  });-->

<!--  // Position output node at the bottom-->
<!--  outputNode.position.y = 300;-->

<!--  // Update nodes in the form-->
<!--  form.nodes = nodes.value.map(node => ({-->
<!--    id: node.id,-->
<!--    type: node.data.type,-->
<!--    position: node.position,-->
<!--    data: node.data-->
<!--  }));-->
<!--});-->

<!--// Submit the workflow-->
<!--const submit = () => {-->
<!--  // Update form data with latest nodes and edges-->
<!--  form.nodes = nodes.value.map(node => ({-->
<!--    id: node.id,-->
<!--    type: node.data.type,-->
<!--    position: node.position,-->
<!--    data: node.data-->
<!--  }));-->

<!--  form.edges = edges.value;-->

<!--  // Submit the form-->
<!--  form.post(route('workflows.store'), {-->
<!--    onSuccess: () => {-->
<!--      // Success handling-->
<!--    }-->
<!--  });-->
<!--};-->

<!--// Zoom utilities-->
<!--const handleZoomIn = () => {-->
<!--  zoomIn();-->
<!--};-->

<!--const handleZoomOut = () => {-->
<!--  zoomOut();-->
<!--};-->

<!--const handleFitView = () => {-->
<!--  fitView();-->
<!--};-->

<!--// Check if the workflow is valid-->
<!--const isValid = computed(() => {-->
<!--  return form.name.trim() !== '' && nodes.value.length > 0;-->
<!--});-->
<!--</script>-->

<!--<template>-->
<!--  <Head title="Create Workflow" />-->

<!--  <BasePageHeading title="Create Workflow" subtitle="Build your AI automation flow">-->
<!--    <template #extra>-->
<!--      <nav class="breadcrumb push">-->
<!--        <Link :href="route('dashboard')" class="breadcrumb-item">-->
<!--          <i class="fa fa-home"></i>-->
<!--        </Link>-->
<!--        <Link :href="route('workflows.index')" class="breadcrumb-item">-->
<!--          Workflows-->
<!--        </Link>-->
<!--        <span class="breadcrumb-item active">Create</span>-->
<!--      </nav>-->
<!--    </template>-->
<!--  </BasePageHeading>-->

<!--  <div class="content">-->
<!--    <div class="row">-->
<!--      &lt;!&ndash; Workflow Details Block &ndash;&gt;-->
<!--      <div class="col-md-12 mb-4">-->
<!--        <div class="block block-rounded">-->
<!--          <div class="block-header block-header-default">-->
<!--            <h3 class="block-title">Workflow Details</h3>-->
<!--          </div>-->
<!--          <div class="block-content">-->
<!--            <div class="row">-->
<!--              <div class="col-md-6">-->
<!--                <div class="mb-4">-->
<!--                  <label class="form-label" for="workflow-name">Workflow Name</label>-->
<!--                  <input-->
<!--                    type="text"-->
<!--                    id="workflow-name"-->
<!--                    class="form-control"-->
<!--                    v-model="form.name"-->
<!--                    placeholder="Enter workflow name"-->
<!--                    required-->
<!--                  >-->
<!--                  <div v-if="form.errors.name" class="invalid-feedback d-block">-->
<!--                    {{ form.errors.name }}-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
<!--              <div class="col-md-6">-->
<!--                <div class="mb-4">-->
<!--                  <label class="form-label" for="workflow-description">Description (optional)</label>-->
<!--                  <textarea-->
<!--                    id="workflow-description"-->
<!--                    class="form-control"-->
<!--                    v-model="form.description"-->
<!--                    placeholder="Describe what this workflow does"-->
<!--                    rows="1"-->
<!--                  ></textarea>-->
<!--                  <div v-if="form.errors.description" class="invalid-feedback d-block">-->
<!--                    {{ form.errors.description }}-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->

<!--      &lt;!&ndash; Workflow Builder &ndash;&gt;-->
<!--      <div class="col-md-12">-->
<!--        <div class="block block-rounded">-->
<!--          <div class="block-header block-header-default">-->
<!--            <h3 class="block-title">Workflow Builder</h3>-->
<!--            <div class="block-options">-->
<!--              <button-->
<!--                type="button"-->
<!--                class="btn btn-sm btn-alt-primary"-->
<!--                @click="submit"-->
<!--                :disabled="!isValid || form.processing"-->
<!--              >-->
<!--                <i class="fa fa-fw fa-save opacity-50 me-1"></i> Save Workflow-->
<!--              </button>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="block-content p-0">-->
<!--            <div class="workflow-builder" style="height: 600px; position: relative;">-->
<!--              &lt;!&ndash; Sidebar &ndash;&gt;-->
<!--              <div class="workflow-sidebar bg-body-light border-end p-3" style="width: 260px; height: 100%; position: absolute; left: 0; top: 0; overflow-y: auto; z-index: 10;">-->
<!--                &lt;!&ndash; Sidebar Tabs &ndash;&gt;-->
<!--                <ul class="nav nav-tabs nav-tabs-block" role="tablist">-->
<!--                  <li class="nav-item" role="presentation">-->
<!--                    <button-->
<!--                      class="nav-link"-->
<!--                      :class="{'active': activePanel === 'nodes'}"-->
<!--                      @click="activePanel = 'nodes'"-->
<!--                    >-->
<!--                      <i class="fa fa-cubes me-1"></i> Nodes-->
<!--                    </button>-->
<!--                  </li>-->
<!--                  <li class="nav-item" role="presentation">-->
<!--                    <button-->
<!--                      class="nav-link"-->
<!--                      :class="{'active': activePanel === 'properties'}"-->
<!--                      @click="activePanel = 'properties'"-->
<!--                    >-->
<!--                      <i class="fa fa-cog me-1"></i> Properties-->
<!--                    </button>-->
<!--                  </li>-->
<!--                </ul>-->

<!--                &lt;!&ndash; Nodes Panel &ndash;&gt;-->
<!--                <div v-if="activePanel === 'nodes'" class="py-3">-->
<!--                  <div class="fs-sm fw-semibold text-uppercase mb-2">Add Nodes</div>-->
<!--                  <div class="d-grid gap-2">-->
<!--                    &lt;!&ndash; Prompt Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-primary"-->
<!--                      @click="addPromptNode"-->
<!--                    >-->
<!--                      <i class="fa fa-file-alt me-1"></i> Prompt-->
<!--                    </button>-->

<!--                    &lt;!&ndash; Condition Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-warning"-->
<!--                      @click="addConditionNode"-->
<!--                    >-->
<!--                      <i class="fa fa-code-branch me-1"></i> Condition-->
<!--                    </button>-->

<!--                    &lt;!&ndash; Input Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-info"-->
<!--                      @click="addInputNode"-->
<!--                    >-->
<!--                      <i class="fa fa-sign-in-alt me-1"></i> Input-->
<!--                    </button>-->

<!--                    &lt;!&ndash; Output Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-success"-->
<!--                      @click="addOutputNode"-->
<!--                    >-->
<!--                      <i class="fa fa-sign-out-alt me-1"></i> Output-->
<!--                    </button>-->

<!--                    &lt;!&ndash; API Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-danger"-->
<!--                      @click="addApiNode"-->
<!--                    >-->
<!--                      <i class="fa fa-globe me-1"></i> API Call-->
<!--                    </button>-->

<!--                    &lt;!&ndash; Transform Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-secondary"-->
<!--                      @click="addTransformNode"-->
<!--                    >-->
<!--                      <i class="fa fa-exchange-alt me-1"></i> Transform-->
<!--                    </button>-->
<!--                  </div>-->

<!--                  <div class="fs-sm fw-semibold text-uppercase mt-4 mb-2">Instructions</div>-->
<!--                  <ul class="fs-sm text-muted ps-3">-->
<!--                    <li>Add nodes to build your workflow</li>-->
<!--                    <li>Connect nodes by dragging from one node to another</li>-->
<!--                    <li>Configure nodes by selecting them</li>-->
<!--                    <li>Input nodes provide variables to your workflow</li>-->
<!--                    <li>Output nodes define the workflow result</li>-->
<!--                  </ul>-->
<!--                </div>-->

<!--                &lt;!&ndash; Properties Panel (for future implementation) &ndash;&gt;-->
<!--                <div v-if="activePanel === 'properties'" class="py-3">-->
<!--                  <div class="alert alert-info d-flex">-->
<!--                    <div class="flex-shrink-0">-->
<!--                      <i class="fa fa-info-circle fa-fw"></i>-->
<!--                    </div>-->
<!--                    <div class="ms-2">-->
<!--                      Select a node in the workflow to view and edit its properties-->
<!--                    </div>-->
<!--                  </div>-->

<!--                  <div class="form-check form-switch mb-3">-->
<!--                    <input-->
<!--                      class="form-check-input"-->
<!--                      type="checkbox"-->
<!--                      id="workflow-autosave"-->
<!--                      v-model="form.settings.autoSave"-->
<!--                    >-->
<!--                    <label class="form-check-label" for="workflow-autosave">-->
<!--                      Auto save workflow on execution-->
<!--                    </label>-->
<!--                  </div>-->

<!--                  <div class="form-check form-switch">-->
<!--                    <input-->
<!--                      class="form-check-input"-->
<!--                      type="checkbox"-->
<!--                      id="workflow-notify"-->
<!--                      v-model="form.settings.notifyOnCompletion"-->
<!--                    >-->
<!--                    <label class="form-check-label" for="workflow-notify">-->
<!--                      Notify on workflow completion-->
<!--                    </label>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->

<!--              &lt;!&ndash; Workflow Canvas &ndash;&gt;-->
<!--              <div style="margin-left: 260px; height: 100%">-->
<!--                <VueFlow-->
<!--                  v-model="nodes"-->
<!--                  v-model:edges="edges"-->
<!--                  :nodeTypes="nodeTypes"-->
<!--                  :default-zoom="1.5"-->
<!--                  :min-zoom="0.2"-->
<!--                  :max-zoom="4"-->
<!--                  @nodesChange="onNodesChange"-->
<!--                  @edgesChange="onEdgesChange"-->
<!--                  @connect="onConnect"-->
<!--                  class="workflow-canvas"-->
<!--                >-->
<!--                  <Background pattern-color="#aaa" gap="24" />-->
<!--                  <MiniMap height="120" width="240" />-->
<!--                  <Controls />-->

<!--                  <Panel position="top-right" class="p-2">-->
<!--                    <div class="btn-group">-->
<!--                      <button-->
<!--                        type="button"-->
<!--                        class="btn btn-sm btn-alt-secondary"-->
<!--                        title="Zoom In"-->
<!--                        @click="handleZoomIn"-->
<!--                      >-->
<!--                        <i class="fa fa-search-plus"></i>-->
<!--                      </button>-->
<!--                      <button-->
<!--                        type="button"-->
<!--                        class="btn btn-sm btn-alt-secondary"-->
<!--                        title="Zoom Out"-->
<!--                        @click="handleZoomOut"-->
<!--                      >-->
<!--                        <i class="fa fa-search-minus"></i>-->
<!--                      </button>-->
<!--                      <button-->
<!--                        type="button"-->
<!--                        class="btn btn-sm btn-alt-secondary"-->
<!--                        title="Fit View"-->
<!--                        @click="handleFitView"-->
<!--                      >-->
<!--                        <i class="fa fa-arrows-alt"></i>-->
<!--                      </button>-->
<!--                    </div>-->
<!--                  </Panel>-->
<!--                </VueFlow>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->

<!--    &lt;!&ndash; Action Buttons &ndash;&gt;-->
<!--    <div class="d-flex justify-content-between">-->
<!--      <Link-->
<!--        :href="route('workflows.index')"-->
<!--        class="btn btn-alt-secondary"-->
<!--      >-->
<!--        <i class="fa fa-arrow-left opacity-50 me-1"></i> Cancel-->
<!--      </Link>-->

<!--      <button-->
<!--        type="button"-->
<!--        class="btn btn-alt-primary"-->
<!--        @click="submit"-->
<!--        :disabled="!isValid || form.processing"-->
<!--      >-->
<!--        <i class="fa fa-save opacity-50 me-1"></i> Save Workflow-->
<!--      </button>-->
<!--    </div>-->
<!--  </div>-->
<!--</template>-->

<!--<style>-->
<!--.workflow-canvas .vue-flow__node {-->
<!--  border-radius: 5px;-->
<!--  padding: 10px;-->
<!--  font-size: 12px;-->
<!--  text-align: center;-->
<!--  border: 1px solid #ddd;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node.selected,-->
<!--.workflow-canvas .vue-flow__node.selected:hover {-->
<!--  box-shadow: 0 0 0 2px #5c80d1;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-promptNode {-->
<!--  background-color: rgba(92, 128, 209, 0.1);-->
<!--  border-color: #5c80d1;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-conditionNode {-->
<!--  background-color: rgba(219, 144, 56, 0.1);-->
<!--  border-color: #db9038;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-inputNode {-->
<!--  background-color: rgba(38, 169, 224, 0.1);-->
<!--  border-color: #26a9e0;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-outputNode {-->
<!--  background-color: rgba(66, 185, 131, 0.1);-->
<!--  border-color: #42b983;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-apiNode {-->
<!--  background-color: rgba(232, 74, 95, 0.1);-->
<!--  border-color: #e84a5f;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-transformNode {-->
<!--  background-color: rgba(108, 117, 125, 0.1);-->
<!--  border-color: #6c757d;-->
<!--}-->

<!--.workflow-canvas .vue-flow__edge-path {-->
<!--  stroke: #b1b1b7;-->
<!--  stroke-width: 2;-->
<!--  stroke-dasharray: none;-->
<!--}-->

<!--.workflow-canvas .vue-flow__edge.selected .vue-flow__edge-path {-->
<!--  stroke: #5c80d1;-->
<!--  stroke-width: 3;-->
<!--}-->

<!--.workflow-canvas .vue-flow__handle {-->
<!--  width: 8px;-->
<!--  height: 8px;-->
<!--}-->
<!--</style>-->


<!--<script setup>-->
<!--import { ref, onMounted, computed } from 'vue';-->
<!--import { Head, Link, useForm } from '@inertiajs/vue3';-->

<!--// Import Vue Flow-->
<!--import { VueFlow, useVueFlow } from '@vue-flow/core';-->
<!--import { Panel } from '@vue-flow/core';-->
<!--import { Background } from '@vue-flow/background';-->
<!--import { Controls } from '@vue-flow/controls';-->
<!--import { MiniMap } from '@vue-flow/minimap';-->

<!--import '@vue-flow/core/dist/style.css';-->
<!--import '@vue-flow/core/dist/theme-default.css';-->
<!--import { v4 as uuidv4 } from 'uuid';-->

<!--// Import custom node types-->
<!--import PromptNode from '@/views//Pages/Workflows/Nodes/PromptNode.vue';-->
<!--import ConditionNode from '@/views//Pages/Workflows/Nodes/ConditionNode.vue';-->
<!--import InputNode from '@/views//Pages/Workflows/Nodes/InputNode.vue';-->
<!--import OutputNode from '@/views//Pages/Workflows/Nodes/OutputNode.vue';-->
<!--import ApiNode from '@/views//Pages/Workflows/Nodes/ApiNode.vue';-->
<!--import TransformNode from '@/views//Pages/Workflows/Nodes/TransformNode.vue';-->
<!--// Update path to match your project structure-->
<!--import BasePageHeading from '@/components/BasePageHeading.vue';-->

<!--// Props-->
<!--const props = defineProps({-->
<!--  prompts: {-->
<!--    type: Array,-->
<!--    default: () => []-->
<!--  },-->
<!--  apiKeys: {-->
<!--    type: Array,-->
<!--    default: () => []-->
<!--  }-->
<!--});-->

<!--// Form for workflow details-->
<!--const form = useForm({-->
<!--  name: '',-->
<!--  description: '',-->
<!--  nodes: [],-->
<!--  edges: [],-->
<!--  settings: {-->
<!--    autoSave: true,-->
<!--    notifyOnCompletion: true-->
<!--  }-->
<!--});-->

<!--// Node types registration-->
<!--const nodeTypes = {-->
<!--  promptNode: PromptNode,-->
<!--  conditionNode: ConditionNode,-->
<!--  inputNode: InputNode,-->
<!--  outputNode: OutputNode,-->
<!--  apiNode: ApiNode,-->
<!--  transformNode: TransformNode-->
<!--};-->

<!--// Create a Vue Flow instance - use composition API consistently-->
<!--const vueFlowInstance = useVueFlow();-->

<!--// Destructure the methods we need with proper references-->
<!--const nodes = vueFlowInstance.nodes;-->
<!--const edges = vueFlowInstance.edges;-->
<!--const setNodes = vueFlowInstance.setNodes;-->
<!--const addEdges = vueFlowInstance.addEdges;-->
<!--const onConnect = vueFlowInstance.onConnect;-->
<!--const onNodesChange = vueFlowInstance.onNodesChange;-->
<!--const onEdgesChange = vueFlowInstance.onEdgesChange;-->
<!--const getNodes = vueFlowInstance.getNodes;-->

<!--// Sidebar state-->
<!--const activePanel = ref('nodes');-->

<!--// Create a new node safely-->
<!--const createNode = (type, data = {}) => {-->
<!--  const id = `node-${uuidv4()}`;-->
<!--  const nodeData = {-->
<!--    id,-->
<!--    type: `${type}Node`,-->
<!--    position: { x: 250, y: 100 },-->
<!--    data: {-->
<!--      ...data,-->
<!--      label: data.label || `${type.charAt(0).toUpperCase() + type.slice(1)}`,-->
<!--      type: type-->
<!--    }-->
<!--  };-->

<!--  // Use setNodes to safely add a new node instead of using addNodes directly-->
<!--  setNodes((nds) => [...nds, nodeData]);-->

<!--  return nodeData;-->
<!--};-->

<!--// Add different types of nodes-->
<!--const addPromptNode = () => {-->
<!--  createNode('prompt', {-->
<!--    label: 'Prompt',-->
<!--    content: '',-->
<!--    prompt_id: null,-->
<!--    provider: 'openai',-->
<!--    model: 'gpt-4',-->
<!--    temperature: 0.7,-->
<!--    max_tokens: 2000,-->
<!--    output_variable: 'result'-->
<!--  });-->
<!--};-->

<!--const addConditionNode = () => {-->
<!--  createNode('condition', {-->
<!--    label: 'Condition',-->
<!--    condition: '',-->
<!--    true_path: null,-->
<!--    false_path: null-->
<!--  });-->
<!--};-->

<!--const addInputNode = () => {-->
<!--  createNode('input', {-->
<!--    label: 'Input',-->
<!--    variable: '',-->
<!--    default_value: ''-->
<!--  });-->
<!--};-->

<!--const addOutputNode = () => {-->
<!--  createNode('output', {-->
<!--    label: 'Output',-->
<!--    variables: []-->
<!--  });-->
<!--};-->

<!--const addApiNode = () => {-->
<!--  createNode('api', {-->
<!--    label: 'API Call',-->
<!--    url: '',-->
<!--    method: 'GET',-->
<!--    headers: {},-->
<!--    body: {},-->
<!--    output_variable: 'api_result'-->
<!--  });-->
<!--};-->

<!--const addTransformNode = () => {-->
<!--  createNode('transform', {-->
<!--    label: 'Transform',-->
<!--    input_variable: '',-->
<!--    output_variable: '',-->
<!--    transformation: 'json_parse',-->
<!--    regex: '',-->
<!--    code: ''-->
<!--  });-->
<!--};-->

<!--// Handle connection between nodes safely using setEdges-->
<!--onConnect((params) => {-->
<!--  const newEdge = {-->
<!--    id: `edge-${uuidv4()}`,-->
<!--    source: params.source,-->
<!--    target: params.target,-->
<!--    animated: true-->
<!--  };-->

<!--  setEdges((eds) => [...eds, newEdge]);-->
<!--});-->

<!--// Track changes to nodes and edges-->
<!--onNodesChange((changes) => {-->
<!--  // Update form data when nodes change-->
<!--  form.nodes = nodes.value.map(node => ({-->
<!--    id: node.id,-->
<!--    type: node.data.type,-->
<!--    position: node.position,-->
<!--    data: node.data-->
<!--  }));-->
<!--});-->

<!--onEdgesChange((changes) => {-->
<!--  // Update form data when edges change-->
<!--  form.edges = edges.value;-->
<!--});-->

<!--// Set default nodes for new workflow-->
<!--onMounted(() => {-->
<!--  // Create the initial nodes directly-->
<!--  const inputNodeId = `node-${uuidv4()}`;-->
<!--  const outputNodeId = `node-${uuidv4()}`;-->

<!--  // Set initial nodes with correct structure-->
<!--  setNodes([-->
<!--    {-->
<!--      id: inputNodeId,-->
<!--      type: 'inputNode',-->
<!--      position: { x: 250, y: 100 },-->
<!--      data: {-->
<!--        label: 'Input',-->
<!--        variable: 'input',-->
<!--        default_value: '',-->
<!--        type: 'input'-->
<!--      }-->
<!--    },-->
<!--    {-->
<!--      id: outputNodeId,-->
<!--      type: 'outputNode',-->
<!--      position: { x: 250, y: 300 },-->
<!--      data: {-->
<!--        label: 'Output',-->
<!--        variables: ['result'],-->
<!--        type: 'output'-->
<!--      }-->
<!--    }-->
<!--  ]);-->

<!--  // Wait until the nodes are available before updating the form-->
<!--  setTimeout(() => {-->
<!--    if (nodes.value?.length > 0) {-->
<!--      form.nodes = nodes.value.map(node => ({-->
<!--        id: node.id,-->
<!--        type: node.data.type,-->
<!--        position: node.position,-->
<!--        data: node.data-->
<!--      }));-->
<!--    }-->
<!--  }, 100);-->
<!--});-->

<!--// Submit the workflow-->
<!--const submit = () => {-->
<!--  try {-->
<!--    // Safely update form data with latest nodes and edges-->
<!--    if (nodes.value?.length > 0) {-->
<!--      form.nodes = nodes.value.map(node => ({-->
<!--        id: node.id,-->
<!--        type: node.data?.type || '',-->
<!--        position: node.position || { x: 0, y: 0 },-->
<!--        data: node.data || {}-->
<!--      }));-->
<!--    }-->

<!--    // Safely add edges-->
<!--    if (edges.value) {-->
<!--      form.edges = edges.value;-->
<!--    }-->

<!--    // Submit the form-->
<!--    form.post(route('workflows.store'), {-->
<!--      onSuccess: () => {-->
<!--        // Success handling-->
<!--        console.log("Workflow saved successfully");-->
<!--      },-->
<!--      onError: (errors) => {-->
<!--        console.error("Error saving workflow:", errors);-->
<!--      }-->
<!--    });-->
<!--  } catch (error) {-->
<!--    console.error("Error in submit function:", error);-->
<!--  }-->
<!--};-->

<!--// Zoom utilities - use direct reference to the instance-->
<!--const handleZoomIn = () => {-->
<!--  vueFlowInstance.zoomIn?.();-->
<!--};-->

<!--const handleZoomOut = () => {-->
<!--  vueFlowInstance.zoomOut?.();-->
<!--};-->

<!--const handleFitView = () => {-->
<!--  vueFlowInstance.fitView?.();-->
<!--};-->

<!--// Check if the workflow is valid-->
<!--const isValid = computed(() => {-->
<!--  return form.name.trim() !== '' && nodes.value.length > 0;-->
<!--});-->
<!--</script>-->

<!--<template>-->
<!--  <Head title="Create Workflow" />-->

<!--  <BasePageHeading title="Create Workflow" subtitle="Build your AI automation flow">-->
<!--    <template #extra>-->
<!--      <nav class="breadcrumb push">-->
<!--        <Link :href="route('dashboard')" class="breadcrumb-item">-->
<!--          <i class="fa fa-home"></i>-->
<!--        </Link>-->
<!--        <Link :href="route('workflows.index')" class="breadcrumb-item">-->
<!--          Workflows-->
<!--        </Link>-->
<!--        <span class="breadcrumb-item active">Create</span>-->
<!--      </nav>-->
<!--    </template>-->
<!--  </BasePageHeading>-->

<!--  <div class="content">-->
<!--    <div class="row">-->
<!--      &lt;!&ndash; Workflow Details Block &ndash;&gt;-->
<!--      <div class="col-md-12 mb-4">-->
<!--        <div class="block block-rounded">-->
<!--          <div class="block-header block-header-default">-->
<!--            <h3 class="block-title">Workflow Details</h3>-->
<!--          </div>-->
<!--          <div class="block-content">-->
<!--            <div class="row">-->
<!--              <div class="col-md-6">-->
<!--                <div class="mb-4">-->
<!--                  <label class="form-label" for="workflow-name">Workflow Name</label>-->
<!--                  <input-->
<!--                    type="text"-->
<!--                    id="workflow-name"-->
<!--                    class="form-control"-->
<!--                    v-model="form.name"-->
<!--                    placeholder="Enter workflow name"-->
<!--                    required-->
<!--                  >-->
<!--                  <div v-if="form.errors.name" class="invalid-feedback d-block">-->
<!--                    {{ form.errors.name }}-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
<!--              <div class="col-md-6">-->
<!--                <div class="mb-4">-->
<!--                  <label class="form-label" for="workflow-description">Description (optional)</label>-->
<!--                  <textarea-->
<!--                    id="workflow-description"-->
<!--                    class="form-control"-->
<!--                    v-model="form.description"-->
<!--                    placeholder="Describe what this workflow does"-->
<!--                    rows="1"-->
<!--                  ></textarea>-->
<!--                  <div v-if="form.errors.description" class="invalid-feedback d-block">-->
<!--                    {{ form.errors.description }}-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->

<!--      &lt;!&ndash; Workflow Builder &ndash;&gt;-->
<!--      <div class="col-md-12">-->
<!--        <div class="block block-rounded">-->
<!--          <div class="block-header block-header-default">-->
<!--            <h3 class="block-title">Workflow Builder</h3>-->
<!--            <div class="block-options">-->
<!--              <button-->
<!--                type="button"-->
<!--                class="btn btn-sm btn-alt-primary"-->
<!--                @click="submit"-->
<!--                :disabled="!isValid || form.processing"-->
<!--              >-->
<!--                <i class="fa fa-fw fa-save opacity-50 me-1"></i> Save Workflow-->
<!--              </button>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="block-content p-0">-->
<!--            <div class="workflow-builder" style="height: 600px; position: relative;">-->
<!--              &lt;!&ndash; Sidebar &ndash;&gt;-->
<!--              <div class="workflow-sidebar bg-body-light border-end p-3" style="width: 260px; height: 100%; position: absolute; left: 0; top: 0; overflow-y: auto; z-index: 10;">-->
<!--                &lt;!&ndash; Sidebar Tabs &ndash;&gt;-->
<!--                <ul class="nav nav-tabs nav-tabs-block" role="tablist">-->
<!--                  <li class="nav-item" role="presentation">-->
<!--                    <button-->
<!--                      class="nav-link"-->
<!--                      :class="{'active': activePanel === 'nodes'}"-->
<!--                      @click="activePanel = 'nodes'"-->
<!--                    >-->
<!--                      <i class="fa fa-cubes me-1"></i> Nodes-->
<!--                    </button>-->
<!--                  </li>-->
<!--                  <li class="nav-item" role="presentation">-->
<!--                    <button-->
<!--                      class="nav-link"-->
<!--                      :class="{'active': activePanel === 'properties'}"-->
<!--                      @click="activePanel = 'properties'"-->
<!--                    >-->
<!--                      <i class="fa fa-cog me-1"></i> Properties-->
<!--                    </button>-->
<!--                  </li>-->
<!--                </ul>-->

<!--                &lt;!&ndash; Nodes Panel &ndash;&gt;-->
<!--                <div v-if="activePanel === 'nodes'" class="py-3">-->
<!--                  <div class="fs-sm fw-semibold text-uppercase mb-2">Add Nodes</div>-->
<!--                  <div class="d-grid gap-2">-->
<!--                    &lt;!&ndash; Prompt Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-primary"-->
<!--                      @click="addPromptNode"-->
<!--                    >-->
<!--                      <i class="fa fa-file-alt me-1"></i> Prompt-->
<!--                    </button>-->

<!--                    &lt;!&ndash; Condition Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-warning"-->
<!--                      @click="addConditionNode"-->
<!--                    >-->
<!--                      <i class="fa fa-code-branch me-1"></i> Condition-->
<!--                    </button>-->

<!--                    &lt;!&ndash; Input Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-info"-->
<!--                      @click="addInputNode"-->
<!--                    >-->
<!--                      <i class="fa fa-sign-in-alt me-1"></i> Input-->
<!--                    </button>-->

<!--                    &lt;!&ndash; Output Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-success"-->
<!--                      @click="addOutputNode"-->
<!--                    >-->
<!--                      <i class="fa fa-sign-out-alt me-1"></i> Output-->
<!--                    </button>-->

<!--                    &lt;!&ndash; API Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-danger"-->
<!--                      @click="addApiNode"-->
<!--                    >-->
<!--                      <i class="fa fa-globe me-1"></i> API Call-->
<!--                    </button>-->

<!--                    &lt;!&ndash; Transform Node &ndash;&gt;-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-alt-secondary"-->
<!--                      @click="addTransformNode"-->
<!--                    >-->
<!--                      <i class="fa fa-exchange-alt me-1"></i> Transform-->
<!--                    </button>-->
<!--                  </div>-->

<!--                  <div class="fs-sm fw-semibold text-uppercase mt-4 mb-2">Instructions</div>-->
<!--                  <ul class="fs-sm text-muted ps-3">-->
<!--                    <li>Add nodes to build your workflow</li>-->
<!--                    <li>Connect nodes by dragging from one node to another</li>-->
<!--                    <li>Configure nodes by selecting them</li>-->
<!--                    <li>Input nodes provide variables to your workflow</li>-->
<!--                    <li>Output nodes define the workflow result</li>-->
<!--                  </ul>-->
<!--                </div>-->

<!--                &lt;!&ndash; Properties Panel (for future implementation) &ndash;&gt;-->
<!--                <div v-if="activePanel === 'properties'" class="py-3">-->
<!--                  <div class="alert alert-info d-flex">-->
<!--                    <div class="flex-shrink-0">-->
<!--                      <i class="fa fa-info-circle fa-fw"></i>-->
<!--                    </div>-->
<!--                    <div class="ms-2">-->
<!--                      Select a node in the workflow to view and edit its properties-->
<!--                    </div>-->
<!--                  </div>-->

<!--                  <div class="form-check form-switch mb-3">-->
<!--                    <input-->
<!--                      class="form-check-input"-->
<!--                      type="checkbox"-->
<!--                      id="workflow-autosave"-->
<!--                      v-model="form.settings.autoSave"-->
<!--                    >-->
<!--                    <label class="form-check-label" for="workflow-autosave">-->
<!--                      Auto save workflow on execution-->
<!--                    </label>-->
<!--                  </div>-->

<!--                  <div class="form-check form-switch">-->
<!--                    <input-->
<!--                      class="form-check-input"-->
<!--                      type="checkbox"-->
<!--                      id="workflow-notify"-->
<!--                      v-model="form.settings.notifyOnCompletion"-->
<!--                    >-->
<!--                    <label class="form-check-label" for="workflow-notify">-->
<!--                      Notify on workflow completion-->
<!--                    </label>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->

<!--              &lt;!&ndash; Workflow Canvas &ndash;&gt;-->
<!--              <div style="margin-left: 260px; height: 100%">-->
<!--                <VueFlow-->
<!--                  v-model:nodes="nodes"-->
<!--                  v-model:edges="edges"-->
<!--                  :nodeTypes="nodeTypes"-->
<!--                  :default-zoom="1.5"-->
<!--                  :min-zoom="0.2"-->
<!--                  :max-zoom="4"-->
<!--                  @nodesChange="onNodesChange"-->
<!--                  @edgesChange="onEdgesChange"-->
<!--                  @connect="onConnect"-->
<!--                  class="workflow-canvas"-->
<!--                >-->
<!--                  <Background pattern-color="#aaa" gap="24" />-->
<!--                  <MiniMap height="120" width="240" />-->
<!--                  <Controls />-->

<!--                  <Panel position="top-right" class="p-2">-->
<!--                    <div class="btn-group">-->
<!--                      <button-->
<!--                        type="button"-->
<!--                        class="btn btn-sm btn-alt-secondary"-->
<!--                        title="Zoom In"-->
<!--                        @click="handleZoomIn"-->
<!--                      >-->
<!--                        <i class="fa fa-search-plus"></i>-->
<!--                      </button>-->
<!--                      <button-->
<!--                        type="button"-->
<!--                        class="btn btn-sm btn-alt-secondary"-->
<!--                        title="Zoom Out"-->
<!--                        @click="handleZoomOut"-->
<!--                      >-->
<!--                        <i class="fa fa-search-minus"></i>-->
<!--                      </button>-->
<!--                      <button-->
<!--                        type="button"-->
<!--                        class="btn btn-sm btn-alt-secondary"-->
<!--                        title="Fit View"-->
<!--                        @click="handleFitView"-->
<!--                      >-->
<!--                        <i class="fa fa-arrows-alt"></i>-->
<!--                      </button>-->
<!--                    </div>-->
<!--                  </Panel>-->
<!--                </VueFlow>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->

<!--    &lt;!&ndash; Action Buttons &ndash;&gt;-->
<!--    <div class="d-flex justify-content-between">-->
<!--      <Link-->
<!--        :href="route('workflows.index')"-->
<!--        class="btn btn-alt-secondary"-->
<!--      >-->
<!--        <i class="fa fa-arrow-left opacity-50 me-1"></i> Cancel-->
<!--      </Link>-->

<!--      <button-->
<!--        type="button"-->
<!--        class="btn btn-alt-primary"-->
<!--        @click="submit"-->
<!--        :disabled="!isValid || form.processing"-->
<!--      >-->
<!--        <i class="fa fa-save opacity-50 me-1"></i> Save Workflow-->
<!--      </button>-->
<!--    </div>-->
<!--  </div>-->
<!--</template>-->

<!--<style>-->
<!--.workflow-canvas .vue-flow__node {-->
<!--  border-radius: 5px;-->
<!--  padding: 10px;-->
<!--  font-size: 12px;-->
<!--  text-align: center;-->
<!--  border: 1px solid #ddd;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node.selected,-->
<!--.workflow-canvas .vue-flow__node.selected:hover {-->
<!--  box-shadow: 0 0 0 2px #5c80d1;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-promptNode {-->
<!--  background-color: rgba(92, 128, 209, 0.1);-->
<!--  border-color: #5c80d1;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-conditionNode {-->
<!--  background-color: rgba(219, 144, 56, 0.1);-->
<!--  border-color: #db9038;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-inputNode {-->
<!--  background-color: rgba(38, 169, 224, 0.1);-->
<!--  border-color: #26a9e0;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-outputNode {-->
<!--  background-color: rgba(66, 185, 131, 0.1);-->
<!--  border-color: #42b983;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-apiNode {-->
<!--  background-color: rgba(232, 74, 95, 0.1);-->
<!--  border-color: #e84a5f;-->
<!--}-->

<!--.workflow-canvas .vue-flow__node-transformNode {-->
<!--  background-color: rgba(108, 117, 125, 0.1);-->
<!--  border-color: #6c757d;-->
<!--}-->

<!--.workflow-canvas .vue-flow__edge-path {-->
<!--  stroke: #b1b1b7;-->
<!--  stroke-width: 2;-->
<!--  stroke-dasharray: none;-->
<!--}-->

<!--.workflow-canvas .vue-flow__edge.selected .vue-flow__edge-path {-->
<!--  stroke: #5c80d1;-->
<!--  stroke-width: 3;-->
<!--}-->

<!--.workflow-canvas .vue-flow__handle {-->
<!--  width: 8px;-->
<!--  height: 8px;-->
<!--}-->
<!--</style>-->


<script setup>
import { ref, reactive, onMounted, computed, nextTick } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

// Import Vue Flow
import { VueFlow } from '@vue-flow/core';
import { Background } from '@vue-flow/background';
import { Controls } from '@vue-flow/controls';
import { MiniMap } from '@vue-flow/minimap';
import { Panel } from '@vue-flow/core';
import { useVueFlow } from '@vue-flow/core';
import { v4 as uuidv4 } from 'uuid';

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

// Use Vue Flow instance but don't immediately destructure it
const vueFlow = useVueFlow();

// Create local reactive state that we can sync with Vue Flow
const flowNodes = ref([]);
const flowEdges = ref([]);

// Sidebar state
const activePanel = ref('nodes');
const initialNodesCreated = ref(false);

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

// Create initial nodes
const createInitialNodes = () => {
  if (initialNodesCreated.value) return;

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

  // Set the initial nodes
  updateNodes(initialNodes);
  initialNodesCreated.value = true;

  // Update form with initial nodes
  form.nodes = initialNodes.map(node => ({
    id: node.id,
    type: node.data.type,
    position: node.position,
    data: node.data
  }));
};

// Set default nodes for new workflow
onMounted(() => {
  // Use nextTick to ensure Vue Flow is mounted before adding nodes
  nextTick(() => {
    createInitialNodes();
  });
});

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
    form.post(route('workflows.store'), {
      onSuccess: () => {
        console.log("Workflow saved successfully");
      },
      onError: (errors) => {
        console.error("Error saving workflow:", errors);
      }
    });
  } catch (error) {
    console.error("Error in submit function:", error);
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

// Check if the workflow is valid
const isValid = computed(() => {
  return form.name.trim() !== '' && flowNodes.value.length > 0;
});
</script>

<template>
  <Head title="Create Workflow" />

  <BasePageHeading title="Create Workflow" subtitle="Build your AI automation flow">
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
                class="btn btn-sm btn-alt-primary"
                @click="submit"
                :disabled="!isValid || form.processing"
              >
                <i class="fa fa-fw fa-save opacity-50 me-1"></i> Save Workflow
              </button>
            </div>
          </div>
          <div class="block-content p-0">
            <div class="workflow-builder" style="height: 600px; position: relative;">
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
                    <li>Input nodes provide variables to your workflow</li>
                    <li>Output nodes define the workflow result</li>
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
                  class="workflow-canvas"
                >
                  <Background pattern-color="#aaa" gap="24" />
                  <MiniMap height="120" width="240" />
                  <Controls />

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
      <Link
        :href="route('workflows.index')"
        class="btn btn-alt-secondary"
      >
        <i class="fa fa-arrow-left opacity-50 me-1"></i> Cancel
      </Link>

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
</style>
