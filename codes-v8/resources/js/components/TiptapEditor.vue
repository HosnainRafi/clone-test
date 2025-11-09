<script setup lang="ts">
import Link from '@tiptap/extension-link';
import StarterKit from '@tiptap/starter-kit';
import { EditorContent, useEditor } from '@tiptap/vue-3';
import { onBeforeUnmount, watch } from 'vue';

// --- PROPS & EMITS ---
const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
});
const emit = defineEmits(['update:modelValue']);

// --- EDITOR SETUP ---
const editor = useEditor({
    content: props.modelValue,
    extensions: [
        StarterKit, // Includes Bold, Italic, Strike, Heading, Lists, History (for undo/redo)
        Link.configure({
            openOnClick: false, // Don't open links on click in the editor
            autolink: true,
        }),
    ],
    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getHTML());
    },
    // Use Tailwind classes for styling the editor content
    editorProps: {
        attributes: {
            // These classes match your .form-input style
            class: 'prose prose-sm sm:prose dark:prose-invert min-h-[250px] w-full max-w-none rounded-b border-t-0 border-[1.5px] border-stroke bg-transparent px-5 py-3 outline-none dark:border-form-strokedark dark:bg-form-input',
        },
    },
});

// --- LINK METHOD ---
const setLink = () => {
    if (!editor.value) return;
    const previousUrl = editor.value.getAttributes('link').href;
    const url = window.prompt('URL', previousUrl);

    // cancelled
    if (url === null) return;

    // empty
    if (url === '') {
        editor.value.chain().focus().extendMarkRange('link').unsetLink().run();
        return;
    }

    // update link
    editor.value.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
};

// --- LIFECYCLE HOOKS ---
// Watch for external model changes (e.g., from a "Reset" button)
watch(
    () => props.modelValue,
    (value) => {
        if (editor.value && editor.value.getHTML() !== value) {
            editor.value.commands.setContent(value, false); // false = don't emit update
        }
    },
);

onBeforeUnmount(() => {
    if (editor.value) {
        editor.value.destroy();
    }
});
</script>

<template>
    <div v-if="editor" class="rounded border-[1.5px] border-stroke dark:border-form-strokedark">
        <div
            class="toolbar-wrapper flex flex-wrap items-center gap-x-4 gap-y-1 rounded-t border-b-[1.5px] border-stroke bg-gray-100 px-4 py-2 dark:border-form-strokedark dark:bg-meta-4"
        >
            <button @click="editor.chain().focus().undo().run()" type="button" class="toolbar-btn" :disabled="!editor.can().undo()">Undo</button>
            <button @click="editor.chain().focus().redo().run()" type="button" class="toolbar-btn" :disabled="!editor.can().redo()">Redo</button>

            <span class="toolbar-divider"></span>

            <button
                @click="editor.chain().focus().toggleBold().run()"
                type="button"
                class="toolbar-btn font-bold"
                :class="{ 'text-primary': editor.isActive('bold') }"
            >
                Bold
            </button>
            <button
                @click="editor.chain().focus().toggleItalic().run()"
                type="button"
                class="toolbar-btn italic"
                :class="{ 'text-primary': editor.isActive('italic') }"
            >
                Italic
            </button>
            <button
                @click="editor.chain().focus().toggleStrike().run()"
                type="button"
                class="toolbar-btn line-through"
                :class="{ 'text-primary': editor.isActive('strike') }"
            >
                Strike
            </button>

            <span class="toolbar-divider"></span>

            <button
                @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
                type="button"
                class="toolbar-btn font-semibold"
                :class="{ 'text-primary': editor.isActive('heading', { level: 2 }) }"
            >
                H2
            </button>
            <button
                @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
                type="button"
                class="toolbar-btn font-semibold"
                :class="{ 'text-primary': editor.isActive('heading', { level: 3 }) }"
            >
                H3
            </button>

            <span class="toolbar-divider"></span>

            <button
                @click="editor.chain().focus().toggleBulletList().run()"
                type="button"
                class="toolbar-btn"
                :class="{ 'text-primary': editor.isActive('bulletList') }"
            >
                List
            </button>
            <button
                @click="editor.chain().focus().toggleNumberedList().run()"
                type="button"
                class="toolbar-btn"
                :class="{ 'text-primary': editor.isActive('numberedList') }"
            >
                Num-List
            </button>

            <span class="toolbar-divider"></span>

            <button @click="setLink" type="button" class="toolbar-btn" :class="{ 'text-primary': editor.isActive('link') }">Link</button>
            <button @click="editor.chain().focus().unsetLink().run()" type="button" class="toolbar-btn" :disabled="!editor.isActive('link')">
                Unlink
            </button>
        </div>

        <EditorContent :editor="editor" />
    </div>
</template>

<style>
/* Add this reference to your main Tailwind CSS file */
@reference "../../css/app.css";

/* Basic styles for the Tiptap editor */
.prose {
    @apply max-w-none;
}
.prose > :first-child {
    @apply mt-0; /* This will now be recognized */
}
.prose > :last-child {
    @apply mb-0;
}
.prose:focus {
    @apply outline-none;
}

/* Fix for dark mode list markers */
.dark .prose :where(ol > li)::marker {
    @apply text-gray-400;
}
.dark .prose :where(ul > li)::marker {
    @apply text-gray-400;
}

/* Toolbar button styles */
.toolbar-btn {
    @apply opacity-70 transition-opacity hover:opacity-100;
}
.toolbar-btn:disabled {
    @apply opacity-30;
}

/* Toolbar divider */
.toolbar-divider {
    @apply h-4 w-px bg-stroke dark:bg-form-strokedark;
}
</style>
