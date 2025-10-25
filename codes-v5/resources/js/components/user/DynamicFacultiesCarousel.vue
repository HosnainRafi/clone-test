<script setup lang="ts">
import { Badge } from '@/components/user/ui/badge';
import { Button } from '@/components/user/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/user/ui/card';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/user/ui/carousel';
import { computed } from 'vue';

import {
    Award,
    BookOpen,
    BookText,
    Briefcase,
    Building,
    Calculator,
    ExternalLink,
    Globe,
    GraduationCap,
    Heart,
    Microscope,
    Star,
    Stethoscope,
    Users,
} from 'lucide-vue-next';

// ✅ Define interface for dynamic functionality
interface FacultyItem {
    id?: number;
    name: string;
    shortName: string;
    description: string;
    image: string;
    fallbackGradient: string;
    link: string;
    departments: string[];
    stats: {
        departments: number;
        students: string;
        faculty: string;
        programs: number;
    };
    highlights: string[];
    featured?: boolean;
    established?: string;
    ranking?: string;
    dean?: {
        name: string;
        title: string;
        email?: string;
    };
    researchAreas: string[];
    achievements: string[];
    iconName: string;
    isActive?: boolean;
    displayOrder?: number;
}

// ✅ Accept props for dynamic faculties
const props = defineProps<{
    facultyItems?: FacultyItem[];
}>();

// ✅ Icon mapping for dynamic icons
const iconMap = {
    Calculator,
    Microscope,
    Globe,
    Briefcase,
    Users,
    BookText,
    Heart,
    Stethoscope,
    Building,
    GraduationCap,
    BookOpen,
    Award,
} as any;

// ✅ Keep your original static faculty data as default fallback
const defaultFaculties: FacultyItem[] = [
    {
        id: 1,
        name: 'Faculty of Engineering',
        shortName: 'Engineering',
        description:
            'Leading innovation in technology and engineering with cutting-edge research, industry partnerships, and world-class laboratories preparing students for the digital future.',
        image: '/images/faculties/engineering.jpg',
        fallbackGradient: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%)',
        link: '/faculty-of-engineering',
        departments: [
            'Computer Science and Engineering (CSE)',
            'Information and Communication Technology (ICT)',
            'Electrical and Electronic Engineering (EEE)',
            'Civil Engineering (CE)',
        ],
        stats: {
            departments: 4,
            students: '1,250+',
            faculty: '48+',
            programs: 12,
        },
        highlights: ['State-of-the-art laboratories', 'Industry collaboration', 'Research excellence', 'International partnerships'],
        researchAreas: ['Artificial Intelligence & Machine Learning', 'Robotics & Automation', 'Renewable Energy Systems', 'Smart Infrastructure'],
        achievements: [
            '15+ International Research Publications (2024)',
            'IEEE Student Branch Chapter',
            'Industry Collaboration with 20+ Companies',
            '90% Graduate Employment Rate',
        ],
        featured: true,
        established: '2002',
        ranking: 'Top Rated',
        dean: {
            name: 'Prof. Dr. Mohammad Motiur Rahman',
            title: 'Dean, Faculty of Engineering',
            email: 'dean.engineering@mbstu.ac.bd',
        },
        iconName: 'Calculator',
        isActive: true,
        displayOrder: 1,
    },
    // ... rest of your static faculties data (same as before)
];

// ✅ Use provided faculties or fallback to static data - DYNAMIC FUNCTIONALITY
const faculties = computed(() => {
    if (props.facultyItems && props.facultyItems.length > 0) {
        return props.facultyItems
            .filter((item) => item.isActive !== false)
            .sort((a, b) => (a.displayOrder || 0) - (b.displayOrder || 0))
            .map((item, index) => ({
                id: item.id || index + 1,
                name: item.name || 'Unknown Faculty',
                shortName: item.shortName || item.name || 'Faculty',
                description: item.description || 'No description available',
                image: item.image || '/images/faculties/default.jpg',
                fallbackGradient: item.fallbackGradient || 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)',
                link: item.link || '#',
                departments: item.departments || [],
                stats: {
                    departments: item.stats?.departments || 0,
                    students: item.stats?.students || '0',
                    faculty: item.stats?.faculty || '0',
                    programs: item.stats?.programs || 0,
                },
                highlights: item.highlights || [],
                researchAreas: item.researchAreas || [],
                achievements: item.achievements || [],
                featured: item.featured || false,
                established: item.established || '',
                ranking: item.ranking || '',
                dean: item.dean || null,
                iconName: item.iconName || 'Building',
                isActive: item.isActive !== false,
                displayOrder: item.displayOrder || index + 1,
            }));
    }
    return defaultFaculties.filter((item) => item.isActive !== false);
});

// Get component for icon
const getIconComponent = (iconName: string) => {
    return iconMap[iconName] || Building;
};

const getRankingColor = (ranking: string): string => {
    const colors: Record<string, string> = {
        'Top Rated': 'bg-emerald-100 text-emerald-800 border-emerald-300',
        Excellence: 'bg-blue-100 text-blue-800 border-blue-300',
        Premier: 'bg-purple-100 text-purple-800 border-purple-300',
        Growing: 'bg-amber-100 text-amber-800 border-amber-300',
        Emerging: 'bg-orange-100 text-orange-800 border-orange-300',
        'Cultural Hub': 'bg-cyan-100 text-cyan-800 border-cyan-300',
        Specialized: 'bg-pink-100 text-pink-800 border-pink-300',
    };
    return colors[ranking] || 'bg-gray-100 text-[hsl(var(--secondary))] border-gray-300';
};

// ✅ Debug logging for dynamic functionality
console.log('FacultiesCarousel - Props facultyItems:', props.facultyItems);
console.log('FacultiesCarousel - Computed faculties:', faculties.value);
</script>

<template>
    <!-- ✅ KEEPING EXACT ORIGINAL DESIGN AND STYLING -->
    <section id="faculties-carousel" class="container pt-24">
        <!-- Section Header - ORIGINAL DESIGN -->
        <div class="mb-8 text-center">
            <h2 class="mb-4 text-center text-3xl font-bold text-[hsl(var(--secondary))] md:text-4xl">Our Academic Faculties</h2>
            <p class="mx-auto max-w-2xl text-center text-muted-foreground">
                Discover the {{ faculties.length }} diverse faculties that make MBSTU a comprehensive center for academic excellence and research
                innovation across multiple disciplines.
            </p>
        </div>

        <!-- ✅ No Faculties State -->
        <div v-if="faculties.length === 0" class="py-12 text-center">
            <div class="mx-auto max-w-md">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700">
                    <Building class="h-8 w-8 text-gray-400" />
                </div>
                <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">No Faculties Available</h3>
                <p class="text-gray-500 dark:text-gray-400">Faculty information will be displayed here when available.</p>
            </div>
        </div>

        <!-- Faculties Carousel - ✅ FIXED: Consistent Card Heights -->
        <Carousel
            v-else
            :opts="{
                align: 'start',
                loop: true,
            }"
            class="relative mx-auto w-full max-w-7xl"
        >
            <CarouselContent class="-ml-2 md:-ml-4">
                <CarouselItem v-for="faculty in faculties" :key="faculty.id" class="pl-2 md:basis-1/2 md:pl-4 lg:basis-1/3">
                    <!-- ✅ FIXED: Cards with consistent heights using flexbox -->
                    <Card
                        class="group faculty-card flex h-full transform flex-col overflow-hidden border-2 border-gray-100 bg-white transition-all duration-500 hover:-translate-y-2 hover:border-blue-200 hover:shadow-2xl"
                    >
                        <!-- ✅ FIXED: Header section with fixed height -->
                        <div class="relative flex-shrink-0 overflow-hidden">
                            <div
                                class="relative flex h-32 items-center justify-center bg-gradient-to-br from-primary/10 to-primary/5"
                                :style="`background: ${faculty.fallbackGradient}`"
                            >
                                <div class="absolute inset-0 bg-black/20"></div>

                                <!-- Featured Badge - DYNAMIC DATA -->
                                <div v-if="faculty.featured" class="absolute top-2 left-2 z-10">
                                    <Badge class="border-0 bg-gradient-to-r from-amber-500 to-amber-600 text-xs text-white shadow-lg">
                                        <Star class="mr-1 h-2.5 w-2.5" />
                                        Featured
                                    </Badge>
                                </div>

                                <!-- Ranking Badge - DYNAMIC DATA -->
                                <div v-if="faculty.ranking" class="absolute top-2 right-2 z-10">
                                    <Badge :class="getRankingColor(faculty.ranking)" class="border text-xs font-bold shadow-lg">
                                        {{ faculty.ranking }}
                                    </Badge>
                                </div>

                                <!-- Established Badge - DYNAMIC DATA -->
                                <div v-if="faculty.established" class="absolute bottom-2 left-2 z-10">
                                    <Badge class="border-white/30 bg-white/20 text-xs text-white shadow-sm backdrop-blur-sm hover:bg-white/30">
                                        <Building class="mr-1 h-2.5 w-2.5" />
                                        Est. {{ faculty.established }}
                                    </Badge>
                                </div>

                                <!-- Programs Badge - DYNAMIC DATA -->
                                <div class="absolute right-2 bottom-2 z-10">
                                    <Badge class="border-white/30 bg-white/20 text-xs text-white shadow-sm backdrop-blur-sm hover:bg-white/30">
                                        <BookOpen class="mr-1 h-2.5 w-2.5" />
                                        {{ faculty.stats.programs }} Programs
                                    </Badge>
                                </div>

                                <!-- Dynamic Faculty Icon - DYNAMIC COMPONENT -->
                                <component :is="getIconComponent(faculty.iconName)" class="h-10 w-10 text-white/90" />
                            </div>
                        </div>

                        <!-- ✅ FIXED: Card Header with fixed height -->
                        <CardHeader class="flex-shrink-0 pb-3" style="min-height: 140px">
                            <!-- Stats Row - DYNAMIC DATA -->
                            <div class="mb-2 flex items-center justify-between text-sm text-muted-foreground">
                                <div class="flex items-center gap-1">
                                    <BookOpen class="h-3.5 w-3.5 text-blue-500" />
                                    <span class="text-xs font-medium">{{ faculty.stats.departments }} Dept.</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <Users class="h-3.5 w-3.5 text-green-500" />
                                    <span class="text-xs font-medium">{{ faculty.stats.students }}</span>
                                </div>
                            </div>

                            <!-- ✅ FIXED: Title with fixed height (2 lines max) -->
                            <CardTitle
                                class="faculty-title mb-2 text-base leading-tight font-bold text-[hsl(var(--secondary))] transition-colors duration-200 hover:text-[hsl(var(--primary))]"
                            >
                                {{ faculty.name }}
                            </CardTitle>

                            <!-- ✅ FIXED: Description with fixed height (3 lines max) -->
                            <CardDescription class="faculty-description text-sm leading-relaxed text-muted-foreground">
                                {{ faculty.description }}
                            </CardDescription>
                        </CardHeader>

                        <!-- ✅ FIXED: Content section that grows to fill remaining space -->
                        <CardContent class="flex flex-grow flex-col space-y-3 pt-0 pb-4">
                            <!-- ✅ FIXED: Dean section with consistent height -->
                            <div class="dean-section flex-shrink-0" style="min-height: 60px">
                                <div v-if="faculty.dean" class="flex h-full flex-col justify-center rounded-lg bg-gray-50 p-3">
                                    <div class="mb-1 text-xs font-medium text-[hsl(var(--secondary))]">Dean</div>
                                    <div class="text-sm leading-tight font-semibold text-gray-900">{{ faculty.dean.name }}</div>
                                    <div class="text-xs leading-tight text-muted-foreground">{{ faculty.dean.title }}</div>
                                </div>
                                <div v-else class="flex h-full items-center justify-center rounded-lg bg-gray-50">
                                    <span class="text-xs text-gray-400">Dean information not available</span>
                                </div>
                            </div>

                            <!-- ✅ FIXED: Departments section with consistent height -->
                            <div class="departments-section flex-shrink-0" style="min-height: 50px">
                                <div v-if="faculty.departments.length > 0" class="flex h-full flex-col justify-center">
                                    <div class="mb-1 flex items-center gap-1 text-sm font-medium text-[hsl(var(--secondary))]">
                                        <GraduationCap class="h-3.5 w-3.5" />
                                        Key Departments:
                                    </div>
                                    <div class="text-xs leading-tight text-muted-foreground">
                                        {{ faculty.departments.slice(0, 2).join(', ') }}
                                        <span v-if="faculty.departments.length > 2" class="font-medium text-[hsl(var(--primary))]">
                                            + {{ faculty.departments.length - 2 }} more
                                        </span>
                                    </div>
                                </div>
                                <div v-else class="flex h-full items-center justify-center">
                                    <span class="text-xs text-gray-400">Departments information not available</span>
                                </div>
                            </div>

                            <!-- ✅ FIXED: Action buttons always at bottom -->
                            <div class="mt-auto flex-shrink-0 pt-2">
                                <div class="flex gap-2">
                                    <Button
                                        size="sm"
                                        class="flex-1 bg-[hsl(var(--secondary))] text-xs text-white transition-all duration-200 hover:bg-[hsl(var(--primary))]"
                                        variant="default"
                                        :as="faculty.link !== '#' ? 'a' : 'button'"
                                        :href="faculty.link !== '#' ? faculty.link : undefined"
                                    >
                                        <component :is="getIconComponent(faculty.iconName)" class="mr-1 h-3 w-3" />
                                        Explore Faculty
                                    </Button>
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        class="border-gray-300 px-3 hover:border-[hsl(var(--primary))] hover:text-[hsl(var(--primary))]"
                                        :as="faculty.link !== '#' ? 'a' : 'button'"
                                        :href="faculty.link !== '#' ? faculty.link : undefined"
                                    >
                                        <ExternalLink class="h-3 w-3" />
                                    </Button>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </CarouselItem>
            </CarouselContent>
            <!-- Navigation - ORIGINAL DESIGN -->
            <CarouselPrevious class="-left-12 hidden sm:flex lg:-left-16" />
            <CarouselNext class="-right-12 hidden sm:flex lg:-right-16" />
        </Carousel>

        <!-- ✅ Debug Info for dynamic functionality (enable for debugging) -->
        <div v-if="false" class="mt-8 rounded bg-gray-100 p-4 text-xs dark:bg-gray-800">
            <p><strong>Faculties count:</strong> {{ faculties.length }}</p>
            <p><strong>Has props:</strong> {{ !!props.facultyItems }}</p>
            <p><strong>Props length:</strong> {{ props.facultyItems?.length || 0 }}</p>
            <p><strong>Using:</strong> {{ (props.facultyItems?.length || 0) > 0 ? 'Dynamic Data' : 'Static Faculties' }}</p>
        </div>
    </section>
</template>

<style scoped>
/* ✅ FIXED: Consistent card heights - MOST IMPORTANT PART */
.faculty-card {
    min-height: 400px; /* Ensure all cards have minimum height */
    max-height: 450px; /* Prevent cards from getting too tall */
    display: flex;
    flex-direction: column;
}

/* ✅ FIXED: Title with consistent height (2 lines max) */
.faculty-title {
    height: 2.5rem; /* Fixed height for 2 lines */
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.25;
}

/* ✅ FIXED: Description with consistent height (3 lines max) */
.faculty-description {
    height: 3.75rem; /* Fixed height for 3 lines */
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.25;
}

/* ✅ FIXED: Ensure content sections have consistent heights */
.dean-section {
    height: 60px;
    display: flex;
    align-items: stretch;
}

.departments-section {
    height: 50px;
    display: flex;
    align-items: stretch;
}

/* ✅ FIXED: Make CardContent grow to fill remaining space */
.faculty-card .space-y-3 {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

/* ✅ FIXED: Push buttons to bottom */
.mt-auto {
    margin-top: auto;
}

/* ✅ ORIGINAL STYLES KEPT */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ✅ Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
    .transition-all,
    .transition-colors,
    .transform {
        transition: none;
    }

    .hover\:-translate-y-2:hover {
        transform: none;
    }
}

/* ✅ High contrast support */
@media (prefers-contrast: high) {
    .border-gray-100 {
        border-color: #666;
    }

    .text-muted-foreground {
        color: #333;
    }
}

/* ✅ Ensure dynamic icons render properly */
.h-10.w-10,
.h-12.w-12 {
    flex-shrink: 0;
}

/* ✅ Responsive adjustments for consistent heights */
@media (max-width: 768px) {
    .faculty-card {
        min-height: 380px;
        max-height: 420px;
    }

    .faculty-title {
        height: 2.25rem;
        font-size: 0.9rem;
    }

    .faculty-description {
        height: 3.5rem;
        font-size: 0.825rem;
    }

    .dean-section {
        height: 55px;
    }

    .departments-section {
        height: 45px;
    }
}

@media (max-width: 640px) {
    .faculty-card {
        min-height: 360px;
        max-height: 400px;
    }

    .faculty-title {
        height: 2rem;
        font-size: 0.875rem;
    }

    .faculty-description {
        height: 3.25rem;
        font-size: 0.8rem;
    }
}

/* ✅ Ensure proper text truncation */
.text-xs.leading-tight {
    line-height: 1.2;
}

.text-sm.leading-tight {
    line-height: 1.3;
}

/* ✅ Card hover effects preserved */
.group:hover .h-10.w-10,
.group:hover .h-12.w-12 {
    transform: scale(1.1);
    transition: transform 0.3s ease;
}

/* ✅ Ensure badges don't overlap with content */
.absolute.top-2,
.absolute.bottom-2 {
    z-index: 20;
}

/* ✅ Smooth content transitions */
.faculty-card .space-y-3 > * {
    transition: all 0.3s ease;
}
</style>
