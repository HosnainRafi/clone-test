<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Users, GraduationCap, Eye, MousePointer } from 'lucide-vue-next'

// Statistics data
const stats = [
  {
    id: 1,
    icon: GraduationCap,
    number: 19,
    suffix: '+',
    label: 'Current Teachers',
    description: 'Experienced faculty members',
    color: 'blue',
    bgGradient: 'from-blue-500 to-blue-600'
  },
  {
    id: 2,
    icon: Users,
    number: 250,
    suffix: '+',
    label: 'Current Students',
    description: 'Active enrolled students',
    color: 'green',
    bgGradient: 'from-green-500 to-green-600'
  },
  {
    id: 3,
    icon: Eye,
    number: 66483,
    suffix: '+',
    label: 'Total Visitors',
    description: 'Unique website visitors',
    color: 'purple',
    bgGradient: 'from-purple-500 to-purple-600'
  },
  {
    id: 4,
    icon: MousePointer,
    number: 387405,
    suffix: '+',
    label: 'Total Visits',
    description: 'Total website visits',
    color: 'orange',
    bgGradient: 'from-orange-500 to-orange-600'
  }
]

// Animation state
const animatedNumbers = ref(stats.map(() => 0))
const isVisible = ref(false)

// Number animation function
const animateNumber = (targetNumber: number, index: number, duration: number = 2000) => {
  const startTime = Date.now()
  const startNumber = 0
  
  const updateNumber = () => {
    const elapsed = Date.now() - startTime
    const progress = Math.min(elapsed / duration, 1)
    
    // Easing function for smooth animation
    const easeOutCubic = (t: number) => 1 - Math.pow(1 - t, 3)
    const easedProgress = easeOutCubic(progress)
    
    animatedNumbers.value[index] = Math.floor(startNumber + (targetNumber - startNumber) * easedProgress)
    
    if (progress < 1) {
      requestAnimationFrame(updateNumber)
    } else {
      animatedNumbers.value[index] = targetNumber
    }
  }
  
  requestAnimationFrame(updateNumber)
}

// Format number with commas
const formatNumber = (number: number) => {
  return number.toLocaleString()
}

// Get color classes for each stat
const getColorClasses = (color: string) => {
  const colorMap = {
    blue: {
      icon: 'text-blue-600',
      border: 'border-blue-200',
      bg: 'bg-blue-50'
    },
    green: {
      icon: 'text-green-600',
      border: 'border-green-200',
      bg: 'bg-green-50'
    },
    purple: {
      icon: 'text-purple-600',
      border: 'border-purple-200',
      bg: 'bg-purple-50'
    },
    orange: {
      icon: 'text-orange-600',
      border: 'border-orange-200',
      bg: 'bg-orange-50'
    }
  }
  return colorMap[color as keyof typeof colorMap] || colorMap.blue
}

// Start animations when component is visible
onMounted(() => {
  // Use Intersection Observer to trigger animation when visible
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting && !isVisible.value) {
        isVisible.value = true
        // Start animations with staggered delays
        stats.forEach((stat, index) => {
          setTimeout(() => {
            animateNumber(stat.number, index, 2500)
          }, index * 200)
        })
      }
    })
  }, { threshold: 0.3 })
  
  // Observe the component
  const element = document.querySelector('.at-a-glance-section')
  if (element) {
    observer.observe(element)
  }
})
</script>

<template>
  <section class="at-a-glance-section container pt-24">
    <!-- Section Header -->
    <div class="text-center mb-12">
    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
        At a Glance
    </h2>
    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
        Key statistics about the Department of Computer Science and Engineering at MBSTU
    </p>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
    <div
        v-for="(stat, index) in stats"
        :key="stat.id"
        class="group relative bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border-2"
        :class="getColorClasses(stat.color).border"
    >
        <!-- Background Gradient Decoration -->
        <div 
        class="absolute top-0 right-0 w-20 h-20 rounded-2xl opacity-10 transform rotate-12 transition-all duration-500 group-hover:scale-110"
        :class="`bg-gradient-to-br ${stat.bgGradient}`"
        ></div>
        
        <!-- Icon -->
        <div 
        class="relative z-10 w-16 h-16 rounded-xl flex items-center justify-center mb-6 transition-all duration-300 group-hover:scale-110"
        :class="getColorClasses(stat.color).bg"
        >
        <component 
            :is="stat.icon" 
            class="w-8 h-8 transition-colors duration-300"
            :class="getColorClasses(stat.color).icon"
        />
        </div>

        <!-- Number -->
        <div class="relative z-10 mb-4">
        <div class="flex items-baseline">
            <span class="text-4xl md:text-5xl font-bold text-gray-900 tabular-nums">
            {{ formatNumber(animatedNumbers[index]) }}
            </span>
            <span class="text-2xl font-bold text-gray-600 ml-1">
            {{ stat.suffix }}
            </span>
        </div>
        </div>

        <!-- Label and Description -->
        <div class="relative z-10">
        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors duration-300">
            {{ stat.label }}
        </h3>
        <p class="text-gray-600 text-sm leading-relaxed">
            {{ stat.description }}
        </p>
        </div>

        <!-- Hover Effect Overlay -->
        <div class="absolute inset-0 rounded-2xl bg-gradient-to-br opacity-0 group-hover:opacity-5 transition-opacity duration-500"
            :class="stat.bgGradient"></div>
    </div>
    </div>
  </section>
</template>

<style scoped>
/* Tabular numbers for consistent digit spacing */
.tabular-nums {
  font-variant-numeric: tabular-nums;
}

/* Custom animation keyframes */
@keyframes countUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Staggered animation for cards */
.group:nth-child(1) { animation-delay: 0s; }
.group:nth-child(2) { animation-delay: 0.2s; }
.group:nth-child(3) { animation-delay: 0.4s; }
.group:nth-child(4) { animation-delay: 0.6s; }

/* Hover effects */
.group:hover .w-16 {
  animation: bounce 0.6s ease-in-out;
}

@keyframes bounce {
  0%, 20%, 60%, 100% {
    transform: translateY(0) scale(1.1);
  }
  40% {
    transform: translateY(-10px) scale(1.15);
  }
  80% {
    transform: translateY(-5px) scale(1.12);
  }
}

/* Custom gradient backgrounds */
.bg-gradient-to-br {
  background-image: linear-gradient(to bottom right, var(--tw-gradient-stops));
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .text-4xl {
    font-size: 2.5rem;
  }
  
  .text-5xl {
    font-size: 3rem;
  }
}

/* Loading animation */
@keyframes pulse-glow {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.7;
  }
}

.animate-pulse-glow {
  animation: pulse-glow 2s ease-in-out infinite;
}
</style>