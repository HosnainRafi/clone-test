<script setup lang="ts">
import HeaderArea from '@/components/Header/HeaderArea.vue';
import { useAdminRoutes } from '@/composables/useAdminRoutes';
import { Link, usePage } from '@inertiajs/vue3';
import { Award, BookMarked, BookOpen, Briefcase, FileText, FolderOpen, GraduationCap, Lightbulb, Link2, Menu, User, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const { adminRoute, siteType } = useAdminRoutes();

interface Props {
    teacher?: {
        id: number;
        name: string;
    };
}

const props = defineProps<Props>();
const page = usePage();

// Generate menu items with dynamic routes based on site type
const menuItems = computed(() => {
    // For faculty (teacher login), use simplified routes: /admin/faculty/profile/*
    // For university/department (admin editing teacher), use: /admin/{type}/teacher/profile/*
    const profilePrefix = siteType.value === 'faculty' ? 'profile' : 'teacher/profile';

    // For university/department, append teacher_id query parameter
    const teacherId = props.teacher?.id;
    const queryParam = siteType.value !== 'faculty' && teacherId ? `?teacher_id=${teacherId}` : '';

    return [
        {
            name: 'Basic Info',
            path: `${adminRoute(`${profilePrefix}/basic-info`)}${queryParam}`,
            icon: User,
        },
        {
            name: 'About Me',
            path: `${adminRoute(`${profilePrefix}/about`)}${queryParam}`,
            icon: FileText,
        },
        {
            name: 'Research Interests',
            path: `${adminRoute(`${profilePrefix}/research-interests`)}${queryParam}`,
            icon: Lightbulb,
        },
        {
            name: 'Education',
            path: `${adminRoute(`${profilePrefix}/education`)}${queryParam}`,
            icon: GraduationCap,
        },
        {
            name: 'Experience',
            path: `${adminRoute(`${profilePrefix}/experience`)}${queryParam}`,
            icon: Briefcase,
        },
        {
            name: 'Publications',
            path: `${adminRoute(`${profilePrefix}/publications`)}${queryParam}`,
            icon: BookOpen,
        },
        {
            name: 'Projects',
            path: `${adminRoute(`${profilePrefix}/projects`)}${queryParam}`,
            icon: FolderOpen,
        },
        {
            name: 'Courses Taught',
            path: `${adminRoute(`${profilePrefix}/courses`)}${queryParam}`,
            icon: BookMarked,
        },
        {
            name: 'Awards & Honors',
            path: `${adminRoute(`${profilePrefix}/awards`)}${queryParam}`,
            icon: Award,
        },
        {
            name: 'Social & Academic Links',
            path: `${adminRoute(`${profilePrefix}/social-links`)}${queryParam}`,
            icon: Link2,
        },
    ];
});

const teachersListRoute = computed(() => adminRoute('teachers'));

const currentRoute = computed(() => page.url);
const sidebarOpen = ref(false);

const isActive = (path: string) => {
    return currentRoute.value.includes(path);
};

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};
</script>

<template>
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside
            :class="[
                'fixed top-0 left-0 z-50 h-screen w-72 flex-shrink-0 transform bg-white shadow-lg transition-transform duration-300 lg:static lg:translate-x-0 dark:bg-boxdark',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full',
            ]"
        >
            <!-- Sidebar Header -->
            <div class="border-b border-stroke px-6 py-5 dark:border-strokedark">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-black dark:text-white">Teacher Profile</h2>
                    <button class="lg:hidden" @click="toggleSidebar">
                        <X class="h-6 w-6 text-black dark:text-white" />
                    </button>
                </div>
                <p v-if="props.teacher" class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ props.teacher.name }}</p>
                <a
                    :href="teachersListRoute"
                    class="dark:hover:bg-opacity-80 mt-3 inline-flex items-center gap-2 rounded bg-gray-200 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:bg-gray-300 dark:bg-meta-4 dark:text-gray-300"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Teachers List
                </a>
            </div>

            <!-- Navigation Menu -->
            <nav class="overflow-y-auto px-4 py-4" style="max-height: calc(100vh - 100px)">
                <ul class="space-y-1">
                    <li v-for="item in menuItems" :key="item.path">
                        <Link
                            :href="item.path"
                            :class="[
                                'group flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200',
                                isActive(item.path)
                                    ? 'bg-primary text-white shadow-md'
                                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-meta-4',
                            ]"
                        >
                            <component
                                :is="item.icon"
                                :class="['h-5 w-5', isActive(item.path) ? 'text-white' : 'text-gray-500 group-hover:text-primary']"
                            />
                            <span>{{ item.name }}</span>
                        </Link>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Mobile Overlay -->
        <div v-if="sidebarOpen" class="bg-opacity-50 fixed inset-0 z-40 bg-black lg:hidden" @click="toggleSidebar"></div>

        <!-- Main Content Area -->
        <div class="relative flex flex-1 flex-col overflow-x-hidden overflow-y-auto">
            <!-- Header -->
            <HeaderArea />

            <!-- Mobile Menu Button -->
            <div class="border-b border-stroke bg-white px-4 py-3 lg:hidden dark:border-strokedark dark:bg-boxdark">
                <button @click="toggleSidebar" class="flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white">
                    <Menu class="h-5 w-5" />
                    <span>Menu</span>
                </button>
            </div>

            <!-- Page Content -->
            <main>
                <div class="mx-auto max-w-screen-2xl bg-whiten p-4 md:p-6 2xl:p-10">
                    <slot></slot>
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Custom scrollbar for sidebar */
nav::-webkit-scrollbar {
    width: 6px;
}

nav::-webkit-scrollbar-track {
    background: transparent;
}

nav::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

nav::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

.dark nav::-webkit-scrollbar-thumb {
    background: #4b5563;
}

.dark nav::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}
</style>
