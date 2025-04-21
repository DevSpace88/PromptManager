// resources/js/Pages/Prompts/Index.vue
<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

// Props definition
const props = defineProps({
  prompts: {
    type: Array,
    required: true
  }
});

// Modal controls for delete confirmation
const deleteModal = ref(null);
const promptToDelete = ref(null);
const deleting = ref(false);

// Format date to localized format
const formatDate = (dateString) => {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('default', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  }).format(date);
};

// Confirm deletion with modal
const confirmPromptDeletion = (prompt) => {
  promptToDelete.value = prompt;
  deleteModal.value.show();
};

// Delete the prompt
const deletePrompt = () => {
  if (!promptToDelete.value) return;

  deleting.value = true;

  router.delete(route('prompts.destroy', promptToDelete.value.id), {
    onSuccess: () => {
      deleteModal.value.hide();
      deleting.value = false;
    },
    onError: () => {
      deleting.value = false;
    },
  });
};

// Initialize modal on component mount
onMounted(() => {
  deleteModal.value = new bootstrap.Modal(document.getElementById('modal-delete-prompt'));
});
</script>

<template>
  <Head title="Prompts" />

  <BasePageHeading title="Prompts" subtitle="Create and manage your AI prompts">
    <template #extra>
      <Link :href="route('prompts.create')" class="btn btn-alt-primary" v-click-ripple>
        <i class="fa fa-plus opacity-50 me-1"></i>
        <span>New Prompt</span>
      </Link>
    </template>
  </BasePageHeading>

  <div class="content">
    <!-- All Prompts Block -->
    <BaseBlock title="Your Prompts" content-class="p-0">
      <template #options>
        <div class="block-options">
          <div class="dropdown">
            <button type="button" class="btn-block-option" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-fw fa-ellipsis-v"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
              <Link :href="route('prompts.create')" class="dropdown-item">
                <i class="fa fa-fw fa-plus opacity-50 me-1"></i>
                <span>New Prompt</span>
              </Link>
              <div role="separator" class="dropdown-divider"></div>
              <Link :href="route('playground.index')" class="dropdown-item">
                <i class="fa fa-fw fa-flask opacity-50 me-1"></i>
                <span>Playground</span>
              </Link>
            </div>
          </div>
        </div>
      </template>

      <!-- Empty State -->
      <div v-if="prompts.length === 0" class="block-content text-center py-6">
        <div class="py-4">
          <i class="fa fa-4x fa-file-alt text-gray opacity-25 mb-3"></i>
          <p class="fs-lg fw-medium text-gray-darker">You haven't created any prompts yet.</p>
          <Link :href="route('prompts.create')" class="btn btn-hero btn-primary mt-2">
            <i class="fa fa-plus opacity-50 me-1"></i> Create Your First Prompt
          </Link>
        </div>
      </div>

      <!-- Prompts Table -->
      <div v-else class="block-content block-content-full">
        <div class="table-responsive">
          <table class="table table-striped table-vcenter">
            <thead>
            <tr>
              <th>Title</th>
              <th class="d-none d-md-table-cell">Description</th>
              <th>Tags</th>
              <th class="d-none d-sm-table-cell text-center">Version</th>
              <th class="d-none d-sm-table-cell text-center">Updated</th>
              <th class="text-center" style="width: 160px;">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="prompt in prompts" :key="prompt.id">
              <td>
                <Link :href="route('prompts.show', prompt.id)" class="fw-semibold">
                  {{ prompt.title }}
                </Link>
              </td>
              <td class="d-none d-md-table-cell">
                <div class="text-muted fs-sm text-truncate" style="max-width: 300px;">
                  {{ prompt.description || 'No description' }}
                </div>
              </td>
              <td>
                <div class="d-flex flex-wrap gap-1">
                    <span
                      v-for="(tag, index) in prompt.tags"
                      :key="index"
                      class="fs-xs fw-semibold d-inline-block py-1 px-2 rounded-pill bg-primary-lighter text-primary"
                    >
                      {{ tag }}
                    </span>
                  <span v-if="!prompt.tags || prompt.tags.length === 0" class="fs-xs text-muted">
                      No tags
                    </span>
                </div>
              </td>
              <td class="d-none d-sm-table-cell text-center">
                  <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">
                    v{{ prompt.current_version?.version || 1 }}
                  </span>
              </td>
              <td class="d-none d-sm-table-cell text-center">
                <span class="fs-xs text-muted">{{ formatDate(prompt.updated_at) }}</span>
              </td>
              <td class="text-center">
                <div class="btn-group">
                  <Link :href="route('prompts.show', prompt.id)" class="btn btn-sm btn-alt-secondary" title="View">
                    <i class="fa fa-fw fa-eye"></i>
                  </Link>
                  <Link :href="route('prompts.edit', prompt.id)" class="btn btn-sm btn-alt-secondary" title="Edit">
                    <i class="fa fa-fw fa-pencil-alt"></i>
                  </Link>
                  <Link :href="route('playground.show', prompt.id)" class="btn btn-sm btn-alt-secondary js-bs-tooltip" title="Test in Playground">
                    <i class="fa fa-fw fa-flask"></i>
                  </Link>
                  <button
                    type="button"
                    class="btn btn-sm btn-alt-secondary text-danger"
                    @click="confirmPromptDeletion(prompt)"
                    title="Delete"
                  >
                    <i class="fa fa-fw fa-times"></i>
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

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="modal-delete-prompt" tabindex="-1" role="dialog" aria-labelledby="modal-delete-prompt" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="block block-rounded block-themed block-transparent mb-0">
          <div class="block-header bg-danger">
            <h3 class="block-title">Delete Prompt</h3>
            <div class="block-options">
              <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                <i class="fa fa-fw fa-times"></i>
              </button>
            </div>
          </div>
          <div class="block-content fs-sm">
            <p>Are you sure you want to delete this prompt?</p>
            <p v-if="promptToDelete" class="fw-semibold">{{ promptToDelete.title }}</p>
            <p class="text-danger">
              <i class="fa fa-exclamation-triangle me-1"></i> This action cannot be undone. All versions of this prompt will be permanently deleted.
            </p>
          </div>
          <div class="block-content block-content-full text-end bg-body">
            <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Cancel</button>
            <button
              type="button"
              class="btn btn-sm btn-danger"
              :class="{ 'disabled': deleting }"
              @click="deletePrompt()"
            >
              <i class="fa fa-fw fa-trash opacity-50 me-1"></i> Delete Prompt
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END Delete Confirmation Modal -->
</template>
