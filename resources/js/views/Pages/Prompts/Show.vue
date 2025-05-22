<!--<script setup>-->
<!--import { ref, onMounted } from 'vue';-->
<!--import { Head, Link, router } from '@inertiajs/vue3';-->
<!--import { route } from 'ziggy-js';-->

<!--const props = defineProps({-->
<!--  prompt: {-->
<!--    type: Object,-->
<!--    required: true-->
<!--  },-->
<!--  currentVersion: {-->
<!--    type: Object,-->
<!--    required: true-->
<!--  }-->
<!--});-->

<!--// Version management-->
<!--const versions = ref([]);-->
<!--const isLoadingVersions = ref(false);-->
<!--const versionPreview = ref(null);-->
<!--const showVersionModal = ref(false);-->
<!--const isPreviewingVersion = ref(false);-->

<!--// Load versions on component mount-->
<!--// Korrigierte onMounted-Funktion für Show.vue-->
<!--onMounted(async () => {-->
<!--  // Hier wird nicht initSectionsFromContent aufgerufen, da diese Funktion in Show.vue nicht existiert-->
<!--  await loadVersions();-->

<!--  // Einfache Modal-Initialisierung-->
<!--  setTimeout(() => {-->
<!--    try {-->
<!--      const modalElement = document.getElementById('modal-version-preview');-->
<!--      if (modalElement) {-->
<!--        // Verwende Bootstrap direkt statt über ref-->
<!--        const modalInstance = new bootstrap.Modal(modalElement);-->

<!--        // Speichere die Instanz für späteren Zugriff-->
<!--        versionModal.value = modalInstance;-->

<!--        // Event-Listener für Modal-Schließen-->
<!--        modalElement.addEventListener('hidden.bs.modal', () => {-->
<!--          showVersionModal.value = false;-->
<!--        });-->
<!--      }-->
<!--    } catch (e) {-->
<!--      console.error('Fehler bei Modal-Initialisierung:', e);-->
<!--    }-->
<!--  }, 500);-->
<!--});-->

<!--// Füge diese Variable hinzu, um die Modal-Instanz zu speichern-->
<!--const versionModal = ref(null);-->

<!--// Load all versions for the prompt-->
<!--const loadVersions = async () => {-->
<!--  isLoadingVersions.value = true;-->

<!--  try {-->
<!--    const response = await fetch(route('prompts.versions', props.prompt.id));-->
<!--    if (response.ok) {-->
<!--      const data = await response.json();-->
<!--      versions.value = data.versions;-->
<!--    } else {-->
<!--      console.error('Failed to load versions');-->
<!--    }-->
<!--  } catch (error) {-->
<!--    console.error('Error loading versions:', error);-->
<!--  } finally {-->
<!--    isLoadingVersions.value = false;-->
<!--  }-->
<!--};-->

<!--// Preview a specific version-->
<!--const previewVersion = async (versionId) => {-->
<!--  if (!versionId) return;-->

<!--  isPreviewingVersion.value = true;-->

<!--  try {-->
<!--    const response = await fetch(route('prompts.versions.preview', {-->
<!--      prompt: props.prompt.id,-->
<!--      version: versionId-->
<!--    }));-->

<!--    if (response.ok) {-->
<!--      const data = await response.json();-->
<!--      versionPreview.value = data;-->

<!--      // Direkt das Modal öffnen statt über showVersionModal-->
<!--      if (versionModal.value) {-->
<!--        versionModal.value.show();-->
<!--      }-->
<!--    } else {-->
<!--      console.error('Failed to preview version');-->
<!--    }-->
<!--  } catch (error) {-->
<!--    console.error('Error previewing version:', error);-->
<!--  } finally {-->
<!--    isPreviewingVersion.value = false;-->
<!--  }-->
<!--};-->

<!--// Set a specific version as current-->
<!--// const setVersionAsCurrent = (versionId) => {-->
<!--//   if (!confirm('Möchtest du diese Version aktivieren? Nicht gespeicherte Änderungen gehen verloren.')) {-->
<!--//     return;-->
<!--//   }-->
<!--//-->
<!--//   // Die Version-ID muss explizit mitgegeben werden-->
<!--//   const params = {-->
<!--//     prompt: props.prompt.id,-->
<!--//     version: versionId-->
<!--//   };-->
<!--//-->
<!--//   // Wir verwenden hier die Form-Submit-Methode, da sie zuverlässiger ist-->
<!--//   const form = document.createElement('form');-->
<!--//   form.method = 'POST';-->
<!--//   form.action = route('prompts.versions.set-current', params);-->
<!--//-->
<!--//   // CSRF-Token hinzufügen-->
<!--//   const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');-->
<!--//   const csrfInput = document.createElement('input');-->
<!--//   csrfInput.type = 'hidden';-->
<!--//   csrfInput.name = '_token';-->
<!--//   csrfInput.value = csrfToken;-->
<!--//   form.appendChild(csrfInput);-->
<!--//-->
<!--//   // Method-Spoofing für Laravel-->
<!--//   const methodInput = document.createElement('input');-->
<!--//   methodInput.type = 'hidden';-->
<!--//   methodInput.name = '_method';-->
<!--//   methodInput.value = 'POST';-->
<!--//   form.appendChild(methodInput);-->
<!--//-->
<!--//   // Formular zum DOM hinzufügen und absenden-->
<!--//   document.body.appendChild(form);-->
<!--//   form.submit();-->
<!--// };-->

<!--// const setVersionAsCurrent = (versionId) => {-->
<!--//   if (!confirm('Möchtest du diese Version aktivieren? Nicht gespeicherte Änderungen gehen verloren.')) {-->
<!--//     return;-->
<!--//   }-->
<!--//-->
<!--//   router.post(route('prompts.versions.set-current', {-->
<!--//     prompt: props.prompt.id,-->
<!--//     version: versionId-->
<!--//   })).then(() => {-->
<!--//     // Nach erfolgreichem Request die Seite neu laden-->
<!--//     router.reload();-->
<!--//   }).catch(error => {-->
<!--//     console.error('Fehler beim Setzen der Version:', error);-->
<!--//   });-->
<!--// };-->

<!--// const setVersionAsCurrent = (versionId) => {-->
<!--//   if (!confirm('Möchtest du diese Version aktivieren? Nicht gespeicherte Änderungen gehen verloren.')) {-->
<!--//     return;-->
<!--//   }-->
<!--//-->
<!--//   const params = {-->
<!--//     prompt: props.prompt.id,-->
<!--//     version: versionId-->
<!--//   };-->
<!--//-->
<!--//   // Erzeuge die URL mithilfe der route()-Funktion-->
<!--//   const url = route('prompts.versions.set-current', params);-->
<!--//-->
<!--//   // Führe den POST-Request per Fetch aus-->
<!--//   fetch(url, {-->
<!--//     method: 'POST',-->
<!--//     headers: {-->
<!--//       'Content-Type': 'application/json',-->
<!--//       'Accept': 'application/json',-->
<!--//       'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')-->
<!--//     }-->
<!--//   })-->
<!--//     .then(response => {-->
<!--//       if (response.ok) {-->
<!--//         router.reload();-->
<!--//       } else {-->
<!--//         return response.json().then(data => {-->
<!--//           console.error('Fehler beim Setzen der Version:', data);-->
<!--//         });-->
<!--//       }-->
<!--//     })-->
<!--//     .catch(error => {-->
<!--//       console.error('Fehler beim Setzen der Version:', error);-->
<!--//     });-->
<!--// };-->

<!--// const setVersionAsCurrent = (versionId) => {-->
<!--//   if (!confirm('Möchtest du diese Version aktivieren? Nicht gespeicherte Änderungen gehen verloren.')) {-->
<!--//     return;-->
<!--//   }-->
<!--//-->
<!--//   const params = {-->
<!--//     prompt: props.prompt.id,-->
<!--//     version: versionId-->
<!--//   };-->
<!--//-->
<!--//   // Methode 1: Mit Axios-->
<!--//   // Axios setzt automatisch den X-XSRF-TOKEN aus den Cookies-->
<!--//   axios.post(route('prompts.versions.set-current', params))-->
<!--//     .then(() => {-->
<!--//       window.location.reload(); // Seite neu laden-->
<!--//     })-->
<!--//     .catch(error => {-->
<!--//       console.error('Fehler beim Setzen der Version:', error);-->
<!--//     });-->
<!--//-->
<!--//   // ODER Methode 2: Mit Formular-Submit (keine CSRF-Token-Abfrage nötig)-->
<!--//   // const form = document.createElement('form');-->
<!--//   // form.method = 'POST';-->
<!--//   // form.action = route('prompts.versions.set-current', params);-->
<!--//   //-->
<!--//   // // Method-Spoofing für Laravel-->
<!--//   // const methodInput = document.createElement('input');-->
<!--//   // methodInput.type = 'hidden';-->
<!--//   // methodInput.name = '_method';-->
<!--//   // methodInput.value = 'POST';-->
<!--//   // form.appendChild(methodInput);-->
<!--//   //-->
<!--//   // // Formular zum DOM hinzufügen und absenden-->
<!--//   // document.body.appendChild(form);-->
<!--//   // form.submit();-->
<!--// };-->

<!--// const setVersionAsCurrent = (versionId) => {-->
<!--//   if (!confirm('Möchtest du diese Version aktivieren? Nicht gespeicherte Änderungen gehen verloren.')) {-->
<!--//     return;-->
<!--//   }-->
<!--//-->
<!--//   // Stelle sicher, dass beide Parameter explizit übergeben werden-->
<!--//   router.post(route('prompts.versions.set-current', {-->
<!--//     prompt: props.prompt.id,-->
<!--//     version: versionId  // Dies ist der fehlende Parameter-->
<!--//   }));-->
<!--// };-->

<!--const confirmSetAsCurrent = (e) => {-->
<!--  if (!confirm('Möchtest du diese Version aktivieren? Nicht gespeicherte Änderungen gehen verloren.')) {-->
<!--    e.preventDefault();-->
<!--  }-->
<!--};-->

<!--// Format date to localized format-->
<!--const formatDate = (dateString) => {-->
<!--  if (!dateString) return '';-->

<!--  const date = new Date(dateString);-->
<!--  return new Intl.DateTimeFormat('de-DE', {-->
<!--    day: '2-digit',-->
<!--    month: '2-digit',-->
<!--    year: 'numeric',-->
<!--    hour: '2-digit',-->
<!--    minute: '2-digit'-->
<!--  }).format(date);-->
<!--};-->
<!--</script>-->

<!--<template>-->
<!--  <Head :title="prompt.title" />-->

<!--  <BasePageHeading :title="prompt.title" :subtitle="prompt.description || 'Prompt Details'">-->
<!--    <template #extra>-->
<!--      <div class="d-flex">-->
<!--        &lt;!&ndash; Version Dropdown &ndash;&gt;-->
<!--        <div class="dropdown d-inline-block me-1">-->
<!--          <button type="button" class="btn btn-alt-secondary" id="versions-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--            <i class="fa fa-history me-1"></i> Versionen-->
<!--            <span class="badge rounded-pill bg-primary ms-1">{{ versions.length }}</span>-->
<!--          </button>-->
<!--          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="versions-dropdown">-->
<!--            <h6 class="dropdown-header">Gespeicherte Versionen</h6>-->
<!--            <div v-if="isLoadingVersions" class="dropdown-item">-->
<!--              <div class="d-flex align-items-center">-->
<!--                <span class="spinner-border spinner-border-sm me-2" role="status"></span>-->
<!--                <span>Lade Versionen...</span>-->
<!--              </div>-->
<!--            </div>-->
<!--            <div v-else-if="versions.length === 0" class="dropdown-item">-->
<!--              Keine Versionen gefunden-->
<!--            </div>-->
<!--            <template v-else>-->
<!--              <div v-for="version in versions" :key="version.id" class="dropdown-item">-->
<!--                <div class="d-flex justify-content-between align-items-center">-->
<!--                  <div>-->
<!--                    <span class="fw-semibold">Version {{ version.version }}</span>-->
<!--                    <span v-if="version.is_current" class="badge rounded-pill bg-primary ms-1">Aktuell</span>-->
<!--                    <br>-->
<!--                    <small class="text-muted">{{ formatDate(version.created_at) }}</small>-->
<!--                  </div>-->
<!--                  <div>-->
<!--                    <button-->
<!--                      type="button"-->
<!--                      class="btn btn-sm btn-alt-info me-1"-->
<!--                      @click.prevent="previewVersion(version.id)"-->
<!--                      title="Vorschau"-->
<!--                    >-->
<!--                      <i class="fa fa-eye"></i>-->
<!--                    </button>-->
<!--                    <button-->
<!--                      v-if="!version.is_current"-->
<!--                      type="button"-->
<!--                      class="btn btn-sm btn-alt-success"-->
<!--                      @click.prevent="setVersionAsCurrent(version.id)"-->
<!--                      title="Aktivieren"-->
<!--                    >-->
<!--                      <i class="fa fa-check"></i>-->
<!--                    </button>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
<!--            </template>-->
<!--          </div>-->
<!--        </div>-->

<!--        <Link :href="route('prompts.edit', prompt.id)" class="btn btn-alt-primary me-1" v-click-ripple>-->
<!--          <i class="fa fa-edit opacity-50 me-1"></i>-->
<!--          <span>Edit Prompt</span>-->
<!--        </Link>-->
<!--        <Link :href="route('playground.show', prompt.id)" class="btn btn-alt-success" v-click-ripple>-->
<!--          <i class="fa fa-flask opacity-50 me-1"></i>-->
<!--          <span>Use in Playground</span>-->
<!--        </Link>-->
<!--      </div>-->
<!--    </template>-->
<!--    <template #nav>-->
<!--      <Link :href="route('prompts.index')" class="breadcrumb-item">Prompts</Link>-->
<!--      <span class="breadcrumb-item active">{{ prompt.title }}</span>-->
<!--    </template>-->
<!--  </BasePageHeading>-->

<!--  <div class="content">-->
<!--    &lt;!&ndash; Prompt Details &ndash;&gt;-->
<!--    <div class="block block-rounded">-->
<!--      <div class="block-header block-header-default">-->
<!--        <h3 class="block-title">-->
<!--          Prompt Information-->
<!--          <span v-if="currentVersion" class="badge bg-primary ms-2">-->
<!--            Version {{ currentVersion.version }}-->
<!--          </span>-->
<!--        </h3>-->
<!--      </div>-->
<!--      <div class="block-content">-->
<!--        <div class="row push">-->
<!--          <div class="col-lg-4">-->
<!--            <div class="mb-4">-->
<!--              <label class="form-label">Title</label>-->
<!--              <div class="form-control-plaintext">{{ prompt.title }}</div>-->
<!--            </div>-->
<!--            <div class="mb-4">-->
<!--              <label class="form-label">Description</label>-->
<!--              <div class="form-control-plaintext">{{ prompt.description || 'No description provided' }}</div>-->
<!--            </div>-->
<!--            <div class="mb-4">-->
<!--              <label class="form-label">Tags</label>-->
<!--              <div>-->
<!--                <span v-for="(tag, index) in prompt.tags" :key="index" class="badge bg-primary me-1 mb-1">-->
<!--                  {{ tag }}-->
<!--                </span>-->
<!--                <span v-if="!prompt.tags || prompt.tags.length === 0" class="text-muted">No tags</span>-->
<!--              </div>-->
<!--            </div>-->
<!--            <div class="mb-4">-->
<!--              <label class="form-label">Last Updated</label>-->
<!--              <div class="form-control-plaintext">{{ formatDate(prompt.updated_at) }}</div>-->
<!--            </div>-->

<!--            &lt;!&ndash; Version History &ndash;&gt;-->
<!--            <div class="mb-4">-->
<!--              <label class="form-label">Version History</label>-->
<!--              <div v-if="isLoadingVersions" class="text-center py-2">-->
<!--                <div class="spinner-border spinner-border-sm" role="status"></div>-->
<!--                <span class="ms-2">Lade Versionen...</span>-->
<!--              </div>-->
<!--              <div v-else-if="versions.length === 0" class="text-center py-2">-->
<!--                <span class="text-muted">Keine Versionen gefunden</span>-->
<!--              </div>-->
<!--              <div v-else class="list-group">-->
<!--                <button-->
<!--                  v-for="version in versions"-->
<!--                  :key="version.id"-->
<!--                  type="button"-->
<!--                  class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"-->
<!--                  :class="{ 'active': version.is_current }"-->
<!--                  @click="previewVersion(version.id)"-->
<!--                >-->
<!--                  <div>-->
<!--                    <span class="fw-semibold">Version {{ version.version }}</span>-->
<!--                    <small class="d-block">{{ formatDate(version.created_at) }}</small>-->
<!--                  </div>-->
<!--                  <div>-->
<!--                    <span v-if="version.is_current" class="badge rounded-pill bg-light text-primary">Aktuell</span>-->
<!--                    <i v-else class="fa fa-eye"></i>-->
<!--                  </div>-->
<!--                </button>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="col-lg-8">-->
<!--            <div class="mb-4">-->
<!--              <label class="form-label">Current Version (v{{ currentVersion.version }})</label>-->
<!--              <div class="form-control-plaintext bg-body-light p-3 rounded" style="white-space: pre-wrap; word-wrap: break-word; overflow-wrap: break-word; word-break: break-all;">-->
<!--                {{ currentVersion.content }}-->
<!--              </div>-->
<!--            </div>-->
<!--            <div v-if="currentVersion.variables && currentVersion.variables.length > 0" class="mb-4">-->
<!--              <label class="form-label">Variables</label>-->
<!--              <div>-->
<!--                <span v-for="(variable, index) in currentVersion.variables" :key="index" class="badge bg-info me-1 mb-1">-->
<!--                  {{ variable }}-->
<!--                </span>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->

<!--  &lt;!&ndash; Version Preview Modal &ndash;&gt;-->
<!--  <div class="modal fade" id="modal-version-preview" tabindex="-1" role="dialog" aria-labelledby="modal-version-preview" aria-hidden="true">-->
<!--    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">-->
<!--      <div class="modal-content">-->
<!--        <div class="block block-rounded block-themed block-transparent mb-0">-->
<!--          <div class="block-header bg-primary">-->
<!--            <h3 class="block-title" v-if="versionPreview">-->
<!--              Version {{ versionPreview.version }} Vorschau-->
<!--              <small class="text-white-75 ms-1">-->
<!--                {{ formatDate(versionPreview.created_at) }}-->
<!--              </small>-->
<!--            </h3>-->
<!--            <div class="block-options">-->
<!--              <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">-->
<!--                <i class="fa fa-fw fa-times"></i>-->
<!--              </button>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="block-content fs-sm" v-if="versionPreview">-->
<!--            <div class="mb-4">-->
<!--              <h4 class="h5">Prompt Inhalt</h4>-->
<!--              <div class="form-control-plaintext bg-body-light p-3 rounded" style="white-space: pre-wrap; word-wrap: break-word; overflow-wrap: break-word;">-->
<!--                {{ versionPreview.content }}-->
<!--              </div>-->
<!--            </div>-->
<!--            <div v-if="versionPreview.variables && versionPreview.variables.length > 0">-->
<!--              <h4 class="h5">Erkannte Variablen</h4>-->
<!--              <div class="d-flex flex-wrap gap-1">-->
<!--                <span-->
<!--                  v-for="variable in versionPreview.variables"-->
<!--                  :key="variable"-->
<!--                  class="fs-sm fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success"-->
<!--                >-->
<!--                  {{ variable }}-->
<!--                </span>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="block-content block-content-full text-end bg-body">-->
<!--            <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">-->
<!--              Schließen-->
<!--            </button>-->
<!--&lt;!&ndash;            <button&ndash;&gt;-->
<!--&lt;!&ndash;              v-if="versionPreview && !versions.find(v => v.id === versionPreview.id)?.is_current"&ndash;&gt;-->
<!--&lt;!&ndash;              type="button"&ndash;&gt;-->
<!--&lt;!&ndash;              class="btn btn-sm btn-primary"&ndash;&gt;-->
<!--&lt;!&ndash;              @click="setVersionAsCurrent(versionPreview.id)"&ndash;&gt;-->
<!--&lt;!&ndash;            >&ndash;&gt;-->
<!--&lt;!&ndash;              <i class="fa fa-fw fa-check me-1"></i> Diese Version verwenden&ndash;&gt;-->
<!--&lt;!&ndash;            </button>&ndash;&gt;-->
<!--            <Link-->
<!--              :href="`/prompts/${prompt.id}/versions/${version.id}/set-current`"-->
<!--              method="post"-->
<!--              as="button"-->
<!--              class="btn btn-sm btn-primary"-->
<!--              @click.prevent="confirmSetAsCurrent"-->
<!--              :data="{}"-->
<!--            >-->
<!--              Als aktuelle Version setzen-->
<!--            </Link>-->

<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->
<!--</template>-->

<script setup>
import { ref, onMounted } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
// Entfernen von Ziggy-Import, da wir es nicht verwenden wollen

const props = defineProps({
  prompt: {
    type: Object,
    required: true,
  },
  currentVersion: {
    type: Object,
    required: true,
  },
});

// Version management
const versions = ref([]);
const isLoadingVersions = ref(false);
const versionPreview = ref(null);
const showVersionModal = ref(false);
const isPreviewingVersion = ref(false);

// Load versions on component mount
// Korrigierte onMounted-Funktion für Show.vue
onMounted(async () => {
  // Hier wird nicht initSectionsFromContent aufgerufen, da diese Funktion in Show.vue nicht existiert
  await loadVersions();

  // Einfache Modal-Initialisierung
  setTimeout(() => {
    try {
      const modalElement = document.getElementById("modal-version-preview");
      if (modalElement) {
        // Verwende Bootstrap direkt statt über ref
        const modalInstance = new bootstrap.Modal(modalElement);

        // Speichere die Instanz für späteren Zugriff
        versionModal.value = modalInstance;

        // Event-Listener für Modal-Schließen
        modalElement.addEventListener("hidden.bs.modal", () => {
          showVersionModal.value = false;
        });
      }
    } catch (e) {
      console.error("Fehler bei Modal-Initialisierung:", e);
    }
  }, 500);
});

// Füge diese Variable hinzu, um die Modal-Instanz zu speichern
const versionModal = ref(null);

// Load all versions for the prompt
const loadVersions = async () => {
  isLoadingVersions.value = true;

  try {
    // URLs direkt konstruieren anstatt route() zu verwenden
    const response = await fetch(`/prompts/${props.prompt.id}/versions`);
    if (response.ok) {
      const data = await response.json();
      versions.value = data.versions;
    } else {
      console.error("Failed to load versions");
    }
  } catch (error) {
    console.error("Error loading versions:", error);
  } finally {
    isLoadingVersions.value = false;
  }
};

// Preview a specific version
const previewVersion = async (versionId) => {
  console.log("[Show.vue] previewVersion aufgerufen mit versionId:", versionId);
  if (!versionId) return;

  isPreviewingVersion.value = true;

  try {
    // URLs direkt konstruieren anstatt route() zu verwenden
    const response = await fetch(
      `/prompts/${props.prompt.id}/versions/${versionId}/preview`,
    );

    if (response.ok) {
      const data = await response.json();
      console.log("[Show.vue] Daten von /preview Endpunkt:", data);
      versionPreview.value = data;
      console.log(
        "[Show.vue] versionPreview.value gesetzt:",
        versionPreview.value,
      );

      // Direkt das Modal öffnen statt über showVersionModal
      if (versionModal.value) {
        versionModal.value.show();
      }
    } else {
      console.error(
        "[Show.vue] Failed to preview version. Status:",
        response.status,
      );
    }
  } catch (error) {
    console.error("[Show.vue] Error previewing version:", error);
  } finally {
    isPreviewingVersion.value = false;
  }
};

// Implementierung mit direkter URL ohne Ziggy
const setVersionAsCurrent = (versionId) => {
  console.log(
    "[Show.vue] setVersionAsCurrent aufgerufen mit versionId:",
    versionId,
  );
  if (!versionId) {
    console.error("Keine Versions-ID angegeben");
    return;
  }

  if (
    !confirm(
      "Möchtest du diese Version aktivieren? Nicht gespeicherte Änderungen gehen verloren.",
    )
  ) {
    return;
  }

  router.post(
    `/prompts/${props.prompt.id}/versions/${versionId}/set-current`,
    {}, // Keine zusätzlichen Daten nötig für diesen POST Request
    {
      preserveState: false, // Wichtig: Lädt Props neu, aktualisiert currentVersion
      preserveScroll: true, // Behält die Scroll-Position bei
      onSuccess: () => {
        console.log("[Show.vue] setVersionAsCurrent onSuccess erreicht.");
        // props.currentVersion und props.prompt sollten durch Inertia aktualisiert worden sein.
        // Die Hauptansicht (currentVersion.content etc.) aktualisiert sich reaktiv.

        loadVersions(); // Lädt die Versionsliste neu, um is_current in der Seitenleiste zu aktualisieren

        // Modal schließen
        if (
          versionModal.value &&
          typeof versionModal.value.hide === "function"
        ) {
          console.log("[Show.vue] Schließe Modal.");
          versionModal.value.hide();
        }
        showVersionModal.value = false; // Zusätzlicher Reset für den Modal-Trigger-State
      },
      onError: (errors) => {
        console.error(
          "[Show.vue] Fehler beim Setzen der Version via Inertia:",
          errors,
        );
        // Hier könnte man dem Benutzer eine Fehlermeldung anzeigen
      },
    },
  );
};

const confirmSetAsCurrent = (e) => {
  if (
    !confirm(
      "Möchtest du diese Version aktivieren? Nicht gespeicherte Änderungen gehen verloren.",
    )
  ) {
    e.preventDefault();
  }
};

// Format date to localized format
const formatDate = (dateString) => {
  if (!dateString) return "";

  const date = new Date(dateString);
  return new Intl.DateTimeFormat("de-DE", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  }).format(date);
};
</script>

<template>
  <Head :title="prompt.title" />

  <BasePageHeading
    :title="prompt.title"
    :subtitle="prompt.description || 'Prompt Details'"
  >
    <template #extra>
      <div class="d-flex">
        <!-- Version Dropdown -->
        <div class="dropdown d-inline-block me-1">
          <button
            type="button"
            class="btn btn-alt-secondary"
            id="versions-dropdown"
            data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            <i class="fa fa-history me-1"></i> Versionen
            <span class="badge rounded-pill bg-primary ms-1">{{
              versions.length
            }}</span>
          </button>
          <div
            class="dropdown-menu dropdown-menu-end"
            aria-labelledby="versions-dropdown"
          >
            <h6 class="dropdown-header">Gespeicherte Versionen</h6>
            <div v-if="isLoadingVersions" class="dropdown-item">
              <div class="d-flex align-items-center">
                <span
                  class="spinner-border spinner-border-sm me-2"
                  role="status"
                ></span>
                <span>Lade Versionen...</span>
              </div>
            </div>
            <div v-else-if="versions.length === 0" class="dropdown-item">
              Keine Versionen gefunden
            </div>
            <template v-else>
              <div
                v-for="version in versions"
                :key="version.id"
                class="dropdown-item"
              >
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <span class="fw-semibold"
                      >Version {{ version.version }}</span
                    >
                    <span
                      v-if="version.is_current"
                      class="badge rounded-pill bg-primary ms-1"
                      >Aktuell</span
                    >
                    <br />
                    <small class="text-muted">{{
                      formatDate(version.created_at)
                    }}</small>
                  </div>
                  <div>
                    <button
                      type="button"
                      class="btn btn-sm btn-alt-info me-1"
                      @click.prevent="previewVersion(version.id)"
                      title="Vorschau"
                    >
                      <i class="fa fa-eye"></i>
                    </button>
                    <button
                      v-if="!version.is_current"
                      type="button"
                      class="btn btn-sm btn-alt-success"
                      @click.prevent="setVersionAsCurrent(version.id)"
                      title="Aktivieren"
                    >
                      <i class="fa fa-check"></i>
                    </button>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>

        <!-- Hier URLs direkt konstruieren anstatt route() zu verwenden -->
        <Link
          :href="`/prompts/${prompt.id}/edit`"
          class="btn btn-alt-primary me-1"
          v-click-ripple
        >
          <i class="fa fa-edit opacity-50 me-1"></i>
          <span>Edit Prompt</span>
        </Link>
        <Link
          :href="`/playground/${prompt.id}`"
          class="btn btn-alt-success"
          v-click-ripple
        >
          <i class="fa fa-flask opacity-50 me-1"></i>
          <span>Use in Playground</span>
        </Link>
      </div>
    </template>
    <template #nav>
      <!-- Hier URLs direkt konstruieren anstatt route() zu verwenden -->
      <Link href="/prompts" class="breadcrumb-item">Prompts</Link>
      <span class="breadcrumb-item active">{{ prompt.title }}</span>
    </template>
  </BasePageHeading>

  <div class="content">
    <!-- Prompt Details -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">
          Prompt Information
          <span v-if="currentVersion" class="badge bg-primary ms-2">
            Version {{ currentVersion.version }}
          </span>
        </h3>
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
              <div class="form-control-plaintext">
                {{ prompt.description || "No description provided" }}
              </div>
            </div>
            <div class="mb-4">
              <label class="form-label">Tags</label>
              <div>
                <span
                  v-for="(tag, index) in prompt.tags"
                  :key="index"
                  class="badge bg-primary me-1 mb-1"
                >
                  {{ tag }}
                </span>
                <span
                  v-if="!prompt.tags || prompt.tags.length === 0"
                  class="text-muted"
                  >No tags</span
                >
              </div>
            </div>
            <div class="mb-4">
              <label class="form-label">Last Updated</label>
              <div class="form-control-plaintext">
                {{ formatDate(prompt.updated_at) }}
              </div>
            </div>

            <!-- Version History -->
            <div class="mb-4">
              <label class="form-label">Version History</label>
              <div v-if="isLoadingVersions" class="text-center py-2">
                <div
                  class="spinner-border spinner-border-sm"
                  role="status"
                ></div>
                <span class="ms-2">Lade Versionen...</span>
              </div>
              <div v-else-if="versions.length === 0" class="text-center py-2">
                <span class="text-muted">Keine Versionen gefunden</span>
              </div>
              <div v-else class="list-group">
                <button
                  v-for="version in versions"
                  :key="version.id"
                  type="button"
                  class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                  :class="{ active: version.is_current }"
                  @click="previewVersion(version.id)"
                >
                  <div>
                    <span class="fw-semibold"
                      >Version {{ version.version }}</span
                    >
                    <small class="d-block">{{
                      formatDate(version.created_at)
                    }}</small>
                  </div>
                  <div>
                    <span
                      v-if="version.is_current"
                      class="badge rounded-pill bg-light text-primary"
                      >Aktuell</span
                    >
                    <i v-else class="fa fa-eye"></i>
                  </div>
                </button>
              </div>
            </div>
          </div>
          <div class="col-lg-8">
            <div class="mb-4">
              <label class="form-label"
                >Current Version (v{{ currentVersion.version }})</label
              >
              <div
                class="form-control-plaintext bg-body-light p-3 rounded"
                style="
                  white-space: pre-wrap;
                  word-wrap: break-word;
                  overflow-wrap: break-word;
                  word-break: break-all;
                "
              >
                {{ currentVersion.content }}
              </div>
            </div>
            <div
              v-if="
                currentVersion.variables && currentVersion.variables.length > 0
              "
              class="mb-4"
            >
              <label class="form-label">Variables</label>
              <div>
                <span
                  v-for="(variable, index) in currentVersion.variables"
                  :key="index"
                  class="badge bg-info me-1 mb-1"
                >
                  {{ variable }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Version Preview Modal -->
  <div
    class="modal fade"
    id="modal-version-preview"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modal-version-preview"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="block block-rounded block-themed block-transparent mb-0">
          <div class="block-header bg-primary">
            <h3 class="block-title" v-if="versionPreview">
              Version {{ versionPreview.version }} Vorschau
              <small class="text-white-75 ms-1">
                {{ formatDate(versionPreview.created_at) }}
              </small>
            </h3>
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
          <div class="block-content fs-sm" v-if="versionPreview">
            <div class="mb-4">
              <h4 class="h5">Prompt Inhalt</h4>
              <div
                class="form-control-plaintext bg-body-light p-3 rounded"
                style="
                  white-space: pre-wrap;
                  word-wrap: break-word;
                  overflow-wrap: break-word;
                "
              >
                {{ versionPreview.content }}
              </div>
            </div>
            <div
              v-if="
                versionPreview.variables && versionPreview.variables.length > 0
              "
            >
              <h4 class="h5">Erkannte Variablen</h4>
              <div class="d-flex flex-wrap gap-1">
                <span
                  v-for="variable in versionPreview.variables"
                  :key="variable"
                  class="fs-sm fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success"
                >
                  {{ variable }}
                </span>
              </div>
            </div>
          </div>
          <div class="block-content block-content-full text-end bg-body">
            <button
              type="button"
              class="btn btn-sm btn-alt-secondary me-1"
              data-bs-dismiss="modal"
            >
              Schließen
            </button>
            <!-- Button, der die setVersionAsCurrent Funktion aufruft -->
            <button
              v-if="versionPreview && !versionPreview.is_current"
              type="button"
              class="btn btn-sm btn-primary"
              @click="
                console.log(
                  '[Show.vue] Modal Button Klick. versionPreview:',
                  versionPreview,
                  'versionPreview.id:',
                  versionPreview?.id,
                );
                setVersionAsCurrent(versionPreview.id);
              "
            >
              <i class="fa fa-fw fa-check me-1"></i> Diese Version verwenden
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
