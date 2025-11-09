<script setup lang="ts">
import TiptapEditor from '@/components/TiptapEditor.vue';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue'; // 1. Import 'watch'

// --- INTERFACES & PROPS ---
interface TenderItem {
    id: number;
    title: string;
    tender_number: string;
    description: string;
    published_at: string;
    closing_at: string;
    attachments: string[];
    isActive: boolean;
    displayOrder: number;
    slug: string; // 2. Add slug to the interface
    link: string; // 2. Add link to the interface
}
interface PageProps {
    tenderItems: TenderItem[];
    siteId: number | null;
}
const props = defineProps<PageProps>();

// --- STATE MANAGEMENT ---
const tenderData = ref<TenderItem[]>([]);
const isSaving = ref(false);
const message = ref('');
const messageType = ref<'success' | 'error' | ''>('');
const viewMode = ref<'list' | 'form'>('list');
const editingItem = ref<TenderItem | null>(null);
const editingFiles = ref<File[]>([]);
const tenderFiles = ref<Record<number, File[]>>({});

// --- METHODS ---
// Helper function to create a slug from a string
const createSlug = (text: string) => {
    return text
        .toLowerCase()
        .replace(/ /g, '-')
        .replace(/[^\w-]+/g, '');
};

// 3. Watch for changes to the title of the editingItem
watch(
    () => editingItem.value?.title,
    (newTitle) => {
        if (editingItem.value && newTitle) {
            editingItem.value.slug = createSlug(newTitle);
            editingItem.value.link = `/tenders/${editingItem.value.slug}`;
        }
    },
    { deep: true },
);

const initializeTenders = () => {
    tenderData.value = JSON.parse(JSON.stringify(props.tenderItems || []));
};
initializeTenders();

const hasUnsavedChanges = computed(() => {
    return JSON.stringify(tenderData.value) !== JSON.stringify(props.tenderItems);
});

const isValidConfiguration = computed(() => {
    if (viewMode.value === 'form' && editingItem.value) {
        return editingItem.value.title && editingItem.value.tender_number;
    }
    return true;
});

const showMessage = (msg: string, type: 'success' | 'error') => {
    message.value = msg;
    messageType.value = type;
    setTimeout(() => {
        message.value = '';
    }, 5000);
};

const onAddItem = () => {
    const nextTenderNumber = tenderData.value.length + 1;
    const initialTitle = 'New Tender Title';
    const initialSlug = createSlug(initialTitle); // 4. Create initial slug

    editingItem.value = {
        id: Date.now(),
        title: initialTitle,
        tender_number: `MBSTU/TENDER/${new Date().getFullYear()}/${nextTenderNumber}`,
        description: '<p>Enter tender details here.</p>',
        published_at: new Date().toISOString().split('T')[0],
        closing_at: new Date(new Date().setDate(new Date().getDate() + 30)).toISOString().split('T')[0],
        attachments: [],
        isActive: true,
        displayOrder: nextTenderNumber,
        slug: initialSlug, // 4. Set initial slug
        link: `/tenders/${initialSlug}`, // 4. Set initial link
    };
    viewMode.value = 'form';
    editingFiles.value = [];
};

const onEditItem = (item: TenderItem) => {
    editingItem.value = JSON.parse(JSON.stringify(item));
    viewMode.value = 'form';
    editingFiles.value = [];
};

// ... (onDeleteItem, onSaveItem, etc. remain the same) ...
const onDeleteItem = (id: number) => {
    const index = tenderData.value.findIndex((item) => item.id === id);
    if (index > -1) {
        tenderData.value.splice(index, 1);
        tenderData.value.forEach((item, idx) => {
            item.displayOrder = idx + 1;
        });
        showMessage('Tender removed.', 'success');
        validateAndSave(); // Auto-save on delete
    }
};

const onSaveItem = () => {
    if (!editingItem.value || !isValidConfiguration.value) {
        showMessage('Please fill all required fields (Title, Tender Number).', 'error');
        return;
    }
    const index = tenderData.value.findIndex((item) => item.id === editingItem.value!.id);
    if (index > -1) {
        tenderData.value[index] = editingItem.value;
        if (editingFiles.value.length > 0) {
            tenderFiles.value[index] = [...editingFiles.value];
        }
    } else {
        tenderData.value.push(editingItem.value);
        const newIndex = tenderData.value.length - 1;
        if (editingFiles.value.length > 0) {
            tenderFiles.value[newIndex] = [...editingFiles.value];
        }
    }
    viewMode.value = 'list';
    editingItem.value = null;
    editingFiles.value = [];
    showMessage('Tender saved locally.', 'success');
    validateAndSave(); // Auto-save
};

const onCancelEdit = () => {
    editingItem.value = null;
    viewMode.value = 'list';
};

const resetToOriginal = () => {
    initializeTenders();
    showMessage('Changes have been discarded.', 'success');
};

const validateAndSave = async () => {
    if (!props.siteId) return showMessage('Site ID is missing.', 'error');
    isSaving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        let response;

        const hasFiles = Object.keys(tenderFiles.value).length > 0;

        if (hasFiles) {
            const form = new FormData();
            form.append('siteId', String(props.siteId));
            form.append('tenderItems', JSON.stringify(tenderData.value));

            Object.keys(tenderFiles.value).forEach((idx) => {
                const files = tenderFiles.value[Number(idx)];
                files.forEach((f) => {
                    form.append(`tenderFiles[${idx}][]`, f);
                });
            });

            response = await fetch('/admin/tenders-section', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken || '',
                    Accept: 'application/json',
                },
                body: form,
            });
        } else {
            response = await fetch('/admin/tenders-section', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken || '', Accept: 'application/json' },
                body: JSON.stringify({ tenderItems: tenderData.value, siteId: props.siteId }),
            });
        }

        const result = await response.json();
        if (response.ok && result.success) {
            showMessage(result.message, 'success');
            router.reload({ only: ['tenderItems'], onSuccess: () => initializeTenders() });
        } else {
            showMessage(result.message || 'Server error occurred.', 'error');
        }
    } catch (err) {
        console.error('Save error:', err);
        showMessage('A network error occurred.', 'error');
    } finally {
        isSaving.value = false;
    }
};

const onFilesSelected = (e: Event) => {
    const input = e.target as HTMLInputElement;
    editingFiles.value = input.files ? Array.from(input.files) : [];
};

const removeAttachment = (idx: number) => {
    if (!editingItem.value) return;
    editingItem.value.attachments.splice(idx, 1);
};
</script>

<template>
    <DefaultLayout>
        <div
            class="shadow-default rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 sm:px-7.5 xl:pb-1 dark:border-strokedark dark:bg-boxdark"
        >
            <!-- Header -->
            <div class="mb-6">
                <!-- ... header content ... -->
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h4 class="text-xl font-semibold text-black dark:text-white">Tender Section Management</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Site ID: {{ siteId || 'N/A' }}
                            <span v-if="hasUnsavedChanges" class="ml-2 text-warning">• Unsaved Changes</span>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button v-if="viewMode === 'list'" @click="onAddItem" class="action-btn bg-primary">Add Tender</button>
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
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Tender No.</th>
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Published</th>
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Closing</th>
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in tenderData" :key="item.id">
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">{{ item.title }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">{{ item.tender_number }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">{{ item.published_at }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">{{ item.closing_at }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <div class="flex items-center space-x-3.5">
                                        <button @click="onEditItem(item)" class="action-btn bg-primary px-3 py-1 text-sm">Edit</button>
                                        <button @click="onDeleteItem(item.id)" class="action-btn bg-danger px-3 py-1 text-sm">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="tenderData.length === 0">
                                <td colspan="5" class="py-10 text-center text-gray-500">No tenders found. Click "Add Tender" to begin.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Form View -->
            <div v-if="viewMode === 'form' && editingItem">
                <div class="rounded-sm border border-stroke bg-gray-50 p-4 dark:bg-meta-4">
                    <h5 class="mb-4 text-lg font-medium text-black dark:text-white">
                        {{ editingItem.id > 1000000000000 ? 'Add Tender' : 'Edit Tender' }}
                    </h5>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label">Title *</label>
                                <input v-model="editingItem.title" type="text" class="form-input" />
                            </div>
                            <div>
                                <label class="form-label">Tender Number *</label>
                                <input v-model="editingItem.tender_number" type="text" class="form-input" />
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Description</label>
                            <TiptapEditor v-model="editingItem.description" />
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label">Published Date</label>
                                <input v-model="editingItem.published_at" type="date" class="form-input" />
                            </div>
                            <div>
                                <label class="form-label">Closing Date</label>
                                <input v-model="editingItem.closing_at" type="date" class="form-input" />
                            </div>
                        </div>

                        <!-- 5. Add the Link URL input field -->
                        <div>
                            <label class="form-label">Link URL (auto-generated)</label>
                            <input v-model="editingItem.link" type="text" class="form-input bg-gray-100 dark:bg-gray-700" readonly />
                        </div>

                        <div>
                            <label class="form-label">Attachments</label>
                            <!-- ... attachments logic ... -->
                            <div v-if="editingItem.attachments && editingItem.attachments.length">
                                <ul class="ml-5 list-disc">
                                    <li v-for="(att, i) in editingItem.attachments" :key="i" class="flex items-center gap-3">
                                        <a :href="att" target="_blank" class="text-primary underline">{{ att.split('/').pop() }}</a>
                                        <button type="button" class="text-sm text-danger" @click="removeAttachment(i)">Remove</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-2">
                                <input type="file" multiple @change="onFilesSelected" class="form-input" />
                                <div v-if="editingFiles.length" class="mt-2 text-sm">
                                    Selected files:
                                    <span v-for="(f, i) in editingFiles" :key="i">{{ f.name }}{{ i < editingFiles.length - 1 ? ', ' : '' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button @click="onCancelEdit" class="action-btn bg-gray-500">Cancel</button>
                    <button @click="onSaveItem" :disabled="!isValidConfiguration" class="action-btn bg-success">
                        {{ editingItem.id > 1000000000000 ? 'Add Tender' : 'Update Tender' }}
                    </button>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<style scoped>
/* ... your styles ... */
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
</style>
