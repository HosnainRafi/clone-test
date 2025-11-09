<script setup lang="ts">
import DynamicNavbar from '@/components/user/DynamicNavbar.vue';
import Footer from '@/components/user/Footer2.vue';
import { Link } from '@inertiajs/vue3';
import { Calendar, Clock, Download, FileText } from 'lucide-vue-next'; // Relevant icons for tenders

// 1. DEFINE INTERFACES for Tender data
interface Tender {
    id: number;
    title: string;
    tender_number: string;
    description: string;
    attachments: string[];
    published_at: string;
    closing_at: string;
    // Add other fields from your model as needed
}

interface MenuItem {
    title: string;
    // ... other properties
}

interface PageProps {
    tender: Tender; // The main tender being displayed
    latestTenders: Tender[]; // For the sidebar
    menuItems: MenuItem[];
}

// 2. DEFINE PROPS
const props = defineProps<PageProps>();

// 3. HELPER FUNCTIONS
const formatDate = (dateString: string) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleDateString('en-GB', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        timeZone: 'UTC', // Ensure date is not affected by user's timezone
    });
};

const getFileName = (url: string) => {
    try {
        const urlObject = new URL(url);
        // Decodes the pathname and gets the last part
        return decodeURIComponent(urlObject.pathname.split('/').pop() || 'Download File');
    } catch (e) {
        return 'Download File';
    }
};
</script>

<template>
    <div class="flex min-h-screen flex-col bg-gray-50">
        <DynamicNavbar :menuItems="props.menuItems" />

        <!-- 4. HEADER & BREADCRUMBS -->
        <div class="container mx-auto pt-12 text-center">
            <div class="mx-auto mb-8 max-w-4xl">
                <h1 class="mb-4 text-3xl font-bold text-[hsl(var(--secondary))] md:text-4xl">
                    {{ props.tender.title }}
                </h1>
                <div class="text-lg text-gray-600">
                    <Link href="/" class="hover:text-[hsl(var(--primary))] hover:underline">Home</Link>
                    <span class="mx-2">></span>
                    <Link href="/tenders" class="hover:text-[hsl(var(--primary))] hover:underline">Tenders</Link>
                    <span class="mx-2">></span>
                    <span class="font-semibold text-[hsl(var(--secondary))]">Details</span>
                </div>
            </div>
        </div>

        <main class="container mx-auto max-w-7xl flex-grow p-4 md:p-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <article class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <!-- 5. TENDER METADATA (DATES & TENDER NO.) -->
                        <div class="mb-6 flex flex-wrap items-center gap-x-6 gap-y-3 border-b border-gray-200 pb-4 text-sm text-gray-600">
                            <div class="flex items-center font-medium">
                                <FileText class="mr-2 h-4 w-4 text-gray-500" />
                                <span>Tender No:</span>&nbsp;<span class="text-gray-800">{{ props.tender.tender_number }}</span>
                            </div>
                            <div class="flex items-center">
                                <Calendar class="mr-2 h-4 w-4 text-gray-500" />
                                <span>Published:</span>&nbsp;<span class="text-gray-800">{{ formatDate(props.tender.published_at) }}</span>
                            </div>
                            <div class="flex items-center font-semibold text-red-600">
                                <Clock class="mr-2 h-4 w-4" />
                                <span>Closing:</span>&nbsp;<span>{{ formatDate(props.tender.closing_at) }}</span>
                            </div>
                        </div>

                        <!-- 6. TENDER DESCRIPTION (Rendered from HTML) -->
                        <div class="prose lg:prose-lg max-w-none text-gray-600" v-html="props.tender.description"></div>

                        <!-- 7. ATTACHMENTS SECTION -->
                        <div v-if="props.tender.attachments && props.tender.attachments.length > 0" class="mt-8 border-t border-gray-200 pt-6">
                            <h3 class="mb-4 text-lg font-semibold text-[hsl(var(--secondary))]">Downloads & Attachments</h3>
                            <ul class="space-y-3">
                                <li v-for="(fileUrl, index) in props.tender.attachments" :key="index">
                                    <a
                                        :href="fileUrl"
                                        target="_blank"
                                        download
                                        class="group flex items-center gap-3 rounded-md bg-gray-100 p-3 transition-colors hover:bg-blue-50 hover:text-[hsl(var(--primary))]"
                                    >
                                        <Download
                                            class="h-5 w-5 flex-shrink-0 text-gray-500 transition-colors group-hover:text-[hsl(var(--primary))]"
                                        />
                                        <span class="font-medium text-gray-700 transition-colors group-hover:text-[hsl(var(--primary))]">{{
                                            getFileName(fileUrl)
                                        }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </article>
                </div>

                <!-- 8. LATEST TENDERS SIDEBAR -->
                <aside class="space-y-6 lg:col-span-1">
                    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="border-l-3 border-l-[hsl(var(--secondary))] pl-3 text-lg font-semibold text-[hsl(var(--secondary))]">
                            Latest Tenders
                        </h3>
                        <ul class="mt-5 space-y-4">
                            <li
                                v-for="latestTender in props.latestTenders"
                                :key="latestTender.id"
                                class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0"
                            >
                                <Link :href="`/tenders/${latestTender.slug}`" class="group block">
                                    <h4
                                        class="leading-tight font-semibold text-[hsl(var(--secondary))] transition-colors group-hover:text-[hsl(var(--primary))]"
                                    >
                                        {{ latestTender.title }}
                                    </h4>
                                    <div class="mt-2 text-xs text-gray-500">Published on {{ formatDate(latestTender.published_at) }}</div>
                                </Link>
                            </li>
                        </ul>
                        <Link
                            href="/tenders"
                            class="mt-6 block w-full rounded-md bg-gray-100 py-2 text-center font-medium text-[hsl(var(--secondary))] transition-colors hover:bg-gray-200 hover:text-[hsl(var(--primary))]"
                        >
                            View All Tenders →
                        </Link>
                    </div>
                </aside>
            </div>
        </main>

        <Footer />
    </div>
</template>

<style>
/* Re-using the same prose styles from your News/Show.vue for consistency */
.prose {
    color: #4b5563;
}
.prose h1,
.prose h2,
.prose h3,
.prose h4,
.prose h5,
.prose h6 {
    color: #374151;
    font-weight: 600;
}
.prose p {
    margin-bottom: 1.25em;
    line-height: 1.6;
}
.prose a {
    color: hsl(var(--secondary));
    text-decoration: none;
}
.prose a:hover {
    color: hsl(var(--primary));
    text-decoration: underline;
}
.prose ul,
.prose ol {
    margin-left: 1.25em;
    margin-bottom: 1.25em;
}
.prose li {
    margin-bottom: 0.5em;
}
.border-l-3 {
    border-left-width: 3px;
}
</style>
