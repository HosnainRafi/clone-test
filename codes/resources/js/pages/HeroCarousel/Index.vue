<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3'; // Import router if needed for save logic
import { computed, ref, watch } from 'vue';

// Define interfaces
interface HeroSlide {
    // Keep your existing HeroSlide interface
    id?: number; // Keep using ID for local list management
    title: string;
    subtitle: string;
    description: string;
    image: string;
    fallbackGradient: string;
    ctaText: string;
    ctaLink: string;
    secondaryCta?: {
        text: string;
        link: string;
    } | null;
}

interface PageProps {
    siteData: any; // You might not need siteData directly if only using siteId
    heroSlides: HeroSlide[]; // Data from Laravel
    siteId: number | null;
}

const props = defineProps<PageProps>();
const { siteId } = props;

// --- STATE MANAGEMENT ---
const heroSlidesData = ref<HeroSlide[]>([]); // Local copy for editing
const isSaving = ref(false);
const message = ref('');
const messageType = ref<'success' | 'error' | ''>();

// --- NEW STATE FOR LIST/FORM VIEW ---
const viewMode = ref<'list' | 'form'>('list'); // Start in list view
const editingItem = ref<HeroSlide | null>(null); // Holds the slide being added/edited

// --- METHODS ---

// Initialize hero slides from props, ensuring local IDs
const initializeHeroSlides = () => {
    const slides = props.heroSlides || [];
    // Ensure each slide has a unique local ID for list keys and editing
    heroSlidesData.value = JSON.parse(JSON.stringify(slides)).map((slide: HeroSlide, index: number) => ({
        ...slide,
        // Use existing ID if present, otherwise generate a temporary one
        id: slide.id || Date.now() + index,
        // Ensure secondaryCta is null if empty object/undefined from backend
        secondaryCta: slide.secondaryCta && slide.secondaryCta.text && slide.secondaryCta.link ? slide.secondaryCta : null,
    }));
    viewMode.value = 'list'; // Reset to list view on init/reload
    editingItem.value = null;
};
initializeHeroSlides(); // Initialize on load

// Watch for prop changes (e.g., after saving) to re-initialize
watch(
    () => props.heroSlides,
    (newSlides) => {
        initializeHeroSlides();
    },
    { deep: true },
);

const hasUnsavedChanges = computed(() => {
    // Compare local data with the original prop data
    // Note: Simple JSON comparison might fail if order changes or IDs differ
    // A more robust deep comparison might be needed for complex cases.
    try {
        // Create comparable versions (strip local IDs if they weren't in original props)
        const currentComparable = heroSlidesData.value.map(({ id, ...rest }) => ({ ...rest }));
        const originalComparable = (props.heroSlides || []).map(({ id, ...rest }) => ({ ...rest }));
        return JSON.stringify(currentComparable) !== JSON.stringify(originalComparable);
    } catch {
        return false; // Error during comparison
    }
});

// Validation for the *currently editing* slide in the form
const isEditingItemValid = computed(() => {
    if (viewMode.value === 'form' && editingItem.value) {
        const slide = editingItem.value;
        // Check required fields
        if (
            !slide.title?.trim() ||
            !slide.subtitle?.trim() ||
            !slide.description?.trim() ||
            !slide.ctaText?.trim() ||
            !slide.ctaLink?.trim() ||
            !slide.fallbackGradient?.trim()
        ) {
            return false;
        }
        // If secondary CTA exists, check its fields
        if (slide.secondaryCta && (!slide.secondaryCta.text?.trim() || !slide.secondaryCta.link?.trim())) {
            return false;
        }
        return true;
    }
    return false; // Not in form mode or no item being edited
});

const showMessage = (msg: string, type: 'success' | 'error') => {
    message.value = msg;
    messageType.value = type;
    setTimeout(() => {
        message.value = '';
        messageType.value = undefined;
    }, 5000);
};

// --- CRUD Methods ---

const onAddItem = () => {
    // Create a new blank slide object
    editingItem.value = {
        id: Date.now(), // Temporary ID for the new item
        title: '',
        subtitle: '',
        description: '',
        image: '/images/carousel/default.jpg', // Default image
        fallbackGradient: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)', // Default gradient
        ctaText: '',
        ctaLink: '#',
        secondaryCta: null,
    };
    viewMode.value = 'form'; // Switch to form view
};

const onEditItem = (item: HeroSlide) => {
    // Deep copy the selected item for editing to avoid modifying the list directly
    editingItem.value = JSON.parse(JSON.stringify(item));
    viewMode.value = 'form'; // Switch to form view
};

const onDeleteItem = (id: number) => {
    if (!confirm('Are you sure you want to delete this slide? This cannot be undone locally until saved.')) {
        return;
    }
    const index = heroSlidesData.value.findIndex((slide) => slide.id === id);
    if (index > -1) {
        heroSlidesData.value.splice(index, 1);
        showMessage('Slide removed locally. Click "Save All Changes" to finalize.', 'success');
    }
};

// Saves the item being edited (in editingItem) back to the local list (heroSlidesData)
const onSaveItem = () => {
    if (!editingItem.value || !isEditingItemValid.value) {
        showMessage('Please fill all required fields (*) correctly.', 'error');
        return;
    }

    // Clean up: Ensure secondaryCta is null if fields are empty
    if (editingItem.value.secondaryCta && (!editingItem.value.secondaryCta.text?.trim() || !editingItem.value.secondaryCta.link?.trim())) {
        editingItem.value.secondaryCta = null;
    }

    const index = heroSlidesData.value.findIndex((slide) => slide.id === editingItem.value!.id);
    if (index > -1) {
        // Update existing item in the list
        heroSlidesData.value[index] = editingItem.value;
    } else {
        // Add new item to the list
        heroSlidesData.value.push(editingItem.value);
    }
    viewMode.value = 'list'; // Go back to list view
    editingItem.value = null; // Clear editing state
    showMessage('Changes staged locally. Click "Save All Changes" to persist.', 'success');
};

const onCancelEdit = () => {
    editingItem.value = null; // Clear editing state
    viewMode.value = 'list'; // Go back to list view
};

const addSecondaryCta = () => {
    if (editingItem.value) {
        editingItem.value.secondaryCta = { text: '', link: '#' };
    }
};

const removeSecondaryCta = () => {
    if (editingItem.value) {
        editingItem.value.secondaryCta = null;
    }
};

const resetToOriginal = () => {
    if (confirm('Discard all unsaved changes and reload original data?')) {
        initializeHeroSlides();
        showMessage('Configuration reset to original state.', 'success');
    }
};

// Saves ALL local changes (heroSlidesData) to the backend
const validateAndSave = async () => {
    if (!props.siteId) return showMessage('Site ID is missing.', 'error');
    if (!hasUnsavedChanges.value) return showMessage('No changes to save.', 'success');

    // Optional: Add a final validation check across all slides before saving
    const allSlidesValid = heroSlidesData.value.every(
        (slide) =>
            slide.title?.trim() &&
            slide.subtitle?.trim() &&
            slide.description?.trim() &&
            slide.ctaText?.trim() &&
            slide.ctaLink?.trim() &&
            slide.fallbackGradient?.trim() &&
            (!slide.secondaryCta || (slide.secondaryCta.text?.trim() && slide.secondaryCta.link?.trim())),
    );
    if (!allSlidesValid) {
        return showMessage('One or more slides have missing required fields. Please edit and complete them before saving.', 'error');
    }

    isSaving.value = true;
    try {
        // Prepare data for saving (remove temporary frontend IDs)
        const dataToSave = heroSlidesData.value.map(({ id, ...rest }) => {
            // Ensure secondaryCta is null if empty, before sending
            if (rest.secondaryCta && (!rest.secondaryCta.text?.trim() || !rest.secondaryCta.link?.trim())) {
                rest.secondaryCta = null;
            }
            return rest;
        });

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch('/hero-carousel/save', {
            // Use your existing save route
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                Accept: 'application/json',
            },
            body: JSON.stringify({
                heroSlides: dataToSave, // The key your backend expects
                siteId: props.siteId,
            }),
        });
        const result = await response.json();

        if (response.ok && result.success) {
            showMessage(result.message, 'success');
            // Reload page data to get fresh props from server
            router.reload({
                only: ['heroSlides'], // Only fetch updated heroSlides
                onSuccess: () => initializeHeroSlides(), // Re-initialize local state
            });
        } else {
            if (response.status === 422 && result.errors) {
                const errorMessages = Object.values(result.errors).flat().join(' ');
                showMessage(`Validation failed: ${errorMessages}`, 'error');
            } else {
                showMessage(result.message || 'Save failed with status: ' + response.status, 'error');
            }
        }
    } catch (err: any) {
        console.error('Save error:', err);
        showMessage('Network error: ' + err.message, 'error');
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
                        <h4 class="text-xl font-semibold text-black dark:text-white">Hero Carousel Management</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Site ID: {{ siteId || 'Not Available' }}
                            <span v-if="hasUnsavedChanges" class="ml-2 text-warning">• Unsaved Changes</span>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button v-if="viewMode === 'list'" @click="onAddItem" class="action-btn bg-primary">Add Hero Slide</button>
                        <button v-if="hasUnsavedChanges && viewMode === 'list'" @click="resetToOriginal" class="action-btn bg-secondary">
                            Discard All
                        </button>
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
                                <th class="min-w-[250px] px-4 py-4 font-medium text-black dark:text-white">Title</th>
                                <th class="min-w-[250px] px-4 py-4 font-medium text-black dark:text-white">Subtitle</th>
                                <th class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white">Primary CTA</th>
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="slide in heroSlidesData" :key="slide.id">
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="line-clamp-2 text-black dark:text-white">{{ slide.title }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="line-clamp-2 text-black dark:text-white">{{ slide.subtitle }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">{{ slide.ctaText }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <div class="flex items-center space-x-3.5">
                                        <button @click="onEditItem(slide)" class="action-btn bg-primary px-3 py-1 text-sm">Edit</button>
                                        <button @click="onDeleteItem(slide.id!)" class="action-btn bg-danger px-3 py-1 text-sm">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="heroSlidesData.length === 0">
                                <td colspan="4" class="py-10 text-center text-gray-500">No hero slides found. Click "Add Hero Slide" to begin.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-if="viewMode === 'form' && editingItem">
                <form @submit.prevent="onSaveItem" novalidate>
                    <div class="rounded-sm border border-stroke bg-gray-50 p-6 dark:border-strokedark dark:bg-meta-4">
                        <h5 class="mb-6 text-lg font-medium text-black dark:text-white">
                            {{ editingItem.id! > 1000000000000 ? 'Add New Hero Slide' : `Edit Slide: ${editingItem.title}` }}
                        </h5>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-4.5">
                                <div>
                                    <label class="form-label">Slide Title <span class="text-meta-1">*</span></label
                                    ><input
                                        v-model.trim="editingItem.title"
                                        type="text"
                                        placeholder="Enter slide title"
                                        class="form-input"
                                        required
                                    />
                                </div>
                                <div>
                                    <label class="form-label">Subtitle <span class="text-meta-1">*</span></label
                                    ><input
                                        v-model.trim="editingItem.subtitle"
                                        type="text"
                                        placeholder="Enter slide subtitle"
                                        class="form-input"
                                        required
                                    />
                                </div>
                                <div>
                                    <label class="form-label">Description <span class="text-meta-1">*</span></label
                                    ><textarea
                                        v-model.trim="editingItem.description"
                                        rows="4"
                                        placeholder="Enter slide description"
                                        class="form-input"
                                        required
                                    ></textarea>
                                </div>
                                <div>
                                    <label class="form-label">Background Image URL</label
                                    ><input
                                        v-model.trim="editingItem.image"
                                        type="text"
                                        placeholder="/images/carousel/slide.jpg"
                                        class="form-input"
                                    />
                                </div>
                                <div>
                                    <label class="form-label">Fallback Gradient <span class="text-meta-1">*</span></label
                                    ><input
                                        v-model.trim="editingItem.fallbackGradient"
                                        type="text"
                                        placeholder="linear-gradient(...)"
                                        class="form-input"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="space-y-4.5">
                                <div>
                                    <label class="form-label">Primary CTA Text <span class="text-meta-1">*</span></label
                                    ><input v-model.trim="editingItem.ctaText" type="text" placeholder="Learn More" class="form-input" required />
                                </div>
                                <div>
                                    <label class="form-label">Primary CTA Link <span class="text-meta-1">*</span></label
                                    ><input
                                        v-model.trim="editingItem.ctaLink"
                                        type="text"
                                        placeholder="/about or https://example.com"
                                        class="form-input"
                                        required
                                    />
                                </div>
                                <div class="rounded border border-stroke bg-white p-4 dark:border-strokedark dark:bg-boxdark">
                                    <div class="mb-3 flex items-center justify-between">
                                        <h6 class="font-medium text-black dark:text-white">Secondary CTA (Optional)</h6>
                                        <button
                                            v-if="!editingItem.secondaryCta"
                                            @click="addSecondaryCta"
                                            type="button"
                                            class="action-btn bg-success px-3 py-1 text-sm"
                                        >
                                            Add
                                        </button>
                                        <button v-else @click="removeSecondaryCta" type="button" class="action-btn bg-danger px-3 py-1 text-sm">
                                            Remove
                                        </button>
                                    </div>
                                    <div v-if="editingItem.secondaryCta" class="space-y-3">
                                        <div>
                                            <label class="form-label !mb-1 text-xs">Text <span class="text-meta-1">*</span></label
                                            ><input
                                                v-model.trim="editingItem.secondaryCta.text"
                                                type="text"
                                                class="form-input !py-2 text-sm"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label class="form-label !mb-1 text-xs">Link <span class="text-meta-1">*</span></label
                                            ><input
                                                v-model.trim="editingItem.secondaryCta.link"
                                                type="text"
                                                class="form-input !py-2 text-sm"
                                                required
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="onCancelEdit" class="action-btn bg-graydark dark:bg-meta-4">Cancel</button>
                        <button
                            type="submit"
                            :disabled="!isEditingItemValid"
                            class="action-btn disabled:bg-opacity-50 bg-success disabled:cursor-not-allowed"
                        >
                            {{ editingItem.id! > 1000000000000 ? 'Add Slide to List' : 'Update Slide in List' }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-6 flex justify-end" v-if="hasUnsavedChanges && viewMode === 'list'">
                <button
                    @click="validateAndSave"
                    :disabled="isSaving || !siteId || !hasUnsavedChanges"
                    class="save-btn"
                    :class="{
                        'hover:bg-opacity-90 bg-primary': hasUnsavedChanges,
                        'cursor-not-allowed bg-gray-400': isSaving || !siteId || !hasUnsavedChanges,
                    }"
                >
                    <svg
                        v-if="isSaving"
                        class="mr-3 -ml-1 h-5 w-5 animate-spin text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>
                    {{ isSaving ? 'Saving...' : 'Save All Changes to Server' }}
                </button>
            </div>

            <details class="mt-6" v-if="viewMode === 'list'">
                <summary class="cursor-pointer text-sm font-medium text-gray-600 hover:text-primary dark:text-gray-400">
                    View Current JSON Configuration
                </summary>
                <pre class="mt-2 max-h-60 overflow-auto rounded bg-gray-100 p-4 text-xs dark:bg-gray-800">{{
                    JSON.stringify(heroSlidesData, null, 2)
                }}</pre>
            </details>
        </div>
    </DefaultLayout>
</template>

<style scoped>
/* Re-use styles from other admin pages */
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
textarea.form-input {
    @apply min-h-[120px];
} /* Adjust height for description */
.form-checkbox {
    @apply h-5 w-5 cursor-pointer rounded border-stroke text-primary focus:ring-transparent dark:border-strokedark;
}
.save-btn {
    @apply inline-flex items-center justify-center rounded-md px-6 py-3 text-center font-medium text-white transition-colors;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
/* Required indicator */
.text-meta-1 {
    color: #dc3545;
}
/* Input with error */
.border-danger {
    border-color: #dc3545 !important;
}
</style>
