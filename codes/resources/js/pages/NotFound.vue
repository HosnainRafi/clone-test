<template>
  <PublicLayout :menuItems="menuItems" :siteData="siteData">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center">
          <h1 class="text-xl font-semibold text-gray-900">Page Not Found</h1>
        </div>
      </div>
    </header>

    <!-- 404 Content -->
    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8">
      <div class="max-w-md w-full text-center">
        <!-- 404 Icon -->
        <div class="mb-8">
          <svg class="mx-auto h-32 w-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
        </div>

        <!-- 404 Message -->
        <div class="mb-8">
          <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
          <h2 class="text-2xl font-semibold text-gray-700 mb-4">Page Not Found</h2>
          <p class="text-gray-500 mb-2">
            The page you're looking for doesn't exist or has been moved.
          </p>
          <p class="text-sm text-gray-400">
            URL: <code class="bg-gray-100 px-2 py-1 rounded">{{ currentUrl }}</code>
          </p>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-4">
          <button
            @click="goBack"
            class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors"
          >
            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Go Back
          </button>
          
          <a
            href="/"
            class="w-full inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors"
          >
            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Go to Homepage
          </a>
        </div>

        <!-- Additional Help -->
        <div class="mt-12 pt-8 border-t border-gray-200">
          <h3 class="text-lg font-medium text-gray-900 mb-4">What can you do?</h3>
          <div class="space-y-3 text-sm text-gray-600">
            <div class="flex items-start">
              <svg class="mr-2 h-5 w-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <span>Check the URL for typos</span>
            </div>
            <div class="flex items-start">
              <svg class="mr-2 h-5 w-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <span>Use the navigation menu to find what you're looking for</span>
            </div>
            <div class="flex items-start">
              <svg class="mr-2 h-5 w-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <span>Go back to the previous page</span>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="text-center text-sm text-gray-500">
          <p>&copy; {{ currentYear }} Your Website. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </PublicLayout>
</template>

<script setup lang="ts">
import { computed, onMounted } from 'vue'
import PublicLayout from '@/layouts/PublicLayout.vue'

// Props interfaces
interface MenuItemProps {
  title: string;
  col: number;
  subItems: {
    title: string;
    description: string;
    href: string;
  }[];
}

interface Props {
  message?: string;
  data?: {
    siteData: any;
    theme: any;
    components: any;
    viewType: any;
    fullDomain: string;
    page: any;
    menuItems: MenuItemProps[];
  };
  requestedUrl?: string;
}

const props = defineProps<Props>()

// Extract data with fallbacks
const menuItems = props.data?.menuItems || []
const siteData = props.data?.siteData || {}

// Computed properties
const currentUrl = computed(() => {
  if (typeof window !== 'undefined') {
    return window.location.pathname
  }
  return props.requestedUrl || '/unknown'
})

const currentYear = computed(() => {
  return new Date().getFullYear()
})

// Methods
const goBack = () => {
  if (typeof window !== 'undefined' && window.history.length > 1) {
    window.history.back()
  } else {
    // Fallback to homepage if no history
    window.location.href = '/'
  }
}

// Set page title
onMounted(() => {
  if (typeof document !== 'undefined') {
    document.title = '404 - Page Not Found'
  }
})
</script>

<style scoped>
/* Custom styles for 404 page */
code {
  font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
}

/* Ensure buttons have proper hover states */
button:hover, a:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Smooth transitions */
button, a {
  transition: all 0.2s ease-in-out;
}
</style>
