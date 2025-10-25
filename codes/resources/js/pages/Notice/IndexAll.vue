<script setup lang="ts">
import DynamicNavbar from '@/components/user/DynamicNavbar.vue';
import Footer from '@/components/user/Footer2.vue';
import { Link } from '@inertiajs/vue3';
import { AlertCircle, Building, Calendar } from 'lucide-vue-next';

// --- INTERFACES & PROPS ---
interface NoticeItem {
    id: number;
    title: string;
    date: string;
    category: string;
    priority: 'high' | 'medium' | 'low';
    department: string;
    link: string;
}

interface PaginatedNotices {
    data: NoticeItem[];
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
}

interface PageProps {
    notices: PaginatedNotices;
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

// Updated priority classes to match the new design
const priorityClasses = (priority: string) => {
    switch (priority) {
        case 'high':
            return 'border-l-red-500 text-red-600 bg-red-50';
        case 'medium':
            return 'border-l-yellow-500 text-yellow-600 bg-yellow-50';
        default:
            return 'border-l-blue-500 text-[hsl(var(--secondary))] bg-blue-50';
    }
};

// Simple hover for the card
const cardClasses = 'border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:shadow-md';
</script>

<template>
    <div>
        <DynamicNavbar :menuItems="props.menuItems" />
        <main class="bg-gray-50">
            <div class="container mx-auto px-4 py-12 sm:px-6 lg:px-8">
                <header class="mb-12 text-center">
                    <h1 class="mb-4 text-3xl font-bold text-[hsl(var(--secondary))] md:text-4xl">Official Notice Board</h1>
                    <p class="mx-auto max-w-3xl text-lg text-gray-600">
                        All important announcements, updates, and information from the university administration.
                    </p>
                    <p class="mt-2 text-lg text-gray-600">
                        <Link href="/" class="hover:text-[hsl(var(--primary))] hover:underline">Home</Link>
                        <span class="mx-2">></span>
                        <span class="font-semibold text-[hsl(var(--secondary))]">Notices</span>
                    </p>
                </header>

                <div v-if="props.notices.data.length > 0" class="space-y-6">
                    <Link
                        v-for="notice in props.notices.data"
                        :key="notice.id"
                        :href="notice.link"
                        class="group block rounded-lg border-l-4 p-5"
                        :class="[priorityClasses(notice.priority), cardClasses]"
                    >
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
                            <div class="flex-grow">
                                <div
                                    class="mb-2 flex items-center text-sm font-semibold"
                                    :class="priority === 'high' ? 'text-red-600' : 'text-gray-600'"
                                >
                                    <AlertCircle v-if="notice.priority === 'high'" class="mr-2 h-4 w-4" />
                                    <span class="font-bold uppercase">{{ notice.priority }}</span>
                                    <span class="mx-2">|</span>
                                    <span>{{ notice.category }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-[hsl(var(--secondary))] transition-colors group-hover:text-[hsl(var(--primary))]">
                                    {{ notice.title }}
                                </h3>
                            </div>
                            <div class="mt-3 flex-shrink-0 text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                <div class="flex items-center sm:justify-end">
                                    <Calendar class="mr-2 h-4 w-4" />
                                    <span>{{ formattedDate(notice.date) }}</span>
                                </div>
                                <div class="mt-2 flex items-center sm:justify-end">
                                    <Building class="mr-2 h-4 w-4" />
                                    <span>{{ notice.department }}</span>
                                </div>
                            </div>
                        </div>
                    </Link>

                    <nav
                        v-if="props.notices.links.length > 3"
                        class="mt-12 flex items-center justify-between border-t border-gray-200 px-4 pt-6 sm:px-0"
                    >
                        <div class="-mt-px flex w-0 flex-1">
                            <Link
                                v-if="props.notices.links[0].url"
                                :href="props.notices.links[0].url"
                                class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-[hsl(var(--primary))]"
                                >&laquo; Previous</Link
                            >
                        </div>
                        <div class="hidden md:-mt-px md:flex">
                            <Link
                                v-for="link in props.notices.links.slice(1, -1)"
                                :key="link.label"
                                :href="link.url || ''"
                                :class="[
                                    'inline-flex items-center border-t-2 px-4 pt-4 text-sm font-medium',
                                    link.active
                                        ? 'border-[hsl(var(--secondary))] text-[hsl(var(--secondary))]'
                                        : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-[hsl(var(--primary))]',
                                ]"
                                ><span v-html="link.label"></span
                            ></Link>
                        </div>
                        <div class="-mt-px flex w-0 flex-1 justify-end">
                            <Link
                                v-if="props.notices.links[props.notices.links.length - 1].url"
                                :href="props.notices.links[props.notices.links.length - 1].url!"
                                class="inline-flex items-center border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-[hsl(var(--primary))]"
                                >Next &raquo;</Link
                            >
                        </div>
                    </nav>
                </div>
                <div v-else class="py-16 text-center">
                    <h2 class="text-2xl font-semibold text-gray-700">No Notices Found</h2>
                    <p class="mt-2 text-gray-500">There are no active announcements at this time.</p>
                </div>
            </div>
        </main>
        <Footer />
    </div>
</template>

<style scoped>
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
