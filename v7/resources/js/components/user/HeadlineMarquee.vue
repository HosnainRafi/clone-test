<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

// Simple marquee implementation
const marqueeRef = ref<HTMLElement>()
const isPaused = ref(false)

// Headline news and announcements for MBSTU CSE
const headlines = [
  {
    id: 1,
    type: 'announcement',
    text: '🎓 Admission Open for Fall 2025 - Apply now for undergraduate and graduate programs',
    link: '/admission',
    priority: 'high'
  },
  {
    id: 2,
    type: 'news',
    text: '🏆 CSE Department wins National Programming Contest 2025',
    link: '/news/programming-contest-2025',
    priority: 'medium'
  },
  {
    id: 3,
    type: 'event',
    text: '📅 International Conference on AI & Machine Learning - March 15-17, 2025',
    link: '/events/ai-conference-2025',
    priority: 'high'
  },
  {
    id: 4,
    type: 'research',
    text: '🔬 New Research Lab inaugurated for Cybersecurity and Blockchain Technology',
    link: '/research/cybersecurity-lab',
    priority: 'medium'
  },
  {
    id: 5,
    type: 'achievement',
    text: '🌟 Dr. Rahman receives Best Faculty Award for outstanding research contribution',
    link: '/faculty/dr-rahman-award',
    priority: 'medium'
  },
  {
    id: 6,
    type: 'announcement',
    text: '📚 New Digital Library resources available - Access 10,000+ technical journals',
    link: '/library/digital-resources',
    priority: 'low'
  }
]

// Filter high priority headlines for main marquee
const priorityHeadlines = headlines.filter(h => h.priority === 'high')
const allHeadlines = headlines
</script>

<template>
  <div class="text-white py-3 border-b bg-[hsl(var(--quaternary))] shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
      <!-- Breaking News Label -->
      <div class="flex items-center">
        <div class="flex-shrink-0 mr-4">
          <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-600 text-white animate-pulse">
            <span class="w-2 h-2 bg-white rounded-full mr-2 animate-ping"></span>
            LATEST
          </span>
        </div>
        
        <!-- Main Marquee -->
        <div class="flex-1 overflow-hidden">
          <div 
            ref="marqueeRef"
            class="marquee-container"
            @mouseenter="isPaused = true"
            @mouseleave="isPaused = false"
          >
            <div 
              class="marquee-content text-sm md:text-base"
              :class="{ 'paused': isPaused }"
            >
              <div class="flex items-center space-x-8">
                <div
                  v-for="headline in allHeadlines"
                  :key="headline.id"
                  class="flex items-center space-x-2 whitespace-nowrap"
                >
                  <a
                    :href="headline.link"
                    class="hover:text-[hsl(var(--primary))] text-[hsl(var(--secondary))] transition-colors duration-200 font-medium"
                  >
                    {{ headline.text }}
                  </a>
                  <span class="text-blue-300 mx-4">•</span>
                </div>
                <!-- Duplicate content for seamless loop -->
                <div
                  v-for="headline in allHeadlines"
                  :key="`duplicate-${headline.id}`"
                  class="flex items-center space-x-2 whitespace-nowrap"
                >
                  <a
                    :href="headline.link"
                    class="hover:text-blue-200 transition-colors duration-200 font-medium"
                    :class="{
                      'text-yellow-300': headline.priority === 'high',
                      'text-green-300': headline.priority === 'medium',
                      'text-blue-100': headline.priority === 'low'
                    }"
                  >
                    {{ headline.text }}
                  </a>
                  <span class="text-blue-300 mx-4">•</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="hidden md:flex items-center space-x-2 ml-4 flex-shrink-0">
          <a
            href="/notices"
            class="text-xs px-3 py-1 bg-[hsl(var(--secondary))] hover:bg-[hsl(var(--primary))] rounded-md transition-colors duration-200 font-medium"
          >
            All Notices
          </a>
          <a
            href="/news"
            class="text-xs px-3 py-1 bg-[hsl(var(--secondary))] hover:bg-[hsl(var(--primary))] rounded-md transition-colors duration-200 font-medium"
          >
            All News
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Custom marquee implementation */
.marquee-container {
  overflow: hidden;
  width: 100%;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
}

.marquee-content {
  display: flex;
  animation: marquee 60s linear infinite;
}

.marquee-content.paused {
  animation-play-state: paused;
}

@keyframes marquee {
  0% {
    transform: translateX(100%);
  }
  100% {
    transform: translateX(-100%);
  }
}

/* Enhance the breaking news animation */
@keyframes pulse-glow {
  0%, 100% {
    box-shadow: 0 0 5px rgba(220, 38, 38, 0.5);
  }
  50% {
    box-shadow: 0 0 20px rgba(220, 38, 38, 0.8);
  }
}

.animate-pulse {
  animation: pulse-glow 2s infinite;
}

/* Smooth hover transitions for links */
a {
  transition: all 0.2s ease-in-out;
}

a:hover {
  transform: translateY(-1px);
}

/* Mobile responsiveness */
@media (max-width: 768px) {
  .vue3-marquee {
    font-size: 0.875rem;
  }
}

</style>