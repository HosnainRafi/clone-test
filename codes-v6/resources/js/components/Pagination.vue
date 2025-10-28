<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface PageLink {
    url: string | null;
    label: string;
    active: boolean;
}

defineProps<{
    links: PageLink[];
}>();
</script>

<template>
    <div v-if="links.length > 3" class="mt-8 flex justify-center space-x-1">
        <template v-for="(link, key) in links" :key="key">
            <div v-if="link.url === null" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-400" v-html="link.label" />
            <Link
                v-else
                :href="link.url"
                class="rounded-md border px-4 py-2 text-sm transition-colors"
                :class="{
                    'border-primary bg-primary text-white': link.active,
                    'border-gray-300 bg-white hover:bg-gray-100': !link.active,
                }"
                v-html="link.label"
            />
        </template>
    </div>
</template>
