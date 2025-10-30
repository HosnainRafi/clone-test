<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// Define interfaces
interface SubItem {
    title: string;
    description: string;
    href: string;
}

interface MenuItem {
    id?: number;
    title: string;
    col: number;
    subItems: SubItem[];
}

interface PageProps {
    siteData: any;
    menuItems: MenuItem[];
    siteId: number | null;
}

// Receive props from Laravel
const props = defineProps<PageProps>();

// Extract siteId for easier access
const { siteId } = props;

// State management
const menuItems = ref<MenuItem[]>([]);
const isLoading = ref(false);
const isSaving = ref(false);
const message = ref('');
const messageType = ref<'success' | 'error' | ''>('');

// Initialize menu items from props
const initializeMenuItems = () => {
    if (props.menuItems && props.menuItems.length > 0) {
        // Add IDs to existing items if they don't have them
        menuItems.value = props.menuItems.map((item, index) => ({
            ...item,
            id: item.id || Date.now() + index,
        }));
    } else {
        // Default menu items if none exist
        menuItems.value = [
            {
                id: 1,
                title: 'About',
                col: 2,
                subItems: [
                    {
                        title: 'About Mawlana Bhashani',
                        description: "Learn about the visionary leader and founder's philosophy",
                        href: 'https://mbstu.ac.bd/about-mawlana-bhashani/',
                    },
                    {
                        title: 'Overview',
                        description: 'Comprehensive overview of the university',
                        href: 'https://mbstu.ac.bd/overview/',
                    },
                ],
            },
        ];
    }
};

// Initialize on component mount
initializeMenuItems();

// Computed properties
const hasUnsavedChanges = computed(() => {
    return JSON.stringify(menuItems.value) !== JSON.stringify(props.menuItems);
});

const isValidConfiguration = computed(() => {
    try {
        // Check if all menu items have required fields
        for (const item of menuItems.value) {
            if (!item.title || !item.col || !Array.isArray(item.subItems)) {
                return false;
            }

            // Check sub items
            for (const subItem of item.subItems) {
                if (!subItem.title || !subItem.description || !subItem.href) {
                    return false;
                }
            }
        }

        // Try to serialize to JSON
        JSON.stringify(menuItems.value);
        return true;
    } catch (error) {
        return false;
    }
});

// Methods
const addMenuItem = () => {
    const newItem: MenuItem = {
        id: Date.now(),
        title: 'New Menu Item',
        col: 1,
        subItems: [],
    };
    menuItems.value.push(newItem);
};

const removeMenuItem = (id: number) => {
    const index = menuItems.value.findIndex((item) => item.id === id);
    if (index > -1) {
        menuItems.value.splice(index, 1);
    }
};

const addSubItem = (menuId: number) => {
    const menuItem = menuItems.value.find((item) => item.id === menuId);
    if (menuItem) {
        menuItem.subItems.push({
            title: 'New Sub Item',
            description: 'Description for new sub item',
            href: '#',
        });
    }
};

const removeSubItem = (menuId: number, subIndex: number) => {
    const menuItem = menuItems.value.find((item) => item.id === menuId);
    if (menuItem) {
        menuItem.subItems.splice(subIndex, 1);
    }
};

const validateAndSave = async () => {
    console.log('validateAndSave called', {
        siteId: props.siteId,
        isValid: isValidConfiguration.value,
        menuItemsCount: menuItems.value.length,
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
        const dataToSave = menuItems.value.map((item) => ({
            title: item.title,
            col: item.col,
            subItems: item.subItems,
        }));

        console.log('Sending save request', {
            url: '/menu/save',
            data: { menuItems: dataToSave, siteId: props.siteId },
        });

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        console.log('CSRF Token:', csrfToken);

        const response = await fetch('/admin/menu', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                Accept: 'application/json',
            },
            body: JSON.stringify({
                menuItems: dataToSave,
                siteId: props.siteId,
            }),
        });

        console.log('Response status:', response.status);
        console.log('Response headers:', Object.fromEntries(response.headers.entries()));

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

        console.log('Parsed result:', result);

        if (response.ok && result.success) {
            showMessage(result.message, 'success');
            // Optionally reload the page to get fresh data
            setTimeout(() => {
                router.reload();
            }, 1500);
        } else {
            showMessage(result.message || 'Save failed with status: ' + response.status, 'error');
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
    initializeMenuItems();
    showMessage('Configuration reset to original state', 'success');
};

const forceSave = async () => {
    // Force save without validation for testing
    const effectiveSiteId = siteId || 1; // Use fallback site ID

    isSaving.value = true;

    try {
        const dataToSave = menuItems.value.map((item) => ({
            title: item.title || 'Untitled',
            col: item.col || 1,
            subItems: item.subItems || [],
        }));

        console.log('Force saving with data:', { dataToSave, siteId: effectiveSiteId });

        const response = await fetch('/admin/menu', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify({
                menuItems: dataToSave,
                siteId: effectiveSiteId,
            }),
        });

        const result = await response.json();
        console.log('Save response:', result);

        if (result.success) {
            showMessage('Force save successful: ' + result.message, 'success');
        } else {
            showMessage('Force save failed: ' + result.message, 'error');
        }
    } catch (error) {
        console.error('Force save error:', error);
        showMessage('Force save failed: ' + (error instanceof Error ? error.message : 'Unknown error'), 'error');
    } finally {
        isSaving.value = false;
    }
};

const testDatabase = async () => {
    try {
        console.log('Testing database connection...');
        const response = await fetch('/test-db');
        const result = await response.json();

        console.log('Database test result:', result);

        if (result.success) {
            showMessage(`Database OK: ${result.sites_count} sites found`, 'success');
        } else {
            showMessage(`Database Error: ${result.message}`, 'error');
        }
    } catch (error) {
        console.error('Database test error:', error);
        showMessage('Database test failed: ' + (error instanceof Error ? error.message : 'Unknown error'), 'error');
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
                        <h4 class="text-xl font-semibold text-black dark:text-white">Menu Management</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Site ID: {{ siteId || 'Not Available' }} | Status:
                            <span :class="isValidConfiguration ? 'text-success' : 'text-danger'">
                                {{ isValidConfiguration ? 'Valid' : 'Invalid Configuration' }}
                            </span>
                            <span v-if="hasUnsavedChanges" class="ml-2 text-warning">• Unsaved Changes</span>
                        </p>
                        <!-- Debug Info -->
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                            Debug: Valid={{ isValidConfiguration }} | Saving={{ isSaving }} | SiteID={{ !!siteId }} | Button Enabled={{
                                isValidConfiguration && !isSaving && !!siteId
                            }}
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
                            @click="addMenuItem"
                            class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-center font-medium text-white"
                        >
                            Add Menu Item
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

            <!-- Menu Items -->
            <div class="space-y-6">
                <div v-if="menuItems.length === 0" class="py-8 text-center text-gray-500 dark:text-gray-400">
                    No menu items configured. Click "Add Menu Item" to get started.
                </div>

                <div
                    v-for="(menuItem, menuIndex) in menuItems"
                    :key="menuItem.id"
                    class="rounded-sm border border-stroke bg-gray-50 p-4 dark:bg-meta-4"
                >
                    <div class="mb-4 flex items-start justify-between">
                        <div class="flex-1">
                            <div class="mb-3">
                                <label class="mb-2.5 block font-medium text-black dark:text-white"> Menu Title * </label>
                                <input
                                    v-model="menuItem.title"
                                    type="text"
                                    :class="{
                                        'border-danger': !menuItem.title,
                                        'border-stroke': menuItem.title,
                                    }"
                                    class="w-full rounded border-[1.5px] bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                    placeholder="Enter menu title"
                                />
                                <p v-if="!menuItem.title" class="mt-1 text-sm text-danger">Title is required</p>
                            </div>
                            <div class="mb-3">
                                <label class="mb-2.5 block font-medium text-black dark:text-white"> Columns * </label>
                                <select
                                    v-model.number="menuItem.col"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                >
                                    <option :value="1">1 Column</option>
                                    <option :value="2">2 Columns</option>
                                    <option :value="3">3 Columns</option>
                                    <option :value="4">4 Columns</option>
                                </select>
                            </div>
                        </div>
                        <button
                            @click="removeMenuItem(menuItem.id!)"
                            class="hover:bg-opacity-90 ml-4 inline-flex items-center justify-center rounded-md bg-danger px-3 py-2 text-center font-medium text-white"
                        >
                            Remove
                        </button>
                    </div>

                    <div class="mb-4">
                        <div class="mb-3 flex items-center justify-between">
                            <h5 class="font-medium text-black dark:text-white">Sub Items ({{ menuItem.subItems.length }})</h5>
                            <button
                                @click="addSubItem(menuItem.id!)"
                                class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-success px-3 py-1 text-sm font-medium text-white"
                            >
                                Add Sub Item
                            </button>
                        </div>

                        <div v-if="menuItem.subItems.length === 0" class="py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No sub items. Click "Add Sub Item" to add navigation links.
                        </div>

                        <div class="space-y-3">
                            <div
                                v-for="(subItem, subIndex) in menuItem.subItems"
                                :key="subIndex"
                                class="grid grid-cols-1 gap-3 rounded border border-stroke bg-white p-3 md:grid-cols-3 dark:bg-boxdark"
                            >
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white"> Title * </label>
                                    <input
                                        v-model="subItem.title"
                                        type="text"
                                        :class="{
                                            'border-danger': !subItem.title,
                                            'border-stroke': subItem.title,
                                        }"
                                        class="w-full rounded border-[1.5px] bg-transparent px-3 py-2 text-sm transition outline-none focus:border-primary active:border-primary"
                                        placeholder="Sub item title"
                                    />
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white"> Description * </label>
                                    <input
                                        v-model="subItem.description"
                                        type="text"
                                        :class="{
                                            'border-danger': !subItem.description,
                                            'border-stroke': subItem.description,
                                        }"
                                        class="w-full rounded border-[1.5px] bg-transparent px-3 py-2 text-sm transition outline-none focus:border-primary active:border-primary"
                                        placeholder="Brief description"
                                    />
                                </div>
                                <div class="flex items-end gap-2">
                                    <div class="flex-1">
                                        <label class="mb-1 block text-sm font-medium text-black dark:text-white"> URL * </label>
                                        <input
                                            v-model="subItem.href"
                                            type="text"
                                            :class="{
                                                'border-danger': !subItem.href,
                                                'border-stroke': subItem.href,
                                            }"
                                            class="w-full rounded border-[1.5px] bg-transparent px-3 py-2 text-sm transition outline-none focus:border-primary active:border-primary"
                                            placeholder="https://example.com"
                                        />
                                    </div>
                                    <button
                                        @click="removeSubItem(menuItem.id!, subIndex)"
                                        class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-danger px-2 py-2 text-sm font-medium text-white"
                                        title="Remove sub item"
                                    >
                                        ×
                                    </button>
                                </div>
                            </div>
                        </div>
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
                        {{ isSaving ? 'Saving...' : 'Save Menu Configuration' }}
                    </button>

                    <!-- Force Save Button for Testing -->
                    <button
                        @click="forceSave"
                        class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-warning px-4 py-3 text-center font-medium text-white"
                        title="Force save (for testing)"
                    >
                        Force Save
                    </button>

                    <!-- Test DB Button -->
                    <button
                        @click="testDatabase"
                        class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-secondary px-4 py-3 text-center font-medium text-white"
                        title="Test database connection"
                    >
                        Test DB
                    </button>
                </div>
            </div>

            <!-- JSON Preview (for debugging) -->
            <details class="mt-6">
                <summary class="cursor-pointer text-sm font-medium text-gray-600 hover:text-primary dark:text-gray-400">
                    View JSON Configuration
                </summary>
                <pre class="mt-2 max-h-60 overflow-auto rounded bg-gray-100 p-4 text-xs dark:bg-gray-800">{{
                    JSON.stringify(menuItems, null, 2)
                }}</pre>
            </details>
        </div>
    </DefaultLayout>
</template>
