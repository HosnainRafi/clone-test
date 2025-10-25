<script setup lang="ts">
import DynamicNavbar from '@/components/user/DynamicNavbar.vue';
import Footer from '@/components/user/Footer2.vue';
import { Link } from '@inertiajs/vue3';
import { Calendar, Tag } from 'lucide-vue-next'; // Added icons

// 1. DEFINE INTERFACES
interface NewsArticle {
    title: string;
    excerpt: string; // This should contain the full HTML content
    image: string;
    date: string;
    category: string;
    link: string;
    author: string; // Added author and readTime for completeness
    readTime: string;
}

interface MenuItem {
    title: string;
    // ... other properties
}

interface PageProps {
    newsArticle: NewsArticle;
    latestNews: NewsArticle[];
    menuItems: MenuItem[];
    siteData: any;
}

// 2. DEFINE PROPS
const props = defineProps<PageProps>();

// 3. HELPER FUNCTIONS
const formatDate = (dateString: string, options: Intl.DateTimeFormatOptions = { year: 'numeric', month: 'long', day: 'numeric' }) => {
    return new Date(dateString).toLocaleDateString('en-US', options);
};
</script>

<template>
    <div class="flex min-h-screen flex-col bg-gray-50">
        <DynamicNavbar :menuItems="props.menuItems" />

        <div class="container mx-auto pt-12 text-center">
            <div class="mx-auto mb-8 max-w-4xl">
                <h1 class="mb-4 text-3xl font-bold text-[hsl(var(--secondary))] md:text-4xl">
                    {{ props.newsArticle.title }}
                </h1>
                <div class="text-lg text-gray-600">
                    <Link href="/" class="hover:text-[hsl(var(--primary))] hover:underline">Home</Link>
                    <span class="mx-2">></span>
                    <Link href="/news" class="hover:text-[hsl(var(--primary))] hover:underline">News</Link>
                    <span class="mx-2">></span>
                    <span class="font-semibold text-[hsl(var(--secondary))]">Article</span>
                </div>
            </div>
        </div>

        <main class="container mx-auto max-w-7xl flex-grow p-4 md:p-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <article class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="mb-6 h-96 w-full rounded-md bg-gradient-to-br from-blue-500 to-purple-600"></div>

                        <div class="mb-4 flex flex-wrap items-center gap-x-4 gap-y-2 border-b border-gray-200 pb-4 text-sm text-gray-500">
                            <div class="flex items-center">
                                <Calendar class="mr-1.5 h-4 w-4" />
                                <time :datetime="props.newsArticle.date">
                                    {{ formatDate(props.newsArticle.date) }}
                                </time>
                            </div>
                            <div class="flex items-center">
                                <Tag class="mr-1.5 h-4 w-4" />
                                <span>{{ props.newsArticle.category }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="font-medium">By:</span>&nbsp;<span>{{ props.newsArticle.author || 'Admin' }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="font-medium">{{ props.newsArticle.readTime || '5 min read' }}</span>
                            </div>
                        </div>

                        <div class="prose lg:prose-lg max-w-none text-gray-600" v-html="props.newsArticle.excerpt"></div>
                    </article>
                </div>

                <aside class="space-y-6 lg:col-span-1">
                    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="border-l-3 border-l-[hsl(var(--secondary))] pl-3 text-lg font-semibold text-[hsl(var(--secondary))]">
                            Latest News
                        </h3>
                        <ul class="mt-5 space-y-5">
                            <li v-for="news in props.latestNews" :key="news.link" class="border-b border-gray-200 pb-5 last:border-b-0 last:pb-0">
                                <Link :href="news.link" class="group flex items-start gap-4">
                                    <div class="h-20 w-20 flex-shrink-0 rounded-md bg-gradient-to-br from-blue-400 to-purple-500 object-cover"></div>
                                    <div class="flex-grow">
                                        <h4
                                            class="line-clamp-2 leading-tight font-semibold text-[hsl(var(--secondary))] transition-colors group-hover:text-[hsl(var(--primary))]"
                                        >
                                            {{ news.title }}
                                        </h4>
                                        <div class="mt-2 flex items-center gap-3 text-xs text-gray-500">
                                            <time :datetime="news.date">{{
                                                formatDate(news.date, { year: 'numeric', month: 'short', day: 'numeric' })
                                            }}</time>
                                        </div>
                                    </div>
                                </Link>
                            </li>
                        </ul>
                        <Link
                            href="/news"
                            class="mt-6 block w-full rounded-md bg-gray-100 py-2 text-center font-medium text-[hsl(var(--secondary))] transition-colors hover:bg-gray-200 hover:text-[hsl(var(--primary))]"
                        >
                            View All News →
                        </Link>
                    </div>
                </aside>
            </div>
        </main>

        <Footer />
    </div>
</template>

<style>
/* Basic prose styles for rendered HTML */
.prose {
    color: #4b5563; /* text-gray-600 */
}
.prose h1,
.prose h2,
.prose h3,
.prose h4,
.prose h5,
.prose h6 {
    color: #374151; /* text-gray-700 */
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
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
