<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// Define interfaces
interface HeadlineItem {
    id?: number;
    type: 'announcement' | 'news' | 'event' | 'research' | 'achievement' | 'notice';
    text: string;
    link: string;
    priority: 'high' | 'medium' | 'low';
    isActive: boolean;
    icon?: string;
}

interface PageProps {
    siteData: any;
    headlines: HeadlineItem[];
    siteId: number | null;
}

// Receive props from Laravel
const props = defineProps<PageProps>();

// Extract siteId for easier access
const { siteId } = props;

// State management
const headlinesData = ref<HeadlineItem[]>([]);
const isSaving = ref(false);
const message = ref('');
const messageType = ref<'success' | 'error' | ''>('');

// Available types and their default icons
const headlineTypes = [
    { value: 'announcement', label: 'Announcement', icon: '📢' },
    { value: 'news', label: 'News', icon: '📰' },
    { value: 'event', label: 'Event', icon: '📅' },
    { value: 'research', label: 'Research', icon: '🔬' },
    { value: 'achievement', label: 'Achievement', icon: '🏆' },
    { value: 'notice', label: 'Notice', icon: '📋' },
];

const priorityLevels = [
    { value: 'high', label: 'High Priority', color: 'text-red-600' },
    { value: 'medium', label: 'Medium Priority', color: 'text-yellow-600' },
    { value: 'low', label: 'Low Priority', color: 'text-green-600' },
];

// Initialize headlines from props
const initializeHeadlines = () => {
    if (props.headlines && props.headlines.length > 0) {
        console.log('Rafi', props.headlines);
        // Add IDs to existing items if they don't have them
        headlinesData.value = props.headlines.map((headline, index) => ({
            ...headline,
            id: headline.id || Date.now() + index,
        }));
    } else {
        // Default headlines if none exist
        headlinesData.value = [
            {
                id: 1,
                type: 'announcement',
                text: '🎓 Admission Open for Fall 2025 - Apply now for undergraduate and graduate programs',
                link: '/admission',
                priority: 'high',
                isActive: true,
                icon: '🎓',
            },
            {
                id: 2,
                type: 'news',
                text: '🏆 CSE Department wins National Programming Contest 2025',
                link: '/news/programming-contest-2025',
                priority: 'medium',
                isActive: true,
                icon: '🏆',
            },
            {
                id: 3,
                type: 'event',
                text: '📅 International Conference on AI & Machine Learning - March 15-17, 2025',
                link: '/events/ai-conference-2025',
                priority: 'high',
                isActive: true,
                icon: '📅',
            },
        ];
    }
};

// Initialize on component mount
initializeHeadlines();

// Computed properties
const hasUnsavedChanges = computed(() => {
    return JSON.stringify(headlinesData.value) !== JSON.stringify(props.headlines);
});

const isValidConfiguration = computed(() => {
    try {
        // Check if all headlines have required fields
        for (const headline of headlinesData.value) {
            if (!headline.text || !headline.type || !headline.priority) {
                return false;
            }
        }

        // Try to serialize to JSON
        JSON.stringify(headlinesData.value);
        return true;
    } catch {
        return false;
    }
});

const activeHeadlinesCount = computed(() => {
    return headlinesData.value.filter((h) => h.isActive).length;
});

// Methods
const addHeadline = () => {
    const newHeadline: HeadlineItem = {
        id: Date.now(),
        type: 'announcement',
        text: 'New headline text',
        link: '#',
        priority: 'medium',
        isActive: true,
        icon: '📢',
    };
    headlinesData.value.push(newHeadline);
};

const removeHeadline = (id: number) => {
    const index = headlinesData.value.findIndex((headline) => headline.id === id);
    if (index > -1) {
        headlinesData.value.splice(index, 1);
    }
};

const toggleHeadlineActive = (id: number) => {
    const headline = headlinesData.value.find((h) => h.id === id);
    if (headline) {
        headline.isActive = !headline.isActive;
    }
};

const getTypeIcon = (type: string) => {
    const typeConfig = headlineTypes.find((t) => t.value === type);
    return typeConfig?.icon || '📢';
};

const validateAndSave = async () => {
    console.log('validateAndSave called', {
        siteId: props.siteId,
        isValid: isValidConfiguration.value,
        headlinesCount: headlinesData.value.length,
    });

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
        // Prepare data for saving (remove IDs as they're only for frontend)
        const dataToSave = headlinesData.value.map((headline) => ({
            type: headline.type,
            text: headline.text,
            link: headline.link,
            priority: headline.priority,
            isActive: headline.isActive,
            icon: headline.icon,
        }));

        console.log('Sending save request', {
            url: '/headline-marquee/save',
            data: { headlines: dataToSave, siteId: props.siteId },
        });

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const response = await fetch('/headline-marquee/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                Accept: 'application/json',
            },
            body: JSON.stringify({
                headlines: dataToSave,
                siteId: props.siteId,
            }),
        });

        console.log('Response status:', response.status);

        const responseText = await response.text();
        console.log('Raw response:', responseText);

        let result;
        try {
            result = JSON.parse(responseText);
        } catch (parseError) {
            console.error('Failed to parse response as JSON:', parseError);
            showMessage('Server returned invalid response: ' + responseText.substring(0, 100), 'error');
            return;
        }

        if (response.ok && result.success) {
            showMessage(result.message, 'success');
            setTimeout(() => {
                router.reload();
            }, 1500);
        } else {
            showMessage(result.message || 'Save failed with status: ' + response.status, 'error');
        }
    } catch (err) {
        console.error('Save error:', err);
        showMessage('Network error: ' + (err instanceof Error ? err.message : 'Unknown error'), 'error');
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
    initializeHeadlines();
    showMessage('Configuration reset to original state', 'success');
};

// Update icon when type changes
const updateHeadlineType = (id: number, newType: string) => {
    const headline = headlinesData.value.find((h) => h.id === id);
    if (headline) {
        headline.type = newType as any;
        headline.icon = getTypeIcon(newType);
    }
};
</script>

<template>
    <DefaultLayout>
        <div
            class="shadow-default rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 sm:px-7.5 xl:pb-1 dark:border-strokedark dark:bg-boxdark"
        >
            <!-- Header with Status -->
            <div class="mb-6">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h4 class="text-xl font-semibold text-black dark:text-white">Headline Marquee Management</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Site ID: {{ siteId || 'Not Available' }} | Status:
                            <span :class="isValidConfiguration ? 'text-success' : 'text-danger'">
                                {{ isValidConfiguration ? 'Valid' : 'Invalid Configuration' }}
                            </span>
                            <span v-if="hasUnsavedChanges" class="ml-2 text-warning">• Unsaved Changes</span>
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                            Total Headlines: {{ headlinesData.length }} | Active: {{ activeHeadlinesCount }} | Button Enabled:
                            {{ isValidConfiguration && !isSaving && !!siteId }}
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
                            @click="addHeadline"
                            class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-center font-medium text-white"
                        >
                            Add Headline
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

            <!-- Headlines List -->
            <div class="space-y-4">
                <div v-if="headlinesData.length === 0" class="py-8 text-center text-gray-500 dark:text-gray-400">
                    No headlines configured. Click "Add Headline" to get started.
                </div>

                <div
                    v-for="(headline, index) in headlinesData"
                    :key="headline.id"
                    class="rounded-sm border border-stroke bg-gray-50 p-4 dark:bg-meta-4"
                    :class="{
                        'opacity-50': !headline.isActive,
                        'border-l-4 border-l-primary': headline.isActive,
                    }"
                >
                    <div class="mb-4 flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">{{ headline.icon || getTypeIcon(headline.type) }}</span>
                            <div>
                                <h5 class="text-lg font-medium text-black dark:text-white">Headline {{ index + 1 }}</h5>
                                <div class="mt-1 flex items-center gap-2">
                                    <span :class="priorityLevels.find((p) => p.value === headline.priority)?.color" class="text-xs font-semibold">
                                        {{ priorityLevels.find((p) => p.value === headline.priority)?.label }}
                                    </span>
                                    <span class="text-xs text-gray-500">•</span>
                                    <span class="text-xs text-gray-500 capitalize">{{ headline.type }}</span>
                                    <span class="text-xs text-gray-500">•</span>
                                    <span :class="headline.isActive ? 'text-green-600' : 'text-red-600'" class="text-xs font-semibold">
                                        {{ headline.isActive ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button
                                @click="toggleHeadlineActive(headline.id!)"
                                :class="headline.isActive ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600'"
                                class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md px-3 py-2 text-center font-medium text-white"
                            >
                                {{ headline.isActive ? 'Deactivate' : 'Activate' }}
                            </button>
                            <button
                                @click="removeHeadline(headline.id!)"
                                class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-danger px-3 py-2 text-center font-medium text-white"
                            >
                                Remove
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <!-- Type Selection -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-black dark:text-white"> Type * </label>
                            <select
                                :value="headline.type"
                                @change="updateHeadlineType(headline.id!, ($event.target as HTMLSelectElement).value)"
                                class="w-full rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm transition outline-none focus:border-primary active:border-primary"
                            >
                                <option v-for="type in headlineTypes" :key="type.value" :value="type.value">{{ type.icon }} {{ type.label }}</option>
                            </select>
                        </div>

                        <!-- Priority Selection -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-black dark:text-white"> Priority * </label>
                            <select
                                v-model="headline.priority"
                                class="w-full rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm transition outline-none focus:border-primary active:border-primary"
                            >
                                <option v-for="priority in priorityLevels" :key="priority.value" :value="priority.value">
                                    {{ priority.label }}
                                </option>
                            </select>
                        </div>

                        <!-- Icon -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-black dark:text-white"> Icon </label>
                            <input
                                v-model="headline.icon"
                                type="text"
                                class="w-full rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm transition outline-none focus:border-primary active:border-primary"
                                placeholder="📢"
                            />
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <label class="mt-6 flex cursor-pointer items-center">
                                <input v-model="headline.isActive" type="checkbox" class="sr-only" />
                                <div class="relative">
                                    <div class="block h-8 w-14 rounded-full bg-gray-600"></div>
                                    <div
                                        :class="headline.isActive ? 'translate-x-6 bg-primary' : 'translate-x-1 bg-gray-400'"
                                        class="absolute top-1 left-1 h-6 w-6 rounded-full transition"
                                    ></div>
                                </div>
                                <div class="ml-3 text-sm font-medium text-black dark:text-white">
                                    {{ headline.isActive ? 'Active' : 'Inactive' }}
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Headline Text -->
                    <div class="mt-4">
                        <label class="mb-2 block font-medium text-black dark:text-white"> Headline Text * </label>
                        <textarea
                            v-model="headline.text"
                            rows="2"
                            :class="{
                                'border-danger': !headline.text,
                                'border-stroke': headline.text,
                            }"
                            class="w-full rounded border-[1.5px] bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            placeholder="Enter headline text"
                        ></textarea>
                        <p v-if="!headline.text" class="mt-1 text-sm text-danger">Headline text is required</p>
                    </div>

                    <!-- Link -->
                    <div class="mt-4">
                        <label class="mb-2 block font-medium text-black dark:text-white"> Link URL </label>
                        <input
                            v-model="headline.link"
                            type="text"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            placeholder="/news/article-slug or https://example.com"
                        />
                    </div>
                </div>
            </div>

            <!-- Save Actions -->
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <span v-if="!isValidConfiguration" class="text-danger"> ⚠️ Please fix validation errors before saving </span>
                    <span v-else-if="hasUnsavedChanges" class="text-warning"> 📝 You have unsaved changes </span>
                    <span v-else class="text-success"> ✅ Configuration is valid and saved </span>
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
                        {{ isSaving ? 'Saving...' : 'Save Headline Configuration' }}
                    </button>
                </div>
            </div>

            <!-- JSON Preview (for debugging) -->
            <details class="mt-6">
                <summary class="cursor-pointer text-sm font-medium text-gray-600 hover:text-primary dark:text-gray-400">
                    View JSON Configuration
                </summary>
                <pre class="mt-2 max-h-60 overflow-auto rounded bg-gray-100 p-4 text-xs dark:bg-gray-800">{{
                    JSON.stringify(headlinesData, null, 2)
                }}</pre>
            </details>
        </div>
    </DefaultLayout>
</template>
