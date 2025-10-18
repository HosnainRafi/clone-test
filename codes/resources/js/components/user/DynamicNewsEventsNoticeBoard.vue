<script setup lang="ts">
import { Bell, Calendar, ChevronRight, Clock, ExternalLink, Newspaper } from 'lucide-vue-next';
import { ref } from 'vue';

// Define Interfaces
interface NewsItem {
    id: number;
    title: string;
    excerpt: string;
    date: string;
}
interface EventItem {
    id: number;
    title: string;
    description: string;
    date: string;
    time: string;
    venue: string;
}
interface NoticeItem {
    id: number;
    title: string;
    content: string;
    date: string;
}

// Props
const props = defineProps<{
    news: NewsItem[];
    events: EventItem[];
    notices: NoticeItem[];
}>();

const activeTab = ref<'news' | 'events' | 'notices'>('news');

// Format date utility
const formatDate = (dateString: string) => new Date(dateString).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
</script>

<template>
    <section class="container pt-24">
        <!-- Header -->
        <div class="mb-12 text-center">
            <h2 class="mb-4 text-3xl font-bold text-[hsl(var(--secondary))] md:text-4xl">News, Events & Notices</h2>
            <p class="mx-auto max-w-3xl text-lg text-gray-600">Stay updated with the latest from MBSTU.</p>
        </div>

        <!-- Tabs -->
        <div class="mb-8 flex justify-center">
            <div class="inline-flex rounded-lg border bg-white p-1 shadow-lg">
                <button
                    @click="activeTab = 'news'"
                    :class="[
                        'flex items-center rounded-md px-6 py-3 text-sm font-medium',
                        activeTab === 'news' ? 'bg-[hsl(var(--tertiary))] text-white' : 'text-gray-600',
                    ]"
                >
                    <Newspaper class="mr-2 h-4 w-4" /> News
                </button>
                <button
                    @click="activeTab = 'events'"
                    :class="[
                        'flex items-center rounded-md px-6 py-3 text-sm font-medium',
                        activeTab === 'events' ? 'bg-[hsl(var(--tertiary))] text-white' : 'text-gray-600',
                    ]"
                >
                    <Calendar class="mr-2 h-4 w-4" /> Events
                </button>
                <button
                    @click="activeTab = 'notices'"
                    :class="[
                        'flex items-center rounded-md px-6 py-3 text-sm font-medium',
                        activeTab === 'notices' ? 'bg-[hsl(var(--tertiary))] text-white' : 'text-gray-600',
                    ]"
                >
                    <Bell class="mr-2 h-4 w-4" /> Notices
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <!-- News -->
                <div v-if="activeTab === 'news'" class="space-y-6">
                    <div v-for="item in news.slice(0, 4)" :key="item.id" class="rounded-lg border bg-white p-6 shadow-sm">
                        <h3 class="mb-2 text-xl font-semibold text-[hsl(var(--secondary))]">{{ item.title }}</h3>
                        <p class="mb-3 text-sm text-gray-500">{{ formatDate(item.date) }}</p>
                        <p class="mb-4 line-clamp-2 text-gray-600">{{ item.excerpt }}</p>
                        <a :href="`/news/${item.id}`" class="inline-flex items-center font-medium text-[hsl(var(--secondary))]"
                            >Read More <ChevronRight class="ml-1 h-4 w-4"
                        /></a>
                    </div>
                </div>
                <!-- Events -->
                <div v-if="activeTab === 'events'" class="space-y-6">
                    <div v-for="event in events.slice(0, 4)" :key="event.id" class="rounded-lg border bg-white p-6 shadow-sm">
                        <h3 class="mb-2 text-xl font-semibold text-[hsl(var(--secondary))]">{{ event.title }}</h3>
                        <div class="mb-3 flex items-center space-x-4 text-sm text-gray-500">
                            <span><Clock class="mr-1 inline h-4 w-4" />{{ formatDate(event.date) }} at {{ event.time }}</span>
                            <span><Clock class="mr-1 inline h-4 w-4" />{{ event.venue }}</span>
                        </div>
                        <p class="mb-4 line-clamp-2 text-gray-600">{{ event.description }}</p>
                        <a :href="`/events/${event.id}`" class="inline-flex items-center font-medium text-[hsl(var(--secondary))]"
                            >View Details <ExternalLink class="ml-1 h-4 w-4"
                        /></a>
                    </div>
                </div>
                <!-- Notices -->
                <div v-if="activeTab === 'notices'" class="space-y-4">
                    <div v-for="notice in notices.slice(0, 4)" :key="notice.id" class="rounded-lg border bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-[hsl(var(--secondary))]">{{ notice.title }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ formatDate(notice.date) }}</p>
                        <a :href="`/notices/${notice.id}`" class="mt-2 inline-flex items-center font-medium text-[hsl(var(--secondary))]"
                            >Read Notice <ChevronRight class="ml-1 h-4 w-4"
                        /></a>
                    </div>
                </div>
            </div>
            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="rounded-lg border bg-white p-6 shadow-sm">
                    <h3 class="mb-4 text-lg font-semibold text-[hsl(var(--secondary))]">Quick Links</h3>
                    <div class="space-y-2">
                        <a href="/news" class="block font-medium text-[hsl(var(--secondary))] hover:underline">View All News →</a>
                        <a href="/events" class="block font-medium text-[hsl(var(--secondary))] hover:underline">Event Calendar →</a>
                        <a href="/notices" class="block font-medium text-[hsl(var(--secondary))] hover:underline">All Notices →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
