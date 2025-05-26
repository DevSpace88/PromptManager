<!--&lt;!&ndash; resources/js/Components/WorkflowEditor.vue &ndash;&gt;-->
<!--<script setup>-->
<!--import { ref, reactive, onMounted, computed, nextTick, onUnmounted, watch, markRaw } from 'vue';-->

<!--// Import Vue Flow-->
<!--import { VueFlow } from '@vue-flow/core';-->
<!--import { Background } from '@vue-flow/background';-->
<!--import { Controls } from '@vue-flow/controls';-->
<!--import { MiniMap } from '@vue-flow/minimap';-->
<!--import { Panel } from '@vue-flow/core';-->
<!--import { useVueFlow } from '@vue-flow/core';-->
<!--import { v4 as uuidv4 } from 'uuid';-->

<!--import '@vue-flow/core/dist/style.css';-->
<!--import '@vue-flow/core/dist/theme-default.css';-->

<!--// Import custom node types - pass prompts to nodes-->
<!--import PromptNode from '@/views/Pages/Workflows/Nodes/PromptNode.vue';-->
<!--import ConditionNode from '@/views/Pages/Workflows/Nodes/ConditionNode.vue';-->
<!--import InputNode from '@/views/Pages/Workflows/Nodes/InputNode.vue';-->
<!--import OutputNode from '@/views/Pages/Workflows/Nodes/OutputNode.vue';-->
<!--import ApiNode from '@/views/Pages/Workflows/Nodes/ApiNode.vue';-->
<!--import TransformNode from '@/views/Pages/Workflows/Nodes/TransformNode.vue';-->

<!--// Props-->
<!--const props = defineProps({-->
<!--  initialNodes: {-->
<!--    type: Array,-->
<!--    default: () => []-->
<!--  },-->
<!--  initialEdges: {-->
<!--    type: Array,-->
<!--    default: () => []-->
<!--  },-->
<!--  prompts: {-->
<!--    type: Array,-->
<!--    default: () => []-->
<!--  },-->
<!--  apiKeys: {-->
<!--    type: Array,-->
<!--    default: () => []-->
<!--  }-->
<!--});-->

<!--// Emit events up to parent-->
<!--const emit = defineEmits(['update:nodes', 'update:edges', 'update:setting']);-->

<!--// Node types registration - use markRaw to prevent reactivity warnings-->
<!--const nodeTypes = {-->
<!--  promptNode: markRaw(PromptNode),-->
<!--  conditionNode: markRaw(ConditionNode),-->
<!--  inputNode: markRaw(InputNode),-->
<!--  outputNode: markRaw(OutputNode),-->
<!--  apiNode: markRaw(ApiNode),-->
<!--  transformNode: markRaw(TransformNode)-->
<!--};-->

<!--// Use Vue Flow instance-->
<!--const vueFlow = useVueFlow();-->

<!--// Create local reactive state that we can sync with Vue Flow-->
<!--const flowNodes = ref([]);-->
<!--const flowEdges = ref([]);-->

<!--// Sidebar state-->
<!--const activePanel = ref('nodes');-->
<!--const initialNodesCreated = ref(false);-->
<!--const isFullscreen = ref(false);-->
<!--const workflowBuilder = ref(null);-->

<!--// Debug output for prompts-->
<!--console.log('WorkflowEditor received prompts:', props.prompts);-->

<!--// Method to safely update nodes-->
<!--const updateNodes = (newNodes) => {-->
<!--  flowNodes.value = newNodes;-->
<!--  vueFlow.setNodes(newNodes);-->
<!--  emit('update:nodes', newNodes.map(node => ({-->
<!--    id: node.id,-->
<!--    type: node.data?.type || '',-->
<!--    position: node.position || { x: 0, y: 0 },-->
<!--    data: node.data || {}-->
<!--  })));-->
<!--};-->

<!--// Method to safely update edges-->
<!--const updateEdges = (newEdges) => {-->
<!--  flowEdges.value = newEdges;-->
<!--  vueFlow.setEdges(newEdges);-->
<!--  emit('update:edges', newEdges);-->
<!--};-->

<!--// Create a new node-->
<!--const createNode = (type, data = {}) => {-->
<!--  const id = `node-${uuidv4()}`;-->

<!--  // Add prompts data for PromptNode-->
<!--  if (type === 'prompt') {-->
<!--    console.log('Adding prompts to prompt node:', props.prompts);-->
<!--    // data.prompts = [...props.prompts]; // Create a copy, not a reference-->
<!--    data.prompts = props.prompts;-->
<!--  }-->

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

<!--  console.log('Created node with data:', nodeData);-->

<!--  // Update nodes by creating a new array-->
<!--  updateNodes([...flowNodes.value, nodeData]);-->
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
<!--// const onConnect = (params) => {-->
<!--//   const newEdge = {-->
<!--//     id: `edge-${uuidv4()}`,-->
<!--//     source: params.source,-->
<!--//     target: params.target,-->
<!--//     animated: true-->
<!--//   };-->
<!--//-->
<!--//   updateEdges([...flowEdges.value, newEdge]);-->
<!--// };-->

<!--// In WorkflowEditor.vue - onConnect-Funktion anpassen-->
<!--const onConnect = (params) => {-->
<!--  console.log('Connection params:', params); // Debug-Info-->

<!--  const newEdge = {-->
<!--    id: `edge-${uuidv4()}`,-->
<!--    source: params.source,-->
<!--    sourceHandle: params.sourceHandle, // Wichtig für Condition Nodes!-->
<!--    target: params.target,-->
<!--    targetHandle: params.targetHandle,-->
<!--    animated: true,-->
<!--    // Zusätzliche Daten für Conditions-->
<!--    data: {-->
<!--      condition: params.sourceHandle?.includes('true') ? 'true' : 'false'-->
<!--    }-->
<!--  };-->

<!--  updateEdges([...flowEdges.value, newEdge]);-->
<!--};-->

<!--// Track changes to nodes and edges-->
<!--const onNodesChange = (changes) => {-->
<!--  const updatedNodes = vueFlow.applyNodeChanges(changes, flowNodes.value);-->
<!--  flowNodes.value = updatedNodes;-->

<!--  // Emit updated nodes-->
<!--  emit('update:nodes', updatedNodes.map(node => ({-->
<!--    id: node.id,-->
<!--    type: node.data?.type || '',-->
<!--    position: node.position || { x: 0, y: 0 },-->
<!--    data: node.data || {}-->
<!--  })));-->
<!--};-->

<!--const onEdgesChange = (changes) => {-->
<!--  const updatedEdges = vueFlow.applyEdgeChanges(changes, flowEdges.value);-->
<!--  flowEdges.value = updatedEdges;-->

<!--  // Emit updated edges-->
<!--  emit('update:edges', updatedEdges);-->
<!--};-->

<!--// Initialize with workflow nodes and edges or create default ones-->
<!--const initializeWorkflow = () => {-->
<!--  if (initialNodesCreated.value) return;-->

<!--  if (props.initialNodes && props.initialNodes.length > 0) {-->
<!--    // Format nodes to match VueFlow requirements-->
<!--    const nodes = props.initialNodes.map(node => {-->
<!--      // Add prompts to each prompt node-->
<!--      if (node.type === 'prompt') {-->
<!--        console.log('Adding prompts to existing prompt node:', props.prompts);-->

<!--        // Ensure node.data exists-->
<!--        if (!node.data) node.data = {};-->

<!--        // Add prompts directly to node.data-->
<!--        // node.data.prompts = [...props.prompts];-->
<!--        node.data.prompts = props.prompts;-->
<!--      }-->

<!--      return {-->
<!--        ...node,-->
<!--        // Make sure node types match component names expected by Vue Flow-->
<!--        type: `${node.type}Node`,-->
<!--      };-->
<!--    });-->

<!--    console.log('Prepared nodes with prompts:', nodes);-->
<!--    updateNodes(nodes);-->
<!--  } else {-->
<!--    // If no nodes, add default nodes-->
<!--    const inputNodeId = `node-${uuidv4()}`;-->
<!--    const outputNodeId = `node-${uuidv4()}`;-->

<!--    const initialNodes = [-->
<!--      {-->
<!--        id: inputNodeId,-->
<!--        type: 'inputNode',-->
<!--        position: { x: 250, y: 100 },-->
<!--        data: {-->
<!--          label: 'Input',-->
<!--          variable: 'input',-->
<!--          default_value: '',-->
<!--          type: 'input'-->
<!--        }-->
<!--      },-->
<!--      {-->
<!--        id: outputNodeId,-->
<!--        type: 'outputNode',-->
<!--        position: { x: 250, y: 300 },-->
<!--        data: {-->
<!--          label: 'Output',-->
<!--          variables: ['result'],-->
<!--          type: 'output'-->
<!--        }-->
<!--      }-->
<!--    ];-->

<!--    updateNodes(initialNodes);-->
<!--  }-->

<!--  // if (props.initialEdges && props.initialEdges.length > 0) {-->
<!--  //   updateEdges(props.initialEdges);-->
<!--  // }-->
<!--  // In der initializeWorkflow-Funktion-->
<!--  if (props.initialEdges && props.initialEdges.length > 0) {-->
<!--    // Erweitere die bestehenden Kanten um fehlende Eigenschaften-->
<!--    const enhancedEdges = props.initialEdges.map(edge => ({-->
<!--      ...edge,-->
<!--      animated: true,-->
<!--      // Stelle sicher, dass sourceHandle und targetHandle existieren-->
<!--      sourceHandle: edge.sourceHandle || null,-->
<!--      targetHandle: edge.targetHandle || null-->
<!--    }));-->
<!--    updateEdges(enhancedEdges);-->
<!--  }-->

<!--  initialNodesCreated.value = true;-->

<!--  // Fit view to see all nodes-->
<!--  nextTick(() => {-->
<!--    setTimeout(() => {-->
<!--      vueFlow.fitView();-->
<!--    }, 100);-->
<!--  });-->
<!--};-->

<!--// Watch for changes in props to reinitialize if needed-->
<!--watch(() => props.initialNodes, (newNodes) => {-->
<!--  if (newNodes && newNodes.length > 0 && !initialNodesCreated.value) {-->
<!--    initializeWorkflow();-->
<!--  }-->
<!--}, { immediate: false });-->

<!--watch(() => props.prompts, (newPrompts) => {-->
<!--  console.log('Prompts changed:', newPrompts);-->

<!--  // Update prompts in existing prompt nodes-->
<!--  if (flowNodes.value && flowNodes.value.length > 0) {-->
<!--    const updatedNodes = flowNodes.value.map(node => {-->
<!--      if (node.type === 'promptNode' && node.data) {-->
<!--        return {-->
<!--          ...node,-->
<!--          data: {-->
<!--            ...node.data,-->
<!--            // prompts: [...newPrompts]-->
<!--            prompts: newPrompts-->
<!--          }-->
<!--        };-->
<!--      }-->
<!--      return node;-->
<!--    });-->

<!--    updateNodes(updatedNodes);-->
<!--  }-->
<!--}, { deep: true });-->

<!--// Fullscreen toggle function-->
<!--const toggleFullscreen = () => {-->
<!--  isFullscreen.value = !isFullscreen.value;-->

<!--  if (isFullscreen.value) {-->
<!--    if (workflowBuilder.value?.requestFullscreen) {-->
<!--      workflowBuilder.value.requestFullscreen();-->
<!--    }-->
<!--  } else {-->
<!--    if (document.exitFullscreen) {-->
<!--      document.exitFullscreen();-->
<!--    }-->
<!--  }-->
<!--};-->

<!--// Handle fullscreen change from browser controls-->
<!--const handleFullscreenChange = () => {-->
<!--  if (!document.fullscreenElement) {-->
<!--    isFullscreen.value = false;-->
<!--  }-->
<!--};-->

<!--// Function to update parent form settings-->
<!--const updateParentSetting = (key, value) => {-->
<!--  emit('update:setting', { key, value });-->
<!--};-->

<!--// Handle keyboard events for node/edge deletion-->
<!--const handleKeyDown = (event) => {-->
<!--  // Only allow Delete key and only when not in an input or textarea-->
<!--  if (event.key === 'Delete' && !event.target.closest('input, textarea')) {-->
<!--    deleteSelected();-->
<!--  }-->
<!--};-->

<!--// Delete selected nodes and edges-->
<!--const deleteSelected = () => {-->
<!--  // Get selected nodes and edges-->
<!--  const selectedNodes = flowNodes.value.filter(node => node.selected);-->
<!--  const selectedEdges = flowEdges.value.filter(edge => edge.selected);-->

<!--  if (selectedNodes.length > 0) {-->
<!--    // Create a new array without the selected nodes-->
<!--    const newNodes = flowNodes.value.filter(node => !node.selected);-->
<!--    updateNodes(newNodes);-->
<!--  }-->

<!--  if (selectedEdges.length > 0) {-->
<!--    // Create a new array without the selected edges-->
<!--    const newEdges = flowEdges.value.filter(edge => !edge.selected);-->
<!--    updateEdges(newEdges);-->
<!--  }-->
<!--};-->

<!--// Zoom utilities-->
<!--const handleZoomIn = () => {-->
<!--  vueFlow.zoomIn();-->
<!--};-->

<!--const handleZoomOut = () => {-->
<!--  vueFlow.zoomOut();-->
<!--};-->

<!--const handleFitView = () => {-->
<!--  vueFlow.fitView();-->
<!--};-->

<!--// Set up on mount-->
<!--onMounted(() => {-->
<!--  document.addEventListener('fullscreenchange', handleFullscreenChange);-->

<!--  // Use nextTick to ensure Vue Flow is mounted before adding nodes-->
<!--  nextTick(() => {-->
<!--    console.log('WorkflowEditor mounted, initializing workflow');-->
<!--    initializeWorkflow();-->
<!--  });-->
<!--});-->

<!--// Clean up on unmount-->
<!--onUnmounted(() => {-->
<!--  document.removeEventListener('fullscreenchange', handleFullscreenChange);-->
<!--});-->
<!--</script>-->

<!--<template>-->
<!--  <div-->
<!--    class="workflow-builder"-->
<!--    :class="{'fullscreen': isFullscreen}"-->
<!--    ref="workflowBuilder"-->
<!--    style="height: 600px; position: relative;"-->
<!--  >-->
<!--    &lt;!&ndash; Sidebar &ndash;&gt;-->
<!--    <div class="workflow-sidebar bg-body-light border-end p-3" style="width: 260px; height: 100%; position: absolute; left: 0; top: 0; overflow-y: auto; z-index: 10;">-->
<!--      &lt;!&ndash; Sidebar Tabs &ndash;&gt;-->
<!--      <ul class="nav nav-tabs nav-tabs-block" role="tablist">-->
<!--        <li class="nav-item" role="presentation">-->
<!--          <button-->
<!--            class="nav-link"-->
<!--            :class="{'active': activePanel === 'nodes'}"-->
<!--            @click="activePanel = 'nodes'"-->
<!--          >-->
<!--            <i class="fa fa-cubes me-1"></i> Nodes-->
<!--          </button>-->
<!--        </li>-->
<!--        <li class="nav-item" role="presentation">-->
<!--          <button-->
<!--            class="nav-link"-->
<!--            :class="{'active': activePanel === 'properties'}"-->
<!--            @click="activePanel = 'properties'"-->
<!--          >-->
<!--            <i class="fa fa-cog me-1"></i> Properties-->
<!--          </button>-->
<!--        </li>-->
<!--      </ul>-->

<!--      &lt;!&ndash; Nodes Panel &ndash;&gt;-->
<!--      <div v-if="activePanel === 'nodes'" class="py-3">-->
<!--        <div class="fs-sm fw-semibold text-uppercase mb-2">Add Nodes</div>-->
<!--        <div class="d-grid gap-2">-->
<!--          &lt;!&ndash; Prompt Node &ndash;&gt;-->
<!--          <button-->
<!--            type="button"-->
<!--            class="btn btn-alt-primary"-->
<!--            @click="addPromptNode"-->
<!--          >-->
<!--            <i class="fa fa-file-alt me-1"></i> Prompt-->
<!--          </button>-->

<!--          &lt;!&ndash; Condition Node &ndash;&gt;-->
<!--          <button-->
<!--            type="button"-->
<!--            class="btn btn-alt-warning"-->
<!--            @click="addConditionNode"-->
<!--          >-->
<!--            <i class="fa fa-code-branch me-1"></i> Condition-->
<!--          </button>-->

<!--          &lt;!&ndash; Input Node &ndash;&gt;-->
<!--          <button-->
<!--            type="button"-->
<!--            class="btn btn-alt-info"-->
<!--            @click="addInputNode"-->
<!--          >-->
<!--            <i class="fa fa-sign-in-alt me-1"></i> Input-->
<!--          </button>-->

<!--          &lt;!&ndash; Output Node &ndash;&gt;-->
<!--          <button-->
<!--            type="button"-->
<!--            class="btn btn-alt-success"-->
<!--            @click="addOutputNode"-->
<!--          >-->
<!--            <i class="fa fa-sign-out-alt me-1"></i> Output-->
<!--          </button>-->

<!--          &lt;!&ndash; API Node &ndash;&gt;-->
<!--          <button-->
<!--            type="button"-->
<!--            class="btn btn-alt-danger"-->
<!--            @click="addApiNode"-->
<!--          >-->
<!--            <i class="fa fa-globe me-1"></i> API Call-->
<!--          </button>-->

<!--          &lt;!&ndash; Transform Node &ndash;&gt;-->
<!--          <button-->
<!--            type="button"-->
<!--            class="btn btn-alt-secondary"-->
<!--            @click="addTransformNode"-->
<!--          >-->
<!--            <i class="fa fa-exchange-alt me-1"></i> Transform-->
<!--          </button>-->
<!--        </div>-->

<!--        <div class="fs-sm fw-semibold text-uppercase mt-4 mb-2">Instructions</div>-->
<!--        <ul class="fs-sm text-muted ps-3">-->
<!--          <li>Add nodes to build your workflow</li>-->
<!--          <li>Connect nodes by dragging from one node to another</li>-->
<!--          <li>Configure nodes by selecting them</li>-->
<!--          <li>Drag nodes to move them around the canvas</li>-->
<!--          <li>Select an edge and press Delete to remove it</li>-->
<!--          <li>Select a node and press Delete to remove it</li>-->
<!--        </ul>-->

<!--        &lt;!&ndash; Debug Info &ndash;&gt;-->
<!--        <div class="alert alert-info mt-4">-->
<!--          <p class="mb-1"><strong>Debug Info:</strong></p>-->
<!--          <p class="mb-1">Prompts available: {{ prompts.length }}</p>-->
<!--          <p class="mb-0" v-if="prompts.length > 0">First prompt: {{ prompts[0].name }}</p>-->
<!--        </div>-->
<!--      </div>-->

<!--      &lt;!&ndash; Properties Panel &ndash;&gt;-->
<!--      <div v-if="activePanel === 'properties'" class="py-3">-->
<!--        <div class="alert alert-info d-flex">-->
<!--          <div class="flex-shrink-0">-->
<!--            <i class="fa fa-info-circle fa-fw"></i>-->
<!--          </div>-->
<!--          <div class="ms-2">-->
<!--            Select a node in the workflow to view and edit its properties-->
<!--          </div>-->
<!--        </div>-->

<!--        <slot name="properties">-->
<!--          &lt;!&ndash; Default properties panel content, can be overridden by parent &ndash;&gt;-->
<!--          <div class="form-check form-switch mb-3">-->
<!--            <input-->
<!--              class="form-check-input"-->
<!--              type="checkbox"-->
<!--              id="workflow-autosave"-->
<!--              :checked="false"-->
<!--              @change="e => updateParentSetting('autoSave', e.target.checked)"-->
<!--            >-->
<!--            <label class="form-check-label" for="workflow-autosave">-->
<!--              Auto save workflow on execution-->
<!--            </label>-->
<!--          </div>-->

<!--          <div class="form-check form-switch">-->
<!--            <input-->
<!--              class="form-check-input"-->
<!--              type="checkbox"-->
<!--              id="workflow-notify"-->
<!--              :checked="false"-->
<!--              @change="e => updateParentSetting('notifyOnCompletion', e.target.checked)"-->
<!--            >-->
<!--            <label class="form-check-label" for="workflow-notify">-->
<!--              Notify on workflow completion-->
<!--            </label>-->
<!--          </div>-->
<!--        </slot>-->
<!--      </div>-->
<!--    </div>-->

<!--    &lt;!&ndash; Workflow Canvas &ndash;&gt;-->
<!--    <div style="margin-left: 260px; height: 100%">-->
<!--      <VueFlow-->
<!--        v-model:nodes="flowNodes"-->
<!--        v-model:edges="flowEdges"-->
<!--        :nodeTypes="nodeTypes"-->
<!--        :default-zoom="1.5"-->
<!--        :min-zoom="0.2"-->
<!--        :max-zoom="4"-->
<!--        @nodesChange="onNodesChange"-->
<!--        @edgesChange="onEdgesChange"-->
<!--        @connect="onConnect"-->
<!--        @keydown="handleKeyDown"-->
<!--        :deleteKeyCode="['Delete']"-->
<!--        :nodeDragHandleClassName="'node-drag-handle'"-->
<!--        class="workflow-canvas"-->
<!--      >-->
<!--        <Background pattern-color="#aaa" gap="24" />-->
<!--        <MiniMap-->
<!--          height="120"-->
<!--          width="240"-->
<!--          nodeStrokeColor="#111"-->
<!--          nodeColor="#ddd"-->
<!--          nodeBorderRadius="3"-->
<!--        />-->
<!--        <Controls-->
<!--          :showZoom="true"-->
<!--          :showFitView="true"-->
<!--          :showInteractive="true"-->
<!--          :fitViewPadding="0.2"-->
<!--        />-->

<!--        <Panel position="top-right" class="p-2">-->
<!--          <div class="btn-group">-->
<!--            <button-->
<!--              type="button"-->
<!--              class="btn btn-sm btn-alt-secondary"-->
<!--              title="Zoom In"-->
<!--              @click="handleZoomIn"-->
<!--            >-->
<!--              <i class="fa fa-search-plus"></i>-->
<!--            </button>-->
<!--            <button-->
<!--              type="button"-->
<!--              class="btn btn-sm btn-alt-secondary"-->
<!--              title="Zoom Out"-->
<!--              @click="handleZoomOut"-->
<!--            >-->
<!--              <i class="fa fa-search-minus"></i>-->
<!--            </button>-->
<!--            <button-->
<!--              type="button"-->
<!--              class="btn btn-sm btn-alt-secondary"-->
<!--              title="Fit View"-->
<!--              @click="handleFitView"-->
<!--            >-->
<!--              <i class="fa fa-arrows-alt"></i>-->
<!--            </button>-->
<!--            <button-->
<!--              type="button"-->
<!--              class="btn btn-sm btn-alt-secondary"-->
<!--              title="Delete Selected"-->
<!--              @click="deleteSelected"-->
<!--            >-->
<!--              <i class="fa fa-trash"></i>-->
<!--            </button>-->
<!--            <button-->
<!--              type="button"-->
<!--              class="btn btn-sm btn-alt-secondary"-->
<!--              title="Toggle Fullscreen"-->
<!--              @click="toggleFullscreen"-->
<!--            >-->
<!--              <i :class="['fa', isFullscreen ? 'fa-compress' : 'fa-expand']"></i>-->
<!--            </button>-->
<!--          </div>-->
<!--        </Panel>-->
<!--      </VueFlow>-->
<!--    </div>-->
<!--  </div>-->
<!--</template>-->

<!--<style scoped>-->
<!--.workflow-canvas :deep(.vue-flow__node) {-->
<!--  border-radius: 5px;-->
<!--  padding: 10px;-->
<!--  font-size: 12px;-->
<!--  text-align: center;-->
<!--  border: 1px solid #ddd;-->
<!--}-->

<!--.workflow-canvas :deep(.vue-flow__node.selected),-->
<!--.workflow-canvas :deep(.vue-flow__node.selected:hover) {-->
<!--  box-shadow: 0 0 0 2px #5c80d1;-->
<!--}-->

<!--.workflow-canvas :deep(.vue-flow__node-promptNode) {-->
<!--  background-color: rgba(92, 128, 209, 0.1);-->
<!--  border-color: #5c80d1;-->
<!--}-->

<!--.workflow-canvas :deep(.vue-flow__node-conditionNode) {-->
<!--  background-color: rgba(219, 144, 56, 0.1);-->
<!--  border-color: #db9038;-->
<!--}-->

<!--.workflow-canvas :deep(.vue-flow__node-inputNode) {-->
<!--  background-color: rgba(38, 169, 224, 0.1);-->
<!--  border-color: #26a9e0;-->
<!--}-->

<!--.workflow-canvas :deep(.vue-flow__node-outputNode) {-->
<!--  background-color: rgba(66, 185, 131, 0.1);-->
<!--  border-color: #42b983;-->
<!--}-->

<!--.workflow-canvas :deep(.vue-flow__node-apiNode) {-->
<!--  background-color: rgba(232, 74, 95, 0.1);-->
<!--  border-color: #e84a5f;-->
<!--}-->

<!--.workflow-canvas :deep(.vue-flow__node-transformNode) {-->
<!--  background-color: rgba(108, 117, 125, 0.1);-->
<!--  border-color: #6c757d;-->
<!--}-->

<!--.workflow-canvas :deep(.vue-flow__edge-path) {-->
<!--  stroke: #b1b1b7;-->
<!--  stroke-width: 2;-->
<!--  stroke-dasharray: none;-->
<!--}-->

<!--.workflow-canvas :deep(.vue-flow__edge.selected .vue-flow__edge-path) {-->
<!--  stroke: #5c80d1;-->
<!--  stroke-width: 3;-->
<!--}-->

<!--.workflow-canvas :deep(.vue-flow__handle) {-->
<!--  width: 8px;-->
<!--  height: 8px;-->
<!--}-->

<!--.workflow-canvas :deep(.vue-flow__edge.animated .vue-flow__edge-path) {-->
<!--  stroke-dasharray: 5;-->
<!--  animation: dashdraw 0.5s linear infinite;-->
<!--}-->

<!--@keyframes dashdraw {-->
<!--  from {-->
<!--    stroke-dashoffset: 10;-->
<!--  }-->
<!--}-->

<!--/* Fullscreen mode styles */-->
<!--.workflow-builder.fullscreen {-->
<!--  position: fixed;-->
<!--  top: 0;-->
<!--  left: 0;-->
<!--  right: 0;-->
<!--  bottom: 0;-->
<!--  z-index: 9999;-->
<!--  height: 100vh !important;-->
<!--  width: 100vw;-->
<!--  background-color: white;-->
<!--}-->

<!--/* Control panel styles - ensure the icons are visible */-->
<!--.workflow-canvas :deep(.vue-flow__controls) {-->
<!--  box-shadow: 0 0 2px 1px rgba(0, 0, 0, 0.08);-->
<!--}-->

<!--.workflow-canvas :deep(.vue-flow__controls-button) {-->
<!--  background: #fafafa;-->
<!--  border: 1px solid #eee;-->
<!--  border-radius: 5px;-->
<!--  height: 24px;-->
<!--  width: 24px;-->
<!--  display: flex;-->
<!--  justify-content: center;-->
<!--  align-items: center;-->
<!--  margin-bottom: 8px;-->
<!--}-->

<!--.workflow-canvas :deep(.vue-flow__controls-button svg) {-->
<!--  fill: #333;-->
<!--  height: 14px;-->
<!--  width: 14px;-->
<!--}-->

<!--/* MiniMap styling */-->
<!--.workflow-canvas :deep(.vue-flow__minimap) {-->
<!--  background-color: #f8f9fa;-->
<!--  border: 1px solid #ddd;-->
<!--}-->

<!--/* Styling to prevent node dragging when interacting with inputs */-->
<!--.workflow-canvas :deep(.vue-flow__node input),-->
<!--.workflow-canvas :deep(.vue-flow__node textarea),-->
<!--.workflow-canvas :deep(.vue-flow__node select),-->
<!--.workflow-canvas :deep(.vue-flow__node button) {-->
<!--  pointer-events: auto;-->
<!--}-->

<!--/* This class allows moving the node */-->
<!--:deep(.node-drag-handle) {-->
<!--  cursor: move;-->
<!--}-->
<!--</style>-->

<!-- resources/js/Components/WorkflowEditor.vue -->
<script setup>
import {
  ref,
  reactive,
  onMounted,
  computed,
  nextTick,
  onUnmounted,
  watch,
  markRaw,
} from "vue";

// Import Vue Flow
import { VueFlow } from "@vue-flow/core";
import { Background } from "@vue-flow/background";
import { Controls } from "@vue-flow/controls";
import { MiniMap } from "@vue-flow/minimap";
import { Panel } from "@vue-flow/core";
import { useVueFlow } from "@vue-flow/core";
import { v4 as uuidv4 } from "uuid";

import "@vue-flow/core/dist/style.css";
import "@vue-flow/core/dist/theme-default.css";

// Import custom node types - pass prompts to nodes
import PromptNode from "@/views/Pages/Workflows/Nodes/PromptNode.vue";
import ConditionNode from "@/views/Pages/Workflows/Nodes/ConditionNode.vue";
import InputNode from "@/views/Pages/Workflows/Nodes/InputNode.vue";
import OutputNode from "@/views/Pages/Workflows/Nodes/OutputNode.vue";
import ApiNode from "@/views/Pages/Workflows/Nodes/ApiNode.vue";
import TransformNode from "@/views/Pages/Workflows/Nodes/TransformNode.vue";

// Props
const props = defineProps({
  initialNodes: {
    type: Array,
    default: () => [],
  },
  initialEdges: {
    type: Array,
    default: () => [],
  },
  prompts: {
    type: Array,
    default: () => [],
  },
  apiKeys: {
    type: Array,
    default: () => [],
  },
});

// Emit events up to parent
const emit = defineEmits(["update:nodes", "update:edges", "update:setting"]);

// Node types registration - use markRaw to prevent reactivity warnings
const nodeTypes = {
  promptNode: markRaw(PromptNode),
  conditionNode: markRaw(ConditionNode),
  inputNode: markRaw(InputNode),
  outputNode: markRaw(OutputNode),
  apiNode: markRaw(ApiNode),
  transformNode: markRaw(TransformNode),
};

// Use Vue Flow instance
const vueFlow = useVueFlow();

// Create local reactive state that we can sync with Vue Flow
const flowNodes = ref([]);
const flowEdges = ref([]);

// Sidebar state
const activePanel = ref("nodes");
const initialNodesCreated = ref(false);
const isFullscreen = ref(false);
const workflowBuilder = ref(null);

// Debug output for prompts
console.log("WorkflowEditor received prompts:", props.prompts);

// Method to safely update nodes
const updateNodes = (newNodes) => {
  flowNodes.value = newNodes;
  vueFlow.setNodes(newNodes);
  emit(
    "update:nodes",
    newNodes.map((node) => ({
      id: node.id,
      type: node.data?.type || "",
      position: node.position || { x: 0, y: 0 },
      data: node.data || {},
    })),
  );
};

// Method to safely update edges
const updateEdges = (newEdges) => {
  flowEdges.value = newEdges;
  vueFlow.setEdges(newEdges);
  emit("update:edges", newEdges);
};

// Create a new node
const createNode = (type, data = {}) => {
  const id = `node-${uuidv4()}`;

  // For Prompt-Nodes, add the prompts directly
  if (type === "prompt") {
    console.log("Adding prompts to prompt node:", props.prompts);
    // Don't clone the array, use direct reference to avoid proxy issues
    data.prompts = props.prompts;

    // Debug output
    console.log("Node data with prompts:", data);
  }

  const nodeData = {
    id,
    type: `${type}Node`,
    position: { x: 250, y: 100 },
    data: {
      ...data,
      label: data.label || `${type.charAt(0).toUpperCase() + type.slice(1)}`,
      type: type,
    },
  };

  // Additional debug for prompt nodes
  if (type === "prompt") {
    console.log("Final prompt node data:", nodeData.data);
  }

  // Update nodes by creating a new array
  updateNodes([...flowNodes.value, nodeData]);
  return nodeData;
};

// Add different types of nodes
const addPromptNode = () => {
  createNode("prompt", {
    label: "Prompt",
    content: "",
    prompt_id: null,
    provider: "openai",
    model: "gpt-4",
    temperature: 0.7,
    max_tokens: 2000,
    output_variable: "result",
  });
};

const addConditionNode = () => {
  createNode("condition", {
    label: "Condition",
    condition: "",
    true_path: null,
    false_path: null,
  });
};

const addInputNode = () => {
  createNode("input", {
    label: "Input",
    variable: "",
    default_value: "",
  });
};

const addOutputNode = () => {
  createNode("output", {
    label: "Output",
    variables: [],
  });
};

const addApiNode = () => {
  createNode("api", {
    label: "API Call",
    url: "",
    method: "GET",
    headers: {},
    body: {},
    output_variable: "api_result",
  });
};

const addTransformNode = () => {
  createNode("transform", {
    label: "Transform",
    input_variable: "",
    output_variable: "",
    transformation: "json_parse",
    regex: "",
    code: "",
  });
};

// Handle connection between nodes
const onConnect = (params) => {
  console.log("Connection params:", params); // Debug info

  const newEdge = {
    id: `edge-${uuidv4()}`,
    source: params.source,
    sourceHandle: params.sourceHandle, // Important for condition nodes
    target: params.target,
    targetHandle: params.targetHandle,
    animated: true,
    // Additional data for conditions
    data: {
      condition: params.sourceHandle?.includes("true") ? "true" : "false",
    },
  };

  updateEdges([...flowEdges.value, newEdge]);
};

// Track changes to nodes and edges
const onNodesChange = (changes) => {
  const updatedNodes = vueFlow.applyNodeChanges(changes, flowNodes.value);
  flowNodes.value = updatedNodes;

  // Emit updated nodes
  emit(
    "update:nodes",
    updatedNodes.map((node) => ({
      id: node.id,
      type: node.data?.type || "",
      position: node.position || { x: 0, y: 0 },
      data: node.data || {},
    })),
  );
};

const onEdgesChange = (changes) => {
  const updatedEdges = vueFlow.applyEdgeChanges(changes, flowEdges.value);
  flowEdges.value = updatedEdges;

  // Emit updated edges
  emit("update:edges", updatedEdges);
};

// Initialize with workflow nodes and edges or create default ones
const initializeWorkflow = () => {
  if (initialNodesCreated.value) return;

  if (props.initialNodes && props.initialNodes.length > 0) {
    // Format nodes to match VueFlow requirements
    const nodes = props.initialNodes.map((node) => {
      // Add prompts to each prompt node
      if (node.type === "prompt") {
        console.log("Adding prompts to existing prompt node:", props.prompts);

        // Ensure node.data exists
        if (!node.data) node.data = {};

        // Add prompts directly (no cloning)
        node.data.prompts = props.prompts;

        // Debug output
        console.log("Updated prompt node:", node);
      }

      return {
        ...node,
        // Make sure node types match component names expected by Vue Flow
        type: `${node.type}Node`,
      };
    });

    console.log("Prepared nodes with prompts:", nodes);
    updateNodes(nodes);
  } else {
    // If no nodes, add default nodes
    const inputNodeId = `node-${uuidv4()}`;
    const outputNodeId = `node-${uuidv4()}`;

    const initialNodes = [
      {
        id: inputNodeId,
        type: "inputNode",
        position: { x: 250, y: 100 },
        data: {
          label: "Input",
          variable: "input",
          default_value: "",
          type: "input",
        },
      },
      {
        id: outputNodeId,
        type: "outputNode",
        position: { x: 250, y: 300 },
        data: {
          label: "Output",
          variables: ["result"],
          type: "output",
        },
      },
    ];

    updateNodes(initialNodes);
  }

  if (props.initialEdges && props.initialEdges.length > 0) {
    // Enhance edges with properties needed for animation and proper connection
    const enhancedEdges = props.initialEdges.map((edge) => ({
      ...edge,
      animated: true,
      // Ensure sourceHandle and targetHandle exist
      sourceHandle: edge.sourceHandle || null,
      targetHandle: edge.targetHandle || null,
    }));
    updateEdges(enhancedEdges);
  }

  initialNodesCreated.value = true;

  // Fit view to see all nodes
  nextTick(() => {
    setTimeout(() => {
      vueFlow.fitView();
    }, 100);
  });
};

// Watch for changes in props to reinitialize if needed
watch(
  () => props.initialNodes,
  (newNodes) => {
    if (newNodes && newNodes.length > 0 && !initialNodesCreated.value) {
      initializeWorkflow();
    }
  },
  { immediate: false },
);

watch(
  () => props.prompts,
  (newPrompts) => {
    console.log("Prompts changed:", newPrompts);

    // Update prompts in existing prompt nodes
    if (flowNodes.value && flowNodes.value.length > 0) {
      const updatedNodes = flowNodes.value.map((node) => {
        if (node.type === "promptNode" && node.data) {
          return {
            ...node,
            data: {
              ...node.data,
              prompts: newPrompts, // Direct reference, no cloning
            },
          };
        }
        return node;
      });

      updateNodes(updatedNodes);
    }
  },
  { deep: true },
);

// Function to update parent form settings
const updateParentSetting = (key, value) => {
  emit("update:setting", { key, value });
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

// Handle keyboard events for node/edge deletion
const handleKeyDown = (event) => {
  // Only allow Delete key and only when not in an input or textarea
  if (event.key === "Delete" && !event.target.closest("input, textarea")) {
    deleteSelected();
  }
};

// Delete selected nodes and edges
const deleteSelected = () => {
  // Get selected nodes and edges
  const selectedNodes = flowNodes.value.filter((node) => node.selected);
  const selectedEdges = flowEdges.value.filter((edge) => edge.selected);

  if (selectedNodes.length > 0) {
    // Create a new array without the selected nodes
    const newNodes = flowNodes.value.filter((node) => !node.selected);
    updateNodes(newNodes);
  }

  if (selectedEdges.length > 0) {
    // Create a new array without the selected edges
    const newEdges = flowEdges.value.filter((edge) => !edge.selected);
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

// Set up on mount
onMounted(() => {
  document.addEventListener("fullscreenchange", handleFullscreenChange);

  // Use nextTick to ensure Vue Flow is mounted before adding nodes
  nextTick(() => {
    console.log("WorkflowEditor mounted, initializing workflow");
    initializeWorkflow();
  });
});

// Clean up on unmount
onUnmounted(() => {
  document.removeEventListener("fullscreenchange", handleFullscreenChange);
});
</script>

<template>
  <div
    class="workflow-builder"
    :class="{ fullscreen: isFullscreen }"
    ref="workflowBuilder"
    style="height: 600px; position: relative"
  >
    <!-- Sidebar -->
    <div
      class="workflow-sidebar bg-body-light border-end p-3"
      style="
        width: 260px;
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
        overflow-y: auto;
        z-index: 10;
      "
    >
      <!-- Sidebar Tabs -->
      <ul class="nav nav-tabs nav-tabs-block" role="tablist">
        <li class="nav-item" role="presentation">
          <button
            class="nav-link"
            :class="{ active: activePanel === 'nodes' }"
            @click="activePanel = 'nodes'"
          >
            <i class="fa fa-cubes me-1"></i> Nodes
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button
            class="nav-link"
            :class="{ active: activePanel === 'properties' }"
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
          <button type="button" class="btn btn-alt-info" @click="addInputNode">
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
          <button type="button" class="btn btn-alt-danger" @click="addApiNode">
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

        <div class="fs-sm fw-semibold text-uppercase mt-4 mb-2">
          Instructions
        </div>
        <ul class="fs-sm text-muted ps-3">
          <li>Add nodes to build your workflow</li>
          <li>Connect nodes by dragging from one node to another</li>
          <li>Configure nodes by selecting them</li>
          <li>Drag nodes to move them around the canvas</li>
          <li>Select an edge and press Delete to remove it</li>
          <li>Select a node and press Delete to remove it</li>
        </ul>

        <!-- Debug Info -->
        <!-- <div class="alert alert-info mt-4">
          <p class="mb-1"><strong>Debug Info:</strong></p>
          <p class="mb-1">Prompts available: {{ prompts.length }}</p>
          <p class="mb-0" v-if="prompts.length > 0">
            First prompt: {{ prompts[0].name }}
          </p>
        </div> -->
      </div>

      <!-- Properties Panel -->
      <div v-if="activePanel === 'properties'" class="py-3">
        <div class="alert alert-info d-flex">
          <div class="flex-shrink-0">
            <i class="fa fa-info-circle fa-fw"></i>
          </div>
          <div class="ms-2">
            Select a node in the workflow to view and edit its properties
          </div>
        </div>

        <slot name="properties">
          <!-- Default properties panel content, can be overridden by parent -->
          <div class="form-check form-switch mb-3">
            <input
              class="form-check-input"
              type="checkbox"
              id="workflow-autosave"
              :checked="false"
              @change="(e) => updateParentSetting('autoSave', e.target.checked)"
            />
            <label class="form-check-label" for="workflow-autosave">
              Auto save workflow on execution
            </label>
          </div>

          <div class="form-check form-switch">
            <input
              class="form-check-input"
              type="checkbox"
              id="workflow-notify"
              :checked="false"
              @change="
                (e) =>
                  updateParentSetting('notifyOnCompletion', e.target.checked)
              "
            />
            <label class="form-check-label" for="workflow-notify">
              Notify on workflow completion
            </label>
          </div>
        </slot>
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
        :deleteKeyCode="['Delete']"
        :nodeDragHandleClassName="'node-drag-handle'"
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
            <button
              type="button"
              class="btn btn-sm btn-alt-secondary"
              title="Toggle Fullscreen"
              @click="toggleFullscreen"
            >
              <i
                :class="['fa', isFullscreen ? 'fa-compress' : 'fa-expand']"
              ></i>
            </button>
          </div>
        </Panel>
      </VueFlow>
    </div>
  </div>
</template>

<style scoped>
.workflow-canvas :deep(.vue-flow__node) {
  border-radius: 5px;
  padding: 10px;
  font-size: 12px;
  text-align: center;
  border: 1px solid #ddd;
}

.workflow-canvas :deep(.vue-flow__node.selected),
.workflow-canvas :deep(.vue-flow__node.selected:hover) {
  box-shadow: 0 0 0 2px #5c80d1;
}

.workflow-canvas :deep(.vue-flow__node-promptNode) {
  background-color: rgba(92, 128, 209, 0.1);
  border-color: #5c80d1;
}

.workflow-canvas :deep(.vue-flow__node-conditionNode) {
  background-color: rgba(219, 144, 56, 0.1);
  border-color: #db9038;
}

.workflow-canvas :deep(.vue-flow__node-inputNode) {
  background-color: rgba(38, 169, 224, 0.1);
  border-color: #26a9e0;
}

.workflow-canvas :deep(.vue-flow__node-outputNode) {
  background-color: rgba(66, 185, 131, 0.1);
  border-color: #42b983;
}

.workflow-canvas :deep(.vue-flow__node-apiNode) {
  background-color: rgba(232, 74, 95, 0.1);
  border-color: #e84a5f;
}

.workflow-canvas :deep(.vue-flow__node-transformNode) {
  background-color: rgba(108, 117, 125, 0.1);
  border-color: #6c757d;
}

.workflow-canvas :deep(.vue-flow__edge-path) {
  stroke: #b1b1b7;
  stroke-width: 2;
  stroke-dasharray: none;
}

.workflow-canvas :deep(.vue-flow__edge.selected .vue-flow__edge-path) {
  stroke: #5c80d1;
  stroke-width: 3;
}

.workflow-canvas :deep(.vue-flow__handle) {
  width: 8px;
  height: 8px;
}

/* Animated edges */
.workflow-canvas :deep(.vue-flow__edge.animated .vue-flow__edge-path) {
  stroke-dasharray: 5;
  animation: dashdraw 0.5s linear infinite;
}

@keyframes dashdraw {
  from {
    stroke-dashoffset: 10;
  }
}

/* Condition node handles */
.workflow-canvas :deep(.vue-flow__handle.source-true) {
  background-color: #28a745 !important; /* Green for true */
  bottom: -8px;
  top: auto;
}

.workflow-canvas :deep(.vue-flow__handle.source-false) {
  background-color: #dc3545 !important; /* Red for false */
  right: -8px;
  left: auto;
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
.workflow-canvas :deep(.vue-flow__controls) {
  box-shadow: 0 0 2px 1px rgba(0, 0, 0, 0.08);
}

.workflow-canvas :deep(.vue-flow__controls-button) {
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

.workflow-canvas :deep(.vue-flow__controls-button svg) {
  fill: #333;
  height: 14px;
  width: 14px;
}

/* MiniMap styling */
.workflow-canvas :deep(.vue-flow__minimap) {
  background-color: #f8f9fa;
  border: 1px solid #ddd;
}

/* Styling to prevent node dragging when interacting with inputs */
.workflow-canvas :deep(.vue-flow__node input),
.workflow-canvas :deep(.vue-flow__node textarea),
.workflow-canvas :deep(.vue-flow__node select),
.workflow-canvas :deep(.vue-flow__node button) {
  pointer-events: auto;
}

/* This class allows moving the node */
:deep(.node-drag-handle) {
  cursor: move;
}
</style>
