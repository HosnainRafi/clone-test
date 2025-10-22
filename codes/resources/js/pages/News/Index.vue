<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// --- TIPTAP EDITOR IMPORT ---
// We import our custom Tiptap component instead of a library
import TiptapEditor from '@/components/TiptapEditor.vue';
// (Adjust path if you create the component elsewhere)

// --- INTERFACES & PROPS ---
interface NewsItem {
    id: number;
    title: string;
    excerpt: string;
    image: string;
    date: string;
    category: string;
    link: string;
    isActive: boolean;
    displayOrder: number;
}
interface PageProps {
    newsItems: NewsItem[];
    siteId: number | null;
}
const props = defineProps<PageProps>();
const { siteId } = props;

// --- STATE MANAGEMENT ---
const newsData = ref<NewsItem[]>([]);
const isSaving = ref(false);
const message = ref('');
const messageType = ref<'success' | 'error' | ''>('');

// (All CKEditor and TinyMCE config has been removed)

// --- METHODS ---
const initializeNewsItems = () => {
    newsData.value = JSON.parse(JSON.stringify(props.newsItems || []));
};
initializeNewsItems();

const hasUnsavedChanges = computed(() => {
    return JSON.stringify(newsData.value) !== JSON.stringify(props.newsItems);
});

const isValidConfiguration = computed(() => {
    return newsData.value.every((item) => item.title && item.excerpt && item.category);
});

const showMessage = (msg: string, type: 'success' | 'error') => {
    message.value = msg;
    messageType.value = type;
    setTimeout(() => {
        message.value = '';
    }, 5000);
};

const addNewsItem = () => {
    const nextArticleNumber = newsData.value.length + 1;
    newsData.value.push({
        id: Date.now(),
        title: 'New News Article',
        excerpt: '<p>Enter your full news content here.</p>',
        image: '/images/news/default-placeholder.jpg',
        date: new Date().toISOString().split('T')[0],
        category: 'General News',
        link: `/news/new-article-${nextArticleNumber}`,
        isActive: true,
        displayOrder: nextArticleNumber,
    });
};

const removeNewsItem = (id: number) => {
    const index = newsData.value.findIndex((item) => item.id === id);
    if (index > -1) {
        newsData.value.splice(index, 1);
        newsData.value.forEach((item, idx) => {
            item.displayOrder = idx + 1;
        });
    }
};

const resetToOriginal = () => {
    initializeNewsItems();
    showMessage('Changes have been discarded.', 'success');
};

const validateAndSave = async () => {
    if (!siteId) return showMessage('Site ID is missing.', 'error');
    if (!isValidConfiguration.value) return showMessage('Please fill all required fields.', 'error');
    isSaving.value = true;
    try {
        const dataToSave = newsData.value.map(({ id, ...rest }) => rest);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch('/news-section/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken || '', Accept: 'application/json' },
            body: JSON.stringify({ newsItems: dataToSave, siteId: props.siteId }),
        });
        const result = await response.json();
        if (response.ok && result.success) {
            showMessage(result.message, 'success');
            setTimeout(() => router.reload({ only: ['newsItems'] }), 1500);
        } else {
            showMessage(result.message || 'An unknown server error occurred.', 'error');
        }
    } catch (error) {
        showMessage('A network error occurred. Please try again.', 'error');
    } finally {
        isSaving.value = false;
    }
};
</script>

<template>
    <DefaultLayout>
        <div
            class="shadow-default rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 sm:px-7.5 xl:pb-1 dark:border-strokedark dark:bg-boxdark"
        >
            <div class="mb-6">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h4 class="text-xl font-semibold text-black dark:text-white">News Section Management</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Site ID: {{ siteId || 'N/A' }} | Status:
                            <span :class="isValidConfiguration ? 'text-success' : 'text-danger'">{{
                                isValidConfiguration ? 'Valid' : 'Invalid'
                            }}</span>
                            <span v-if="hasUnsavedChanges" class="ml-2 text-warning">• Unsaved Changes</span>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button v-if="hasUnsavedChanges" @click="resetToOriginal" class="action-btn bg-secondary">Reset</button>
                        <button @click="addNewsItem" class="action-btn bg-primary">Add News Item</button>
                    </div>
                </div>
                <div
                    v-if="message"
                    :class="messageType === 'success' ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger'"
                    class="mb-4 rounded-md border px-4 py-3"
                >
                    {{ message }}
                </div>
            </div>

            <div class="space-y-6">
                <div v-for="(item, index) in newsData" :key="item.id" class="rounded-sm border border-stroke bg-gray-50 p-4 dark:bg-meta-4">
                    <div class="mb-4 flex items-start justify-between">
                        <h5 class="text-lg font-medium text-black dark:text-white">News Item {{ index + 1 }}</h5>
                        <button @click="removeNewsItem(item.id)" class="action-btn bg-danger px-3 py-1">Remove</button>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Title *</label>
                            <input v-model="item.title" type="text" class="form-input" />
                        </div>

                        <div>
                            <label class="form-label">Content *</label>
                            <TiptapEditor v-model="item.excerpt" />
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label">Category *</label>
                                <input v-model="item.category" type="text" class="form-input" />
                            </div>
                            <div>
                                <label class="form-label">Date</label>
                                <input v-model="item.date" type="date" class="form-input" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label">Image URL</label>
                                <input v-model="item.image" type="text" class="form-input" />
                            </div>
                            <div>
                                <label class="form-label">Link URL (auto-generated)</label>
                                <input v-model="item.link" type="text" class="form-input bg-gray-100 dark:bg-gray-700" disabled />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button
                    @click="validateAndSave"
                    :disabled="!isValidConfiguration || isSaving || !siteId || !hasUnsavedChanges"
                    class="save-btn"
                    :class="{
                        'hover:bg-opacity-90 bg-primary': isValidConfiguration && hasUnsavedChanges,
                        'cursor-not-allowed bg-gray-400': !isValidConfiguration || isSaving || !siteId || !hasUnsavedChanges,
                    }"
                >
                    Save News
                </button>
            </div>
        </div>
    </DefaultLayout>
</template>

<style scoped>
@reference '../../../../resources/css/app.css';
.action-btn {
    @apply inline-flex items-center justify-center rounded-md px-4 py-2 text-center text-sm font-medium text-white;
}
.form-label {
    @apply mb-2.5 block font-medium text-black dark:text-white;
}
.form-input {
    @apply w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium outline-none focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input;
}
.save-btn {
    @apply inline-flex items-center justify-center rounded-md px-6 py-3 text-center font-medium text-white transition-colors;
}
</style>
