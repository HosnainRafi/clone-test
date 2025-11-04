<script setup lang="ts">
import { ref } from 'vue';
import { Button } from "@/components/user/ui/button";
import { Play, X } from 'lucide-vue-next';

// State for video modal
const isVideoModalOpen = ref(false);
const videoId = 'PZ9MHpFet34';

// Functions to handle video modal
const openVideoModal = () => {
  isVideoModalOpen.value = true;
  document.body.style.overflow = 'hidden'; // Prevent background scrolling
};

const closeVideoModal = () => {
  isVideoModalOpen.value = false;
  document.body.style.overflow = ''; // Restore scrolling
};

// Close modal on escape key
const handleKeyDown = (event: KeyboardEvent) => {
  if (event.key === 'Escape') {
    closeVideoModal();
  }
};

// Add escape key listener when modal is open
const onModalOpenChange = (open: boolean) => {
  if (open) {
    document.addEventListener('keydown', handleKeyDown);
  } else {
    document.removeEventListener('keydown', handleKeyDown);
  }
};

// Watch for modal state changes
import { watch } from 'vue';
watch(isVideoModalOpen, (isOpen) => {
  onModalOpenChange(isOpen);
});
</script>

<template>
  <section class="relative container pt-24 min-h-[70vh] flex items-center justify-center overflow-hidden">
    <!-- Background Image with Overlay -->
    <div 
      class="absolute inset-0 bg-cover bg-center bg-no-repeat"
      style="background-image: url('https://mbstu.ac.bd/wp-content/uploads/2023/08/home-Video.jpg')"
    >
      <!-- Dark overlay for better text readability -->
      <div class="absolute inset-0 bg-black/50"></div>
      
      <!-- Animated gradient overlay -->
      <div class="absolute inset-0 bg-gradient-to-br from-blue-900/30 via-transparent to-green-900/30"></div>
    </div>

    <!-- Content Container -->
    <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
      <!-- Welcome Title -->
      <div class="mb-8 space-y-4">
        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-[hsl(var(--secondary))] leading-tight">
          Welcome to MBSTU
        </h1>
      </div>

      <!-- Play Button -->
      <div class="mb-12">
        <Button
          @click="openVideoModal"
          size="lg"
          class="group relative bg-white/10 hover:bg-white/20 backdrop-blur-sm border-2 border-white/30 hover:border-white/50 text-white hover:text-white transition-all duration-300 px-8 py-6 text-lg font-semibold rounded-full shadow-2xl hover:shadow-white/25 transform hover:scale-105"
        >
          <!-- Play Icon with Animation -->
          <div class="flex items-center space-x-3">
            <div class="relative">
              <!-- Pulsing ring animation -->
              <div class="absolute inset-0 bg-white/30 rounded-full animate-ping"></div>
              <div class="relative bg-white/20 rounded-full p-3 group-hover:bg-white/30 transition-colors duration-300">
                <Play class="h-6 w-6 text-white fill-white ml-0.5" />
              </div>
            </div>
            <span>Watch Campus Video</span>
          </div>
        </Button>
      </div>
    </div>

    <!-- Video Modal -->
    <Teleport to="body">
      <div 
        v-if="isVideoModalOpen"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        @click="closeVideoModal"
      >
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>
        
        <!-- Modal Content -->
        <div 
          class="relative w-full max-w-5xl mx-auto bg-black rounded-lg overflow-hidden shadow-2xl"
          @click.stop
        >
          <!-- Close Button -->
          <Button
            @click="closeVideoModal"
            variant="ghost"
            size="sm"
            class="absolute top-4 right-4 z-50 bg-black/50 hover:bg-black/70 text-white hover:text-white border-white/20 rounded-full p-2"
          >
            <X class="h-4 w-4" />
          </Button>

          <!-- Video Container -->
          <div class="relative w-full" style="padding-bottom: 56.25%"> <!-- 16:9 aspect ratio -->
            <iframe
              v-if="isVideoModalOpen"
              class="absolute top-0 left-0 w-full h-full"
              :src="`https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&modestbranding=1`"
              title="The beauty of Mawlana Bhashani Science and Technology University."
              frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
              referrerpolicy="strict-origin-when-cross-origin"
              allowfullscreen
            ></iframe>
          </div>
        </div>
      </div>
    </Teleport>
  </section>
</template>

<style scoped>
/* Custom animations */
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-20px); }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

/* Ensure the background image loads properly */
.bg-cover {
  background-size: cover;
}

.bg-center {
  background-position: center;
}

.bg-no-repeat {
  background-repeat: no-repeat;
}

/* Custom backdrop blur for older browser support */
.backdrop-blur-sm {
  backdrop-filter: blur(4px);
  -webkit-backdrop-filter: blur(4px);
}

/* Smooth scrolling indicator */
@keyframes scroll {
  0% { opacity: 1; transform: translateY(0); }
  100% { opacity: 0; transform: translateY(20px); }
}

.animate-scroll {
  animation: scroll 2s ease-in-out infinite;
}

/* Responsive video container */
.video-responsive {
  position: relative;
  padding-bottom: 56.25%; /* 16:9 aspect ratio */
  height: 0;
  overflow: hidden;
}

.video-responsive iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

/* Custom gradient text */
.gradient-text {
  background: linear-gradient(135deg, #3b82f6, #ffffff, #10b981);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
}
</style>