<script setup lang="ts">
import { computed } from 'vue';

// Define the interfaces for the props
interface TopBarLink {
    title: string;
    href: string;
}

interface LoginLink {
    title: string;
    href: string;
}

interface Settings {
    contactEmail?: string;
    address?: string;
    topBarLinks?: TopBarLink[];
    loginLink?: LoginLink;
}

// Define the component's props
const props = defineProps<{
    isDepartmentPage: boolean;
    settings: Settings;
}>();

// Compute the data from settings
const contactEmail = computed(() => {
    console.log('DynamicTopBar - contactEmail:', props.settings.contactEmail);
    return props.settings.contactEmail;
});
const address = computed(() => {
    console.log('DynamicTopBar - address:', props.settings.address);
    return props.settings.address;
});
const topBarLinks = computed(() => {
    console.log('DynamicTopBar - topBarLinks:', props.settings.topBarLinks);
    return props.settings.topBarLinks || [];
});
const loginLink = computed(() => {
    console.log('DynamicTopBar - loginLink:', props.settings.loginLink);
    return props.settings.loginLink;
});
console.log('DynamicTopBar - isDepartmentPage:', props.isDepartmentPage);
</script>

<template>
    <div v-if="isDepartmentPage" class="block bg-[hsl(var(--secondary))] py-2 text-sm text-[hsl(var(--secondary-foreground))]">
        <div class="container mx-auto flex items-center justify-between">
            <div class="hidden items-center space-x-6 md:flex">
                <span v-if="contactEmail" class="flex items-center">
                    <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                    {{ contactEmail }}
                </span>
                <span v-if="address" class="flex items-center">
                    <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            fill-rule="evenodd"
                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    {{ address }}
                </span>
            </div>
            <div class="block">
                <span class="text-xs">MBSTU</span>
            </div>
        </div>
    </div>

    <div v-else class="block bg-[hsl(var(--secondary))] py-2 text-sm text-[hsl(var(--secondary-foreground))]">
        <div class="container mx-auto flex items-center justify-between">
            <div class="flex flex-wrap items-center space-x-1 text-xs 2xsm:space-x-0">
                <a
                    v-for="(link, index) in topBarLinks"
                    :key="index"
                    :href="link.href"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="rounded border-r border-gray-300 px-2 py-1 transition-colors last:border-r-0 hover:text-[hsl(var(--primary))]"
                >
                    {{ link.title }}
                </a>
            </div>
            <div v-if="loginLink" class="flex items-center space-x-1 text-xs">
                <a
                    :href="loginLink.href"
                    class="rounded border-r border-gray-300 px-2 py-1 transition-colors last:border-r-0 hover:text-[hsl(var(--primary))]"
                >
                    {{ loginLink.title }}
                </a>
            </div>
        </div>
    </div>
</template>
