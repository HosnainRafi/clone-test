<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Bell, Calendar, ChevronRight, Clock, ExternalLink, MapPin, Newspaper, Star } from 'lucide-vue-next';
import { ref } from 'vue';

// --- 1. Define Correct and Complete Interfaces ---
interface NewsItem {
    id: number;
    title: string;
    excerpt: string;
    date: string;
    category: string;
    link: string;
    author: string;
    readTime: string;
    priority: 'high' | 'medium' | 'low';
}
interface EventItem {
    id: number;
    title: string;
    description: string;
    date: string;
    time: string;
    venue: string;
    status: string;
    link: string;
    category: string;
    organizer: string;
    registration: string;
    fee: string;
    participants: string;
}
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

const props = defineProps<{
    newsItems: NewsItem[];
    eventItems: EventItem[];
    noticeItems: NoticeItem[];
}>();

// --- 2. State and Utility Functions ---
const activeTab = ref<'news' | 'events' | 'notices'>('news');

const formatDate = (dateString: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
};

const getTimeAgo = (dateString: string) => {
    const now = new Date();
    const date = new Date(dateString);
    const diffTime = Math.abs(now.getTime() - date.getTime());
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    if (diffDays === 1) return '1 day ago';
    if (diffDays < 7) return `${diffDays} days ago`;
    if (diffDays < 30) return `${Math.floor(diffDays / 7)} weeks ago`;
    return `${Math.floor(diffDays / 30)} months ago`;
};

const getPriorityColor = (priority: string) => {
    switch (priority) {
        case 'high':
            return 'text-red-600 bg-red-50 border-red-200';
        case 'medium':
            return 'text-yellow-600 bg-yellow-50 border-yellow-200';
        default:
            return 'text-[hsl(var(--secondary))] bg-blue-50 border-blue-200';
    }
};

const getEventStatus = (status: string) => {
    switch (status) {
        case 'upcoming':
            return 'text-green-600 bg-green-50';
        case 'ongoing':
            return 'text-[hsl(var(--secondary))] bg-blue-50';
        case 'completed':
            return 'text-gray-600 bg-gray-50';
        default:
            return 'text-gray-600 bg-gray-50';
    }
};
</script>

<template>
    <section class="container py-24">
        <div class="mb-12 text-center">
            <h2 class="mb-4 text-3xl font-bold text-[hsl(var(--secondary))] md:text-4xl">News, Events & Notices</h2>
            <p class="mx-auto max-w-3xl text-lg text-gray-600">
                Stay updated with the latest news, upcoming events, and important announcements from the Department of Computer Science and
                Engineering
            </p>
        </div>

        <div class="mb-8 flex justify-center">
            <div class="inline-flex rounded-lg border border-gray-200 bg-white p-1 shadow-lg">
                <button
                    @click="activeTab = 'news'"
                    :class="[
                        'flex items-center rounded-md font-medium transition-all duration-200 2xsm:px-1 xsm:py-2 xsm:text-xs md:px-6 md:py-3 md:text-sm',
                        activeTab === 'news'
                            ? 'bg-[hsl(var(--tertiary))] text-white shadow-sm'
                            : 'hover:bg-opacity-60 text-gray-600 hover:bg-[hsl(var(--tertiary-hover))] hover:text-[hsl(var(--primary))]',
                    ]"
                >
                    <Newspaper class="mr-2 h-4 w-4" />
                    News
                    <span
                        :class="[
                            'ml-2 rounded-full px-2 py-0.5 text-xs font-semibold',
                            activeTab === 'news' ? 'bg-white text-[hsl(var(--secondary))]' : 'bg-blue-100 text-blue-700',
                        ]"
                        >{{ newsItems.length }}</span
                    >
                </button>
                <button
                    @click="activeTab = 'events'"
                    :class="[
                        'flex items-center rounded-md font-medium transition-all duration-200 2xsm:px-1 xsm:py-2 xsm:text-xs md:px-6 md:py-3 md:text-sm',
                        activeTab === 'events'
                            ? 'bg-[hsl(var(--tertiary))] text-white shadow-sm'
                            : 'hover:bg-opacity-60 text-gray-600 hover:bg-[hsl(var(--tertiary-hover))] hover:text-[hsl(var(--primary))]',
                    ]"
                >
                    <Calendar class="mr-2 h-4 w-4" />
                    Events
                    <span
                        :class="[
                            'ml-2 rounded-full px-2 py-0.5 text-xs font-semibold',
                            activeTab === 'events' ? 'bg-white text-[hsl(var(--secondary))]' : 'bg-green-100 text-green-700',
                        ]"
                        >{{ eventItems.filter((e) => e.status === 'upcoming').length }}</span
                    >
                </button>
                <button
                    @click="activeTab = 'notices'"
                    :class="[
                        'flex items-center rounded-md font-medium transition-all duration-200 2xsm:px-1 xsm:py-2 xsm:text-xs md:px-6 md:py-3 md:text-sm',
                        activeTab === 'notices'
                            ? 'bg-[hsl(var(--tertiary))] text-white shadow-sm'
                            : 'hover:bg-opacity-60 text-gray-600 hover:bg-[hsl(var(--tertiary-hover))] hover:text-[hsl(var(--primary))]',
                    ]"
                >
                    <Bell class="mr-2 h-4 w-4" />
                    Notices
                    <span
                        :class="[
                            'ml-2 rounded-full px-2 py-0.5 text-xs font-semibold',
                            activeTab === 'notices' ? 'bg-white text-[hsl(var(--secondary))]' : 'bg-red-100 text-red-700',
                        ]"
                        >{{ noticeItems.length }}</span
                    >
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div v-if="activeTab === 'news'" class="space-y-6">
                    <div
                        v-for="item in newsItems.slice(0, 4)"
                        :key="item.id"
                        class="group overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:shadow-md"
                    >
                        <div class="md:flex">
                            <div class="md:w-1/3">
                                <div class="relative h-48 overflow-hidden bg-gradient-to-br from-blue-500 to-purple-600 md:h-full">
                                    <div class="bg-opacity-20 absolute inset-0 bg-black"></div>
                                    <div class="absolute top-4 left-4">
                                        <span :class="['rounded-full px-2 py-1 text-xs font-medium', getPriorityColor(item.priority)]">
                                            {{ item.category }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6 md:w-2/3">
                                <div class="mb-3 flex items-center justify-between">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <Clock class="mr-1.5 h-4 w-4" />{{ formatDate(item.date) }}
                                    </div>
                                    <span class="text-sm font-medium text-[hsl(var(--secondary))]">{{ item.readTime }}</span>
                                </div>
                                <h3
                                    class="mb-3 text-xl font-semibold text-[hsl(var(--secondary))] transition-colors group-hover:text-[hsl(var(--primary))]"
                                >
                                    {{ item.title }}
                                </h3>
                                <p class="mb-4 line-clamp-2 text-gray-600" v-html="item.excerpt"></p>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">By {{ item.author }}</span>
                                    <Link
                                        :href="item.link"
                                        class="inline-flex items-center font-medium text-[hsl(var(--secondary))] hover:text-[hsl(var(--primary))]"
                                    >
                                        Read More <ChevronRight class="ml-1 h-4 w-4" />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="activeTab === 'events'" class="space-y-6">
                    <div
                        v-for="event in eventItems.slice(0, 4)"
                        :key="event.id"
                        class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-md"
                    >
                        <div class="mb-4 flex items-start justify-between">
                            <div class="flex items-center">
                                <div class="mr-4 rounded-lg bg-blue-100 p-3">
                                    <Calendar class="h-6 w-6 text-[hsl(var(--secondary))]" />
                                </div>
                                <div>
                                    <h3 class="mb-2 text-xl font-semibold text-[hsl(var(--secondary))]">{{ event.title }}</h3>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span class="flex items-center"
                                            ><Clock class="mr-1 h-4 w-4" />{{ formatDate(event.date) }} at {{ event.time }}</span
                                        ><span class="flex items-center"><MapPin class="mr-1 h-4 w-4" />{{ event.venue }}</span>
                                    </div>
                                </div>
                            </div>
                            <span :class="['rounded-full px-3 py-1 text-xs font-medium', getEventStatus(event.status)]">{{ event.status }}</span>
                        </div>
                        <p class="mb-4 text-gray-600" v-html="event.description"></p>
                        <div class="grid grid-cols-2 gap-4 text-sm md:grid-cols-4">
                            <div>
                                <span class="font-medium text-[hsl(var(--secondary))]">Category:</span>
                                <p class="text-gray-600">{{ event.category }}</p>
                            </div>
                            <div>
                                <span class="font-medium text-[hsl(var(--secondary))]">Registration:</span>
                                <p class="text-gray-600">{{ event.registration }}</p>
                            </div>
                            <div>
                                <span class="font-medium text-[hsl(var(--secondary))]">Fee:</span>
                                <p class="text-gray-600">{{ event.fee }}</p>
                            </div>
                            <div>
                                <span class="font-medium text-[hsl(var(--secondary))]">Participants:</span>
                                <p class="text-gray-600">{{ event.participants }}</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4">
                            <span class="text-sm text-gray-500">Organized by {{ event.organizer }}</span>
                            <Link
                                :href="event.link"
                                class="inline-flex items-center font-medium text-[hsl(var(--secondary))] hover:text-[hsl(var(--primary))]"
                            >
                                View Details <ExternalLink class="ml-1 h-4 w-4" />
                            </Link>
                        </div>
                    </div>
                </div>

                <div v-if="activeTab === 'notices'" class="space-y-4">
                    <div
                        v-for="notice in noticeItems.slice(0, 4)"
                        :key="notice.id"
                        class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-md"
                    >
                        <div class="mb-3 flex items-start justify-between">
                            <div class="flex items-center">
                                <div :class="['mr-3 rounded-lg p-2', notice.priority === 'high' ? 'bg-red-100' : 'bg-blue-100']">
                                    <Bell :class="['h-5 w-5', notice.priority === 'high' ? 'text-red-600' : 'text-[hsl(var(--secondary))]']" />
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-[hsl(var(--secondary))]">{{ notice.title }}</h3>
                                    <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                        <span>{{ formatDate(notice.date) }}</span
                                        ><span>{{ notice.department }}</span>
                                    </div>
                                </div>
                            </div>
                            <span :class="['rounded-full px-2 py-1 text-xs font-medium', getPriorityColor(notice.priority)]">{{
                                notice.priority
                            }}</span>
                        </div>
                        <p class="mb-4 line-clamp-2 text-gray-600" v-html="notice.content"></p>
                        <div v-if="notice.attachments && notice.attachments.length > 0" class="mb-4">
                            <h4 class="mb-2 text-sm font-medium text-[hsl(var(--secondary))]">Attachments:</h4>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="attachment in notice.attachments"
                                    :key="attachment"
                                    class="inline-flex cursor-pointer items-center rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700 hover:bg-gray-200"
                                >
                                    📎 {{ attachment }}
                                </span>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">Valid until: {{ formatDate(notice.validUntil) }}</div>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="mb-4 text-lg font-semibold text-[hsl(var(--secondary))]">Quick Stats</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total News</span
                            ><span class="font-semibold text-[hsl(var(--secondary))]">{{ newsItems.length }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Upcoming Events</span
                            ><span class="font-semibold text-green-600">{{ eventItems.filter((e) => e.status === 'upcoming').length }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">High Priority Notices</span
                            ><span class="font-semibold text-red-600">{{ noticeItems.filter((n) => n.priority === 'high').length }}</span>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="mb-4 flex items-center text-lg font-semibold text-[hsl(var(--secondary))]">
                        <Star class="mr-2 h-5 w-5 text-yellow-500" />
                        Recent Updates
                    </h3>
                    <div class="space-y-3">
                        <div
                            v-for="item in [...newsItems.slice(0, 2), ...eventItems.slice(0, 1)]"
                            :key="`recent-${item.id}`"
                            class="border-l-3 border-l-[hsl(var(--secondary))] pl-3"
                        >
                            <h4 class="line-clamp-2 text-sm font-medium text-[hsl(var(--secondary))]">{{ item.title }}</h4>
                            <p class="mt-1 text-xs text-gray-500">{{ getTimeAgo(item.date) }}</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="mb-4 text-lg font-semibold text-[hsl(var(--secondary))]">Quick Links</h3>
                    <div class="space-y-2">
                        <Link
                            href="/news"
                            class="block text-sm font-medium text-[hsl(var(--secondary))] hover:text-[hsl(var(--primary))] hover:underline"
                            >View All News →</Link
                        >
                        <Link
                            href="/events"
                            class="block text-sm font-medium text-[hsl(var(--secondary))] hover:text-[hsl(var(--primary))] hover:underline"
                            >Event Calendar →</Link
                        >
                        <Link
                            href="/notices"
                            class="block text-sm font-medium text-[hsl(var(--secondary))] hover:text-[hsl(var(--primary))] hover:underline"
                            >All Notices →</Link
                        >
                        <Link
                            href="/announcements"
                            class="block text-sm font-medium text-[hsl(var(--secondary))] hover:text-[hsl(var(--primary))] hover:underline"
                            >Announcements →</Link
                        >
                    </div>
                </div>
            </aside>
        </div>
    </section>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.border-l-3 {
    border-left-width: 3px;
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

.group {
    transition: all 0.3s ease;
}

@media (max-width: 768px) {
    .grid {
        grid-template-columns: 1fr;
    }

    .bg-white:hover {
        transform: none;
    }
}
</style>
