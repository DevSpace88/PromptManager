<template>
  <div ref="editorContainer" class="monaco-editor-container" style="width: 100%; height: 100%;"></div>
</template>

<script>
export default {
  name: 'MonacoEditor',
  props: {
    modelValue: {
      type: String,
      default: ''
    },
    language: {
      type: String,
      default: 'javascript'
    },
    theme: {
      type: String,
      default: 'vs-dark'
    },
    options: {
      type: Object,
      default: () => ({})
    }
  },
  emits: ['update:modelValue'],
  data() {
    return {
      editor: null,
      value: this.modelValue
    }
  },
  watch: {
    modelValue(newValue) {
      if (this.editor && newValue !== this.editor.getValue()) {
        this.editor.setValue(newValue);
      }
    },
    language(newVal) {
      if (this.editor) {
        window.monaco.editor.setModelLanguage(this.editor.getModel(), newVal);
      }
    },
    theme(newVal) {
      if (this.editor) {
        window.monaco.editor.setTheme(newVal);
      }
    }
  },
  mounted() {
    this.initMonaco();
  },
  beforeUnmount() {
    this.disposeMonaco();
  },
  methods: {
    initMonaco() {
      const loadMonaco = () => {
        if (typeof window.monaco === 'undefined') {
          // Monaco ist noch nicht geladen - Skript dynamisch einfügen
          const script = document.createElement('script');
          script.src = 'https://cdn.jsdelivr.net/npm/monaco-editor@0.43.0/min/vs/loader.js';
          script.onload = () => {
            window.require.config({
              paths: { 'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor@0.43.0/min/vs' }
            });
            window.require(['vs/editor/editor.main'], this.createEditor);
          };
          document.head.appendChild(script);
        } else {
          // Monaco ist bereits geladen
          this.createEditor();
        }
      };

      loadMonaco();
    },
    createEditor() {
      // Standard-Optionen
      const defaultOptions = {
        value: this.modelValue,
        language: this.language,
        theme: this.theme,
        automaticLayout: true,
        minimap: { enabled: false },
        scrollBeyondLastLine: false,
        fontSize: 14,
        fontFamily: 'Consolas, "Liberation Mono", Menlo, Courier, monospace',
        scrollbar: {
          useShadows: false,
          vertical: 'visible',
          horizontal: 'visible',
          verticalScrollbarSize: 10,
          horizontalScrollbarSize: 10
        }
      };

      // Editor mit Optionen aus Props erstellen
      this.editor = window.monaco.editor.create(
        this.$refs.editorContainer,
        { ...defaultOptions, ...this.options }
      );

      // Event-Listener für Änderungen
      this.editor.onDidChangeModelContent(() => {
        const value = this.editor.getValue();
        this.$emit('update:modelValue', value);
      });
    },
    disposeMonaco() {
      if (this.editor) {
        this.editor.dispose();
        this.editor = null;
      }
    }
  }
}
</script>

<style>
.monaco-editor-container {
  border: 1px solid #e2e8f0;
  border-radius: 0.375rem;
  overflow: hidden;
}
</style>
