<script setup lang="ts">
import DynamicNavbar from '@/components/user/DynamicNavbar.vue';
import Footer from '@/components/user/Footer2.vue';
import { Link } from '@inertiajs/vue3'; // Import Inertia's Link component

// 1. DEFINE INTERFACES
interface NewsArticle {
    title: string;
    excerpt: string;
    image: string;
    date: string;
    category: string;
    link: string;
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
    <div class="flex min-h-screen flex-col bg-gray-100 dark:bg-boxdark-2">
        <!-- Navbar -->
        <DynamicNavbar :menuItems="props.menuItems" />

        <!-- ✅ UPDATED: Page Header with new color and clickable breadcrumbs -->
        <div class="bg-header-teal py-8 text-center text-white shadow-lg">
            <h1 class="text-3xl font-bold">{{ props.newsArticle.title }}</h1>
            <div class="mt-2 text-sm">
                <!-- Use Inertia <Link> for SPA navigation -->
                <Link href="/" class="hover:underline">Home</Link>
                <span class="mx-2">></span>
                <Link href="/news" class="hover:underline">News</Link>
                <span class="mx-2">></span>
                <span class="font-semibold">Read</span>
            </div>
        </div>

        <!-- Main Content Area -->
        <main class="container mx-auto max-w-7xl flex-grow p-4 md:p-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <!-- Left Column: Main Article Content -->
                <div class="lg:col-span-2">
                    <article class="rounded-lg bg-white p-6 shadow-md dark:bg-boxdark">
                        <img
                            v-if="props.newsArticle.image"
                            :src="props.newsArticle.image"
                            :alt="props.newsArticle.title"
                            class="mb-6 w-full rounded-md object-cover"
                        />
                        <h2 class="mb-4 text-2xl font-bold text-black dark:text-white">{{ props.newsArticle.title }}</h2>
                        <div
                            class="mb-4 flex items-center gap-2 border-b border-gray-200 pb-4 text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                ></path>
                            </svg>
                            <time :datetime="props.newsArticle.date">{{
                                formatDate(props.newsArticle.date, { year: 'numeric', month: '2-digit', day: '2-digit' })
                            }}</time>
                        </div>

                        <!-- ✅ THE FINAL FIX: Use v-html to render the content -->
                        <!-- This tells Vue to interpret the 'excerpt' string as HTML -->
                        <!-- The 'prose' class from @tailwindcss/typography styles it -->
                        <div class="prose dark:prose-invert lg:prose-lg max-w-none" v-html="props.newsArticle.excerpt"></div>
                    </article>
                </div>

                <!-- ✅ UPDATED: Right Column Sidebar Styling -->
                <aside class="lg:col-span-1">
                    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-md dark:border-gray-700 dark:bg-boxdark">
                        <h3 class="border-header-teal mb-5 border-l-4 pl-3 text-lg font-semibold text-black dark:text-white">Latest News</h3>
                        <ul class="space-y-5">
                            <li
                                v-for="news in props.latestNews"
                                :key="news.link"
                                class="border-b border-gray-200 pb-5 last:border-b-0 last:pb-0 dark:border-gray-700"
                            >
                                <Link :href="news.link" class="group flex items-start gap-4">
                                    <img :src="news.image" alt="" class="h-20 w-20 flex-shrink-0 rounded-md object-cover" />
                                    <div class="flex-grow">
                                        <h4 class="group-hover:text-header-teal leading-tight font-semibold text-black dark:text-white">
                                            {{ news.title }}
                                        </h4>
                                        <div class="mt-2 flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                            <span>{{ news.category }}</span>
                                            <span>•</span>
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
                            class="text-header-teal mt-6 block w-full rounded-md bg-gray-100 py-2 text-center font-semibold hover:bg-gray-200 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
                        >
                            View All News →
                        </Link>
                    </div>
                </aside>
            </div>
        </main>

        <!-- Footer -->
        <Footer />
    </div>
</template>

<style>
@reference "../../../../resources/css/app.css";
/* You can keep the prose styles from the previous step if needed */
.prose p {
    @apply mb-4 leading-relaxed;
}
</style>
