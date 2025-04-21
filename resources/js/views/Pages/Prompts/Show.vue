<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
  prompt: {
    type: Object,
    required: true
  },
  currentVersion: {
    type: Object,
    required: true
  }
});

// Format date to localized format
const formatDate = (dateString) => {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('default', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  }).format(date);
};
</script>

<template>
  <Head :title="prompt.title" />

  <BasePageHeading :title="prompt.title" :subtitle="prompt.description || 'Prompt Details'">
    <template #extra>
      <div class="d-flex">
        <Link :href="route('prompts.edit', prompt.id)" class="btn btn-alt-primary me-1" v-click-ripple>
          <i class="fa fa-edit opacity-50 me-1"></i>
          <span>Edit Prompt</span>
        </Link>
        <Link :href="route('playground.index', { prompt_id: prompt.id })" class="btn btn-alt-success" v-click-ripple>
          <i class="fa fa-flask opacity-50 me-1"></i>
          <span>Use in Playground</span>
        </Link>
      </div>
    </template>
    <template #nav>
      <Link :href="route('prompts.index')" class="breadcrumb-item">Prompts</Link>
      <span class="breadcrumb-item active">{{ prompt.title }}</span>
    </template>
  </BasePageHeading>

  <div class="content">
    <!-- Prompt Details -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Prompt Information</h3>
      </div>
      <div class="block-content">
        <div class="row push">
          <div class="col-lg-4">
            <div class="mb-4">
              <label class="form-label">Title</label>
              <div class="form-control-plaintext">{{ prompt.title }}</div>
            </div>
            <div class="mb-4">
              <label class="form-label">Description</label>
              <div class="form-control-plaintext">{{ prompt.description || 'No description provided' }}</div>
            </div>
            <div class="mb-4">
              <label class="form-label">Tags</label>
              <div>
                <span v-for="(tag, index) in prompt.tags" :key="index" class="badge bg-primary me-1 mb-1">
                  {{ tag }}
                </span>
                <span v-if="!prompt.tags || prompt.tags.length === 0" class="text-muted">No tags</span>
              </div>
            </div>
            <div class="mb-4">
              <label class="form-label">Last Updated</label>
              <div class="form-control-plaintext">{{ formatDate(prompt.updated_at) }}</div>
            </div>
          </div>
          <div class="col-lg-8">
            <div class="mb-4">
              <label class="form-label">Current Version (v{{ currentVersion.version }})</label>
              <div class="form-control-plaintext bg-body-light p-3 rounded" style="white-space: pre-wrap; word-wrap: break-word; overflow-wrap: break-word; word-break: break-all;">
                {{ currentVersion.content }}
              </div>
            </div>
            <div v-if="currentVersion.variables && currentVersion.variables.length > 0" class="mb-4">
              <label class="form-label">Variables</label>
              <div>
                <span v-for="(variable, index) in currentVersion.variables" :key="index" class="badge bg-info me-1 mb-1">
                  {{ variable }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
