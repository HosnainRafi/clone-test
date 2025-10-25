<script setup lang="ts">
import TiptapEditor from '@/components/TiptapEditor.vue';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

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
const viewMode = ref<'list' | 'form'>('list'); // 'list' or 'form'
const editingItem = ref<NewsItem | null>(null);

// --- METHODS ---
const initializeNewsItems = () => {
    newsData.value = JSON.parse(JSON.stringify(props.newsItems || []));
};
initializeNewsItems();

const hasUnsavedChanges = computed(() => {
    return JSON.stringify(newsData.value) !== JSON.stringify(props.newsItems);
});

const isValidConfiguration = computed(() => {
    if (viewMode.value === 'form' && editingItem.value) {
        return editingItem.value.title && editingItem.value.excerpt && editingItem.value.category;
    }
    return newsData.value.every((item) => item.title && item.excerpt && item.category);
});

const showMessage = (msg: string, type: 'success' | 'error') => {
    message.value = msg;
    messageType.value = type;
    setTimeout(() => {
        message.value = '';
    }, 5000);
};

const onAddItem = () => {
    const nextArticleNumber = newsData.value.length + 1;
    editingItem.value = {
        id: Date.now(), // Temporary ID for new items
        title: 'New News Article',
        excerpt: '<p>Enter your full news content here.</p>',
        image: '/images/news/default-placeholder.jpg',
        date: new Date().toISOString().split('T')[0],
        category: 'General News',
        link: `/news/new-article-${nextArticleNumber}`,
        isActive: true,
        displayOrder: nextArticleNumber,
    };
    viewMode.value = 'form';
};

const onEditItem = (item: NewsItem) => {
    editingItem.value = JSON.parse(JSON.stringify(item)); // Deep copy to avoid direct mutation
    viewMode.value = 'form';
};

const onDeleteItem = (id: number) => {
    const index = newsData.value.findIndex((item) => item.id === id);
    if (index > -1) {
        newsData.value.splice(index, 1);
        // Re-order remaining items
        newsData.value.forEach((item, idx) => {
            item.displayOrder = idx + 1;
        });
        showMessage('Item removed. Click "Save News" to persist changes.', 'success');
    }
};

const onSaveItem = () => {
    if (!editingItem.value || !isValidConfiguration.value) {
        showMessage('Please fill all required fields before saving.', 'error');
        return;
    }

    const index = newsData.value.findIndex((item) => item.id === editingItem.value!.id);

    if (index > -1) {
        // Update existing item
        newsData.value[index] = editingItem.value;
    } else {
        // Add new item
        newsData.value.push(editingItem.value);
    }

    editingItem.value = null;
    viewMode.value = 'list';
    showMessage('Changes staged. Click "Save News" to persist them.', 'success');
};

const onCancelEdit = () => {
    editingItem.value = null;
    viewMode.value = 'list';
};

const resetToOriginal = () => {
    initializeNewsItems();
    showMessage('Changes have been discarded.', 'success');
};

const validateAndSave = async () => {
    if (!siteId) return showMessage('Site ID is missing.', 'error');
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
            // Reload props from server
            router.reload({
                only: ['newsItems'],
                onSuccess: () => {
                    initializeNewsItems();
                },
            });
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
            <!-- Header -->
            <div class="mb-6">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h4 class="text-xl font-semibold text-black dark:text-white">News Section Management</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Site ID: {{ siteId || 'N/A' }}
                            <span v-if="hasUnsavedChanges" class="ml-2 text-warning">• Unsaved Changes</span>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button v-if="viewMode === 'list'" @click="onAddItem" class="action-btn bg-primary">Add News Item</button>
                        <button v-if="hasUnsavedChanges" @click="resetToOriginal" class="action-btn bg-secondary">Discard All</button>
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

            <!-- List View -->
            <div v-if="viewMode === 'list'">
                <div class="max-w-full overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-2 text-left dark:bg-meta-4">
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Title</th>
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Category</th>
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Date</th>
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in newsData" :key="item.id">
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">{{ item.title }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">{{ item.category }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">{{ item.date }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <div class="flex items-center space-x-3.5">
                                        <button @click="onEditItem(item)" class="action-btn bg-primary px-3 py-1 text-sm">Edit</button>
                                        <button @click="onDeleteItem(item.id)" class="action-btn bg-danger px-3 py-1 text-sm">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="newsData.length === 0">
                                <td colspan="4" class="py-10 text-center text-gray-500">No news items found. Click "Add News Item" to begin.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Form View -->
            <div v-if="viewMode === 'form' && editingItem">
                <div class="space-y-6">
                    <div class="rounded-sm border border-stroke bg-gray-50 p-4 dark:bg-meta-4">
                        <div class="mb-4 flex items-start justify-between">
                            <h5 class="text-lg font-medium text-black dark:text-white">
                                {{ editingItem.id > 1000000000000 ? 'Add News Item' : 'Edit News Item' }}
                            </h5>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="form-label">Title *</label>
                                <input v-model="editingItem.title" type="text" class="form-input" />
                            </div>
                            <div>
                                <label class="form-label">Content *</label>
                                <TiptapEditor v-model="editingItem.excerpt" />
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="form-label">Category *</label>
                                    <input v-model="editingItem.category" type="text" class="form-input" />
                                </div>
                                <div>
                                    <label class="form-label">Date</label>
                                    <input v-model="editingItem.date" type="date" class="form-input" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="form-label">Image URL</label>
                                    <input v-model="editingItem.image" type="text" class="form-input" />
                                </div>
                                <div>
                                    <label class="form-label">Link URL (auto-generated)</label>
                                    <input v-model="editingItem.link" type="text" class="form-input bg-gray-100 dark:bg-gray-700" disabled />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button @click="onCancelEdit" class="action-btn bg-gray-500">Cancel</button>
                    <button @click="onSaveItem" :disabled="!isValidConfiguration" class="action-btn bg-success">
                        {{ editingItem.id > 1000000000000 ? 'Add Item' : 'Update Item' }}
                    </button>
                </div>
            </div>

            <!-- Global Save Button -->
            <div class="mt-6 flex justify-end" v-if="hasUnsavedChanges">
                <button
                    @click="validateAndSave"
                    :disabled="isSaving || !siteId || !hasUnsavedChanges"
                    class="save-btn"
                    :class="{
                        'hover:bg-opacity-90 bg-primary': hasUnsavedChanges,
                        'cursor-not-allowed bg-gray-400': isSaving || !siteId || !hasUnsavedChanges,
                    }"
                >
                    {{ isSaving ? 'Saving...' : 'Save All Changes' }}
                </button>
            </div>
        </div>
    </DefaultLayout>
</template>

<style scoped>
/* Your existing styles remain unchanged */
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
