<template>
  <div>
    <!-- Page Header -->
    <div class="bg-body-light">
      <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
          <div class="flex-grow-1">
            <h1 class="h3 fw-bold mb-1">
              AI Playground
            </h1>
            <h2 class="fs-base lh-base fw-medium text-muted mb-0">
              Testen und optimieren Sie Ihre Prompts in Echtzeit
            </h2>
          </div>
          <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-alt">
              <li class="breadcrumb-item">
                <Link :href="route('dashboard')" class="link-fx">Dashboard</Link>
              </li>
              <li class="breadcrumb-item" aria-current="page">
                Playground
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <!-- END Page Header -->

    <!-- Page Content -->
    <div class="content">
      <!-- Create New Prompt -->
      <div class="row mb-4">
        <div class="col-12">
          <Link :href="route('prompts.create')" class="block block-rounded block-link-shadow text-center">
            <div class="block-content block-content-full bg-body-light">
              <div class="py-3">
                <div class="mb-3">
                  <i class="fa fa-plus-circle fa-3x text-primary"></i>
                </div>
                <div class="fs-4 fw-semibold">Neuen Prompt erstellen</div>
                <div class="text-muted">Beginnen Sie mit einem neuen AI-Prompt</div>
              </div>
            </div>
          </Link>
        </div>
      </div>

      <!-- Prompt List -->
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Meine Prompts</h3>
          <div class="block-options">
            <div class="form-inline">
              <input
                v-model="search"
                class="form-control form-control-sm"
                type="text"
                placeholder="Suchen..."
              >
            </div>
          </div>
        </div>
        <div class="block-content pb-3">
          <div v-if="prompts.length === 0" class="text-center py-4">
            <div class="mb-3">
              <i class="fa fa-folder-open fa-4x text-gray"></i>
            </div>
            <p class="fs-sm text-muted">Sie haben noch keine Prompts erstellt.</p>
            <Link :href="route('prompts.create')" class="btn btn-sm btn-primary">
              <i class="fa fa-plus me-1"></i> Ersten Prompt erstellen
            </Link>
          </div>

          <div v-else class="row">
            <div v-for="prompt in filteredPrompts" :key="prompt.id" class="col-md-6 col-xl-4">
              <div class="block block-rounded block-link-shadow h-100 mb-4">
                <div class="block-content block-content-full d-flex flex-column h-100">
                  <div class="mb-2">
                    <div class="d-flex justify-content-between">
                      <h4 class="fs-5 mb-1">{{ prompt.title }}</h4>
                      <div class="dropdown">
                        <button type="button" class="btn btn-sm btn-alt-secondary" id="dropdown-prompt-actions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-prompt-actions">
                          <Link class="dropdown-item" :href="route('prompts.edit', prompt.id)">
                            <i class="fa fa-fw fa-edit me-1"></i> Bearbeiten
                          </Link>
                          <Link class="dropdown-item" :href="route('playground.show', prompt.id)">
                            <i class="fa fa-fw fa-flask me-1"></i> Im Playground testen
                          </Link>
                          <div class="dropdown-divider"></div>
                          <button class="dropdown-item text-danger" @click="confirmDelete(prompt)">
                            <i class="fa fa-fw fa-trash me-1"></i> Löschen
                          </button>
                        </div>
                      </div>
                    </div>
                    <div v-if="prompt.tags && prompt.tags.length > 0" class="mb-2">
                      <span v-for="tag in prompt.tags" :key="tag" class="badge bg-primary me-1">{{ tag }}</span>
                    </div>
                  </div>
                  <p class="text-muted flex-grow-1">{{ truncate(prompt.description, 100) }}</p>
                  <div v-if="prompt.currentVersion" class="fs-sm text-muted mb-0 mt-auto">
                    <i class="fa fa-clock me-1"></i> Zuletzt aktualisiert: {{ formatDate(prompt.updated_at) }}
                  </div>
                  <div class="mt-3">
                    <Link :href="route('playground.show', prompt.id)" class="btn btn-sm btn-alt-primary w-100">
                      <i class="fa fa-flask me-1"></i> Im Playground öffnen
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END Page Content -->

    <!-- Delete Prompt Modal -->
    <div class="modal" id="modal-delete-prompt" tabindex="-1" role="dialog" aria-labelledby="modal-delete-prompt" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="block block-rounded block-transparent mb-0">
            <div class="block-header block-header-default">
              <h3 class="block-title">Prompt löschen</h3>
              <div class="block-options">
                <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                  <i class="fa fa-fw fa-times"></i>
                </button>
              </div>
            </div>
            <div class="block-content fs-sm">
              <p>Sind Sie sicher, dass Sie diesen Prompt löschen möchten? Diese Aktion kann nicht rückgängig gemacht werden.</p>
              <p><strong>{{ promptToDelete?.title }}</strong></p>
            </div>
            <div class="block-content block-content-full text-end bg-body">
              <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Abbrechen</button>
              <button type="button" class="btn btn-sm btn-danger" @click="deletePrompt" :disabled="isDeleting">
                <i v-if="isDeleting" class="fa fa-spinner fa-spin me-1"></i>
                Löschen
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

export default {
  props: {
    prompts: Array,
  },

  setup(props) {
    const search = ref('');
    const promptToDelete = ref(null);
    const isDeleting = ref(false);

    const filteredPrompts = computed(() => {
      if (!search.value) return props.prompts;

      const searchTerm = search.value.toLowerCase();
      return props.prompts.filter(prompt =>
        prompt.title.toLowerCase().includes(searchTerm) ||
        (prompt.description && prompt.description.toLowerCase().includes(searchTerm)) ||
        (prompt.tags && prompt.tags.some(tag => tag.toLowerCase().includes(searchTerm)))
      );
    });

    const truncate = (text, length) => {
      if (!text) return '';
      return text.length > length ? text.substring(0, length) + '...' : text;
    };

    const formatDate = (dateString) => {
      const date = new Date(dateString);
      return new Intl.DateTimeFormat('de-DE', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      }).format(date);
    };

    const confirmDelete = (prompt) => {
      promptToDelete.value = prompt;
      // Use OneUI modal API
      window.OneUI.helpers('jq-appear');
      new bootstrap.Modal(document.getElementById('modal-delete-prompt')).show();
    };

    const deletePrompt = () => {
      if (!promptToDelete.value) return;

      isDeleting.value = true;
      router.delete(route('prompts.destroy', promptToDelete.value.id), {
        onSuccess: () => {
          // Modal will be hidden automatically by Inertia refresh
          isDeleting.value = false;
          promptToDelete.value = null;
        },
        onError: () => {
          isDeleting.value = false;
        }
      });
    };

    return {
      search,
      filteredPrompts,
      truncate,
      formatDate,
      confirmDelete,
      deletePrompt,
      promptToDelete,
      isDeleting
    };
  }
}
</script>
