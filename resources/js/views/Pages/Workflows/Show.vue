<script setup>
import { ref, onMounted, computed, markRaw } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { VueFlow } from "@vue-flow/core";
import { Background } from "@vue-flow/background";
import { Controls } from "@vue-flow/controls";
import { MiniMap } from "@vue-flow/minimap";
import { useVueFlow } from "@vue-flow/core";
import { v4 as uuidv4 } from "uuid";

// Ensure CSS is imported
import "@vue-flow/core/dist/style.css";
import "@vue-flow/core/dist/theme-default.css";

// Import node components
import PromptNode from "@/views/Pages/Workflows/Nodes/PromptNode.vue";
import ConditionNode from "@/views/Pages/Workflows/Nodes/ConditionNode.vue";
import InputNode from "@/views/Pages/Workflows/Nodes/InputNode.vue";
import OutputNode from "@/views/Pages/Workflows/Nodes/OutputNode.vue";
import ApiNode from "@/views/Pages/Workflows/Nodes/ApiNode.vue";
import TransformNode from "@/views/Pages/Workflows/Nodes/TransformNode.vue";

// Props
const props = defineProps({
  workflow: {
    type: Object,
    required: true,
  },
  recentExecutions: {
    type: Array,
    default: () => [],
  },
});

// Vue Flow instance
const vueFlow = useVueFlow();

// Flow elements
const flowNodes = ref([]);
const flowEdges = ref([]);

// Execution inputs
const executionInput = ref({});
const isExecuting = ref(false);

// Execute modal reference
const executeModal = ref(null);

// Node types registration - use markRaw to prevent reactivity warnings
const nodeTypes = {
  promptNode: markRaw(PromptNode),
  conditionNode: markRaw(ConditionNode),
  inputNode: markRaw(InputNode),
  outputNode: markRaw(OutputNode),
  apiNode: markRaw(ApiNode),
  transformNode: markRaw(TransformNode),
};

// Format date
const formatDate = (dateString) => {
  if (!dateString) return "Never";
  const date = new Date(dateString);
  return date.toLocaleString();
};

// Get status class
const getStatusClass = (status) => {
  switch (status) {
    case "completed":
      return "bg-success";
    case "failed":
      return "bg-danger";
    case "running":
      return "bg-warning";
    case "pending":
      return "bg-info";
    default:
      return "bg-secondary";
  }
};

// Show execution modal
const showExecuteModal = () => {
  // Extract input variables from workflow nodes
  const inputNodes = props.workflow.nodes.filter(
    (node) => node.type === "input",
  );
  const inputData = {};

  inputNodes.forEach((node) => {
    if (node.data && node.data.variable) {
      inputData[node.data.variable] = node.data.default_value || "";
    }
  });

  executionInput.value = inputData;
  executeModal.value.show();
};

// Execute workflow
const executeWorkflow = () => {
  isExecuting.value = true;

  router.post(
    route("workflows.execute", props.workflow.id),
    {
      input_data: executionInput.value,
    },
    {
      onSuccess: (page) => {
        executeModal.value.hide();
        isExecuting.value = false;

        // If there's an execution_id in the response, redirect to execution status
        if (page.props.execution_id) {
          router.get(route("executions.status", page.props.execution_id));
        }
      },
      onError: () => {
        isExecuting.value = false;
      },
    },
  );
};

// Initialize flow elements
const initializeFlow = () => {
  // Set nodes and edges from workflow
  const nodes = props.workflow.nodes.map((node) => ({
    ...node,
    // Make sure node types match the registered components
    type: `${node.type}Node`,
    // Make sure node is not selected
    selected: false,
    // Make node not draggable in view mode
    draggable: false,
  }));

  flowNodes.value = nodes;
  flowEdges.value = props.workflow.edges;

  // Fit view to show all nodes
  setTimeout(() => {
    vueFlow.fitView();
  }, 100);
};

// Has input variables
const hasInputVariables = computed(() => {
  const inputNodes = props.workflow.nodes.filter(
    (node) => node.type === "input",
  );
  return inputNodes.length > 0;
});

// // Zoom controls
// const handleZoomIn = () => {
//   vueFlow.zoomIn();
// };
//
// const handleZoomOut = () => {
//   vueFlow.zoomOut();
// };
//
// const handleFitView = () => {
//   vueFlow.fitView();
// };

const handleZoomIn = () => {
  vueFlow.zoomIn?.();
};

const handleZoomOut = () => {
  vueFlow.zoomOut?.();
};

const handleFitView = () => {
  vueFlow.fitView?.();
};

// Initialize on component mount
onMounted(() => {
  executeModal.value = new bootstrap.Modal(
    document.getElementById("modal-execute-workflow"),
  );
  initializeFlow();
});
</script>

<template>
  <Head :title="workflow.name" />

  <BasePageHeading :title="workflow.name" subtitle="Workflow Details">
    <template #extra>
      <nav class="breadcrumb push">
        <Link :href="route('dashboard')" class="breadcrumb-item">
          <i class="fa fa-home"></i>
        </Link>
        <Link :href="route('workflows.index')" class="breadcrumb-item">
          Workflows
        </Link>
        <span class="breadcrumb-item active">{{ workflow.name }}</span>
      </nav>
    </template>
    <template #actions>
      <div class="btn-group me-1">
        <button
          type="button"
          class="btn btn-sm btn-alt-secondary dropdown-toggle"
          id="dropdown-default-alt-secondary"
          data-bs-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          <i class="fa fa-fw fa-cog"></i>
          <span class="d-none d-sm-inline ms-1">Options</span>
        </button>
        <div
          class="dropdown-menu dropdown-menu-end"
          aria-labelledby="dropdown-default-alt-secondary"
        >
          <Link
            :href="route('workflows.edit', workflow.id)"
            class="dropdown-item"
          >
            <i class="fa fa-fw fa-pencil-alt me-1"></i> Edit Workflow
          </Link>
          <a
            :href="route('workflows.download', { workflow: workflow.id })"
            class="dropdown-item"
            download
          >
            <i class="fa fa-fw fa-download me-1"></i> Export JSON
          </a>
          <div class="dropdown-divider"></div>
          <Link
            :href="route('workflows.destroy', workflow.id)"
            class="dropdown-item text-danger"
            method="delete"
            as="button"
            preserve-scroll
          >
            <i class="fa fa-fw fa-trash me-1"></i> Delete Workflow
          </Link>
        </div>
      </div>
      <button
        @click="showExecuteModal"
        type="button"
        class="btn btn-sm btn-primary"
      >
        <i class="fa fa-play opacity-50 me-1"></i> Execute
      </button>
    </template>
  </BasePageHeading>

  <div class="content">
    <div class="row items-push">
      <!-- Workflow Details -->
      <div class="col-md-6 col-xl-4">
        <BaseBlock title="Details">
          <div class="fs-sm">
            <div class="row mb-2">
              <div class="col-6 fw-semibold text-muted">Status:</div>
              <div class="col-6">
                <span
                  :class="{
                    'text-success': workflow.is_active,
                    'text-danger': !workflow.is_active,
                  }"
                >
                  <i
                    :class="[
                      'fa',
                      workflow.is_active
                        ? 'fa-check-circle'
                        : 'fa-times-circle',
                    ]"
                  ></i>
                  {{ workflow.is_active ? "Active" : "Inactive" }}
                </span>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-6 fw-semibold text-muted">Created:</div>
              <div class="col-6">{{ formatDate(workflow.created_at) }}</div>
            </div>
            <div class="row mb-2">
              <div class="col-6 fw-semibold text-muted">Last Updated:</div>
              <div class="col-6">{{ formatDate(workflow.updated_at) }}</div>
            </div>
            <div class="row mb-2">
              <div class="col-6 fw-semibold text-muted">Last Execution:</div>
              <div class="col-6">
                {{ formatDate(workflow.last_execution_at) }}
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-6 fw-semibold text-muted">Nodes:</div>
              <div class="col-6">{{ workflow.nodes?.length || 0 }}</div>
            </div>
            <div class="row">
              <div class="col-6 fw-semibold text-muted">Connections:</div>
              <div class="col-6">{{ workflow.edges?.length || 0 }}</div>
            </div>
          </div>
        </BaseBlock>

        <BaseBlock title="Description">
          <div v-if="workflow.description" class="fs-sm pb-4">
            {{ workflow.description }}
          </div>
          <div v-else class="fs-sm text-muted fst-italic pb-4">
            No description provided
          </div>
        </BaseBlock>

        <BaseBlock v-if="hasInputVariables" title="Input Variables">
          <div class="fs-sm">
            <div
              v-for="node in workflow.nodes.filter((n) => n.type === 'input')"
              :key="node.id"
              class="mb-3"
            >
              <div class="fw-semibold">{{ node.data.variable }}</div>
              <div class="text-muted">
                <small>Default: {{ node.data.default_value || "None" }}</small>
              </div>
            </div>
          </div>
        </BaseBlock>
      </div>

      <!-- Workflow Diagram -->
      <div class="col-md-6 col-xl-8">
        <BaseBlock
          title="Workflow Diagram"
          options-class="bg-body-light"
          content-class="p-0"
        >
          <div class="workflow-diagram" style="height: 600px">
            <VueFlow
              v-model:nodes="flowNodes"
              v-model:edges="flowEdges"
              :nodeTypes="nodeTypes"
              :default-zoom="1.5"
              :min-zoom="0.2"
              :max-zoom="4"
              :panOnDrag="false"
              :selectable="false"
              :zoomOnScroll="true"
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
                </div>
              </Panel>
            </VueFlow>
          </div>
        </BaseBlock>
      </div>
    </div>

    <!-- Recent Executions -->
    <BaseBlock title="Recent Executions">
      <template #options>
        <div class="block-options">
          <button
            type="button"
            class="btn-block-option"
            @click="router.reload({ only: ['recentExecutions'] })"
          >
            <i class="fa fa-sync"></i>
          </button>
        </div>
      </template>

      <!-- Empty State -->
      <div v-if="recentExecutions.length === 0" class="text-center py-4">
        <div class="py-4">
          <i class="fa fa-history fa-4x text-muted mb-3"></i>
          <p class="fs-lg fw-medium text-muted">
            This workflow hasn't been executed yet.
          </p>
          <button @click="showExecuteModal" class="btn btn-hero btn-primary">
            <i class="fa fa-play me-1"></i> Execute Now
          </button>
        </div>
      </div>

      <!-- Executions Table -->
      <div v-else class="table-responsive">
        <table class="table table-bordered table-striped table-vcenter">
          <thead>
            <tr>
              <th style="width: 100px">ID</th>
              <th>Status</th>
              <th class="d-none d-sm-table-cell">Started</th>
              <th class="d-none d-sm-table-cell">Completed</th>
              <th class="d-none d-md-table-cell">Duration</th>
              <th class="text-center" style="width: 100px">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="execution in recentExecutions" :key="execution.id">
              <td>
                <Link
                  :href="route('executions.status', execution.id)"
                  class="fw-semibold"
                >
                  #{{ execution.id }}
                </Link>
              </td>
              <td>
                <span
                  class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill"
                  :class="getStatusClass(execution.status)"
                >
                  {{ execution.status }}
                </span>
              </td>
              <td class="d-none d-sm-table-cell">
                {{ formatDate(execution.started_at) }}
              </td>
              <td class="d-none d-sm-table-cell">
                {{
                  execution.completed_at
                    ? formatDate(execution.completed_at)
                    : "-"
                }}
              </td>
              <td class="d-none d-md-table-cell">
                {{
                  execution.completed_at
                    ? (
                        (new Date(execution.completed_at) -
                          new Date(execution.started_at)) /
                        1000
                      ).toFixed(2) + " s"
                    : "-"
                }}
              </td>
              <td class="text-center">
                <Link
                  :href="route('executions.status', execution.id)"
                  class="btn btn-sm btn-alt-secondary"
                >
                  <i class="fa fa-eye"></i>
                </Link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </BaseBlock>
  </div>

  <!-- Execute Workflow Modal -->
  <div
    class="modal fade"
    id="modal-execute-workflow"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modal-execute-workflow"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="block block-rounded block-themed block-transparent mb-0">
          <div class="block-header bg-primary">
            <h3 class="block-title">Execute Workflow</h3>
            <div class="block-options">
              <button
                type="button"
                class="btn-block-option"
                data-bs-dismiss="modal"
                aria-label="Close"
              >
                <i class="fa fa-fw fa-times"></i>
              </button>
            </div>
          </div>
          <div class="block-content fs-sm">
            <p class="fw-semibold">{{ workflow.name }}</p>

            <!-- Input Variables -->
            <div v-if="Object.keys(executionInput).length > 0">
              <h5 class="h6">Input Variables</h5>
              <div
                v-for="(value, key) in executionInput"
                :key="key"
                class="mb-4"
              >
                <label class="form-label" :for="`input-${key}`">{{
                  key
                }}</label>
                <textarea
                  :id="`input-${key}`"
                  v-model="executionInput[key]"
                  class="form-control"
                  rows="2"
                ></textarea>
              </div>
            </div>
            <div v-else>
              <p class="text-muted">
                This workflow doesn't require any input variables.
              </p>
            </div>
          </div>
          <div class="block-content block-content-full text-end bg-body">
            <button
              type="button"
              class="btn btn-sm btn-alt-secondary me-1"
              data-bs-dismiss="modal"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn btn-sm btn-primary"
              :class="{ disabled: isExecuting }"
              @click="executeWorkflow()"
            >
              <i class="fa fa-fw fa-play opacity-50 me-1"></i> Execute
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END Execute Workflow Modal -->
</template>

<style>
.workflow-canvas .vue-flow__node {
  border-radius: 5px;
  padding: 10px;
  font-size: 12px;
  text-align: center;
  border: 1px solid #ddd;
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

.workflow-canvas .vue-flow__handle {
  width: 8px;
  height: 8px;
}

/* Control panel styles */
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
</style>
