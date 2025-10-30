<script setup lang="ts">
import TiptapEditor from '@/components/TiptapEditor.vue';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { computed, ref } from 'vue';

// --- INTERFACES & PROPS ---
// Interface matching the detailed structure
interface PublicationItem {
    id: number;
    title: string;
    abstract: string;
    authors: string[];
    correspondingAuthor: string;
    journal: string;
    journalRank: string;
    impactFactor: number;
    publishDate: string;
    volume?: string;
    issue?: string;
    pages?: string;
    doi?: string;
    category: string;
    keywords: string[];
    citations: number;
    downloads: number;
    openAccess: boolean;
    featured: boolean;
    fallbackGradient: string;
    pdfUrl?: string;
    journalUrl?: string;
}
interface PageProps {
    publicationItems: PublicationItem[];
    siteId: number | null;
}
const props = defineProps<PageProps>();
const { siteId } = props; // Destructure siteId for easier use

// --- STATE MANAGEMENT ---
const publicationData = ref<PublicationItem[]>([]);
const isSaving = ref(false);
const message = ref('');
const messageType = ref<'success' | 'error' | ''>();
const viewMode = ref<'list' | 'form'>('list');
const editingItem = ref<PublicationItem | null>(null);

// --- METHODS ---
const initializeItems = () => {
    // Ensure arrays are initialized properly, even if null/undefined in props
    const items = props.publicationItems || [];
    publicationData.value = JSON.parse(JSON.stringify(items)).map((item: PublicationItem) => ({
        ...item,
        authors: item.authors || [],
        keywords: item.keywords || [],
    }));
};
initializeItems();

// Remove hasUnsavedChanges since we're saving immediately

// Updated validation: Ensure required fields are present and have values
const isValidConfiguration = computed(() => {
    if (viewMode.value === 'form' && editingItem.value) {
        // Check for non-empty strings and valid arrays/numbers
        return (
            editingItem.value.title?.trim() &&
            editingItem.value.abstract?.trim() && // Check Tiptap content isn't just empty tags like <p></p>
            editingItem.value.authors?.length > 0 &&
            editingItem.value.authors.every((a) => a?.trim()) &&
            editingItem.value.correspondingAuthor?.trim() &&
            editingItem.value.journal?.trim() &&
            editingItem.value.journalRank && // Select ensures a value
            editingItem.value.impactFactor != null && // Check it's not null/undefined
            editingItem.value.publishDate && // Date input usually ensures a value if touched
            editingItem.value.category?.trim() &&
            editingItem.value.keywords?.length > 0 &&
            editingItem.value.keywords.every((k) => k?.trim()) &&
            editingItem.value.fallbackGradient?.trim()
        );
    }
    return true; // Allow saving if not in form mode
});

const showMessage = (msg: string, type: 'success' | 'error') => {
    message.value = msg;
    messageType.value = type;
    setTimeout(() => {
        message.value = '';
    }, 5000); // Message disappears after 5 seconds
};

// Updated 'onAddItem' to include all fields with sensible defaults
const onAddItem = () => {
    editingItem.value = {
        id: Date.now(), // Temporary ID for new items
        title: '', // Start empty for better UX
        abstract: '<p></p>', // Default empty paragraph for Tiptap
        authors: [],
        correspondingAuthor: '',
        journal: '',
        journalRank: 'Q1', // Default rank
        impactFactor: 0.0,
        publishDate: new Date().toISOString().split('T')[0], // Default to today
        volume: '',
        issue: '',
        pages: '',
        doi: '',
        category: '',
        keywords: [],
        citations: 0,
        downloads: 0,
        openAccess: false, // Default to false
        featured: false, // Default to false
        fallbackGradient: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)', // Default gradient
        pdfUrl: '',
        journalUrl: '',
    };
    viewMode.value = 'form';
};

const onEditItem = (item: PublicationItem) => {
    // Ensure arrays exist before stringifying/parsing
    item.authors = item.authors || [];
    item.keywords = item.keywords || [];
    editingItem.value = JSON.parse(JSON.stringify(item)); // Deep copy for editing
    viewMode.value = 'form';
};

const onDeleteItem = async (id: number) => {
    // Add confirmation dialog
    if (!confirm('Are you sure you want to delete this publication? This action cannot be undone.')) {
        return;
    }

    const index = publicationData.value.findIndex((item) => item.id === id);
    if (index > -1) {
        publicationData.value.splice(index, 1);

        // Save to server immediately
        isSaving.value = true;
        try {
            const dataToSave = publicationData.value.map(({ id, ...rest }) => {
                rest.impactFactor = Number(rest.impactFactor) || 0;
                rest.citations = Number(rest.citations) || 0;
                rest.downloads = Number(rest.downloads) || 0;
                rest.openAccess = Boolean(rest.openAccess);
                rest.featured = Boolean(rest.featured);
                return id > 1000000000000 ? rest : { id, ...rest };
            });

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const response = await fetch('/admin/publications-section', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                    Accept: 'application/json',
                },
                body: JSON.stringify({ publicationItems: dataToSave, siteId: props.siteId }),
            });
            const result = await response.json();

            if (response.ok && result.success) {
                showMessage(result.message || 'Publication deleted successfully!', 'success');
                // Update local data with server response
                publicationData.value = result.publicationItems;
            } else {
                if (response.status === 422 && result.errors) {
                    const errorMessages = Object.values(result.errors).flat().join(' ');
                    showMessage(`Validation failed: ${errorMessages}`, 'error');
                } else {
                    showMessage(result.message || 'Server error during delete.', 'error');
                }
            }
        } catch (error: any) {
            console.error('Delete error:', error);
            showMessage(`Network or delete error: ${error.message || 'Unknown error'}`, 'error');
        } finally {
            isSaving.value = false;
        }
    }
};

const onSaveItem = async () => {
    if (!editingItem.value || !isValidConfiguration.value) {
        showMessage('Please fill all required fields (*) correctly.', 'error');
        return;
    }
    // Clean up potentially empty optional fields
    const cleanItem = { ...editingItem.value }; // Copy item
    if (!cleanItem.volume?.trim()) cleanItem.volume = undefined;
    if (!cleanItem.issue?.trim()) cleanItem.issue = undefined;
    if (!cleanItem.pages?.trim()) cleanItem.pages = undefined;
    if (!cleanItem.doi?.trim()) cleanItem.doi = undefined;
    if (!cleanItem.pdfUrl?.trim()) cleanItem.pdfUrl = undefined;
    if (!cleanItem.journalUrl?.trim()) cleanItem.journalUrl = undefined;
    // Ensure numeric fields are numbers
    cleanItem.impactFactor = Number(cleanItem.impactFactor) || 0;
    cleanItem.citations = Number(cleanItem.citations) || 0;
    cleanItem.downloads = Number(cleanItem.downloads) || 0;

    const index = publicationData.value.findIndex((item) => item.id === cleanItem.id);
    if (index > -1) {
        // Update existing item
        publicationData.value[index] = cleanItem;
    } else {
        // Add new item
        publicationData.value.push(cleanItem);
    }

    // Save to server immediately
    isSaving.value = true;
    try {
        const dataToSave = publicationData.value.map(({ id, ...rest }) => {
            rest.impactFactor = Number(rest.impactFactor) || 0;
            rest.citations = Number(rest.citations) || 0;
            rest.downloads = Number(rest.downloads) || 0;
            rest.openAccess = Boolean(rest.openAccess);
            rest.featured = Boolean(rest.featured);
            return id > 1000000000000 ? rest : { id, ...rest };
        });

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch('/admin/publications-section', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                Accept: 'application/json',
            },
            body: JSON.stringify({ publicationItems: dataToSave, siteId: props.siteId }),
        });
        const result = await response.json();

        if (response.ok && result.success) {
            showMessage(result.message || 'Publication saved successfully!', 'success');
            // Update local data with server response
            publicationData.value = result.publicationItems;
            viewMode.value = 'list';
            editingItem.value = null;
        } else {
            if (response.status === 422 && result.errors) {
                const errorMessages = Object.values(result.errors).flat().join(' ');
                showMessage(`Validation failed: ${errorMessages}`, 'error');
            } else {
                showMessage(result.message || 'Server error during save.', 'error');
            }
        }
    } catch (error: any) {
        console.error('Save error:', error);
        showMessage(`Network or saving error: ${error.message || 'Unknown error'}`, 'error');
    } finally {
        isSaving.value = false;
    }
};

const onCancelEdit = () => {
    editingItem.value = null; // Clear editing state
    viewMode.value = 'list';
};

// Removed unused functions since we're saving immediately

// Helper for array inputs/outputs
const arrayInput = (value: string): string[] =>
    value
        .split(',')
        .map((s) => s.trim())
        .filter((s) => s !== '');
const arrayOutput = (arr: string[] | undefined): string => (arr || []).join(', ');
</script>

<template>
    <DefaultLayout>
        <div
            class="shadow-default rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 sm:px-7.5 xl:pb-1 dark:border-strokedark dark:bg-boxdark"
        >
            <div class="mb-6">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h4 class="text-xl font-semibold text-black dark:text-white">Top Publications Management</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Site ID: {{ siteId || 'N/A' }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button v-if="viewMode === 'list'" @click="onAddItem" class="action-btn bg-primary">Add Publication</button>
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

            <div v-if="viewMode === 'list'">
                <div class="max-w-full overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-2 text-left dark:bg-meta-4">
                                <th class="min-w-[220px] px-4 py-4 font-medium text-black dark:text-white">Title</th>
                                <th class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white">Journal</th>
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Rank</th>
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Date</th>
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in publicationData" :key="item.id">
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="line-clamp-2 text-black dark:text-white">{{ item.title }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">{{ item.journal }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-medium"
                                        :class="item.journalRank === 'Q1' ? 'bg-success/10 text-success' : 'bg-warning/10 text-warning'"
                                        >{{ item.journalRank }}</span
                                    >
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">{{ item.publishDate }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <div class="flex items-center space-x-3.5">
                                        <button @click="onEditItem(item)" class="action-btn bg-primary px-3 py-1 text-sm">Edit</button>
                                        <button @click="onDeleteItem(item.id)" class="action-btn bg-danger px-3 py-1 text-sm">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="publicationData.length === 0">
                                <td colspan="5" class="py-10 text-center text-gray-500">No publications found. Click "Add Publication" to begin.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-if="viewMode === 'form' && editingItem">
                <form @submit.prevent="onSaveItem" novalidate>
                    <div class="rounded-sm border border-stroke bg-gray-50 p-6 dark:border-strokedark dark:bg-meta-4">
                        <h5 class="mb-6 text-lg font-medium text-black dark:text-white">
                            {{ editingItem.id > 1000000000000 ? 'Add New Publication' : `Edit Publication: ${editingItem.title}` }}
                        </h5>
                        <div class="space-y-4.5">
                            <h6 class="text-sm font-medium text-black dark:text-white">Required Information</h6>
                            <div>
                                <label class="form-label">Title <span class="text-meta-1">*</span></label
                                ><input
                                    v-model.trim="editingItem.title"
                                    type="text"
                                    placeholder="Enter publication title"
                                    class="form-input"
                                    required
                                />
                            </div>
                            <div>
                                <label class="form-label">Abstract <span class="text-meta-1">*</span></label
                                ><TiptapEditor v-model="editingItem.abstract" required />
                            </div>
                            <div>
                                <label class="form-label">Authors (comma-separated) <span class="text-meta-1">*</span></label
                                ><input
                                    :value="arrayOutput(editingItem.authors)"
                                    @input="editingItem.authors = arrayInput(($event.target as HTMLInputElement).value)"
                                    type="text"
                                    placeholder="e.g., John Doe, Jane Smith"
                                    class="form-input"
                                    required
                                />
                            </div>
                            <div>
                                <label class="form-label">Corresponding Author <span class="text-meta-1">*</span></label
                                ><input
                                    v-model.trim="editingItem.correspondingAuthor"
                                    type="text"
                                    placeholder="Enter corresponding author's name"
                                    class="form-input"
                                    required
                                />
                            </div>
                            <div>
                                <label class="form-label">Journal <span class="text-meta-1">*</span></label
                                ><input v-model.trim="editingItem.journal" type="text" placeholder="Enter journal name" class="form-input" required />
                            </div>
                            <div class="grid grid-cols-1 gap-4.5 sm:grid-cols-3">
                                <div>
                                    <label class="form-label">Journal Rank <span class="text-meta-1">*</span></label
                                    ><select v-model="editingItem.journalRank" class="form-input" required>
                                        <option>Q1</option>
                                        <option>Q2</option>
                                        <option>Q3</option>
                                        <option>Q4</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Impact Factor <span class="text-meta-1">*</span></label
                                    ><input
                                        v-model.number="editingItem.impactFactor"
                                        type="number"
                                        step="0.001"
                                        placeholder="e.g., 3.870"
                                        class="form-input"
                                        required
                                        min="0"
                                    />
                                </div>
                                <div>
                                    <label class="form-label">Publish Date <span class="text-meta-1">*</span></label
                                    ><input v-model="editingItem.publishDate" type="date" class="form-input" required />
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Category <span class="text-meta-1">*</span></label
                                ><input
                                    v-model.trim="editingItem.category"
                                    type="text"
                                    placeholder="e.g., Artificial Intelligence"
                                    class="form-input"
                                    required
                                />
                            </div>
                            <div>
                                <label class="form-label">Keywords (comma-separated) <span class="text-meta-1">*</span></label
                                ><input
                                    :value="arrayOutput(editingItem.keywords)"
                                    @input="editingItem.keywords = arrayInput(($event.target as HTMLInputElement).value)"
                                    type="text"
                                    placeholder="e.g., Deep Learning, NLP"
                                    class="form-input"
                                    required
                                />
                            </div>
                            <div>
                                <label class="form-label">Fallback Gradient <span class="text-meta-1">*</span></label
                                ><input
                                    v-model.trim="editingItem.fallbackGradient"
                                    type="text"
                                    placeholder="e.g., linear-gradient(...)"
                                    class="form-input"
                                    required
                                />
                            </div>

                            <h6 class="pt-4 text-sm font-medium text-black dark:text-white">Optional Information</h6>
                            <div class="grid grid-cols-1 gap-4.5 sm:grid-cols-3">
                                <div>
                                    <label class="form-label">Volume</label
                                    ><input v-model.trim="editingItem.volume" type="text" placeholder="e.g., 17" class="form-input" />
                                </div>
                                <div>
                                    <label class="form-label">Issue</label
                                    ><input v-model.trim="editingItem.issue" type="text" placeholder="e.g., 3" class="form-input" />
                                </div>
                                <div>
                                    <label class="form-label">Pages</label
                                    ><input v-model.trim="editingItem.pages" type="text" placeholder="e.g., 245-267" class="form-input" />
                                </div>
                            </div>
                            <div>
                                <label class="form-label">DOI</label
                                ><input v-model.trim="editingItem.doi" type="text" placeholder="e.g., 10.1109/TLT.2024.1234567" class="form-input" />
                            </div>
                            <div class="grid grid-cols-1 gap-4.5 sm:grid-cols-2">
                                <div>
                                    <label class="form-label">Citations</label
                                    ><input v-model.number="editingItem.citations" type="number" placeholder="e.g., 156" class="form-input" min="0" />
                                </div>
                                <div>
                                    <label class="form-label">Downloads</label
                                    ><input
                                        v-model.number="editingItem.downloads"
                                        type="number"
                                        placeholder="e.g., 2340"
                                        class="form-input"
                                        min="0"
                                    />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4.5 sm:grid-cols-2">
                                <div>
                                    <label class="form-label">PDF URL</label
                                    ><input v-model.trim="editingItem.pdfUrl" type="url" placeholder="Enter URL to PDF" class="form-input" />
                                </div>
                                <div>
                                    <label class="form-label">Journal URL</label
                                    ><input
                                        v-model.trim="editingItem.journalUrl"
                                        type="url"
                                        placeholder="Enter URL to Journal/DOI link"
                                        class="form-input"
                                    />
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-6">
                                <div class="flex items-center gap-2">
                                    <input v-model="editingItem.openAccess" type="checkbox" id="openAccess" class="form-checkbox" /><label
                                        for="openAccess"
                                        class="form-label mb-0 cursor-pointer"
                                        >Open Access</label
                                    >
                                </div>
                                <div class="flex items-center gap-2">
                                    <input v-model="editingItem.featured" type="checkbox" id="featured" class="form-checkbox" /><label
                                        for="featured"
                                        class="form-label mb-0 cursor-pointer"
                                        >Featured (Show on Homepage?)</label
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="onCancelEdit" class="action-btn bg-graydark dark:bg-meta-4">Cancel</button>
                        <button
                            type="submit"
                            :disabled="!isValidConfiguration"
                            class="action-btn disabled:bg-opacity-50 bg-success disabled:cursor-not-allowed"
                        >
                            {{ editingItem.id > 1000000000000 ? 'Add Publication' : 'Update Publication' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Removed save all changes button since we're saving immediately -->
        </div>
    </DefaultLayout>
</template>

<style scoped>
@reference '../../../../resources/css/app.css';
.action-btn {
    @apply inline-flex items-center justify-center rounded-md px-4 py-2 text-center text-sm font-medium text-white transition;
}
.form-label {
    @apply mb-2.5 block font-medium text-black dark:text-white;
}
.form-input {
    @apply w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary;
}
/* Apply input styles to select too */
select.form-input {
    @apply appearance-none bg-whiter pr-8 dark:bg-form-input;
} /* Add custom arrow later if needed */
.form-checkbox {
    @apply h-5 w-5 cursor-pointer rounded border-stroke text-primary focus:ring-transparent dark:border-strokedark;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

:deep(.ProseMirror p.is-editor-empty:first-child::before) {
    content: attr(data-placeholder);
    float: left;
    color: #adb5bd; /* Example placeholder color */
    pointer-events: none;
    height: 0;
}
:deep(.ProseMirror p) {
    margin: 0; /* Override default paragraph margins */
}
/* Style for required indicator */
.text-meta-1 {
    color: #dc3545;
} /* Example red color */
</style>
