<script setup lang="ts">
import TiptapEditor from '@/components/TiptapEditor.vue';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// --- INTERFACES & PROPS ---
interface NoticeItem {
    id: number;
    title: string;
    content: string;
    date: string;
    category: string;
    priority: 'high' | 'medium' | 'low';
    department: string;
    validUntil: string;
    attachments: string[];
    link: string;
    isActive: boolean;
    displayOrder: number;
}
interface PageProps {
    noticeItems: NoticeItem[];
    siteId: number | null;
}
const props = defineProps<PageProps>();

// --- STATE MANAGEMENT ---
const noticeData = ref<NoticeItem[]>([]);
const isSaving = ref(false);
const message = ref('');
const messageType = ref<'success' | 'error' | ''>('');
const viewMode = ref<'list' | 'form'>('list');
const editingItem = ref<NoticeItem | null>(null);

// --- METHODS ---
const initializeNotices = () => {
    noticeData.value = JSON.parse(JSON.stringify(props.noticeItems || []));
};
initializeNotices();

const hasUnsavedChanges = computed(() => {
    return JSON.stringify(noticeData.value) !== JSON.stringify(props.noticeItems);
});

const isValidConfiguration = computed(() => {
    if (viewMode.value === 'form' && editingItem.value) {
        return editingItem.value.title && editingItem.value.content && editingItem.value.category && editingItem.value.department;
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
    const nextNoticeNumber = noticeData.value.length + 1;
    editingItem.value = {
        id: Date.now(),
        title: 'New Notice Title',
        content: '<p>Enter notice content here.</p>',
        date: new Date().toISOString().split('T')[0],
        category: 'General',
        priority: 'medium',
        department: 'Admin Office',
        validUntil: '',
        attachments: [],
        isActive: true,
        displayOrder: nextNoticeNumber,
        link: `/notices/new-notice-${nextNoticeNumber}`,
    };
    viewMode.value = 'form';
};

const onEditItem = (item: NoticeItem) => {
    editingItem.value = JSON.parse(JSON.stringify(item));
    viewMode.value = 'form';
};

const onDeleteItem = (id: number) => {
    const index = noticeData.value.findIndex((item) => item.id === id);
    if (index > -1) {
        noticeData.value.splice(index, 1);
        noticeData.value.forEach((item, idx) => {
            item.displayOrder = idx + 1;
        });
        showMessage('Notice removed. Save to persist changes.', 'success');
    }
};

const onSaveItem = () => {
    if (!editingItem.value || !isValidConfiguration.value) {
        showMessage('Please fill all required fields.', 'error');
        return;
    }
    const index = noticeData.value.findIndex((item) => item.id === editingItem.value!.id);
    if (index > -1) {
        noticeData.value[index] = editingItem.value;
    } else {
        noticeData.value.push(editingItem.value);
    }
    viewMode.value = 'list';
    editingItem.value = null;
    showMessage('Changes staged. Click "Save All Changes" to finalize.', 'success');
};

const onCancelEdit = () => {
    editingItem.value = null;
    viewMode.value = 'list';
};

const resetToOriginal = () => {
    initializeNotices();
    showMessage('Changes have been discarded.', 'success');
};

const validateAndSave = async () => {
    if (!props.siteId) return showMessage('Site ID is missing.', 'error');
    isSaving.value = true;
    try {
        const dataToSave = noticeData.value.map(({ id, ...rest }) => rest);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch('/notices-section/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken || '', Accept: 'application/json' },
            body: JSON.stringify({ noticeItems: dataToSave, siteId: props.siteId }),
        });
        const result = await response.json();
        if (response.ok && result.success) {
            showMessage(result.message, 'success');
            router.reload({ only: ['noticeItems'], onSuccess: () => initializeNotices() });
        } else {
            showMessage(result.message || 'Server error.', 'error');
        }
    } catch (error) {
        showMessage('Network error.', 'error');
    } finally {
        isSaving.value = false;
    }
};

const priorityClass = (priority: string) => {
    if (priority === 'high') return 'bg-danger/10 text-danger';
    if (priority === 'medium') return 'bg-warning/10 text-warning';
    return 'bg-success/10 text-success';
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
                        <h4 class="text-xl font-semibold text-black dark:text-white">Notices Section Management</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Site ID: {{ siteId || 'N/A' }}
                            <span v-if="hasUnsavedChanges" class="ml-2 text-warning">• Unsaved Changes</span>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button v-if="viewMode === 'list'" @click="onAddItem" class="action-btn bg-primary">Add Notice</button>
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
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Date</th>
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Department</th>
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Priority</th>
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in noticeData" :key="item.id">
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">{{ item.title }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">{{ item.date }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">{{ item.department }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <span :class="priorityClass(item.priority)" class="rounded-full px-3 py-1 text-xs font-medium">{{
                                        item.priority
                                    }}</span>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <div class="flex items-center space-x-3.5">
                                        <button @click="onEditItem(item)" class="action-btn bg-primary px-3 py-1 text-sm">Edit</button>
                                        <button @click="onDeleteItem(item.id)" class="action-btn bg-danger px-3 py-1 text-sm">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="noticeData.length === 0">
                                <td colspan="5" class="py-10 text-center text-gray-500">No notices found. Click "Add Notice" to begin.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Form View -->
            <div v-if="viewMode === 'form' && editingItem">
                <div class="rounded-sm border border-stroke bg-gray-50 p-4 dark:bg-meta-4">
                    <h5 class="mb-4 text-lg font-medium text-black dark:text-white">
                        {{ editingItem.id > 1000000000000 ? 'Add Notice' : 'Edit Notice' }}
                    </h5>
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Title *</label>
                            <input v-model="editingItem.title" type="text" class="form-input" />
                        </div>
                        <div>
                            <label class="form-label">Content *</label>
                            <TiptapEditor v-model="editingItem.content" />
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label">Category *</label>
                                <input v-model="editingItem.category" type="text" class="form-input" />
                            </div>
                            <div>
                                <label class="form-label">Department *</label>
                                <input v-model="editingItem.department" type="text" class="form-input" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label">Date</label>
                                <input v-model="editingItem.date" type="date" class="form-input" />
                            </div>
                            <div>
                                <label class="form-label">Valid Until</label>
                                <input v-model="editingItem.validUntil" type="date" class="form-input" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label">Priority</label>
                                <select v-model="editingItem.priority" class="form-input">
                                    <option>high</option>
                                    <option>medium</option>
                                    <option>low</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Link URL (auto-generated)</label>
                                <input v-model="editingItem.link" type="text" class="form-input bg-gray-100 dark:bg-gray-700" disabled />
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Attachments (comma-separated)</label>
                            <input
                                :value="editingItem.attachments.join(', ')"
                                @input="editingItem.attachments = ($event.target as HTMLInputElement).value.split(',').map((s) => s.trim())"
                                type="text"
                                class="form-input"
                            />
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button @click="onCancelEdit" class="action-btn bg-gray-500">Cancel</button>
                    <button @click="onSaveItem" :disabled="!isValidConfiguration" class="action-btn bg-success">
                        {{ editingItem.id > 1000000000000 ? 'Add Notice' : 'Update Notice' }}
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
