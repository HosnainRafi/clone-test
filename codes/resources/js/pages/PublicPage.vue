<template>
    <PublicLayout :menuItems="menuItems" :siteData="data.siteData" :footerData="footerData">
        <!-- Page Header -->
        <header class="border-b border-gray-200 bg-white shadow-sm">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ page.title }}</h1>
                        <p v-if="page.description" class="mt-1 text-sm text-gray-500">{{ page.description }}</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        <span>{{ formatDate(page.created_at) }}</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Render Components -->
            <div v-if="page.components && page.components.length > 0" class="space-y-8">
                <div v-for="(component, index) in page.components" :key="component.id" class="w-full">
                    <!-- Component Wrapper -->
                    <div class="component-wrapper">
                        <!-- Render Dynamic Component -->
                        <component
                            :is="getComponent(component.type)"
                            v-if="getComponent(component.type)"
                            :component-data="getComponentData(component)"
                            :index="index"
                        />

                        <!-- Fallback for Unknown Components -->
                        <div v-else class="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center">
                            <div class="text-gray-400">
                                <svg class="mx-auto mb-4 h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                    ></path>
                                </svg>
                                <h3 class="mb-2 text-lg font-medium text-gray-900">Component Not Available</h3>
                                <p class="text-gray-500">The component "{{ component.type }}" could not be loaded.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="py-16 text-center">
                <div class="text-gray-400">
                    <svg class="mx-auto mb-4 h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        ></path>
                    </svg>
                    <h3 class="mb-2 text-lg font-medium text-gray-900">No Content Available</h3>
                    <p class="text-gray-500">This page doesn't have any content yet.</p>
                </div>
            </div>
        </main>

        <!-- Footer is provided by PublicLayout via DynamicFooter -->
    </PublicLayout>
</template>

<script setup lang="ts">
import FacultiesCarousel from '@/components/user/FacultiesCarousel.vue';
import HeadlineMarquee from '@/components/user/HeadlineMarquee.vue';
import HeroCarousel from '@/components/user/HeroCarousel.vue';
import MessageFrom from '@/components/user/MessageFrom3.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { computed } from 'vue';

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

interface PageData {
    id: number;
    title: string;
    slug: string;
    description?: string;
    components: Array<{
        id: string;
        type: string;
        order: number;
    }>;
    created_at: string;
    updated_at: string;
}

interface Props {
    message: string;
    data: {
        siteData: any;
        theme: any;
        components: any;
        viewType: any;
        fullDomain: string;
        page: any;
        menuItems: MenuItemProps[];
        componentSettings?: any;
        footerData?: any;
    };
    page: PageData;
}

const props = defineProps<Props>();

// Extract the data for easier access
const { data, page } = props;

// Use menu items from Laravel data, with fallback to empty array
const menuItems = data.menuItems || [];

// Footer data for DynamicFooter in layout
const footerData = data.footerData || null;

// Available components library
const componentLibrary = {
    HeroCarousel,
    HeadlineMarquee,
    MessageFrom,
    FacultiesCarousel,
};

// Get component by type
const getComponent = (type: string) => {
    try {
        return componentLibrary[type as keyof typeof componentLibrary] || null;
    } catch (error) {
        console.error('Error getting component:', type, error);
        return null;
    }
};

// Get component data with settings
const getComponentData = (component: any) => {
    const baseData = { ...component };

    // If there are component settings for this component, merge them
    if (data.componentSettings && data.componentSettings[component.id]) {
        const settings = data.componentSettings[component.id];

        console.log(`Loading settings for ${component.type} (${component.id}):`, settings);

        // For HeadlineMarquee, pass headlines as props
        if (component.type === 'HeadlineMarquee' && settings.headlines) {
            baseData.headlines = settings.headlines;
            console.log('HeadlineMarquee headlines loaded:', settings.headlines);
        } else if (component.type === 'MessageFrom') {
            baseData.messageData = settings;
            console.log('MessageFrom data loaded:', settings);
        } else if (component.type === 'HeroCarousel') {
            baseData.slides = settings.slides || [];
            console.log('HeroCarousel slides loaded:', settings.slides);
        } else if (component.type === 'FacultiesCarousel') {
            baseData.faculties = settings.faculties || [];
            console.log('FacultiesCarousel faculties loaded:', settings.faculties);
        }

        // Add other component-specific settings here as needed
    } else {
        console.log(`No settings found for ${component.type} (${component.id})`);
    }

    return baseData;
};

// Format date for display
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

// Page title for browser tab
const pageTitle = computed(() => props.page.title);

// Set page title
if (typeof document !== 'undefined') {
    document.title = pageTitle.value;
}
</script>

<style scoped>
/* Ensure components are responsive */
.component-wrapper > * {
    width: 100%;
    max-width: 100%;
}

/* Add spacing between components */
.component-wrapper {
    position: relative;
}
</style>
