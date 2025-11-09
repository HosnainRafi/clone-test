<script lang="ts" setup>
import { ref } from "vue";

import { useColorMode } from "@vueuse/core";
const mode = useColorMode();
mode.value = "light"; // Academic websites typically use light mode

import {
  Sheet,
  SheetContent,
  SheetFooter,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from "@/components/user/ui/sheet";

import { Button } from "@/components/user/ui/button";

import { Menu, GraduationCap, X } from "lucide-vue-next";

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
    address: any;
    contactEmail: any;
    siteTitle: any;
}>();

const isOpen = ref<boolean>(false);

</script>

<template>
  <!-- <span> POC message from navbar {{message}} </span> -->
  <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
    <!-- Top Bar with University Info -->
    <div class="block bg-[hsl(var(--secondary))] text-[hsl(var(--secondary-foreground))] py-2 text-sm">
      <div class="container mx-auto flex justify-between items-center">
        <div class="hidden md:flex items-center space-x-6">
          <span class="flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
              <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
            </svg>
            {{ contactEmail }}
          </span>
          <span class="flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
            </svg>
            {{ address }}
          </span>
        </div>
        <div class="block">
          <span class="text-xs">Mawlana Bhashani Science and Technology University</span>
        </div>
      </div>
    </div>
    <!-- Main Navigation Bar -->
    <div class="w-full">
      <div class="container flex items-center justify-between h-20">
        <!-- Logo and Department Name -->
        <div class="flex items-center space-x-4">
          <div class="flex items-center">
            <div class="w-16 h-16 bg-[hsl(var(--primary))] rounded-lg flex items-center justify-center mr-4">
              <GraduationCap class="h-8 w-8 text-[hsl(var(--primary-foreground))]" />
            </div>
            <div>
              <h1 class="text-xl font-bold text-[hsl(var(--primary))] leading-tight">{{ siteTitle }}</h1>
            </div>
          </div>
        </div>

        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex">
          <ul class="flex space-x-1">
            <!-- Home -->
            <li>
              <a 
                href="/" 
                class="bg-transparent hover:bg-[hsl(var(--tertiary-hover))] hover:bg-opacity-60 text-gray-700 hover:text-[hsl(var(--primary))]  px-4 py-2 rounded-md text-sm font-medium transition-colors inline-flex items-center"
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
                                v-for="subItem in menuItem.subItems" :key="subItem.title"
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
                class="p-2 hover:bg-[hsl(var(--tertiary-hover))] hover:bg-opacity-60"
              >
                <Menu class="h-6 w-6 text-gray-700" />
                <span class="sr-only">Open main menu</span>
              </Button>
            </SheetTrigger>

            <SheetContent side="left" class="w-full sm:w-80 bg-white p-0 h-full flex flex-col">
              <!-- Mobile Header -->
              <div class="bg-[hsl(var(--primary))] text-white p-4 flex-shrink-0 relative">
                <SheetHeader class="text-left">
                  <SheetTitle class="flex items-center justify-between text-white text-lg">
                    <div class="flex items-center">
                      <GraduationCap class="h-7 w-7 mr-3" />
                      <div>
                        <div class="font-bold">{{ siteTitle }}</div>
                      </div>
                    </div>
                    <!-- Close Button -->
                    <button
                      @click="isOpen = false"
                      class="flex items-center justify-center w-8 h-8 rounded-md bg-white/10 hover:bg-white/20 transition-colors border border-white/20 hover:border-white/30"
                      aria-label="Close menu"
                    >
                      <X class="h-5 w-5 text-white" />
                    </button>
                  </SheetTitle>
                </SheetHeader>
              </div>

              <!-- Mobile Navigation Content -->
              <div class="flex-1 overflow-y-auto mobile-scroll-area">
                <div class="p-4 mobile-nav-sections">
                  <!-- Home Link -->
                  <div class="mb-4 mobile-nav-section">
                    <a 
                      href="/"
                      @click="isOpen = false"
                      class="flex items-center px-4 py-3 text-[hsl(var(--primary))]  font-semibold text-sm uppercase tracking-wide mobile-section-title">
                      <svg class="w-5 h-5 mr-3 mobile-nav-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                      </svg>
                      Home
                    </a>
                  </div>
                  <!-- About Section -->
                  <div v-for="menuItem in menuItems" :key="menuItem.title" class="mb-4 mobile-nav-section">
                    <div class="flex items-center px-4 py-3 text-[hsl(var(--primary))]  font-semibold text-sm uppercase tracking-wide mobile-section-title">
                      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      {{ menuItem.title }}
                    </div>
                    <div class="ml-4 space-y-1">
                      <a
                        v-for="subItem in menuItem.subItems" :key="subItem.title"
                        :href="subItem.href"
                        @click="isOpen = false"
                        class="block px-4 py-2 text-sm text-gray-600 hover:text-[hsl(var(--primary))]  hover:bg-[hsl(var(--tertiary-hover))] rounded-md transition-colors mobile-nav-item"
                      >
                        {{ subItem.title }}
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Mobile Footer -->
              <div class="border-t border-gray-200 p-4 bg-[hsl(var(--tertiary-hover))] flex-shrink-0 mobile-footer">
                <div class="text-center">
                  <div class="text-xs text-gray-500 mb-2">
                    © 2025 {{ siteTitle }}. All rights reserved.
                  </div>
                  <div class="text-xs text-gray-400">
                    Mawlana Bhashani Science and Technology University
                  </div>
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
.group:hover > div[class*="absolute"],
.group > div[class*="absolute"]:hover {
  opacity: 1 !important;
  visibility: visible !important;
}

/* Add slight delay to prevent flicker */
.group > div[class*="absolute"] {
  transition: opacity 200ms ease-in-out, visibility 200ms ease-in-out;
  transition-delay: 100ms;
}

.group:hover > div[class*="absolute"] {
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
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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
  transition: transform 0.2s ease-in-out, color 0.2s ease-in-out;
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
  max-height: calc(100vh - 120px); /* Ensure room for header and footer */
  min-height: 0; /* Allow flex shrinking */
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
  padding-bottom: 2rem; /* Extra padding at bottom for easier scrolling */
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