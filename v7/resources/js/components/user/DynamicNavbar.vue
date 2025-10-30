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



interface MenuItemProps {
  title: string;
  col: number;
  subItems: SubMenuProps[];
}

defineProps<{
  menuItems: MenuItemProps[];
}>();



// const menuItems: any[] = [
//   {
//     title: "About",
//     col: 2,
//     subItems: [
//       {
//         title: "About Mawlana Bhashani",
//         description: "Learn about the visionary leader and founder's philosophy",
//         href: "https://mbstu.ac.bd/about-mawlana-bhashani/"
//       },
//       {
//         title: "Overview",
//         description: "Comprehensive overview of the university",
//         href: "https://mbstu.ac.bd/overview/"
//       },
//       {
//         title: "Vision & Mission",
//         description: "Our commitment to excellence in science and technology education",
//         href: "https://mbstu.ac.bd/vision-mission/"
//       },
//       {
//         title: "Achievements",
//         description: "Awards, recognitions and milestones of our university",
//         href: "https://mbstu.ac.bd/achievements/"
//       },
//       {
//         title: "University Facts & Acts",
//         description: "Legal framework and founding documents",
//         href: "https://mbstu.ac.bd/university-facts-acts/"
//       },
//       {
//         title: "Location, Maps and Direction",
//         description: "Campus location, maps and directions",
//         href: "https://mbstu.ac.bd/location-maps-and-direction/"
//       },
//       {
//         title: "Visit MBSTU",
//         description: "Information for visitors and campus tours",
//         href: "https://mbstu.ac.bd/visit-mbstu/"
//       },
//       {
//         title: "Contact Us",
//         description: "Get in touch with MBSTU",
//         href: "https://mbstu.ac.bd/contact/"
//       }
//     ]
//   },
//   {
//     title: "Academics",
//     col: 3,
//     subItems: [
//       {
//         title: "Academic Calendar",
//         description: "Academic calendar, schedules and important dates",
//         href: "https://mbstu.ac.bd/academic-calender/"
//       },
//       {
//         title: "Undergraduate Programs",
//         description: "Bachelor's degree programs across all faculties",
//         href: "https://mbstu.ac.bd/academics-detail/"
//       },
//       {
//         title: "Cyber Center",
//         description: "Computer and technology facilities",
//         href: "https://mbstu.ac.bd/cyber-center/"
//       },
//       {
//         title: "Faculty of Engineering",
//         description: "Engineering disciplines and programs",
//         href: "https://mbstu.ac.bd/faculty-of-engineering/"
//       },
//       {
//         title: "Faculty of Life Science",
//         description: "Life sciences and biological studies",
//         href: "https://mbstu.ac.bd/faculty-of-life-science/"
//       },
//       {
//         title: "Faculty of Science",
//         description: "Pure and applied sciences",
//         href: "https://mbstu.ac.bd/faculty-of-science/"
//       },
//       {
//         title: "Faculty of Business Studies",
//         description: "Business administration and management",
//         href: "https://mbstu.ac.bd/faculty-of-business-studies/"
//       },
//       {
//         title: "Faculty of Social Science",
//         description: "Social sciences and humanities",
//         href: "https://mbstu.ac.bd/faculty-of-social-science/"
//       },
//       {
//         title: "Faculty of Arts",
//         description: "Arts, literature and cultural studies",
//         href: "https://mbstu.ac.bd/faculty-of-arts/"
//       },
//       {
//         title: "Faculty of Veterinary Medicine and Animal Sciences",
//         description: "Veterinary medicine and animal sciences",
//         href: "https://mbstu.ac.bd/faculty-of-veterinary-medicine-and-animal-sciences/"
//       },
//       {
//         title: "Students Life",
//         description: "Campus activities, clubs and student organizations",
//         href: "https://mbstu.ac.bd/campus-life/"
//       },
//       {
//         title: "Clubs",
//         description: "Student clubs and extracurricular activities",
//         href: "https://mbstu.ac.bd/bhashani/clubs/"
//       }
//     ]
//   },
//   {
//     title: "Faculties",
//     col: 2,
//     subItems: [
//       {
//         title: "Faculty of Engineering",
//         description: "Engineering disciplines and programs",
//         href: "https://mbstu.ac.bd/faculty-of-engineering/"
//       },
//       {
//         title: "Faculty of Life Science",
//         description: "Life sciences and biological studies",
//         href: "https://mbstu.ac.bd/faculty-of-life-science/"
//       },
//       {
//         title: "Faculty of Science",
//         description: "Pure and applied sciences",
//         href: "https://mbstu.ac.bd/faculty-of-science/"
//       },
//       {
//         title: "Faculty of Business Studies",
//         description: "Business administration and management",
//         href: "https://mbstu.ac.bd/faculty-of-business-studies/"
//       },
//       {
//         title: "Faculty of Social Science",
//         description: "Social sciences and humanities",
//         href: "https://mbstu.ac.bd/faculty-of-social-science/"
//       },
//       {
//         title: "Faculty of Arts",
//         description: "Arts, literature and cultural studies",
//         href: "https://mbstu.ac.bd/faculty-of-arts/"
//       },
//       {
//         title: "Faculty of Veterinary Medicine",
//         description: "Veterinary medicine and animal sciences",
//         href: "https://mbstu.ac.bd/faculty-of-veterinary-medicine-and-animal-sciences/"
//       }
//     ]
//   },
//   {
//     title: "Administration and Offices",
//     col: 4,
//     subItems: [
//       {
//         title: "Vice Chancellor",
//         description: "Office of the Vice Chancellor",
//         href: "https://mbstu.ac.bd/vice-chancellor/"
//       },
//       {
//         title: "Pro Vice Chancellor",
//         description: "Office of the Pro Vice Chancellor",
//         href: "https://mbstu.ac.bd/pro-vice-chancellor/"
//       },
//       {
//         title: "Treasurer",
//         description: "Office of the Treasurer",
//         href: "https://mbstu.ac.bd/treasurer/"
//       },
//       {
//         title: "The Regent Board",
//         description: "University governing body",
//         href: "https://mbstu.ac.bd/the-regent-board/"
//       },
//       {
//         title: "Registrar Office",
//         description: "Academic records and administration",
//         href: "https://mbstu.ac.bd/registrar-office/"
//       },
//       {
//         title: "Academic Council",
//         description: "Academic governance and policies",
//         href: "https://mbstu.ac.bd/academic-council/"
//       },
//       {
//         title: "Dean of Faculties",
//         description: "Faculty leadership and administration",
//         href: "https://mbstu.ac.bd/deans/"
//       },
//       {
//         title: "Chairman of The Departments",
//         description: "Departmental heads and leadership",
//         href: "https://mbstu.ac.bd/chairman-of-the-departments/"
//       },
//       {
//         title: "Research Centre",
//         description: "Research coordination and development",
//         href: "https://mbstu.ac.bd/research-centre/"
//       },
//       {
//         title: "Research Cell",
//         description: "Research planning and support",
//         href: "https://mbstu.ac.bd/research-cell/"
//       },
//       {
//         title: "IQAC",
//         description: "Internal Quality Assurance Cell",
//         href: "https://mbstu.ac.bd/iqac/"
//       },
//       {
//         title: "Central Library",
//         description: "Library resources and services",
//         href: "https://mbstu.ac.bd/central-library/"
//       },
//       {
//         title: "Medical Center",
//         description: "Healthcare services for students and staff",
//         href: "https://mbstu.ac.bd/medical-center/"
//       }
//     ]
//   },
//   {
//     title: "Research",
//     col: 1,
//     subItems: [
//       {
//         title: "Research Facilities",
//         description: "State-of-the-art research infrastructure",
//         href: "https://mbstu.ac.bd/research-facilities/"
//       },
//       {
//         title: "Research Centers",
//         description: "Specialized research centers and institutes",
//         href: "https://mbstu.ac.bd/research-centers/"
//       },
//       {
//         title: "Research Labs",
//         description: "Laboratory facilities and equipment",
//         href: "https://mbstu.ac.bd/research-labs/"
//       },
//       {
//         title: "Research Cell",
//         description: "Research coordination and support",
//         href: "https://mbstu.ac.bd/research-cell/"
//       },
//       {
//         title: "Journal",
//         description: "MBSTU Journal and publications",
//         href: "https://journal.mbstu.ac.bd/"
//       }
//     ]
//   },
//   {
//     title: "Campus Life",
//     col: 2,
//     subItems: [
//       {
//         title: "Student Life",
//         description: "Campus activities, clubs and student organizations",
//         href: "https://mbstu.ac.bd/campus-life/"
//       },
//       {
//         title: "Residential Halls",
//         description: "On-campus housing and accommodation",
//         href: "https://mbstu.ac.bd/residential-halls/"
//       },
//       {
//         title: "Central Library",
//         description: "Library resources and digital collections",
//         href: "https://mbstu.ac.bd/central-library/"
//       },
//       {
//         title: "Medical Center",
//         description: "Healthcare services for students and staff",
//         href: "https://mbstu.ac.bd/medical-center/"
//       },
//       {
//         title: "Clubs & Organizations",
//         description: "Student clubs and extracurricular activities",
//         href: "https://mbstu.ac.bd/bhashani/clubs/"
//       },
//       {
//         title: "Student Welfare",
//         description: "Student support and counseling services",
//         href: "https://mbstu.ac.bd/students-welfare-and-counseling/"
//       }
//     ]
//   },
//   {
//     title: "Admission",
//     col: 1,
//     subItems: [
//       {
//         title: "Undergraduate",
//         description: "Bachelor's degree admission information and requirements",
//         href: "https://mbstu.ac.bd/undergraduate-admission/"
//       },
//       {
//         title: "Graduate",
//         description: "Master's and PhD admission information and requirements",
//         href: "https://mbstu.ac.bd/graduate-admission/"
//       },
//       {
//         title: "Others",
//         description: "Other admission programs and requirements",
//         href: "https://mbstu.ac.bd/other-admission/"
//       },
//       {
//         title: "International Students",
//         description: "Admission information for international students",
//         href: "https://mbstu.ac.bd/international-students/"
//       }
//     ]
//   },
//   {
//     title: "Notices",
//     col: 1,
//     subItems: [
//       {
//         title: "Notices",
//         description: "Official notices and announcements",
//         href: "https://mbstu.ac.bd/category/notices/"
//       },
//       {
//         title: "News",
//         description: "Latest news and updates from MBSTU",
//         href: "https://mbstu.ac.bd/category/news/"
//       },
//       {
//         title: "Events",
//         description: "Upcoming events and activities",
//         href: "https://mbstu.ac.bd/category/events/"
//       },
//       {
//         title: "Tender",
//         description: "Tender notices and procurement information",
//         href: "https://mbstu.ac.bd/category/tender/"
//       }
//     ]
//   }
// ]

const isOpen = ref<boolean>(false);
const isDepartmentPage = ref<boolean>(false);
</script>

<template>
  <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
    <!-- Top Bar with University Info -->
    <div v-if="isDepartmentPage"
      class="block bg-[hsl(var(--secondary))] text-[hsl(var(--secondary-foreground))] py-2 text-sm">
      <div class="container mx-auto flex justify-between items-center">
        <div class="hidden md:flex items-center space-x-6">
          <span class="flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
              <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
            </svg>
            cse@mbstu.ac.bd
          </span>
          <span class="flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                clip-rule="evenodd" />
            </svg>
            Santosh, Tangail, Bangladesh
          </span>
        </div>
        <div class="block">
          <span class="text-xs">MBSTU</span>
        </div>
      </div>
    </div>
    <div v-else class="block bg-[hsl(var(--secondary))] text-[hsl(var(--secondary-foreground))] py-2 text-sm">
      <div class="container mx-auto flex justify-between items-center">
        <div class="flex items-center flex-wrap space-x-1 2xsm:space-x-0 text-xs">
          <a href="https://mbstu.ac.bd/form-download/"
            class="hover:text-[hsl(var(--primary))] transition-colors px-2 py-1 rounded border-r border-gray-300 last:border-r-0">
            Forms & Services
          </a>
          <a href="https://mbstu.ac.bd/international-students/"
            class="hover:text-[hsl(var(--primary))] transition-colors px-2 py-1 rounded border-r border-gray-300 last:border-r-0">
            Int'l Students
          </a>
          <a href="https://mbstu.ac.bd/noc/"
            class="hover:text-[hsl(var(--primary))] transition-colors px-2 py-1 rounded border-r border-gray-300 last:border-r-0">
            NOC
          </a>
          <a href="https://mbstu.ac.bd/noc/"
            class="hover:text-[hsl(var(--primary))] transition-colors px-2 py-1 rounded border-r border-gray-300 last:border-r-0">
            IP Phone
          </a>
          <a href="https://mbstu.ac.bd/noc/"
            class="hover:text-[hsl(var(--primary))] transition-colors px-2 py-1 rounded border-r border-gray-300 last:border-r-0">
            UCAM
          </a>
          <a href="http://ucam.mbstu.ac.bd/Security/LogIn.aspx" target="_blank"
            class="hover:text-[hsl(var(--primary))] transition-colors px-2 py-1 rounded border-r border-gray-300 last:border-r-0">
            Contact
          </a>
          <a href="http://ucam.mbstu.ac.bd/Security/LogIn.aspx" target="_blank"
            class="hover:text-[hsl(var(--primary))] transition-colors px-2 py-1 rounded border-r border-gray-300 last:border-r-0">
            Departments
          </a>
        </div>
        <div class="flex items-center space-x-1 text-xs">
          <a href="https://mbstu.ac.bd/account/"
            class="hover:text-[hsl(var(--primary))] transition-colors px-2 py-1 rounded border-r border-gray-300 last:border-r-0">
            LOGIN
          </a>
        </div>
      </div>
    </div>
    <!-- Main Navigation Bar -->
    <div class="w-full">
      <div class="container flex items-center justify-between h-20">
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
              <a href="/"
                class="bg-transparent hover:bg-[hsl(var(--tertiary-hover))] hover:bg-opacity-60 text-gray-700 hover:text-[hsl(var(--primary))]  px-4 py-2 rounded-md text-sm font-medium transition-colors inline-flex items-center">
                Home
              </a>
            </li>

            <!-- Dynamic Menu Items -->
            <li v-for="menuItem in menuItems" :key="menuItem.title" class="relative group">
              <button
                class="bg-transparent hover:bg-[hsl(var(--tertiary-hover))] hover:bg-opacity-60 text-gray-700 hover:text-[hsl(var(--primary))]  px-4 py-2 rounded-md text-sm font-medium transition-colors inline-flex items-center">
                {{ menuItem.title }}
                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <!-- Dropdown content -->
              <div
                :class="`absolute -left-24 mt-1 ${menuItem.title === 'Administration and Offices' ? 'w-[900px]' : 'w-[600px]'} bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50`">
                <div class="p-4">
                  <div :class="`grid grid-cols-${menuItem.col} gap-3`">
                    <div v-for="subItem in menuItem.subItems" :key="subItem.title"
                      class="group/item rounded-lg p-3 hover:bg-[hsl(var(--tertiary-hover))] transition-colors">
                      <a :href="subItem.href" class="block">
                        <h3
                          class="font-medium text-[hsl(var(--secondary))] group-hover/item:text-[hsl(var(--primary))]  mb-1 text-sm">
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
          </ul>
        </nav>

        <!-- Mobile menu button -->
        <div class="lg:hidden">
          <Sheet v-model:open="isOpen">
            <SheetTrigger as-child>
              <Button variant="ghost" size="sm" @click="isOpen = true"
                class="p-2 hover:bg-[hsl(var(--tertiary-hover))] hover:bg-opacity-60">
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
                      <img src="/images/university/logo/MBSTU_logo.png" alt="MBSTU Logo"
                        class="h-12 w-12 object-contain" />
                    </div>
                    <!-- Close Button -->
                    <button @click="isOpen = false"
                      class="flex items-center justify-center w-8 h-8 rounded-md bg-white/10 hover:bg-white/20 transition-colors border border-white/20 hover:border-white/30"
                      aria-label="Close menu">
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
                    <a href="/" @click="isOpen = false"
                      class="flex items-center px-4 py-3 text-[hsl(var(--primary))]  font-semibold text-sm uppercase tracking-wide mobile-section-title">
                      <svg class="w-5 h-5 mr-3 mobile-nav-icon flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                      </svg>
                      Home
                    </a>
                  </div>

                  <!-- Dynamic Menu Sections -->
                  <div v-for="menuItem in menuItems" :key="menuItem.title" class="mb-4 mobile-nav-section">
                    <div
                      class="flex items-center px-4 py-3 text-[hsl(var(--primary))]  font-semibold text-sm uppercase tracking-wide mobile-section-title">
                      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="menuItem.title === 'About' ? 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' :
                          menuItem.title === 'Academics' ? 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253' :
                            menuItem.title === 'Faculties' ? 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4' :
                              menuItem.title === 'Administration and Offices' ? 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' :
                                menuItem.title === 'Research' ? 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z' :
                                  menuItem.title === 'Campus Life' ? 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4' :
                                    menuItem.title === 'Admission' ? 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' :
                                      'M15 17h5l-5 5v-5zM9 2v5H4L9 2zm0 0l5 5M4 12h16m-7 7h7'" />
                      </svg>
                      {{ menuItem.title }}
                    </div>
                    <div class="ml-4 space-y-1">
                      <a v-for="item in menuItem.subItems" :key="item.title" :href="item.href" @click="isOpen = false"
                        class="block px-4 py-2 text-sm text-gray-600 hover:text-[hsl(var(--primary))]  hover:bg-[hsl(var(--tertiary-hover))] rounded-md transition-colors mobile-nav-item">
                        {{ item.title }}
                      </a>
                    </div>
                  </div>

                  <!-- Contact Section -->
                  <div class="mb-4 mobile-nav-section">
                    <a href="https://mbstu.ac.bd/contact" @click="isOpen = false"
                      class="flex items-center px-4 py-3 text-[hsl(var(--primary))]  font-semibold text-sm uppercase tracking-wide mobile-section-title">
                      <svg class="w-5 h-5 mr-3 mobile-nav-icon flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                      </svg>
                      Contact
                    </a>
                  </div>
                </div>
              </div>

              <!-- Mobile Footer -->
              <div class="border-t border-gray-200 p-4 bg-[hsl(var(--tertiary-hover))] flex-shrink-0 mobile-footer">
                <div class="text-center">
                  <div class="text-xs text-gray-500 mb-2">
                    © 2025 MBSTU. All rights reserved.
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
.group:hover>div[class*="absolute"],
.group>div[class*="absolute"]:hover {
  opacity: 1 !important;
  visibility: visible !important;
}

/* Add slight delay to prevent flicker */
.group>div[class*="absolute"] {
  transition: opacity 200ms ease-in-out, visibility 200ms ease-in-out;
  transition-delay: 100ms;
}

.group:hover>div[class*="absolute"] {
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
  .space-y-1>* {
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