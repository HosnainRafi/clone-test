<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

// --- INTERFACES & PROPS ---
interface AtAGlanceStatistic {
    id: number;
    icon: string; // Icon name for frontend
    number: number;
    suffix: string;
    label: string;
    description: string;
    color: 'blue' | 'green' | 'purple' | 'orange' | 'red' | 'indigo';
    bgGradient: string;
    isActive: boolean;
    order: number;
}

interface AtAGlanceData {
    sectionTitle: string;
    sectionSubtitle: string;
    statistics: AtAGlanceStatistic[];
    isVisible: boolean;
    animationDuration: number; // in milliseconds
    animationDelay: number; // delay between each stat animation
}

interface PageProps {
    atAGlanceData: AtAGlanceData | null; // From Controller
    siteId: number | null;
}

const props = defineProps<PageProps>();

// --- STATE MANAGEMENT ---
// Provide a default structure if atAGlanceData is initially null
const defaultAtAGlanceData: AtAGlanceData = {
    sectionTitle: 'At a Glance',
    sectionSubtitle: 'Key statistics about the Department of Computer Science and Engineering at MBSTU',
    isVisible: true,
    animationDuration: 2500,
    animationDelay: 200,
    statistics: [
        {
            id: 1,
            icon: 'GraduationCap',
            number: 19,
            suffix: '+',
            label: 'Current Teachers',
            description: 'Experienced faculty members',
            color: 'blue',
            bgGradient: 'from-blue-500 to-blue-600',
            isActive: true,
            order: 1
        },
        {
            id: 2,
            icon: 'Users',
            number: 250,
            suffix: '+',
            label: 'Current Students',
            description: 'Active enrolled students',
            color: 'green',
            bgGradient: 'from-green-500 to-green-600',
            isActive: true,
            order: 2
        },
        {
            id: 3,
            icon: 'Eye',
            number: 66483,
            suffix: '+',
            label: 'Total Visitors',
            description: 'Unique website visitors',
            color: 'purple',
            bgGradient: 'from-purple-500 to-purple-600',
            isActive: true,
            order: 3
        },
        {
            id: 4,
            icon: 'MousePointer',
            number: 387405,
            suffix: '+',
            label: 'Total Visits',
            description: 'Total website visits',
            color: 'orange',
            bgGradient: 'from-orange-500 to-orange-600',
            isActive: true,
            order: 4
        }
    ]
};

const localAtAGlanceData = ref<AtAGlanceData>(JSON.parse(JSON.stringify(props.atAGlanceData || defaultAtAGlanceData)));

const isSaving = ref(false);
const message = ref('');
const messageType = ref<'success' | 'error' | ''>();

// --- COMPUTED & WATCHERS ---
const originalDataString = computed(() => JSON.stringify(props.atAGlanceData || defaultAtAGlanceData));
const currentDataString = computed(() => JSON.stringify(localAtAGlanceData.value));
const hasUnsavedChanges = computed(() => currentDataString.value !== originalDataString.value);

// Watch for prop changes to reset local state if needed (e.g., after save)
watch(
    () => props.atAGlanceData,
    (newData) => {
        localAtAGlanceData.value = JSON.parse(JSON.stringify(newData || defaultAtAGlanceData));
    },
    { deep: true },
);

// --- METHODS ---
const showMessage = (msg: string, type: 'success' | 'error') => {
    message.value = msg;
    messageType.value = type;
    setTimeout(() => {
        message.value = '';
    }, 5000);
};

// Methods to manage statistics
const addStatistic = () => {
    const newId = Math.max(0, ...localAtAGlanceData.value.statistics.map(s => s.id)) + 1;
    const newOrder = Math.max(0, ...localAtAGlanceData.value.statistics.map(s => s.order)) + 1;
    localAtAGlanceData.value.statistics.push({
        id: newId,
        icon: 'Star',
        number: 0,
        suffix: '',
        label: 'New Statistic',
        description: 'Description for new statistic',
        color: 'blue',
        bgGradient: 'from-blue-500 to-blue-600',
        isActive: true,
        order: newOrder
    });
};

const removeStatistic = (index: number) => {
    if (confirm('Remove this statistic?')) {
        localAtAGlanceData.value.statistics.splice(index, 1);
    }
};

const moveStatisticUp = (index: number) => {
    if (index > 0) {
        const temp = localAtAGlanceData.value.statistics[index];
        localAtAGlanceData.value.statistics[index] = localAtAGlanceData.value.statistics[index - 1];
        localAtAGlanceData.value.statistics[index - 1] = temp;
        
        // Update order values
        localAtAGlanceData.value.statistics[index].order = index + 1;
        localAtAGlanceData.value.statistics[index - 1].order = index;
    }
};

const moveStatisticDown = (index: number) => {
    if (index < localAtAGlanceData.value.statistics.length - 1) {
        const temp = localAtAGlanceData.value.statistics[index];
        localAtAGlanceData.value.statistics[index] = localAtAGlanceData.value.statistics[index + 1];
        localAtAGlanceData.value.statistics[index + 1] = temp;
        
        // Update order values
        localAtAGlanceData.value.statistics[index].order = index + 1;
        localAtAGlanceData.value.statistics[index + 1].order = index + 2;
    }
};

const resetToOriginal = () => {
    if (confirm('Discard unsaved changes?')) {
        localAtAGlanceData.value = JSON.parse(JSON.stringify(props.atAGlanceData || defaultAtAGlanceData));
        showMessage('Changes discarded.', 'success');
    }
};

const validateAndSave = async () => {
    if (!props.siteId) return showMessage('Site ID is missing.', 'error');
    if (!hasUnsavedChanges.value) return showMessage('No changes to save.', 'success');

    isSaving.value = true;
    try {
        const response = await fetch('/admin/ataglance-section', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                Accept: 'application/json',
            },
            body: JSON.stringify({ atAGlanceData: localAtAGlanceData.value, siteId: props.siteId }),
        });
        const result = await response.json();
        if (response.ok && result.success) {
            showMessage(result.message, 'success');
            // Optionally reload only the at-a-glance data prop if Inertia setup allows
            router.reload({ only: ['atAGlanceData'] });
        } else {
            showMessage(result.message || 'Server error during save.', 'error');
        }
    } catch (error: any) {
        showMessage(`Network or saving error: ${error.message || 'Unknown error'}`, 'error');
    } finally {
        isSaving.value = false;
    }
};

// Available icons for selection
const availableIcons = [
    'GraduationCap', 'Users', 'Eye', 'MousePointer', 'Star', 'Award', 'Book', 
    'Briefcase', 'Calendar', 'Clock', 'Globe', 'Heart', 'Home', 'MapPin',
    'Phone', 'Search', 'Settings', 'Target', 'TrendingUp', 'Trophy'
];

// Available colors for selection
const availableColors: Array<{value: AtAGlanceStatistic['color'], label: string, gradient: string}> = [
    { value: 'blue', label: 'Blue', gradient: 'from-blue-500 to-blue-600' },
    { value: 'green', label: 'Green', gradient: 'from-green-500 to-green-600' },
    { value: 'purple', label: 'Purple', gradient: 'from-purple-500 to-purple-600' },
    { value: 'orange', label: 'Orange', gradient: 'from-orange-500 to-orange-600' },
    { value: 'red', label: 'Red', gradient: 'from-red-500 to-red-600' },
    { value: 'indigo', label: 'Indigo', gradient: 'from-indigo-500 to-indigo-600' }
];

// Update gradient when color changes
const updateStatisticColor = (statistic: AtAGlanceStatistic, color: AtAGlanceStatistic['color']) => {
    statistic.color = color;
    const colorConfig = availableColors.find(c => c.value === color);
    if (colorConfig) {
        statistic.bgGradient = colorConfig.gradient;
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
                        <h4 class="text-xl font-semibold text-black dark:text-white">At a Glance Management</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Site ID: {{ props.siteId || 'N/A' }}
                            <span v-if="hasUnsavedChanges" class="ml-2 text-warning">• Unsaved Changes</span>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button v-if="hasUnsavedChanges" @click="resetToOriginal" class="inline-flex items-center justify-center rounded-md bg-gray-600 px-4 py-2 text-center text-sm font-medium text-white transition-all duration-200 hover:bg-gray-700 hover:shadow-md hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:scale-95">Discard Changes</button>
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

            <form @submit.prevent="validateAndSave">
                <!-- Section Settings -->
                <div class="space-y-6 rounded-sm border border-stroke bg-gray-50 p-6 dark:border-strokedark dark:bg-meta-4">
                    <h3 class="text-lg font-semibold text-black dark:text-white">Section Settings</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="mb-2.5 block font-medium text-black dark:text-white">Section Title</label>
                            <input v-model="localAtAGlanceData.sectionTitle" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" required />
                        </div>
                        <div>
                            <label class="mb-2.5 block font-medium text-black dark:text-white">Animation Duration (ms)</label>
                            <input v-model.number="localAtAGlanceData.animationDuration" type="number" min="500" max="5000" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />
                        </div>
                    </div>

                    <div>
                        <label class="mb-2.5 block font-medium text-black dark:text-white">Section Subtitle</label>
                        <textarea v-model="localAtAGlanceData.sectionSubtitle" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary min-h-[80px]" rows="3"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="mb-2.5 block font-medium text-black dark:text-white">Animation Delay Between Stats (ms)</label>
                            <input v-model.number="localAtAGlanceData.animationDelay" type="number" min="0" max="1000" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />
                        </div>
                        <div class="flex items-center">
                            <label class="flex items-center">
                                <input v-model="localAtAGlanceData.isVisible" type="checkbox" class="mr-2" />
                                <span class="text-sm font-medium text-black dark:text-white">Section Visible</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Statistics Management -->
                <div class="mt-8 space-y-6 rounded-sm border border-stroke bg-gray-50 p-6 dark:border-strokedark dark:bg-meta-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-black dark:text-white">Statistics</h3>
                        <button type="button" @click="addStatistic" class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-center text-sm font-medium text-white transition-all duration-200 hover:bg-blue-700 hover:shadow-md hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:scale-95">Add Statistic</button>
                    </div>

                    <div v-if="localAtAGlanceData.statistics.length === 0" class="text-center py-8 text-gray-500">
                        No statistics added yet. Click "Add Statistic" to get started.
                    </div>

                    <div v-else class="space-y-4">
                        <div
                            v-for="(statistic, index) in localAtAGlanceData.statistics"
                            :key="statistic.id"
                            class="border border-stroke rounded-lg p-4 bg-white dark:border-strokedark dark:bg-boxdark"
                        >
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-medium text-black dark:text-white">Statistic #{{ index + 1 }}</h4>
                                <div class="flex gap-2">
                                    <button type="button" @click="moveStatisticUp(index)" :disabled="index === 0" class="inline-flex items-center justify-center rounded-md px-2 py-1 text-center text-xs font-medium text-white transition-all duration-200 bg-gray-600 hover:bg-gray-700 hover:shadow-sm hover:scale-110 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-gray-600 disabled:hover:scale-100 focus:outline-none focus:ring-1 focus:ring-gray-500 active:scale-95">↑</button>
                                    <button type="button" @click="moveStatisticDown(index)" :disabled="index === localAtAGlanceData.statistics.length - 1" class="inline-flex items-center justify-center rounded-md px-2 py-1 text-center text-xs font-medium text-white transition-all duration-200 bg-gray-600 hover:bg-gray-700 hover:shadow-sm hover:scale-110 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-gray-600 disabled:hover:scale-100 focus:outline-none focus:ring-1 focus:ring-gray-500 active:scale-95">↓</button>
                                    <button type="button" @click="removeStatistic(index)" class="inline-flex items-center justify-center rounded-md px-2 py-1 text-center text-xs font-medium text-white transition-all duration-200 bg-red-600 hover:bg-red-700 hover:shadow-sm hover:scale-110 focus:outline-none focus:ring-1 focus:ring-red-500 active:scale-95">Remove</button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Icon</label>
                                    <div class="relative">
                                        <select v-model="statistic.icon" class="w-full rounded border-[1.5px] border-stroke bg-white px-5 py-3 pr-10 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary appearance-none">
                                            <option v-for="icon in availableIcons" :key="icon" :value="icon">{{ icon }}</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Number</label>
                                    <input v-model.number="statistic.number" type="number" min="0" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" required />
                                </div>
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Suffix</label>
                                    <input v-model="statistic.suffix" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" placeholder="e.g., +, %, K" />
                                </div>
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Label</label>
                                    <input v-model="statistic.label" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" required />
                                </div>
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Color</label>
                                    <div class="relative">
                                        <select v-model="statistic.color" @change="updateStatisticColor(statistic, statistic.color)" class="w-full rounded border-[1.5px] border-stroke bg-white px-5 py-3 pr-10 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary appearance-none">
                                            <option v-for="color in availableColors" :key="color.value" :value="color.value">{{ color.label }}</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <label class="flex items-center">
                                        <input v-model="statistic.isActive" type="checkbox" class="mr-2" />
                                        <span class="text-sm font-medium text-black dark:text-white">Active</span>
                                    </label>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="mb-2.5 block font-medium text-black dark:text-white">Description</label>
                                <textarea v-model="statistic.description" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary min-h-[60px]" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="mt-8 flex justify-end">
                    <button
                        type="submit"
                        :disabled="isSaving || !hasUnsavedChanges"
                        class="inline-flex items-center justify-center rounded-md bg-blue-600 px-6 py-3 text-center font-medium text-white transition-all duration-200 hover:bg-blue-700 hover:shadow-lg hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-600 disabled:hover:scale-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:scale-95"
                    >
                        {{ isSaving ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </div>
    </DefaultLayout>
</template>

<style scoped>
/* Removed unused CSS classes since we're using inline Tailwind classes for better maintainability */
</style>