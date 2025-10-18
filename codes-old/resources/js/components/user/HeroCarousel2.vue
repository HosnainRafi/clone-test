<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { EmblaCarouselType } from 'embla-carousel'
import useEmblaCarousel from 'embla-carousel-vue'
import { ChevronLeft, ChevronRight } from 'lucide-vue-next'

// Carousel slides data - based on MBSTU official website
const slides = [
  {
    id: 1,
    title: 'Welcome to MBSTU',
    subtitle: 'Mawlana Bhashani Science and Technology University',
    description: 'A leading public university in Bangladesh dedicated to advancing science, technology, and innovation for national development.',
    image: '/images/carousel/mbstu-campus.jpg',
    fallbackGradient: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%)',
    ctaText: 'About MBSTU',
    ctaLink: '/about'
  },
  {
    id: 2,
    title: 'Academic Excellence',
    subtitle: '7 Faculties & 33 Departments',
    description: 'Offering undergraduate and graduate programs across diverse fields including Engineering, Science, Agriculture, and Social Sciences.',
    image: '/images/carousel/academic-building.jpg',
    fallbackGradient: 'linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%)',
    ctaText: 'Explore Programs',
    ctaLink: '/academic'
  },
  {
    id: 3,
    title: 'Research & Innovation',
    subtitle: 'Advancing Knowledge',
    description: 'Pioneering research in emerging technologies, sustainable development, and scientific breakthroughs that shape the future.',
    image: '/images/carousel/research-lab.jpg',
    fallbackGradient: 'linear-gradient(135deg, #7c3aed 0%, #8b5cf6 50%, #a78bfa 100%)',
    ctaText: 'View Research',
    ctaLink: '/research'
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
          <div class="relative h-[600px] md:h-[700px] lg:h-[800px] w-full overflow-hidden">
            <!-- Background with gradient fallback -->
            <div
              class="absolute inset-0 bg-cover bg-center bg-no-repeat"
              :style="{ 
                backgroundImage: `url(${slide.image}), ${slide.fallbackGradient}`,
                background: slide.fallbackGradient
              }"
            ></div>
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/40"></div>
            
            <!-- Content -->
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="max-w-7xl mx-auto px-4 text-center text-white">
                <div class="max-w-5xl mx-auto">
                  <!-- MBSTU Logo or Icon (placeholder) -->
                  <div class="mb-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 backdrop-blur-sm rounded-full mb-4">
                      <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7V10C2 16 6 20.5 12 22C18 20.5 22 16 22 10V7L12 2Z"/>
                      </svg>
                    </div>
                  </div>
                  
                  <!-- Subtitle -->
                  <p class="text-sm md:text-lg uppercase tracking-wide text-blue-100 mb-3 font-semibold">
                    {{ slide.subtitle }}
                  </p>
                  
                  <!-- Main Title -->
                  <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight text-shadow-lg">
                    {{ slide.title }}
                  </h1>
                  
                  <!-- Description -->
                  <p class="text-lg md:text-xl lg:text-2xl mb-10 max-w-4xl mx-auto leading-relaxed opacity-95 font-light">
                    {{ slide.description }}
                  </p>
                  
                  <!-- Call to Action Button -->
                  <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a
                      :href="slide.ctaLink"
                      class="inline-flex items-center px-10 py-4 bg-[hsl(var(--primary))] hover:bg-[hsl(var(--secondary))] text-white font-semibold text-lg rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border-2 border-transparent hover:border-white/20"
                    >
                      {{ slide.ctaText }}
                      <ChevronRight class="ml-2 h-5 w-5" />
                    </a>
                    
                    <!-- Secondary CTA for first slide -->
                    <a
                      v-if="slide.id === 1"
                      href="/admission"
                      class="inline-flex items-center px-8 py-4 bg-transparent border-2 border-white text-white font-semibold text-lg rounded-lg transition-all duration-300 hover:bg-white hover:text-black"
                    >
                      Admission
                    </a>
                  </div>
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

/* Text shadow for better readability */
.text-shadow-lg {
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3), 
               0 0 20px rgba(0, 0, 0, 0.2);
}

/* Enhanced button hover effects */
.embla__slide a {
  text-decoration: none;
}

.embla__slide a:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .embla__slide h1 {
    font-size: 2.5rem;
    line-height: 1.1;
  }
  
  .embla__slide p {
    font-size: 1.1rem;
    margin-bottom: 2rem;
  }
  
  .embla__slide .flex-col {
    flex-direction: column;
  }
}

@media (max-width: 640px) {
  .embla__slide h1 {
    font-size: 2rem;
  }
  
  .embla__slide p {
    font-size: 1rem;
  }
}

/* Enhanced navigation arrows */
.embla button {
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
}

/* Dot indicators enhancement */
.embla button[aria-label*="Go to slide"] {
  backdrop-filter: none;
  -webkit-backdrop-filter: none;
}

.embla button[aria-label*="Go to slide"]:hover {
  transform: scale(1.2);
}

/* Parallax effect for background */
.embla__slide .absolute.inset-0.bg-cover {
  transform: scale(1.1);
  transition: transform 8s ease-out;
}

.embla__slide:hover .absolute.inset-0.bg-cover {
  transform: scale(1.05);
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

/* Animation for content entrance */
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

.embla__slide .max-w-5xl > * {
  animation: fadeInUp 0.8s ease-out forwards;
}

.embla__slide .max-w-5xl > *:nth-child(2) {
  animation-delay: 0.2s;
}

.embla__slide .max-w-5xl > *:nth-child(3) {
  animation-delay: 0.4s;
}

.embla__slide .max-w-5xl > *:nth-child(4) {
  animation-delay: 0.6s;
}

.embla__slide .max-w-5xl > *:nth-child(5) {
  animation-delay: 0.8s;
}
</style>