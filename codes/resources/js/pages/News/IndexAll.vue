<script setup lang="ts">
import Pagination from '@/components/Pagination.vue'; // Import the pagination component
import DynamicNavbar from '@/components/user/DynamicNavbar.vue';
import Footer from '@/components/user/Footer2.vue';
import { Link } from '@inertiajs/vue3';

// 1. DEFINE INTERFACES
interface NewsItem {
    title: string;
    excerpt: string;
    image: string;
    date: string;
    link: string;
}

interface PaginatedNews {
    data: NewsItem[];
    links: any[]; // Laravel's pagination links
}

interface PageProps {
    news: PaginatedNews;
    menuItems: any[];
}

// 2. DEFINE PROPS
const props = defineProps<PageProps>();

// 3. HELPER FUNCTION
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    });
};
</script>

<template>
    <div class="flex min-h-screen flex-col bg-gray-100 dark:bg-boxdark-2">
        <!-- Navbar -->
        <DynamicNavbar :menuItems="props.menuItems" />

        <!-- Page Header -->
        <div class="bg-header-teal py-8 text-center text-white shadow-lg">
            <h1 class="text-3xl font-bold">University News</h1>
            <p class="mt-2 text-sm">
                <Link href="/" class="hover:underline">Home</Link>
                <span class="mx-2">></span>
                <span class="font-semibold">News</span>
            </p>
        </div>

        <!-- Main Content Area: News Grid -->
        <main class="container mx-auto flex-grow p-4 md:p-8">
            <div v-if="props.news.data.length > 0" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <!-- Loop through paginated news items -->
                <article
                    v-for="item in props.news.data"
                    :key="item.link"
                    class="flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white shadow-md transition-shadow duration-300 hover:shadow-xl dark:border-gray-700 dark:bg-boxdark"
                >
                    <!-- Image -->
                    <Link :href="item.link" class="block">
                        <img :src="item.image" :alt="item.title" class="h-56 w-full object-cover" />
                    </Link>

                    <!-- Content -->
                    <div class="flex flex-1 flex-col p-5">
                        <Link :href="item.link" class="block flex-grow">
                            <h3 class="text-lg font-semibold text-black hover:text-red-700 dark:text-white dark:hover:text-red-500">
                                {{ item.title }}
                            </h3>
                        </Link>

                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    ></path>
                                </svg>
                                <time :datetime="item.date">{{ formatDate(item.date) }}</time>
                            </div>
                            <Link
                                :href="item.link"
                                class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700"
                            >
                                READ NEWS →
                            </Link>
                        </div>
                    </div>
                </article>
            </div>
            <div v-else class="py-16 text-center text-gray-500">
                <h2 class="text-2xl font-semibold">No News Articles Found</h2>
                <p class="mt-2">Please check back later for updates.</p>
            </div>

            <!-- Pagination Links -->
            <Pagination :links="props.news.links" />
        </main>

        <!-- Footer -->
        <Footer />
    </div>
</template>
