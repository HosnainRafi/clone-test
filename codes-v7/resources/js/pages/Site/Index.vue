<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import BreadcrumbDefault from '@/components/Breadcrumbs/BreadcrumbDefault.vue'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import { Trash2, Edit, Eye, Plus, Search, Filter, X } from 'lucide-vue-next'

// Define types
interface Site {
  id: number
  name: string
  domain: string
  description?: string
  theme?: string
  status: string
  menuItems?: string | object
  created_at: string
  updated_at: string
}

interface PaginationLink {
  url: string | null
  label: string
  active: boolean
}

interface SitesData {
    data: Site[]
    links: PaginationLink[]
    current_page: number
    last_page: number
    per_page: number
    total: number
    from: number
    to: number
}

// Props
const props = defineProps<{
  sites: SitesData
  filters?: {
    search?: string
    status?: string
  }
  error?: string
}>()

console.log('Sites data:', props);

// State
const pageTitle = ref('Sites Management')
const loading = ref(false)
const search = ref(props.filters?.search || '')
const statusFilter = ref(props.filters?.status || '')
const page = usePage()
const showFlashMessage = ref(true)

// Computed
const flashMessage = computed(() => {
  const flash = page.props.flash as any
  return flash?.error || flash?.success || flash?.warning || flash?.info || props.error
})

const flashMessageType = computed(() => {
  const flash = page.props.flash as any
  if (flash?.error || props.error) return 'error'
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
  { value: '', label: 'All Status' },
  { value: 'active', label: 'Active' },
  { value: 'inactive', label: 'Inactive' },
  { value: 'maintenance', label: 'Maintenance' }
]

const getStatusBadgeClass = (status: string) => {
  const classes = {
    active: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    inactive: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    maintenance: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'
  }
  return classes[status as keyof typeof classes] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
}

const truncateText = (text: string, length: number = 50) => {
  if (!text) return ''
  return text.length > length ? text.substring(0, length) + '...' : text
}

// Methods
const performSearch = () => {
  loading.value = true
  router.get('/admin/sites', {
    search: search.value,
    status: statusFilter.value
  }, {
    preserveState: true,
    preserveScroll: true,
    onFinish: () => {
      loading.value = false
    }
  })
}

const clearFilters = () => {
  search.value = ''
  statusFilter.value = ''
  performSearch()
}

const deleteSite = (id: number, name: string) => {
  if (confirm(`Are you sure you want to delete the site "${name}"? This action cannot be undone.`)) {
    loading.value = true
    router.delete(`/admin/sites/${id}`, {
      onFinish: () => {
        loading.value = false
      }
    })
  }
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString()
}

const dismissFlashMessage = () => {
  showFlashMessage.value = false
}

// Lifecycle
onMounted(() => {
  // Any initialization logic
})
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

    <!-- Sites Table Section Start -->
    <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
      <!-- Header -->
      <div class="py-6 px-4 md:px-6 xl:px-7.5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <h4 class="text-xl font-semibold text-black dark:text-white">
            Sites Management
          </h4>
          <Link
            :href="'/admin/sites/create'"
            class="inline-flex items-center justify-center rounded-md bg-primary py-2 px-4 text-center font-medium text-white hover:bg-opacity-90 lg:px-8 xl:px-10"
          >
            <Plus class="w-4 h-4 mr-2" />
            Add New Site
          </Link>
        </div>

        <!-- Filters -->
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <div>
            <label class="mb-2 block text-sm font-medium text-black dark:text-white">
              Search
            </label>
            <div class="relative">
              <input
                v-model="search"
                type="text"
                placeholder="Search sites..."
                @keyup.enter="performSearch"
                class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-2 pl-10 pr-4 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
              />
              <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
            </div>
          </div>

          <div>
            <label class="mb-2 block text-sm font-medium text-black dark:text-white">
              Status
            </label>
            <select
              v-model="statusFilter"
              class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent py-2 px-4 text-black outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
            >
              <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                {{ option.label }}
              </option>
            </select>
          </div>

          <div class="flex items-end gap-2">
            <button
              @click="performSearch"
              :disabled="loading"
              class="inline-flex items-center justify-center rounded-md bg-primary py-2 px-4 text-center font-medium text-white hover:bg-opacity-90 disabled:opacity-50"
            >
              <Filter class="w-4 h-4 mr-1" />
              Filter
            </button>
            <button
              @click="clearFilters"
              :disabled="loading"
              class="inline-flex items-center justify-center rounded-md border border-stroke py-2 px-4 text-center font-medium text-black hover:bg-gray-50 dark:border-strokedark dark:text-white dark:hover:bg-meta-4 disabled:opacity-50"
            >
              Clear
            </button>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full table-auto">
          <thead>
            <tr class="bg-gray-2 text-left dark:bg-meta-4">
              <th class="min-w-[200px] py-4 px-4 font-medium text-black dark:text-white xl:pl-11">
                Site Name
              </th>
              <th class="min-w-[150px] py-4 px-4 font-medium text-black dark:text-white">
                Domain
              </th>
              <th class="min-w-[200px] py-4 px-4 font-medium text-black dark:text-white">
                Description
              </th>
              <th class="min-w-[100px] py-4 px-4 font-medium text-black dark:text-white">
                Theme
              </th>
              <th class="min-w-[100px] py-4 px-4 font-medium text-black dark:text-white">
                Status
              </th>
              <th class="min-w-[100px] py-4 px-4 font-medium text-black dark:text-white">
                Created
              </th>
              <th class="py-4 px-4 font-medium text-black dark:text-white">
                Actions
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading" class="text-center">
              <td colspan="7" class="py-5 px-4">
                <div class="flex justify-center">
                  <div class="h-8 w-8 animate-spin rounded-full border-4 border-solid border-primary border-t-transparent"></div>
                </div>
              </td>
            </tr>
            <tr v-else-if="sites.data.length === 0" class="text-center">
              <td colspan="7" class="py-5 px-4 text-black dark:text-white">
                No sites found
              </td>
            </tr>
            <tr
              v-else
              v-for="site in sites.data"
              :key="site.id"
              class="border-b border-stroke dark:border-strokedark"
            >
              <td class="border-b border-[#eee] py-5 px-4 pl-9 dark:border-strokedark xl:pl-11">
                <h5 class="font-medium text-black dark:text-white">
                  {{ site.name }}
                </h5>
              </td>
              <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                <p class="text-sm text-black dark:text-white">
                  {{ site.domain }}
                </p>
              </td>
              <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                <p class="text-sm text-black dark:text-white">
                  {{ truncateText(site.description || '', 60) }}
                </p>
              </td>
              <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                <p class="text-sm text-black dark:text-white">
                  {{ site.theme || 'Default' }}
                </p>
              </td>
              <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                <span
                  :class="getStatusBadgeClass(site.status)"
                  class="inline-flex rounded-full px-3 py-1 text-xs font-medium"
                >
                  {{ site.status }}
                </span>
              </td>
              <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                <p class="text-sm text-black dark:text-white">
                  {{ formatDate(site.created_at) }}
                </p>
              </td>
              <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                <div class="flex items-center space-x-2">
                  <Link
                    :href="`/admin/sites/${site.id}`"
                    class="inline-flex items-center justify-center rounded bg-blue-500 py-1 px-2 text-center font-medium text-white hover:bg-opacity-90"
                    title="View"
                  >
                    <Eye class="w-4 h-4" />
                  </Link>
                  <Link
                    :href="`/admin/sites/${site.id}/edit`"
                    class="inline-flex items-center justify-center rounded bg-warning py-1 px-2 text-center font-medium text-white hover:bg-opacity-90"
                    title="Edit"
                  >
                    <Edit class="w-4 h-4" />
                  </Link>
                  <button
                    @click="deleteSite(site.id, site.name)"
                    class="inline-flex items-center justify-center rounded bg-danger py-1 px-2 text-center font-medium text-white hover:bg-opacity-90"
                    title="Delete"
                  >
                    <Trash2 class="w-4 h-4" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div
        v-if="sites.last_page > 1"
        class="flex flex-col items-center justify-between py-4 px-4 md:px-6 xl:px-7.5 sm:flex-row"
      >
        <div class="flex items-center text-sm text-black dark:text-white">
          Showing {{ sites.from }} to {{ sites.to }} of {{ sites.total }} results
        </div>
        <div class="flex items-center space-x-1 mt-4 sm:mt-0">
          <template v-for="(link, index) in sites.links" :key="index">
            <Link
              v-if="link.url"
              :href="link.url"
              :class="{
                'bg-primary text-white': link.active,
                'bg-white text-black dark:bg-boxdark dark:text-white': !link.active
              }"
              class="px-3 py-2 text-sm border border-stroke dark:border-strokedark rounded hover:bg-gray-50 dark:hover:bg-meta-4"
              v-html="link.label"
            />
            <span
              v-else
              class="px-3 py-2 text-sm text-gray-400 border border-stroke dark:border-strokedark rounded"
              v-html="link.label"
            />
          </template>
        </div>
      </div>
    </div>
    <!-- Sites Table Section End -->
  </DefaultLayout>
</template>