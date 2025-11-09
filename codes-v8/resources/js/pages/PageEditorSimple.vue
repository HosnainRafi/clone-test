<template>
  <DefaultLayout>
    <!-- Breadcrumb -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <h2 class="text-title-md2 font-bold text-black dark:text-white">
        {{ pageId ? 'Edit Page' : 'Create New Page' }}
      </h2>
      <nav>
        <ol class="flex items-center gap-2">
          <li>
            <Link class="font-medium" href="/dashboard">Dashboard /</Link>
          </li>
          <li>
            <Link class="font-medium" href="/dashboard/pages">Pages /</Link>
          </li>
          <li class="font-medium text-primary">{{ pageId ? 'Edit' : 'Create' }}</li>
        </ol>
      </nav>
    </div>

    <!-- Debug Info -->
    <div class="mb-6 rounded-lg border border-stroke bg-white p-4 dark:border-strokedark dark:bg-boxdark">
      <h3 class="mb-2 font-semibold">Debug Info:</h3>
      <p><strong>Page ID:</strong> {{ pageId || 'None (Create mode)' }}</p>
      <p><strong>Form Title:</strong> {{ form.title || 'Empty' }}</p>
      <p><strong>Form Slug:</strong> {{ form.slug || 'Empty' }}</p>
      <p><strong>Loading:</strong> {{ isLoading ? 'Yes' : 'No' }}</p>
    </div>

    <!-- Simple Form -->
    <div class="rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
      <form @submit.prevent="submitForm" class="space-y-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <div>
            <label class="mb-2.5 block text-black dark:text-white">Title</label>
            <input
              v-model="form.title"
              type="text"
              placeholder="Enter page title"
              class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
              required
            />
          </div>

          <div>
            <label class="mb-2.5 block text-black dark:text-white">Slug</label>
            <input
              v-model="form.slug"
              type="text"
              placeholder="page-slug"
              class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
              required
            />
          </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-between border-t border-stroke pt-6 dark:border-strokedark">
          <Link
            href="/dashboard/pages"
            class="inline-flex items-center rounded border border-stroke py-2 px-6 text-black hover:shadow-1 dark:border-strokedark dark:text-white"
          >
            Back to Pages
          </Link>
          <button
            type="submit"
            :disabled="isLoading"
            class="inline-flex items-center rounded bg-primary py-2 px-6 text-white hover:bg-opacity-90 disabled:opacity-50"
          >
            {{ isLoading ? 'Saving...' : (pageId ? 'Update Page' : 'Create Page') }}
          </button>
        </div>
      </form>
    </div>
  </DefaultLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import DefaultLayout from '@/layouts/DefaultLayout.vue'

// Props
const props = defineProps<{
  pageId?: string | number
}>()

// Form data
const form = ref({
  title: '',
  slug: ''
})

const isLoading = ref(false)

// Auto-generate slug from title
watch(() => form.value.title, (newTitle) => {
  if (newTitle && !props.pageId) {
    form.value.slug = newTitle
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/(^-|-$)/g, '')
  }
})

// Load page data if editing
const loadPageData = async () => {
  if (!props.pageId) {
    console.log('No pageId provided, skipping data load')
    return
  }
  
  isLoading.value = true
  
  try {
    console.log('Loading page data for ID:', props.pageId)
    const response = await fetch(`/api/pages/${props.pageId}`)
    console.log('Load page response status:', response.status)
    
    if (response.ok) {
      const page = await response.json()
      console.log('Loaded page data:', page)
      
      form.value.title = page.title || ''
      form.value.slug = page.slug || ''
    } else {
      const errorText = await response.text()
      console.error('Failed to load page:', response.status, errorText)
    }
  } catch (error) {
    console.error('Error loading page data:', error)
  } finally {
    isLoading.value = false
  }
}

// Form submission
const submitForm = async () => {
  isLoading.value = true
  
  try {
    console.log('Submitting form:', form.value)
    
    const formData = {
      ...form.value,
      components: [] // Empty components for now
    }
    
    if (props.pageId) {
      // Update existing page
      const response = await fetch(`/api/pages/${props.pageId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify(formData)
      })
      
      if (response.ok) {
        console.log('Page updated successfully')
        router.visit('/dashboard/pages')
      } else {
        const errorText = await response.text()
        console.error('Failed to update page:', response.status, errorText)
      }
    } else {
      // Create new page
      const response = await fetch('/api/pages', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify(formData)
      })
      
      if (response.ok) {
        const newPage = await response.json()
        console.log('Page created successfully')
        // Redirect to edit page for the newly created page
        router.visit(`/dashboard/pages/${newPage.id}/edit`)
      } else {
        const errorText = await response.text()
        console.error('Failed to create page:', response.status, errorText)
      }
    }
  } catch (error) {
    console.error('Error submitting form:', error)
  } finally {
    isLoading.value = false
  }
}

// Watch for pageId changes and reload data
watch(() => props.pageId, (newPageId) => {
  if (newPageId) {
    console.log('PageId changed to:', newPageId)
    loadPageData()
  }
}, { immediate: true })

// Lifecycle
onMounted(() => {
  console.log('PageEditorSimple mounted with pageId:', props.pageId)
})
</script>
