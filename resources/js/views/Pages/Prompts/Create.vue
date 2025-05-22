<script setup>
import { ref, watch, onMounted } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { useSortable } from "@/composables/useSortable";
import { usePromptEditor } from "@/composables/usePromptEditor";

const form = useForm({
  title: "",
  description: "",
  content: "",
  tags: [],
  prompt_sections: [
    // Wird vom Composable initialisiert, aber wir können einen Standardwert für den Fall setzen,
    // dass form.content initial leer ist und initSectionsFromContent aufgerufen wird.
    // Das Composable handhabt dies aber bereits.
  ],
});

// Verwende das Composable für die Editor-Logik
const {
  newTag,
  extractedVariables,
  isAiGenerating,
  activeSection,
  initSectionsFromContent, // Wird explizit aufgerufen falls nötig, oder vom Composable beim Setup
  // extractVariables, // Intern im Composable durch Watcher
  addTag,
  handleTagKeydown,
  removeTag,
  addSection,
  removeSection,
  toggleVariable,
  generateImprovedPrompt,
  // updatePromptContent, // Intern im Composable durch Watcher
} = usePromptEditor(form);

// Element references for drag-and-drop
const sectionsContainer = ref(null);

// Setup sortable for the sections
useSortable(sectionsContainer, {
  onEnd: (event) => {
    const newSections = [...form.prompt_sections];
    const [movedItem] = newSections.splice(event.oldIndex, 1);
    newSections.splice(event.newIndex, 0, movedItem);
    form.prompt_sections = newSections;
    // updatePromptContent(); // Nicht mehr nötig, der Watcher im Composable erledigt das
  },
});

// Initialisierung beim Mounten der Komponente
onMounted(() => {
  // Das Composable usePromptEditor initialisiert prompt_sections bereits,
  // wenn form.content initial einen Wert hat oder leer ist.
  // Ein expliziter Aufruf von initSectionsFromContent ist hier nicht unbedingt nötig,
  // es sei denn, man möchte es aus einem anderen Grund erzwingen.
  // Da form.content initial leer ist, stellt das Composable sicher, dass eine leere Sektion existiert.
  // extractVariables(form.content); // Wird durch Watcher im Composable erledigt
});

// Die formatDate Funktion wird in Create.vue nicht direkt verwendet, kann also entfernt werden,
// falls sie nicht für zukünftige Erweiterungen gedacht war.
// const formatDate = (dateString) => { ... };

// Submit the form
const submit = () => {
  // updatePromptContent(); // Nicht mehr nötig, der Watcher im Composable erledigt das
  form.post(route("prompts.store"), {
    onSuccess: () => {
      form.reset(); // Standard-Reset von Inertia form
      // Das Composable initialisiert prompt_sections automatisch, wenn form.content (nach reset) leer ist.
      // extractedVariables.value = []; // Wird durch den Watch auf form.content im Composable zurückgesetzt
    },
  });
};

// Entfernte Funktionen (sind jetzt im Composable usePromptEditor):
// - extractVariables
// - watch(() => form.content, ...)
// - addTag
// - handleTagKeydown
// - removeTag
// - addSection
// - removeSection
// - toggleVariable
// - updatePromptContent
// - watch(() => form.prompt_sections, ...)
// - generateImprovedPrompt
// - improvePromptDemo
// - initSectionsFromContent (wird jetzt vom Composable gehandhabt oder bei Bedarf aufgerufen)
</script>

<template>
  <Head title="Create Prompt" />

  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h2 mb-1">Create Prompt</h1>
        <p class="text-muted">
          Design an AI prompt template with dynamic sections
        </p>
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
            <button
              type="submit"
              class="btn btn-sm btn-alt-primary"
              :disabled="form.processing"
            >
              <i class="fa fa-fw fa-check opacity-50"></i> Save Prompt
            </button>
          </div>
        </div>
        <div class="block-content block-content-full">
          <div class="row items-push">
            <div class="col-lg-4">
              <p class="text-muted">
                Create a prompt template that can be reused in your workflows.
                Add dynamic sections and mark them as variables or use
                <code v-pre>{{ variable_name }}</code> syntax for variables that
                can be dynamically replaced.
              </p>

              <div
                v-if="extractedVariables.length > 0"
                class="block block-rounded bg-body-light"
              >
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
                    These variables will be available for input during prompt
                    testing and workflow execution.
                  </p>
                </div>
              </div>

              <!-- Version Info Block for new users -->
              <div class="block block-rounded bg-body-light mb-4">
                <div class="block-header">
                  <h3 class="block-title fs-sm">
                    <i class="fa fa-fw fa-history me-1"></i> Versioning
                  </h3>
                </div>
                <div class="block-content fs-sm">
                  <p>
                    Your prompt will be versioned automatically. Each time you
                    edit the content, a new version will be created.
                  </p>
                  <p class="mb-0">
                    You'll be able to view and restore previous versions after
                    saving.
                  </p>
                </div>
              </div>

              <!-- Tips Block -->
              <div class="block block-rounded bg-body-light">
                <div class="block-header">
                  <h3 class="block-title fs-sm">
                    <i class="fa fa-fw fa-lightbulb me-1"></i> Prompt
                    Engineering Tips
                  </h3>
                </div>
                <div class="block-content fs-sm">
                  <ul class="mb-0">
                    <li>
                      Be specific about the task you want the AI to perform
                    </li>
                    <li>Define the format you want the output in</li>
                    <li>
                      Provide examples for better results (few-shot learning)
                    </li>
                    <li>Break complex tasks into smaller steps</li>
                    <li>Use variables for dynamic content</li>
                    <li>
                      Use the AI enhancement button to improve your sections
                    </li>
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
                />
                <div v-if="form.errors.title" class="invalid-feedback">
                  {{ form.errors.title }}
                </div>
              </div>

              <!-- Description -->
              <div class="mb-4">
                <label class="form-label" for="prompt-description"
                  >Description (Optional)</label
                >
                <textarea
                  id="prompt-description"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.description }"
                  v-model="form.description"
                  rows="2"
                  placeholder="Describe what this prompt does and how to use it"
                ></textarea>
                <div v-if="form.errors.description" class="invalid-feedback">
                  {{ form.errors.description }}
                </div>
              </div>

              <!-- Tags -->
              <div class="mb-4">
                <label class="form-label" for="prompt-tags"
                  >Tags (Optional)</label
                >
                <div class="d-flex flex-wrap gap-1 mb-2">
                  <span
                    v-for="(tag, index) in form.tags"
                    :key="index"
                    class="fs-sm fw-semibold d-inline-block py-1 px-3 rounded-pill bg-primary-light text-primary"
                  >
                    {{ tag }}
                    <button
                      type="button"
                      class="btn-close btn-close-white fs-sm ms-1"
                      @click="removeTag(index)"
                    ></button>
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
                  />
                  <button
                    type="button"
                    class="btn btn-alt-primary"
                    @click="addTag"
                  >
                    <i class="fa fa-plus me-1"></i> Add
                  </button>
                </div>
                <div class="fs-sm text-muted mt-1">
                  Tags help you organize and find your prompts later.
                </div>
              </div>

              <!-- Dynamic Prompt Sections -->
              <div class="mb-4">
                <div
                  class="d-flex justify-content-between align-items-center mb-2"
                >
                  <label class="form-label mb-0">Prompt Sections</label>
                  <button
                    type="button"
                    class="btn btn-sm btn-alt-success"
                    @click="addSection"
                  >
                    <i class="fa fa-plus me-1"></i> Add Section
                  </button>
                </div>

                <div ref="sectionsContainer" class="sections-container">
                  <div
                    v-for="(section, index) in form.prompt_sections"
                    :key="section.id"
                    class="block block-rounded mb-3 position-relative prompt-section"
                    :class="{ 'block-bordered': section.is_variable }"
                  >
                    <div class="block-header block-header-default">
                      <h3 class="block-title fs-sm">
                        <i
                          class="fa me-1"
                          :class="
                            section.is_variable
                              ? 'fa-code text-info'
                              : 'fa-align-left'
                          "
                        ></i>
                        {{
                          section.is_variable
                            ? `Variable: ${section.variable_name}`
                            : `Section ${index + 1}`
                        }}
                      </h3>
                      <div class="block-options">
                        <!-- Drag handle -->
                        <button
                          type="button"
                          class="btn btn-sm btn-alt-secondary js-tooltip handle"
                          title="Drag to reorder"
                        >
                          <i class="fa fa-fw fa-arrows-alt"></i>
                        </button>

                        <!-- Toggle variable -->
                        <button
                          type="button"
                          class="btn btn-sm"
                          :class="
                            section.is_variable
                              ? 'btn-alt-info'
                              : 'btn-alt-secondary'
                          "
                          @click="toggleVariable(section)"
                          :title="
                            section.is_variable
                              ? 'Convert to text'
                              : 'Convert to variable'
                          "
                        >
                          <i
                            class="fa fa-fw"
                            :class="section.is_variable ? 'fa-font' : 'fa-code'"
                          ></i>
                        </button>

                        <!-- AI enhance button -->
                        <button
                          v-if="!section.is_variable"
                          type="button"
                          class="btn btn-sm btn-alt-warning"
                          @click="generateImprovedPrompt(section)"
                          :disabled="isAiGenerating"
                        >
                          <i
                            class="fa fa-fw"
                            :class="
                              isAiGenerating && activeSection === section
                                ? 'fa-spinner fa-spin'
                                : 'fa-magic'
                            "
                          ></i>
                        </button>

                        <!-- Remove section -->
                        <button
                          type="button"
                          class="btn btn-sm btn-alt-danger"
                          @click="removeSection(index)"
                          :disabled="form.prompt_sections.length <= 1"
                        >
                          <i class="fa fa-fw fa-times"></i>
                        </button>
                      </div>
                    </div>

                    <div class="block-content p-2">
                      <div v-if="section.is_variable" class="mb-2">
                        <div class="input-group input-group-sm">
                          <span class="input-group-text bg-body-light"
                            >Variable Name</span
                          >
                          <input
                            type="text"
                            class="form-control form-control-sm"
                            v-model="section.variable_name"
                            placeholder="Enter variable name"
                          />
                        </div>
                      </div>
                      <textarea
                        v-else
                        class="form-control"
                        v-model="section.content"
                        rows="4"
                        placeholder="Enter prompt text or click the magic wand to enhance with AI"
                      ></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Final Generated Prompt Preview -->
              <div class="mb-4">
                <label class="form-label" for="prompt-content">
                  Final Prompt Preview
                  <small class="text-muted"
                    >(Generated from sections above)</small
                  >
                </label>
                <textarea
                  id="prompt-content"
                  class="form-control bg-body-light"
                  v-model="form.content"
                  rows="6"
                  readonly
                ></textarea>
                <div
                  v-if="form.errors.content"
                  class="invalid-feedback d-block"
                >
                  {{ form.errors.content }}
                </div>
                <div class="fs-sm text-muted mt-1">
                  This is the final prompt that will be saved. It's
                  automatically generated from your sections above.
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
                  <i class="fa fa-fw fa-check opacity-50 me-1"></i> Create
                  Prompt
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<style scoped>
.handle {
  cursor: grab;
}
.handle:active {
  cursor: grabbing;
}
.prompt-section {
  transition: all 0.2s ease;
}
.prompt-section:hover {
  box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.05);
}
</style>
