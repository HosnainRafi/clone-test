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
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <DynamicNavbar :menuItems="props.menuItems" />

        <!-- Breadcrumb -->
        <div class="border-b bg-white/80 backdrop-blur-sm">
            <div class="container mx-auto px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <Link href="/" class="hover:text-blue-600">Home</Link>
                    <span>/</span>
                    <Link href="/teachers" class="hover:text-blue-600">Faculty</Link>
                </div>
            </div>
        </div>

        <main class="py-16">
            <!-- Header Section -->
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-16 text-center">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"
                            />
                        </svg>
                        Academic Excellence
                    </div>
                    <h1 class="mb-4 bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-5xl font-extrabold text-transparent md:text-6xl">
                        Our Faculty Members
                    </h1>
                    <p class="mx-auto max-w-3xl text-xl text-gray-600">
                        Meet our distinguished team of educators and researchers who are committed to excellence in teaching and advancing knowledge
                        in their fields
                    </p>
                    <div class="mt-6 flex items-center justify-center gap-3">
                        <div class="flex items-center gap-2 rounded-lg bg-white px-4 py-2 shadow-sm">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                />
                            </svg>
                            <span class="font-semibold text-gray-700">{{ props.teachers.data.length }} Faculty Members</span>
                        </div>
                        <div class="flex items-center gap-2 rounded-lg bg-white px-4 py-2 shadow-sm">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            <span class="font-semibold text-gray-700">{{ departments.length }} Departments</span>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Section -->
                <div class="mb-12">
                    <div class="mx-auto max-w-5xl rounded-2xl bg-white p-8 shadow-xl ring-1 ring-gray-900/5">
                        <div class="flex flex-col gap-5 md:flex-row">
                            <!-- Search Box -->
                            <div class="relative flex-1">
                                <label class="mb-2 block text-sm font-semibold text-gray-700">Search Teachers</label>
                                <div class="relative">
                                    <svg
                                        class="absolute top-1/2 left-4 h-5 w-5 -translate-y-1/2 text-gray-400"
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
                                        class="w-full rounded-xl border-2 border-gray-200 py-3.5 pr-4 pl-12 text-gray-700 transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none"
                                    />
                                </div>
                            </div>

                            <!-- Department Filter -->
                            <div class="relative md:w-72">
                                <label class="mb-2 block text-sm font-semibold text-gray-700">Filter by Department</label>
                                <div class="relative">
                                    <select
                                        v-model="selectedDepartment"
                                        class="w-full appearance-none rounded-xl border-2 border-gray-200 bg-white py-3.5 pr-12 pl-4 text-gray-700 transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none"
                                    >
                                        <option value="all">All Departments</option>
                                        <option v-for="dept in departments" :key="dept" :value="dept">{{ dept }}</option>
                                    </select>
                                    <svg
                                        class="pointer-events-none absolute top-1/2 right-4 h-5 w-5 -translate-y-1/2 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Results Count -->
                        <div v-if="searchQuery || selectedDepartment !== 'all'" class="mt-6 rounded-lg bg-blue-50 px-4 py-3 text-center">
                            <p class="text-sm font-medium text-blue-800">
                                <span class="font-bold">{{ filteredTeachers.length }}</span> of
                                <span class="font-bold">{{ props.teachers.data.length }}</span> teachers match your criteria
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Teachers Grid -->
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="teacher in filteredTeachers"
                        :key="teacher.id"
                        class="group relative overflow-hidden rounded-3xl bg-white shadow-lg transition-all duration-500 hover:-translate-y-3 hover:shadow-2xl"
                    >
                        <!-- Enhanced Profile Image Section -->
                        <div class="relative bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 px-8 pt-12 pb-16">
                            <!-- Decorative Background Elements -->
                            <div class="absolute inset-0 overflow-hidden">
                                <!-- Animated Circles -->
                                <div
                                    class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-gradient-to-br from-blue-200/40 to-indigo-200/40 blur-2xl transition-all duration-700 group-hover:scale-150"
                                ></div>
                                <div
                                    class="absolute -bottom-10 -left-10 h-40 w-40 rounded-full bg-gradient-to-tr from-purple-200/40 to-pink-200/40 blur-2xl transition-all duration-700 group-hover:scale-150"
                                ></div>
                                <!-- Grid Pattern -->
                                <div
                                    class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAwIDEwIEwgNDAgMTAgTSAxMCAwIEwgMTAgNDAgTSAwIDIwIEwgNDAgMjAgTSAyMCAwIEwgMjAgNDAgTSAwIDMwIEwgNDAgMzAgTSAzMCAwIEwgMzAgNDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSg5OSwgMTAyLCAyNDEsIDAuMDUpIiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=')] opacity-50"
                                ></div>
                            </div>

                            <!-- Profile Image Container with Multiple Rings -->
                            <div class="relative mx-auto w-fit">
                                <!-- Outer Glow Ring (Animated) -->
                                <div
                                    class="absolute inset-0 -m-3 rounded-full bg-gradient-to-r from-blue-400 via-indigo-400 to-purple-400 opacity-0 blur-xl transition-all duration-500 group-hover:animate-pulse group-hover:opacity-60"
                                ></div>

                                <!-- Rotating Border Ring -->
                                <div
                                    class="animate-spin-slow absolute inset-0 -m-2 rounded-full bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 p-1 opacity-80"
                                    style="animation-duration: 8s"
                                >
                                    <div class="h-full w-full rounded-full bg-white"></div>
                                </div>

                                <!-- Secondary Ring with Gradient -->
                                <div class="relative rounded-full bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 p-1.5 shadow-2xl">
                                    <!-- Inner White Ring -->
                                    <div class="rounded-full bg-white p-2">
                                        <!-- Profile Image -->
                                        <div class="relative h-40 w-40 overflow-hidden rounded-full shadow-xl">
                                            <img
                                                v-if="teacher.profile_image"
                                                :src="teacher.profile_image"
                                                :alt="teacher.name"
                                                class="h-full w-full object-cover transition-all duration-700 group-hover:scale-110 group-hover:rotate-3"
                                            />
                                            <div
                                                v-else
                                                class="flex h-full w-full items-center justify-center bg-gradient-to-br from-blue-400 to-indigo-500 text-5xl font-bold text-white transition-transform duration-700 group-hover:scale-110"
                                            >
                                                {{ getInitials(teacher.name) }}
                                            </div>

                                            <!-- Hover Overlay -->
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-blue-600/80 via-transparent to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                                            ></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Online Status Indicator -->
                                <div class="absolute right-2 bottom-2 h-6 w-6 rounded-full border-4 border-white bg-green-500 shadow-lg">
                                    <div class="absolute inset-0 animate-ping rounded-full bg-green-400 opacity-75"></div>
                                </div>

                                <!-- Verification Badge -->
                                <div
                                    class="absolute top-0 -right-1 flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg ring-4 ring-white"
                                >
                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="px-6 pb-6">
                            <!-- Name & Title -->
                            <div class="mt-2 mb-4 text-center">
                                <h3 class="mb-2 text-2xl font-bold text-gray-900 transition-colors group-hover:text-blue-600">
                                    {{ teacher.name }}
                                </h3>
                                <p class="mb-1 text-sm font-semibold text-blue-600">{{ teacher.designation }}</p>
                                <p class="text-sm font-medium text-gray-500">{{ teacher.department }}</p>
                            </div>

                            <!-- Contact Information -->
                            <div class="mb-5 space-y-3 border-y border-gray-100 py-4">
                                <div v-if="teacher.email" class="group/item flex items-center gap-3">
                                    <div
                                        class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-blue-50 transition-colors group-hover/item:bg-blue-100"
                                    >
                                        <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                            class="block truncate text-sm font-medium text-gray-900 transition-colors hover:text-blue-600"
                                        >
                                            {{ teacher.email }}
                                        </a>
                                    </div>
                                </div>

                                <div v-if="teacher.phone_number" class="group/item flex items-center gap-3">
                                    <div
                                        class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-green-50 transition-colors group-hover/item:bg-green-100"
                                    >
                                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                            class="block text-sm font-medium text-gray-900 transition-colors hover:text-green-600"
                                        >
                                            {{ teacher.phone_number }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Research Interests -->
                            <div v-if="teacher.research_interests && teacher.research_interests.length > 0" class="mb-5">
                                <div class="mb-2 flex items-center gap-2">
                                    <svg class="h-4 w-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                                        />
                                    </svg>
                                    <p class="text-xs font-bold tracking-wide text-gray-600 uppercase">Research Interests</p>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="(interest, index) in teacher.research_interests.slice(0, 3)"
                                        :key="index"
                                        class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700"
                                    >
                                        {{ interest }}
                                    </span>
                                    <span
                                        v-if="teacher.research_interests.length > 3"
                                        class="rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-600"
                                    >
                                        +{{ teacher.research_interests.length - 3 }} more
                                    </span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <Link
                                :href="teacher.link"
                                class="group/btn flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl hover:shadow-blue-500/40"
                            >
                                <span>View Full Profile</span>
                                <svg
                                    class="h-5 w-5 transition-transform group-hover/btn:translate-x-1"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="filteredTeachers.length === 0" class="py-20 text-center">
                    <div class="mx-auto mb-6 flex h-32 w-32 items-center justify-center rounded-full bg-gray-100">
                        <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <h3 class="mb-3 text-2xl font-bold text-gray-900">No teachers found</h3>
                    <p class="mb-6 text-gray-600">We couldn't find any teachers matching your search criteria</p>
                    <button
                        @click="
                            searchQuery = '';
                            selectedDepartment = 'all';
                        "
                        class="rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white shadow-lg transition-all hover:bg-blue-700 hover:shadow-xl"
                    >
                        Clear Filters
                    </button>
                </div>

                <!-- Pagination -->
                <nav v-if="props.teachers.links && props.teachers.links.length > 3" class="mt-16">
                    <ul class="flex flex-wrap items-center justify-center gap-3">
                        <li v-for="(link, index) in props.teachers.links" :key="index">
                            <a
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    'inline-flex min-w-[44px] items-center justify-center rounded-xl px-5 py-3 text-sm font-bold transition-all duration-300',
                                    link.active
                                        ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/30'
                                        : 'bg-white text-gray-700 shadow-md hover:bg-gray-50 hover:shadow-lg',
                                ]"
                            ></a>
                            <span
                                v-else
                                v-html="link.label"
                                class="inline-flex min-w-[44px] items-center justify-center rounded-xl bg-gray-100 px-5 py-3 text-sm font-bold text-gray-400"
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

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #3b82f6, #6366f1);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #2563eb, #4f46e5);
}

/* Slow spin animation for rotating ring */
@keyframes spin-slow {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin-slow {
    animation: spin-slow 8s linear infinite;
}

/* Animation for cards entrance */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.group {
    animation: fadeInUp 0.6s ease-out backwards;
}

.group:nth-child(1) {
    animation-delay: 0.1s;
}
.group:nth-child(2) {
    animation-delay: 0.2s;
}
.group:nth-child(3) {
    animation-delay: 0.3s;
}
.group:nth-child(4) {
    animation-delay: 0.4s;
}
.group:nth-child(5) {
    animation-delay: 0.5s;
}
.group:nth-child(6) {
    animation-delay: 0.6s;
}

/* Smooth focus ring */
input:focus,
select:focus {
    outline: none;
}
</style>
