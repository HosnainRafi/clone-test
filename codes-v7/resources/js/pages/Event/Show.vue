<script setup lang="ts">
import DynamicNavbar from '@/components/user/DynamicNavbar.vue';
import Footer from '@/components/user/Footer2.vue';
import { Link } from '@inertiajs/vue3';
import { Award, Building, Calendar, Clock, MapPin, Tag, Ticket, Users } from 'lucide-vue-next';

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
    registration: string;
    fee: string;
    organizer: string;
    participants: string;
    link: string;
}

interface PageProps {
    event: EventItem;
    latestEvents: EventItem[];
    menuItems: any[];
}

const props = defineProps<PageProps>();

const formattedDate = (dateString: string) => {
    if (!dateString) return '';
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
    <div class="bg-gray-50">
        <DynamicNavbar :menuItems="props.menuItems" />

        <div class="container mx-auto pt-12 text-center">
            <div class="mx-auto mb-8 max-w-4xl">
                <p class="text-lg text-gray-600">
                    <Link href="/" class="hover:text-[hsl(var(--primary))] hover:underline">Home</Link>
                    <span class="mx-2">></span>
                    <Link href="/events" class="hover:text-[hsl(var(--primary))] hover:underline">Events</Link>
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
                                <span :class="eventStatusClass(props.event.status)" class="rounded-full px-4 py-1.5 text-sm font-semibold">
                                    {{ props.event.status.charAt(0).toUpperCase() + props.event.status.slice(1) }}
                                </span>
                            </div>

                            <h1 class="text-3xl leading-tight font-bold text-[hsl(var(--secondary))] sm:text-4xl">
                                {{ props.event.title }}
                            </h1>

                            <div class="mt-6 border-y border-gray-200 py-4">
                                <div class="flex flex-wrap gap-x-6 gap-y-3 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <Calendar class="mr-2 h-5 w-5 text-[hsl(var(--secondary))]" />
                                        <span>{{ formattedDate(props.event.date) }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <Clock class="mr-2 h-5 w-5 text-[hsl(var(--secondary))]" />
                                        <span>{{ props.event.time }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <MapPin class="mr-2 h-5 w-5 text-[hsl(var(--secondary))]" />
                                        <span>{{ props.event.venue }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <Tag class="mr-2 h-5 w-5 text-[hsl(var(--secondary))]" />
                                        <span>{{ props.event.category }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8" v-html="props.event.description"></div>
                        </div>
                    </div>
                </main>

                <aside class="mt-12 space-y-6 lg:col-span-4 lg:mt-0">
                    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="border-l-3 border-l-[hsl(var(--secondary))] pl-3 text-lg font-semibold text-[hsl(var(--secondary))]">
                            Event Details
                        </h3>
                        <ul class="mt-4 space-y-3 text-sm text-gray-600">
                            <li class="flex items-start">
                                <Building class="mt-1 mr-3 h-5 w-5 flex-shrink-0 text-gray-500" />
                                <span><strong>Organizer:</strong> {{ props.event.organizer }}</span>
                            </li>
                            <li class="flex items-start">
                                <Ticket class="mt-1 mr-3 h-5 w-5 flex-shrink-0 text-gray-500" />
                                <span><strong>Registration:</strong> {{ props.event.registration }}</span>
                            </li>
                            <li class="flex items-start">
                                <Award class="mt-1 mr-3 h-5 w-5 flex-shrink-0 text-gray-500" />
                                <span><strong>Fee:</strong> {{ props.event.fee }}</span>
                            </li>
                            <li class="flex items-start">
                                <Users class="mt-1 mr-3 h-5 w-5 flex-shrink-0 text-gray-500" />
                                <span><strong>Participants:</strong> {{ props.event.participants }}</span>
                            </li>
                        </ul>
                    </div>

                    <div v-if="props.latestEvents.length > 0" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="border-l-3 border-l-[hsl(var(--secondary))] pl-3 text-lg font-semibold text-[hsl(var(--secondary))]">
                            Other Events
                        </h3>
                        <ul class="mt-4 space-y-4">
                            <li v-for="latestEvent in props.latestEvents" :key="latestEvent.id" class="border-b border-gray-100 pb-3 last:border-b-0">
                                <Link :href="latestEvent.link" class="group block">
                                    <p class="font-semibold text-[hsl(var(--secondary))] transition-colors group-hover:text-[hsl(var(--primary))]">
                                        {{ latestEvent.title }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ formattedDate(latestEvent.date) }}</p>
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
    color: #1f2937;
}
.prose h1 {
    color: hsl(var(--secondary));
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
