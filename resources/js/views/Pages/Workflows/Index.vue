<script setup>
import { ref, onMounted } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";

// Props definition
const props = defineProps({
  workflows: {
    type: Array,
    required: true,
  },
  recentExecutions: {
    type: Array,
    default: () => [],
  },
});

// Modal controls for delete confirmation
const deleteModal = ref(null);
const workflowToDelete = ref(null);
const deleting = ref(false);

// Modal for workflow execution
const executeModal = ref(null);
const workflowToExecute = ref(null);
const executingWorkflow = ref(false);
const executionInput = ref({});

// Refs for form elements
const exportSelectRef = ref(null);
const importFileRef = ref(null);

// Selected workflow for export
const selectedWorkflowId = ref("");

// Import data
const selectedImportFile = ref(null);
const importFileName = ref("Choose file...");

// Format date to localized format
const formatDate = (dateString) => {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat("default", {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  }).format(date);
};

// Get status class based on execution status
const getStatusClass = (status) => {
  switch (status) {
    case "completed":
      return "bg-success-light text-success";
    case "failed":
      return "bg-danger-light text-danger";
    case "running":
      return "bg-warning-light text-warning";
    case "pending":
      return "bg-info-light text-info";
    default:
      return "bg-secondary";
  }
};

// Confirm deletion with modal
const confirmWorkflowDeletion = (workflow) => {
  workflowToDelete.value = workflow;
  deleteModal.value.show();
};

// Delete the workflow
const deleteWorkflow = () => {
  if (!workflowToDelete.value) return;

  deleting.value = true;

  router.delete(route("workflows.destroy", workflowToDelete.value.id), {
    onSuccess: () => {
      deleteModal.value.hide();
      deleting.value = false;
    },
    onError: () => {
      deleting.value = false;
    },
  });
};

// Open workflow execution modal
const showExecuteModal = (workflow) => {
  workflowToExecute.value = workflow;

  // Extract input variables from workflow nodes
  const inputNodes = workflow.nodes.filter((node) => node.type === "input");
  const inputData = {};

  inputNodes.forEach((node) => {
    if (node.data && node.data.variable) {
      inputData[node.data.variable] = node.data.default_value || "";
    }
  });

  executionInput.value = inputData;
  executeModal.value.show();
};

// Execute the workflow
const executeWorkflow = () => {
  if (!workflowToExecute.value) return;

  executingWorkflow.value = true;

  router.post(
    route("workflows.execute", workflowToExecute.value.id),
    {
      input_data: executionInput.value,
    },
    {
      onSuccess: (page) => {
        executeModal.value.hide();
        executingWorkflow.value = false;

        // If there's an execution_id in the response, redirect to execution status
        if (page.props.execution_id) {
          router.get(route("executions.status", page.props.execution_id));
        }
      },
      onError: () => {
        executingWorkflow.value = false;
      },
    },
  );
};

// Export workflow
const exportWorkflow = () => {
  if (!selectedWorkflowId.value) return;

  // Direkt zur Download-URL navigieren, um Inertia zu umgehen
  window.location.href = route("workflows.export", selectedWorkflowId.value);
};

// Handle file selection for import
const handleFileImportChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    selectedImportFile.value = file;
    importFileName.value = file.name;
  } else {
    selectedImportFile.value = null;
    importFileName.value = "Choose file...";
  }
};

// Import workflow
const importWorkflow = () => {
  if (!selectedImportFile.value) {
    // Hier könnte eine Fehlermeldung angezeigt werden, falls keine Datei ausgewählt wurde
    console.error("No file selected for import.");
    return;
  }

  const reader = new FileReader();
  reader.onload = (e) => {
    try {
      const fileContent = e.target.result;
      // Validieren, ob der Inhalt JSON ist, bevor er gesendet wird (optional, aber empfohlen)
      JSON.parse(fileContent); // Wirft einen Fehler, wenn es kein valides JSON ist

      router.post(
        route("workflows.import"),
        {
          workflow_data: fileContent, // Der Controller erwartet 'workflow_data' als String
        },
        {
          onSuccess: () => {
            // Reset file input after successful import
            if (importFileRef.value) {
              importFileRef.value.value = null;
            }
            selectedImportFile.value = null;
            importFileName.value = "Choose file...";
            // Ggf. Erfolgsmeldung oder Aktualisierung der Workflow-Liste anstoßen
          },
          onError: (errors) => {
            console.error("Error importing workflow:", errors);
            // Ggf. Fehlermeldung anzeigen
          },
        },
      );
    } catch (error) {
      console.error("Invalid JSON file:", error);
      // Hier könnte eine Nutzermeldung für ungültiges JSON angezeigt werden
      alert("The selected file is not a valid JSON file.");
    }
  };
  reader.onerror = (error) => {
    console.error("Error reading file:", error);
    alert("Error reading the selected file.");
  };
  reader.readAsText(selectedImportFile.value);
};

// Initialize modals on component mount
onMounted(() => {
  deleteModal.value = new bootstrap.Modal(
    document.getElementById("modal-delete-workflow"),
  );
  executeModal.value = new bootstrap.Modal(
    document.getElementById("modal-execute-workflow"),
  );
});
</script>

<template>
  <Head title="Workflows" />

  <BasePageHeading
    title="Workflows"
    subtitle="Build and automate your AI processes"
  >
    <template #extra>
      <Link
        :href="route('workflows.create')"
        class="btn btn-alt-primary"
        v-click-ripple
      >
        <i class="fa fa-plus opacity-50 me-1"></i>
        <span>New Workflow</span>
      </Link>
    </template>
  </BasePageHeading>

  <div class="content">
    <div class="row items-push">
      <!-- Workflows -->
      <div class="col-xl-8">
        <BaseBlock title="Your Workflows" content-class="p-0">
          <template #options>
            <div class="block-options">
              <Link
                :href="route('workflows.create')"
                class="btn btn-sm btn-alt-primary"
                v-click-ripple
              >
                <i class="fa fa-plus me-1"></i> Create
              </Link>
            </div>
          </template>

          <!-- Empty State -->
          <div
            v-if="workflows.length === 0"
            class="block-content text-center py-6"
          >
            <div class="py-4">
              <i
                class="fa fa-4x fa-diagram-project text-gray opacity-25 mb-3"
              ></i>
              <p class="fs-lg fw-medium text-gray-darker">
                You haven't created any workflows yet.
              </p>
              <Link
                :href="route('workflows.create')"
                class="btn btn-hero btn-primary mt-2"
              >
                <i class="fa fa-plus opacity-50 me-1"></i> Create Your First
                Workflow
              </Link>
            </div>
          </div>

          <!-- Workflows Table -->
          <div v-else class="block-content block-content-full">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-vcenter">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th class="d-none d-md-table-cell">Description</th>
                    <th class="text-center">Status</th>
                    <th class="d-none d-sm-table-cell text-center">Last Run</th>
                    <th class="text-center" style="width: 150px">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="workflow in workflows" :key="workflow.id">
                    <td>
                      <Link
                        :href="route('workflows.show', workflow.id)"
                        class="fw-semibold"
                      >
                        {{ workflow.name }}
                      </Link>
                    </td>
                    <td class="d-none d-md-table-cell">
                      <div class="text-muted fs-sm">
                        {{ workflow.description || "No description" }}
                      </div>
                    </td>
                    <td class="text-center">
                      <div class="form-check form-switch d-inline-block">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          :id="`workflow-active-${workflow.id}`"
                          :checked="workflow.is_active"
                          @change="
                            router.put(route('workflows.update', workflow.id), {
                              name: workflow.name,
                              description: workflow.description,
                              is_active: !workflow.is_active,
                            })
                          "
                        />
                      </div>
                    </td>
                    <td class="d-none d-sm-table-cell text-center">
                      <span class="fs-xs text-muted">
                        {{
                          workflow.last_execution_at
                            ? formatDate(workflow.last_execution_at)
                            : "Never"
                        }}
                      </span>
                    </td>
                    <td class="text-center">
                      <div class="btn-group">
                        <button
                          type="button"
                          class="btn btn-sm btn-alt-primary"
                          @click="showExecuteModal(workflow)"
                          title="Run"
                        >
                          <i class="fa fa-play"></i>
                        </button>
                        <Link
                          :href="route('workflows.show', workflow.id)"
                          class="btn btn-sm btn-alt-secondary"
                          title="View"
                        >
                          <i class="fa fa-eye"></i>
                        </Link>
                        <Link
                          :href="route('workflows.edit', workflow.id)"
                          class="btn btn-sm btn-alt-secondary"
                          title="Edit"
                        >
                          <i class="fa fa-pencil-alt"></i>
                        </Link>
                        <button
                          type="button"
                          class="btn btn-sm btn-alt-danger"
                          @click="confirmWorkflowDeletion(workflow)"
                          title="Delete"
                        >
                          <i class="fa fa-times"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </BaseBlock>
      </div>

      <!-- Recent Executions -->
      <div class="col-xl-4">
        <BaseBlock title="Recent Executions">
          <template #options>
            <button
              type="button"
              class="btn-block-option"
              @click="router.reload({ only: ['recentExecutions'] })"
              title="Refresh"
            >
              <i class="fa fa-sync"></i>
            </button>
          </template>

          <!-- Empty State -->
          <div v-if="recentExecutions.length === 0" class="text-center py-4">
            <i class="fa fa-history fa-3x text-muted mb-3"></i>
            <p class="fs-sm text-muted">No recent executions</p>
          </div>

          <!-- Executions List -->
          <div v-else>
            <div class="list-group push">
              <Link
                v-for="execution in recentExecutions"
                :key="execution.id"
                :href="route('workflows.show', execution.workflow.id)"
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
              >
                <div>
                  <div class="fw-semibold">{{ execution.workflow.name }}</div>
                  <div class="fs-sm text-muted">
                    {{ formatDate(execution.started_at) }}
                  </div>
                </div>
                <span
                  class="fw-semibold d-inline-block py-1 px-3 rounded-pill fs-sm"
                  :class="getStatusClass(execution.status)"
                >
                  {{ execution.status }}
                </span>
              </Link>
            </div>
          </div>
        </BaseBlock>
      </div>
    </div>

    <!-- Workflow Export/Import Block -->
    <BaseBlock title="Import & Export">
      <div class="row">
        <div class="col-md-6">
          <h4 class="h5 fw-medium">Export Workflow</h4>
          <p class="text-muted fs-sm">
            Export your workflows to share with others or use as backups.
          </p>
          <div v-if="workflows.length > 0" class="mb-4">
            <div class="input-group">
              <select
                class="form-select"
                id="workflow-export"
                ref="exportSelectRef"
                v-model="selectedWorkflowId"
              >
                <option value="">Select a workflow to export</option>
                <option
                  v-for="workflow in workflows"
                  :key="workflow.id"
                  :value="workflow.id"
                >
                  {{ workflow.name }}
                </option>
              </select>
              <button
                class="btn btn-alt-primary"
                type="button"
                @click="exportWorkflow"
                :disabled="!selectedWorkflowId"
              >
                <i class="fa fa-download me-1"></i> Export
              </button>
            </div>
          </div>
          <div v-else class="mb-4">
            <div class="alert alert-info d-flex">
              <div class="flex-shrink-0">
                <i class="fa fa-info-circle fa-fw"></i>
              </div>
              <div class="ms-2">
                No workflows available for export. Create a workflow first.
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 pb-4">
          <h4 class="h5 fw-medium">Import Workflow</h4>
          <p class="text-muted fs-sm">
            Import workflows from JSON files to quickly set up your automations.
          </p>
          <div class="input-group">
            <input
              type="file"
              class="form-control"
              id="workflow-import-file"
              ref="importFileRef"
              accept=".json"
              @change="handleFileImportChange"
              aria-describedby="workflow-import-button"
            />
            <button
              class="btn btn-alt-primary"
              type="button"
              id="workflow-import-button"
              @click="importWorkflow"
              :disabled="!selectedImportFile"
            >
              <i class="fa fa-upload me-1"></i> Import
            </button>
          </div>
          <div
            v-if="importFileName !== 'Choose file...'"
            class="form-text mt-1"
          >
            Selected file: {{ importFileName }}
          </div>
        </div>
      </div>
    </BaseBlock>
  </div>

  <!-- Delete Confirmation Modal -->
  <div
    class="modal fade"
    id="modal-delete-workflow"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modal-delete-workflow"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="block block-rounded block-themed block-transparent mb-0">
          <div class="block-header bg-danger">
            <h3 class="block-title">Delete Workflow</h3>
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
            <p>Are you sure you want to delete this workflow?</p>
            <p v-if="workflowToDelete" class="fw-semibold">
              {{ workflowToDelete.name }}
            </p>
            <p class="text-danger">
              <i class="fa fa-exclamation-triangle me-1"></i> This action cannot
              be undone. The workflow and all its execution history will be
              permanently deleted.
            </p>
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
              class="btn btn-sm btn-danger"
              :class="{ disabled: deleting }"
              @click="deleteWorkflow()"
            >
              <i class="fa fa-fw fa-trash opacity-50 me-1"></i> Delete Workflow
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END Delete Confirmation Modal -->

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
            <p v-if="workflowToExecute" class="fw-semibold">
              {{ workflowToExecute.name }}
            </p>

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
              :class="{ disabled: executingWorkflow }"
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
