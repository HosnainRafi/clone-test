<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { BookOpen, Briefcase, Building, Calendar, Flag, Globe, GraduationCap, Heart, Home, Shield, Trophy, Users } from 'lucide-vue-next';
import { computed, ref } from 'vue';

// Interface for a single glance item
interface GlanceItem {
    id: number;
    label: string;
    value: string;
    iconName: string;
    iconColor: string;
    isActive: boolean;
    displayOrder: number;
}

// Props from the backend
const props = defineProps<{
    glanceItems: GlanceItem[];
    siteId: number | null;
}>();

const { siteId } = props;

// ✅ Define the list of available icons
const availableIcons = {
    Users,
    Trophy,
    Home,
    Calendar,
    BookOpen,
    Heart,
    Building,
    Shield,
    GraduationCap,
    Flag,
    Globe,
    Briefcase,
};

// Helper to safely get an icon component
const getIconComponent = (name: string) => {
    return (availableIcons as any)[name] || Users;
};

// State management
const glanceData = ref<GlanceItem[]>([]);
const isSaving = ref(false);
const message = ref('');
const messageType = ref<'success' | 'error' | ''>('');

// Initialize data
const initializeItems = () => {
    glanceData.value = (props.glanceItems || []).map((item, index) => ({
        ...item,
        id: item.id || Date.now() + index,
    }));
};
initializeItems();

// Computed Properties
const hasUnsavedChanges = computed(() => {
    return JSON.stringify(glanceData.value) !== JSON.stringify(props.glanceItems);
});

const isValidConfiguration = computed(() => {
    return glanceData.value.every((item) => item.label && item.value && item.iconName);
});

const activeItemsCount = computed(() => {
    return glanceData.value.filter((item) => item.isActive).length;
});

const sortedItems = computed(() => {
    return [...glanceData.value].sort((a, b) => a.displayOrder - b.displayOrder);
});

// Methods
const addItem = () => {
    const newItem: GlanceItem = {
        id: Date.now(),
        label: 'New Stat',
        value: '0+',
        iconName: 'Users',
        iconColor: '#4A90E2',
        isActive: true,
        displayOrder: glanceData.value.length + 1,
    };
    glanceData.value.push(newItem);
};

const removeItem = (id: number) => {
    glanceData.value = glanceData.value.filter((item) => item.id !== id);
    reorderItems();
};

const moveUp = (id: number) => {
    const index = glanceData.value.findIndex((item) => item.id === id);
    if (index > 0) {
        [glanceData.value[index], glanceData.value[index - 1]] = [glanceData.value[index - 1], glanceData.value[index]];
        reorderItems();
    }
};

const moveDown = (id: number) => {
    const index = glanceData.value.findIndex((item) => item.id === id);
    if (index < glanceData.value.length - 1) {
        [glanceData.value[index], glanceData.value[index + 1]] = [glanceData.value[index + 1], glanceData.value[index]];
        reorderItems();
    }
};

const toggleActive = (id: number) => {
    const item = glanceData.value.find((i) => i.id === id);
    if (item) item.isActive = !item.isActive;
};

const reorderItems = () => {
    glanceData.value.forEach((item, index) => {
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
        const response = await fetch('/admin/campus-glance', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                Accept: 'application/json',
            },
            body: JSON.stringify({ siteId, glanceItems: glanceData.value }),
        });
        const result = await response.json();
        if (response.ok && result.success) {
            showMessage(result.message, 'success');
            setTimeout(() => router.reload({ only: ['glanceItems'] }), 1500);
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
                        <h4 class="text-xl font-semibold text-black dark:text-white">Manage Campus at a Glance</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Site ID: {{ siteId || 'N/A' }} | Status:
                            <span :class="isValidConfiguration ? 'text-success' : 'text-danger'">{{
                                isValidConfiguration ? 'Valid' : 'Invalid'
                            }}</span>
                            <span v-if="hasUnsavedChanges" class="ml-2 text-warning">• Unsaved Changes</span>
                        </p>
                        <p class="mt-1 text-xs text-gray-500">Total Stats: {{ glanceData.length }} | Active: {{ activeItemsCount }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button v-if="hasUnsavedChanges" @click="resetToOriginal" class="rounded-md bg-secondary px-4 py-2 text-white">Reset</button>
                        <button @click="addItem" class="rounded-md bg-primary px-4 py-2 text-white">Add Stat</button>
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
                <div v-if="glanceData.length === 0" class="py-8 text-center text-gray-500">No stats found. Click "Add Stat" to begin.</div>

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
                                <h5 class="text-lg font-medium text-black dark:text-white">{{ item.label }}</h5>
                                <div class="mt-1 flex items-center gap-2">
                                    <span :class="item.isActive ? 'text-green-600' : 'text-red-600'" class="text-xs font-semibold">{{
                                        item.isActive ? 'Active' : 'Inactive'
                                    }}</span>
                                    <span class="text-xs text-gray-500">•</span>
                                    <span class="text-xs font-bold" :style="{ color: item.iconColor }">{{ item.value }}</span>
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
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label class="mb-2 block font-medium text-black dark:text-white">Label *</label>
                            <input
                                v-model="item.label"
                                type="text"
                                placeholder="e.g., Active Students"
                                class="w-full rounded border-[1.5px] border-stroke p-3"
                                :class="!item.label && 'border-danger'"
                            />
                        </div>
                        <div>
                            <label class="mb-2 block font-medium text-black dark:text-white">Value *</label>
                            <input
                                v-model="item.value"
                                type="text"
                                placeholder="e.g., 5000+"
                                class="w-full rounded border-[1.5px] border-stroke p-3"
                                :class="!item.value && 'border-danger'"
                            />
                        </div>

                        <!-- ✅ FIXED: Icon Name Dropdown with Preview -->
                        <div>
                            <label class="mb-2 block font-medium text-black dark:text-white">Icon *</label>
                            <div class="relative">
                                <span class="absolute top-1/2 left-3 -translate-y-1/2">
                                    <component :is="getIconComponent(item.iconName)" class="h-5 w-5" :style="{ color: item.iconColor }" />
                                </span>
                                <select
                                    v-model="item.iconName"
                                    class="w-full appearance-none rounded border-[1.5px] border-stroke bg-transparent py-3 pr-8 pl-12 transition outline-none focus:border-primary active:border-primary"
                                    :class="!item.iconName && 'border-danger'"
                                >
                                    <option value="" disabled>Select an icon</option>
                                    <option v-for="iconName in Object.keys(availableIcons)" :key="iconName" :value="iconName">
                                        {{ iconName }}
                                    </option>
                                </select>
                                <span class="absolute top-1/2 right-3 -translate-y-1/2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g opacity="0.8"><path d="M12 15.4L6 9.4L7.4 8L12 12.6L16.6 8L18 9.4L12 15.4Z" fill="#637381"></path></g>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col">
                            <label class="mb-2 block font-medium text-black dark:text-white">Icon Color</label>
                            <input v-model="item.iconColor" type="color" class="h-12 w-full rounded border-[1.5px] border-stroke p-1" />
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
                    {{ isSaving ? 'Saving...' : 'Save Glance Configuration' }}
                </button>
            </div>

            <details class="mt-6">
                <summary class="cursor-pointer text-sm font-medium text-gray-600">View JSON</summary>
                <pre class="mt-2 max-h-60 overflow-auto rounded bg-gray-100 p-4 text-xs">{{ JSON.stringify(glanceData, null, 2) }}</pre>
            </details>
        </div>
    </DefaultLayout>
</template>
