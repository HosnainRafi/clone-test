<script setup lang="ts">
import DynamicFooter from '@/components/user/DynamicFooter.vue';
import DynamicNavbar from '@/components/user/DynamicNavbar.vue';
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface TeacherItem {
    id: number;
    slug: string;
    name: string;
    designation?: string;
    department?: string;
    profile_image?: string;
    email?: string;
    phone_number?: string;
    research_interests?: string[];
    link: string;
}

interface PaginatedTeachers {
    data: TeacherItem[];
    links: { url: string | null; label: string; active: boolean }[];
}

interface Props {
    teachers: PaginatedTeachers;
    menuItems: any[];
    siteData: any;
}

const props = defineProps<Props>();
const searchQuery = ref('');
const selectedDepartment = ref('all');

const departments = computed(() => {
    const depts = new Set<string>();
    props.teachers.data.forEach((teacher) => {
        if (teacher.department) depts.add(teacher.department);
    });
    return Array.from(depts).sort();
});

const filteredTeachers = computed(() => {
    return props.teachers.data.filter((teacher) => {
        const matchesSearch =
            searchQuery.value === '' ||
            teacher.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            teacher.designation?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            teacher.department?.toLowerCase().includes(searchQuery.value.toLowerCase());

        const matchesDepartment = selectedDepartment.value === 'all' || teacher.department === selectedDepartment.value;

        return matchesSearch && matchesDepartment;
    });
});

const getInitials = (name: string) => {
    return name
        .split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <DynamicNavbar :menuItems="props.menuItems" />

        <!-- Breadcrumb -->
        <div class="border-b bg-white">
            <div class="container mx-auto px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <Link href="/" class="hover:text-blue-700 hover:underline">Home</Link>
                    <span>/</span>
                    <span class="font-medium text-gray-900">Faculty</span>
                </div>
            </div>
        </div>

        <main class="py-12">
            <!-- Header Section -->
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-12">
                    <h1 class="mb-3 text-4xl font-bold text-gray-900">Faculty & Staff Directory</h1>
                    <p class="text-lg text-gray-600">Browse our distinguished faculty members and their areas of expertise</p>
                    <div class="mt-6 flex flex-wrap items-center gap-6 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded bg-blue-50">
                                <svg class="h-4 w-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                            </div>
                            <span
                                ><strong class="font-semibold text-gray-900">{{ props.teachers.data.length }}</strong> Faculty Members</span
                            >
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded bg-blue-50">
                                <svg class="h-4 w-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                    />
                                </svg>
                            </div>
                            <span
                                ><strong class="font-semibold text-gray-900">{{ departments.length }}</strong> Departments</span
                            >
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Section -->
                <div class="mb-10">
                    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="grid gap-4 md:grid-cols-2">
                            <!-- Search Box -->
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Search Faculty</label>
                                <div class="relative">
                                    <svg
                                        class="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                        />
                                    </svg>
                                    <input
                                        v-model="searchQuery"
                                        type="text"
                                        placeholder="Search by name, designation, or department..."
                                        class="w-full rounded-md border border-gray-300 py-2.5 pr-4 pl-10 text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none"
                                    />
                                </div>
                            </div>

                            <!-- Department Filter -->
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Department</label>
                                <select
                                    v-model="selectedDepartment"
                                    class="w-full appearance-none rounded-md border border-gray-300 bg-white py-2.5 pr-10 pl-3 text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none"
                                >
                                    <option value="all">All Departments</option>
                                    <option v-for="dept in departments" :key="dept" :value="dept">{{ dept }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Results Count -->
                        <div v-if="searchQuery || selectedDepartment !== 'all'" class="mt-4 border-t border-gray-200 pt-4">
                            <p class="text-sm text-gray-600">
                                Showing <strong class="font-semibold text-gray-900">{{ filteredTeachers.length }}</strong> of
                                <strong class="font-semibold text-gray-900">{{ props.teachers.data.length }}</strong> faculty members
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Teachers Grid -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="teacher in filteredTeachers"
                        :key="teacher.id"
                        class="group overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition-shadow hover:shadow-md"
                    >
                        <!-- Profile Section -->
                        <div class="border-b border-gray-100 bg-gradient-to-br from-gray-50 to-white p-6">
                            <div class="flex flex-col items-center">
                                <!-- Profile Image -->
                                <div class="relative mb-4">
                                    <div class="h-32 w-32 overflow-hidden rounded-full border-4 border-white shadow-md">
                                        <img
                                            v-if="teacher.profile_image"
                                            :src="teacher.profile_image"
                                            :alt="teacher.name"
                                            class="h-full w-full object-cover"
                                        />
                                        <div
                                            v-else
                                            class="flex h-full w-full items-center justify-center bg-gradient-to-br from-blue-600 to-blue-700 text-3xl font-bold text-white"
                                        >
                                            {{ getInitials(teacher.name) }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Name & Title -->
                                <h3 class="mb-1 text-center text-xl font-bold text-gray-900">{{ teacher.name }}</h3>
                                <p class="mb-1 text-center text-sm font-medium text-blue-700">{{ teacher.designation }}</p>
                                <p class="text-center text-sm text-gray-600">{{ teacher.department }}</p>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="p-6">
                            <div class="mb-5 space-y-3">
                                <div v-if="teacher.email" class="flex items-start gap-3">
                                    <div class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded bg-blue-50">
                                        <svg class="h-4 w-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                            />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-gray-500">Email</p>
                                        <a
                                            :href="`mailto:${teacher.email}`"
                                            class="block truncate text-sm text-gray-900 hover:text-blue-700 hover:underline"
                                        >
                                            {{ teacher.email }}
                                        </a>
                                    </div>
                                </div>

                                <div v-if="teacher.phone_number" class="flex items-start gap-3">
                                    <div class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded bg-blue-50">
                                        <svg class="h-4 w-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                            />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-gray-500">Phone</p>
                                        <a
                                            :href="`tel:${teacher.phone_number}`"
                                            class="block text-sm text-gray-900 hover:text-blue-700 hover:underline"
                                        >
                                            {{ teacher.phone_number }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Research Interests -->
                            <div v-if="teacher.research_interests && teacher.research_interests.length > 0" class="mb-5">
                                <p class="mb-2 text-xs font-semibold tracking-wide text-gray-500 uppercase">Research Interests</p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="(interest, index) in teacher.research_interests.slice(0, 3)"
                                        :key="index"
                                        class="inline-block rounded bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700"
                                    >
                                        {{ interest }}
                                    </span>
                                    <span
                                        v-if="teacher.research_interests.length > 3"
                                        class="inline-block rounded bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600"
                                    >
                                        +{{ teacher.research_interests.length - 3 }}
                                    </span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <Link
                                :href="teacher.link"
                                class="flex w-full items-center justify-center gap-2 rounded-md border border-blue-700 bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-blue-800"
                            >
                                <span>View Profile</span>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="filteredTeachers.length === 0" class="rounded-lg border border-gray-200 bg-white py-16 text-center">
                    <svg class="mx-auto mb-4 h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                        />
                    </svg>
                    <h3 class="mb-2 text-lg font-semibold text-gray-900">No faculty members found</h3>
                    <p class="mb-6 text-gray-600">Try adjusting your search or filter criteria</p>
                    <button
                        @click="
                            searchQuery = '';
                            selectedDepartment = 'all';
                        "
                        class="rounded-md border border-gray-300 bg-white px-6 py-2.5 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-50"
                    >
                        Reset Filters
                    </button>
                </div>

                <!-- Pagination -->
                <nav v-if="props.teachers.links && props.teachers.links.length > 3" class="mt-12">
                    <ul class="flex flex-wrap items-center justify-center gap-2">
                        <li v-for="(link, index) in props.teachers.links" :key="index">
                            <a
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    'inline-flex min-w-[40px] items-center justify-center rounded-md border px-4 py-2 text-sm font-medium transition-colors',
                                    link.active
                                        ? 'border-blue-700 bg-blue-700 text-white'
                                        : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50',
                                ]"
                            ></a>
                            <span
                                v-else
                                v-html="link.label"
                                class="inline-flex min-w-[40px] items-center justify-center rounded-md border border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-400"
                            ></span>
                        </li>
                    </ul>
                </nav>
            </div>
        </main>

        <DynamicFooter :footer-data="props.siteData.settings?.footerData || null" />
    </div>
</template>

<style scoped>
.container {
    max-width: 1400px;
}
</style>
