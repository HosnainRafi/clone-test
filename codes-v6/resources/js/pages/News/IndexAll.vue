<script setup lang="ts">
import Pagination from '@/components/Pagination.vue'; // Import the pagination component
import DynamicNavbar from '@/components/user/DynamicNavbar.vue';
import Footer from '@/components/user/Footer2.vue';
import { Link } from '@inertiajs/vue3';
import { Calendar } from 'lucide-vue-next';

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
        month: 'long', // Changed to 'long' for consistency
        day: 'numeric',
    });
};
</script>

<template>
    <div class="flex min-h-screen flex-col bg-gray-50">
        <DynamicNavbar :menuItems="props.menuItems" />

        <div class="container mx-auto mb-12 pt-12 text-center">
            <h1 class="mb-4 text-3xl font-bold text-[hsl(var(--secondary))] md:text-4xl">University News</h1>
            <p class="text-lg text-gray-600">
                <Link href="/" class="hover:text-[hsl(var(--primary))] hover:underline">Home</Link>
                <span class="mx-2">></span>
                <span class="font-semibold text-[hsl(var(--secondary))]">News</span>
            </p>
        </div>

        <main class="container mx-auto flex-grow p-4 md:p-8">
            <div v-if="props.news.data.length > 0" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <article
                    v-for="item in props.news.data"
                    :key="item.link"
                    class="group flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:shadow-md"
                >
                    <Link :href="item.link" class="block">
                        <div class="h-56 w-full bg-gradient-to-br from-blue-500 to-purple-600 object-cover"></div>
                    </Link>

                    <div class="flex flex-1 flex-col p-5">
                        <Link :href="item.link" class="block flex-grow">
                            <h3
                                class="mb-3 text-lg font-semibold text-[hsl(var(--secondary))] transition-colors group-hover:text-[hsl(var(--primary))]"
                            >
                                {{ item.title }}
                            </h3>
                        </Link>
                        <p class="mb-4 line-clamp-2 text-gray-600" v-html="item.excerpt"></p>

                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <Calendar class="h-4 w-4" />
                                <time :datetime="item.date">{{ formatDate(item.date) }}</time>
                            </div>
                            <Link
                                :href="item.link"
                                class="inline-flex items-center font-medium text-[hsl(var(--secondary))] hover:text-[hsl(var(--primary))]"
                            >
                                Read More →
                            </Link>
                        </div>
                    </div>
                </article>
            </div>
            <div v-else class="py-16 text-center text-gray-500">
                <h2 class="text-2xl font-semibold">No News Articles Found</h2>
                <p class="mt-2">Please check back later for updates.</p>
            </div>

            <Pagination :links="props.news.links" class="mt-12" />
        </main>

        <Footer />
    </div>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth hover transitions for cards */
.bg-white {
    transition: all 0.3s ease;
}

.bg-white:hover {
    transform: translateY(-2px);
    box-shadow:
        0 10px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
