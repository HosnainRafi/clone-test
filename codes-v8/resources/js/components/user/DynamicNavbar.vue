<script lang="ts" setup>
import { ref } from 'vue';

import { useColorMode } from '@vueuse/core';
const mode = useColorMode();
mode.value = 'light'; // Academic websites typically use light mode

import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/user/ui/sheet';

import { Button } from '@/components/user/ui/button';

import { Menu, X } from 'lucide-vue-next';

interface SubMenuProps {
    title: string;
    description: string;
    href: string;
}

interface MenuItemProps {
    title: string;
    col: number;
    subItems: SubMenuProps[];
}

defineProps<{
    menuItems: MenuItemProps[];
}>();

const isOpen = ref<boolean>(false);
</script>

<template>
    <header class="sticky top-0 z-50 border-b border-gray-200 bg-white shadow-sm">
        <!-- Top Bar with University Info -->

        <!-- Main Navigation Bar -->
        <div class="w-full">
            <div class="container flex h-20 items-center justify-between">
                <!-- Logo and Department Name -->
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <img src="/images/university/logo/MBSTU_logo.png" alt="MBSTU Logo" class="h-16 w-16 object-contain" />
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex">
                    <ul class="flex space-x-1">
                        <!-- Home -->
                        <li>
                            <a
                                href="/"
                                class="hover:bg-opacity-60 inline-flex items-center rounded-md bg-transparent px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-[hsl(var(--tertiary-hover))] hover:text-[hsl(var(--primary))]"
                            >
                                Home
                            </a>
                        </li>

                        <!-- Dynamic Menu Items -->
                        <li v-for="menuItem in menuItems" :key="menuItem.title" class="group relative">
                            <button
                                class="hover:bg-opacity-60 inline-flex items-center rounded-md bg-transparent px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-[hsl(var(--tertiary-hover))] hover:text-[hsl(var(--primary))]"
                            >
                                {{ menuItem.title }}
                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <!-- Dropdown content -->
                            <div
                                :class="`absolute -left-24 mt-1 ${menuItem.title === 'Administration and Offices' ? 'w-[900px]' : 'w-[600px]'} invisible z-50 rounded-lg border border-gray-200 bg-white opacity-0 shadow-lg transition-all duration-200 group-hover:visible group-hover:opacity-100`"
                            >
                                <div class="p-4">
                                    <div :class="`grid grid-cols-${menuItem.col} gap-3`">
                                        <div
                                            v-for="subItem in menuItem.subItems"
                                            :key="subItem.title"
                                            class="group/item rounded-lg p-3 transition-colors hover:bg-[hsl(var(--tertiary-hover))]"
                                        >
                                            <a :href="subItem.href" class="block">
                                                <h3
                                                    class="mb-1 text-sm font-medium text-[hsl(var(--secondary))] group-hover/item:text-[hsl(var(--primary))]"
                                                >
                                                    {{ subItem.title }}
                                                </h3>
                                                <p class="text-xs leading-snug text-gray-600">
                                                    {{ subItem.description }}
                                                </p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </nav>

                <!-- Mobile menu button -->
                <div class="lg:hidden">
                    <Sheet v-model:open="isOpen">
                        <SheetTrigger as-child>
                            <Button
                                variant="ghost"
                                size="sm"
                                @click="isOpen = true"
                                class="hover:bg-opacity-60 p-2 hover:bg-[hsl(var(--tertiary-hover))]"
                            >
                                <Menu class="h-6 w-6 text-gray-700" />
                                <span class="sr-only">Open main menu</span>
                            </Button>
                        </SheetTrigger>

                        <SheetContent side="left" class="flex h-full w-full flex-col bg-white p-0 sm:w-80">
                            <!-- Mobile Header -->
                            <div class="relative flex-shrink-0 bg-[hsl(var(--primary))] p-4 text-white">
                                <SheetHeader class="text-left">
                                    <SheetTitle class="flex items-center justify-between text-lg text-white">
                                        <div class="flex items-center">
                                            <img src="/images/university/logo/MBSTU_logo.png" alt="MBSTU Logo" class="h-12 w-12 object-contain" />
                                        </div>
                                        <!-- Close Button -->
                                        <button
                                            @click="isOpen = false"
                                            class="flex h-8 w-8 items-center justify-center rounded-md border border-white/20 bg-white/10 transition-colors hover:border-white/30 hover:bg-white/20"
                                            aria-label="Close menu"
                                        >
                                            <X class="h-5 w-5 text-white" />
                                        </button>
                                    </SheetTitle>
                                </SheetHeader>
                            </div>

                            <!-- Mobile Navigation Content -->
                            <div class="mobile-scroll-area flex-1 overflow-y-auto">
                                <div class="mobile-nav-sections p-4">
                                    <!-- Home Link -->
                                    <div class="mobile-nav-section mb-4">
                                        <a
                                            href="/"
                                            @click="isOpen = false"
                                            class="mobile-section-title flex items-center px-4 py-3 text-sm font-semibold tracking-wide text-[hsl(var(--primary))] uppercase"
                                        >
                                            <svg
                                                class="mobile-nav-icon mr-3 h-5 w-5 flex-shrink-0"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                                />
                                            </svg>
                                            Home
                                        </a>
                                    </div>

                                    <!-- Dynamic Menu Sections -->
                                    <div v-for="menuItem in menuItems" :key="menuItem.title" class="mobile-nav-section mb-4">
                                        <div
                                            class="mobile-section-title flex items-center px-4 py-3 text-sm font-semibold tracking-wide text-[hsl(var(--primary))] uppercase"
                                        >
                                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    :d="
                                                        menuItem.title === 'About'
                                                            ? 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                                                            : menuItem.title === 'Academics'
                                                              ? 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'
                                                              : menuItem.title === 'Faculties'
                                                                ? 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'
                                                                : menuItem.title === 'Administration and Offices'
                                                                  ? 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
                                                                  : menuItem.title === 'Research'
                                                                    ? 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'
                                                                    : menuItem.title === 'Campus Life'
                                                                      ? 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'
                                                                      : menuItem.title === 'Admission'
                                                                        ? 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                                                                        : 'M15 17h5l-5 5v-5zM9 2v5H4L9 2zm0 0l5 5M4 12h16m-7 7h7'
                                                    "
                                                />
                                            </svg>
                                            {{ menuItem.title }}
                                        </div>
                                        <div class="ml-4 space-y-1">
                                            <a
                                                v-for="item in menuItem.subItems"
                                                :key="item.title"
                                                :href="item.href"
                                                @click="isOpen = false"
                                                class="mobile-nav-item block rounded-md px-4 py-2 text-sm text-gray-600 transition-colors hover:bg-[hsl(var(--tertiary-hover))] hover:text-[hsl(var(--primary))]"
                                            >
                                                {{ item.title }}
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Contact Section -->
                                    <div class="mobile-nav-section mb-4">
                                        <a
                                            href="https://mbstu.ac.bd/contact"
                                            @click="isOpen = false"
                                            class="mobile-section-title flex items-center px-4 py-3 text-sm font-semibold tracking-wide text-[hsl(var(--primary))] uppercase"
                                        >
                                            <svg
                                                class="mobile-nav-icon mr-3 h-5 w-5 flex-shrink-0"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                                />
                                            </svg>
                                            Contact
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Footer -->
                            <div class="mobile-footer flex-shrink-0 border-t border-gray-200 bg-[hsl(var(--tertiary-hover))] p-4">
                                <div class="text-center">
                                    <div class="mb-2 text-xs text-gray-500">© 2025 MBSTU. All rights reserved.</div>
                                    <div class="text-xs text-gray-400">Mawlana Bhashani Science and Technology University</div>
                                </div>
                            </div>
                        </SheetContent>
                    </Sheet>
                </div>
            </div>
        </div>
    </header>
</template>

<style scoped>
/* Academic navbar specific styles */
header {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
}

.university-blue {
    color: #1e3a8a;
}

.university-blue-bg {
    background-color: #1e3a8a;
}

/* Dropdown hover effects */
.group:hover .opacity-0 {
    opacity: 1;
}

.group:hover .invisible {
    visibility: visible;
}

/* Ensure dropdown stays visible when hovering over content */
.group:hover > div[class*='absolute'],
.group > div[class*='absolute']:hover {
    opacity: 1 !important;
    visibility: visible !important;
}

/* Add slight delay to prevent flicker */
.group > div[class*='absolute'] {
    transition:
        opacity 200ms ease-in-out,
        visibility 200ms ease-in-out;
    transition-delay: 100ms;
}

.group:hover > div[class*='absolute'] {
    transition-delay: 0ms;
}

/* Ensure proper contrast and readability */
.nav-link {
    transition: all 0.2s ease-in-out;
}

.nav-link:hover {
    background-color: #eff6ff;
    color: #1e3a8a;
}

/* Mobile menu enhancements */
.mobile-nav-item {
    border-radius: 0.375rem;
    transition: all 0.2s ease-in-out;
}

.mobile-nav-item:hover {
    background-color: #f3f4f6;
    color: #1e3a8a;
}

/* Top bar styling */
.top-bar {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
}

/* Logo container */
.logo-container {
    position: relative;
}

.logo-container::after {
    content: '';
    position: absolute;
    right: -1rem;
    top: 50%;
    transform: translateY(-50%);
    height: 60%;
    width: 1px;
    background-color: #e5e7eb;
}

@media (max-width: 1024px) {
    .logo-container::after {
        display: none;
    }
}

/* Department name styling */
.department-name {
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -0.02em;
}

.university-name {
    font-weight: 400;
    line-height: 1.3;
}

/* Dropdown content styling */
.dropdown-content {
    border: 1px solid #e5e7eb;
    box-shadow:
        0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05);
    border-radius: 0.5rem;
    background-color: white;
}

/* Sub-menu item styling */
.submenu-item {
    padding: 0.75rem;
    border-radius: 0.375rem;
    transition: all 0.2s ease-in-out;
    cursor: pointer;
}

.submenu-item:hover {
    background-color: #f9fafb;
}

.submenu-item h3 {
    font-weight: 500;
    color: #111827;
    margin-bottom: 0.25rem;
    transition: color 0.2s ease-in-out;
}

.submenu-item:hover h3 {
    color: #1e3a8a;
}

.submenu-item p {
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.4;
}

/* Mobile sheet content */
.mobile-sheet-content {
    background-color: white;
    border: none;
}

/* Mobile navigation specific styles */
.mobile-nav-section {
    border-left: 3px solid #eff6ff;
    margin-bottom: 1rem;
    padding-left: 0.5rem;
}

.mobile-nav-section:hover {
    border-left-color: #3b82f6;
}

.mobile-section-title {
    background: linear-gradient(90deg, #eff6ff 0%, transparent 100%);
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
}

/* Mobile link icons */
.mobile-nav-icon {
    transition:
        transform 0.2s ease-in-out,
        color 0.2s ease-in-out;
}

.mobile-nav-item:hover .mobile-nav-icon {
    transform: scale(1.1);
    color: #1e3a8a;
}

/* Mobile header gradient */
.mobile-header-bg {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Mobile scroll area */
.mobile-scroll-area {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
    max-height: calc(100vh - 120px);
    /* Ensure room for header and footer */
    min-height: 0;
    /* Allow flex shrinking */
}

.mobile-scroll-area::-webkit-scrollbar {
    width: 4px;
}

.mobile-scroll-area::-webkit-scrollbar-track {
    background: transparent;
}

.mobile-scroll-area::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 2px;
}

.mobile-scroll-area::-webkit-scrollbar-thumb:hover {
    background-color: #94a3b8;
}

/* Ensure mobile sections are visible */
.mobile-nav-sections {
    min-height: fit-content;
    padding-bottom: 2rem;
    /* Extra padding at bottom for easier scrolling */
}

/* Mobile section visibility fix */
.mb-4 {
    margin-bottom: 1rem !important;
    display: block !important;
    visibility: visible !important;
}

/* Force mobile menu sections to be visible */
@media (max-width: 1024px) {
    .mobile-nav-section {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        position: relative !important;
    }

    /* Ensure sections don't get cut off */
    .space-y-1 > * {
        display: block !important;
    }

    /* Make sure all navigation items are visible */
    .block {
        display: block !important;
        visibility: visible !important;
    }
}

/* Mobile menu button enhancement */
.lg\\:hidden button:hover {
    background-color: #eff6ff !important;
    color: #1e3a8a !important;
}

/* Mobile section styling */
.mobile-section {
    position: relative;
}

.mobile-section::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, transparent, #3b82f6, transparent);
    opacity: 0.3;
}

/* Mobile footer styling */
.mobile-footer {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

/* Enhanced mobile transitions */
.mobile-nav-item {
    position: relative;
    overflow: hidden;
}

.mobile-nav-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
    transition: left 0.3s ease-in-out;
}

.mobile-nav-item:hover::before {
    left: 100%;
}

/* Contact info in top bar */
.contact-info {
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.contact-info svg {
    width: 1rem;
    height: 1rem;
    opacity: 0.9;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .department-name {
        font-size: 1rem;
    }

    .university-name {
        font-size: 0.75rem;
    }

    .logo-container .logo-icon {
        width: 3rem;
        height: 3rem;
    }
}

/* Ensure proper z-index stacking */
.navigation-content {
    z-index: 50;
}

/* Academic color scheme */
:root {
    --academic-blue: #1e3a8a;
    --academic-blue-light: #3b82f6;
    --academic-blue-lighter: #eff6ff;
    --academic-gray: #6b7280;
    --academic-gray-light: #f3f4f6;
}

/* Dropdown arrow animation */
.group:hover svg {
    transform: rotate(180deg);
}

.group svg {
    transition: transform 0.2s ease-in-out;
}
</style>
