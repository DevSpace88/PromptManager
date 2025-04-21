<!--<script setup>-->
<!--import { Head } from '@inertiajs/vue3';-->
<!--import { Link } from '@inertiajs/vue3';-->

<!--import { computed } from 'vue';-->

<!--// Props for stats data-->
<!--const props = defineProps({-->
<!--  promptCount: {-->
<!--    type: Number,-->
<!--    default: 0-->
<!--  },-->
<!--  workflowCount: {-->
<!--    type: Number,-->
<!--    default: 0-->
<!--  },-->
<!--  apiKeyCount: {-->
<!--    type: Number,-->
<!--    default: 0-->
<!--  },-->
<!--  recentExecutions: {-->
<!--    type: Array,-->
<!--    default: () => []-->
<!--  },-->
<!--  popularPrompts: {-->
<!--    type: Array,-->
<!--    default: () => []-->
<!--  }-->
<!--});-->

<!--// Format date as readable string-->
<!--const formatDate = (dateString) => {-->
<!--  const date = new Date(dateString);-->
<!--  return new Intl.DateTimeFormat('default', {-->
<!--    year: 'numeric',-->
<!--    month: 'short',-->
<!--    day: 'numeric',-->
<!--    hour: '2-digit',-->
<!--    minute: '2-digit'-->
<!--  }).format(date);-->
<!--};-->

<!--// Get status badge class based on workflow execution status-->
<!--const getStatusClass = (status) => {-->
<!--  switch(status) {-->
<!--    case 'completed': return 'bg-success';-->
<!--    case 'failed': return 'bg-danger';-->
<!--    case 'running': return 'bg-warning';-->
<!--    case 'pending': return 'bg-info';-->
<!--    default: return 'bg-secondary';-->
<!--  }-->
<!--};-->
<!--</script>-->

<!--<template>-->
<!--  <Head title="Dashboard" />-->

<!--  <BasePageHeading-->
<!--    title="AI Prompt Flow"-->
<!--    :subtitle="`Welcome ${$page.props.auth.user.name.split(' ')[0]}, everything looks good!`"-->
<!--  >-->
<!--    <template #extra>-->
<!--      <div class="d-flex gap-2">-->
<!--        <Link :href="route('prompts.create')" class="btn btn-alt-primary" v-click-ripple>-->
<!--          <i class="fa fa-plus opacity-50 me-1"></i>-->
<!--          <span>New Prompt</span>-->
<!--        </Link>-->
<!--        <Link :href="route('workflows.create')" class="btn btn-alt-success" v-click-ripple>-->
<!--          <i class="fa fa-diagram-project opacity-50 me-1"></i>-->
<!--          <span>Create Workflow</span>-->
<!--        </Link>-->
<!--      </div>-->
<!--    </template>-->
<!--  </BasePageHeading>-->

<!--  <div class="content">-->
<!--    &lt;!&ndash; Overview Stats &ndash;&gt;-->
<!--    <div class="row items-push">-->
<!--      <div class="col-sm-6 col-xl-3">-->
<!--        <BaseBlock class="text-center h-100">-->
<!--          <div class="item item-rounded mx-auto bg-primary-light">-->
<!--            <i class="fa fa-2x fa-file-alt text-primary"></i>-->
<!--          </div>-->
<!--          <div class="fs-3 fw-semibold mt-3">{{ promptCount }}</div>-->
<!--          <div class="text-muted mb-3">Prompts</div>-->
<!--          <Link :href="route('prompts.index')" class="btn btn-sm btn-alt-primary">-->
<!--            <i class="fa fa-list opacity-50 me-1"></i> Manage Prompts-->
<!--          </Link>-->
<!--        </BaseBlock>-->
<!--      </div>-->
<!--      <div class="col-sm-6 col-xl-3">-->
<!--        <BaseBlock class="text-center h-100 mb-0">-->
<!--          <div class="item item-rounded mx-auto bg-info-light">-->
<!--            <i class="fa fa-2x fa-diagram-project text-info"></i>-->
<!--          </div>-->
<!--          <div class="fs-3 fw-semibold mt-3">{{ workflowCount }}</div>-->
<!--          <div class="text-muted mb-3">Workflows</div>-->
<!--          <Link :href="route('workflows.index')" class="btn btn-sm btn-alt-info">-->
<!--            <i class="fa fa-list opacity-50 me-1"></i> View Workflows-->
<!--          </Link>-->
<!--        </BaseBlock>-->
<!--      </div>-->
<!--      <div class="col-sm-6 col-xl-3">-->
<!--        <BaseBlock class="text-center h-100">-->
<!--          <div class="item item-rounded mx-auto bg-warning-light">-->
<!--            <i class="fa fa-2x fa-flask text-warning"></i>-->
<!--          </div>-->
<!--          <div class="fs-3 fw-semibold mt-3">Playground</div>-->
<!--          <div class="text-muted mb-3">Test Your Prompts</div>-->
<!--          <Link :href="route('playground.index')" class="btn btn-sm btn-alt-warning">-->
<!--            <i class="fa fa-play opacity-50 me-1"></i> Open Playground-->
<!--          </Link>-->
<!--        </BaseBlock>-->
<!--      </div>-->
<!--      <div class="col-sm-6 col-xl-3">-->
<!--        <BaseBlock class="text-center h-100">-->
<!--          <div class="item item-rounded mx-auto bg-success-light">-->
<!--            <i class="fa fa-2x fa-key text-success"></i>-->
<!--          </div>-->
<!--          <div class="fs-3 fw-semibold mt-3">{{ apiKeyCount }}</div>-->
<!--          <div class="text-muted mb-3">API Keys</div>-->
<!--          <Link :href="route('api-keys.index')" class="btn btn-sm btn-alt-success">-->
<!--            <i class="fa fa-cog opacity-50 me-1"></i> Manage Keys-->
<!--          </Link>-->
<!--        </BaseBlock>-->
<!--      </div>-->
<!--    </div>-->
<!--    &lt;!&ndash; END Overview Stats &ndash;&gt;-->

<!--    &lt;!&ndash; Recent Activity &ndash;&gt;-->
<!--    <div class="row items-push">-->
<!--      &lt;!&ndash; Recent Executions &ndash;&gt;-->
<!--      <div class="col-xl-7">-->
<!--        <BaseBlock title="Recent Workflow Executions">-->
<!--          <template #options>-->
<!--            <Link :href="route('workflows.index')" class="btn btn-sm btn-alt-secondary">-->
<!--              <i class="fa fa-list opacity-50 me-1"></i> View All-->
<!--            </Link>-->
<!--          </template>-->

<!--          <div v-if="recentExecutions.length === 0" class="text-center py-4">-->
<!--            <i class="fa fa-history fa-3x text-muted mb-3"></i>-->
<!--            <p class="fs-sm text-muted mb-0">No workflow executions yet</p>-->
<!--            <p class="fs-sm">Start running workflows to see execution history</p>-->
<!--          </div>-->

<!--          <div v-else class="table-responsive">-->
<!--            <table class="table table-hover table-vcenter">-->
<!--              <thead>-->
<!--              <tr>-->
<!--                <th>Workflow</th>-->
<!--                <th class="d-none d-sm-table-cell">Started</th>-->
<!--                <th class="text-center">Status</th>-->
<!--                <th class="d-none d-sm-table-cell text-center">Duration</th>-->
<!--                <th class="text-center">Actions</th>-->
<!--              </tr>-->
<!--              </thead>-->
<!--              <tbody>-->
<!--              <tr v-for="execution in recentExecutions" :key="execution.id">-->
<!--                <td>-->
<!--                  <Link :href="route('workflows.show', execution.workflow_id)" class="fw-semibold">-->
<!--                    {{ execution.workflow.name }}-->
<!--                  </Link>-->
<!--                </td>-->
<!--                <td class="d-none d-sm-table-cell">-->
<!--                  <span class="fs-sm">{{ formatDate(execution.started_at) }}</span>-->
<!--                </td>-->
<!--                <td class="text-center">-->
<!--                    <span-->
<!--                      class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill"-->
<!--                      :class="getStatusClass(execution.status)"-->
<!--                    >-->
<!--                      {{ execution.status }}-->
<!--                    </span>-->
<!--                </td>-->
<!--                <td class="d-none d-sm-table-cell text-center">-->
<!--                  <span class="fs-sm">{{ execution.completed_at ? `${((new Date(execution.completed_at) - new Date(execution.started_at)) / 1000).toFixed(1)}s` : '–' }}</span>-->
<!--                </td>-->
<!--                <td class="text-center">-->
<!--                  <button-->
<!--                    class="btn btn-sm btn-alt-secondary"-->
<!--                    @click="$inertia.get(route('executions.status', execution.id))"-->
<!--                  >-->
<!--                    <i class="fa fa-eye"></i>-->
<!--                  </button>-->
<!--                </td>-->
<!--              </tr>-->
<!--              </tbody>-->
<!--            </table>-->
<!--          </div>-->
<!--        </BaseBlock>-->
<!--      </div>-->
<!--      &lt;!&ndash; END Recent Executions &ndash;&gt;-->

<!--      &lt;!&ndash; Popular Prompts &ndash;&gt;-->
<!--      <div class="col-xl-5">-->
<!--        <BaseBlock title="Popular Prompts">-->
<!--          <template #options>-->
<!--            <Link :href="route('prompts.index')" class="btn btn-sm btn-alt-secondary">-->
<!--              <i class="fa fa-list opacity-50 me-1"></i> View All-->
<!--            </Link>-->
<!--          </template>-->

<!--          <div v-if="popularPrompts.length === 0" class="text-center py-4">-->
<!--            <i class="fa fa-file-alt fa-3x text-muted mb-3"></i>-->
<!--            <p class="fs-sm text-muted mb-0">No prompts created yet</p>-->
<!--            <p class="fs-sm">Create your first prompt to get started</p>-->
<!--          </div>-->

<!--          <div v-else>-->
<!--            <ul class="list list-activity mb-0">-->
<!--              <li v-for="prompt in popularPrompts" :key="prompt.id">-->
<!--                <i class="fa fa-fw fa-file-alt text-primary"></i>-->
<!--                <div class="fs-sm fw-semibold">-->
<!--                  <Link :href="route('prompts.show', prompt.id)">{{ prompt.title }}</Link>-->
<!--                </div>-->
<!--                <div v-if="prompt.description" class="fs-sm text-muted text-truncate">-->
<!--                  {{ prompt.description }}-->
<!--                </div>-->
<!--                <div class="d-flex flex-wrap gap-1 mt-1">-->
<!--                  <span-->
<!--                    v-for="(tag, index) in prompt.tags"-->
<!--                    :key="index"-->
<!--                    class="fs-xs fw-semibold py-1 px-2 rounded-pill bg-primary-lighter text-primary"-->
<!--                  >-->
<!--                    {{ tag }}-->
<!--                  </span>-->
<!--                </div>-->
<!--                <div class="d-flex align-items-center justify-content-end mt-1">-->
<!--                  <Link :href="route('playground.show', prompt.id)" class="btn btn-sm btn-alt-success me-1">-->
<!--                    <i class="fa fa-play opacity-50"></i>-->
<!--                  </Link>-->
<!--                  <Link :href="route('prompts.edit', prompt.id)" class="btn btn-sm btn-alt-primary">-->
<!--                    <i class="fa fa-pencil-alt opacity-50"></i>-->
<!--                  </Link>-->
<!--                </div>-->
<!--              </li>-->
<!--            </ul>-->
<!--          </div>-->
<!--        </BaseBlock>-->
<!--      </div>-->
<!--      &lt;!&ndash; END Popular Prompts &ndash;&gt;-->
<!--    </div>-->
<!--    &lt;!&ndash; END Recent Activity &ndash;&gt;-->

<!--    &lt;!&ndash; Getting Started &ndash;&gt;-->
<!--    <BaseBlock title="Getting Started with AI Prompt Flow">-->
<!--      <div class="row py-3">-->
<!--        <div class="col-md-4">-->
<!--          <div class="d-flex">-->
<!--            <div class="flex-shrink-0 me-3">-->
<!--              <div class="item item-rounded bg-primary-light">-->
<!--                <i class="fa fa-file-alt text-primary"></i>-->
<!--              </div>-->
<!--            </div>-->
<!--            <div class="flex-grow-1">-->
<!--              <h4 class="fs-lg fw-semibold mb-1">1. Create Prompts</h4>-->
<!--              <p class="text-muted mb-0">-->
<!--                Design prompt templates with variables that can be dynamically replaced during execution.-->
<!--                Use the {{variable}} syntax to define variables in your prompts.-->
<!--              </p>-->
<!--              <Link :href="route('prompts.create')" class="btn btn-sm btn-alt-primary mt-2">-->
<!--                <i class="fa fa-plus opacity-50 me-1"></i> Create Prompt-->
<!--              </Link>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--        <div class="col-md-4 mt-4 mt-md-0">-->
<!--          <div class="d-flex">-->
<!--            <div class="flex-shrink-0 me-3">-->
<!--              <div class="item item-rounded bg-info-light">-->
<!--                <i class="fa fa-diagram-project text-info"></i>-->
<!--              </div>-->
<!--            </div>-->
<!--            <div class="flex-grow-1">-->
<!--              <h4 class="fs-lg fw-semibold mb-1">2. Design Workflows</h4>-->
<!--              <p class="text-muted mb-0">-->
<!--                Connect different nodes to create powerful automation flows. Chain prompts,-->
<!--                conditions, and transformations for complex operations.-->
<!--              </p>-->
<!--              <Link :href="route('workflows.create')" class="btn btn-sm btn-alt-info mt-2">-->
<!--                <i class="fa fa-plus opacity-50 me-1"></i> Create Workflow-->
<!--              </Link>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--        <div class="col-md-4 mt-4 mt-md-0">-->
<!--          <div class="d-flex">-->
<!--            <div class="flex-shrink-0 me-3">-->
<!--              <div class="item item-rounded bg-warning-light">-->
<!--                <i class="fa fa-flask text-warning"></i>-->
<!--              </div>-->
<!--            </div>-->
<!--            <div class="flex-grow-1">-->
<!--              <h4 class="fs-lg fw-semibold mb-1">3. Test in Playground</h4>-->
<!--              <p class="text-muted mb-0">-->
<!--                Try your prompts with different inputs and parameters before using them-->
<!--                in workflows. Fine-tune for better results.-->
<!--              </p>-->
<!--              <Link :href="route('playground.index')" class="btn btn-sm btn-alt-warning mt-2">-->
<!--                <i class="fa fa-play opacity-50 me-1"></i> Open Playground-->
<!--              </Link>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </BaseBlock>-->
<!--    &lt;!&ndash; END Getting Started &ndash;&gt;-->
<!--  </div>-->
<!--</template>-->


<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

// Props from the controller
const props = defineProps({
  promptCount: {
    type: Number,
    required: true
  },
  workflowCount: {
    type: Number,
    required: true
  },
  apiKeyCount: {
    type: Number,
    required: true
  },
  recentExecutions: {
    type: Array,
    default: () => []
  },
  popularPrompts: {
    type: Array,
    default: () => []
  }
});

// Format date to localized format
const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('default', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date);
};

// Get status class for execution status
const getStatusClass = (status) => {
  switch(status) {
    case 'completed': return 'bg-success-light text-success';
    case 'failed': return 'bg-danger-light text-danger';
    case 'running': return 'bg-warning-light text-warning';
    case 'pending': return 'bg-info-light text-info';
    default: return 'bg-secondary';
  }
};
</script>

<template>
  <Head title="Dashboard" />

  <BasePageHeading title="Dashboard" subtitle="Welcome to AI Prompt Flow">
    <template #extra>
      <Link :href="route('dashboard')" class="breadcrumb-item active">
        <i class="fa fa-home"></i>
      </Link>
    </template>
  </BasePageHeading>

  <div class="content">
    <!-- Stats -->
    <div class="row items-push">
      <!-- Prompts -->
      <div class="col-md-4">
        <BaseBlock class="text-center h-100">
          <div class="p-3">
            <div class="item item-round bg-primary-lighter mx-auto mb-3">
              <i class="fa fa-2x fa-pen-alt text-primary"></i>
            </div>
            <div class="fs-lg fw-semibold text-primary">{{ promptCount }}</div>
            <div class="text-muted mb-3">Prompts</div>
            <Link :href="route('prompts.index')" class="btn btn-sm btn-alt-primary">
              <i class="fa fa-eye opacity-50 me-1"></i> View Prompts
            </Link>
          </div>
        </BaseBlock>
      </div>
      <!-- Workflows -->
      <div class="col-md-4">
        <BaseBlock class="text-center h-100">
          <div class="p-3">
            <div class="item item-round bg-warning-lighter mx-auto mb-3">
              <i class="fa fa-2x fa-diagram-project text-warning"></i>
            </div>
            <div class="fs-lg fw-semibold text-warning">{{ workflowCount }}</div>
            <div class="text-muted mb-3">Workflows</div>
            <Link :href="route('workflows.index')" class="btn btn-sm btn-alt-warning">
              <i class="fa fa-eye opacity-50 me-1"></i> View Workflows
            </Link>
          </div>
        </BaseBlock>
      </div>
      <!-- API Keys -->
      <div class="col-md-4">
        <BaseBlock class="text-center h-100">
          <div class="p-3">
            <div class="item item-round bg-info-lighter mx-auto mb-3">
              <i class="fa fa-2x fa-key text-info"></i>
            </div>
            <div class="fs-lg fw-semibold text-info">{{ apiKeyCount }}</div>
            <div class="text-muted mb-3">API Keys</div>
            <Link :href="route('api-keys.index')" class="btn btn-sm btn-alt-info">
              <i class="fa fa-eye opacity-50 me-1"></i> Manage Keys
            </Link>
          </div>
        </BaseBlock>
      </div>
    </div>

    <div class="row items-push">
      <!-- Recent Workflow Executions -->
      <div class="col-xl-6">
        <BaseBlock title="Recent Workflow Executions">
          <template #options>
            <div class="block-options">
              <Link :href="route('workflows.index')" class="btn btn-sm btn-alt-secondary">
                <i class="fa fa-arrow-right"></i> All Workflows
              </Link>
            </div>
          </template>

          <!-- Empty State -->
          <div v-if="recentExecutions.length === 0" class="py-4 text-center">
            <div class="mb-3">
              <i class="fa fa-history fa-3x text-muted"></i>
            </div>
            <p class="fs-sm text-muted">No recent workflow executions</p>
            <Link :href="route('workflows.create')" class="btn btn-sm btn-alt-primary">
              <i class="fa fa-plus me-1"></i> Create Workflow
            </Link>
          </div>

          <!-- Recent Executions List -->
          <div v-else class="list-group push">
            <div
              v-for="execution in recentExecutions"
              :key="execution.id"
              class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
            >
              <div>
                <div class="fw-semibold">
                  <Link
                    :href="execution.workflow?.id ? route('workflows.show', execution.workflow.id) : '#'"
                    class="link-fx"
                  >
                    {{ execution.workflow_name }}
                  </Link>
                </div>
                <div class="fs-sm text-muted">{{ formatDate(execution.created_at) }}</div>
              </div>
              <span
                class="fw-semibold d-inline-block py-1 px-3 rounded-pill fs-sm"
                :class="getStatusClass(execution.status)"
              >
                {{ execution.status }}
              </span>
            </div>
          </div>
        </BaseBlock>
      </div>

      <!-- Popular Prompts -->
      <div class="col-xl-6">
        <BaseBlock title="Your Prompts">
          <template #options>
            <div class="block-options">
              <Link :href="route('prompts.index')" class="btn btn-sm btn-alt-secondary">
                <i class="fa fa-arrow-right"></i> All Prompts
              </Link>
            </div>
          </template>

          <!-- Empty State -->
          <div v-if="popularPrompts.length === 0" class="py-4 text-center">
            <div class="mb-3">
              <i class="fa fa-pen-alt fa-3x text-muted"></i>
            </div>
            <p class="fs-sm text-muted">No prompts created yet</p>
            <Link :href="route('prompts.create')" class="btn btn-sm btn-alt-primary">
              <i class="fa fa-plus me-1"></i> Create Prompt
            </Link>
          </div>

          <!-- Popular Prompts List -->
          <div v-else class="list-group push">
            <Link
              v-for="prompt in popularPrompts"
              :key="prompt.id"
              :href="route('prompts.show', prompt.id)"
              class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
            >
              <div>
                <div class="fw-semibold">{{ prompt.title }}</div>
                <div class="fs-sm text-muted">{{ prompt.description || 'No description' }}</div>
              </div>
              <span class="badge bg-primary rounded-pill">{{ prompt.version_count }} versions</span>
            </Link>
          </div>
        </BaseBlock>
      </div>
    </div>

    <!-- Getting Started -->
    <BaseBlock title="Getting Started with AI Prompt Flow">
      <div class="row py-3">
        <div class="col-md-4 mb-3">
          <div class="d-flex">
            <div class="flex-shrink-0">
              <span class="fw-bold text-dark fs-1">1</span>
            </div>
            <div class="flex-grow-1 ms-3">
              <h4 class="fs-lg fw-semibold text-dark mb-1">Add API Keys</h4>
              <p class="text-muted">
                Connect your AI service APIs like OpenAI, Anthropic, or Google to use them in your prompts and workflows.
              </p>
              <Link :href="route('api-keys.index')" class="btn btn-sm btn-alt-primary">
                <i class="fa fa-plus opacity-50 me-1"></i> Add API Key
              </Link>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="d-flex">
            <div class="flex-shrink-0">
              <span class="fw-bold text-dark fs-1">2</span>
            </div>
            <div class="flex-grow-1 ms-3">
              <h4 class="fs-lg fw-semibold text-dark mb-1">Create Prompts</h4>
              <p class="text-muted">
                Write and manage prompt templates with variables that can be reused across your projects.
              </p>
              <Link :href="route('prompts.create')" class="btn btn-sm btn-alt-primary">
                <i class="fa fa-plus opacity-50 me-1"></i> Create Prompt
              </Link>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="d-flex">
            <div class="flex-shrink-0">
              <span class="fw-bold text-dark fs-1">3</span>
            </div>
            <div class="flex-grow-1 ms-3">
              <h4 class="fs-lg fw-semibold text-dark mb-1">Build Workflows</h4>
              <p class="text-muted">
                Connect prompts, API calls, and logic into powerful automated workflows for complex AI tasks.
              </p>
              <Link :href="route('workflows.create')" class="btn btn-sm btn-alt-primary">
                <i class="fa fa-plus opacity-50 me-1"></i> Create Workflow
              </Link>
            </div>
          </div>
        </div>
      </div>
    </BaseBlock>
  </div>
</template>
