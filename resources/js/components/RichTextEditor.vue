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
    this.initEditor()
  },
  beforeDestroy() {
    if (this.editor) {
      this.editor.off('text-change');
      this.editor.disable();
      const editorEl = document.getElementById(this.editorId);
      if (editorEl) {
        editorEl.innerHTML = '';
      }
      this.editor = null;
    }
  },
  // For Vue 3 compatibility
  beforeUnmount() {
    if (this.editor) {
      this.editor.off('text-change');
      this.editor.disable();
      const editorEl = document.getElementById(this.editorId);
      if (editorEl) {
        editorEl.innerHTML = '';
      }
      this.editor = null;
    }
  },
  methods: {
    initEditor() {
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

      this.editor = new Quill(`#${this.editorId}`, {
        theme: 'snow',
        placeholder: this.placeholder,
        modules: {
          toolbar: toolbarOptions
        }
      })

      // Set initial content
      this.setContent(this.value)

      // Listen for content changes
      this.editor.on('text-change', () => {
        if (this.ignoreChange) {
          this.ignoreChange = false
          return
        }
        const html = this.editor.root.innerHTML
        const normalized = this.normalizeEmptyContent(html)
        if (normalized !== this.content) {
          this.content = normalized
        //   console.log('RichTextEditor value:', normalized)
          this.$emit('input', normalized) // Vue 2
          this.$emit('update:modelValue', normalized) // Vue 3
        }
      })
    },
    setContent(html) {
      if (this.editor) {
        this.ignoreChange = true
        const content = this.normalizeEmptyContent(html)
        this.editor.clipboard.dangerouslyPasteHTML(content || '')
        this.content = content
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
    value(newValue) {
        console.log('Value changed:', newValue)
      const normalizedNew = this.normalizeEmptyContent(newValue)
      const normalizedCurrent = this.content

      if (normalizedNew !== normalizedCurrent) {
        this.setContent(newValue)
      }
    }
  }
}
</script>

<style scoped>
.rich-text-editor {
    width: 100%;
}

.quill-editor {
    min-height: 200px;
}

:deep(.ql-editor) {
    min-height: 200px;
    font-size: 14px;
    line-height: 1.6;
}

:deep(.ql-toolbar) {
    border-top: 1px solid #ccc;
    border-left: 1px solid #ccc;
    border-right: 1px solid #ccc;
    border-bottom: none;
    border-radius: 4px 4px 0 0;
}

:deep(.ql-container) {
    border-bottom: 1px solid #ccc;
    border-left: 1px solid #ccc;
    border-right: 1px solid #ccc;
    border-top: none;
    border-radius: 0 0 4px 4px;
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
</style>
