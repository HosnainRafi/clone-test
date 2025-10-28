<script setup lang="ts">
import DynamicNavbar from '@/components/user/DynamicNavbar.vue';
import Footer from '@/components/user/Footer2.vue';
import { Link } from '@inertiajs/vue3';
import { Calendar, Clock, Tag } from 'lucide-vue-next';

// --- INTERFACES & PROPS ---
interface EventItem {
    id: number;
    title: string;
    description: string;
    date: string;
    time: string;
    endDate: string;
    venue: string;
    category: string;
    status: 'upcoming' | 'completed' | 'postponed';
    link: string;
}

interface PaginatedEvents {
    data: EventItem[];
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
}

interface PageProps {
    events: PaginatedEvents;
    menuItems: any[];
}

const props = defineProps<PageProps>();

const formattedDate = (dateString: string) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

// Updated status class to match target design
const eventStatusClass = (status: string) => {
    switch (status) {
        case 'upcoming':
            return 'text-green-600 bg-green-50';
        case 'completed':
            return 'text-gray-600 bg-gray-50';
        case 'postponed':
            return 'text-yellow-600 bg-yellow-50';
        default:
            return 'text-gray-600 bg-gray-50';
    }
};
</script>

<template>
    <div>
        <DynamicNavbar :menuItems="props.menuItems" />
        <main class="bg-gray-50">
            <div class="container mx-auto px-4 py-12 sm:px-6 lg:px-8">
                <div class="mb-12 text-center">
                    <h1 class="mb-4 text-3xl font-bold text-[hsl(var(--secondary))] md:text-4xl">University Events</h1>
                    <p class="mx-auto max-w-3xl text-lg text-gray-600">
                        Stay updated with the latest workshops, seminars, and conferences happening on campus.
                    </p>
                    <p class="mt-2 text-lg text-gray-600">
                        <Link href="/" class="hover:text-[hsl(var(--primary))] hover:underline">Home</Link>
                        <span class="mx-2">></span>
                        <span class="font-semibold text-[hsl(var(--secondary))]">Events</span>
                    </p>
                </div>

                <div v-if="props.events.data.length > 0">
                    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                        <Link
                            v-for="event in props.events.data"
                            :key="event.id"
                            :href="event.link"
                            class="group block rounded-lg border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:shadow-md"
                        >
                            <div class="p-6">
                                <div class="mb-3 flex items-center justify-between">
                                    <span
                                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold"
                                        :class="eventStatusClass(event.status)"
                                    >
                                        {{ event.status.charAt(0).toUpperCase() + event.status.slice(1) }}
                                    </span>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <Tag class="mr-1.5 h-4 w-4" />
                                        <span>{{ event.category }}</span>
                                    </div>
                                </div>

                                <h3
                                    class="text-xl font-semibold text-[hsl(var(--secondary))] transition-colors group-hover:text-[hsl(var(--primary))]"
                                >
                                    {{ event.title }}
                                </h3>

                                <p class="mt-3 line-clamp-3 text-sm text-gray-600" v-html="event.description"></p>

                                <div class="mt-4 border-t border-gray-200 pt-4">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <Calendar class="mr-2 h-5 w-5" />
                                        <span>{{ formattedDate(event.date) }}</span>
                                        <span v-if="event.endDate && event.endDate !== event.date"> - {{ formattedDate(event.endDate) }}</span>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500">
                                        <Clock class="mr-2 h-5 w-5" />
                                        <span>{{ event.time }} at {{ event.venue }}</span>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </div>

                    <nav v-if="props.events.links.length > 3" class="mt-12 flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
                        <div class="-mt-px flex w-0 flex-1">
                            <Link
                                v-if="props.events.links[0].url"
                                :href="props.events.links[0].url"
                                class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-[hsl(var(--primary))]"
                            >
                                &laquo; Previous
                            </Link>
                        </div>
                        <div class="hidden md:-mt-px md:flex">
                            <Link
                                v-for="link in props.events.links.slice(1, -1)"
                                :key="link.label"
                                :href="link.url || ''"
                                :class="[
                                    'inline-flex items-center border-t-2 px-4 pt-4 text-sm font-medium',
                                    link.active
                                        ? 'border-[hsl(var(--secondary))] text-[hsl(var(--secondary))]'
                                        : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-[hsl(var(--primary))]',
                                ]"
                            >
                                <span v-html="link.label"></span>
                            </Link>
                        </div>
                        <div class="-mt-px flex w-0 flex-1 justify-end">
                            <Link
                                v-if="props.events.links[props.events.links.length - 1].url"
                                :href="props.events.links[props.events.links.length - 1].url!"
                                class="inline-flex items-center border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-[hsl(var(--primary))]"
                            >
                                Next &raquo;
                            </Link>
                        </div>
                    </nav>
                </div>
                <div v-else class="py-16 text-center">
                    <h2 class="text-2xl font-semibold text-gray-700">No Events Scheduled</h2>
                    <p class="mt-2 text-gray-500">Please check back later for updates.</p>
                </div>
            </div>
        </main>
        <Footer />
    </div>
</template>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
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
