<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface NoticeItem {
    id: number;
    title: string;
    date: string;
    link: string;
    priority: 'high' | 'medium' | 'low';
}

const props = defineProps<{ noticeItems: NoticeItem[] }>();

const priorityClass = (priority: string) => {
    if (priority === 'high') return 'border-red-500';
    if (priority === 'medium') return 'border-yellow-500';
    return 'border-gray-300';
};
</script>

<template>
    <div v-if="noticeItems && noticeItems.length" class="bg-gray-100 py-12 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <div class="mb-8 text-center">
                <h2 class="text-3xl font-bold">Latest Notices</h2>
                <p class="text-gray-600 dark:text-gray-400">Important announcements and updates</p>
            </div>
            <div class="space-y-4">
                <Link
                    v-for="notice in noticeItems"
                    :key="notice.id"
                    :href="notice.link"
                    class="block rounded-lg bg-white p-4 shadow transition hover:shadow-lg dark:bg-gray-700"
                    :class="priorityClass(notice.priority) + ' border-l-4'"
                >
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ notice.title }}</h3>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ notice.date }}</span>
                    </div>
                </Link>
            </div>
        </div>
    </div>
</template>
