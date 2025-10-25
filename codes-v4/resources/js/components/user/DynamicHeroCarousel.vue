<script setup lang="ts">
import { EmblaCarouselType } from 'embla-carousel';
import useEmblaCarousel from 'embla-carousel-vue';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';

// ✅ Define slide interface for dynamic functionality
interface HeroSlide {
    id?: number;
    title: string;
    subtitle: string;
    description: string;
    image: string;
    fallbackGradient: string;
    ctaText: string;
    ctaLink: string;
    secondaryCta?: {
        text: string;
        link: string;
    } | null;
}

// ✅ Accept props for dynamic slides
const props = defineProps<{
    slides?: HeroSlide[];
}>();

// ✅ Keep your original static slides as default fallback
const defaultSlides: HeroSlide[] = [
    {
        id: 1,
        title: 'Welcome to Computer Science and Engineering',
        subtitle: 'Department of CSE, MBSTU',
        description: 'Empowering the next generation of computer scientists and engineers with cutting-edge education and research opportunities.',
        image: '/images/carousel/slide1.jpg',
        fallbackGradient: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%)',
        ctaText: 'Learn More',
        ctaLink: '/about',
    },
    {
        id: 2,
        title: 'Innovation in Technology',
        subtitle: 'Research Excellence',
        description: 'Leading groundbreaking research in artificial intelligence, machine learning, and software engineering.',
        image: '/images/carousel/slide2.jpg',
        fallbackGradient: 'linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%)',
        ctaText: 'Explore Research',
        ctaLink: '/research',
    },
    {
        id: 3,
        title: 'Academic Excellence',
        subtitle: 'Quality Education',
        description: 'Comprehensive undergraduate and graduate programs designed to meet industry demands and academic standards.',
        image: '/images/carousel/slide3.jpg',
        fallbackGradient: 'linear-gradient(135deg, #7c3aed 0%, #8b5cf6 50%, #a78bfa 100%)',
        ctaText: 'View Programs',
        ctaLink: '/academic',
    },
    {
        id: 4,
        title: 'Future Leaders',
        subtitle: 'Student Success',
        description: 'Nurturing talented students to become leaders in technology and innovation across various industries.',
        image: '/images/carousel/slide4.jpg',
        fallbackGradient: 'linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%)',
        ctaText: 'Meet Students',
        ctaLink: '/students',
    },
];

// ✅ Use provided slides or fallback to default slides - DYNAMIC FUNCTIONALITY
const slides = computed(() => {
    if (props.slides && props.slides.length > 0) {
        // Add IDs if they don't exist and ensure all required fields are present
        return props.slides.map((slide, index) => ({
            id: slide.id || index + 1,
            title: slide.title || 'Untitled Slide',
            subtitle: slide.subtitle || 'Subtitle',
            description: slide.description || 'Description',
            image: slide.image || '/images/carousel/default.jpg',
            fallbackGradient: slide.fallbackGradient || 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)',
            ctaText: slide.ctaText || 'Learn More',
            ctaLink: slide.ctaLink || '#',
            secondaryCta: slide.secondaryCta || null,
        }));
    }
    return defaultSlides;
});

// Embla carousel setup
const [emblaRef, emblaApi] = useEmblaCarousel({
    loop: true,
    duration: 30,
    dragFree: false,
});

const selectedIndex = ref(0);
const scrollSnaps = ref<number[]>([]);

const scrollTo = (index: number) => {
    if (emblaApi.value) emblaApi.value.scrollTo(index);
};

const scrollPrev = () => {
    if (emblaApi.value) emblaApi.value.scrollPrev();
};

const scrollNext = () => {
    if (emblaApi.value) emblaApi.value.scrollNext();
};

const onInit = (emblaInstance: EmblaCarouselType) => {
    scrollSnaps.value = emblaInstance.scrollSnapList();
};

const onSelect = (emblaInstance: EmblaCarouselType) => {
    selectedIndex.value = emblaInstance.selectedScrollSnap();
};

// Auto-play functionality
let autoplayInterval: number | null = null;

const startAutoplay = () => {
    autoplayInterval = setInterval(() => {
        if (emblaApi.value) {
            emblaApi.value.scrollNext();
        }
    }, 5000); // Change slide every 5 seconds
};

const stopAutoplay = () => {
    if (autoplayInterval) {
        clearInterval(autoplayInterval);
        autoplayInterval = null;
    }
};

onMounted(() => {
    if (emblaApi.value) {
        emblaApi.value.on('init', onInit);
        emblaApi.value.on('select', onSelect);
        onInit(emblaApi.value);
        onSelect(emblaApi.value);

        // Start autoplay
        startAutoplay();
    }
});

onUnmounted(() => {
    stopAutoplay();
    if (emblaApi.value) {
        emblaApi.value.off('init', onInit);
        emblaApi.value.off('select', onSelect);
    }
});

// ✅ Debug logging for dynamic functionality
console.log('HeroCarousel - Props slides:', props.slides);
console.log('HeroCarousel - Computed slides:', slides.value);
</script>

<template>
    <div class="relative w-full">
        <!-- Carousel Container -->
        <div class="embla overflow-hidden" ref="emblaRef">
            <div class="embla__container flex">
                <div v-for="slide in slides" :key="slide.id" class="embla__slide relative w-full flex-none">
                    <!-- Slide Background Image - KEEPING ORIGINAL DESIGN -->
                    <div class="relative h-[500px] w-full overflow-hidden md:h-[600px] lg:h-[700px]">
                        <!-- Background with gradient fallback - DYNAMIC DATA -->
                        <div
                            class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                            :style="{
                                backgroundImage: `url(${slide.image}), ${slide.fallbackGradient}`,
                                background: slide.fallbackGradient,
                            }"
                        ></div>
                        <!-- Overlay - KEEPING ORIGINAL STYLING -->
                        <div class="absolute inset-0 bg-black/30"></div>

                        <!-- Content - KEEPING ORIGINAL DESIGN WITH DYNAMIC DATA -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="mx-auto max-w-7xl px-4 text-center text-white">
                                <div class="mx-auto max-w-4xl">
                                    <!-- Subtitle - DYNAMIC DATA -->
                                    <p class="mb-2 text-sm font-medium tracking-wide text-blue-200 uppercase md:text-base">
                                        {{ slide.subtitle }}
                                    </p>

                                    <!-- Main Title - DYNAMIC DATA -->
                                    <h1 class="mb-6 text-3xl leading-tight font-bold md:text-5xl lg:text-6xl">
                                        {{ slide.title }}
                                    </h1>

                                    <!-- Description - DYNAMIC DATA -->
                                    <p class="mx-auto mb-8 max-w-3xl text-lg leading-relaxed opacity-90 md:text-xl">
                                        {{ slide.description }}
                                    </p>

                                    <!-- Call to Action Button - DYNAMIC DATA -->
                                    <a
                                        :href="slide.ctaLink"
                                        class="inline-flex transform items-center rounded-lg bg-[hsl(var(--secondary))] px-8 py-4 text-lg font-semibold text-white shadow-lg transition-all duration-300 hover:scale-105 hover:bg-[hsl(var(--primary))] hover:shadow-xl"
                                    >
                                        {{ slide.ctaText }}
                                        <ChevronRight class="ml-2 h-5 w-5" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Arrows - KEEPING ORIGINAL DESIGN -->
        <button
            @click="scrollPrev"
            @mouseenter="stopAutoplay"
            @mouseleave="startAutoplay"
            class="absolute top-1/2 left-4 z-10 -translate-y-1/2 transform cursor-pointer rounded-full bg-white/20 p-3 text-white backdrop-blur-sm transition-all duration-300 hover:scale-125 hover:bg-white/30"
            aria-label="Previous slide"
        >
            <ChevronLeft class="h-6 w-6" />
        </button>

        <button
            @click="scrollNext"
            @mouseenter="stopAutoplay"
            @mouseleave="startAutoplay"
            class="absolute top-1/2 right-4 z-10 -translate-y-1/2 transform cursor-pointer rounded-full bg-white/20 p-3 text-white backdrop-blur-sm transition-all duration-300 hover:scale-125 hover:bg-white/30"
            aria-label="Next slide"
        >
            <ChevronRight class="h-6 w-6" />
        </button>

        <!-- Dot Indicators - KEEPING ORIGINAL DESIGN -->
        <div class="absolute bottom-6 left-1/2 z-10 -translate-x-1/2 transform">
            <div class="flex space-x-3">
                <button
                    v-for="(_, index) in scrollSnaps"
                    :key="index"
                    @click="scrollTo(index)"
                    @mouseenter="stopAutoplay"
                    @mouseleave="startAutoplay"
                    :class="[
                        'h-3.5 w-3.5 cursor-pointer rounded-full transition-all duration-300',
                        index === selectedIndex ? 'bg-white' : 'border-2 border-solid border-white',
                    ]"
                    :aria-label="`Go to slide ${index + 1}`"
                />
            </div>
        </div>

        <!-- ✅ Debug Info for dynamic functionality (enable for debugging) -->
        <div v-if="false" class="absolute top-4 left-4 z-50 rounded bg-black/70 p-2 text-xs text-white">
            <div>Slides count: {{ slides.length }}</div>
            <div>Has props: {{ !!props.slides }}</div>
            <div>Current slide: {{ selectedIndex + 1 }}</div>
            <div>Using: {{ (props.slides?.length || 0) > 0 ? 'Dynamic Data' : 'Default Slides' }}</div>
        </div>
    </div>
</template>

<style scoped>
/* ✅ KEEPING ALL YOUR ORIGINAL STYLES */
.embla {
    max-width: 100%;
    margin: 0 auto;
}

.embla__container {
    display: flex;
}

.embla__slide {
    position: relative;
    min-width: 0;
}

/* Smooth slide transitions */
.embla__slide {
    transition: opacity 0.3s ease-in-out;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .embla__slide h1 {
        font-size: 2rem;
        line-height: 1.2;
    }

    .embla__slide p {
        font-size: 1rem;
    }
}

/* Custom scrollbar for content if needed */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 3px;
}

::-webkit-scrollbar-thumb {
    background: rgba(59, 130, 246, 0.5);
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(59, 130, 246, 0.7);
}

/* ✅ Additional styles from your static version */
.embla button {
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

/* Enhanced hover effects for navigation */
.embla button[aria-label*='slide']:hover {
    transform: scale(1.2);
}

/* Better text readability */
.embla__slide h1,
.embla__slide p {
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

/* Smooth transitions for dynamic content changes */
.embla__slide .absolute.inset-0.bg-cover {
    transition: background-image 0.5s ease-in-out;
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
    .embla__slide {
        animation: none;
    }

    .transition-all,
    .transform,
    .hover\:scale-105:hover,
    .hover\:scale-125:hover {
        transition: none;
        transform: none;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .bg-black\/30 {
        background: rgba(0, 0, 0, 0.7);
    }

    .text-white {
        text-shadow: 2px 2px 0 rgba(0, 0, 0, 1);
    }
}
</style>
