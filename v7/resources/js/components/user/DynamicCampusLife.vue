<script setup lang="ts">
import { Badge } from '@/components/user/ui/badge';
import { Button } from '@/components/user/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/user/ui/card';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/user/ui/carousel';
import {
    ArrowRight,
    BookOpen,
    Bus,
    Calendar,
    Camera,
    CheckCircle,
    Coffee,
    Dumbbell,
    ExternalLink,
    Gamepad2,
    Heart,
    Home,
    Library,
    MapPin,
    Music,
    Palette,
    Shield,
    Star,
    Stethoscope,
    Trophy,
    Users,
    Utensils,
    Wifi,
} from 'lucide-vue-next';
import { computed } from 'vue';
import DynamicCampusGlance from './DynamicCampusGlance.vue';

// Interfaces for component props
interface GlanceItem {
    id: number;
    label: string;
    value: string;
    iconName: string;
    iconColor: string;
    isActive: boolean;
    displayOrder: number;
}
interface CampusLifeItem {
    id: number;
    title: string;
    category: string;
    description: string;
    image: string;
    fallbackGradient: string;
    iconName: string;
    features: string[];
    stats?: { label: string; value: string }[];
    link: string;
    featured?: boolean;
    isActive: boolean;
    displayOrder: number;
}

// Props definition
const props = defineProps<{
    campusLifeItems?: CampusLifeItem[];
    glanceItems?: GlanceItem[];
}>();

// Icon mapping
const iconMap = {
    Users,
    BookOpen,
    Heart,
    Coffee,
    Music,
    Palette,
    Trophy,
    Shield,
    Home,
    Gamepad2,
    Camera,
    Dumbbell,
    Library,
    Bus,
    Stethoscope,
    Utensils,
    Wifi,
    MapPin,
    Calendar,
    ExternalLink,
    ArrowRight,
    Star,
    CheckCircle,
} as any;

// Process and sort items for display
const campusLifeItems = computed(() => {
    return (props.campusLifeItems || []).filter((item) => item.isActive).sort((a, b) => a.displayOrder - b.displayOrder);
});

const getIconComponent = (iconName: string) => iconMap[iconName] || Users;

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

        <!-- Campus Life Carousel -->
        <Carousel v-if="campusLifeItems.length > 0" :opts="{ align: 'start', loop: true }" class="relative mx-auto w-full max-w-7xl">
            <CarouselContent class="-ml-2 md:-ml-4">
                <CarouselItem v-for="item in campusLifeItems" :key="item.id" class="pl-2 md:basis-1/2 md:pl-4 lg:basis-1/3">
                    <!-- ✅ FIXED: Added flex classes to the Card component -->
                    <Card
                        class="group flex h-full transform flex-col overflow-hidden border-2 border-gray-100 bg-white transition-all duration-500 hover:-translate-y-2 hover:border-blue-200 hover:shadow-2xl"
                    >
                        <div class="relative overflow-hidden">
                            <div class="relative flex h-36 items-center justify-center" :style="{ background: item.fallbackGradient }">
                                <div class="absolute inset-0 bg-black/20"></div>
                                <div v-if="item.featured" class="absolute top-3 left-3 z-10">
                                    <Badge class="border-0 bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-lg"
                                        ><Star class="mr-1 h-3 w-3" />Popular</Badge
                                    >
                                </div>
                                <div class="absolute top-3 right-3 z-10">
                                    <Badge :class="getCategoryColor(item.category)" class="border font-bold shadow-lg">{{ item.category }}</Badge>
                                </div>
                                <div v-if="item.stats && item.stats.length > 0" class="absolute bottom-3 left-3 z-10">
                                    <Badge class="border-white/30 bg-white/20 text-xs text-white shadow-sm backdrop-blur-sm hover:bg-white/30"
                                        ><CheckCircle class="mr-1 h-3 w-3" />{{ item.stats[0].value }} {{ item.stats[0].label }}</Badge
                                    >
                                </div>
                                <component :is="getIconComponent(item.iconName)" class="h-12 w-12 text-white/90" />
                            </div>
                        </div>
                        <CardHeader class="pb-3">
                            <!-- ✅ FIXED: Set a minimum height to prevent title jumping -->
                            <CardTitle
                                class="mb-2 line-clamp-2 min-h-[1.5em] text-lg leading-tight font-bold text-[hsl(var(--secondary))] transition-colors duration-200 hover:text-[hsl(var(--primary))]"
                                >{{ item.title }}</CardTitle
                            >
                            <!-- ✅ FIXED: Set a minimum height for the description -->
                            <CardDescription class="line-clamp-3 min-h-[4.5em] text-sm leading-relaxed text-muted-foreground">{{
                                item.description
                            }}</CardDescription>
                        </CardHeader>
                        <!-- ✅ FIXED: Added flex-grow to make this section fill available space -->
                        <CardContent class="flex flex-grow flex-col space-y-4 pt-0">
                            <div class="flex-grow space-y-2">
                                <div class="flex items-center gap-1 text-sm font-medium text-[hsl(var(--secondary))]">
                                    <component :is="getIconComponent(item.iconName)" class="h-4 w-4" />Key Features:
                                </div>
                                <div class="grid grid-cols-1 gap-1">
                                    <div
                                        v-for="feature in item.features.slice(0, 4)"
                                        :key="feature"
                                        class="flex items-center text-xs text-muted-foreground"
                                    >
                                        <span class="mr-2 h-1.5 w-1.5 flex-shrink-0 rounded-full bg-[hsl(var(--primary))]"></span>{{ feature }}
                                    </div>
                                </div>
                            </div>
                            <div v-if="item.stats && item.stats.length > 1" class="grid grid-cols-2 gap-3">
                                <div v-for="stat in item.stats" :key="stat.label" class="rounded-lg bg-gray-50 p-2 text-center">
                                    <div class="text-sm font-bold text-[hsl(var(--secondary))]">{{ stat.value }}</div>
                                    <div class="text-xs text-muted-foreground">{{ stat.label }}</div>
                                </div>
                            </div>
                            <div class="pt-2">
                                <Button as="a" :href="item.link" size="sm" class="w-full text-xs" variant="default">
                                    <component :is="getIconComponent(item.iconName)" class="mr-1 h-3 w-3" />Learn More<ArrowRight
                                        class="ml-1 h-3 w-3"
                                    />
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </CarouselItem>
            </CarouselContent>
            <CarouselPrevious class="-left-12 hidden sm:flex lg:-left-16" />
            <CarouselNext class="-right-12 hidden sm:flex lg:-right-16" />
        </Carousel>

        <DynamicCampusGlance :glance-items="glanceItems" />

        <div class="mt-12 text-center">
            <Button as="a" href="/campus-life" size="lg" class="px-8 py-4 text-lg">
                Explore Campus Life <ExternalLink class="ml-2 h-5 w-5" />
            </Button>
        </div>
    </section>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
/* No need for extra CSS, Tailwind's flexbox classes handle the alignment */
</style>
