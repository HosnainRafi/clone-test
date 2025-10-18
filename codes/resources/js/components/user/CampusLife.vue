<script setup lang="ts">
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/user/ui/card';

import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/user/ui/carousel';

import { Badge } from '@/components/user/ui/badge';
import { Button } from '@/components/user/ui/button';

import {
    ArrowRight,
    Calendar,
    CheckCircle,
    Dumbbell,
    ExternalLink,
    Home,
    Library,
    Palette,
    Shield,
    Star,
    Stethoscope,
    Trophy,
    Users,
} from 'lucide-vue-next';

interface CampusLifeItem {
    id: number;
    title: string;
    category: string;
    description: string;
    image: string;
    fallbackGradient: string;
    icon: any;
    features: string[];
    stats?: {
        label: string;
        value: string;
    }[];
    link: string;
    featured?: boolean;
}

// Campus Life data based on MBSTU website
const campusLifeItems: CampusLifeItem[] = [
    {
        id: 1,
        title: 'Student Life',
        category: 'Campus Experience',
        description:
            'At MBSTU, student success is our priority. Our campus ensures that all students have the support and resources they need to thrive in this dynamic learning environment.',
        image: '/images/campus-life/student-life.jpg',
        fallbackGradient: 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #1e40af 100%)',
        icon: Users,
        features: ['Vibrant student community', 'Academic support services', 'Student organizations', 'Leadership opportunities'],
        stats: [
            { label: 'Active Students', value: '5000+' },
            { label: 'Student Clubs', value: '25+' },
        ],
        link: '/campus-life',
        featured: true,
    },
    {
        id: 2,
        title: 'Arts & Culture',
        category: 'Cultural Activities',
        description:
            'Experience rich cultural diversity through various artistic and cultural programs that celebrate creativity, tradition, and modern expression.',
        image: '/images/campus-life/arts-culture.jpg',
        fallbackGradient: 'linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #92400e 100%)',
        icon: Palette,
        features: ['Cultural festivals', 'Art exhibitions', 'Music performances', 'Traditional celebrations'],
        stats: [
            { label: 'Cultural Events', value: '50+/year' },
            { label: 'Art Societies', value: '8+' },
        ],
        link: '/arts-culture',
        featured: true,
    },
    {
        id: 3,
        title: 'Recreation & Wellness',
        category: 'Health & Sports',
        description:
            'Maintain physical and mental well-being through comprehensive recreational facilities, sports programs, and wellness initiatives.',
        image: '/images/campus-life/recreation-wellness.jpg',
        fallbackGradient: 'linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%)',
        icon: Dumbbell,
        features: ['Modern gymnasium', 'Sports facilities', 'Fitness programs', 'Wellness counseling'],
        stats: [
            { label: 'Sports Teams', value: '15+' },
            { label: 'Tournaments', value: '20+/year' },
        ],
        link: '/recreation-wellness',
    },
    {
        id: 4,
        title: 'Residential Halls',
        category: 'Accommodation',
        description: 'Comfortable and secure residential facilities providing a home away from home with modern amenities and supportive community.',
        image: '/images/campus-life/residential-halls.jpg',
        fallbackGradient: 'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 50%, #6d28d9 100%)',
        icon: Home,
        features: ['Modern dormitories', 'Security services', 'Common facilities', 'Study environments'],
        stats: [
            { label: 'Residential Halls', value: '6+' },
            { label: 'Housing Capacity', value: '1500+' },
        ],
        link: '/residential-halls',
    },
    {
        id: 5,
        title: 'Central Library',
        category: 'Academic Resources',
        description:
            'State-of-the-art library facility with extensive collections, digital resources, and collaborative study spaces for academic excellence.',
        image: '/images/campus-life/central-library.jpg',
        fallbackGradient: 'linear-gradient(135deg, #dc2626 0%, #b91c1c 50%, #991b1b 100%)',
        icon: Library,
        features: ['Digital collections', 'Study spaces', 'Research support', 'Online databases'],
        stats: [
            { label: 'Books', value: '50,000+' },
            { label: 'Digital Resources', value: '1000+' },
        ],
        link: '/central-library',
    },
    {
        id: 6,
        title: 'BNCC',
        category: 'Military Training',
        description:
            'Bangladesh National Cadet Corps program fostering leadership, discipline, and patriotism among students through military training.',
        image: '/images/campus-life/bncc.jpg',
        fallbackGradient: 'linear-gradient(135deg, #059669 0%, #047857 50%, #065f46 100%)',
        icon: Shield,
        features: ['Leadership training', 'Discipline development', 'Adventure activities', 'National service'],
        stats: [
            { label: 'BNCC Cadets', value: '200+' },
            { label: 'Training Programs', value: '12+/year' },
        ],
        link: '/bncc',
    },
    {
        id: 7,
        title: 'Student Clubs',
        category: 'Extracurricular',
        description: 'Join diverse student clubs and organizations to pursue interests, develop skills, and build lifelong friendships and networks.',
        image: '/images/campus-life/student-clubs.jpg',
        fallbackGradient: 'linear-gradient(135deg, #ea580c 0%, #dc2626 50%, #b91c1c 100%)',
        icon: Users,
        features: ['Academic clubs', 'Hobby groups', 'Professional societies', 'Volunteer organizations'],
        stats: [
            { label: 'Active Clubs', value: '25+' },
            { label: 'Club Members', value: '2000+' },
        ],
        link: '/clubs',
    },
    {
        id: 8,
        title: 'Medical Center',
        category: 'Healthcare',
        description:
            'Comprehensive healthcare services providing medical support, emergency care, and health awareness programs for campus community.',
        image: '/images/campus-life/medical-center.jpg',
        fallbackGradient: 'linear-gradient(135deg, #be185d 0%, #9d174d 50%, #831843 100%)',
        icon: Stethoscope,
        features: ['24/7 emergency care', 'General consultation', 'Health checkups', 'Medical awareness'],
        stats: [
            { label: 'Medical Staff', value: '8+' },
            { label: 'Daily Patients', value: '50+' },
        ],
        link: '/medical-center',
    },
];

const getCategoryColor = (category: string): string => {
    const colors: Record<string, string> = {
        'Campus Experience': 'bg-blue-100 text-blue-800 border-blue-200',
        'Cultural Activities': 'bg-amber-100 text-amber-800 border-amber-200',
        'Health & Sports': 'bg-green-100 text-green-800 border-green-200',
        Accommodation: 'bg-purple-100 text-purple-800 border-purple-200',
        'Academic Resources': 'bg-red-100 text-red-800 border-red-200',
        'Military Training': 'bg-emerald-100 text-emerald-800 border-emerald-200',
        Extracurricular: 'bg-orange-100 text-orange-800 border-orange-200',
        Healthcare: 'bg-pink-100 text-pink-800 border-pink-200',
    };
    return colors[category] || 'bg-gray-100 text-gray-800 border-gray-200';
};
</script>

<template>
    <section id="campus-life" class="container pt-24">
        <!-- Section Header -->
        <div class="mb-8 text-center">
            <h2 class="mb-4 text-center text-3xl font-bold text-[hsl(var(--secondary))] md:text-4xl">Campus Life at MBSTU</h2>
            <p class="mx-auto max-w-3xl text-center text-muted-foreground">
                Experience vibrant campus life with diverse opportunities for personal growth, cultural engagement, and academic excellence in a
                supportive and dynamic community environment.
            </p>
        </div>

        <Carousel
            :opts="{
                align: 'start',
                loop: true,
            }"
            class="relative mx-auto w-full max-w-7xl"
        >
            <CarouselContent class="-ml-2 md:-ml-4">
                <CarouselItem v-for="item in campusLifeItems" :key="item.id" class="pl-2 md:basis-1/2 md:pl-4 lg:basis-1/3">
                    <Card
                        class="group h-full transform overflow-hidden border-2 border-gray-100 bg-white transition-all duration-500 hover:-translate-y-2 hover:border-blue-200 hover:shadow-2xl"
                    >
                        <!-- Campus Life Header -->
                        <div class="relative overflow-hidden">
                            <div
                                class="relative flex h-36 items-center justify-center bg-gradient-to-br from-primary/10 to-primary/5"
                                :style="`background: ${item.fallbackGradient}`"
                            >
                                <div class="absolute inset-0 bg-black/20"></div>

                                <!-- Featured Badge -->
                                <div v-if="item.featured" class="absolute top-3 left-3 z-10">
                                    <Badge class="border-0 bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-lg">
                                        <Star class="mr-1 h-3 w-3" />
                                        Popular
                                    </Badge>
                                </div>

                                <!-- Category Badge -->
                                <div class="absolute top-3 right-3 z-10">
                                    <Badge :class="getCategoryColor(item.category)" class="border font-bold shadow-lg">
                                        {{ item.category }}
                                    </Badge>
                                </div>

                                <!-- Stats Badge -->
                                <div v-if="item.stats && item.stats.length > 0" class="absolute bottom-3 left-3 z-10">
                                    <Badge class="border-white/30 bg-white/20 text-xs text-white shadow-sm backdrop-blur-sm hover:bg-white/30">
                                        <CheckCircle class="mr-1 h-3 w-3" />
                                        {{ item.stats[0].value }} {{ item.stats[0].label }}
                                    </Badge>
                                </div>

                                <!-- Dynamic Icon -->
                                <component :is="item.icon" class="h-12 w-12 text-white/90" />
                            </div>
                        </div>

                        <CardHeader class="pb-3">
                            <!-- Title -->
                            <CardTitle
                                class="mb-2 line-clamp-2 text-lg leading-tight font-bold text-[hsl(var(--secondary))] transition-colors duration-200 hover:text-[hsl(var(--primary))]"
                            >
                                {{ item.title }}
                            </CardTitle>

                            <!-- Description -->
                            <CardDescription class="line-clamp-3 text-sm leading-relaxed text-muted-foreground">
                                {{ item.description }}
                            </CardDescription>
                        </CardHeader>

                        <CardContent class="space-y-4 pt-0">
                            <!-- Features -->
                            <div class="space-y-2">
                                <div class="flex items-center gap-1 text-sm font-medium text-[hsl(var(--secondary))]">
                                    <component :is="item.icon" class="h-4 w-4" />
                                    Key Features:
                                </div>
                                <div class="grid grid-cols-1 gap-1">
                                    <div
                                        v-for="feature in item.features.slice(0, 4)"
                                        :key="feature"
                                        class="flex items-center text-xs text-muted-foreground"
                                    >
                                        <span class="mr-2 h-1.5 w-1.5 flex-shrink-0 rounded-full bg-[hsl(var(--primary))]"></span>
                                        {{ feature }}
                                    </div>
                                </div>
                            </div>

                            <!-- Statistics -->
                            <div v-if="item.stats && item.stats.length > 1" class="grid grid-cols-2 gap-3">
                                <div v-for="stat in item.stats" :key="stat.label" class="rounded-lg bg-gray-50 p-2 text-center">
                                    <div class="text-sm font-bold text-[hsl(var(--secondary))]">{{ stat.value }}</div>
                                    <div class="text-xs text-muted-foreground">{{ stat.label }}</div>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="pt-2">
                                <Button
                                    size="sm"
                                    class="w-full bg-[hsl(var(--secondary))] text-xs text-white transition-all duration-200 hover:bg-[hsl(var(--primary))]"
                                    variant="default"
                                >
                                    <component :is="item.icon" class="mr-1 h-3 w-3" />
                                    Learn More
                                    <ArrowRight class="ml-1 h-3 w-3" />
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </CarouselItem>
            </CarouselContent>
            <CarouselPrevious class="-left-12 hidden sm:flex lg:-left-16" />
            <CarouselNext class="-right-12 hidden sm:flex lg:-right-16" />
        </Carousel>

        <!-- Quick Stats Section -->
        <div class="mt-12 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 p-8">
            <div class="mb-6 text-center">
                <h3 class="mb-2 text-2xl font-bold text-[hsl(var(--secondary))]">Campus Life at a Glance</h3>
                <p class="text-muted-foreground">Experience the vibrant MBSTU community</p>
            </div>

            <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
                <div class="text-center">
                    <div class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-full bg-blue-500 text-white">
                        <Users class="h-6 w-6" />
                    </div>
                    <div class="text-2xl font-bold text-[hsl(var(--secondary))]">5000+</div>
                    <div class="text-sm text-muted-foreground">Active Students</div>
                </div>

                <div class="text-center">
                    <div class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-full bg-green-500 text-white">
                        <Trophy class="h-6 w-6" />
                    </div>
                    <div class="text-2xl font-bold text-[hsl(var(--secondary))]">25+</div>
                    <div class="text-sm text-muted-foreground">Student Clubs</div>
                </div>

                <div class="text-center">
                    <div class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-full bg-purple-500 text-white">
                        <Home class="h-6 w-6" />
                    </div>
                    <div class="text-2xl font-bold text-[hsl(var(--secondary))]">6+</div>
                    <div class="text-sm text-muted-foreground">Residential Halls</div>
                </div>

                <div class="text-center">
                    <div class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-full bg-orange-500 text-white">
                        <Calendar class="h-6 w-6" />
                    </div>
                    <div class="text-2xl font-bold text-[hsl(var(--secondary))]">100+</div>
                    <div class="text-sm text-muted-foreground">Annual Events</div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="mt-12 text-center">
            <Button
                size="lg"
                class="transform bg-[hsl(var(--secondary))] px-8 py-4 text-lg font-semibold text-white shadow-lg transition-all duration-300 hover:scale-105 hover:bg-[hsl(var(--primary))] hover:shadow-xl"
                variant="default"
            >
                Explore Campus Life
                <ExternalLink class="ml-2 h-5 w-5" />
            </Button>
        </div>
    </section>
</template>

<style scoped>
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
</style>
