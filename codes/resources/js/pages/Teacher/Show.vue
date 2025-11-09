<script setup lang="ts">
import DynamicFooter from '@/components/user/DynamicFooter.vue';
import DynamicNavbar from '@/components/user/DynamicNavbar.vue';
import { Link } from '@inertiajs/vue3';
import { Award, BookOpen, Briefcase, FileText, Globe, GraduationCap, Linkedin, Mail, MapPin, MessageSquare, Phone, User } from 'lucide-vue-next';
import { computed, ref } from 'vue';

// --- INTERFACES ---
interface Education {
    degree: string;
    field: string;
    university: string;
    year: string | number;
}

interface Experience {
    role: string;
    institution: string;
    period: string;
}

interface Publication {
    title: string;
    authors: string;
    journal?: string;
    conference?: string;
    year: number;
    link: string;
    category?: string;
}

interface Project {
    title: string;
    role: string;
    funding_source: string;
    status: string;
}

interface Course {
    code: string;
    title: string;
}

interface TeacherDetail {
    id: number;
    slug: string;
    name: string;
    designation?: string;
    department?: string;
    profile_image?: string;
    email?: string;
    phone_number?: string;
    office_location?: string;
    website_url?: string;
    about_me?: string;
    research_interests?: string[];
    education?: Education[];
    experience?: Experience[];
    publications?: Publication[];
    projects?: Project[];
    courses_taught?: Course[];
    awards?: any[];
    social_links?: {
        linkedin?: string;
        google_scholar?: string;
        researchgate?: string;
    };
}

interface PageProps {
    teacher: TeacherDetail;
    menuItems: any[];
    footerData: any;
}

const props = defineProps<PageProps>();

// Active tab state
const activeTab = ref<string>('about');

const tabs = [
    { id: 'about', label: 'About Me', icon: User },
    { id: 'publications', label: 'Publications', icon: BookOpen },
    { id: 'projects', label: 'Projects', icon: Briefcase },
    { id: 'courses', label: 'Teaching', icon: GraduationCap },
    { id: 'contact', label: 'Contact', icon: MessageSquare },
];

const switchTab = (tabId: string) => {
    activeTab.value = tabId;
};

// Publication filters
const selectedCategory = ref<string>('');
const selectedYear = ref<string>('');

// Get unique categories from publications
const availableCategories = computed(() => {
    if (!props.teacher.publications) return [];
    const categories = props.teacher.publications.map((pub) => pub.category).filter((cat): cat is string => !!cat);
    return [...new Set(categories)].sort();
});

// Get unique years from publications
const availableYears = computed(() => {
    if (!props.teacher.publications) return [];
    const years = props.teacher.publications.map((pub) => pub.year).filter((year): year is number => !!year);
    return [...new Set(years)].sort((a, b) => b - a);
});

// Filtered publications based on selected category and year
const filteredPublications = computed(() => {
    if (!props.teacher.publications) return [];

    return props.teacher.publications.filter((pub) => {
        const categoryMatch = !selectedCategory.value || pub.category === selectedCategory.value;
        const yearMatch = !selectedYear.value || pub.year?.toString() === selectedYear.value;
        return categoryMatch && yearMatch;
    });
});

// Reset filters
const resetFilters = () => {
    selectedCategory.value = '';
    selectedYear.value = '';
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
        <DynamicNavbar :menuItems="props.menuItems" />

        <!-- Breadcrumb -->
        <div class="border-b bg-white/80 backdrop-blur-sm">
            <div class="container mx-auto px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <Link href="/" class="hover:text-blue-600">Home</Link>
                    <span>/</span>
                    <Link href="/teachers" class="hover:text-blue-600">Faculty</Link>
                    <span>/</span>
                    <span class="font-semibold text-gray-900">Profile</span>
                </div>
            </div>
        </div>

        <main class="py-8">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Profile Header Card - Redesigned without cover -->
                <div class="mb-8 rounded-2xl bg-white p-8 shadow-xl">
                    <div class="flex flex-col items-center gap-6 md:flex-row md:items-start">
                        <!-- Profile Image -->
                        <div class="flex-shrink-0">
                            <div
                                class="relative h-40 w-40 overflow-hidden rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-500 shadow-lg ring-4 ring-blue-100"
                            >
                                <img
                                    v-if="props.teacher.profile_image"
                                    :src="props.teacher.profile_image"
                                    :alt="props.teacher.name"
                                    class="h-full w-full object-cover"
                                />
                                <div v-else class="flex h-full w-full items-center justify-center text-5xl font-bold text-white">
                                    {{ props.teacher.name.charAt(0) }}
                                </div>
                            </div>
                        </div>

                        <!-- Profile Information -->
                        <div class="flex-1 text-center md:text-left">
                            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">{{ props.teacher.name }}</h1>
                            <p class="mt-2 text-xl font-medium text-blue-600">{{ props.teacher.designation }}</p>
                            <p class="mt-1 text-lg text-gray-600">{{ props.teacher.department }}</p>

                            <!-- Quick Contact Info -->
                            <div class="mt-5 flex flex-wrap items-center justify-center gap-4 md:justify-start">
                                <div v-if="props.teacher.email" class="flex items-center gap-2 text-sm text-gray-700">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-50">
                                        <Mail class="h-4 w-4 text-blue-600" />
                                    </div>
                                    <a :href="`mailto:${props.teacher.email}`" class="hover:text-blue-600">
                                        {{ props.teacher.email }}
                                    </a>
                                </div>

                                <div v-if="props.teacher.phone_number" class="flex items-center gap-2 text-sm text-gray-700">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-green-50">
                                        <Phone class="h-4 w-4 text-green-600" />
                                    </div>
                                    <a :href="`tel:${props.teacher.phone_number}`" class="hover:text-blue-600">
                                        {{ props.teacher.phone_number }}
                                    </a>
                                </div>

                                <div v-if="props.teacher.office_location" class="flex items-center gap-2 text-sm text-gray-700">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-purple-50">
                                        <MapPin class="h-4 w-4 text-purple-600" />
                                    </div>
                                    <span>{{ props.teacher.office_location }}</span>
                                </div>
                            </div>

                            <!-- Social Links -->
                            <div class="mt-5 flex flex-wrap items-center justify-center gap-3 md:justify-start">
                                <a
                                    v-if="props.teacher.social_links?.linkedin"
                                    :href="props.teacher.social_links.linkedin"
                                    target="_blank"
                                    class="flex items-center gap-2 rounded-lg bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700 transition-all hover:bg-blue-600 hover:text-white hover:shadow-md"
                                >
                                    <Linkedin class="h-4 w-4" />
                                    <span>LinkedIn</span>
                                </a>

                                <a
                                    v-if="props.teacher.social_links?.google_scholar"
                                    :href="props.teacher.social_links.google_scholar"
                                    target="_blank"
                                    class="flex items-center gap-2 rounded-lg bg-red-50 px-4 py-2 text-sm font-medium text-red-700 transition-all hover:bg-red-600 hover:text-white hover:shadow-md"
                                >
                                    <GraduationCap class="h-4 w-4" />
                                    <span>Google Scholar</span>
                                </a>

                                <a
                                    v-if="props.teacher.social_links?.researchgate"
                                    :href="props.teacher.social_links.researchgate"
                                    target="_blank"
                                    class="flex items-center gap-2 rounded-lg bg-teal-50 px-4 py-2 text-sm font-medium text-teal-700 transition-all hover:bg-teal-600 hover:text-white hover:shadow-md"
                                >
                                    <FileText class="h-4 w-4" />
                                    <span>ResearchGate</span>
                                </a>

                                <a
                                    v-if="props.teacher.website_url"
                                    :href="props.teacher.website_url"
                                    target="_blank"
                                    class="flex items-center gap-2 rounded-lg bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-700 transition-all hover:bg-indigo-600 hover:text-white hover:shadow-md"
                                >
                                    <Globe class="h-4 w-4" />
                                    <span>Website</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Section -->
                <div class="rounded-2xl bg-white shadow-lg">
                    <!-- Tab Headers -->
                    <div class="border-b border-gray-200 px-4 sm:px-6">
                        <nav class="-mb-px flex space-x-4 overflow-x-auto" aria-label="Tabs">
                            <button
                                v-for="tab in tabs"
                                :key="tab.id"
                                @click="switchTab(tab.id)"
                                :class="[
                                    'group inline-flex items-center gap-2 border-b-2 px-3 py-4 text-sm font-medium whitespace-nowrap transition-all',
                                    activeTab === tab.id
                                        ? 'border-blue-600 text-blue-600'
                                        : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                                ]"
                            >
                                <component
                                    :is="tab.icon"
                                    :class="['h-5 w-5', activeTab === tab.id ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600']"
                                />
                                <span>{{ tab.label }}</span>
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6 sm:p-8">
                        <!-- About Me Tab -->
                        <div v-show="activeTab === 'about'" class="space-y-8">
                            <!-- About Me Section -->
                            <div v-if="props.teacher.about_me">
                                <h2 class="mb-4 flex items-center gap-2 text-2xl font-bold text-gray-900">
                                    <User class="h-6 w-6 text-blue-600" />
                                    About Me
                                </h2>
                                <div class="prose max-w-none text-gray-700" v-html="props.teacher.about_me"></div>
                            </div>

                            <!-- Research Interests -->
                            <div v-if="props.teacher.research_interests && props.teacher.research_interests.length > 0">
                                <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-gray-900">
                                    <BookOpen class="h-5 w-5 text-indigo-600" />
                                    Research Interests
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="(interest, index) in props.teacher.research_interests"
                                        :key="index"
                                        class="rounded-full bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-700"
                                    >
                                        {{ interest }}
                                    </span>
                                </div>
                            </div>

                            <!-- Education -->
                            <div v-if="props.teacher.education && props.teacher.education.length > 0">
                                <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-gray-900">
                                    <GraduationCap class="h-5 w-5 text-green-600" />
                                    Education
                                </h3>
                                <div class="space-y-4">
                                    <div
                                        v-for="(edu, index) in props.teacher.education"
                                        :key="index"
                                        class="rounded-xl border border-gray-200 bg-gradient-to-r from-gray-50 to-white p-5"
                                    >
                                        <h4 class="text-lg font-semibold text-gray-900">{{ edu.degree }}</h4>
                                        <p class="mt-1 text-gray-700">{{ edu.field }}</p>
                                        <div class="mt-2 flex items-center gap-4 text-sm text-gray-600">
                                            <span>{{ edu.university }}</span>
                                            <span class="text-gray-400">•</span>
                                            <span class="font-medium text-blue-600">{{ edu.year }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Experience -->
                            <div v-if="props.teacher.experience && props.teacher.experience.length > 0">
                                <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-gray-900">
                                    <Briefcase class="h-5 w-5 text-purple-600" />
                                    Professional Experience
                                </h3>
                                <div class="space-y-4">
                                    <div
                                        v-for="(exp, index) in props.teacher.experience"
                                        :key="index"
                                        class="rounded-xl border border-gray-200 bg-gradient-to-r from-purple-50 to-white p-5"
                                    >
                                        <h4 class="text-lg font-semibold text-gray-900">{{ exp.role }}</h4>
                                        <p class="mt-1 text-gray-700">{{ exp.institution }}</p>
                                        <p class="mt-2 text-sm font-medium text-purple-600">{{ exp.period }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Awards -->
                            <div v-if="props.teacher.awards && props.teacher.awards.length > 0">
                                <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-gray-900">
                                    <Award class="h-5 w-5 text-yellow-600" />
                                    Awards & Honors
                                </h3>
                                <div class="space-y-3">
                                    <div
                                        v-for="(award, index) in props.teacher.awards"
                                        :key="index"
                                        class="flex items-start gap-3 rounded-xl border border-yellow-200 bg-yellow-50 p-4"
                                    >
                                        <Award class="mt-0.5 h-5 w-5 flex-shrink-0 text-yellow-600" />
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ award.title }}</h4>
                                            <p class="text-sm text-gray-600">{{ award.event }} • {{ award.year }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Publications Tab -->
                        <div v-show="activeTab === 'publications'">
                            <h2 class="mb-6 flex items-center gap-2 text-2xl font-bold text-gray-900">
                                <BookOpen class="h-6 w-6 text-blue-600" />
                                Publications
                            </h2>

                            <!-- Filters Section -->
                            <div
                                v-if="props.teacher.publications && props.teacher.publications.length > 0"
                                class="mb-6 rounded-xl border border-gray-200 bg-gray-50 p-5"
                            >
                                <div class="mb-4 flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-gray-700">Filter Publications</h3>
                                    <button
                                        v-if="selectedCategory || selectedYear"
                                        @click="resetFilters"
                                        class="text-sm font-medium text-blue-600 hover:text-blue-800"
                                    >
                                        Reset Filters
                                    </button>
                                </div>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <!-- Category Filter -->
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">Category</label>
                                        <select
                                            v-model="selectedCategory"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                        >
                                            <option value="">All Categories</option>
                                            <option v-for="cat in availableCategories" :key="cat" :value="cat">
                                                {{ cat }}
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Year Filter -->
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">Year</label>
                                        <select
                                            v-model="selectedYear"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                        >
                                            <option value="">All Years</option>
                                            <option v-for="year in availableYears" :key="year" :value="year.toString()">
                                                {{ year }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Active Filters Display -->
                                <div v-if="selectedCategory || selectedYear" class="mt-4 flex flex-wrap items-center gap-2">
                                    <span class="text-sm text-gray-600">Active filters:</span>
                                    <span
                                        v-if="selectedCategory"
                                        class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700"
                                    >
                                        {{ selectedCategory }}
                                        <button @click="selectedCategory = ''" class="hover:text-blue-900">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </span>
                                    <span
                                        v-if="selectedYear"
                                        class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700"
                                    >
                                        Year: {{ selectedYear }}
                                        <button @click="selectedYear = ''" class="hover:text-green-900">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <!-- Publications List -->
                            <div v-if="filteredPublications.length > 0" class="space-y-4">
                                <div
                                    v-for="(pub, index) in filteredPublications"
                                    :key="index"
                                    class="group rounded-xl border border-gray-200 bg-white p-6 transition-all hover:border-blue-300 hover:shadow-md"
                                >
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-600"
                                        >
                                            {{ index + 1 }}
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600">
                                                {{ pub.title }}
                                            </h3>
                                            <p class="mt-2 text-sm text-gray-600">{{ pub.authors }}</p>
                                            <div class="mt-3 flex flex-wrap items-center gap-3 text-sm">
                                                <span v-if="pub.category" class="rounded-full bg-blue-50 px-3 py-1 font-medium text-blue-700">
                                                    {{ pub.category }}
                                                </span>
                                                <span v-if="pub.journal" class="rounded-full bg-green-50 px-3 py-1 font-medium text-green-700">
                                                    {{ pub.journal }}
                                                </span>
                                                <span v-if="pub.conference" class="rounded-full bg-purple-50 px-3 py-1 font-medium text-purple-700">
                                                    {{ pub.conference }}
                                                </span>
                                                <span class="font-semibold text-gray-700">{{ pub.year }}</span>
                                            </div>
                                            <a
                                                v-if="pub.link"
                                                :href="pub.link"
                                                target="_blank"
                                                class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800"
                                            >
                                                View Publication
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                                    />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- No Results Message -->
                            <div v-else-if="props.teacher.publications && props.teacher.publications.length > 0" class="py-16 text-center">
                                <BookOpen class="mx-auto mb-4 h-16 w-16 text-gray-300" />
                                <p class="text-lg font-medium text-gray-900">No publications found</p>
                                <p class="mt-2 text-gray-500">Try adjusting your filters</p>
                                <button
                                    @click="resetFilters"
                                    class="mt-4 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700"
                                >
                                    Clear Filters
                                </button>
                            </div>

                            <!-- No Publications at All -->
                            <div v-else class="py-16 text-center">
                                <BookOpen class="mx-auto mb-4 h-16 w-16 text-gray-300" />
                                <p class="text-lg text-gray-500">No publications available</p>
                            </div>
                        </div>

                        <!-- Projects Tab -->
                        <div v-show="activeTab === 'projects'">
                            <h2 class="mb-6 flex items-center gap-2 text-2xl font-bold text-gray-900">
                                <Briefcase class="h-6 w-6 text-blue-600" />
                                Research Projects
                            </h2>

                            <div v-if="props.teacher.projects && props.teacher.projects.length > 0" class="space-y-4">
                                <div
                                    v-for="(project, index) in props.teacher.projects"
                                    :key="index"
                                    class="rounded-xl border border-gray-200 bg-gradient-to-r from-blue-50 to-white p-6"
                                >
                                    <div class="flex items-start justify-between">
                                        <h3 class="text-xl font-semibold text-gray-900">{{ project.title }}</h3>
                                        <span
                                            :class="[
                                                'rounded-full px-3 py-1 text-xs font-medium',
                                                project.status.toLowerCase() === 'completed'
                                                    ? 'bg-green-100 text-green-700'
                                                    : 'bg-yellow-100 text-yellow-700',
                                            ]"
                                        >
                                            {{ project.status }}
                                        </span>
                                    </div>
                                    <div class="mt-4 space-y-2 text-sm">
                                        <p class="flex items-center gap-2 text-gray-700">
                                            <span class="font-semibold">Role:</span>
                                            {{ project.role }}
                                        </p>
                                        <p class="flex items-center gap-2 text-gray-700">
                                            <span class="font-semibold">Funding:</span>
                                            {{ project.funding_source }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="py-16 text-center">
                                <Briefcase class="mx-auto mb-4 h-16 w-16 text-gray-300" />
                                <p class="text-lg text-gray-500">No projects available</p>
                            </div>
                        </div>

                        <!-- Teaching/Courses Tab -->
                        <div v-show="activeTab === 'courses'">
                            <h2 class="mb-6 flex items-center gap-2 text-2xl font-bold text-gray-900">
                                <GraduationCap class="h-6 w-6 text-blue-600" />
                                Courses Taught
                            </h2>

                            <div v-if="props.teacher.courses_taught && props.teacher.courses_taught.length > 0" class="grid gap-4 sm:grid-cols-2">
                                <div
                                    v-for="(course, index) in props.teacher.courses_taught"
                                    :key="index"
                                    class="group rounded-xl border border-gray-200 bg-white p-5 transition-all hover:border-blue-300 hover:shadow-md"
                                >
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 text-sm font-bold text-white"
                                        >
                                            {{ course.code.split('-')[1] }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-blue-600">{{ course.code }}</p>
                                            <h4 class="mt-1 font-semibold text-gray-900 group-hover:text-blue-600">
                                                {{ course.title }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="py-16 text-center">
                                <GraduationCap class="mx-auto mb-4 h-16 w-16 text-gray-300" />
                                <p class="text-lg text-gray-500">No courses available</p>
                            </div>
                        </div>

                        <!-- Contact Tab -->
                        <div v-show="activeTab === 'contact'">
                            <h2 class="mb-6 flex items-center gap-2 text-2xl font-bold text-gray-900">
                                <MessageSquare class="h-6 w-6 text-blue-600" />
                                Contact Information
                            </h2>

                            <div class="grid gap-6 md:grid-cols-2">
                                <!-- Contact Card -->
                                <div class="space-y-4 rounded-xl border border-gray-200 bg-gradient-to-br from-blue-50 to-white p-6">
                                    <h3 class="text-lg font-semibold text-gray-900">Get in Touch</h3>

                                    <div v-if="props.teacher.email" class="flex items-start gap-3">
                                        <div class="mt-1 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-100">
                                            <Mail class="h-5 w-5 text-blue-600" />
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Email</p>
                                            <a :href="`mailto:${props.teacher.email}`" class="text-gray-900 hover:text-blue-600">
                                                {{ props.teacher.email }}
                                            </a>
                                        </div>
                                    </div>

                                    <div v-if="props.teacher.phone_number" class="flex items-start gap-3">
                                        <div class="mt-1 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-green-100">
                                            <Phone class="h-5 w-5 text-green-600" />
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Phone</p>
                                            <a :href="`tel:${props.teacher.phone_number}`" class="text-gray-900 hover:text-blue-600">
                                                {{ props.teacher.phone_number }}
                                            </a>
                                        </div>
                                    </div>

                                    <div v-if="props.teacher.office_location" class="flex items-start gap-3">
                                        <div class="mt-1 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-purple-100">
                                            <MapPin class="h-5 w-5 text-purple-600" />
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Office Location</p>
                                            <p class="text-gray-900">{{ props.teacher.office_location }}</p>
                                        </div>
                                    </div>

                                    <div v-if="props.teacher.website_url" class="flex items-start gap-3">
                                        <div class="mt-1 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100">
                                            <Globe class="h-5 w-5 text-indigo-600" />
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Website</p>
                                            <a :href="props.teacher.website_url" target="_blank" class="break-all text-gray-900 hover:text-blue-600">
                                                {{ props.teacher.website_url }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Social Links Card -->
                                <div class="space-y-4 rounded-xl border border-gray-200 bg-gradient-to-br from-indigo-50 to-white p-6">
                                    <h3 class="text-lg font-semibold text-gray-900">Academic Profiles</h3>

                                    <div v-if="props.teacher.social_links?.linkedin" class="group">
                                        <a
                                            :href="props.teacher.social_links.linkedin"
                                            target="_blank"
                                            class="flex items-center gap-3 rounded-lg bg-white p-4 shadow-sm transition-all hover:shadow-md"
                                        >
                                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-600">
                                                <Linkedin class="h-6 w-6 text-white" />
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900 group-hover:text-blue-600">LinkedIn</p>
                                                <p class="text-sm text-gray-500">Professional Network</p>
                                            </div>
                                            <svg
                                                class="h-5 w-5 text-gray-400 transition-transform group-hover:translate-x-1"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>

                                    <div v-if="props.teacher.social_links?.google_scholar" class="group">
                                        <a
                                            :href="props.teacher.social_links.google_scholar"
                                            target="_blank"
                                            class="flex items-center gap-3 rounded-lg bg-white p-4 shadow-sm transition-all hover:shadow-md"
                                        >
                                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-600">
                                                <GraduationCap class="h-6 w-6 text-white" />
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900 group-hover:text-red-600">Google Scholar</p>
                                                <p class="text-sm text-gray-500">Research Publications</p>
                                            </div>
                                            <svg
                                                class="h-5 w-5 text-gray-400 transition-transform group-hover:translate-x-1"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>

                                    <div v-if="props.teacher.social_links?.researchgate" class="group">
                                        <a
                                            :href="props.teacher.social_links.researchgate"
                                            target="_blank"
                                            class="flex items-center gap-3 rounded-lg bg-white p-4 shadow-sm transition-all hover:shadow-md"
                                        >
                                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-teal-600">
                                                <FileText class="h-6 w-6 text-white" />
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900 group-hover:text-teal-600">ResearchGate</p>
                                                <p class="text-sm text-gray-500">Research Network</p>
                                            </div>
                                            <svg
                                                class="h-5 w-5 text-gray-400 transition-transform group-hover:translate-x-1"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>

                                    <div
                                        v-if="
                                            !props.teacher.social_links?.linkedin &&
                                            !props.teacher.social_links?.google_scholar &&
                                            !props.teacher.social_links?.researchgate
                                        "
                                        class="py-8 text-center"
                                    >
                                        <p class="text-gray-500">No social profiles available</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <DynamicFooter :footerData="props.footerData" />
    </div>
</template>

<style scoped>
.container {
    max-width: 1200px;
}

/* Smooth tab transitions */
.tab-content {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Prose styles for about_me content */
.prose {
    line-height: 1.75;
}

.prose p {
    margin-bottom: 1.25em;
}

.prose h2,
.prose h3 {
    margin-top: 1.5em;
    margin-bottom: 0.75em;
    font-weight: 600;
}

.prose a {
    color: #2563eb;
    text-decoration: underline;
}

.prose ul,
.prose ol {
    margin-top: 1em;
    margin-bottom: 1em;
    padding-left: 1.5em;
}

.prose li {
    margin-top: 0.5em;
    margin-bottom: 0.5em;
}

/* Custom scrollbar for horizontal tab scroll on mobile */
nav::-webkit-scrollbar {
    height: 4px;
}

nav::-webkit-scrollbar-track {
    background: #f3f4f6;
}

nav::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 2px;
}

nav::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}
</style>
