<!--&lt;!&ndash;&lt;!&ndash;<script setup>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { ref, reactive, onMounted, computed, nextTick } from 'vue';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { Head, Link, useForm } from '@inertiajs/vue3';&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Import Vue Flow&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { VueFlow } from '@vue-flow/core';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { Background } from '@vue-flow/background';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { Controls } from '@vue-flow/controls';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { MiniMap } from '@vue-flow/minimap';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { Panel } from '@vue-flow/core';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { useVueFlow } from '@vue-flow/core';&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;import { v4 as uuidv4 } from 'uuid';&ndash;&gt;&ndash;&gt;-->

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
<!--&lt;!&ndash;&lt;!&ndash;  name: '',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  description: '',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  nodes: [],&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  edges: [],&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  settings: {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    autoSave: true,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    notifyOnCompletion: true&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Node types registration&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const nodeTypes = {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  promptNode: PromptNode,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  conditionNode: ConditionNode,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  inputNode: InputNode,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  outputNode: OutputNode,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  apiNode: ApiNode,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  transformNode: TransformNode&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Use Vue Flow instance but don't immediately destructure it&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const vueFlow = useVueFlow();&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Create local reactive state that we can sync with Vue Flow&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const flowNodes = ref([]);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const flowEdges = ref([]);&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Sidebar state&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const activePanel = ref('nodes');&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const initialNodesCreated = ref(false);&ndash;&gt;&ndash;&gt;-->

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

<!--&lt;!&ndash;&lt;!&ndash;// Create initial nodes&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const createInitialNodes = () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  if (initialNodesCreated.value) return;&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  const inputNodeId = `node-${uuidv4()}`;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  const outputNodeId = `node-${uuidv4()}`;&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  const initialNodes = [&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      id: inputNodeId,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      type: 'inputNode',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      position: { x: 250, y: 100 },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      data: {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        label: 'Input',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        variable: 'input',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        default_value: '',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        type: 'input'&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      id: outputNodeId,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      type: 'outputNode',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      position: { x: 250, y: 300 },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      data: {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        label: 'Output',&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        variables: ['result'],&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        type: 'output'&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  ];&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  // Set the initial nodes&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  updateNodes(initialNodes);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  initialNodesCreated.value = true;&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  // Update form with initial nodes&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  form.nodes = initialNodes.map(node => ({&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    id: node.id,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    type: node.data.type,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    position: node.position,&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    data: node.data&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  }));&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;};&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;// Set default nodes for new workflow&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;onMounted(() => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  // Use nextTick to ensure Vue Flow is mounted before adding nodes&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  nextTick(() => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    createInitialNodes();&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  });&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->

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
<!--&lt;!&ndash;&lt;!&ndash;    form.post(route('workflows.store'), {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      onSuccess: () => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        console.log("Workflow saved successfully");&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      },&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      onError: (errors) => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        console.error("Error saving workflow:", errors);&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      }&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    });&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  } catch (error) {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    console.error("Error in submit function:", error);&ndash;&gt;&ndash;&gt;-->
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

<!--&lt;!&ndash;&lt;!&ndash;// Check if the workflow is valid&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;const isValid = computed(() => {&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  return form.name.trim() !== '' && flowNodes.value.length > 0;&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;});&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;</script>&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;<template>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;  <Head title="Create Workflow" />&ndash;&gt;&ndash;&gt;-->

<!--&lt;!&ndash;&lt;!&ndash;  <BasePageHeading title="Create Workflow" subtitle="Build your AI automation flow">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;    <template #extra>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      <nav class="breadcrumb push">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <Link :href="route('dashboard')" class="breadcrumb-item">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <i class="fa fa-home"></i>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </Link>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <Link :href="route('workflows.index')" class="breadcrumb-item">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          Workflows&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        </Link>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <span class="breadcrumb-item active">Create</span>&ndash;&gt;&ndash;&gt;-->
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
<!--&lt;!&ndash;&lt;!&ndash;                class="btn btn-sm btn-alt-primary"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                @click="submit"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                :disabled="!isValid || form.processing"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                <i class="fa fa-fw fa-save opacity-50 me-1"></i> Save Workflow&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;              </button>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;          <div class="block-content p-0">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <div class="workflow-builder" style="height: 600px; position: relative;">&ndash;&gt;&ndash;&gt;-->
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
<!--&lt;!&ndash;&lt;!&ndash;                    <li>Input nodes provide variables to your workflow</li>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <li>Output nodes define the workflow result</li>&ndash;&gt;&ndash;&gt;-->
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
<!--&lt;!&ndash;&lt;!&ndash;                  class="workflow-canvas"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <Background pattern-color="#aaa" gap="24" />&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <MiniMap height="120" width="240" />&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                  <Controls />&ndash;&gt;&ndash;&gt;-->

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
<!--&lt;!&ndash;&lt;!&ndash;    <div class="d-flex justify-content-between">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      <Link&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        :href="route('workflows.index')"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        class="btn btn-alt-secondary"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      >&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;        <i class="fa fa-arrow-left opacity-50 me-1"></i> Cancel&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;      </Link>&ndash;&gt;&ndash;&gt;-->

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
<!--&lt;!&ndash;&lt;!&ndash;</style>&ndash;&gt;&ndash;&gt;-->


<!--&lt;!&ndash;<script setup>&ndash;&gt;-->
<!--&lt;!&ndash;import { ref, reactive, onMounted, computed, nextTick, onUnmounted } from 'vue';&ndash;&gt;-->
<!--&lt;!&ndash;import { Head, Link, useForm } from '@inertiajs/vue3';&ndash;&gt;-->

<!--&lt;!&ndash;// Import Vue Flow&ndash;&gt;-->
<!--&lt;!&ndash;import { VueFlow } from '@vue-flow/core';&ndash;&gt;-->
<!--&lt;!&ndash;import { Background } from '@vue-flow/background';&ndash;&gt;-->
<!--&lt;!&ndash;import { Controls } from '@vue-flow/controls';&ndash;&gt;-->
<!--&lt;!&ndash;import { MiniMap } from '@vue-flow/minimap';&ndash;&gt;-->
<!--&lt;!&ndash;import { Panel } from '@vue-flow/core';&ndash;&gt;-->
<!--&lt;!&ndash;import { useVueFlow } from '@vue-flow/core';&ndash;&gt;-->
<!--&lt;!&ndash;import { v4 as uuidv4 } from 'uuid';&ndash;&gt;-->

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
<!--&lt;!&ndash;  name: '',&ndash;&gt;-->
<!--&lt;!&ndash;  description: '',&ndash;&gt;-->
<!--&lt;!&ndash;  nodes: [],&ndash;&gt;-->
<!--&lt;!&ndash;  edges: [],&ndash;&gt;-->
<!--&lt;!&ndash;  settings: {&ndash;&gt;-->
<!--&lt;!&ndash;    autoSave: true,&ndash;&gt;-->
<!--&lt;!&ndash;    notifyOnCompletion: true&ndash;&gt;-->
<!--&lt;!&ndash;  }&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Node types registration&ndash;&gt;-->
<!--&lt;!&ndash;const nodeTypes = {&ndash;&gt;-->
<!--&lt;!&ndash;  promptNode: PromptNode,&ndash;&gt;-->
<!--&lt;!&ndash;  conditionNode: ConditionNode,&ndash;&gt;-->
<!--&lt;!&ndash;  inputNode: InputNode,&ndash;&gt;-->
<!--&lt;!&ndash;  outputNode: OutputNode,&ndash;&gt;-->
<!--&lt;!&ndash;  apiNode: ApiNode,&ndash;&gt;-->
<!--&lt;!&ndash;  transformNode: TransformNode&ndash;&gt;-->
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

<!--&lt;!&ndash;// Create initial nodes&ndash;&gt;-->
<!--&lt;!&ndash;const createInitialNodes = () => {&ndash;&gt;-->
<!--&lt;!&ndash;  if (initialNodesCreated.value) return;&ndash;&gt;-->

<!--&lt;!&ndash;  const inputNodeId = `node-${uuidv4()}`;&ndash;&gt;-->
<!--&lt;!&ndash;  const outputNodeId = `node-${uuidv4()}`;&ndash;&gt;-->

<!--&lt;!&ndash;  const initialNodes = [&ndash;&gt;-->
<!--&lt;!&ndash;    {&ndash;&gt;-->
<!--&lt;!&ndash;      id: inputNodeId,&ndash;&gt;-->
<!--&lt;!&ndash;      type: 'inputNode',&ndash;&gt;-->
<!--&lt;!&ndash;      position: { x: 250, y: 100 },&ndash;&gt;-->
<!--&lt;!&ndash;      data: {&ndash;&gt;-->
<!--&lt;!&ndash;        label: 'Input',&ndash;&gt;-->
<!--&lt;!&ndash;        variable: 'input',&ndash;&gt;-->
<!--&lt;!&ndash;        default_value: '',&ndash;&gt;-->
<!--&lt;!&ndash;        type: 'input'&ndash;&gt;-->
<!--&lt;!&ndash;      }&ndash;&gt;-->
<!--&lt;!&ndash;    },&ndash;&gt;-->
<!--&lt;!&ndash;    {&ndash;&gt;-->
<!--&lt;!&ndash;      id: outputNodeId,&ndash;&gt;-->
<!--&lt;!&ndash;      type: 'outputNode',&ndash;&gt;-->
<!--&lt;!&ndash;      position: { x: 250, y: 300 },&ndash;&gt;-->
<!--&lt;!&ndash;      data: {&ndash;&gt;-->
<!--&lt;!&ndash;        label: 'Output',&ndash;&gt;-->
<!--&lt;!&ndash;        variables: ['result'],&ndash;&gt;-->
<!--&lt;!&ndash;        type: 'output'&ndash;&gt;-->
<!--&lt;!&ndash;      }&ndash;&gt;-->
<!--&lt;!&ndash;    }&ndash;&gt;-->
<!--&lt;!&ndash;  ];&ndash;&gt;-->

<!--&lt;!&ndash;  // Set the initial nodes&ndash;&gt;-->
<!--&lt;!&ndash;  updateNodes(initialNodes);&ndash;&gt;-->
<!--&lt;!&ndash;  initialNodesCreated.value = true;&ndash;&gt;-->

<!--&lt;!&ndash;  // Update form with initial nodes&ndash;&gt;-->
<!--&lt;!&ndash;  form.nodes = initialNodes.map(node => ({&ndash;&gt;-->
<!--&lt;!&ndash;    id: node.id,&ndash;&gt;-->
<!--&lt;!&ndash;    type: node.data.type,&ndash;&gt;-->
<!--&lt;!&ndash;    position: node.position,&ndash;&gt;-->
<!--&lt;!&ndash;    data: node.data&ndash;&gt;-->
<!--&lt;!&ndash;  }));&ndash;&gt;-->
<!--&lt;!&ndash;};&ndash;&gt;-->

<!--&lt;!&ndash;// Set default nodes for new workflow&ndash;&gt;-->
<!--&lt;!&ndash;onMounted(() => {&ndash;&gt;-->
<!--&lt;!&ndash;  // Use nextTick to ensure Vue Flow is mounted before adding nodes&ndash;&gt;-->
<!--&lt;!&ndash;  nextTick(() => {&ndash;&gt;-->
<!--&lt;!&ndash;    createInitialNodes();&ndash;&gt;-->
<!--&lt;!&ndash;  });&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

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
<!--&lt;!&ndash;    form.post(route('workflows.store'), {&ndash;&gt;-->
<!--&lt;!&ndash;      onSuccess: () => {&ndash;&gt;-->
<!--&lt;!&ndash;        console.log("Workflow saved successfully");&ndash;&gt;-->
<!--&lt;!&ndash;      },&ndash;&gt;-->
<!--&lt;!&ndash;      onError: (errors) => {&ndash;&gt;-->
<!--&lt;!&ndash;        console.error("Error saving workflow:", errors);&ndash;&gt;-->
<!--&lt;!&ndash;      }&ndash;&gt;-->
<!--&lt;!&ndash;    });&ndash;&gt;-->
<!--&lt;!&ndash;  } catch (error) {&ndash;&gt;-->
<!--&lt;!&ndash;    console.error("Error in submit function:", error);&ndash;&gt;-->
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
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Clean up event listener&ndash;&gt;-->
<!--&lt;!&ndash;onUnmounted(() => {&ndash;&gt;-->
<!--&lt;!&ndash;  document.removeEventListener('fullscreenchange', handleFullscreenChange);&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->

<!--&lt;!&ndash;// Handle keyboard events for node/edge deletion&ndash;&gt;-->
<!--&lt;!&ndash;const handleKeyDown = (event) => {&ndash;&gt;-->
<!--&lt;!&ndash;  if (event.key === 'Delete' || event.key === 'Backspace') {&ndash;&gt;-->
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

<!--&lt;!&ndash;// Check if the workflow is valid&ndash;&gt;-->
<!--&lt;!&ndash;const isValid = computed(() => {&ndash;&gt;-->
<!--&lt;!&ndash;  return form.name.trim() !== '' && flowNodes.value.length > 0;&ndash;&gt;-->
<!--&lt;!&ndash;});&ndash;&gt;-->
<!--&lt;!&ndash;</script>&ndash;&gt;-->

<!--&lt;!&ndash;<template>&ndash;&gt;-->
<!--&lt;!&ndash;  <Head title="Create Workflow" />&ndash;&gt;-->

<!--&lt;!&ndash;  <BasePageHeading title="Create Workflow" subtitle="Build your AI automation flow">&ndash;&gt;-->
<!--&lt;!&ndash;    <template #extra>&ndash;&gt;-->
<!--&lt;!&ndash;      <nav class="breadcrumb push">&ndash;&gt;-->
<!--&lt;!&ndash;        <Link :href="route('dashboard')" class="breadcrumb-item">&ndash;&gt;-->
<!--&lt;!&ndash;          <i class="fa fa-home"></i>&ndash;&gt;-->
<!--&lt;!&ndash;        </Link>&ndash;&gt;-->
<!--&lt;!&ndash;        <Link :href="route('workflows.index')" class="breadcrumb-item">&ndash;&gt;-->
<!--&lt;!&ndash;          Workflows&ndash;&gt;-->
<!--&lt;!&ndash;        </Link>&ndash;&gt;-->
<!--&lt;!&ndash;        <span class="breadcrumb-item active">Create</span>&ndash;&gt;-->
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
<!--&lt;!&ndash;&lt;!&ndash;                  class="workflow-canvas"&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                >&ndash;&gt;&ndash;&gt;-->
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
<!--&lt;!&ndash;                  :deleteKeyCode="['Backspace', 'Delete']"&ndash;&gt;-->
<!--&lt;!&ndash;                  :nodeDragHandleClassName="'node-drag-handle'"&ndash;&gt;-->
<!--&lt;!&ndash;                  class="workflow-canvas"&ndash;&gt;-->
<!--&lt;!&ndash;                >&ndash;&gt;-->

<!--&lt;!&ndash;                <Background pattern-color="#aaa" gap="24" />&ndash;&gt;-->
<!--&lt;!&ndash;                  <MiniMap&ndash;&gt;-->
<!--&lt;!&ndash;                    height="120"&ndash;&gt;-->
<!--&lt;!&ndash;                    width="240"&ndash;&gt;-->
<!--&lt;!&ndash;                    nodeStrokeColor="#111"&ndash;&gt;-->
<!--&lt;!&ndash;                    nodeColor="#ddd"&ndash;&gt;-->
<!--&lt;!&ndash;                    nodeBorderRadius="3"&ndash;&gt;-->
<!--&lt;!&ndash;                  />&ndash;&gt;-->
<!--&lt;!&ndash;                  <Controls&ndash;&gt;-->
<!--&lt;!&ndash;                    showZoom="true"&ndash;&gt;-->
<!--&lt;!&ndash;                    showFitView="true"&ndash;&gt;-->
<!--&lt;!&ndash;                    showInteractive="true"&ndash;&gt;-->
<!--&lt;!&ndash;                    fitViewPadding="0.2"&ndash;&gt;-->
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
<!--&lt;!&ndash;      <Link&ndash;&gt;-->
<!--&lt;!&ndash;        :href="route('workflows.index')"&ndash;&gt;-->
<!--&lt;!&ndash;        class="btn btn-alt-secondary"&ndash;&gt;-->
<!--&lt;!&ndash;      >&ndash;&gt;-->
<!--&lt;!&ndash;        <i class="fa fa-arrow-left opacity-50 me-1"></i> Cancel&ndash;&gt;-->
<!--&lt;!&ndash;      </Link>&ndash;&gt;-->

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
<!--&lt;!&ndash;</style>&ndash;&gt;-->


<!--<script setup>-->
<!--import { ref, reactive, onMounted, computed, nextTick, onUnmounted, markRaw } from 'vue';-->
<!--import { Head, Link, useForm } from '@inertiajs/vue3';-->

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

<!--// Import custom node types - adjust paths as needed-->
<!--import PromptNode from '@/views/Pages/Workflows/Nodes/PromptNode.vue';-->
<!--import ConditionNode from '@/views/Pages/Workflows/Nodes/ConditionNode.vue';-->
<!--import InputNode from '@/views/Pages/Workflows/Nodes/InputNode.vue';-->
<!--import OutputNode from '@/views/Pages/Workflows/Nodes/OutputNode.vue';-->
<!--import ApiNode from '@/views/Pages/Workflows/Nodes/ApiNode.vue';-->
<!--import TransformNode from '@/views/Pages/Workflows/Nodes/TransformNode.vue';-->
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

<!--// Node types registration - use markRaw to prevent reactivity warnings-->
<!--const nodeTypes = {-->
<!--  promptNode: markRaw(PromptNode),-->
<!--  conditionNode: markRaw(ConditionNode),-->
<!--  inputNode: markRaw(InputNode),-->
<!--  outputNode: markRaw(OutputNode),-->
<!--  apiNode: markRaw(ApiNode),-->
<!--  transformNode: markRaw(TransformNode)-->
<!--};-->

<!--// Use Vue Flow instance but don't immediately destructure it-->
<!--const vueFlow = useVueFlow();-->

<!--// Create local reactive state that we can sync with Vue Flow-->
<!--const flowNodes = ref([]);-->
<!--const flowEdges = ref([]);-->

<!--// Sidebar state-->
<!--const activePanel = ref('nodes');-->
<!--const initialNodesCreated = ref(false);-->
<!--const isFullscreen = ref(false);-->
<!--const workflowBuilder = ref(null);-->

<!--// Method to safely update nodes-->
<!--const updateNodes = (newNodes) => {-->
<!--  flowNodes.value = newNodes;-->
<!--  vueFlow.setNodes(newNodes);-->
<!--};-->

<!--// Method to safely update edges-->
<!--const updateEdges = (newEdges) => {-->
<!--  flowEdges.value = newEdges;-->
<!--  vueFlow.setEdges(newEdges);-->
<!--};-->

<!--// Create a new node-->
<!--const createNode = (type, data = {}) => {-->
<!--  const id = `node-${uuidv4()}`;-->

<!--  // Add prompts data for PromptNode-->
<!--  if (type === 'prompt') {-->
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
<!--const onConnect = (params) => {-->
<!--  const newEdge = {-->
<!--    id: `edge-${uuidv4()}`,-->
<!--    source: params.source,-->
<!--    target: params.target,-->
<!--    animated: true-->
<!--  };-->

<!--  updateEdges([...flowEdges.value, newEdge]);-->
<!--};-->

<!--// Track changes to nodes and edges-->
<!--const onNodesChange = (changes) => {-->
<!--  const updatedNodes = vueFlow.applyNodeChanges(changes, flowNodes.value);-->
<!--  flowNodes.value = updatedNodes;-->

<!--  // Update form data when nodes change-->
<!--  form.nodes = updatedNodes.map(node => ({-->
<!--    id: node.id,-->
<!--    type: node.data?.type || '',-->
<!--    position: node.position || { x: 0, y: 0 },-->
<!--    data: node.data || {}-->
<!--  }));-->
<!--};-->

<!--const onEdgesChange = (changes) => {-->
<!--  const updatedEdges = vueFlow.applyEdgeChanges(changes, flowEdges.value);-->
<!--  flowEdges.value = updatedEdges;-->

<!--  // Update form data when edges change-->
<!--  form.edges = updatedEdges;-->
<!--};-->

<!--// Create initial nodes-->
<!--const createInitialNodes = () => {-->
<!--  if (initialNodesCreated.value) return;-->

<!--  const inputNodeId = `node-${uuidv4()}`;-->
<!--  const outputNodeId = `node-${uuidv4()}`;-->

<!--  const initialNodes = [-->
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
<!--  ];-->

<!--  // Set the initial nodes-->
<!--  updateNodes(initialNodes);-->
<!--  initialNodesCreated.value = true;-->

<!--  // Update form with initial nodes-->
<!--  form.nodes = initialNodes.map(node => ({-->
<!--    id: node.id,-->
<!--    type: node.data.type,-->
<!--    position: node.position,-->
<!--    data: node.data-->
<!--  }));-->
<!--};-->

<!--// Set default nodes for new workflow-->
<!--onMounted(() => {-->
<!--  // Use nextTick to ensure Vue Flow is mounted before adding nodes-->
<!--  nextTick(() => {-->
<!--    createInitialNodes();-->
<!--  });-->
<!--});-->

<!--// Submit the workflow-->
<!--const submit = () => {-->
<!--  try {-->
<!--    // Update form data with latest nodes and edges-->
<!--    form.nodes = flowNodes.value.map(node => ({-->
<!--      id: node.id,-->
<!--      type: node.data?.type || '',-->
<!--      position: node.position || { x: 0, y: 0 },-->
<!--      data: node.data || {}-->
<!--    }));-->

<!--    form.edges = flowEdges.value;-->

<!--    // Submit the form-->
<!--    form.post(route('workflows.store'), {-->
<!--      onSuccess: () => {-->
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

<!--// Add event listener for fullscreen change-->
<!--onMounted(() => {-->
<!--  document.addEventListener('fullscreenchange', handleFullscreenChange);-->
<!--});-->

<!--// Clean up event listener-->
<!--onUnmounted(() => {-->
<!--  document.removeEventListener('fullscreenchange', handleFullscreenChange);-->
<!--});-->

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

<!--// Check if the workflow is valid-->
<!--const isValid = computed(() => {-->
<!--  return form.name.trim() !== '' && flowNodes.value.length > 0;-->
<!--});-->

<!--console.log('Prompts aus Props:', props.prompts);-->
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
<!--                class="btn btn-sm btn-alt-secondary me-1"-->
<!--                @click="toggleFullscreen"-->
<!--                title="Toggle Fullscreen"-->
<!--              >-->
<!--                <i :class="['fa', isFullscreen ? 'fa-compress' : 'fa-expand']"></i>-->
<!--              </button>-->
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
<!--            <div-->
<!--              class="workflow-builder"-->
<!--              :class="{'fullscreen': isFullscreen}"-->
<!--              ref="workflowBuilder"-->
<!--              style="height: 600px; position: relative;"-->
<!--            >-->
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
<!--                    <li>Drag nodes to move them around the canvas</li>-->
<!--                    <li>Select an edge and press Delete to remove it</li>-->
<!--                    <li>Select a node and press Delete to remove it</li>-->
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
<!--                  v-model:nodes="flowNodes"-->
<!--                  v-model:edges="flowEdges"-->
<!--                  :nodeTypes="nodeTypes"-->
<!--                  :default-zoom="1.5"-->
<!--                  :min-zoom="0.2"-->
<!--                  :max-zoom="4"-->
<!--                  @nodesChange="onNodesChange"-->
<!--                  @edgesChange="onEdgesChange"-->
<!--                  @connect="onConnect"-->
<!--                  @keydown="handleKeyDown"-->
<!--                  :deleteKeyCode="['Delete']"-->
<!--                  :nodeDragHandleClassName="'node-drag-handle'"-->
<!--                  class="workflow-canvas"-->
<!--                >-->
<!--                  <Background pattern-color="#aaa" gap="24" />-->
<!--                  <MiniMap-->
<!--                    height="120"-->
<!--                    width="240"-->
<!--                    nodeStrokeColor="#111"-->
<!--                    nodeColor="#ddd"-->
<!--                    nodeBorderRadius="3"-->
<!--                  />-->
<!--                  <Controls-->
<!--                    showZoom="true"-->
<!--                    showFitView="true"-->
<!--                    showInteractive="true"-->
<!--                    fitViewPadding="0.2"-->
<!--                  />-->

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
<!--                      <button-->
<!--                        type="button"-->
<!--                        class="btn btn-sm btn-alt-secondary"-->
<!--                        title="Delete Selected"-->
<!--                        @click="deleteSelected"-->
<!--                      >-->
<!--                        <i class="fa fa-trash"></i>-->
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
<!--    <div class="d-flex justify-content-between mb-4">-->
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
<!--.vue-flow__controls {-->
<!--  box-shadow: 0 0 2px 1px rgba(0, 0, 0, 0.08);-->
<!--}-->

<!--.vue-flow__controls-button {-->
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

<!--.vue-flow__controls-button svg {-->
<!--  fill: #333;-->
<!--  height: 14px;-->
<!--  width: 14px;-->
<!--}-->

<!--/* MiniMap styling */-->
<!--.vue-flow__minimap {-->
<!--  background-color: #f8f9fa;-->
<!--  border: 1px solid #ddd;-->
<!--}-->

<!--/* Selected elements styling */-->
<!--.vue-flow__node.selected {-->
<!--  box-shadow: 0 0 0 2px #5c80d1 !important;-->
<!--}-->

<!--.vue-flow__edge.selected .vue-flow__edge-path {-->
<!--  stroke: #5c80d1 !important;-->
<!--  stroke-width: 3px !important;-->
<!--}-->

<!--/* Styling to prevent node dragging when interacting with inputs */-->
<!--.workflow-canvas .vue-flow__node input,-->
<!--.workflow-canvas .vue-flow__node textarea,-->
<!--.workflow-canvas .vue-flow__node select,-->
<!--.workflow-canvas .vue-flow__node button {-->
<!--  pointer-events: auto;-->
<!--}-->

<!--/* This class allows moving the node */-->
<!--.node-drag-handle {-->
<!--  cursor: move;-->
<!--}-->
<!--</style>-->
<!-- resources/js/views/Pages/Workflows/Create.vue -->
<script setup>
import { ref, onMounted, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import BasePageHeading from '@/components/BasePageHeading.vue';
import WorkflowEditor from "@/components/WorkflowEditor.vue";

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

// Debugging - check if prompts are received
console.log('Create.vue received prompts:', props.prompts);

// Submit the workflow
const submit = () => {
  try {
    // Submit the form with the current data
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

// Update nodes and edges from editor component
const updateNodes = (nodes) => {
  form.nodes = nodes;
};

const updateEdges = (edges) => {
  form.edges = edges;
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
