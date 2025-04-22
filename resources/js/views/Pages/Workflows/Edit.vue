<!--&lt;!&ndash;&lt;!&ndash;<script setup>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { ref, reactive, onMounted, computed, nextTick, onUnmounted, markRaw } from 'vue';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { Head, Link, useForm, router } from '@inertiajs/vue3';&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Import Vue Flow&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { VueFlow } from '@vue-flow/core';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { Background } from '@vue-flow/background';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { Controls } from '@vue-flow/controls';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { MiniMap } from '@vue-flow/minimap';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { Panel } from '@vue-flow/core';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { useVueFlow } from '@vue-flow/core';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { v4 as uuidv4 } from 'uuid';&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Ensure CSS is imported&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import '@vue-flow/core/dist/style.css';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import '@vue-flow/core/dist/theme-default.css';&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Import custom node types - adjust paths as needed&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import PromptNode from '@/views/Pages/Workflows/Nodes/PromptNode.vue';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import ConditionNode from '@/views/Pages/Workflows/Nodes/ConditionNode.vue';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import InputNode from '@/views/Pages/Workflows/Nodes/InputNode.vue';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import OutputNode from '@/views/Pages/Workflows/Nodes/OutputNode.vue';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import ApiNode from '@/views/Pages/Workflows/Nodes/ApiNode.vue';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import TransformNode from '@/views/Pages/Workflows/Nodes/TransformNode.vue';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import BasePageHeading from '@/components/BasePageHeading.vue';&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Props&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const props = defineProps({&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  workflow: {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    type: Object,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    required: true&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  prompts: {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    type: Array,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    default: () => []&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  apiKeys: {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    type: Array,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    default: () => []&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Form for workflow details&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const form = useForm({&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  name: props.workflow.name,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  description: props.workflow.description || '',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  nodes: props.workflow.nodes || [],&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  edges: props.workflow.edges || [],&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  settings: props.workflow.settings || {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    autoSave: true,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    notifyOnCompletion: true&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  is_active: props.workflow.is_active&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Node types registration - use markRaw to prevent reactivity warnings&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const nodeTypes = {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  promptNode: markRaw(PromptNode),&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  conditionNode: markRaw(ConditionNode),&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  inputNode: markRaw(InputNode),&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  outputNode: markRaw(OutputNode),&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  apiNode: markRaw(ApiNode),&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  transformNode: markRaw(TransformNode)&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Use Vue Flow instance but don't immediately destructure it&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const vueFlow = useVueFlow();&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Create local reactive state that we can sync with Vue Flow&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const flowNodes = ref([]);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const flowEdges = ref([]);&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Sidebar state&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const activePanel = ref('nodes');&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const initialNodesCreated = ref(false);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const isFullscreen = ref(false);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const workflowBuilder = ref(null);&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Method to safely update nodes&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const updateNodes = (newNodes) => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  flowNodes.value = newNodes;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  vueFlow.setNodes(newNodes);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Method to safely update edges&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const updateEdges = (newEdges) => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  flowEdges.value = newEdges;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  vueFlow.setEdges(newEdges);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Create a new node&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const createNode = (type, data = {}) => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  const id = `node-${uuidv4()}`;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  const nodeData = {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    id,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    type: `${type}Node`,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    position: { x: 250, y: 100 },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    data: {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      ...data,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      label: data.label || `${type.charAt(0).toUpperCase() + type.slice(1)}`,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      type: type&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  };&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  // Update nodes by creating a new array&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  updateNodes([...flowNodes.value, nodeData]);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  return nodeData;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Add different types of nodes&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const addPromptNode = () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  createNode('prompt', {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    label: 'Prompt',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    content: '',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    prompt_id: null,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    provider: 'openai',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    model: 'gpt-4',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    temperature: 0.7,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    max_tokens: 2000,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    output_variable: 'result'&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  });&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;const addConditionNode = () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  createNode('condition', {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    label: 'Condition',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    condition: '',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    true_path: null,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    false_path: null&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  });&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;const addInputNode = () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  createNode('input', {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    label: 'Input',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    variable: '',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    default_value: ''&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  });&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;const addOutputNode = () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  createNode('output', {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    label: 'Output',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    variables: []&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  });&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;const addApiNode = () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  createNode('api', {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    label: 'API Call',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    url: '',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    method: 'GET',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    headers: {},&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    body: {},&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    output_variable: 'api_result'&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  });&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;const addTransformNode = () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  createNode('transform', {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    label: 'Transform',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    input_variable: '',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    output_variable: '',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    transformation: 'json_parse',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    regex: '',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    code: ''&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  });&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Handle connection between nodes&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const onConnect = (params) => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  const newEdge = {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    id: `edge-${uuidv4()}`,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    source: params.source,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    target: params.target,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    animated: true&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  };&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  updateEdges([...flowEdges.value, newEdge]);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Track changes to nodes and edges&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const onNodesChange = (changes) => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  const updatedNodes = vueFlow.applyNodeChanges(changes, flowNodes.value);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  flowNodes.value = updatedNodes;&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  // Update form data when nodes change&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  form.nodes = updatedNodes.map(node => ({&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    id: node.id,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    type: node.data?.type || '',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    position: node.position || { x: 0, y: 0 },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    data: node.data || {}&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }));&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;const onEdgesChange = (changes) => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  const updatedEdges = vueFlow.applyEdgeChanges(changes, flowEdges.value);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  flowEdges.value = updatedEdges;&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  // Update form data when edges change&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  form.edges = updatedEdges;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Initialize with workflow nodes and edges&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const initializeWorkflow = () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  if (initialNodesCreated.value) return;&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  if (props.workflow.nodes && props.workflow.nodes.length > 0) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    // Format nodes to match VueFlow requirements&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    const nodes = props.workflow.nodes.map(node => ({&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      ...node,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      // Make sure node types match component names expected by Vue Flow&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      type: `${node.type}Node`,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    }));&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;    updateNodes(nodes);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  } else {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    // If no nodes, add default nodes&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    const inputNodeId = `node-${uuidv4()}`;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    const outputNodeId = `node-${uuidv4()}`;&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;    const initialNodes = [&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        id: inputNodeId,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        type: 'inputNode',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        position: { x: 250, y: 100 },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        data: {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          label: 'Input',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          variable: 'input',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          default_value: '',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          type: 'input'&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        id: outputNodeId,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        type: 'outputNode',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        position: { x: 250, y: 300 },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        data: {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          label: 'Output',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          variables: ['result'],&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          type: 'output'&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    ];&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;    updateNodes(initialNodes);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  if (props.workflow.edges && props.workflow.edges.length > 0) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    updateEdges(props.workflow.edges);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  initialNodesCreated.value = true;&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  // Update form with initial nodes and edges&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  nextTick(() => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    if (flowNodes.value?.length > 0) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      form.nodes = flowNodes.value.map(node => ({&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        id: node.id,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        type: node.data.type,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        position: node.position,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        data: node.data&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      }));&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;      form.edges = flowEdges.value;&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;      // Fit view to see all nodes&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      setTimeout(() => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        vueFlow.fitView();&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      }, 100);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  });&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Fullscreen toggle function&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const toggleFullscreen = () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  isFullscreen.value = !isFullscreen.value;&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  if (isFullscreen.value) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    if (workflowBuilder.value?.requestFullscreen) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      workflowBuilder.value.requestFullscreen();&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  } else {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    if (document.exitFullscreen) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      document.exitFullscreen();&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Handle fullscreen change from browser controls&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const handleFullscreenChange = () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  if (!document.fullscreenElement) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    isFullscreen.value = false;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Add event listener for fullscreen change&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;onMounted(() => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  document.addEventListener('fullscreenchange', handleFullscreenChange);&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  // Use nextTick to ensure Vue Flow is mounted before adding nodes&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  nextTick(() => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    initializeWorkflow();&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  });&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Clean up event listener&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;onUnmounted(() => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  document.removeEventListener('fullscreenchange', handleFullscreenChange);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Handle keyboard events for node/edge deletion&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const handleKeyDown = (event) => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  if (event.key === 'Delete' || event.key === 'Backspace') {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    deleteSelected();&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Delete selected nodes and edges&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const deleteSelected = () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  // Get selected nodes and edges&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  const selectedNodes = flowNodes.value.filter(node => node.selected);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  const selectedEdges = flowEdges.value.filter(edge => edge.selected);&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  if (selectedNodes.length > 0) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    // Create a new array without the selected nodes&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    const newNodes = flowNodes.value.filter(node => !node.selected);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    updateNodes(newNodes);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  if (selectedEdges.length > 0) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    // Create a new array without the selected edges&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    const newEdges = flowEdges.value.filter(edge => !edge.selected);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    updateEdges(newEdges);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Zoom utilities&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const handleZoomIn = () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  vueFlow.zoomIn();&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;const handleZoomOut = () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  vueFlow.zoomOut();&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;const handleFitView = () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  vueFlow.fitView();&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Cancel editing and return to workflow list&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const cancelEdit = () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  router.get(route('workflows.index'));&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Submit the workflow&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const submit = () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  try {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    // Update form data with latest nodes and edges&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    form.nodes = flowNodes.value.map(node => ({&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      id: node.id,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      type: node.data?.type || '',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      position: node.position || { x: 0, y: 0 },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      data: node.data || {}&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    }));&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;    form.edges = flowEdges.value;&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;    // Submit the form&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    form.put(route('workflows.update', props.workflow.id), {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      onSuccess: () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        console.log("Workflow updated successfully");&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      onError: (errors) => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        console.error("Error updating workflow:", errors);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    });&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  } catch (error) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    console.error("Error in submit function:", error);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Check if the workflow is valid&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const isValid = computed(() => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  return form.name.trim() !== '' && flowNodes.value.length > 0;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;</script>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;<template>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  <Head title="Edit Workflow" />&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  <BasePageHeading :title="`Edit: ${workflow.name}`" subtitle="Modify your workflow">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    <template #extra>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      <nav class="breadcrumb push">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <Link :href="route('dashboard')" class="breadcrumb-item">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <i class="fa fa-home"></i>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </Link>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <Link :href="route('workflows.index')" class="breadcrumb-item">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          Workflows&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </Link>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <Link :href="route('workflows.show', workflow.id)" class="breadcrumb-item">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          {{ workflow.name }}&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </Link>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <span class="breadcrumb-item active">Edit</span>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      </nav>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    </template>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  </BasePageHeading>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  <div class="content">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    <div class="row">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      &lt;!&ndash; Workflow Details Block &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      <div class="col-md-12 mb-4">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div class="block block-rounded">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="block-header block-header-default">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <h3 class="block-title">Workflow Details</h3>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="block-content">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <div class="row">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <div class="col-md-6">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                <div class="mb-4">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <label class="form-label" for="workflow-name">Workflow Name</label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <input&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    type="text"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    id="workflow-name"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    class="form-control"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    v-model="form.name"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    placeholder="Enter workflow name"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    required&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <div v-if="form.errors.name" class="invalid-feedback d-block">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    {{ form.errors.name }}&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <div class="col-md-6">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                <div class="mb-4">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <label class="form-label" for="workflow-description">Description (optional)</label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <textarea&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    id="workflow-description"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    class="form-control"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    v-model="form.description"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    placeholder="Describe what this workflow does"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    rows="1"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  ></textarea>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <div v-if="form.errors.description" class="invalid-feedback d-block">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    {{ form.errors.description }}&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <div class="row">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <div class="col-md-6">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                <div class="mb-4">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <div class="form-check form-switch">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <input class="form-check-input" type="checkbox" id="workflow-active" v-model="form.is_active">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <label class="form-check-label" for="workflow-active">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      Workflow is active&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    </label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <div class="fs-sm text-muted mt-1">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    When active, this workflow can be executed. Inactive workflows cannot be run.&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;      &lt;!&ndash; Workflow Builder &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      <div class="col-md-12">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <div class="block block-rounded">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="block-header block-header-default">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <h3 class="block-title">Workflow Builder</h3>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <div class="block-options">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <button&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                type="button"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                class="btn btn-sm btn-alt-secondary me-1"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                @click="toggleFullscreen"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                title="Toggle Fullscreen"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                <i :class="['fa', isFullscreen ? 'fa-compress' : 'fa-expand']"></i>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              </button>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <button&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                type="button"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                class="btn btn-sm btn-alt-primary"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                @click="submit"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                :disabled="!isValid || form.processing"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                <i class="fa fa-fw fa-save opacity-50 me-1"></i> Save Workflow&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              </button>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="block-content p-0">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <div&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              class="workflow-builder"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              :class="{'fullscreen': isFullscreen}"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              ref="workflowBuilder"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              style="height: 600px; position: relative;"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              &lt;!&ndash; Sidebar &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <div class="workflow-sidebar bg-body-light border-end p-3" style="width: 260px; height: 100%; position: absolute; left: 0; top: 0; overflow-y: auto; z-index: 10;">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                &lt;!&ndash; Sidebar Tabs &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                <ul class="nav nav-tabs nav-tabs-block" role="tablist">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <li class="nav-item" role="presentation">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <button&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      class="nav-link"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      :class="{'active': activePanel === 'nodes'}"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      @click="activePanel = 'nodes'"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      <i class="fa fa-cubes me-1"></i> Nodes&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    </button>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  </li>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <li class="nav-item" role="presentation">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <button&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      class="nav-link"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      :class="{'active': activePanel === 'properties'}"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      @click="activePanel = 'properties'"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      <i class="fa fa-cog me-1"></i> Properties&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    </button>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  </li>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                </ul>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;                &lt;!&ndash; Nodes Panel &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                <div v-if="activePanel === 'nodes'" class="py-3">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <div class="fs-sm fw-semibold text-uppercase mb-2">Add Nodes</div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <div class="d-grid gap-2">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    &lt;!&ndash; Prompt Node &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <button&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      type="button"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      class="btn btn-alt-primary"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      @click="addPromptNode"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      <i class="fa fa-file-alt me-1"></i> Prompt&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    </button>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;                    &lt;!&ndash; Condition Node &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <button&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      type="button"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      class="btn btn-alt-warning"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      @click="addConditionNode"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      <i class="fa fa-code-branch me-1"></i> Condition&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    </button>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;                    &lt;!&ndash; Input Node &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <button&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      type="button"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      class="btn btn-alt-info"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      @click="addInputNode"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      <i class="fa fa-sign-in-alt me-1"></i> Input&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    </button>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;                    &lt;!&ndash; Output Node &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <button&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      type="button"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      class="btn btn-alt-success"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      @click="addOutputNode"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      <i class="fa fa-sign-out-alt me-1"></i> Output&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    </button>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;                    &lt;!&ndash; API Node &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <button&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      type="button"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      class="btn btn-alt-danger"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      @click="addApiNode"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      <i class="fa fa-globe me-1"></i> API Call&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    </button>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;                    &lt;!&ndash; Transform Node &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <button&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      type="button"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      class="btn btn-alt-secondary"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      @click="addTransformNode"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      <i class="fa fa-exchange-alt me-1"></i> Transform&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    </button>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;                  <div class="fs-sm fw-semibold text-uppercase mt-4 mb-2">Instructions</div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <ul class="fs-sm text-muted ps-3">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <li>Add nodes to build your workflow</li>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <li>Connect nodes by dragging from one node to another</li>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <li>Configure nodes by selecting them</li>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <li>Drag nodes to move them around the canvas</li>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <li>Select an edge and press Delete to remove it</li>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <li>Select a node and press Delete to remove it</li>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  </ul>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;                &lt;!&ndash; Properties Panel (for future implementation) &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                <div v-if="activePanel === 'properties'" class="py-3">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <div class="alert alert-info d-flex">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <div class="flex-shrink-0">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      <i class="fa fa-info-circle fa-fw"></i>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <div class="ms-2">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      Select a node in the workflow to view and edit its properties&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;                  <div class="form-check form-switch mb-3">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <input&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      class="form-check-input"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      type="checkbox"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      id="workflow-autosave"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      v-model="form.settings.autoSave"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <label class="form-check-label" for="workflow-autosave">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      Auto save workflow on execution&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    </label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;                  <div class="form-check form-switch">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <input&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      class="form-check-input"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      type="checkbox"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      id="workflow-notify"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      v-model="form.settings.notifyOnCompletion"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <label class="form-check-label" for="workflow-notify">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      Notify on workflow completion&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    </label>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;              &lt;!&ndash; Workflow Canvas &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              <div style="margin-left: 260px; height: 100%">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;&lt;!&ndash;                <VueFlow&ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;&lt;!&ndash;                  v-model:nodes="flowNodes"&ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;&lt;!&ndash;                  v-model:edges="flowEdges"&ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;&lt;!&ndash;                  :nodeTypes="nodeTypes"&ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;&lt;!&ndash;                  :default-zoom="1.5"&ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;&lt;!&ndash;                  :min-zoom="0.2"&ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;&lt;!&ndash;                  :max-zoom="4"&ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;&lt;!&ndash;                  @nodesChange="onNodesChange"&ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;&lt;!&ndash;                  @edgesChange="onEdgesChange"&ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;&lt;!&ndash;                  @connect="onConnect"&ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;&lt;!&ndash;                  @keydown="handleKeyDown"&ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;&lt;!&ndash;                  :deleteKeyCode="['Backspace', 'Delete']"&ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;&lt;!&ndash;                  class="workflow-canvas"&ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;&lt;!&ndash;                >&ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                <VueFlow&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  v-model:nodes="flowNodes"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  v-model:edges="flowEdges"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  :nodeTypes="nodeTypes"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  :default-zoom="1.5"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  :min-zoom="0.2"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  :max-zoom="4"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  @nodesChange="onNodesChange"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  @edgesChange="onEdgesChange"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  @connect="onConnect"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  @keydown="handleKeyDown"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  :deleteKeyCode="['Backspace', 'Delete']"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  :nodeDraggable="false"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  class="workflow-canvas"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <Background pattern-color="#aaa" gap="24" />&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <MiniMap&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    height="120"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    width="240"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    nodeStrokeColor="#111"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    nodeColor="#ddd"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    nodeBorderRadius="3"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  />&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <Controls&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    :showZoom="true"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    :showFitView="true"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    :showInteractive="true"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    :fitViewPadding="0.2"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  />&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;                  <Panel position="top-right" class="p-2">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <div class="btn-group">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      <button&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        type="button"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        class="btn btn-sm btn-alt-secondary"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        title="Zoom In"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        @click="handleZoomIn"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        <i class="fa fa-search-plus"></i>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      </button>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      <button&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        type="button"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        class="btn btn-sm btn-alt-secondary"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        title="Zoom Out"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        @click="handleZoomOut"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        <i class="fa fa-search-minus"></i>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      </button>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      <button&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        type="button"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        class="btn btn-sm btn-alt-secondary"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        title="Fit View"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        @click="handleFitView"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        <i class="fa fa-arrows-alt"></i>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      </button>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      <button&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        type="button"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        class="btn btn-sm btn-alt-secondary"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        title="Delete Selected"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        @click="deleteSelected"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        <i class="fa fa-trash"></i>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                      </button>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  </Panel>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                </VueFlow>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    </div>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;    &lt;!&ndash; Action Buttons &ndash;&gt;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    <div class="d-flex justify-content-between mb-4">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      <button&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        type="button"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        class="btn btn-alt-secondary"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        @click="cancelEdit"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <i class="fa fa-arrow-left opacity-50 me-1"></i> Cancel&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      </button>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;      <button&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        type="button"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        class="btn btn-alt-primary"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        @click="submit"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        :disabled="!isValid || form.processing"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <i class="fa fa-save opacity-50 me-1"></i> Save Workflow&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      </button>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;</template>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;<style>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;.workflow-canvas .vue-flow__node {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border-radius: 5px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  padding: 10px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  font-size: 12px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  text-align: center;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border: 1px solid #ddd;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.workflow-canvas .vue-flow__node.selected,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;.workflow-canvas .vue-flow__node.selected:hover {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  box-shadow: 0 0 0 2px #5c80d1;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.workflow-canvas .vue-flow__node-promptNode {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  background-color: rgba(92, 128, 209, 0.1);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border-color: #5c80d1;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.workflow-canvas .vue-flow__node-conditionNode {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  background-color: rgba(219, 144, 56, 0.1);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border-color: #db9038;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.workflow-canvas .vue-flow__node-inputNode {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  background-color: rgba(38, 169, 224, 0.1);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border-color: #26a9e0;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.workflow-canvas .vue-flow__node-outputNode {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  background-color: rgba(66, 185, 131, 0.1);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border-color: #42b983;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.workflow-canvas .vue-flow__node-apiNode {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  background-color: rgba(232, 74, 95, 0.1);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border-color: #e84a5f;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.workflow-canvas .vue-flow__node-transformNode {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  background-color: rgba(108, 117, 125, 0.1);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border-color: #6c757d;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.workflow-canvas .vue-flow__edge-path {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  stroke: #b1b1b7;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  stroke-width: 2;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  stroke-dasharray: none;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.workflow-canvas .vue-flow__edge.selected .vue-flow__edge-path {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  stroke: #5c80d1;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  stroke-width: 3;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.workflow-canvas .vue-flow__handle {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  width: 8px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  height: 8px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;/* Fullscreen mode styles */&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;.workflow-builder.fullscreen {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  position: fixed;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  top: 0;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  left: 0;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  right: 0;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  bottom: 0;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  z-index: 9999;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  height: 100vh !important;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  width: 100vw;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  background-color: white;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;/* Control panel styles - ensure the icons are visible */&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;.vue-flow__controls {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  box-shadow: 0 0 2px 1px rgba(0, 0, 0, 0.08);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.vue-flow__controls-button {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  background: #fafafa;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border: 1px solid #eee;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border-radius: 5px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  height: 24px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  width: 24px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  display: flex;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  justify-content: center;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  align-items: center;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  margin-bottom: 8px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.vue-flow__controls-button svg {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  fill: #333;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  height: 14px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  width: 14px;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;/* MiniMap styling */&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;.vue-flow__minimap {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  background-color: #f8f9fa;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  border: 1px solid #ddd;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;/* Selected elements styling */&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;.vue-flow__node.selected {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  box-shadow: 0 0 0 2px #5c80d1 !important;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;.vue-flow__edge.selected .vue-flow__edge-path {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  stroke: #5c80d1 !important;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  stroke-width: 3px !important;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;}&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;</style>&ndash;&gt;&ndash;&gt;-->


<!--&lt;!&ndash;<script setup>&ndash;&gt;-->
<!--&lt;!&ndash;import { ref, reactive, onMounted, computed, nextTick, onUnmounted, markRaw } from 'vue';&ndash;&gt;-->
<!--&lt;!&ndash;import { Head, Link, useForm, router } from '@inertiajs/vue3';&ndash;&gt;-->

<!--&lt;!&ndash;// Import Vue Flow&ndash;&gt;-->
<!--&lt;!&ndash;import { VueFlow } from '@vue-flow/core';&ndash;&gt;-->
<!--&lt;!&ndash;import { Background } from '@vue-flow/background';&ndash;&gt;-->
<!--&lt;!&ndash;import { Controls } from '@vue-flow/controls';&ndash;&gt;-->
<!--&lt;!&ndash;import { MiniMap } from '@vue-flow/minimap';&ndash;&gt;-->
<!--&lt;!&ndash;import { Panel } from '@vue-flow/core';&ndash;&gt;-->
<!--&lt;!&ndash;import { useVueFlow } from '@vue-flow/core';&ndash;&gt;-->
<!--&lt;!&ndash;import { v4 as uuidv4 } from 'uuid';&ndash;&gt;-->

<!--&lt;!&ndash;// Ensure CSS is imported&ndash;&gt;-->
<!--&lt;!&ndash;import '@vue-flow/core/dist/style.css';&ndash;&gt;-->
<!--&lt;!&ndash;import '@vue-flow/core/dist/theme-default.css';&ndash;&gt;-->

<!--&lt;!&ndash;// Import custom node types - adjust paths as needed&ndash;&gt;-->
<!--&lt;!&ndash;import PromptNode from '@/views/Pages/Workflows/Nodes/PromptNode.vue';&ndash;&gt;-->
<!--&lt;!&ndash;import ConditionNode from '@/views/Pages/Workflows/Nodes/ConditionNode.vue';&ndash;&gt;-->
<!--&lt;!&ndash;import InputNode from '@/views/Pages/Workflows/Nodes/InputNode.vue';&ndash;&gt;-->
<!--&lt;!&ndash;import OutputNode from '@/views/Pages/Workflows/Nodes/OutputNode.vue';&ndash;&gt;-->
<!--&lt;!&ndash;import ApiNode from '@/views/Pages/Workflows/Nodes/ApiNode.vue';&ndash;&gt;-->
<!--&lt;!&ndash;import TransformNode from '@/views/Pages/Workflows/Nodes/TransformNode.vue';&ndash;&gt;-->
<!--&lt;!&ndash;import BasePageHeading from '@/components/BasePageHeading.vue';&ndash;&gt;-->

<!--&lt;!&ndash;// Props&ndash;&gt;-->
<!--&lt;!&ndash;const props = defineProps({&ndash;&gt;-->
<!--&lt;!&ndash;  workflow: {&ndash;&gt;-->
<!--&lt;!&ndash;    type: Object,&ndash;&gt;-->
<!--&lt;!&ndash;    required: true&ndash;&gt;-->
<!--&lt;!&ndash;  },&ndash;&gt;-->
<!--&lt;!&ndash;  prompts: {&ndash;&gt;-->
<!--&lt;!&ndash;    type: Array,&ndash;&gt;-->
<!--&lt;!&ndash;    default: () => []&ndash;&gt;-->
<!--&lt;!&ndash;  },&ndash;&gt;-->
<!--&lt;!&ndash;  apiKeys: {&ndash;&gt;-->
<!--&lt;!&ndash;    type: Array,&ndash;&gt;-->
<!--&lt;!&ndash;    default: () => []&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Form for workflow details&ndash;&gt;-->
<!--&lt;!&ndash;const form = useForm({&ndash;&gt;-->
<!--&lt;!&ndash;  name: props.workflow.name,&ndash;&gt;-->
<!--&lt;!&ndash;  description: props.workflow.description || '',&ndash;&gt;-->
<!--&lt;!&ndash;  nodes: props.workflow.nodes || [],&ndash;&gt;-->
<!--&lt;!&ndash;  edges: props.workflow.edges || [],&ndash;&gt;-->
<!--&lt;!&ndash;  settings: props.workflow.settings || {&ndash;&gt;-->
<!--&lt;!&ndash;    autoSave: true,&ndash;&gt;-->
<!--&lt;!&ndash;    notifyOnCompletion: true&ndash;&gt;-->
<!--&lt;!&ndash;  },&ndash;&gt;-->
<!--&lt;!&ndash;  is_active: props.workflow.is_active&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Node types registration - use markRaw to prevent reactivity warnings&ndash;&gt;-->
<!--&lt;!&ndash;const nodeTypes = {&ndash;&gt;-->
<!--&lt;!&ndash;  promptNode: markRaw(PromptNode),&ndash;&gt;-->
<!--&lt;!&ndash;  conditionNode: markRaw(ConditionNode),&ndash;&gt;-->
<!--&lt;!&ndash;  inputNode: markRaw(InputNode),&ndash;&gt;-->
<!--&lt;!&ndash;  outputNode: markRaw(OutputNode),&ndash;&gt;-->
<!--&lt;!&ndash;  apiNode: markRaw(ApiNode),&ndash;&gt;-->
<!--&lt;!&ndash;  transformNode: markRaw(TransformNode)&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Use Vue Flow instance but don't immediately destructure it&ndash;&gt;-->
<!--&lt;!&ndash;const vueFlow = useVueFlow();&ndash;&gt;-->

<!--&lt;!&ndash;// Create local reactive state that we can sync with Vue Flow&ndash;&gt;-->
<!--&lt;!&ndash;const flowNodes = ref([]);&ndash;&gt;-->
<!--&lt;!&ndash;const flowEdges = ref([]);&ndash;&gt;-->

<!--&lt;!&ndash;// Sidebar state&ndash;&gt;-->
<!--&lt;!&ndash;const activePanel = ref('nodes');&ndash;&gt;-->
<!--&lt;!&ndash;const initialNodesCreated = ref(false);&ndash;&gt;-->
<!--&lt;!&ndash;const isFullscreen = ref(false);&ndash;&gt;-->
<!--&lt;!&ndash;const workflowBuilder = ref(null);&ndash;&gt;-->

<!--&lt;!&ndash;// Method to safely update nodes&ndash;&gt;-->
<!--&lt;!&ndash;const updateNodes = (newNodes) => {&ndash;&gt;-->
<!--&lt;!&ndash;  flowNodes.value = newNodes;&ndash;&gt;-->
<!--&lt;!&ndash;  vueFlow.setNodes(newNodes);&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Method to safely update edges&ndash;&gt;-->
<!--&lt;!&ndash;const updateEdges = (newEdges) => {&ndash;&gt;-->
<!--&lt;!&ndash;  flowEdges.value = newEdges;&ndash;&gt;-->
<!--&lt;!&ndash;  vueFlow.setEdges(newEdges);&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Create a new node&ndash;&gt;-->
<!--&lt;!&ndash;const createNode = (type, data = {}) => {&ndash;&gt;-->
<!--&lt;!&ndash;  const id = `node-${uuidv4()}`;&ndash;&gt;-->

<!--&lt;!&ndash;  // Add prompts data for PromptNode&ndash;&gt;-->
<!--&lt;!&ndash;  if (type === 'prompt') {&ndash;&gt;-->
<!--&lt;!&ndash;    data.prompts = props.prompts;&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->

<!--&lt;!&ndash;  const nodeData = {&ndash;&gt;-->
<!--&lt;!&ndash;    id,&ndash;&gt;-->
<!--&lt;!&ndash;    type: `${type}Node`,&ndash;&gt;-->
<!--&lt;!&ndash;    position: { x: 250, y: 100 },&ndash;&gt;-->
<!--&lt;!&ndash;    data: {&ndash;&gt;-->
<!--&lt;!&ndash;      ...data,&ndash;&gt;-->
<!--&lt;!&ndash;      label: data.label || `${type.charAt(0).toUpperCase() + type.slice(1)}`,&ndash;&gt;-->
<!--&lt;!&ndash;      type: type&ndash;&gt;-->
<!--&lt;!&ndash;    }&ndash;&gt;-->
<!--&lt;!&ndash;  };&ndash;&gt;-->

<!--&lt;!&ndash;  // Update nodes by creating a new array&ndash;&gt;-->
<!--&lt;!&ndash;  updateNodes([...flowNodes.value, nodeData]);&ndash;&gt;-->
<!--&lt;!&ndash;  return nodeData;&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Add different types of nodes&ndash;&gt;-->
<!--&lt;!&ndash;const addPromptNode = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  createNode('prompt', {&ndash;&gt;-->
<!--&lt;!&ndash;    label: 'Prompt',&ndash;&gt;-->
<!--&lt;!&ndash;    content: '',&ndash;&gt;-->
<!--&lt;!&ndash;    prompt_id: null,&ndash;&gt;-->
<!--&lt;!&ndash;    provider: 'openai',&ndash;&gt;-->
<!--&lt;!&ndash;    model: 'gpt-4',&ndash;&gt;-->
<!--&lt;!&ndash;    temperature: 0.7,&ndash;&gt;-->
<!--&lt;!&ndash;    max_tokens: 2000,&ndash;&gt;-->
<!--&lt;!&ndash;    output_variable: 'result'&ndash;&gt;-->
<!--&lt;!&ndash;  });&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;const addConditionNode = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  createNode('condition', {&ndash;&gt;-->
<!--&lt;!&ndash;    label: 'Condition',&ndash;&gt;-->
<!--&lt;!&ndash;    condition: '',&ndash;&gt;-->
<!--&lt;!&ndash;    true_path: null,&ndash;&gt;-->
<!--&lt;!&ndash;    false_path: null&ndash;&gt;-->
<!--&lt;!&ndash;  });&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;const addInputNode = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  createNode('input', {&ndash;&gt;-->
<!--&lt;!&ndash;    label: 'Input',&ndash;&gt;-->
<!--&lt;!&ndash;    variable: '',&ndash;&gt;-->
<!--&lt;!&ndash;    default_value: ''&ndash;&gt;-->
<!--&lt;!&ndash;  });&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;const addOutputNode = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  createNode('output', {&ndash;&gt;-->
<!--&lt;!&ndash;    label: 'Output',&ndash;&gt;-->
<!--&lt;!&ndash;    variables: []&ndash;&gt;-->
<!--&lt;!&ndash;  });&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;const addApiNode = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  createNode('api', {&ndash;&gt;-->
<!--&lt;!&ndash;    label: 'API Call',&ndash;&gt;-->
<!--&lt;!&ndash;    url: '',&ndash;&gt;-->
<!--&lt;!&ndash;    method: 'GET',&ndash;&gt;-->
<!--&lt;!&ndash;    headers: {},&ndash;&gt;-->
<!--&lt;!&ndash;    body: {},&ndash;&gt;-->
<!--&lt;!&ndash;    output_variable: 'api_result'&ndash;&gt;-->
<!--&lt;!&ndash;  });&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;const addTransformNode = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  createNode('transform', {&ndash;&gt;-->
<!--&lt;!&ndash;    label: 'Transform',&ndash;&gt;-->
<!--&lt;!&ndash;    input_variable: '',&ndash;&gt;-->
<!--&lt;!&ndash;    output_variable: '',&ndash;&gt;-->
<!--&lt;!&ndash;    transformation: 'json_parse',&ndash;&gt;-->
<!--&lt;!&ndash;    regex: '',&ndash;&gt;-->
<!--&lt;!&ndash;    code: ''&ndash;&gt;-->
<!--&lt;!&ndash;  });&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Handle connection between nodes&ndash;&gt;-->
<!--&lt;!&ndash;const onConnect = (params) => {&ndash;&gt;-->
<!--&lt;!&ndash;  const newEdge = {&ndash;&gt;-->
<!--&lt;!&ndash;    id: `edge-${uuidv4()}`,&ndash;&gt;-->
<!--&lt;!&ndash;    source: params.source,&ndash;&gt;-->
<!--&lt;!&ndash;    target: params.target,&ndash;&gt;-->
<!--&lt;!&ndash;    animated: true&ndash;&gt;-->
<!--&lt;!&ndash;  };&ndash;&gt;-->

<!--&lt;!&ndash;  updateEdges([...flowEdges.value, newEdge]);&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Track changes to nodes and edges&ndash;&gt;-->
<!--&lt;!&ndash;const onNodesChange = (changes) => {&ndash;&gt;-->
<!--&lt;!&ndash;  const updatedNodes = vueFlow.applyNodeChanges(changes, flowNodes.value);&ndash;&gt;-->
<!--&lt;!&ndash;  flowNodes.value = updatedNodes;&ndash;&gt;-->

<!--&lt;!&ndash;  // Update form data when nodes change&ndash;&gt;-->
<!--&lt;!&ndash;  form.nodes = updatedNodes.map(node => ({&ndash;&gt;-->
<!--&lt;!&ndash;    id: node.id,&ndash;&gt;-->
<!--&lt;!&ndash;    type: node.data?.type || '',&ndash;&gt;-->
<!--&lt;!&ndash;    position: node.position || { x: 0, y: 0 },&ndash;&gt;-->
<!--&lt;!&ndash;    data: node.data || {}&ndash;&gt;-->
<!--&lt;!&ndash;  }));&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;const onEdgesChange = (changes) => {&ndash;&gt;-->
<!--&lt;!&ndash;  const updatedEdges = vueFlow.applyEdgeChanges(changes, flowEdges.value);&ndash;&gt;-->
<!--&lt;!&ndash;  flowEdges.value = updatedEdges;&ndash;&gt;-->

<!--&lt;!&ndash;  // Update form data when edges change&ndash;&gt;-->
<!--&lt;!&ndash;  form.edges = updatedEdges;&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Initialize with workflow nodes and edges&ndash;&gt;-->
<!--&lt;!&ndash;const initializeWorkflow = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  if (initialNodesCreated.value) return;&ndash;&gt;-->

<!--&lt;!&ndash;  if (props.workflow.nodes && props.workflow.nodes.length > 0) {&ndash;&gt;-->
<!--&lt;!&ndash;    // Format nodes to match VueFlow requirements&ndash;&gt;-->
<!--&lt;!&ndash;    const nodes = props.workflow.nodes.map(node => {&ndash;&gt;-->
<!--&lt;!&ndash;      // Add prompts to each prompt node&ndash;&gt;-->
<!--&lt;!&ndash;      if (node.type === 'prompt') {&ndash;&gt;-->
<!--&lt;!&ndash;        node.data.prompts = props.prompts;&ndash;&gt;-->
<!--&lt;!&ndash;      }&ndash;&gt;-->

<!--&lt;!&ndash;      return {&ndash;&gt;-->
<!--&lt;!&ndash;        ...node,&ndash;&gt;-->
<!--&lt;!&ndash;        // Make sure node types match component names expected by Vue Flow&ndash;&gt;-->
<!--&lt;!&ndash;        type: `${node.type}Node`,&ndash;&gt;-->
<!--&lt;!&ndash;      };&ndash;&gt;-->
<!--&lt;!&ndash;    });&ndash;&gt;-->

<!--&lt;!&ndash;    updateNodes(nodes);&ndash;&gt;-->
<!--&lt;!&ndash;  } else {&ndash;&gt;-->
<!--&lt;!&ndash;    // If no nodes, add default nodes&ndash;&gt;-->
<!--&lt;!&ndash;    const inputNodeId = `node-${uuidv4()}`;&ndash;&gt;-->
<!--&lt;!&ndash;    const outputNodeId = `node-${uuidv4()}`;&ndash;&gt;-->

<!--&lt;!&ndash;    const initialNodes = [&ndash;&gt;-->
<!--&lt;!&ndash;      {&ndash;&gt;-->
<!--&lt;!&ndash;        id: inputNodeId,&ndash;&gt;-->
<!--&lt;!&ndash;        type: 'inputNode',&ndash;&gt;-->
<!--&lt;!&ndash;        position: { x: 250, y: 100 },&ndash;&gt;-->
<!--&lt;!&ndash;        data: {&ndash;&gt;-->
<!--&lt;!&ndash;          label: 'Input',&ndash;&gt;-->
<!--&lt;!&ndash;          variable: 'input',&ndash;&gt;-->
<!--&lt;!&ndash;          default_value: '',&ndash;&gt;-->
<!--&lt;!&ndash;          type: 'input'&ndash;&gt;-->
<!--&lt;!&ndash;        }&ndash;&gt;-->
<!--&lt;!&ndash;      },&ndash;&gt;-->
<!--&lt;!&ndash;      {&ndash;&gt;-->
<!--&lt;!&ndash;        id: outputNodeId,&ndash;&gt;-->
<!--&lt;!&ndash;        type: 'outputNode',&ndash;&gt;-->
<!--&lt;!&ndash;        position: { x: 250, y: 300 },&ndash;&gt;-->
<!--&lt;!&ndash;        data: {&ndash;&gt;-->
<!--&lt;!&ndash;          label: 'Output',&ndash;&gt;-->
<!--&lt;!&ndash;          variables: ['result'],&ndash;&gt;-->
<!--&lt;!&ndash;          type: 'output'&ndash;&gt;-->
<!--&lt;!&ndash;        }&ndash;&gt;-->
<!--&lt;!&ndash;      }&ndash;&gt;-->
<!--&lt;!&ndash;    ];&ndash;&gt;-->

<!--&lt;!&ndash;    updateNodes(initialNodes);&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->

<!--&lt;!&ndash;  if (props.workflow.edges && props.workflow.edges.length > 0) {&ndash;&gt;-->
<!--&lt;!&ndash;    updateEdges(props.workflow.edges);&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->

<!--&lt;!&ndash;  initialNodesCreated.value = true;&ndash;&gt;-->

<!--&lt;!&ndash;  // Update form with initial nodes and edges&ndash;&gt;-->
<!--&lt;!&ndash;  nextTick(() => {&ndash;&gt;-->
<!--&lt;!&ndash;    if (flowNodes.value?.length > 0) {&ndash;&gt;-->
<!--&lt;!&ndash;      form.nodes = flowNodes.value.map(node => ({&ndash;&gt;-->
<!--&lt;!&ndash;        id: node.id,&ndash;&gt;-->
<!--&lt;!&ndash;        type: node.data.type,&ndash;&gt;-->
<!--&lt;!&ndash;        position: node.position,&ndash;&gt;-->
<!--&lt;!&ndash;        data: node.data&ndash;&gt;-->
<!--&lt;!&ndash;      }));&ndash;&gt;-->

<!--&lt;!&ndash;      form.edges = flowEdges.value;&ndash;&gt;-->

<!--&lt;!&ndash;      // Fit view to see all nodes&ndash;&gt;-->
<!--&lt;!&ndash;      setTimeout(() => {&ndash;&gt;-->
<!--&lt;!&ndash;        vueFlow.fitView();&ndash;&gt;-->
<!--&lt;!&ndash;      }, 100);&ndash;&gt;-->
<!--&lt;!&ndash;    }&ndash;&gt;-->
<!--&lt;!&ndash;  });&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Fullscreen toggle function&ndash;&gt;-->
<!--&lt;!&ndash;const toggleFullscreen = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  isFullscreen.value = !isFullscreen.value;&ndash;&gt;-->

<!--&lt;!&ndash;  if (isFullscreen.value) {&ndash;&gt;-->
<!--&lt;!&ndash;    if (workflowBuilder.value?.requestFullscreen) {&ndash;&gt;-->
<!--&lt;!&ndash;      workflowBuilder.value.requestFullscreen();&ndash;&gt;-->
<!--&lt;!&ndash;    }&ndash;&gt;-->
<!--&lt;!&ndash;  } else {&ndash;&gt;-->
<!--&lt;!&ndash;    if (document.exitFullscreen) {&ndash;&gt;-->
<!--&lt;!&ndash;      document.exitFullscreen();&ndash;&gt;-->
<!--&lt;!&ndash;    }&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Handle fullscreen change from browser controls&ndash;&gt;-->
<!--&lt;!&ndash;const handleFullscreenChange = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  if (!document.fullscreenElement) {&ndash;&gt;-->
<!--&lt;!&ndash;    isFullscreen.value = false;&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Add event listener for fullscreen change&ndash;&gt;-->
<!--&lt;!&ndash;onMounted(() => {&ndash;&gt;-->
<!--&lt;!&ndash;  document.addEventListener('fullscreenchange', handleFullscreenChange);&ndash;&gt;-->

<!--&lt;!&ndash;  // Use nextTick to ensure Vue Flow is mounted before adding nodes&ndash;&gt;-->
<!--&lt;!&ndash;  nextTick(() => {&ndash;&gt;-->
<!--&lt;!&ndash;    initializeWorkflow();&ndash;&gt;-->
<!--&lt;!&ndash;  });&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Clean up event listener&ndash;&gt;-->
<!--&lt;!&ndash;onUnmounted(() => {&ndash;&gt;-->
<!--&lt;!&ndash;  document.removeEventListener('fullscreenchange', handleFullscreenChange);&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Handle keyboard events for node/edge deletion&ndash;&gt;-->
<!--&lt;!&ndash;const handleKeyDown = (event) => {&ndash;&gt;-->
<!--&lt;!&ndash;  // Only allow Delete key and only when not in an input or textarea&ndash;&gt;-->
<!--&lt;!&ndash;  if (event.key === 'Delete' && !event.target.closest('input, textarea')) {&ndash;&gt;-->
<!--&lt;!&ndash;    deleteSelected();&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Delete selected nodes and edges&ndash;&gt;-->
<!--&lt;!&ndash;const deleteSelected = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  // Get selected nodes and edges&ndash;&gt;-->
<!--&lt;!&ndash;  const selectedNodes = flowNodes.value.filter(node => node.selected);&ndash;&gt;-->
<!--&lt;!&ndash;  const selectedEdges = flowEdges.value.filter(edge => edge.selected);&ndash;&gt;-->

<!--&lt;!&ndash;  if (selectedNodes.length > 0) {&ndash;&gt;-->
<!--&lt;!&ndash;    // Create a new array without the selected nodes&ndash;&gt;-->
<!--&lt;!&ndash;    const newNodes = flowNodes.value.filter(node => !node.selected);&ndash;&gt;-->
<!--&lt;!&ndash;    updateNodes(newNodes);&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->

<!--&lt;!&ndash;  if (selectedEdges.length > 0) {&ndash;&gt;-->
<!--&lt;!&ndash;    // Create a new array without the selected edges&ndash;&gt;-->
<!--&lt;!&ndash;    const newEdges = flowEdges.value.filter(edge => !edge.selected);&ndash;&gt;-->
<!--&lt;!&ndash;    updateEdges(newEdges);&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Zoom utilities&ndash;&gt;-->
<!--&lt;!&ndash;const handleZoomIn = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  vueFlow.zoomIn();&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;const handleZoomOut = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  vueFlow.zoomOut();&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;const handleFitView = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  vueFlow.fitView();&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Cancel editing and return to workflow list&ndash;&gt;-->
<!--&lt;!&ndash;const cancelEdit = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  router.get(route('workflows.index'));&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Submit the workflow&ndash;&gt;-->
<!--&lt;!&ndash;const submit = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  try {&ndash;&gt;-->
<!--&lt;!&ndash;    // Update form data with latest nodes and edges&ndash;&gt;-->
<!--&lt;!&ndash;    form.nodes = flowNodes.value.map(node => ({&ndash;&gt;-->
<!--&lt;!&ndash;      id: node.id,&ndash;&gt;-->
<!--&lt;!&ndash;      type: node.data?.type || '',&ndash;&gt;-->
<!--&lt;!&ndash;      position: node.position || { x: 0, y: 0 },&ndash;&gt;-->
<!--&lt;!&ndash;      data: node.data || {}&ndash;&gt;-->
<!--&lt;!&ndash;    }));&ndash;&gt;-->

<!--&lt;!&ndash;    form.edges = flowEdges.value;&ndash;&gt;-->

<!--&lt;!&ndash;    // Submit the form&ndash;&gt;-->
<!--&lt;!&ndash;    form.put(route('workflows.update', props.workflow.id), {&ndash;&gt;-->
<!--&lt;!&ndash;      onSuccess: () => {&ndash;&gt;-->
<!--&lt;!&ndash;        console.log("Workflow updated successfully");&ndash;&gt;-->
<!--&lt;!&ndash;      },&ndash;&gt;-->
<!--&lt;!&ndash;      onError: (errors) => {&ndash;&gt;-->
<!--&lt;!&ndash;        console.error("Error updating workflow:", errors);&ndash;&gt;-->
<!--&lt;!&ndash;      }&ndash;&gt;-->
<!--&lt;!&ndash;    });&ndash;&gt;-->
<!--&lt;!&ndash;  } catch (error) {&ndash;&gt;-->
<!--&lt;!&ndash;    console.error("Error in submit function:", error);&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Check if the workflow is valid&ndash;&gt;-->
<!--&lt;!&ndash;const isValid = computed(() => {&ndash;&gt;-->
<!--&lt;!&ndash;  return form.name.trim() !== '' && flowNodes.value.length > 0;&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;console.log('Prompts aus Props:', props.prompts);&ndash;&gt;-->
<!--&lt;!&ndash;</script>&ndash;&gt;-->

<!--&lt;!&ndash;<template>&ndash;&gt;-->
<!--&lt;!&ndash;  <Head title="Edit Workflow" />&ndash;&gt;-->

<!--&lt;!&ndash;  <BasePageHeading :title="`Edit: ${workflow.name}`" subtitle="Modify your workflow">&ndash;&gt;-->
<!--&lt;!&ndash;    <template #extra>&ndash;&gt;-->
<!--&lt;!&ndash;      <nav class="breadcrumb push">&ndash;&gt;-->
<!--&lt;!&ndash;        <Link :href="route('dashboard')" class="breadcrumb-item">&ndash;&gt;-->
<!--&lt;!&ndash;          <i class="fa fa-home"></i>&ndash;&gt;-->
<!--&lt;!&ndash;        </Link>&ndash;&gt;-->
<!--&lt;!&ndash;        <Link :href="route('workflows.index')" class="breadcrumb-item">&ndash;&gt;-->
<!--&lt;!&ndash;          Workflows&ndash;&gt;-->
<!--&lt;!&ndash;        </Link>&ndash;&gt;-->
<!--&lt;!&ndash;        <Link :href="route('workflows.show', workflow.id)" class="breadcrumb-item">&ndash;&gt;-->
<!--&lt;!&ndash;          {{ workflow.name }}&ndash;&gt;-->
<!--&lt;!&ndash;        </Link>&ndash;&gt;-->
<!--&lt;!&ndash;        <span class="breadcrumb-item active">Edit</span>&ndash;&gt;-->
<!--&lt;!&ndash;      </nav>&ndash;&gt;-->
<!--&lt;!&ndash;    </template>&ndash;&gt;-->
<!--&lt;!&ndash;  </BasePageHeading>&ndash;&gt;-->

<!--&lt;!&ndash;  <div class="content">&ndash;&gt;-->
<!--&lt;!&ndash;    <div class="row">&ndash;&gt;-->
<!--&lt;!&ndash;      &lt;!&ndash; Workflow Details Block &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;      <div class="col-md-12 mb-4">&ndash;&gt;-->
<!--&lt;!&ndash;        <div class="block block-rounded">&ndash;&gt;-->
<!--&lt;!&ndash;          <div class="block-header block-header-default">&ndash;&gt;-->
<!--&lt;!&ndash;            <h3 class="block-title">Workflow Details</h3>&ndash;&gt;-->
<!--&lt;!&ndash;          </div>&ndash;&gt;-->
<!--&lt;!&ndash;          <div class="block-content">&ndash;&gt;-->
<!--&lt;!&ndash;            <div class="row">&ndash;&gt;-->
<!--&lt;!&ndash;              <div class="col-md-6">&ndash;&gt;-->
<!--&lt;!&ndash;                <div class="mb-4">&ndash;&gt;-->
<!--&lt;!&ndash;                  <label class="form-label" for="workflow-name">Workflow Name</label>&ndash;&gt;-->
<!--&lt;!&ndash;                  <input&ndash;&gt;-->
<!--&lt;!&ndash;                    type="text"&ndash;&gt;-->
<!--&lt;!&ndash;                    id="workflow-name"&ndash;&gt;-->
<!--&lt;!&ndash;                    class="form-control"&ndash;&gt;-->
<!--&lt;!&ndash;                    v-model="form.name"&ndash;&gt;-->
<!--&lt;!&ndash;                    placeholder="Enter workflow name"&ndash;&gt;-->
<!--&lt;!&ndash;                    required&ndash;&gt;-->
<!--&lt;!&ndash;                  >&ndash;&gt;-->
<!--&lt;!&ndash;                  <div v-if="form.errors.name" class="invalid-feedback d-block">&ndash;&gt;-->
<!--&lt;!&ndash;                    {{ form.errors.name }}&ndash;&gt;-->
<!--&lt;!&ndash;                  </div>&ndash;&gt;-->
<!--&lt;!&ndash;                </div>&ndash;&gt;-->
<!--&lt;!&ndash;              </div>&ndash;&gt;-->
<!--&lt;!&ndash;              <div class="col-md-6">&ndash;&gt;-->
<!--&lt;!&ndash;                <div class="mb-4">&ndash;&gt;-->
<!--&lt;!&ndash;                  <label class="form-label" for="workflow-description">Description (optional)</label>&ndash;&gt;-->
<!--&lt;!&ndash;                  <textarea&ndash;&gt;-->
<!--&lt;!&ndash;                    id="workflow-description"&ndash;&gt;-->
<!--&lt;!&ndash;                    class="form-control"&ndash;&gt;-->
<!--&lt;!&ndash;                    v-model="form.description"&ndash;&gt;-->
<!--&lt;!&ndash;                    placeholder="Describe what this workflow does"&ndash;&gt;-->
<!--&lt;!&ndash;                    rows="1"&ndash;&gt;-->
<!--&lt;!&ndash;                  ></textarea>&ndash;&gt;-->
<!--&lt;!&ndash;                  <div v-if="form.errors.description" class="invalid-feedback d-block">&ndash;&gt;-->
<!--&lt;!&ndash;                    {{ form.errors.description }}&ndash;&gt;-->
<!--&lt;!&ndash;                  </div>&ndash;&gt;-->
<!--&lt;!&ndash;                </div>&ndash;&gt;-->
<!--&lt;!&ndash;              </div>&ndash;&gt;-->
<!--&lt;!&ndash;            </div>&ndash;&gt;-->
<!--&lt;!&ndash;            <div class="row">&ndash;&gt;-->
<!--&lt;!&ndash;              <div class="col-md-6">&ndash;&gt;-->
<!--&lt;!&ndash;                <div class="mb-4">&ndash;&gt;-->
<!--&lt;!&ndash;                  <div class="form-check form-switch">&ndash;&gt;-->
<!--&lt;!&ndash;                    <input class="form-check-input" type="checkbox" id="workflow-active" v-model="form.is_active">&ndash;&gt;-->
<!--&lt;!&ndash;                    <label class="form-check-label" for="workflow-active">&ndash;&gt;-->
<!--&lt;!&ndash;                      Workflow is active&ndash;&gt;-->
<!--&lt;!&ndash;                    </label>&ndash;&gt;-->
<!--&lt;!&ndash;                  </div>&ndash;&gt;-->
<!--&lt;!&ndash;                  <div class="fs-sm text-muted mt-1">&ndash;&gt;-->
<!--&lt;!&ndash;                    When active, this workflow can be executed. Inactive workflows cannot be run.&ndash;&gt;-->
<!--&lt;!&ndash;                  </div>&ndash;&gt;-->
<!--&lt;!&ndash;                </div>&ndash;&gt;-->
<!--&lt;!&ndash;              </div>&ndash;&gt;-->
<!--&lt;!&ndash;            </div>&ndash;&gt;-->
<!--&lt;!&ndash;          </div>&ndash;&gt;-->
<!--&lt;!&ndash;        </div>&ndash;&gt;-->
<!--&lt;!&ndash;      </div>&ndash;&gt;-->

<!--&lt;!&ndash;      &lt;!&ndash; Workflow Builder &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;      <div class="col-md-12">&ndash;&gt;-->
<!--&lt;!&ndash;        <div class="block block-rounded">&ndash;&gt;-->
<!--&lt;!&ndash;          <div class="block-header block-header-default">&ndash;&gt;-->
<!--&lt;!&ndash;            <h3 class="block-title">Workflow Builder</h3>&ndash;&gt;-->
<!--&lt;!&ndash;            <div class="block-options">&ndash;&gt;-->
<!--&lt;!&ndash;              <button&ndash;&gt;-->
<!--&lt;!&ndash;                type="button"&ndash;&gt;-->
<!--&lt;!&ndash;                class="btn btn-sm btn-alt-secondary me-1"&ndash;&gt;-->
<!--&lt;!&ndash;                @click="toggleFullscreen"&ndash;&gt;-->
<!--&lt;!&ndash;                title="Toggle Fullscreen"&ndash;&gt;-->
<!--&lt;!&ndash;              >&ndash;&gt;-->
<!--&lt;!&ndash;                <i :class="['fa', isFullscreen ? 'fa-compress' : 'fa-expand']"></i>&ndash;&gt;-->
<!--&lt;!&ndash;              </button>&ndash;&gt;-->
<!--&lt;!&ndash;              <button&ndash;&gt;-->
<!--&lt;!&ndash;                type="button"&ndash;&gt;-->
<!--&lt;!&ndash;                class="btn btn-sm btn-alt-primary"&ndash;&gt;-->
<!--&lt;!&ndash;                @click="submit"&ndash;&gt;-->
<!--&lt;!&ndash;                :disabled="!isValid || form.processing"&ndash;&gt;-->
<!--&lt;!&ndash;              >&ndash;&gt;-->
<!--&lt;!&ndash;                <i class="fa fa-fw fa-save opacity-50 me-1"></i> Save Workflow&ndash;&gt;-->
<!--&lt;!&ndash;              </button>&ndash;&gt;-->
<!--&lt;!&ndash;            </div>&ndash;&gt;-->
<!--&lt;!&ndash;          </div>&ndash;&gt;-->
<!--&lt;!&ndash;          <div class="block-content p-0">&ndash;&gt;-->
<!--&lt;!&ndash;            <div&ndash;&gt;-->
<!--&lt;!&ndash;              class="workflow-builder"&ndash;&gt;-->
<!--&lt;!&ndash;              :class="{'fullscreen': isFullscreen}"&ndash;&gt;-->
<!--&lt;!&ndash;              ref="workflowBuilder"&ndash;&gt;-->
<!--&lt;!&ndash;              style="height: 600px; position: relative;"&ndash;&gt;-->
<!--&lt;!&ndash;            >&ndash;&gt;-->
<!--&lt;!&ndash;              &lt;!&ndash; Sidebar &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;              <div class="workflow-sidebar bg-body-light border-end p-3" style="width: 260px; height: 100%; position: absolute; left: 0; top: 0; overflow-y: auto; z-index: 10;">&ndash;&gt;-->
<!--&lt;!&ndash;                &lt;!&ndash; Sidebar Tabs &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                <ul class="nav nav-tabs nav-tabs-block" role="tablist">&ndash;&gt;-->
<!--&lt;!&ndash;                  <li class="nav-item" role="presentation">&ndash;&gt;-->
<!--&lt;!&ndash;                    <button&ndash;&gt;-->
<!--&lt;!&ndash;                      class="nav-link"&ndash;&gt;-->
<!--&lt;!&ndash;                      :class="{'active': activePanel === 'nodes'}"&ndash;&gt;-->
<!--&lt;!&ndash;                      @click="activePanel = 'nodes'"&ndash;&gt;-->
<!--&lt;!&ndash;                    >&ndash;&gt;-->
<!--&lt;!&ndash;                      <i class="fa fa-cubes me-1"></i> Nodes&ndash;&gt;-->
<!--&lt;!&ndash;                    </button>&ndash;&gt;-->
<!--&lt;!&ndash;                  </li>&ndash;&gt;-->
<!--&lt;!&ndash;                  <li class="nav-item" role="presentation">&ndash;&gt;-->
<!--&lt;!&ndash;                    <button&ndash;&gt;-->
<!--&lt;!&ndash;                      class="nav-link"&ndash;&gt;-->
<!--&lt;!&ndash;                      :class="{'active': activePanel === 'properties'}"&ndash;&gt;-->
<!--&lt;!&ndash;                      @click="activePanel = 'properties'"&ndash;&gt;-->
<!--&lt;!&ndash;                    >&ndash;&gt;-->
<!--&lt;!&ndash;                      <i class="fa fa-cog me-1"></i> Properties&ndash;&gt;-->
<!--&lt;!&ndash;                    </button>&ndash;&gt;-->
<!--&lt;!&ndash;                  </li>&ndash;&gt;-->
<!--&lt;!&ndash;                </ul>&ndash;&gt;-->

<!--&lt;!&ndash;                &lt;!&ndash; Nodes Panel &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                <div v-if="activePanel === 'nodes'" class="py-3">&ndash;&gt;-->
<!--&lt;!&ndash;                  <div class="fs-sm fw-semibold text-uppercase mb-2">Add Nodes</div>&ndash;&gt;-->
<!--&lt;!&ndash;                  <div class="d-grid gap-2">&ndash;&gt;-->
<!--&lt;!&ndash;                    &lt;!&ndash; Prompt Node &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                    <button&ndash;&gt;-->
<!--&lt;!&ndash;                      type="button"&ndash;&gt;-->
<!--&lt;!&ndash;                      class="btn btn-alt-primary"&ndash;&gt;-->
<!--&lt;!&ndash;                      @click="addPromptNode"&ndash;&gt;-->
<!--&lt;!&ndash;                    >&ndash;&gt;-->
<!--&lt;!&ndash;                      <i class="fa fa-file-alt me-1"></i> Prompt&ndash;&gt;-->
<!--&lt;!&ndash;                    </button>&ndash;&gt;-->

<!--&lt;!&ndash;                    &lt;!&ndash; Condition Node &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                    <button&ndash;&gt;-->
<!--&lt;!&ndash;                      type="button"&ndash;&gt;-->
<!--&lt;!&ndash;                      class="btn btn-alt-warning"&ndash;&gt;-->
<!--&lt;!&ndash;                      @click="addConditionNode"&ndash;&gt;-->
<!--&lt;!&ndash;                    >&ndash;&gt;-->
<!--&lt;!&ndash;                      <i class="fa fa-code-branch me-1"></i> Condition&ndash;&gt;-->
<!--&lt;!&ndash;                    </button>&ndash;&gt;-->

<!--&lt;!&ndash;                    &lt;!&ndash; Input Node &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                    <button&ndash;&gt;-->
<!--&lt;!&ndash;                      type="button"&ndash;&gt;-->
<!--&lt;!&ndash;                      class="btn btn-alt-info"&ndash;&gt;-->
<!--&lt;!&ndash;                      @click="addInputNode"&ndash;&gt;-->
<!--&lt;!&ndash;                    >&ndash;&gt;-->
<!--&lt;!&ndash;                      <i class="fa fa-sign-in-alt me-1"></i> Input&ndash;&gt;-->
<!--&lt;!&ndash;                    </button>&ndash;&gt;-->

<!--&lt;!&ndash;                    &lt;!&ndash; Output Node &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                    <button&ndash;&gt;-->
<!--&lt;!&ndash;                      type="button"&ndash;&gt;-->
<!--&lt;!&ndash;                      class="btn btn-alt-success"&ndash;&gt;-->
<!--&lt;!&ndash;                      @click="addOutputNode"&ndash;&gt;-->
<!--&lt;!&ndash;                    >&ndash;&gt;-->
<!--&lt;!&ndash;                      <i class="fa fa-sign-out-alt me-1"></i> Output&ndash;&gt;-->
<!--&lt;!&ndash;                    </button>&ndash;&gt;-->

<!--&lt;!&ndash;                    &lt;!&ndash; API Node &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                    <button&ndash;&gt;-->
<!--&lt;!&ndash;                      type="button"&ndash;&gt;-->
<!--&lt;!&ndash;                      class="btn btn-alt-danger"&ndash;&gt;-->
<!--&lt;!&ndash;                      @click="addApiNode"&ndash;&gt;-->
<!--&lt;!&ndash;                    >&ndash;&gt;-->
<!--&lt;!&ndash;                      <i class="fa fa-globe me-1"></i> API Call&ndash;&gt;-->
<!--&lt;!&ndash;                    </button>&ndash;&gt;-->

<!--&lt;!&ndash;                    &lt;!&ndash; Transform Node &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                    <button&ndash;&gt;-->
<!--&lt;!&ndash;                      type="button"&ndash;&gt;-->
<!--&lt;!&ndash;                      class="btn btn-alt-secondary"&ndash;&gt;-->
<!--&lt;!&ndash;                      @click="addTransformNode"&ndash;&gt;-->
<!--&lt;!&ndash;                    >&ndash;&gt;-->
<!--&lt;!&ndash;                      <i class="fa fa-exchange-alt me-1"></i> Transform&ndash;&gt;-->
<!--&lt;!&ndash;                    </button>&ndash;&gt;-->
<!--&lt;!&ndash;                  </div>&ndash;&gt;-->

<!--&lt;!&ndash;                  <div class="fs-sm fw-semibold text-uppercase mt-4 mb-2">Instructions</div>&ndash;&gt;-->
<!--&lt;!&ndash;                  <ul class="fs-sm text-muted ps-3">&ndash;&gt;-->
<!--&lt;!&ndash;                    <li>Add nodes to build your workflow</li>&ndash;&gt;-->
<!--&lt;!&ndash;                    <li>Connect nodes by dragging from one node to another</li>&ndash;&gt;-->
<!--&lt;!&ndash;                    <li>Configure nodes by selecting them</li>&ndash;&gt;-->
<!--&lt;!&ndash;                    <li>Drag nodes to move them around the canvas</li>&ndash;&gt;-->
<!--&lt;!&ndash;                    <li>Select an edge and press Delete to remove it</li>&ndash;&gt;-->
<!--&lt;!&ndash;                    <li>Select a node and press Delete to remove it</li>&ndash;&gt;-->
<!--&lt;!&ndash;                  </ul>&ndash;&gt;-->
<!--&lt;!&ndash;                </div>&ndash;&gt;-->

<!--&lt;!&ndash;                &lt;!&ndash; Properties Panel (for future implementation) &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                <div v-if="activePanel === 'properties'" class="py-3">&ndash;&gt;-->
<!--&lt;!&ndash;                  <div class="alert alert-info d-flex">&ndash;&gt;-->
<!--&lt;!&ndash;                    <div class="flex-shrink-0">&ndash;&gt;-->
<!--&lt;!&ndash;                      <i class="fa fa-info-circle fa-fw"></i>&ndash;&gt;-->
<!--&lt;!&ndash;                    </div>&ndash;&gt;-->
<!--&lt;!&ndash;                    <div class="ms-2">&ndash;&gt;-->
<!--&lt;!&ndash;                      Select a node in the workflow to view and edit its properties&ndash;&gt;-->
<!--&lt;!&ndash;                    </div>&ndash;&gt;-->
<!--&lt;!&ndash;                  </div>&ndash;&gt;-->

<!--&lt;!&ndash;                  <div class="form-check form-switch mb-3">&ndash;&gt;-->
<!--&lt;!&ndash;                    <input&ndash;&gt;-->
<!--&lt;!&ndash;                      class="form-check-input"&ndash;&gt;-->
<!--&lt;!&ndash;                      type="checkbox"&ndash;&gt;-->
<!--&lt;!&ndash;                      id="workflow-autosave"&ndash;&gt;-->
<!--&lt;!&ndash;                      v-model="form.settings.autoSave"&ndash;&gt;-->
<!--&lt;!&ndash;                    >&ndash;&gt;-->
<!--&lt;!&ndash;                    <label class="form-check-label" for="workflow-autosave">&ndash;&gt;-->
<!--&lt;!&ndash;                      Auto save workflow on execution&ndash;&gt;-->
<!--&lt;!&ndash;                    </label>&ndash;&gt;-->
<!--&lt;!&ndash;                  </div>&ndash;&gt;-->

<!--&lt;!&ndash;                  <div class="form-check form-switch">&ndash;&gt;-->
<!--&lt;!&ndash;                    <input&ndash;&gt;-->
<!--&lt;!&ndash;                      class="form-check-input"&ndash;&gt;-->
<!--&lt;!&ndash;                      type="checkbox"&ndash;&gt;-->
<!--&lt;!&ndash;                      id="workflow-notify"&ndash;&gt;-->
<!--&lt;!&ndash;                      v-model="form.settings.notifyOnCompletion"&ndash;&gt;-->
<!--&lt;!&ndash;                    >&ndash;&gt;-->
<!--&lt;!&ndash;                    <label class="form-check-label" for="workflow-notify">&ndash;&gt;-->
<!--&lt;!&ndash;                      Notify on workflow completion&ndash;&gt;-->
<!--&lt;!&ndash;                    </label>&ndash;&gt;-->
<!--&lt;!&ndash;                  </div>&ndash;&gt;-->
<!--&lt;!&ndash;                </div>&ndash;&gt;-->
<!--&lt;!&ndash;              </div>&ndash;&gt;-->

<!--&lt;!&ndash;              &lt;!&ndash; Workflow Canvas &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;              <div style="margin-left: 260px; height: 100%">&ndash;&gt;-->
<!--&lt;!&ndash;                <VueFlow&ndash;&gt;-->
<!--&lt;!&ndash;                  v-model:nodes="flowNodes"&ndash;&gt;-->
<!--&lt;!&ndash;                  v-model:edges="flowEdges"&ndash;&gt;-->
<!--&lt;!&ndash;                  :nodeTypes="nodeTypes"&ndash;&gt;-->
<!--&lt;!&ndash;                  :default-zoom="1.5"&ndash;&gt;-->
<!--&lt;!&ndash;                  :min-zoom="0.2"&ndash;&gt;-->
<!--&lt;!&ndash;                  :max-zoom="4"&ndash;&gt;-->
<!--&lt;!&ndash;                  @nodesChange="onNodesChange"&ndash;&gt;-->
<!--&lt;!&ndash;                  @edgesChange="onEdgesChange"&ndash;&gt;-->
<!--&lt;!&ndash;                  @connect="onConnect"&ndash;&gt;-->
<!--&lt;!&ndash;                  @keydown="handleKeyDown"&ndash;&gt;-->
<!--&lt;!&ndash;                  :deleteKeyCode="['Delete']"&ndash;&gt;-->
<!--&lt;!&ndash;                  :nodeDragHandleClassName="'node-drag-handle'"&ndash;&gt;-->
<!--&lt;!&ndash;                  class="workflow-canvas"&ndash;&gt;-->
<!--&lt;!&ndash;                >&ndash;&gt;-->
<!--&lt;!&ndash;                  <Background pattern-color="#aaa" gap="24" />&ndash;&gt;-->
<!--&lt;!&ndash;                  <MiniMap&ndash;&gt;-->
<!--&lt;!&ndash;                    height="120"&ndash;&gt;-->
<!--&lt;!&ndash;                    width="240"&ndash;&gt;-->
<!--&lt;!&ndash;                    nodeStrokeColor="#111"&ndash;&gt;-->
<!--&lt;!&ndash;                    nodeColor="#ddd"&ndash;&gt;-->
<!--&lt;!&ndash;                    nodeBorderRadius="3"&ndash;&gt;-->
<!--&lt;!&ndash;                  />&ndash;&gt;-->
<!--&lt;!&ndash;                  <Controls&ndash;&gt;-->
<!--&lt;!&ndash;                    :showZoom="true"&ndash;&gt;-->
<!--&lt;!&ndash;                    :showFitView="true"&ndash;&gt;-->
<!--&lt;!&ndash;                    :showInteractive="true"&ndash;&gt;-->
<!--&lt;!&ndash;                    :fitViewPadding="0.2"&ndash;&gt;-->
<!--&lt;!&ndash;                  />&ndash;&gt;-->

<!--&lt;!&ndash;                  <Panel position="top-right" class="p-2">&ndash;&gt;-->
<!--&lt;!&ndash;                    <div class="btn-group">&ndash;&gt;-->
<!--&lt;!&ndash;                      <button&ndash;&gt;-->
<!--&lt;!&ndash;                        type="button"&ndash;&gt;-->
<!--&lt;!&ndash;                        class="btn btn-sm btn-alt-secondary"&ndash;&gt;-->
<!--&lt;!&ndash;                        title="Zoom In"&ndash;&gt;-->
<!--&lt;!&ndash;                        @click="handleZoomIn"&ndash;&gt;-->
<!--&lt;!&ndash;                      >&ndash;&gt;-->
<!--&lt;!&ndash;                        <i class="fa fa-search-plus"></i>&ndash;&gt;-->
<!--&lt;!&ndash;                      </button>&ndash;&gt;-->
<!--&lt;!&ndash;                      <button&ndash;&gt;-->
<!--&lt;!&ndash;                        type="button"&ndash;&gt;-->
<!--&lt;!&ndash;                        class="btn btn-sm btn-alt-secondary"&ndash;&gt;-->
<!--&lt;!&ndash;                        title="Zoom Out"&ndash;&gt;-->
<!--&lt;!&ndash;                        @click="handleZoomOut"&ndash;&gt;-->
<!--&lt;!&ndash;                      >&ndash;&gt;-->
<!--&lt;!&ndash;                        <i class="fa fa-search-minus"></i>&ndash;&gt;-->
<!--&lt;!&ndash;                      </button>&ndash;&gt;-->
<!--&lt;!&ndash;                      <button&ndash;&gt;-->
<!--&lt;!&ndash;                        type="button"&ndash;&gt;-->
<!--&lt;!&ndash;                        class="btn btn-sm btn-alt-secondary"&ndash;&gt;-->
<!--&lt;!&ndash;                        title="Fit View"&ndash;&gt;-->
<!--&lt;!&ndash;                        @click="handleFitView"&ndash;&gt;-->
<!--&lt;!&ndash;                      >&ndash;&gt;-->
<!--&lt;!&ndash;                        <i class="fa fa-arrows-alt"></i>&ndash;&gt;-->
<!--&lt;!&ndash;                      </button>&ndash;&gt;-->
<!--&lt;!&ndash;                      <button&ndash;&gt;-->
<!--&lt;!&ndash;                        type="button"&ndash;&gt;-->
<!--&lt;!&ndash;                        class="btn btn-sm btn-alt-secondary"&ndash;&gt;-->
<!--&lt;!&ndash;                        title="Delete Selected"&ndash;&gt;-->
<!--&lt;!&ndash;                        @click="deleteSelected"&ndash;&gt;-->
<!--&lt;!&ndash;                      >&ndash;&gt;-->
<!--&lt;!&ndash;                        <i class="fa fa-trash"></i>&ndash;&gt;-->
<!--&lt;!&ndash;                      </button>&ndash;&gt;-->
<!--&lt;!&ndash;                    </div>&ndash;&gt;-->
<!--&lt;!&ndash;                  </Panel>&ndash;&gt;-->
<!--&lt;!&ndash;                </VueFlow>&ndash;&gt;-->
<!--&lt;!&ndash;              </div>&ndash;&gt;-->
<!--&lt;!&ndash;            </div>&ndash;&gt;-->
<!--&lt;!&ndash;          </div>&ndash;&gt;-->
<!--&lt;!&ndash;        </div>&ndash;&gt;-->
<!--&lt;!&ndash;      </div>&ndash;&gt;-->
<!--&lt;!&ndash;    </div>&ndash;&gt;-->

<!--&lt;!&ndash;    &lt;!&ndash; Action Buttons &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;    <div class="d-flex justify-content-between mb-4">&ndash;&gt;-->
<!--&lt;!&ndash;      <button&ndash;&gt;-->
<!--&lt;!&ndash;        type="button"&ndash;&gt;-->
<!--&lt;!&ndash;        class="btn btn-alt-secondary"&ndash;&gt;-->
<!--&lt;!&ndash;        @click="cancelEdit"&ndash;&gt;-->
<!--&lt;!&ndash;      >&ndash;&gt;-->
<!--&lt;!&ndash;        <i class="fa fa-arrow-left opacity-50 me-1"></i> Cancel&ndash;&gt;-->
<!--&lt;!&ndash;      </button>&ndash;&gt;-->

<!--&lt;!&ndash;      <button&ndash;&gt;-->
<!--&lt;!&ndash;        type="button"&ndash;&gt;-->
<!--&lt;!&ndash;        class="btn btn-alt-primary"&ndash;&gt;-->
<!--&lt;!&ndash;        @click="submit"&ndash;&gt;-->
<!--&lt;!&ndash;        :disabled="!isValid || form.processing"&ndash;&gt;-->
<!--&lt;!&ndash;      >&ndash;&gt;-->
<!--&lt;!&ndash;        <i class="fa fa-save opacity-50 me-1"></i> Save Workflow&ndash;&gt;-->
<!--&lt;!&ndash;      </button>&ndash;&gt;-->
<!--&lt;!&ndash;    </div>&ndash;&gt;-->
<!--&lt;!&ndash;  </div>&ndash;&gt;-->
<!--&lt;!&ndash;</template>&ndash;&gt;-->

<!--&lt;!&ndash;<style>&ndash;&gt;-->
<!--&lt;!&ndash;.workflow-canvas .vue-flow__node {&ndash;&gt;-->
<!--&lt;!&ndash;  border-radius: 5px;&ndash;&gt;-->
<!--&lt;!&ndash;  padding: 10px;&ndash;&gt;-->
<!--&lt;!&ndash;  font-size: 12px;&ndash;&gt;-->
<!--&lt;!&ndash;  text-align: center;&ndash;&gt;-->
<!--&lt;!&ndash;  border: 1px solid #ddd;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.workflow-canvas .vue-flow__node.selected,&ndash;&gt;-->
<!--&lt;!&ndash;.workflow-canvas .vue-flow__node.selected:hover {&ndash;&gt;-->
<!--&lt;!&ndash;  box-shadow: 0 0 0 2px #5c80d1;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.workflow-canvas .vue-flow__node-promptNode {&ndash;&gt;-->
<!--&lt;!&ndash;  background-color: rgba(92, 128, 209, 0.1);&ndash;&gt;-->
<!--&lt;!&ndash;  border-color: #5c80d1;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.workflow-canvas .vue-flow__node-conditionNode {&ndash;&gt;-->
<!--&lt;!&ndash;  background-color: rgba(219, 144, 56, 0.1);&ndash;&gt;-->
<!--&lt;!&ndash;  border-color: #db9038;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.workflow-canvas .vue-flow__node-inputNode {&ndash;&gt;-->
<!--&lt;!&ndash;  background-color: rgba(38, 169, 224, 0.1);&ndash;&gt;-->
<!--&lt;!&ndash;  border-color: #26a9e0;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.workflow-canvas .vue-flow__node-outputNode {&ndash;&gt;-->
<!--&lt;!&ndash;  background-color: rgba(66, 185, 131, 0.1);&ndash;&gt;-->
<!--&lt;!&ndash;  border-color: #42b983;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.workflow-canvas .vue-flow__node-apiNode {&ndash;&gt;-->
<!--&lt;!&ndash;  background-color: rgba(232, 74, 95, 0.1);&ndash;&gt;-->
<!--&lt;!&ndash;  border-color: #e84a5f;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.workflow-canvas .vue-flow__node-transformNode {&ndash;&gt;-->
<!--&lt;!&ndash;  background-color: rgba(108, 117, 125, 0.1);&ndash;&gt;-->
<!--&lt;!&ndash;  border-color: #6c757d;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.workflow-canvas .vue-flow__edge-path {&ndash;&gt;-->
<!--&lt;!&ndash;  stroke: #b1b1b7;&ndash;&gt;-->
<!--&lt;!&ndash;  stroke-width: 2;&ndash;&gt;-->
<!--&lt;!&ndash;  stroke-dasharray: none;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.workflow-canvas .vue-flow__edge.selected .vue-flow__edge-path {&ndash;&gt;-->
<!--&lt;!&ndash;  stroke: #5c80d1;&ndash;&gt;-->
<!--&lt;!&ndash;  stroke-width: 3;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.workflow-canvas .vue-flow__handle {&ndash;&gt;-->
<!--&lt;!&ndash;  width: 8px;&ndash;&gt;-->
<!--&lt;!&ndash;  height: 8px;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;/* Fullscreen mode styles */&ndash;&gt;-->
<!--&lt;!&ndash;.workflow-builder.fullscreen {&ndash;&gt;-->
<!--&lt;!&ndash;  position: fixed;&ndash;&gt;-->
<!--&lt;!&ndash;  top: 0;&ndash;&gt;-->
<!--&lt;!&ndash;  left: 0;&ndash;&gt;-->
<!--&lt;!&ndash;  right: 0;&ndash;&gt;-->
<!--&lt;!&ndash;  bottom: 0;&ndash;&gt;-->
<!--&lt;!&ndash;  z-index: 9999;&ndash;&gt;-->
<!--&lt;!&ndash;  height: 100vh !important;&ndash;&gt;-->
<!--&lt;!&ndash;  width: 100vw;&ndash;&gt;-->
<!--&lt;!&ndash;  background-color: white;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;/* Control panel styles - ensure the icons are visible */&ndash;&gt;-->
<!--&lt;!&ndash;.vue-flow__controls {&ndash;&gt;-->
<!--&lt;!&ndash;  box-shadow: 0 0 2px 1px rgba(0, 0, 0, 0.08);&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.vue-flow__controls-button {&ndash;&gt;-->
<!--&lt;!&ndash;  background: #fafafa;&ndash;&gt;-->
<!--&lt;!&ndash;  border: 1px solid #eee;&ndash;&gt;-->
<!--&lt;!&ndash;  border-radius: 5px;&ndash;&gt;-->
<!--&lt;!&ndash;  height: 24px;&ndash;&gt;-->
<!--&lt;!&ndash;  width: 24px;&ndash;&gt;-->
<!--&lt;!&ndash;  display: flex;&ndash;&gt;-->
<!--&lt;!&ndash;  justify-content: center;&ndash;&gt;-->
<!--&lt;!&ndash;  align-items: center;&ndash;&gt;-->
<!--&lt;!&ndash;  margin-bottom: 8px;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.vue-flow__controls-button svg {&ndash;&gt;-->
<!--&lt;!&ndash;  fill: #333;&ndash;&gt;-->
<!--&lt;!&ndash;  height: 14px;&ndash;&gt;-->
<!--&lt;!&ndash;  width: 14px;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;/* MiniMap styling */&ndash;&gt;-->
<!--&lt;!&ndash;.vue-flow__minimap {&ndash;&gt;-->
<!--&lt;!&ndash;  background-color: #f8f9fa;&ndash;&gt;-->
<!--&lt;!&ndash;  border: 1px solid #ddd;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;/* Selected elements styling */&ndash;&gt;-->
<!--&lt;!&ndash;.vue-flow__node.selected {&ndash;&gt;-->
<!--&lt;!&ndash;  box-shadow: 0 0 0 2px #5c80d1 !important;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;.vue-flow__edge.selected .vue-flow__edge-path {&ndash;&gt;-->
<!--&lt;!&ndash;  stroke: #5c80d1 !important;&ndash;&gt;-->
<!--&lt;!&ndash;  stroke-width: 3px !important;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;/* Styling to prevent node dragging when interacting with inputs */&ndash;&gt;-->
<!--&lt;!&ndash;.workflow-canvas .vue-flow__node input,&ndash;&gt;-->
<!--&lt;!&ndash;.workflow-canvas .vue-flow__node textarea,&ndash;&gt;-->
<!--&lt;!&ndash;.workflow-canvas .vue-flow__node select,&ndash;&gt;-->
<!--&lt;!&ndash;.workflow-canvas .vue-flow__node button {&ndash;&gt;-->
<!--&lt;!&ndash;  pointer-events: auto;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->

<!--&lt;!&ndash;/* This class allows moving the node */&ndash;&gt;-->
<!--&lt;!&ndash;.node-drag-handle {&ndash;&gt;-->
<!--&lt;!&ndash;  cursor: move;&ndash;&gt;-->
<!--&lt;!&ndash;}&ndash;&gt;-->
<!--&lt;!&ndash;</style>&ndash;&gt;-->


<!--&lt;!&ndash; resources/js/views/Pages/Workflows/Edit.vue &ndash;&gt;-->
<!--<script setup>-->
<!--import { ref, onMounted, computed } from 'vue';-->
<!--import { Head, Link, useForm, router } from '@inertiajs/vue3';-->
<!--import BasePageHeading from '@/components/BasePageHeading.vue';-->
<!--import WorkflowEditor from '@/components/WorkflowEditor.vue'; // Importiere die neue Komponente-->

<!--// Props-->
<!--const props = defineProps({-->
<!--  workflow: {-->
<!--    type: Object,-->
<!--    required: true-->
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

<!--// Form for workflow details-->
<!--const form = useForm({-->
<!--  name: props.workflow.name,-->
<!--  description: props.workflow.description || '',-->
<!--  nodes: props.workflow.nodes || [],-->
<!--  edges: props.workflow.edges || [],-->
<!--  settings: props.workflow.settings || {-->
<!--    autoSave: true,-->
<!--    notifyOnCompletion: true-->
<!--  },-->
<!--  is_active: props.workflow.is_active-->
<!--});-->

<!--// Debugging - check if prompts are received-->
<!--console.log('Edit.vue received prompts:', props.prompts);-->
<!--console.log('Edit.vue received workflow:', props.workflow);-->

<!--// Submit the workflow-->
<!--const submit = () => {-->
<!--  try {-->
<!--    // Submit the form with current data-->
<!--    form.put(route('workflows.update', props.workflow.id), {-->
<!--      onSuccess: () => {-->
<!--        console.log("Workflow updated successfully");-->
<!--      },-->
<!--      onError: (errors) => {-->
<!--        console.error("Error updating workflow:", errors);-->
<!--      }-->
<!--    });-->
<!--  } catch (error) {-->
<!--    console.error("Error in submit function:", error);-->
<!--  }-->
<!--};-->

<!--// Update nodes and edges from editor component-->
<!--const updateNodes = (nodes) => {-->
<!--  form.nodes = nodes;-->
<!--};-->

<!--const updateEdges = (edges) => {-->
<!--  form.edges = edges;-->
<!--};-->

<!--// Cancel editing and return to workflow list-->
<!--const cancelEdit = () => {-->
<!--  router.get(route('workflows.index'));-->
<!--};-->

<!--// Check if the workflow is valid-->
<!--const isValid = computed(() => {-->
<!--  return form.name.trim() !== '' && form.nodes.length > 0;-->
<!--});-->
<!--</script>-->

<!--<template>-->
<!--  <Head title="Edit Workflow" />-->

<!--  <BasePageHeading :title="`Edit: ${workflow.name}`" subtitle="Modify your workflow">-->
<!--    <template #extra>-->
<!--      <nav class="breadcrumb push">-->
<!--        <Link :href="route('dashboard')" class="breadcrumb-item">-->
<!--          <i class="fa fa-home"></i>-->
<!--        </Link>-->
<!--        <Link :href="route('workflows.index')" class="breadcrumb-item">-->
<!--          Workflows-->
<!--        </Link>-->
<!--        <Link :href="route('workflows.show', workflow.id)" class="breadcrumb-item">-->
<!--          {{ workflow.name }}-->
<!--        </Link>-->
<!--        <span class="breadcrumb-item active">Edit</span>-->
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
<!--            <div class="row">-->
<!--              <div class="col-md-6">-->
<!--                <div class="mb-4">-->
<!--                  <div class="form-check form-switch">-->
<!--                    <input class="form-check-input" type="checkbox" id="workflow-active" v-model="form.is_active">-->
<!--                    <label class="form-check-label" for="workflow-active">-->
<!--                      Workflow is active-->
<!--                    </label>-->
<!--                  </div>-->
<!--                  <div class="fs-sm text-muted mt-1">-->
<!--                    When active, this workflow can be executed. Inactive workflows cannot be run.-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->

<!--      &lt;!&ndash; Workflow Builder Block &ndash;&gt;-->
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
<!--            &lt;!&ndash; Workflow Editor Component &ndash;&gt;-->
<!--            <WorkflowEditor-->
<!--              :initialNodes="workflow.nodes"-->
<!--              :initialEdges="workflow.edges"-->
<!--              :prompts="prompts"-->
<!--              :apiKeys="apiKeys"-->
<!--              @update:nodes="updateNodes"-->
<!--              @update:edges="updateEdges"-->
<!--            />-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->

<!--    &lt;!&ndash; Action Buttons &ndash;&gt;-->
<!--    <div class="d-flex justify-content-between mb-4">-->
<!--      <button-->
<!--        type="button"-->
<!--        class="btn btn-alt-secondary"-->
<!--        @click="cancelEdit"-->
<!--      >-->
<!--        <i class="fa fa-arrow-left opacity-50 me-1"></i> Cancel-->
<!--      </button>-->

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


<!-- resources/js/views/Pages/Workflows/Edit.vue -->
<script setup>
import { ref, onMounted, computed } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import BasePageHeading from '@/components/BasePageHeading.vue';
import WorkflowEditor from '../../../Components/WorkflowEditor.vue'; // Pfad anpassen

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

// Debugging - check if prompts are received
console.log('Edit.vue received prompts:', props.prompts);
console.log('Edit.vue received workflow:', props.workflow);

// Submit the workflow
const submit = () => {
  try {
    // Submit the form with current data
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

// Update nodes and edges from editor component
const updateNodes = (nodes) => {
  form.nodes = nodes;
};

const updateEdges = (edges) => {
  form.edges = edges;
};

// Cancel editing and return to workflow list
const cancelEdit = () => {
  router.get(route('workflows.index'));
};

// Update settings from editor component
const updateSetting = ({ key, value }) => {
  if (form.settings) {
    form.settings[key] = value;
  }
};

// Check if the workflow is valid
const isValid = computed(() => {
  return form.name.trim() !== '' && form.nodes.length > 0;
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

      <!-- Workflow Builder Block -->
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
            <!-- Workflow Editor Component -->
            <WorkflowEditor
              :initialNodes="workflow.nodes"
              :initialEdges="workflow.edges"
              :prompts="prompts"
              :apiKeys="apiKeys"
              @update:nodes="updateNodes"
              @update:edges="updateEdges"
              @update:setting="updateSetting"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between mb-4">
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
