<script setup lang="ts">
import { ref } from 'vue';

// Define the interface for a single news item
interface NewsItem {
    id: number;
    title: string;
    excerpt: string;
    image: string;
    date: string;
    category: string;
    link: string;
    isActive: boolean;
}

// Define props
const props = defineProps<{
    newsItems?: NewsItem[];
}>();

const activeNews = ref(props.newsItems?.filter((item) => item.isActive).slice(0, 3) || []);

const getTimeAgo = (dateString: string) => {
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now.getTime() - date.getTime()) / 1000);
    let interval = seconds / 31536000;
    if (interval > 1) return Math.floor(interval) + ' years ago';
    interval = seconds / 2592000;
    if (interval > 1) return Math.floor(interval) + ' months ago';
    interval = seconds / 86400;
    if (interval > 1) return Math.floor(interval) + ' days ago';
    interval = seconds / 3600;
    if (interval > 1) return Math.floor(interval) + ' hours ago';
    interval = seconds / 60;
    if (interval > 1) return Math.floor(interval) + ' minutes ago';
    return Math.floor(seconds) + ' seconds ago';
};
</script>

<template>
    <div v-if="activeNews.length > 0" class="border-t border-gray-200 bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Latest News</h2>
                <p class="mx-auto mt-4 max-w-2xl text-lg text-gray-500">Stay updated with the latest happenings and stories from our university.</p>
            </div>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="item in activeNews"
                    :key="item.id"
                    class="group relative flex flex-col overflow-hidden rounded-lg border bg-white shadow-md transition-shadow duration-300 hover:shadow-xl"
                >
                    <div class="aspect-w-3 aspect-h-2 bg-gray-200">
                        <img
                            :src="item.image"
                            :alt="item.title"
                            class="h-full w-full object-cover object-center transition-transform duration-500 group-hover:scale-105"
                        />
                    </div>
                    <div class="flex flex-1 flex-col space-y-2 p-6">
                        <p class="text-sm text-gray-500">
                            <time :datetime="item.date">{{ getTimeAgo(item.date) }}</time>
                            <span class="mx-2" aria-hidden="true">&middot;</span>
                            <span class="font-medium text-indigo-600">{{ item.category }}</span>
                        </p>
                        <h3 class="text-lg font-semibold text-gray-900">
                            <a :href="item.link" class="focus:outline-none">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                {{ item.title }}
                            </a>
                        </h3>
                        <p class="flex-1 text-sm text-gray-600">{{ item.excerpt }}</p>
                        <div class="mt-4">
                            <a :href="item.link" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">
                                Read more<span aria-hidden="true"> &rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-10 text-center">
                <a href="/news" class="text-base font-semibold text-indigo-600 hover:text-indigo-500">
                    View all news<span aria-hidden="true"> &rarr;</span>
                </a>
            </div>
        </div>
    </div>
</template>
