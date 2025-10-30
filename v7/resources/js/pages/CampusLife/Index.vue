<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// Interface for a single campus life item
interface CampusLifeItem {
    id: number;
    title: string;
    category: string;
    description: string;
    image: string;
    fallbackGradient: string;
    iconName: string;
    features: string[];
    link: string;
    featured: boolean;
    isActive: boolean;
    displayOrder: number;
}

// Props from the backend
const props = defineProps<{
    campusLifeItems: CampusLifeItem[];
    siteId: number | null;
}>();

const { siteId } = props;

// State management
const campusLifeData = ref<CampusLifeItem[]>([]);
const isSaving = ref(false);
const message = ref('');
const messageType = ref<'success' | 'error' | ''>('');

// Initialize data
const initializeItems = () => {
    campusLifeData.value = (props.campusLifeItems || []).map((item, index) => ({
        ...item,
        id: item.id || Date.now() + index,
        features: Array.isArray(item.features) ? item.features : [], // Ensure features is an array
    }));
};
initializeItems();

// Computed Properties
const hasUnsavedChanges = computed(() => {
    return JSON.stringify(campusLifeData.value) !== JSON.stringify(props.campusLifeItems);
});

const isValidConfiguration = computed(() => {
    return campusLifeData.value.every((item) => item.title && item.category && item.iconName);
});

const activeItemsCount = computed(() => {
    return campusLifeData.value.filter((item) => item.isActive).length;
});

const sortedItems = computed(() => {
    return [...campusLifeData.value].sort((a, b) => a.displayOrder - b.displayOrder);
});

// Methods
const addItem = () => {
    const newItem: CampusLifeItem = {
        id: Date.now(),
        title: 'New Campus Feature',
        category: 'General',
        description: 'A brief description of this new feature.',
        image: '/images/campus-life/default.jpg',
        fallbackGradient: 'linear-gradient(135deg, #6b7280 0%, #374151 100%)',
        iconName: 'Building',
        features: ['New Feature 1', 'New Feature 2'],
        link: '#',
        featured: false,
        isActive: true,
        displayOrder: campusLifeData.value.length + 1,
    };
    campusLifeData.value.push(newItem);
};

const removeItem = (id: number) => {
    campusLifeData.value = campusLifeData.value.filter((item) => item.id !== id);
    reorderItems();
};

const moveUp = (id: number) => {
    const index = campusLifeData.value.findIndex((item) => item.id === id);
    if (index > 0) {
        [campusLifeData.value[index], campusLifeData.value[index - 1]] = [campusLifeData.value[index - 1], campusLifeData.value[index]];
        reorderItems();
    }
};

const moveDown = (id: number) => {
    const index = campusLifeData.value.findIndex((item) => item.id === id);
    if (index < campusLifeData.value.length - 1) {
        [campusLifeData.value[index], campusLifeData.value[index + 1]] = [campusLifeData.value[index + 1], campusLifeData.value[index]];
        reorderItems();
    }
};

const toggleActive = (id: number) => {
    const item = campusLifeData.value.find((i) => i.id === id);
    if (item) item.isActive = !item.isActive;
};

const reorderItems = () => {
    campusLifeData.value.forEach((item, index) => {
        item.displayOrder = index + 1;
    });
};

const saveConfiguration = async () => {
    if (!siteId) {
        showMessage('Site ID is required to save.', 'error');
        return;
    }
    if (!isValidConfiguration.value) {
        showMessage('Please fill all required fields.', 'error');
        return;
    }
    isSaving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch('/campus-life/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                Accept: 'application/json',
            },
            body: JSON.stringify({ siteId, campusLifeItems: campusLifeData.value }),
        });
        const result = await response.json();
        if (response.ok && result.success) {
            showMessage(result.message, 'success');
            setTimeout(() => router.reload({ only: ['campusLifeItems'] }), 1500);
        } else {
            showMessage(result.message || 'An error occurred.', 'error');
        }
    } catch (e) {
        showMessage('A network error occurred.', 'error');
    } finally {
        isSaving.value = false;
    }
};

const showMessage = (msg: string, type: 'success' | 'error') => {
    message.value = msg;
    messageType.value = type;
    setTimeout(() => {
        message.value = '';
    }, 5000);
};

const resetToOriginal = () => {
    initializeItems();
    showMessage('Configuration has been reset.', 'success');
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
                        <h4 class="text-xl font-semibold text-black dark:text-white">Manage Campus Life Section</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Site ID: {{ siteId || 'N/A' }} | Status:
                            <span :class="isValidConfiguration ? 'text-success' : 'text-danger'">{{
                                isValidConfiguration ? 'Valid' : 'Invalid'
                            }}</span>
                            <span v-if="hasUnsavedChanges" class="ml-2 text-warning">• Unsaved Changes</span>
                        </p>
                        <p class="mt-1 text-xs text-gray-500">Total Items: {{ campusLifeData.length }} | Active: {{ activeItemsCount }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button v-if="hasUnsavedChanges" @click="resetToOriginal" class="rounded-md bg-secondary px-4 py-2 text-white">Reset</button>
                        <button @click="addItem" class="rounded-md bg-primary px-4 py-2 text-white">Add Item</button>
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

            <!-- Items List -->
            <div class="space-y-6">
                <div v-if="campusLifeData.length === 0" class="py-8 text-center text-gray-500">No items found. Click "Add Item" to begin.</div>

                <div
                    v-for="(item, index) in sortedItems"
                    :key="item.id"
                    class="rounded-sm border border-stroke bg-gray-50 p-6 dark:bg-meta-4"
                    :class="{ 'opacity-50': !item.isActive, 'border-l-4 border-l-primary': item.isActive }"
                >
                    <!-- Item Header -->
                    <div class="mb-4 flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-2xl font-bold text-primary">
                                {{ item.displayOrder }}
                            </div>
                            <div>
                                <h5 class="text-lg font-medium text-black dark:text-white">{{ item.title }}</h5>
                                <div class="mt-1 flex items-center gap-2">
                                    <span :class="item.isActive ? 'text-green-600' : 'text-red-600'" class="text-xs font-semibold">{{
                                        item.isActive ? 'Active' : 'Inactive'
                                    }}</span>
                                    <span class="text-xs text-gray-500">•</span>
                                    <span class="text-xs text-gray-500">Category: {{ item.category }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button
                                @click="moveUp(item.id)"
                                :disabled="index === 0"
                                class="rounded-md bg-blue-500 px-2 py-1 text-xs text-white disabled:bg-gray-400"
                            >
                                ↑
                            </button>
                            <button
                                @click="moveDown(item.id)"
                                :disabled="index === sortedItems.length - 1"
                                class="rounded-md bg-blue-500 px-2 py-1 text-xs text-white disabled:bg-gray-400"
                            >
                                ↓
                            </button>
                            <button
                                @click="toggleActive(item.id)"
                                :class="item.isActive ? 'bg-yellow-500' : 'bg-green-500'"
                                class="rounded-md px-3 py-2 text-white"
                            >
                                {{ item.isActive ? 'Deactivate' : 'Activate' }}
                            </button>
                            <button @click="removeItem(item.id)" class="rounded-md bg-danger px-3 py-2 text-white">Remove</button>
                        </div>
                    </div>

                    <!-- Item Fields -->
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white">Title *</label>
                                <input
                                    v-model="item.title"
                                    type="text"
                                    class="w-full rounded border-[1.5px] border-stroke p-3"
                                    :class="!item.title && 'border-danger'"
                                />
                            </div>
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white">Description</label>
                                <textarea v-model="item.description" rows="3" class="w-full rounded border-[1.5px] border-stroke p-3"></textarea>
                            </div>
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white">Features (comma-separated)</label>
                                <input
                                    :value="item.features.join(', ')"
                                    @input="item.features = ($event.target as HTMLInputElement).value.split(',').map((f) => f.trim())"
                                    type="text"
                                    class="w-full rounded border-[1.5px] border-stroke p-3"
                                />
                            </div>
                        </div>
                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white">Category *</label>
                                <input
                                    v-model="item.category"
                                    type="text"
                                    class="w-full rounded border-[1.5px] border-stroke p-3"
                                    :class="!item.category && 'border-danger'"
                                />
                            </div>
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white">Icon Name *</label>
                                <input
                                    v-model="item.iconName"
                                    type="text"
                                    class="w-full rounded border-[1.5px] border-stroke p-3"
                                    :class="!item.iconName && 'border-danger'"
                                />
                            </div>
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white">Image URL</label>
                                <input v-model="item.image" type="text" class="w-full rounded border-[1.5px] border-stroke p-3" />
                            </div>

                            <!-- ✅ FIXED: Added Link and Fallback Gradient Inputs -->
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white">Link URL</label>
                                <input
                                    v-model="item.link"
                                    type="text"
                                    placeholder="/campus-life/feature"
                                    class="w-full rounded border-[1.5px] border-stroke p-3"
                                />
                            </div>
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white">Fallback Gradient</label>
                                <input
                                    v-model="item.fallbackGradient"
                                    type="text"
                                    placeholder="linear-gradient(...)"
                                    class="w-full rounded border-[1.5px] border-stroke p-3"
                                />
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <label class="flex cursor-pointer items-center">
                                    <input type="checkbox" v-model="item.featured" class="sr-only" />
                                    <div class="relative h-6 w-10 rounded-full bg-gray-600">
                                        <div
                                            :class="item.featured ? 'translate-x-4 bg-primary' : 'bg-gray-400'"
                                            class="absolute top-1 left-1 h-4 w-4 rounded-full transition"
                                        ></div>
                                    </div>
                                    <div class="ml-3 text-sm font-medium">Featured</div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Actions -->
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <span v-if="!isValidConfiguration" class="text-danger">Please fix validation errors.</span>
                    <span v-else-if="hasUnsavedChanges" class="text-warning">You have unsaved changes.</span>
                    <span v-else class="text-success">Configuration is up to date.</span>
                </div>
                <button
                    @click="saveConfiguration"
                    :disabled="!isValidConfiguration || isSaving"
                    class="rounded-md px-6 py-3 text-white transition-colors"
                    :class="isValidConfiguration && !isSaving ? 'bg-primary' : 'cursor-not-allowed bg-gray-400'"
                >
                    {{ isSaving ? 'Saving...' : 'Save Campus Life Configuration' }}
                </button>
            </div>

            <details class="mt-6">
                <summary class="cursor-pointer text-sm font-medium text-gray-600">View JSON</summary>
                <pre class="mt-2 max-h-60 overflow-auto rounded bg-gray-100 p-4 text-xs">{{ JSON.stringify(campusLifeData, null, 2) }}</pre>
            </details>
        </div>
    </DefaultLayout>
</template>
