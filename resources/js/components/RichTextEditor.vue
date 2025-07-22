<template>
  <div class="rich-text-editor">
    <div :id="editorId" class="quill-editor"></div>
  </div>
</template>

<script>
import Quill from 'quill'
import 'quill/dist/quill.snow.css'

export default {
  name: 'RichTextEditor',
  props: {
    value: {
      type: String,
      default: ''
    },
    placeholder: {
      type: String,
      default: 'Enter text...'
    }
  },
  data() {
    return {
      editor: null,
      editorId: `editor-${Math.random().toString(36).substr(2, 9)}`,
      content: '',
      ignoreChange: false
    }
  },
  mounted() {
    this.$nextTick(() => {
      this.initEditor()
    })
  },
  beforeDestroy() {
    this.destroyEditor()
  },
  // For Vue 3 compatibility
  beforeUnmount() {
    this.destroyEditor()
  },
  methods: {
    destroyEditor() {
      if (this.editor) {
        try {
          this.editor.off('text-change')
          this.editor.disable()
          const editorEl = document.getElementById(this.editorId)
          if (editorEl) {
            editorEl.innerHTML = ''
          }
        } catch (error) {
          console.warn('Error destroying Quill editor:', error)
        } finally {
          this.editor = null
        }
      }
    },
    initEditor() {
      // Check if DOM element exists
      const editorElement = document.getElementById(this.editorId)
      if (!editorElement) {
        console.warn('Editor element not found:', this.editorId)
        return
      }

      // Destroy existing editor if it exists
      this.destroyEditor()

      const toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        [{ 'header': 1 }, { 'header': 2 }],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],
        [{ 'indent': '-1'}, { 'indent': '+1' }],
        [{ 'direction': 'rtl' }],
        [{ 'size': ['small', false, 'large', 'huge'] }],
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'font': [] }],
        [{ 'align': [] }],
        ['clean'],
        ['link', 'image', 'video']
      ]

      try {
        this.editor = new Quill(`#${this.editorId}`, {
          theme: 'snow',
          placeholder: this.placeholder,
          modules: {
            toolbar: toolbarOptions
          }
        })

        // Ensure editor is enabled
        this.editor.enable()

        // Set initial content after a brief delay to ensure DOM is ready
        this.$nextTick(() => {
          if (this.editor) {
            this.setContent(this.value)
          }
        })

        // Listen for content changes
        this.editor.on('text-change', () => {
          if (this.ignoreChange || !this.editor) {
            this.ignoreChange = false
            return
          }
          const html = this.editor.root.innerHTML
          const normalized = this.normalizeEmptyContent(html)
          if (normalized !== this.content) {
            this.content = normalized
            this.$emit('update:value', normalized) // Vue 3
            this.$emit('input', normalized) // Vue 2 compatibility
          }
        })
      } catch (error) {
        console.error('Error initializing Quill editor:', error)
        this.editor = null
      }
    },
    setContent(html) {
      if (this.editor && this.editor.root) {
        try {
          this.ignoreChange = true
          const content = this.normalizeEmptyContent(html)
          this.editor.clipboard.dangerouslyPasteHTML(content || '')
          this.content = content
          this.ignoreChange = false
        } catch (error) {
          console.warn('Error setting content in Quill editor:', error)
          this.ignoreChange = false
        }
      } else {
        console.warn('RichTextEditor setContent called but editor not ready:', this.editorId)
      }
    },
    normalizeEmptyContent(html) {
      // Normalize different empty states to empty string
      if (!html || html.trim() === '' || html === '<p><br></p>' || html === '<p></p>') {
        return ''
      }
      // Remove whitespace-only HTML
      if (/^<p>\s*<\/p>$/.test(html)) {
        return ''
      }
      return html
    }
  },
  watch: {
    value: {
      handler(newValue, oldValue) {
        const normalizedNew = this.normalizeEmptyContent(newValue)
        const normalizedCurrent = this.normalizeEmptyContent(this.content)

        if (normalizedNew !== normalizedCurrent) {
          this.setContent(newValue)
        }
      },
      immediate: true
    }
  }
}
</script>

<style scoped>
.rich-text-editor {
    width: 100%;
    position: relative;
}

.quill-editor {
    min-height: 200px;
}

:deep(.ql-editor) {
    min-height: 200px;
    font-size: 14px;
    line-height: 1.6;
    cursor: text;
    outline: none;
}

:deep(.ql-toolbar) {
    border-top: 1px solid #ccc;
    border-left: 1px solid #ccc;
    border-right: 1px solid #ccc;
    border-bottom: none;
    border-radius: 4px 4px 0 0;
    background: white;
    z-index: 1;
}

:deep(.ql-container) {
    border-bottom: 1px solid #ccc;
    border-left: 1px solid #ccc;
    border-right: 1px solid #ccc;
    border-top: none;
    border-radius: 0 0 4px 4px;
    background: white;
}

:deep(.ql-editor:focus) {
    border-color: #3b82f6;
    box-shadow: 0 0 0 1px #3b82f6;
}

:deep(.ql-toolbar:focus-within) {
    border-color: #3b82f6;
}

:deep(.ql-container:focus-within) {
    border-color: #3b82f6;
}

:deep(.ql-editor.ql-blank::before) {
    color: #9ca3af;
    font-style: italic;
}

:deep(.ql-editor p) {
    margin-bottom: 0.5rem;
}

:deep(.ql-editor p:last-child) {
    margin-bottom: 0;
}
</style>
