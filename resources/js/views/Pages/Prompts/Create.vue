// resources/js/Pages/Prompts/Create.vue
<script setup>
import { ref, watch } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

// Pfade korrigieren basierend auf dem, was in anderen Dateien verwendet wird
// Beachte: layouts ist kleingeschrieben in anderen Dateien
import MonacoEditor from '@/Components/MonacoEditor.vue';
// BaseLayout entfernen, da es nicht verwendet wird
// import BasePageHeading aus den Imports entfernen, da es möglicherweise Probleme verursacht

// Initialize form with empty values
const form = useForm({
  title: '',
  description: '',
  content: '',
  tags: [],
});

// For handling tag inputs
const newTag = ref('');
const extractedVariables = ref([]);

// Extract variables from prompt content
const extractVariables = (content) => {
  if (!content) {
    extractedVariables.value = [];
    return;
  }

  const regex = /\{\{(.*?)\}\}/g;
  const matches = [...content.matchAll(regex)];
  extractedVariables.value = [...new Set(matches.map(match => match[1].trim()))];
};

// Watch for changes in content to extract variables
watch(() => form.content, (newContent) => {
  extractVariables(newContent);
});

// Add new tag
const addTag = () => {
  if (newTag.value.trim()) {
    if (!form.tags.includes(newTag.value.trim())) {
      form.tags.push(newTag.value.trim());
    }
    newTag.value = '';
  }
};

// Handle Enter key in tag input
const handleTagKeydown = (e) => {
  if (e.key === 'Enter') {
    e.preventDefault();
    addTag();
  }
};

// Remove a tag
const removeTag = (index) => {
  form.tags.splice(index, 1);
};

// Submit the form
const submit = () => {
  form.post(route('prompts.store'), {
    onSuccess: () => {
      // Reset form after successful submission
      form.reset();
      extractedVariables.value = [];
    },
  });
};
</script>

<template>
  <Head title="Create Prompt" />

  <!-- BasePageHeading durch einen einfachen Div ersetzen -->
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h2 mb-1">Create Prompt</h1>
        <p class="text-muted">Design an AI prompt template</p>
      </div>
      <nav class="breadcrumb push">
        <Link :href="route('dashboard')" class="breadcrumb-item">
          <i class="fa fa-home"></i>
        </Link>
        <Link :href="route('prompts.index')" class="breadcrumb-item">
          Prompts
        </Link>
        <span class="breadcrumb-item active">Create</span>
      </nav>
    </div>

    <!-- Create Prompt Form -->
    <form @submit.prevent="submit">
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Prompt Details</h3>
          <div class="block-options">
            <button type="submit" class="btn btn-sm btn-alt-primary" :disabled="form.processing">
              <i class="fa fa-fw fa-check opacity-50"></i> Save Prompt
            </button>
          </div>
        </div>
        <div class="block-content block-content-full">
          <div class="row items-push">
            <div class="col-lg-4">
              <p class="text-muted">
                Create a prompt template that can be reused in your workflows. Use <code>{{variable_name}}</code> syntax for variables that can be dynamically replaced.
              </p>

              <div v-if="extractedVariables.length > 0" class="block block-rounded bg-body-light">
                <div class="block-header">
                  <h3 class="block-title fs-sm">
                    <i class="fa fa-fw fa-code me-1"></i> Detected Variables
                  </h3>
                </div>
                <div class="block-content">
                  <div class="d-flex flex-wrap gap-1 mb-2">
                    <span
                      v-for="variable in extractedVariables"
                      :key="variable"
                      class="fs-sm fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success"
                    >
                      {{ variable }}
                    </span>
                  </div>
                  <p class="fs-sm text-muted mb-0">
                    These variables will be available for input during prompt testing and workflow execution.
                  </p>
                </div>
              </div>

              <!-- Tips Block -->
              <div class="block block-rounded bg-body-light">
                <div class="block-header">
                  <h3 class="block-title fs-sm">
                    <i class="fa fa-fw fa-lightbulb me-1"></i> Prompt Engineering Tips
                  </h3>
                </div>
                <div class="block-content fs-sm">
                  <ul class="mb-0">
                    <li>Be specific about the task you want the AI to perform</li>
                    <li>Define the format you want the output in</li>
                    <li>Provide examples for better results (few-shot learning)</li>
                    <li>Break complex tasks into smaller steps</li>
                    <li>Use variables for dynamic content</li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="col-lg-8">
              <!-- Title -->
              <div class="mb-4">
                <label class="form-label" for="prompt-title">Title</label>
                <input
                  type="text"
                  id="prompt-title"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.title }"
                  v-model="form.title"
                  placeholder="Give your prompt a descriptive name"
                >
                <div v-if="form.errors.title" class="invalid-feedback">{{ form.errors.title }}</div>
              </div>

              <!-- Description -->
              <div class="mb-4">
                <label class="form-label" for="prompt-description">Description (Optional)</label>
                <textarea
                  id="prompt-description"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.description }"
                  v-model="form.description"
                  rows="3"
                  placeholder="Describe what this prompt does and how to use it"
                ></textarea>
                <div v-if="form.errors.description" class="invalid-feedback">{{ form.errors.description }}</div>
              </div>

              <!-- Tags -->
              <div class="mb-4">
                <label class="form-label" for="prompt-tags">Tags (Optional)</label>
                <div class="d-flex flex-wrap gap-1 mb-2">
                  <span
                    v-for="(tag, index) in form.tags"
                    :key="index"
                    class="fs-sm fw-semibold d-inline-block py-1 px-3 rounded-pill bg-primary-light text-primary"
                  >
                    {{ tag }}
                    <button type="button" class="btn-close btn-close-white fs-sm ms-1" @click="removeTag(index)"></button>
                  </span>
                </div>
                <div class="input-group">
                  <input
                    type="text"
                    id="prompt-tags"
                    class="form-control"
                    v-model="newTag"
                    @keydown="handleTagKeydown"
                    placeholder="Add a tag and press Enter"
                  >
                  <button type="button" class="btn btn-alt-primary" @click="addTag">
                    <i class="fa fa-plus me-1"></i> Add
                  </button>
                </div>
                <div class="fs-sm text-muted mt-1">
                  Tags help you organize and find your prompts later.
                </div>
              </div>

              <!-- Content - Text Area statt MonacoEditor -->
              <div class="mb-4">
                <label class="form-label" for="prompt-content">Prompt Content</label>
                <textarea
                  id="prompt-content"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.content }"
                  v-model="form.content"
                  rows="10"
                  placeholder="Enter your prompt template here..."
                ></textarea>
                <div v-if="form.errors.content" class="invalid-feedback">{{ form.errors.content }}</div>
                <div class="fs-sm text-muted mt-1">
                  Use <code>{{variable_name}}</code> syntax to define variables that can be replaced when using this prompt.
                </div>
              </div>

              <!-- Submit -->
              <div class="d-flex justify-content-end">
                <Link
                  :href="route('prompts.index')"
                  class="btn btn-alt-secondary me-1"
                  :disabled="form.processing"
                >
                  Cancel
                </Link>
                <button
                  type="submit"
                  class="btn btn-alt-primary"
                  :disabled="form.processing"
                >
                  <i class="fa fa-fw fa-check opacity-50 me-1"></i> Create Prompt
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
