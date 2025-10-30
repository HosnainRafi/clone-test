<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'

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
      id: item.id || Date.now() + index
    }));
  } else {
    // Default menu items if none exist
    menuItems.value = [
      {
        id: 1,
        title: "About",
        col: 2,
        subItems: [
          {
            title: "About Mawlana Bhashani",
            description: "Learn about the visionary leader and founder's philosophy",
            href: "https://mbstu.ac.bd/about-mawlana-bhashani/"
          },
          {
            title: "Overview",
            description: "Comprehensive overview of the university",
            href: "https://mbstu.ac.bd/overview/"
          }
        ]
      }
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
    title: "New Menu Item",
    col: 1,
    subItems: []
  };
  menuItems.value.push(newItem);
};

const removeMenuItem = (id: number) => {
  const index = menuItems.value.findIndex(item => item.id === id);
  if (index > -1) {
    menuItems.value.splice(index, 1);
  }
};

const addSubItem = (menuId: number) => {
  const menuItem = menuItems.value.find(item => item.id === menuId);
  if (menuItem) {
    menuItem.subItems.push({
      title: "New Sub Item",
      description: "Description for new sub item",
      href: "#"
    });
  }
};

const removeSubItem = (menuId: number, subIndex: number) => {
  const menuItem = menuItems.value.find(item => item.id === menuId);
  if (menuItem) {
    menuItem.subItems.splice(subIndex, 1);
  }
};

const validateAndSave = async () => {
  console.log('validateAndSave called', {
    siteId: props.siteId,
    isValid: isValidConfiguration.value,
    menuItemsCount: menuItems.value.length
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
    const dataToSave = menuItems.value.map(item => ({
      title: item.title,
      col: item.col,
      subItems: item.subItems
    }));

    console.log('Sending save request', {
      url: '/menu/save',
      data: { menuItems: dataToSave, siteId: props.siteId }
    });

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    console.log('CSRF Token:', csrfToken);

    const response = await fetch('/menu/save', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken || '',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        menuItems: dataToSave,
        siteId: props.siteId
      })
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
    const dataToSave = menuItems.value.map(item => ({
      title: item.title || 'Untitled',
      col: item.col || 1,
      subItems: item.subItems || []
    }));

    console.log('Force saving with data:', { dataToSave, siteId: effectiveSiteId });

    const response = await fetch('/menu/save', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({
        menuItems: dataToSave,
        siteId: effectiveSiteId
      })
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
    <div class="rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
      <!-- Header with Status -->
      <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
          <div>
            <h4 class="text-xl font-semibold text-black dark:text-white">
              Menu Management
            </h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
              Site ID: {{ siteId || 'Not Available' }} | 
              Status: 
              <span :class="isValidConfiguration ? 'text-success' : 'text-danger'">
                {{ isValidConfiguration ? 'Valid' : 'Invalid Configuration' }}
              </span>
              <span v-if="hasUnsavedChanges" class="text-warning ml-2">• Unsaved Changes</span>
            </p>
            <!-- Debug Info -->
            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
              Debug: Valid={{ isValidConfiguration }} | Saving={{ isSaving }} | SiteID={{ !!siteId }} | 
              Button Enabled={{ isValidConfiguration && !isSaving && !!siteId }}
            </p>
          </div>
          <div class="flex gap-2">
            <button
              @click="resetToOriginal"
              v-if="hasUnsavedChanges"
              class="inline-flex items-center justify-center rounded-md bg-secondary py-2 px-4 text-center font-medium text-white hover:bg-opacity-90"
            >
              Reset
            </button>
            <button
              @click="addMenuItem"
              class="inline-flex items-center justify-center rounded-md bg-primary py-2 px-4 text-center font-medium text-white hover:bg-opacity-90"
            >
              Add Menu Item
            </button>
          </div>
        </div>

        <!-- Status Messages -->
        <div v-if="message" :class="{
          'bg-success/10 border-success text-success': messageType === 'success',
          'bg-danger/10 border-danger text-danger': messageType === 'error'
        }" class="rounded-md border px-4 py-3 mb-4">
          {{ message }}
        </div>
      </div>

      <!-- Menu Items -->
      <div class="space-y-6">
        <div v-if="menuItems.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
          No menu items configured. Click "Add Menu Item" to get started.
        </div>

        <div
          v-for="(menuItem, menuIndex) in menuItems"
          :key="menuItem.id"
          class="rounded-sm border border-stroke bg-gray-50 dark:bg-meta-4 p-4"
        >
          <div class="flex justify-between items-start mb-4">
            <div class="flex-1">
              <div class="mb-3">
                <label class="mb-2.5 block text-black dark:text-white font-medium">
                  Menu Title *
                </label>
                <input
                  v-model="menuItem.title"
                  type="text"
                  :class="{
                    'border-danger': !menuItem.title,
                    'border-stroke': menuItem.title
                  }"
                  class="w-full rounded border-[1.5px] bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                  placeholder="Enter menu title"
                />
                <p v-if="!menuItem.title" class="text-danger text-sm mt-1">Title is required</p>
              </div>
              <div class="mb-3">
                <label class="mb-2.5 block text-black dark:text-white font-medium">
                  Columns *
                </label>
                <select
                  v-model.number="menuItem.col"
                  class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
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
              class="ml-4 inline-flex items-center justify-center rounded-md bg-danger py-2 px-3 text-center font-medium text-white hover:bg-opacity-90"
            >
              Remove
            </button>
          </div>

          <div class="mb-4">
            <div class="flex justify-between items-center mb-3">
              <h5 class="font-medium text-black dark:text-white">
                Sub Items ({{ menuItem.subItems.length }})
              </h5>
              <button
                @click="addSubItem(menuItem.id!)"
                class="inline-flex items-center justify-center rounded-md bg-success py-1 px-3 text-sm font-medium text-white hover:bg-opacity-90"
              >
                Add Sub Item
              </button>
            </div>

            <div v-if="menuItem.subItems.length === 0" class="text-center py-4 text-gray-500 dark:text-gray-400 text-sm">
              No sub items. Click "Add Sub Item" to add navigation links.
            </div>

            <div class="space-y-3">
              <div
                v-for="(subItem, subIndex) in menuItem.subItems"
                :key="subIndex"
                class="grid grid-cols-1 md:grid-cols-3 gap-3 p-3 border border-stroke rounded bg-white dark:bg-boxdark"
              >
                <div>
                  <label class="mb-1 block text-sm font-medium text-black dark:text-white">
                    Title *
                  </label>
                  <input
                    v-model="subItem.title"
                    type="text"
                    :class="{
                      'border-danger': !subItem.title,
                      'border-stroke': subItem.title
                    }"
                    class="w-full rounded border-[1.5px] bg-transparent py-2 px-3 text-sm outline-none transition focus:border-primary active:border-primary"
                    placeholder="Sub item title"
                  />
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-black dark:text-white">
                    Description *
                  </label>
                  <input
                    v-model="subItem.description"
                    type="text"
                    :class="{
                      'border-danger': !subItem.description,
                      'border-stroke': subItem.description
                    }"
                    class="w-full rounded border-[1.5px] bg-transparent py-2 px-3 text-sm outline-none transition focus:border-primary active:border-primary"
                    placeholder="Brief description"
                  />
                </div>
                <div class="flex items-end gap-2">
                  <div class="flex-1">
                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">
                      URL *
                    </label>
                    <input
                      v-model="subItem.href"
                      type="text"
                      :class="{
                        'border-danger': !subItem.href,
                        'border-stroke': subItem.href
                      }"
                      class="w-full rounded border-[1.5px] bg-transparent py-2 px-3 text-sm outline-none transition focus:border-primary active:border-primary"
                      placeholder="https://example.com"
                    />
                  </div>
                  <button
                    @click="removeSubItem(menuItem.id!, subIndex)"
                    class="inline-flex items-center justify-center rounded-md bg-danger py-2 px-2 text-sm font-medium text-white hover:bg-opacity-90"
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
      <div class="mt-6 flex justify-between items-center">
        <div class="text-sm text-gray-600 dark:text-gray-400">
          <span v-if="!isValidConfiguration" class="text-danger">
            ⚠️ Please fix validation errors before saving
          </span>
          <span v-else-if="hasUnsavedChanges" class="text-warning">
            📝 You have unsaved changes
          </span>
          <span v-else class="text-success">
            ✅ Configuration is valid and saved
          </span>
        </div>
        
        <div class="flex gap-2">
          <button
            @click="validateAndSave"
            :disabled="!isValidConfiguration || isSaving || !siteId"
            :class="{
              'bg-primary hover:bg-opacity-90': isValidConfiguration && !isSaving && siteId,
              'bg-gray-400 cursor-not-allowed': !isValidConfiguration || isSaving || !siteId
            }"
            class="inline-flex items-center justify-center rounded-md py-3 px-6 text-center font-medium text-white transition-colors"
          >
            <svg v-if="isSaving" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ isSaving ? 'Saving...' : 'Save Menu Configuration' }}
          </button>
          
          <!-- Force Save Button for Testing -->
          <button
            @click="forceSave"
            class="inline-flex items-center justify-center rounded-md bg-warning py-3 px-4 text-center font-medium text-white hover:bg-opacity-90"
            title="Force save (for testing)"
          >
            Force Save
          </button>
          
          <!-- Test DB Button -->
          <button
            @click="testDatabase"
            class="inline-flex items-center justify-center rounded-md bg-secondary py-3 px-4 text-center font-medium text-white hover:bg-opacity-90"
            title="Test database connection"
          >
            Test DB
          </button>
        </div>
      </div>

      <!-- JSON Preview (for debugging) -->
      <details class="mt-6">
        <summary class="cursor-pointer text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-primary">
          View JSON Configuration
        </summary>
        <pre class="mt-2 p-4 bg-gray-100 dark:bg-gray-800 rounded text-xs overflow-auto max-h-60">{{ JSON.stringify(menuItems, null, 2) }}</pre>
      </details>
    </div>
  </DefaultLayout>
</template>
