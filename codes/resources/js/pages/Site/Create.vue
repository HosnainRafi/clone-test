<script setup lang="ts">
import { ref, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import BreadcrumbDefault from '@/components/Breadcrumbs/BreadcrumbDefault.vue'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import { Save, ArrowLeft, Eye, Code, AlertCircle, X } from 'lucide-vue-next'

// Define types
interface Theme {
  id: number
  name: string
  value: string
}

interface FormData {
  name: string
  domain: string
  subdomain: string
  description: string
  theme: string
  theme_name: string
  status: string
  menuItems: string
}

// Props
const props = defineProps<{
  themes?: Theme[]
}>()

// State
const pageTitle = ref('Create New Site')
const jsonMode = ref(false)
const jsonError = ref('')
const page = usePage()
const showFlashMessage = ref(true)

// Form
const form = useForm<FormData>({
  name: '',
  domain: '',
  subdomain: '',
  description: '',
  theme: '',
  theme_name: '',
  status: 'active',
  menuItems: JSON.stringify([
    {
      title: "Academic",
      col: "lg",
      subItems: [
        {
          title: "Departments",
          description: "Explore our academic departments and programs",
          href: "/academic/departments"
        },
        {
          title: "Admissions",
          description: "Information about admissions process",
          href: "/academic/admissions"
        }
      ]
    }
  ], null, 2)
})

// Flash message computed properties
const flashMessage = computed(() => {
  const flash = page.props.flash as any
  return flash?.error || flash?.success || flash?.warning || flash?.info
})

const flashMessageType = computed(() => {
  const flash = page.props.flash as any
  if (flash?.error) return 'error'
  if (flash?.success) return 'success'
  if (flash?.warning) return 'warning'
  if (flash?.info) return 'info'
  return null
})

const flashMessageClass = computed(() => {
  const classes = {
    error: 'border-red-300 bg-red-50 text-red-600 dark:border-red-600 dark:bg-red-900/20 dark:text-red-300',
    success: 'border-green-300 bg-green-50 text-green-600 dark:border-green-600 dark:bg-green-900/20 dark:text-green-300',
    warning: 'border-yellow-300 bg-yellow-50 text-yellow-600 dark:border-yellow-600 dark:bg-yellow-900/20 dark:text-yellow-300',
    info: 'border-blue-300 bg-blue-50 text-blue-600 dark:border-blue-600 dark:bg-blue-900/20 dark:text-blue-300'
  }
  const type = flashMessageType.value
  return type ? classes[type as keyof typeof classes] : classes.info
})

// Computed
const statusOptions = [
  { value: 'active', label: 'Active' },
  { value: 'inactive', label: 'Inactive' },
  { value: 'maintenance', label: 'Maintenance' }
]

const themeOptions = computed(() => {
  const defaultThemes = [
    { id: 0, name: 'Default Theme', value: 'default' },
    { id: 1, name: 'Dark Theme', value: 'dark' },
    { id: 2, name: 'Light Theme', value: 'light' }
  ]
  return props.themes || defaultThemes
})

const parsedMenuItems = computed(() => {
  try {
    if (!form.menuItems) return []
    return JSON.parse(form.menuItems)
  } catch (e) {
    return []
  }
})

const isValidJson = computed(() => {
  try {
    if (!form.menuItems) return true
    JSON.parse(form.menuItems)
    return true
  } catch (e) {
    return false
  }
})

// Methods
const validateJson = () => {
  jsonError.value = ''
  
  if (!form.menuItems) {
    jsonError.value = 'Menu items JSON is required'
    return false
  }

  try {
    const menuItems = JSON.parse(form.menuItems)
    
    if (!Array.isArray(menuItems)) {
      jsonError.value = 'Menu items must be an array'
      return false
    }

    for (let i = 0; i < menuItems.length; i++) {
      const item = menuItems[i]
      
      if (!item.title || !item.col) {
        jsonError.value = `Menu item at index ${i} is missing required fields (title, col)`
        return false
      }
      
      if (!item.subItems || !Array.isArray(item.subItems)) {
        jsonError.value = `Menu item at index ${i} must have subItems array`
        return false
      }
      
      for (let j = 0; j < item.subItems.length; j++) {
        const subItem = item.subItems[j]
        if (!subItem.title || !subItem.description || !subItem.href) {
          jsonError.value = `Sub-item at index ${i}.${j} is missing required fields (title, description, href)`
          return false
        }
      }
    }
    
    return true
  } catch (e) {
    jsonError.value = `Invalid JSON format: ${(e as Error).message}`
    return false
  }
}

const formatJson = () => {
  try {
    if (form.menuItems) {
      const parsed = JSON.parse(form.menuItems)
      form.menuItems = JSON.stringify(parsed, null, 2)
      jsonError.value = ''
    }
  } catch (e) {
    jsonError.value = `Cannot format invalid JSON: ${(e as Error).message}`
  }
}

const addMenuItem = () => {
  try {
    const menuItems = form.menuItems ? JSON.parse(form.menuItems) : []
    menuItems.push({
      title: "New Menu Item",
      col: "md",
      subItems: [
        {
          title: "Example Sub Item",
          description: "Description for the sub item",
          href: "/example-path"
        }
      ]
    })
    form.menuItems = JSON.stringify(menuItems, null, 2)
  } catch (e) {
    jsonError.value = `Cannot add menu item: ${(e as Error).message}`
  }
}

const submit = () => {
  console.log('Submit function called')
  
  if (!validateJson()) {
    console.error('Cannot submit form due to JSON validation errors:', jsonError.value)
    return
  }
  
  form.post('/admin/sites', {
    onStart: () => {
      console.log('Form submission started')
    },
    onSuccess: (page) => {
      console.log('Form submitted successfully:', page)
      // You can add a success message here if needed
    },
    onError: (errors) => {
      console.error('Validation errors:', errors)
      console.log('Form errors object:', form.errors)
    },
    onFinish: () => {
      console.log('Form submission finished')
    }
  })
}

const goBack = () => {
  window.history.back()
}

const dismissFlashMessage = () => {
  showFlashMessage.value = false
}
</script>

<template>
  <DefaultLayout>
    <!-- Breadcrumb Start -->
    <BreadcrumbDefault :pageTitle="pageTitle" />
    <!-- Breadcrumb End -->

    <!-- Flash Message -->
    <div 
      v-if="flashMessage && showFlashMessage" 
      :class="flashMessageClass"
      class="mb-6 rounded-sm border p-4"
    >
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <svg 
            v-if="flashMessageType === 'error'" 
            class="mr-3 h-5 w-5" 
            fill="currentColor" 
            viewBox="0 0 20 20"
          >
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
          <svg 
            v-else-if="flashMessageType === 'success'" 
            class="mr-3 h-5 w-5" 
            fill="currentColor" 
            viewBox="0 0 20 20"
          >
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <svg 
            v-else 
            class="mr-3 h-5 w-5" 
            fill="currentColor" 
            viewBox="0 0 20 20"
          >
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
          </svg>
          <p class="text-sm">{{ flashMessage }}</p>
        </div>
        <button
          @click="dismissFlashMessage"
          class="ml-4 inline-flex h-5 w-5 items-center justify-center rounded-full hover:bg-black/10 dark:hover:bg-white/10"
        >
          <X class="h-3 w-3" />
        </button>
      </div>
    </div>

    <!-- Create Site Form Section Start -->
    <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
      <!-- Header -->
      <div class="border-b border-stroke py-4 px-6.5 dark:border-strokedark">
        <div class="flex items-center justify-between">
          <h3 class="font-medium text-black dark:text-white">
            Create New Site
          </h3>
          <button
            @click="goBack"
            type="button"
            class="inline-flex items-center justify-center rounded-md border border-stroke py-2 px-4 text-center font-medium text-black hover:bg-gray-50 dark:border-strokedark dark:text-white dark:hover:bg-meta-4"
          >
            <ArrowLeft class="w-4 h-4 mr-2" />
            Back
          </button>
        </div>
      </div>

      <!-- Form -->
      <form @submit.prevent="submit" class="p-6.5">
        <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2">
          <!-- Site Name -->
          <div>
            <label class="mb-2.5 block text-black dark:text-white">
              Site Name <span class="text-meta-1">*</span>
            </label>
            <input
              v-model="form.name"
              type="text"
              placeholder="Enter site name"
              class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
              :class="{ 'border-red-500': form.errors.name }"
            />
            <div v-if="form.errors.name" class="mt-1 text-sm text-red-500">
              {{ form.errors.name }}
            </div>
          </div>

          <!-- Domain -->
          <div>
            <label class="mb-2.5 block text-black dark:text-white">
              Domain <span class="text-meta-1">*</span>
            </label>
            <input
              v-model="form.domain"
              type="text"
              placeholder="example.com"
              class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
              :class="{ 'border-red-500': form.errors.domain }"
            />
            <div v-if="form.errors.domain" class="mt-1 text-sm text-red-500">
              {{ form.errors.domain }}
            </div>
          </div>

          <!-- Subdomain -->
          <div>
            <label class="mb-2.5 block text-black dark:text-white">
              Subdomain
            </label>
            <input
              v-model="form.subdomain"
              type="text"
              placeholder="subdomain (optional)"
              class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
              :class="{ 'border-red-500': form.errors.subdomain }"
            />
            <div v-if="form.errors.subdomain" class="mt-1 text-sm text-red-500">
              {{ form.errors.subdomain }}
            </div>
          </div>

          <!-- Theme -->
          <div>
            <label class="mb-2.5 block text-black dark:text-white">
              Theme
            </label>
            <select
              v-model="form.theme_name"
              class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
              :class="{ 'border-red-500': form.errors.theme }"
            >
              <option value="">Select a theme</option>
              <option v-for="theme in themeOptions" :key="theme.id" :value="theme.name">
                {{ theme.name }}
              </option>
            </select>
            <div v-if="form.errors.theme" class="mt-1 text-sm text-red-500">
              {{ form.errors.theme }}
            </div>
          </div>

          <!-- Status -->
          <div>
            <label class="mb-2.5 block text-black dark:text-white">
              Status <span class="text-meta-1">*</span>
            </label>
            <select
              v-model="form.status"
              class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
              :class="{ 'border-red-500': form.errors.status }"
            >
              <option v-for="status in statusOptions" :key="status.value" :value="status.value">
                {{ status.label }}
              </option>
            </select>
            <div v-if="form.errors.status" class="mt-1 text-sm text-red-500">
              {{ form.errors.status }}
            </div>
          </div>
        </div>

        <!-- Description -->
        <div class="mb-6">
          <label class="mb-2.5 block text-black dark:text-white">
            Description
          </label>
          <textarea
            v-model="form.description"
            rows="4"
            placeholder="Enter site description"
            class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
            :class="{ 'border-red-500': form.errors.description }"
          ></textarea>
          <div v-if="form.errors.description" class="mt-1 text-sm text-red-500">
            {{ form.errors.description }}
          </div>
        </div>

        <!-- Menu Items JSON Editor -->
        <div class="mb-6">
          <div class="flex items-center justify-between mb-2.5">
            <label class="block text-black dark:text-white">
              Menu Items (JSON) <span class="text-meta-1">*</span>
            </label>
            <div class="flex gap-2">
              <button
                @click="addMenuItem"
                type="button"
                class="text-sm bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600"
              >
                Add Item
              </button>
              <button
                @click="formatJson"
                type="button"
                class="text-sm bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
              >
                Format JSON
              </button>
              <button
                @click="jsonMode = !jsonMode"
                type="button"
                class="text-sm bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600"
              >
                <Code class="w-4 h-4 inline mr-1" />
                {{ jsonMode ? 'Hide Preview' : 'Show Preview' }}
              </button>
            </div>
          </div>

          <div class="grid grid-cols-1 gap-4" :class="{ 'lg:grid-cols-2': jsonMode }">
            <!-- JSON Editor -->
            <div>
              <textarea
                v-model="form.menuItems"
                rows="20"
                placeholder="Enter menu items JSON"
                class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-mono text-sm text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
                :class="{ 
                  'border-red-500': form.errors.menuItems || jsonError || !isValidJson,
                  'border-green-500': isValidJson && form.menuItems
                }"
              ></textarea>
              
              <!-- JSON Validation Messages -->
              <div v-if="jsonError" class="mt-1 flex items-center text-sm text-red-500">
                <AlertCircle class="w-4 h-4 mr-1" />
                {{ jsonError }}
              </div>
              <div v-else-if="!isValidJson" class="mt-1 flex items-center text-sm text-red-500">
                <AlertCircle class="w-4 h-4 mr-1" />
                Invalid JSON format
              </div>
              <div v-else-if="isValidJson && form.menuItems" class="mt-1 flex items-center text-sm text-green-500">
                <Eye class="w-4 h-4 mr-1" />
                Valid JSON ({{ parsedMenuItems.length }} menu items)
              </div>
              
              <div v-if="form.errors.menuItems" class="mt-1 text-sm text-red-500">
                {{ form.errors.menuItems }}
              </div>
            </div>

            <!-- JSON Preview -->
            <div v-if="jsonMode" class="border border-stroke rounded p-4 dark:border-strokedark">
              <h4 class="text-sm font-medium text-black dark:text-white mb-3">Preview</h4>
              <div v-if="isValidJson && parsedMenuItems.length > 0" class="space-y-3">
                <div
                  v-for="(item, index) in parsedMenuItems"
                  :key="index"
                  class="border border-gray-200 rounded p-3 dark:border-gray-600"
                >
                  <div class="font-medium text-black dark:text-white">
                    {{ item.title }} ({{ item.col }})
                  </div>
                  <div v-if="item.subItems && item.subItems.length > 0" class="mt-2 space-y-1">
                    <div
                      v-for="(subItem, subIndex) in item.subItems"
                      :key="subIndex"
                      class="text-sm text-gray-600 dark:text-gray-300 ml-4"
                    >
                      • <strong>{{ subItem.title }}</strong>: {{ subItem.description }}
                      <br>
                      <span class="text-xs text-blue-500">{{ subItem.href }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else-if="!isValidJson" class="text-red-500 text-sm">
                Invalid JSON - cannot preview
              </div>
              <div v-else class="text-gray-500 text-sm">
                No menu items to preview
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-4">
          <button
            @click="goBack"
            type="button"
            class="inline-flex items-center justify-center rounded-md border border-stroke py-3 px-6 text-center font-medium text-black hover:bg-gray-50 dark:border-strokedark dark:text-white dark:hover:bg-meta-4"
          >
            Cancel
          </button>
          <button
            type="submit"
            :disabled="form.processing || !isValidJson"
            class="inline-flex items-center justify-center rounded-md bg-primary py-3 px-6 text-center font-medium text-white hover:bg-opacity-90 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <Save class="w-4 h-4 mr-2" />
            {{ form.processing ? 'Creating...' : 'Create Site' }}
          </button>
        </div>
      </form>
    </div>
    <!-- Create Site Form Section End -->
  </DefaultLayout>
</template>