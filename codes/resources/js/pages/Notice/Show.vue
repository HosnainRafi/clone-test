<script setup lang="ts">
import DynamicNavbar from '@/components/user/DynamicNavbar.vue';
import Footer from '@/components/user/Footer2.vue';
import { Link } from '@inertiajs/vue3';
import { AlertCircle, Building, Calendar, Clock, Paperclip } from 'lucide-vue-next';

// --- INTERFACES & PROPS ---
interface NoticeItem {
    id: number;
    title: string;
    content: string;
    date: string;
    category: string;
    priority: 'high' | 'medium' | 'low';
    department: string;
    validUntil: string;
    attachments: string[];
    link: string;
}

interface PageProps {
    notice: NoticeItem;
    latestNotices: NoticeItem[];
    menuItems: any[];
}

const props = defineProps<PageProps>();

const formattedDate = (dateString: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
};

// Updated priority classes
const priorityClasses = (priority: string) => {
    switch (priority) {
        case 'high':
            return 'text-red-600 bg-red-50';
        case 'medium':
            return 'text-yellow-600 bg-yellow-50';
        default:
            return 'text-blue-600 bg-blue-50';
    }
};
</script>

<template>
    <div class="bg-gray-50">
        <DynamicNavbar :menuItems="props.menuItems" />

        <div class="container mx-auto pt-12 text-center">
            <div class="mx-auto mb-8 max-w-4xl">
                <p class="text-lg text-gray-600">
                    <Link href="/" class="hover:text-[hsl(var(--primary))] hover:underline">Home</Link>
                    <span class="mx-2">></span>
                    <Link href="/notices" class="hover:text-[hsl(var(--primary))] hover:underline">Notices</Link>
                    <span class="mx-2">></span>
                    <span class="font-semibold text-[hsl(var(--secondary))]">Details</span>
                </p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-12">
                <main class="lg:col-span-8">
                    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="prose lg:prose-lg max-w-none text-gray-600">
                            <div class="mb-8">
                                <span
                                    :class="priorityClasses(props.notice.priority)"
                                    class="inline-flex items-center rounded-full px-4 py-1.5 text-sm font-bold"
                                >
                                    <AlertCircle class="mr-2 h-5 w-5" />
                                    Priority: {{ props.notice.priority.charAt(0).toUpperCase() + props.notice.priority.slice(1) }}
                                </span>
                            </div>

                            <h1 class="text-3xl leading-tight font-bold text-[hsl(var(--secondary))] sm:text-4xl">
                                {{ props.notice.title }}
                            </h1>

                            <div class="mt-6 border-y border-gray-200 py-4">
                                <div class="flex flex-wrap gap-x-6 gap-y-3 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <Calendar class="mr-2 h-5 w-5 text-[hsl(var(--secondary))]" />
                                        <span>Published on: {{ formattedDate(props.notice.date) }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <Building class="mr-2 h-5 w-5 text-[hsl(var(--secondary))]" />
                                        <span>From: {{ props.notice.department }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8" v-html="props.notice.content"></div>
                        </div>
                    </div>
                </main>

                <aside class="mt-12 space-y-6 lg:col-span-4 lg:mt-0">
                    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="border-l-3 border-l-[hsl(var(--secondary))] pl-3 text-lg font-semibold text-[hsl(var(--secondary))]">Details</h3>
                        <ul class="mt-4 space-y-3 text-sm text-gray-600">
                            <li v-if="props.notice.validUntil" class="flex items-start">
                                <Clock class="mt-1 mr-3 h-5 w-5 flex-shrink-0 text-gray-500" />
                                <span><strong>Valid Until:</strong> {{ formattedDate(props.notice.validUntil) }}</span>
                            </li>
                            <li v-if="props.notice.attachments && props.notice.attachments.length > 0">
                                <div class="flex items-start">
                                    <Paperclip class="mt-1 mr-3 h-5 w-5 flex-shrink-0 text-gray-500" />
                                    <strong>Attachments:</strong>
                                </div>
                                <ul class="mt-2 list-inside space-y-1 pl-8">
                                    <li v-for="(file, index) in props.notice.attachments" :key="index">
                                        <a href="#" class="text-[hsl(var(--secondary))] hover:text-[hsl(var(--primary))] hover:underline">{{
                                            file
                                        }}</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <div v-if="props.latestNotices.length > 0" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="border-l-3 border-l-[hsl(var(--secondary))] pl-3 text-lg font-semibold text-[hsl(var(--secondary))]">
                            Other Notices
                        </h3>
                        <ul class="mt-4 space-y-4">
                            <li v-for="latest in props.latestNotices" :key="latest.id" class="border-b border-gray-100 pb-3 last:border-b-0">
                                <Link :href="latest.link" class="group block">
                                    <p class="font-semibold text-[hsl(var(--secondary))] transition-colors group-hover:text-[hsl(var(--primary))]">
                                        {{ latest.title }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ formattedDate(latest.date) }}</p>
                                </Link>
                            </li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
        <Footer />
    </div>
</template>

<style>
/* Basic prose styles (same as News/Show) */
.prose {
    color: #4b5563;
}
.prose h1,
.prose h2,
.prose h3 {
    color: #1f2937; /* Use a darker color for headings */
}
.prose h1 {
    color: hsl(var(--secondary)); /* Make main H1 match theme */
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
.border-l-3 {
    border-left-width: 3px;
}
</style>
