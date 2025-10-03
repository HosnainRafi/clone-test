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

interface RouteProps {
  href: string;
  label: string;
  children?: RouteProps[];
}

interface SubMenuProps {
  title: string;
  description: string;
  href: string;
}

const mainNavItems: RouteProps[] = [
  {
    href: "/",
    label: "Home",
  },
  {
    href: "#about",
    label: "About",
    children: [
      { href: "#message-chairman", label: "Message from Chairman" },
      { href: "#message-department", label: "Message from Department" },
      { href: "#history", label: "History of CSE, MBSTU" },
      { href: "#mission-vision", label: "Our Mission and Vision" },
      { href: "#achievements", label: "Achievements" },
      { href: "#facilities", label: "Facilities" },
    ]
  },
  {
    href: "#academic",
    label: "Academic",
    children: [
      { href: "#undergraduate", label: "Undergraduate Program" },
      { href: "#graduate", label: "Graduate Program" },
      { href: "#admission", label: "Admission" },
      { href: "#curriculum", label: "Curriculum" },
      { href: "#calendar", label: "Academic Calendar" },
    ]
  },
  {
    href: "#research",
    label: "Research",
    children: [
      { href: "#publications", label: "Publications" },
      { href: "#projects", label: "Projects" },
      { href: "#labs", label: "Research Labs" },
    ]
  },
  {
    href: "#people",
    label: "People",
    children: [
      { href: "#faculty", label: "Faculty" },
      { href: "#staff", label: "Staff" },
      { href: "#students", label: "Students" },
    ]
  },
  {
    href: "#notices",
    label: "Notice",
  },
  {
    href: "#news",
    label: "News",
  },
  {
    href: "#contact",
    label: "Contact",
  },
];

const aboutItems: SubMenuProps[] = [
  {
    title: "Message from Department",
    description: "Welcome message and overview from the department",
    href: "/page/message-from-department"
  },
  {
    title: "Message from Chairman",
    description: "Welcome message and vision from department chairman",
    href: "/page/message-from-chairman"
  },
  {
    title: "History of CSE, MBSTU",
    description: "Learn about our department's journey since 2003",
    href: "/page/history-of-cse-mbstu"
  },
  {
    title: "Why CSE, MBSTU?",
    description: "Discover what makes our department unique and distinguished",
    href: "/page/why-cse-mbstu"
  },
  {
    title: "Our Mission and Vision",
    description: "Our commitment to excellence in computer science education",
    href: "/page/our-mission-and-vision"
  },
  {
    title: "Achievements",
    description: "Awards, recognitions and milestones of our department",
    href: "/page/achievements"
  },
  {
    title: "Facilities",
    description: "State-of-the-art laboratories and infrastructure",
    href: "/page/facilities"
  }
];

const academicItems: SubMenuProps[] = [
  {
    title: "Undergraduate Program",
    description: "Bachelor of Science in Computer Science and Engineering",
    href: "/page/undergraduate-program"
  },
  {
    title: "Graduate Program",
    description: "Master's and PhD programs in Computer Science",
    href: "/page/graduate-program"
  },
  {
    title: "Professional Program",
    description: "Professional development and certification programs",
    href: "/page/professional-program"
  },
  {
    title: "Others Program",
    description: "Specialized and continuing education programs",
    href: "/page/other-program"
  },
  {
    title: "Undergraduate Admission",
    description: "Admission requirements and process for undergraduate programs",
    href: "/page/undergraduate-admission"
  },
  {
    title: "Graduate Admission",
    description: "Admission requirements and process for graduate programs",
    href: "/page/graduate-admission"
  },
  {
    title: "Undergraduate Curriculum",
    description: "Course structure and curriculum for undergraduate programs",
    href: "/academic/curriculum/undergraduate"
  },
  {
    title: "Graduate Curriculum",
    description: "Course structure and curriculum for graduate programs",
    href: "/academic/curriculum/graduate"
  },
  {
    title: "Academic Calendar",
    description: "Academic calendar, schedules and important dates",
    href: "/academic/calendar"
  }
];

const researchItems: SubMenuProps[] = [
  {
    title: "Publications",
    description: "Research papers, journal articles and conference proceedings",
    href: "/publications"
  },
  {
    title: "Projects",
    description: "Current and completed research projects and initiatives",
    href: "/projects"
  },
  {
    title: "Research Labs",
    description: "Specialized laboratories and research facilities",
    href: "/research/labs"
  },
  {
    title: "Research Areas",
    description: "Key areas of research expertise and specialization",
    href: "/research/areas"
  }
];

const isOpen = ref<boolean>(false);

defineProps<{
  message?: string;
}>();
</script>

<template>
  <!-- <span> POC message from navbar {{message}} </span> -->
  <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
    <!-- Top Bar with University Info (Hidden on mobile) -->
    <div class="hidden md:block bg-[hsl(var(--secondary))] text-[hsl(var(--secondary-foreground))] py-2 px-4 text-sm">
      <div class="container mx-auto flex justify-between items-center">
        <div class="flex items-center space-x-6">
          <span class="flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
              <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
            </svg>
            cse@mbstu.ac.bd
          </span>
          <span class="flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
            </svg>
            Santosh, Tangail, Bangladesh
          </span>
        </div>
        <div class="hidden md:block">
          <span class="text-xs">{{ 'Mawlana Bhashani Science and Technology University' }}</span>
        </div>
      </div>
    </div>

    <!-- Main Navigation Bar -->
    <div class="px-4">
      <div class="container flex items-center justify-between h-20">
        <!-- Logo and Department Name -->
        <div class="flex items-center space-x-4">
          <div class="flex items-center">
        <div class="w-16 h-16 bg-[hsl(var(--primary))] rounded-lg flex items-center justify-center mr-4">
          <GraduationCap class="h-8 w-8 text-[hsl(var(--primary-foreground))]" />
        </div>
        <div>
          <h1 class="text-xl font-bold text-[hsl(var(--primary))] leading-tight">Computer Science and Engineering</h1>
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
                class="bg-transparent hover:bg-blue-50 text-gray-700 hover:text-blue-900 px-4 py-2 rounded-md text-sm font-medium transition-colors inline-flex items-center"
              >
                Home
              </a>
            </li>

            <!-- About (with dropdown) -->
            <li class="relative group">
              <button 
                class="bg-transparent hover:bg-[hsl(var(--tertiary-hover))] hover:bg-opacity-60 text-gray-700 hover:text-[hsl(var(--primary))] px-4 py-2 rounded-md text-sm font-medium transition-colors inline-flex items-center"
              >
                About
                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <!-- Dropdown content -->
              <div class="absolute left-0 mt-1 w-[600px] bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <div class="p-4">
                  <div class="grid grid-cols-2 gap-3">
                    <div 
                      v-for="subItem in aboutItems" 
                      :key="subItem.title"
                      class="group/item rounded-lg p-3 hover:bg-gray-50 transition-colors"
                    >
                      <a :href="subItem.href" class="block">
                        <h3 class="font-medium text-gray-900 group-hover/item:text-blue-900 mb-1 text-sm">
                          {{ subItem.title }}
                        </h3>
                        <p class="text-xs text-gray-600 leading-snug">
                          {{ subItem.description }}
                        </p>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </li>

            <!-- Academic (with dropdown) -->
            <li class="relative group">
              <button 
                class="bg-transparent hover:bg-blue-50 text-gray-700 hover:text-blue-900 px-4 py-2 rounded-md text-sm font-medium transition-colors inline-flex items-center"
              >
                Academic
                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <!-- Dropdown content -->
              <div class="absolute left-0 mt-1 w-[600px] bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <div class="p-4">
                  <div class="grid grid-cols-2 gap-3">
                    <div 
                      v-for="subItem in academicItems" 
                      :key="subItem.title"
                      class="group/item rounded-lg p-3 hover:bg-gray-50 transition-colors"
                    >
                      <a :href="subItem.href" class="block">
                        <h3 class="font-medium text-gray-900 group-hover/item:text-blue-900 mb-1 text-sm">
                          {{ subItem.title }}
                        </h3>
                        <p class="text-xs text-gray-600 leading-snug">
                          {{ subItem.description }}
                        </p>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </li>

            <!-- Research (with dropdown) -->
            <li class="relative group">
              <button 
                class="bg-transparent hover:bg-blue-50 text-gray-700 hover:text-blue-900 px-4 py-2 rounded-md text-sm font-medium transition-colors inline-flex items-center"
              >
                Research
                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <!-- Dropdown content -->
              <div class="absolute left-0 mt-1 w-[500px] bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <div class="p-4">
                  <div class="grid grid-cols-1 gap-3">
                    <div 
                      v-for="subItem in researchItems" 
                      :key="subItem.title"
                      class="group/item rounded-lg p-3 hover:bg-gray-50 transition-colors"
                    >
                      <a :href="subItem.href" class="block">
                        <h3 class="font-medium text-gray-900 group-hover/item:text-blue-900 mb-1">
                          {{ subItem.title }}
                        </h3>
                        <p class="text-sm text-gray-600 leading-snug">
                          {{ subItem.description }}
                        </p>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </li>

            <!-- People (with dropdown) -->
            <li class="relative group">
              <button 
                class="bg-transparent hover:bg-blue-50 text-gray-700 hover:text-blue-900 px-4 py-2 rounded-md text-sm font-medium transition-colors inline-flex items-center"
              >
                People
                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <!-- Dropdown content -->
              <div class="absolute left-0 mt-1 w-[300px] bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <div class="p-4">
                  <div class="grid grid-cols-1 gap-2">
                    <a 
                      href="/people/faculty"
                      class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-900 transition-colors"
                    >
                      Faculty
                    </a>
                    <a 
                      href="/people/staff"
                      class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-900 transition-colors"
                    >
                      Staff
                    </a>
                    <a 
                      href="/people/students"
                      class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-900 transition-colors"
                    >
                      Current Students
                    </a>
                    <a 
                      href="/people/alumni"
                      class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-900 transition-colors"
                    >
                      Alumni
                    </a>
                  </div>
                </div>
              </div>
            </li>

            <!-- Notice -->
            <li>
              <a 
                href="/notices" 
                class="bg-transparent hover:bg-blue-50 text-gray-700 hover:text-blue-900 px-4 py-2 rounded-md text-sm font-medium transition-colors inline-flex items-center"
              >
                Notice
              </a>
            </li>

            <!-- News -->
            <li>
              <a 
                href="/news" 
                class="bg-transparent hover:bg-blue-50 text-gray-700 hover:text-blue-900 px-4 py-2 rounded-md text-sm font-medium transition-colors inline-flex items-center"
              >
                News
              </a>
            </li>

            <!-- Publications -->
            <li>
              <a 
                href="/publications" 
                class="bg-transparent hover:bg-blue-50 text-gray-700 hover:text-blue-900 px-4 py-2 rounded-md text-sm font-medium transition-colors inline-flex items-center"
              >
                Publications
              </a>
            </li>

            <!-- Projects -->
            <li>
              <a 
                href="/projects" 
                class="bg-transparent hover:bg-blue-50 text-gray-700 hover:text-blue-900 px-4 py-2 rounded-md text-sm font-medium transition-colors inline-flex items-center"
              >
                Projects
              </a>
            </li>

            <!-- Contact -->
            <li>
              <a 
                href="/contact" 
                class="bg-transparent hover:bg-blue-50 text-gray-700 hover:text-blue-900 px-4 py-2 rounded-md text-sm font-medium transition-colors inline-flex items-center"
              >
                Contact
              </a>
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
                class="p-2 hover:bg-blue-50"
              >
                <Menu class="h-6 w-6 text-gray-700" />
                <span class="sr-only">Open main menu</span>
              </Button>
            </SheetTrigger>

            <SheetContent side="left" class="w-full sm:w-80 bg-white p-0 h-full flex flex-col">
              <!-- Mobile Header -->
              <div class="bg-blue-900 text-white p-4 flex-shrink-0 relative">
                <SheetHeader class="text-left">
                  <SheetTitle class="flex items-center justify-between text-white text-lg">
                    <div class="flex items-center">
                      <GraduationCap class="h-7 w-7 mr-3" />
                      <div>
                        <div class="font-bold">CSE, MBSTU</div>
                        <div class="text-xs text-blue-100 font-normal">Computer Science and Engineering</div>
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
                      class="flex items-center px-4 py-3 text-blue-900 font-semibold text-sm uppercase tracking-wide mobile-section-title">
                      <svg class="w-5 h-5 mr-3 mobile-nav-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                      </svg>
                      Home
                    </a>
                  </div>
                  <!-- About Section -->
                  <div class="mb-4 mobile-nav-section">
                    <div class="flex items-center px-4 py-3 text-blue-900 font-semibold text-sm uppercase tracking-wide mobile-section-title">
                      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      About
                    </div>
                    <div class="ml-4 space-y-1">
                      <a
                        v-for="item in aboutItems"
                        :key="item.title"
                        :href="item.href"
                        @click="isOpen = false"
                        class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-900 hover:bg-gray-50 rounded-md transition-colors mobile-nav-item"
                      >
                        {{ item.title }}
                      </a>
                    </div>
                  </div>

                  <!-- Academic Section -->
                  <div class="mb-4 mobile-nav-section">
                    <div class="flex items-center px-4 py-3 text-blue-900 font-semibold text-sm uppercase tracking-wide mobile-section-title">
                      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                      </svg>
                      Academic
                    </div>
                    <div class="ml-4 space-y-1">
                      <a
                        v-for="item in academicItems"
                        :key="item.title"
                        :href="item.href"
                        @click="isOpen = false"
                        class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-900 hover:bg-gray-50 rounded-md transition-colors mobile-nav-item"
                      >
                        {{ item.title }}
                      </a>
                    </div>
                  </div>

                  <!-- Research Section -->
                  <div class="mb-4 mobile-nav-section">
                    <div class="flex items-center px-4 py-3 text-blue-900 font-semibold text-sm uppercase tracking-wide mobile-section-title">
                      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                      </svg>
                      Research
                    </div>
                    <div class="ml-4 space-y-1">
                      <a
                        v-for="item in researchItems"
                        :key="item.title"
                        :href="item.href"
                        @click="isOpen = false"
                        class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-900 hover:bg-gray-50 rounded-md transition-colors mobile-nav-item"
                      >
                        {{ item.title }}
                      </a>
                    </div>
                  </div>

                  <!-- People Section -->
                  <div class="mb-4 mobile-nav-section">
                    <div class="flex items-center px-4 py-3 text-blue-900 font-semibold text-sm uppercase tracking-wide mobile-section-title">
                      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                      </svg>
                      People
                    </div>
                    <div class="ml-4 space-y-1">
                      <a
                        href="/people/faculty"
                        @click="isOpen = false"
                        class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-900 hover:bg-gray-50 rounded-md transition-colors mobile-nav-item"
                      >
                        Faculty
                      </a>
                      <a
                        href="/people/staff"
                        @click="isOpen = false"
                        class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-900 hover:bg-gray-50 rounded-md transition-colors mobile-nav-item"
                      >
                        Staff
                      </a>
                      <a
                        href="/people/students"
                        @click="isOpen = false"
                        class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-900 hover:bg-gray-50 rounded-md transition-colors mobile-nav-item"
                      >
                        Current Students
                      </a>
                      <a
                        href="/people/alumni"
                        @click="isOpen = false"
                        class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-900 hover:bg-gray-50 rounded-md transition-colors mobile-nav-item"
                      >
                        Alumni
                      </a>
                    </div>
                  </div>

                    <div class="mb-4 mobile-nav-section">
                      <a 
                        href="/notices"
                        @click="isOpen = false"
                        class="flex items-center px-4 py-3 text-blue-900 font-semibold text-sm uppercase tracking-wide mobile-section-title">
                        <svg class="w-5 h-5 mr-3 mobile-nav-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM9 2v5H4L9 2zm0 0l5 5M4 12h16m-7 7h7" />
                        </svg>
                        Notice
                      </a>
                    </div>
                    <div class="mb-4 mobile-nav-section">
                      <a 
                        href="/news"
                        @click="isOpen = false"
                        class="flex items-center px-4 py-3 text-blue-900 font-semibold text-sm uppercase tracking-wide mobile-section-title">
                        <svg class="w-5 h-5 mr-3 mobile-nav-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        News
                      </a>
                    </div>
                    <div class="mb-4 mobile-nav-section">
                      <a 
                        href="/publications"
                        @click="isOpen = false"
                        class="flex items-center px-4 py-3 text-blue-900 font-semibold text-sm uppercase tracking-wide mobile-section-title">
                        <svg class="w-5 h-5 mr-3 mobile-nav-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Publications
                      </a>
                    </div>
                    <div class="mb-4 mobile-nav-section">
                      <a 
                        href="/projects"
                        @click="isOpen = false"
                        class="flex items-center px-4 py-3 text-blue-900 font-semibold text-sm uppercase tracking-wide mobile-section-title">
                        <svg class="w-5 h-5 mr-3 mobile-nav-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Projects
                      </a>
                    </div>
                    <div class="mb-4 mobile-nav-section">
                      <a 
                        href="/contact"
                        @click="isOpen = false"
                        class="flex items-center px-4 py-3 text-blue-900 font-semibold text-sm uppercase tracking-wide mobile-section-title">
                        <svg class="w-5 h-5 mr-3 mobile-nav-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Contact
                      </a>
                    </div>
                </div>
              </div>

              <!-- Mobile Footer -->
              <div class="border-t border-gray-200 p-4 bg-gray-50 flex-shrink-0 mobile-footer">
                <div class="text-center">
                  <div class="text-xs text-gray-500 mb-2">
                    © 2025 CSE, MBSTU. All rights reserved.
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