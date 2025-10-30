<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// ✅ SIMPLIFIED: Essential interface only
interface WelcomeItem {
    id?: number;
    title: string;
    description?: string;
    backgroundImage: string;
    videoId?: string;
    buttonText: string;
    isActive: boolean;
    displayOrder: number;
}

interface PageProps {
    siteData: any;
    welcomeItems: WelcomeItem[];
    siteId: number | null;
}

// Receive props from Laravel
const props = defineProps<PageProps>();

// Extract siteId for easier access
const { siteId } = props;

// State management
const welcomeData = ref<WelcomeItem[]>([]);
const isSaving = ref(false);
const message = ref('');
const messageType = ref<'success' | 'error' | ''>('');

// Initialize welcome items from props
const initializeWelcomeItems = () => {
    if (props.welcomeItems && props.welcomeItems.length > 0) {
        welcomeData.value = props.welcomeItems.map((item, index) => ({
            ...item,
            id: item.id || Date.now() + index,
        }));
    } else {
        // ✅ SIMPLIFIED: Default welcome with only essential fields
        welcomeData.value = [
            {
                id: 1,
                title: 'Welcome to MBSTU',
                description:
                    'A leading public university in Bangladesh dedicated to advancing science, technology, and innovation for national development.',
                backgroundImage: 'https://mbstu.ac.bd/wp-content/uploads/2023/08/home-Video.jpg',
                videoId: 'PZ9MHpFet34',
                buttonText: 'Watch Campus Video',
                isActive: true,
                displayOrder: 1,
            },
        ];
    }
};

// Initialize on component mount
initializeWelcomeItems();

// Computed properties
const hasUnsavedChanges = computed(() => {
    return JSON.stringify(welcomeData.value) !== JSON.stringify(props.welcomeItems);
});

const isValidConfiguration = computed(() => {
    try {
        // Check if all welcome items have required fields
        for (const item of welcomeData.value) {
            if (!item.title || !item.buttonText) {
                return false;
            }
        }
        return true;
    } catch {
        return false;
    }
});

const activeWelcomeCount = computed(() => {
    return welcomeData.value.filter((w) => w.isActive).length;
});

const sortedWelcomes = computed(() => {
    return [...welcomeData.value].sort((a, b) => a.displayOrder - b.displayOrder);
});

// ✅ SIMPLIFIED: Methods with only essential functionality
const addWelcome = () => {
    const newWelcome: WelcomeItem = {
        id: Date.now(),
        title: 'New Welcome Section',
        description: 'Add your description here...',
        backgroundImage: 'https://mbstu.ac.bd/wp-content/uploads/2023/08/home-Video.jpg',
        videoId: '',
        buttonText: 'Watch Video',
        isActive: true,
        displayOrder: welcomeData.value.length + 1,
    };
    welcomeData.value.push(newWelcome);
};

const removeWelcome = (id: number) => {
    const index = welcomeData.value.findIndex((item) => item.id === id);
    if (index > -1) {
        welcomeData.value.splice(index, 1);
        // Reorder remaining items
        welcomeData.value.forEach((item, idx) => {
            item.displayOrder = idx + 1;
        });
    }
};

const moveUp = (id: number) => {
    const index = welcomeData.value.findIndex((item) => item.id === id);
    if (index > 0) {
        [welcomeData.value[index], welcomeData.value[index - 1]] = [welcomeData.value[index - 1], welcomeData.value[index]];
        // Update display orders
        welcomeData.value.forEach((item, idx) => {
            item.displayOrder = idx + 1;
        });
    }
};

const moveDown = (id: number) => {
    const index = welcomeData.value.findIndex((item) => item.id === id);
    if (index < welcomeData.value.length - 1) {
        [welcomeData.value[index], welcomeData.value[index + 1]] = [welcomeData.value[index + 1], welcomeData.value[index]];
        // Update display orders
        welcomeData.value.forEach((item, idx) => {
            item.displayOrder = idx + 1;
        });
    }
};

const toggleActive = (id: number) => {
    const item = welcomeData.value.find((w) => w.id === id);
    if (item) {
        item.isActive = !item.isActive;
    }
};

// ✅ SIMPLIFIED: Save function with only essential data
const validateAndSave = async () => {
    if (!props.siteId) {
        showMessage('Site ID is required to save configuration', 'error');
        return;
    }

    if (!isValidConfiguration.value) {
        showMessage('Please fix validation errors before saving', 'error');
        return;
    }

    isSaving.value = true;

    try {
        // ✅ SIMPLIFIED: Prepare only essential data for saving
        const dataToSave = welcomeData.value.map((item) => ({
            title: item.title,
            description: item.description,
            backgroundImage: item.backgroundImage,
            videoId: item.videoId,
            buttonText: item.buttonText,
            isActive: item.isActive,
            displayOrder: item.displayOrder,
        }));

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const response = await fetch('/admin/welcome-section', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                Accept: 'application/json',
            },
            body: JSON.stringify({
                welcomeItems: dataToSave,
                siteId: props.siteId,
            }),
        });

        const responseText = await response.text();
        let result;

        try {
            result = JSON.parse(responseText);
        } catch (parseError) {
            console.error('Failed to parse response as JSON:', parseError);
            showMessage('Server returned invalid response', 'error');
            return;
        }

        if (response.ok && result.success) {
            showMessage(result.message, 'success');
            setTimeout(() => {
                router.reload();
            }, 1500);
        } else {
            showMessage(result.message || 'Save failed', 'error');
        }
    } catch (error) {
        console.error('Save error:', error);
        showMessage('Network error: ' + (error instanceof Error ? error.message : 'Unknown error'), 'error');
    } finally {
        isSaving.value = false;
    }
};

const showMessage = (msg: string, type: 'success' | 'error') => {
    message.value = msg;
    messageType.value = type;
    setTimeout(() => {
        message.value = '';
        messageType.value = '';
    }, 5000);
};

const resetToOriginal = () => {
    initializeWelcomeItems();
    showMessage('Configuration reset to original state', 'success');
};

// ✅ SIMPLIFIED: Extract YouTube video ID from URL
const extractVideoId = (url: string): string => {
    if (!url) return '';
    const regex = /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/;
    const match = url.match(regex);
    return match ? match[1] : url;
};

const updateVideoId = (id: number, url: string) => {
    const item = welcomeData.value.find((w) => w.id === id);
    if (item) {
        item.videoId = extractVideoId(url);
    }
};
</script>

<template>
    <DefaultLayout>
        <div
            class="shadow-default rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 sm:px-7.5 xl:pb-1 dark:border-strokedark dark:bg-boxdark"
        >
            <!-- ✅ SIMPLIFIED: Header -->
            <div class="mb-6">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h4 class="text-xl font-semibold text-black dark:text-white">Welcome Section Management</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Site ID: {{ siteId || 'Not Available' }} | Status:
                            <span :class="isValidConfiguration ? 'text-success' : 'text-danger'">
                                {{ isValidConfiguration ? 'Valid' : 'Invalid Configuration' }}
                            </span>
                            <span v-if="hasUnsavedChanges" class="ml-2 text-warning">• Unsaved Changes</span>
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                            Total Welcome Sections: {{ welcomeData.length }} | Active: {{ activeWelcomeCount }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button
                            @click="resetToOriginal"
                            v-if="hasUnsavedChanges"
                            class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-secondary px-4 py-2 text-center font-medium text-white"
                        >
                            Reset
                        </button>
                        <button
                            @click="addWelcome"
                            class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-center font-medium text-white"
                        >
                            Add Welcome Section
                        </button>
                    </div>
                </div>

                <!-- Status Messages -->
                <div
                    v-if="message"
                    :class="{
                        'border-success bg-success/10 text-success': messageType === 'success',
                        'border-danger bg-danger/10 text-danger': messageType === 'error',
                    }"
                    class="mb-4 rounded-md border px-4 py-3"
                >
                    {{ message }}
                </div>
            </div>

            <!-- ✅ SIMPLIFIED: Welcome Items List -->
            <div class="space-y-6">
                <div v-if="welcomeData.length === 0" class="py-8 text-center text-gray-500 dark:text-gray-400">
                    No welcome sections configured. Click "Add Welcome Section" to get started.
                </div>

                <div
                    v-for="(item, index) in sortedWelcomes"
                    :key="item.id"
                    class="rounded-sm border border-stroke bg-gray-50 p-6 dark:bg-meta-4"
                    :class="{
                        'opacity-50': !item.isActive,
                        'border-l-4 border-l-primary': item.isActive,
                    }"
                >
                    <!-- Item Header -->
                    <div class="mb-4 flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-2xl font-bold text-primary">
                                {{ item.displayOrder }}
                            </div>
                            <div>
                                <h5 class="text-lg font-medium text-black dark:text-white">
                                    {{ item.title }}
                                </h5>
                                <div class="mt-1 flex items-center gap-2">
                                    <span :class="item.isActive ? 'text-green-600' : 'text-red-600'" class="text-xs font-semibold">
                                        {{ item.isActive ? 'Active' : 'Inactive' }}
                                    </span>
                                    <span class="text-xs text-gray-500">•</span>
                                    <span class="text-xs text-gray-500">{{ item.videoId ? 'Has Video' : 'No Video' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <button
                                @click="moveUp(item.id!)"
                                :disabled="index === 0"
                                class="inline-flex items-center justify-center rounded-md bg-blue-500 px-2 py-1 text-center text-xs font-medium text-white hover:bg-blue-600 disabled:bg-gray-400"
                            >
                                ↑
                            </button>
                            <button
                                @click="moveDown(item.id!)"
                                :disabled="index === sortedWelcomes.length - 1"
                                class="inline-flex items-center justify-center rounded-md bg-blue-500 px-2 py-1 text-center text-xs font-medium text-white hover:bg-blue-600 disabled:bg-gray-400"
                            >
                                ↓
                            </button>
                            <button
                                @click="toggleActive(item.id!)"
                                :class="item.isActive ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600'"
                                class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md px-3 py-2 text-center font-medium text-white"
                            >
                                {{ item.isActive ? 'Deactivate' : 'Activate' }}
                            </button>
                            <button
                                @click="removeWelcome(item.id!)"
                                class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-danger px-3 py-2 text-center font-medium text-white"
                            >
                                Remove
                            </button>
                        </div>
                    </div>

                    <!-- ✅ SIMPLIFIED: Essential Fields Only -->
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- Left Column: Content -->
                        <div class="space-y-4">
                            <!-- Title -->
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white">Title *</label>
                                <input
                                    v-model="item.title"
                                    type="text"
                                    :class="{
                                        'border-danger': !item.title,
                                        'border-stroke': item.title,
                                    }"
                                    class="w-full rounded border-[1.5px] bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                    placeholder="Welcome to MBSTU"
                                />
                                <p v-if="!item.title" class="mt-1 text-sm text-danger">Title is required</p>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white">Description</label>
                                <textarea
                                    v-model="item.description"
                                    rows="3"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                    placeholder="Brief description of the university..."
                                ></textarea>
                            </div>

                            <!-- Button Configuration -->
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white">Button Text *</label>
                                <input
                                    v-model="item.buttonText"
                                    type="text"
                                    :class="{
                                        'border-danger': !item.buttonText,
                                        'border-stroke': item.buttonText,
                                    }"
                                    class="w-full rounded border-[1.5px] bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                    placeholder="Watch Campus Video"
                                />
                                <p v-if="!item.buttonText" class="mt-1 text-sm text-danger">Button text is required</p>
                            </div>
                        </div>

                        <!-- Right Column: Media -->
                        <div class="space-y-4">
                            <!-- Background Image -->
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white">Background Image URL</label>
                                <input
                                    v-model="item.backgroundImage"
                                    type="text"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                    placeholder="https://example.com/image.jpg"
                                />
                                <p class="mt-1 text-xs text-gray-500">Direct image URL for background</p>
                            </div>

                            <!-- Video Configuration -->
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white">YouTube Video ID/URL</label>
                                <input
                                    :value="item.videoId"
                                    @input="updateVideoId(item.id!, ($event.target as HTMLInputElement).value)"
                                    type="text"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                    placeholder="PZ9MHpFet34 or https://youtube.com/watch?v=..."
                                />
                                <p class="mt-1 text-xs text-gray-500">YouTube video ID or full URL. Leave empty for non-video button.</p>
                            </div>

                            <!-- Active Toggle -->
                            <div class="flex items-center gap-4 pt-4">
                                <label class="flex cursor-pointer items-center">
                                    <input v-model="item.isActive" type="checkbox" class="sr-only" />
                                    <div class="relative">
                                        <div class="block h-6 w-10 rounded-full bg-gray-600"></div>
                                        <div
                                            :class="item.isActive ? 'translate-x-4 bg-primary' : 'translate-x-0 bg-gray-400'"
                                            class="absolute top-1 left-1 h-4 w-4 rounded-full transition"
                                        ></div>
                                    </div>
                                    <div class="ml-3 text-sm font-medium text-black dark:text-white">Active</div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Actions -->
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <span v-if="!isValidConfiguration" class="text-danger">⚠️ Please fix validation errors before saving</span>
                    <span v-else-if="hasUnsavedChanges" class="text-warning">📝 You have unsaved changes</span>
                    <span v-else class="text-success">✅ Configuration is valid and saved</span>
                </div>

                <div class="flex gap-2">
                    <button
                        @click="validateAndSave"
                        :disabled="!isValidConfiguration || isSaving || !siteId"
                        :class="{
                            'hover:bg-opacity-90 bg-primary': isValidConfiguration && !isSaving && siteId,
                            'cursor-not-allowed bg-gray-400': !isValidConfiguration || isSaving || !siteId,
                        }"
                        class="inline-flex items-center justify-center rounded-md px-6 py-3 text-center font-medium text-white transition-colors"
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
                        {{ isSaving ? 'Saving...' : 'Save Welcome Configuration' }}
                    </button>
                </div>
            </div>

            <!-- ✅ SIMPLIFIED: JSON Preview -->
            <details class="mt-6">
                <summary class="cursor-pointer text-sm font-medium text-gray-600 hover:text-primary dark:text-gray-400">
                    View JSON Configuration
                </summary>
                <pre class="mt-2 max-h-60 overflow-auto rounded bg-gray-100 p-4 text-xs dark:bg-gray-800">{{
                    JSON.stringify(welcomeData, null, 2)
                }}</pre>
            </details>
        </div>
    </DefaultLayout>
</template>
