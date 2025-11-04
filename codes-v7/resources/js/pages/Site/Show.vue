<script setup lang="ts">
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import BreadcrumbDefault from '@/components/Breadcrumbs/BreadcrumbDefault.vue'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import { Edit, ArrowLeft, Globe, Calendar, Tag, FileText, Menu, ExternalLink } from 'lucide-vue-next'

// Define types
interface Site {
  id: number
  name: string
  domain: string
  description?: string
  theme_name?: string
  status: string
  menuItems?: string | object
  created_at: string
  updated_at: string
}

// Props
const props = defineProps<{
  site: Site
}>()

// State
const pageTitle = ref(`Site Details: ${props.site.name}`)

// Computed
const parsedMenuItems = computed(() => {
  try {
    if (!props.site.menuItems) return []
    const menuItems = typeof props.site.menuItems === 'string' 
      ? JSON.parse(props.site.menuItems) 
      : props.site.menuItems
    return Array.isArray(menuItems) ? menuItems : []
  } catch (e) {
    console.error('Error parsing menu items:', e)
    return []
  }
})

const statusBadgeClass = computed(() => {
  const classes = {
    active: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    inactive: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    maintenance: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'
  }
  return classes[props.site.status as keyof typeof classes] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
})

const totalSubItems = computed(() => {
  return parsedMenuItems.value.reduce((total, item) => {
    return total + (item.subItems ? item.subItems.length : 0)
  }, 0)
})

// Methods
const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const goBack = () => {
  window.history.back()
}

const openDomain = () => {
  if (props.site.domain) {
    const url = props.site.domain.startsWith('http') 
      ? props.site.domain 
      : `https://${props.site.domain}`
    window.open(url, '_blank')
  }
}
</script>

<template>
  <DefaultLayout>
    <!-- Breadcrumb Start -->
    <BreadcrumbDefault :pageTitle="pageTitle" />
    <!-- Breadcrumb End -->

    <!-- Site Details Section Start -->
    <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
      <!-- Header -->
      <div class="border-b border-stroke py-4 px-6.5 dark:border-strokedark">
        <div class="flex items-center justify-between">
          <h3 class="font-medium text-black dark:text-white">
            Site Details: {{ site.name }}
          </h3>
          <div class="flex gap-2">
            <Link
              :href="`/admin/sites/${site.id}/edit`"
              class="inline-flex items-center justify-center rounded-md bg-primary py-2 px-4 text-center font-medium text-white hover:bg-opacity-90"
            >
              <Edit class="w-4 h-4 mr-2" />
              Edit Site
            </Link>
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
      </div>

      <!-- Content -->
      <div class="p-6.5">
        <!-- Basic Information -->
        <div class="mb-8">
          <h4 class="mb-4 text-lg font-semibold text-black dark:text-white">
            Basic Information
          </h4>
          <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <!-- Site Name -->
            <div class="rounded-lg border border-stroke p-4 dark:border-strokedark">
              <div class="flex items-center mb-2">
                <FileText class="w-5 h-5 text-primary mr-2" />
                <label class="text-sm font-medium text-black dark:text-white">Site Name</label>
              </div>
              <p class="text-lg font-semibold text-black dark:text-white">{{ site.name }}</p>
            </div>

            <!-- Domain -->
            <div class="rounded-lg border border-stroke p-4 dark:border-strokedark">
              <div class="flex items-center mb-2">
                <Globe class="w-5 h-5 text-primary mr-2" />
                <label class="text-sm font-medium text-black dark:text-white">Domain</label>
              </div>
              <div class="flex items-center gap-2">
                <p class="text-lg font-semibold text-black dark:text-white">{{ site.domain }}</p>
                <button
                  @click="openDomain"
                  class="text-blue-500 hover:text-blue-700"
                  title="Open domain in new tab"
                >
                  <ExternalLink class="w-4 h-4" />
                </button>
              </div>
            </div>

            <!-- Theme -->
            <div class="rounded-lg border border-stroke p-4 dark:border-strokedark">
              <div class="flex items-center mb-2">
                <Tag class="w-5 h-5 text-primary mr-2" />
                <label class="text-sm font-medium text-black dark:text-white">Theme</label>
              </div>
              <p class="text-lg font-semibold text-black dark:text-white">
                {{ site.theme_name || 'Default' }}
              </p>
            </div>

            <!-- Status -->
            <div class="rounded-lg border border-stroke p-4 dark:border-strokedark">
              <div class="flex items-center mb-2">
                <Calendar class="w-5 h-5 text-primary mr-2" />
                <label class="text-sm font-medium text-black dark:text-white">Status</label>
              </div>
              <span
                :class="statusBadgeClass"
                class="inline-flex rounded-full px-3 py-1 text-sm font-medium capitalize"
              >
                {{ site.status }}
              </span>
            </div>
          </div>

          <!-- Description -->
          <div v-if="site.description" class="mt-6 rounded-lg border border-stroke p-4 dark:border-strokedark">
            <div class="flex items-center mb-2">
              <FileText class="w-5 h-5 text-primary mr-2" />
              <label class="text-sm font-medium text-black dark:text-white">Description</label>
            </div>
            <p class="text-black dark:text-white leading-relaxed">{{ site.description }}</p>
          </div>
        </div>

        <!-- Menu Items -->
        <div class="mb-8">
          <div class="flex items-center justify-between mb-4">
            <h4 class="text-lg font-semibold text-black dark:text-white flex items-center">
              <Menu class="w-5 h-5 text-primary mr-2" />
              Menu Items
            </h4>
            <div class="text-sm text-gray-600 dark:text-gray-300">
              {{ parsedMenuItems.length }} main items, {{ totalSubItems }} sub-items
            </div>
          </div>

          <div v-if="parsedMenuItems.length > 0" class="space-y-4">
            <div
              v-for="(item, index) in parsedMenuItems"
              :key="index"
              class="rounded-lg border border-stroke p-4 dark:border-strokedark"
            >
              <div class="flex items-center justify-between mb-3">
                <h5 class="text-lg font-medium text-black dark:text-white">
                  {{ item.title }}
                </h5>
                <span class="rounded bg-primary/10 px-2 py-1 text-xs font-medium text-primary">
                  {{ item.col }}
                </span>
              </div>

              <div v-if="item.subItems && item.subItems.length > 0" class="space-y-3">
                <div
                  v-for="(subItem, subIndex) in item.subItems"
                  :key="subIndex"
                  class="border-l-2 border-primary/20 pl-4"
                >
                  <div class="flex items-start justify-between">
                    <div class="flex-1">
                      <h6 class="font-medium text-black dark:text-white">
                        {{ subItem.title }}
                      </h6>
                      <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        {{ subItem.description }}
                      </p>
                      <a
                        :href="subItem.href"
                        class="mt-1 inline-flex items-center text-xs text-blue-500 hover:text-blue-700"
                        target="_blank"
                      >
                        {{ subItem.href }}
                        <ExternalLink class="w-3 h-3 ml-1" />
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <div v-else class="text-sm text-gray-500 dark:text-gray-400 italic">
                No sub-items defined
              </div>
            </div>
          </div>

          <div v-else class="rounded-lg border border-stroke p-8 text-center dark:border-strokedark">
            <Menu class="w-12 h-12 text-gray-400 mx-auto mb-3" />
            <p class="text-gray-600 dark:text-gray-300">No menu items configured</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
              Use the edit button to add menu items
            </p>
          </div>
        </div>

        <!-- Metadata -->
        <div>
          <h4 class="mb-4 text-lg font-semibold text-black dark:text-white">
            Site Metadata
          </h4>
          <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="rounded-lg border border-stroke p-4 dark:border-strokedark">
              <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Site ID</label>
              <p class="mt-1 text-lg font-semibold text-black dark:text-white">#{{ site.id }}</p>
            </div>

            <div class="rounded-lg border border-stroke p-4 dark:border-strokedark">
              <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Created At</label>
              <p class="mt-1 text-sm text-black dark:text-white">{{ formatDate(site.created_at) }}</p>
            </div>

            <div class="rounded-lg border border-stroke p-4 dark:border-strokedark">
              <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Last Updated</label>
              <p class="mt-1 text-sm text-black dark:text-white">{{ formatDate(site.updated_at) }}</p>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 flex justify-end gap-4">
          <Link
            href="/admin/sites"
            class="inline-flex items-center justify-center rounded-md border border-stroke py-3 px-6 text-center font-medium text-black hover:bg-gray-50 dark:border-strokedark dark:text-white dark:hover:bg-meta-4"
          >
            Back to Sites List
          </Link>
          <Link
            :href="`/admin/sites/${site.id}/edit`"
            class="inline-flex items-center justify-center rounded-md bg-primary py-3 px-6 text-center font-medium text-white hover:bg-opacity-90"
          >
            <Edit class="w-4 h-4 mr-2" />
            Edit This Site
          </Link>
        </div>
      </div>
    </div>
    <!-- Site Details Section End -->
  </DefaultLayout>
</template>