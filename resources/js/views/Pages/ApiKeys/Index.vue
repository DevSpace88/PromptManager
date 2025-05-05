// resources/js/Pages/ApiKeys/Index.vue
<script setup>
import { ref, onMounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';

// Props definition
const props = defineProps({
  apiKeys: {
    type: Array,
    required: true
  }
});

// Forms for adding and editing API keys
const addKeyForm = useForm({
  provider: 'openai',
  key: '',
  label: '',
  is_default: false
});

const editKeyForm = useForm({
  id: null,
  provider: '',
  key: '',
  label: '',
  is_default: false
});

// Modal controls
const addKeyModal = ref(null);
const editKeyModal = ref(null);
const deleteKeyModal = ref(null);
const keyToDelete = ref(null);
const deleting = ref(false);
const showKeyValue = ref(false);

// Provider options
const providers = [
  { value: 'openai', label: 'OpenAI', icon: 'fa-brain' },
  { value: 'anthropic', label: 'Anthropic', icon: 'fa-robot' },
  { value: 'google', label: 'Google Gemini', icon: 'fa-microchip' },
  { value: 'deepseek', label: 'DeepSeek', icon: 'fa-network-wired' },
  { value: 'ollama', label: 'Ollama (Local)', icon: 'fa-server' }
];

// Open add key modal
const openAddKeyModal = () => {
  // Reset form
  addKeyForm.reset();
  addKeyModal.value.show();
};

// Add new API key
const addApiKey = () => {
  addKeyForm.post(route('api-keys.store'), {
    onSuccess: () => {
      addKeyModal.value.hide();
    }
  });
};

// Open edit key modal
const openEditKeyModal = (apiKey) => {
  editKeyForm.id = apiKey.id;
  editKeyForm.provider = apiKey.provider;
  editKeyForm.key = '';  // Don't populate current key for security
  editKeyForm.label = apiKey.label;
  editKeyForm.is_default = apiKey.is_default;

  editKeyModal.value.show();
};

// Update API key
const updateApiKey = () => {
  editKeyForm.put(route('api-keys.update', editKeyForm.id), {
    onSuccess: () => {
      editKeyModal.value.hide();
    }
  });
};

// Confirm API key deletion
const confirmKeyDeletion = (apiKey) => {
  keyToDelete.value = apiKey;
  deleteKeyModal.value.show();
};

// Delete API key
const deleteApiKey = () => {
  if (!keyToDelete.value) return;

  deleting.value = true;

  router.delete(route('api-keys.destroy', keyToDelete.value.id), {
    onSuccess: () => {
      deleteKeyModal.value.hide();
      deleting.value = false;
    },
    onError: () => {
      deleting.value = false;
    },
  });
};

// Get provider info by value
const getProviderInfo = (providerValue) => {
  return providers.find(p => p.value === providerValue) || { label: providerValue, icon: 'fa-key' };
};

// Initialize modals on component mount
onMounted(() => {
  addKeyModal.value = new bootstrap.Modal(document.getElementById('modal-add-api-key'));
  editKeyModal.value = new bootstrap.Modal(document.getElementById('modal-edit-api-key'));
  deleteKeyModal.value = new bootstrap.Modal(document.getElementById('modal-delete-api-key'));
});
</script>

<template>
  <Head title="API Keys" />

  <BasePageHeading title="API Keys" subtitle="Manage your AI provider connections">
    <template #extra>
      <button type="button" class="btn btn-alt-primary" @click="openAddKeyModal" v-click-ripple>
        <i class="fa fa-plus opacity-50 me-1"></i>
        <span>Add API Key</span>
      </button>
    </template>
  </BasePageHeading>

  <div class="content">
    <!-- API Keys Overview -->
<!--    <div class="row">-->
<!--      <div class="col-md-6 col-xl-3" v-for="provider in providers" :key="provider.value">-->
<!--        <BaseBlock class="h-100 mb-0">-->
<!--          <template #title>-->
<!--            <i :class="`fa ${provider.icon} me-1 text-muted`"></i> {{ provider.label }}-->
<!--          </template>-->
<!--          <template #options>-->
<!--            <button-->
<!--              type="button"-->
<!--              class="btn-block-option"-->
<!--              @click="-->
<!--                addKeyForm.provider = provider.value;-->
<!--                openAddKeyModal();-->
<!--              "-->
<!--            >-->
<!--              <i class="fa fa-plus"></i>-->
<!--            </button>-->
<!--          </template>-->

<!--          <div v-if="apiKeys.filter(key => key.provider === provider.value).length === 0" class="py-3 text-center">-->
<!--            <div class="text-muted mb-2">No API keys configured</div>-->
<!--            <button-->
<!--              type="button"-->
<!--              class="btn btn-sm btn-alt-primary"-->
<!--              @click="-->
<!--                addKeyForm.provider = provider.value;-->
<!--                openAddKeyModal();-->
<!--              "-->
<!--              v-click-ripple-->
<!--            >-->
<!--              <i class="fa fa-plus me-1"></i> Add Key-->
<!--            </button>-->
<!--          </div>-->

<!--          <div v-else>-->
<!--            <div-->
<!--              v-for="key in apiKeys.filter(key => key.provider === provider.value)"-->
<!--              :key="key.id"-->
<!--              class="d-flex align-items-center justify-content-between py-2"-->
<!--            >-->
<!--              <div>-->
<!--                <div class="fw-medium d-flex align-items-center">-->
<!--                  {{ key.label || 'Unnamed Key' }}-->
<!--                  <span v-if="key.is_default" class="badge bg-success ms-2">Default</span>-->
<!--                </div>-->
<!--                <div class="fs-sm text-muted">{{ key.masked_key }}</div>-->
<!--              </div>-->
<!--              <div class="btn-group">-->
<!--                <button-->
<!--                  type="button"-->
<!--                  class="btn btn-sm btn-alt-secondary"-->
<!--                  @click="openEditKeyModal(key)"-->
<!--                  title="Edit"-->
<!--                >-->
<!--                  <i class="fa fa-pencil-alt"></i>-->
<!--                </button>-->
<!--                <button-->
<!--                  type="button"-->
<!--                  class="btn btn-sm btn-alt-danger"-->
<!--                  @click="confirmKeyDeletion(key)"-->
<!--                  title="Delete"-->
<!--                >-->
<!--                  <i class="fa fa-times"></i>-->
<!--                </button>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </BaseBlock>-->
<!--      </div>-->
<!--    </div>-->

    <!-- API Configuration Guides -->
<!--    <BaseBlock title="API Configuration Guides" class="my-4">-->
<!--      <div class="row items-push">-->
<!--        <div class="col-md-6 col-xl-3 py-4">-->
<!--          <div class="d-flex">-->
<!--            <div class="flex-shrink-0 me-3">-->
<!--              <div class="item item-rounded bg-body">-->
<!--                <i class="fa fa-brain text-primary"></i>-->
<!--              </div>-->
<!--            </div>-->
<!--            <div class="flex-grow-1">-->
<!--              <h4 class="fs-md fw-semibold mb-1">OpenAI API</h4>-->
<!--              <p class="text-muted fs-sm mb-2">-->
<!--                Create API keys in the OpenAI platform to access GPT models.-->
<!--              </p>-->
<!--              <a href="https://platform.openai.com/api-keys" target="_blank" class="btn btn-sm btn-alt-primary">-->
<!--                <i class="fa fa-external-link-alt me-1"></i> Get Key-->
<!--              </a>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--        <div class="col-md-6 col-xl-3 py-4 ">-->
<!--          <div class="d-flex">-->
<!--            <div class="flex-shrink-0 me-3">-->
<!--              <div class="item item-rounded bg-body">-->
<!--                <i class="fa fa-robot text-info"></i>-->
<!--              </div>-->
<!--            </div>-->
<!--            <div class="flex-grow-1">-->
<!--              <h4 class="fs-md fw-semibold mb-1">Anthropic API</h4>-->
<!--              <p class="text-muted fs-sm mb-2">-->
<!--                Get API keys for Claude models via the Anthropic console.-->
<!--              </p>-->
<!--              <a href="https://console.anthropic.com/keys" target="_blank" class="btn btn-sm btn-alt-info">-->
<!--                <i class="fa fa-external-link-alt me-1"></i> Get Key-->
<!--              </a>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--        <div class="col-md-6 col-xl-3 py-4">-->
<!--          <div class="d-flex">-->
<!--            <div class="flex-shrink-0 me-3">-->
<!--              <div class="item item-rounded bg-body">-->
<!--                <i class="fa fa-microchip text-danger"></i>-->
<!--              </div>-->
<!--            </div>-->
<!--            <div class="flex-grow-1">-->
<!--              <h4 class="fs-md fw-semibold mb-1">Google AI</h4>-->
<!--              <p class="text-muted fs-sm mb-2">-->
<!--                Access Gemini models with a Google AI API key.-->
<!--              </p>-->
<!--              <a href="https://ai.google.dev/" target="_blank" class="btn btn-sm btn-alt-danger">-->
<!--                <i class="fa fa-external-link-alt me-1"></i> Get Key-->
<!--              </a>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--        <div class="col-md-6 col-xl-3 py-4">-->
<!--          <div class="d-flex">-->
<!--            <div class="flex-shrink-0 me-3">-->
<!--              <div class="item item-rounded bg-body">-->
<!--                <i class="fa fa-network-wired text-success"></i>-->
<!--              </div>-->
<!--            </div>-->
<!--            <div class="flex-grow-1">-->
<!--              <h4 class="fs-md fw-semibold mb-1">DeepSeek</h4>-->
<!--              <p class="text-muted fs-sm mb-2">-->
<!--                Access DeepSeek models with your API key from the platform.-->
<!--              </p>-->
<!--              <a href="https://platform.deepseek.com/api_keys" target="_blank" class="btn btn-sm btn-alt-success">-->
<!--                <i class="fa fa-external-link-alt me-1"></i> Get Key-->
<!--              </a>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--        <div class="col-md-6 col-xl-3 py-4">-->
<!--          <div class="d-flex">-->
<!--            <div class="flex-shrink-0 me-3">-->
<!--              <div class="item item-rounded bg-body">-->
<!--                <i class="fa fa-server text-warning"></i>-->
<!--              </div>-->
<!--            </div>-->
<!--            <div class="flex-grow-1">-->
<!--              <h4 class="fs-md fw-semibold mb-1">Ollama</h4>-->
<!--              <p class="text-muted fs-sm mb-2">-->
<!--                For Ollama, enter your local server URL as the "key".-->
<!--              </p>-->
<!--              <a href="https://ollama.com/download" target="_blank" class="btn btn-sm btn-alt-warning">-->
<!--                <i class="fa fa-external-link-alt me-1"></i> Install Ollama-->
<!--              </a>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </BaseBlock>-->

    <!-- API Keys Overview -->
    <div class="row g-3">
      <div class="col" v-for="provider in providers" :key="provider.value">
        <BaseBlock class="h-100">
          <template #title>
            <i :class="`fa ${provider.icon} me-1 text-muted`"></i> {{ provider.label }}
          </template>
          <template #options>
            <button
              type="button"
              class="btn-block-option"
              @click="
            addKeyForm.provider = provider.value;
            openAddKeyModal();
          "
            >
              <i class="fa fa-plus"></i>
            </button>
          </template>

          <div v-if="apiKeys.filter(key => key.provider === provider.value).length === 0" class="py-3 text-center">
            <div class="text-muted mb-2">No API keys configured</div>
            <button
              type="button"
              class="btn btn-sm btn-alt-primary"
              @click="
            addKeyForm.provider = provider.value;
            openAddKeyModal();
          "
              v-click-ripple
            >
              <i class="fa fa-plus me-1"></i> Add Key
            </button>
          </div>

          <div v-else>
            <div
              v-for="key in apiKeys.filter(key => key.provider === provider.value)"
              :key="key.id"
              class="d-flex align-items-center justify-content-between py-2"
            >
              <div>
                <div class="fw-medium d-flex align-items-center">
                  {{ key.label || 'Unnamed Key' }}
                  <span v-if="key.is_default" class="badge bg-success ms-2">Default</span>
                </div>
                <div class="fs-sm text-muted">{{ key.masked_key }}</div>
              </div>
              <div class="btn-group">
                <button
                  type="button"
                  class="btn btn-sm btn-alt-secondary"
                  @click="openEditKeyModal(key)"
                  title="Edit"
                >
                  <i class="fa fa-pencil-alt"></i>
                </button>
                <button
                  type="button"
                  class="btn btn-sm btn-alt-danger"
                  @click="confirmKeyDeletion(key)"
                  title="Delete"
                >
                  <i class="fa fa-times"></i>
                </button>
              </div>
            </div>
          </div>
        </BaseBlock>
      </div>
    </div>

    <!-- API Configuration Guides -->
    <BaseBlock title="API Configuration Guides" class="my-4">
      <div class="row items-push justify-content-center">
        <div class="col-lg py-3 px-4">
          <div class="d-flex">
            <div class="flex-shrink-0 me-3">
              <div class="item item-rounded bg-body-light">
                <i class="fa fa-brain text-primary"></i>
              </div>
            </div>
            <div class="flex-grow-1">
              <h4 class="fs-md fw-semibold mb-1">OpenAI API</h4>
              <p class="text-muted fs-sm mb-2">
                Create API keys to access GPT models.
              </p>
              <a href="https://platform.openai.com/api-keys" target="_blank" class="btn btn-sm btn-alt-primary">
                <i class="fa fa-external-link-alt me-1"></i> Get Key
              </a>
            </div>
          </div>
        </div>
        <div class="col-lg py-3 px-4">
          <div class="d-flex">
            <div class="flex-shrink-0 me-3">
              <div class="item item-rounded bg-body-light">
                <i class="fa fa-robot text-info"></i>
              </div>
            </div>
            <div class="flex-grow-1">
              <h4 class="fs-md fw-semibold mb-1">Anthropic API</h4>
              <p class="text-muted fs-sm mb-2">
                Access Claude models via Anthropic.
              </p>
              <a href="https://console.anthropic.com/keys" target="_blank" class="btn btn-sm btn-alt-info">
                <i class="fa fa-external-link-alt me-1"></i> Get Key
              </a>
            </div>
          </div>
        </div>
        <div class="col-lg py-3 px-4">
          <div class="d-flex">
            <div class="flex-shrink-0 me-3">
              <div class="item item-rounded bg-body-light">
                <i class="fa fa-microchip text-danger"></i>
              </div>
            </div>
            <div class="flex-grow-1">
              <h4 class="fs-md fw-semibold mb-1">Google AI</h4>
              <p class="text-muted fs-sm mb-2">
                Connect to Gemini models.
              </p>
              <a href="https://ai.google.dev/" target="_blank" class="btn btn-sm btn-alt-danger">
                <i class="fa fa-external-link-alt me-1"></i> Get Key
              </a>
            </div>
          </div>
        </div>
        <div class="col-lg py-3 px-4">
          <div class="d-flex">
            <div class="flex-shrink-0 me-3">
              <div class="item item-rounded bg-body-light">
                <i class="fa fa-network-wired text-success"></i>
              </div>
            </div>
            <div class="flex-grow-1">
              <h4 class="fs-md fw-semibold mb-1">DeepSeek</h4>
              <p class="text-muted fs-sm mb-2">
                Access DeepSeek AI models.
              </p>
              <a href="https://platform.deepseek.com/api_keys" target="_blank" class="btn btn-sm btn-alt-success">
                <i class="fa fa-external-link-alt me-1"></i> Get Key
              </a>
            </div>
          </div>
        </div>
        <div class="col-lg py-3 px-4">
          <div class="d-flex">
            <div class="flex-shrink-0 me-3">
              <div class="item item-rounded bg-body-light">
                <i class="fa fa-server text-warning"></i>
              </div>
            </div>
            <div class="flex-grow-1">
              <h4 class="fs-md fw-semibold mb-1">Ollama</h4>
              <p class="text-muted fs-sm mb-2">
                Run models locally.
              </p>
              <a href="https://ollama.com/download" target="_blank" class="btn btn-sm btn-alt-warning">
                <i class="fa fa-external-link-alt me-1"></i> Install
              </a>
            </div>
          </div>
        </div>
      </div>
    </BaseBlock>

    <!-- Security Notice -->
    <BaseBlock title="Security Information" content-class="bg-body-light pb-4">
      <div class="d-flex">
        <div class="flex-shrink-0 me-3">
          <i class="fa fa-shield-alt fa-2x text-muted"></i>
        </div>
        <div>
          <h4 class="fs-lg fw-semibold mt-0 mb-2">Your API Keys are Encrypted</h4>
          <p class="text-muted mb-0">
            API keys are stored securely using AES-256 encryption. For extra security, we never show your full API key after it has been saved. You can update your keys at any time by providing a new value.
          </p>
        </div>
      </div>
    </BaseBlock>
  </div>

  <!-- Add API Key Modal -->
  <div class="modal fade" id="modal-add-api-key" tabindex="-1" role="dialog" aria-labelledby="modal-add-api-key" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="block block-rounded block-themed block-transparent mb-0">
          <div class="block-header bg-primary">
            <h3 class="block-title">Add API Key</h3>
            <div class="block-options">
              <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                <i class="fa fa-fw fa-times"></i>
              </button>
            </div>
          </div>
          <form @submit.prevent="addApiKey">
            <div class="block-content fs-sm">
              <div class="mb-4">
                <label class="form-label" for="add-key-provider">Provider</label>
                <select
                  id="add-key-provider"
                  class="form-select"
                  v-model="addKeyForm.provider"
                  required
                >
                  <option v-for="provider in providers" :key="provider.value" :value="provider.value">
                    {{ provider.label }}
                  </option>
                </select>
                <div v-if="addKeyForm.errors.provider" class="invalid-feedback d-block">
                  {{ addKeyForm.errors.provider }}
                </div>
              </div>

              <div class="mb-4">
                <label class="form-label" for="add-key-label">Label (optional)</label>
                <input
                  id="add-key-label"
                  type="text"
                  class="form-control"
                  v-model="addKeyForm.label"
                  placeholder="e.g., Production, Development"
                />
                <div v-if="addKeyForm.errors.label" class="invalid-feedback d-block">
                  {{ addKeyForm.errors.label }}
                </div>
              </div>

              <div class="mb-4">
                <label class="form-label" for="add-key-value">API Key</label>
                <div class="input-group">
                  <input
                    id="add-key-value"
                    :type="showKeyValue ? 'text' : 'password'"
                    class="form-control"
                    v-model="addKeyForm.key"
                    placeholder="Enter API key"
                    required
                  />
                  <button
                    type="button"
                    class="btn btn-alt-secondary"
                    @click="showKeyValue = !showKeyValue"
                  >
                    <i :class="showKeyValue ? 'fa fa-eye-slash' : 'fa fa-eye'"></i>
                  </button>
                </div>
                <div v-if="addKeyForm.errors.key" class="invalid-feedback d-block">
                  {{ addKeyForm.errors.key }}
                </div>
                <div class="form-text">
                  <span v-if="addKeyForm.provider === 'ollama'">
                    For Ollama, enter the URL (e.g., http://localhost:11434)
                  </span>
                  <span v-else>
                    Enter the API key from your provider's dashboard
                  </span>
                </div>
              </div>

              <div class="mb-4">
                <div class="form-check">
                  <input
                    id="add-key-default"
                    type="checkbox"
                    class="form-check-input"
                    v-model="addKeyForm.is_default"
                  />
                  <label class="form-check-label" for="add-key-default">
                    Set as default for this provider
                  </label>
                </div>
                <div v-if="addKeyForm.errors.is_default" class="invalid-feedback d-block">
                  {{ addKeyForm.errors.is_default }}
                </div>
              </div>
            </div>
            <div class="block-content block-content-full text-end bg-body">
              <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Cancel</button>
              <button
                type="submit"
                class="btn btn-sm btn-primary"
                :disabled="addKeyForm.processing"
              >
                <i class="fa fa-fw fa-check opacity-50 me-1"></i> Add Key
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- END Add API Key Modal -->

  <!-- Edit API Key Modal -->
  <div class="modal fade" id="modal-edit-api-key" tabindex="-1" role="dialog" aria-labelledby="modal-edit-api-key" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="block block-rounded block-themed block-transparent mb-0">
          <div class="block-header bg-primary">
            <h3 class="block-title">Edit API Key</h3>
            <div class="block-options">
              <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                <i class="fa fa-fw fa-times"></i>
              </button>
            </div>
          </div>
          <form @submit.prevent="updateApiKey">
            <div class="block-content fs-sm">
              <div class="mb-4">
                <label class="form-label">Provider</label>
                <div class="form-control-plaintext">
                  <i :class="`fa ${getProviderInfo(editKeyForm.provider).icon} me-1`"></i>
                  {{ getProviderInfo(editKeyForm.provider).label }}
                </div>
              </div>

              <div class="mb-4">
                <label class="form-label" for="edit-key-label">Label (optional)</label>
                <input
                  id="edit-key-label"
                  type="text"
                  class="form-control"
                  v-model="editKeyForm.label"
                  placeholder="e.g., Production, Development"
                />
                <div v-if="editKeyForm.errors.label" class="invalid-feedback d-block">
                  {{ editKeyForm.errors.label }}
                </div>
              </div>

              <div class="mb-4">
                <label class="form-label" for="edit-key-value">New API Key (leave blank to keep current)</label>
                <div class="input-group">
                  <input
                    id="edit-key-value"
                    :type="showKeyValue ? 'text' : 'password'"
                    class="form-control"
                    v-model="editKeyForm.key"
                    placeholder="Enter new API key"
                  />
                  <button
                    type="button"
                    class="btn btn-alt-secondary"
                    @click="showKeyValue = !showKeyValue"
                  >
                    <i :class="showKeyValue ? 'fa fa-eye-slash' : 'fa fa-eye'"></i>
                  </button>
                </div>
                <div v-if="editKeyForm.errors.key" class="invalid-feedback d-block">
                  {{ editKeyForm.errors.key }}
                </div>
                <div class="form-text">
                  <span v-if="editKeyForm.provider === 'ollama'">
                    For Ollama, enter the URL (e.g., http://localhost:11434)
                  </span>
                  <span v-else>
                    Only enter a new value if you want to change the current key
                  </span>
                </div>
              </div>

              <div class="mb-4">
                <div class="form-check">
                  <input
                    id="edit-key-default"
                    type="checkbox"
                    class="form-check-input"
                    v-model="editKeyForm.is_default"
                  />
                  <label class="form-check-label" for="edit-key-default">
                    Set as default for this provider
                  </label>
                </div>
                <div v-if="editKeyForm.errors.is_default" class="invalid-feedback d-block">
                  {{ editKeyForm.errors.is_default }}
                </div>
              </div>
            </div>
            <div class="block-content block-content-full text-end bg-body">
              <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Cancel</button>
              <button
                type="submit"
                class="btn btn-sm btn-primary"
                :disabled="editKeyForm.processing"
              >
                <i class="fa fa-fw fa-check opacity-50 me-1"></i> Update Key
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- END Edit API Key Modal -->

  <!-- Delete API Key Modal -->
  <div class="modal fade" id="modal-delete-api-key" tabindex="-1" role="dialog" aria-labelledby="modal-delete-api-key" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="block block-rounded block-themed block-transparent mb-0">
          <div class="block-header bg-danger">
            <h3 class="block-title">Delete API Key</h3>
            <div class="block-options">
              <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                <i class="fa fa-fw fa-times"></i>
              </button>
            </div>
          </div>
          <div class="block-content fs-sm">
            <p>Are you sure you want to delete this API key?</p>
            <p v-if="keyToDelete">
              <span class="fw-semibold">
                <i :class="`fa ${getProviderInfo(keyToDelete.provider).icon} me-1`"></i>
                {{ getProviderInfo(keyToDelete.provider).label }}
              </span>
              <span v-if="keyToDelete.label"> - {{ keyToDelete.label }}</span>
            </p>
            <p class="text-danger">
              <i class="fa fa-exclamation-triangle me-1"></i> This action cannot be undone. Workflows using this API key will no longer function.
            </p>
          </div>
          <div class="block-content block-content-full text-end bg-body">
            <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Cancel</button>
            <button
              type="button"
              class="btn btn-sm btn-danger"
              :class="{ 'disabled': deleting }"
              @click="deleteApiKey()"
            >
              <i class="fa fa-fw fa-trash opacity-50 me-1"></i> Delete Key
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END Delete API Key Modal -->
</template>
