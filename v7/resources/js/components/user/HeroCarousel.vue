<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { EmblaCarouselType } from 'embla-carousel'
import useEmblaCarousel from 'embla-carousel-vue'
import { ChevronLeft, ChevronRight } from 'lucide-vue-next'

// Carousel slides data - based on MBSTU CSE website style
const slides = [
  {
    id: 1,
    title: 'Welcome to Computer Science and Engineering',
    subtitle: 'Department of CSE, MBSTU',
    description: 'Empowering the next generation of computer scientists and engineers with cutting-edge education and research opportunities.',
    image: '/images/carousel/slide1.jpg',
    fallbackGradient: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%)',
    ctaText: 'Learn More',
    ctaLink: '/about'
  },
  {
    id: 2,
    title: 'Innovation in Technology',
    subtitle: 'Research Excellence',
    description: 'Leading groundbreaking research in artificial intelligence, machine learning, and software engineering.',
    image: '/images/carousel/slide2.jpg',
    fallbackGradient: 'linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%)',
    ctaText: 'Explore Research',
    ctaLink: '/research'
  },
  {
    id: 3,
    title: 'Academic Excellence',
    subtitle: 'Quality Education',
    description: 'Comprehensive undergraduate and graduate programs designed to meet industry demands and academic standards.',
    image: '/images/carousel/slide3.jpg',
    fallbackGradient: 'linear-gradient(135deg, #7c3aed 0%, #8b5cf6 50%, #a78bfa 100%)',
    ctaText: 'View Programs',
    ctaLink: '/academic'
  },
  {
    id: 4,
    title: 'Future Leaders',
    subtitle: 'Student Success',
    description: 'Nurturing talented students to become leaders in technology and innovation across various industries.',
    image: '/images/carousel/slide4.jpg',
    fallbackGradient: 'linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%)',
    ctaText: 'Meet Students',
    ctaLink: '/students'
  }
]

// Embla carousel setup
const [emblaRef, emblaApi] = useEmblaCarousel({
  loop: true,
  duration: 30,
  dragFree: false,
})

const selectedIndex = ref(0)
const scrollSnaps = ref<number[]>([])

const scrollTo = (index: number) => {
  if (emblaApi.value) emblaApi.value.scrollTo(index)
}

const scrollPrev = () => {
  if (emblaApi.value) emblaApi.value.scrollPrev()
}

const scrollNext = () => {
  if (emblaApi.value) emblaApi.value.scrollNext()
}

const onInit = (emblaInstance: EmblaCarouselType) => {
  scrollSnaps.value = emblaInstance.scrollSnapList()
}

const onSelect = (emblaInstance: EmblaCarouselType) => {
  selectedIndex.value = emblaInstance.selectedScrollSnap()
}

// Auto-play functionality
let autoplayInterval: number | null = null

const startAutoplay = () => {
  autoplayInterval = setInterval(() => {
    if (emblaApi.value) {
      emblaApi.value.scrollNext()
    }
  }, 5000) // Change slide every 5 seconds
}

const stopAutoplay = () => {
  if (autoplayInterval) {
    clearInterval(autoplayInterval)
    autoplayInterval = null
  }
}

onMounted(() => {
  if (emblaApi.value) {
    emblaApi.value.on('init', onInit)
    emblaApi.value.on('select', onSelect)
    onInit(emblaApi.value)
    onSelect(emblaApi.value)
    
    // Start autoplay
    startAutoplay()
  }
})

onUnmounted(() => {
  stopAutoplay()
  if (emblaApi.value) {
    emblaApi.value.off('init', onInit)
    emblaApi.value.off('select', onSelect)
  }
})
</script>

<template>
  <div class="relative w-full">
    <!-- Carousel Container -->
    <div class="embla overflow-hidden" ref="emblaRef">
      <div class="embla__container flex">
        <div
          v-for="slide in slides"
          :key="slide.id"
          class="embla__slide flex-none w-full relative"
        >
          <!-- Slide Background Image -->
          <div class="relative h-[500px] md:h-[600px] lg:h-[700px] w-full overflow-hidden">
            <!-- Background with gradient fallback -->
            <div
              class="absolute inset-0 bg-cover bg-center bg-no-repeat"
              :style="{ 
                backgroundImage: `url(${slide.image}), ${slide.fallbackGradient}`,
                background: slide.fallbackGradient
              }"
            ></div>
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/30"></div>
            
            <!-- Content -->
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="max-w-7xl mx-auto px-4 text-center text-white">
                <div class="max-w-4xl mx-auto">
                  <!-- Subtitle -->
                  <p class="text-sm md:text-base uppercase tracking-wide text-blue-200 mb-2 font-medium">
                    {{ slide.subtitle }}
                  </p>
                  
                  <!-- Main Title -->
                  <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    {{ slide.title }}
                  </h1>
                  
                  <!-- Description -->
                  <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto leading-relaxed opacity-90">
                    {{ slide.description }}
                  </p>
                  
                  <!-- Call to Action Button -->
                  <a
                    :href="slide.ctaLink"
                    class="inline-flex items-center px-8 py-4 bg-[hsl(var(--secondary))] hover:bg-[hsl(var(--primary))] text-white font-semibold text-lg rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
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

    <!-- Navigation Arrows -->
    <button
      @click="scrollPrev"
      @mouseenter="stopAutoplay"
      @mouseleave="startAutoplay"
      class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white p-3 rounded-full transition-all duration-300 hover:scale-125 cursor-pointer"
      aria-label="Previous slide"
    >
      <ChevronLeft class="h-6 w-6" />
    </button>

    <button
      @click="scrollNext"
      @mouseenter="stopAutoplay"
      @mouseleave="startAutoplay"
      class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white p-3 rounded-full transition-all duration-300 hover:scale-125 cursor-pointer"
      aria-label="Next slide"
    >
      <ChevronRight class="h-6 w-6" />
    </button>

    <!-- Dot Indicators -->
    <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-10">
      <div class="flex space-x-3">
        <button
          v-for="(_, index) in scrollSnaps"
          :key="index"
          @click="scrollTo(index)"
          @mouseenter="stopAutoplay"
          @mouseleave="startAutoplay"
          :class="[
            'w-3.5 h-3.5 rounded-full transition-all duration-300 cursor-pointer',
            index === selectedIndex
              ? 'bg-white'
              : 'border-2 border-solid border-white'
          ]"
          :aria-label="`Go to slide ${index + 1}`"
        />
      </div>
    </div>
  </div>
</template>

<style scoped>
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
</style>