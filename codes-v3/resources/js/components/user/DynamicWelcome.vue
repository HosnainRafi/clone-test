<script setup lang="ts">
import { Button } from '@/components/user/ui/button';
import { Play, X } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

// ✅ SIMPLIFIED: Essential interface only (matching your static design)
interface WelcomeItem {
    id?: number;
    title: string;
    description?: string;
    backgroundImage: string;
    videoId?: string;
    buttonText: string;
    isActive: boolean;
    displayOrder: number;
}

// ✅ Accept props for dynamic welcome section
const props = defineProps<{
    welcomeItems?: WelcomeItem[];
}>();

// State for video modal - KEEPING ORIGINAL IMPLEMENTATION
const isVideoModalOpen = ref(false);
const videoId = ref('PZ9MHpFet34'); // Static fallback video ID

// ✅ SIMPLIFIED: Default welcome matching your static version
const defaultWelcome: WelcomeItem = {
    id: 1,
    title: 'Welcome to MBSTU',
    description: 'A leading public university in Bangladesh dedicated to advancing science, technology, and innovation for national development.',
    backgroundImage: 'https://mbstu.ac.bd/wp-content/uploads/2023/08/home-Video.jpg',
    videoId: 'PZ9MHpFet34',
    buttonText: 'Watch Campus Video',
    isActive: true,
    displayOrder: 1,
};

// ✅ Use provided welcome items or fallback to static default
const currentWelcome = computed(() => {
    if (props.welcomeItems && props.welcomeItems.length > 0) {
        // Use first active welcome item
        const activeWelcome = props.welcomeItems.filter((item) => item.isActive).sort((a, b) => a.displayOrder - b.displayOrder)[0];

        if (activeWelcome) {
            return {
                id: activeWelcome.id || 1,
                title: activeWelcome.title || defaultWelcome.title,
                description: activeWelcome.description || defaultWelcome.description,
                backgroundImage: activeWelcome.backgroundImage || defaultWelcome.backgroundImage,
                videoId: activeWelcome.videoId || defaultWelcome.videoId,
                buttonText: activeWelcome.buttonText || defaultWelcome.buttonText,
                isActive: activeWelcome.isActive,
                displayOrder: activeWelcome.displayOrder || 1,
            };
        }
    }
    return defaultWelcome;
});

// Update video ID when current welcome changes
watch(
    () => currentWelcome.value.videoId,
    (newVideoId) => {
        if (newVideoId) {
            videoId.value = newVideoId;
        }
    },
    { immediate: true },
);

// Functions to handle video modal - ✅ KEEPING ORIGINAL IMPLEMENTATION
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
watch(isVideoModalOpen, (isOpen) => {
    onModalOpenChange(isOpen);
});

// ✅ Debug logging for dynamic functionality
console.log('Welcome - Props welcomeItems:', props.welcomeItems);
console.log('Welcome - Current welcome:', currentWelcome.value);
</script>

<template>
    <!-- ✅ KEEPING EXACT ORIGINAL STATIC DESIGN -->
    <section class="relative container flex min-h-[70vh] items-center justify-center overflow-hidden pt-24">
        <!-- Background Image with Overlay - ORIGINAL DESIGN WITH DYNAMIC DATA -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" :style="{ backgroundImage: `url('${currentWelcome.backgroundImage}')` }">
            <!-- Dark overlay for better text readability - ✅ KEEPING ORIGINAL -->
            <div class="absolute inset-0 bg-black/50"></div>

            <!-- Animated gradient overlay - ✅ KEEPING ORIGINAL -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/30 via-transparent to-green-900/30"></div>
        </div>

        <!-- Content Container - ORIGINAL DESIGN WITH DYNAMIC DATA -->
        <div class="relative z-10 mx-auto max-w-5xl px-4 text-center sm:px-6 lg:px-8">
            <!-- Welcome Title - ✅ KEEPING ORIGINAL STYLING -->
            <div class="mb-8 space-y-4">
                <!-- Main Title - DYNAMIC DATA -->
                <h1 class="text-4xl leading-tight font-bold text-[hsl(var(--secondary))] sm:text-5xl md:text-6xl lg:text-7xl">
                    {{ currentWelcome.title }}
                </h1>

                <!-- Description - DYNAMIC DATA (only if exists) -->
                <p v-if="currentWelcome.description" class="mx-auto max-w-3xl text-lg text-white opacity-85 sm:text-xl">
                    {{ currentWelcome.description }}
                </p>
            </div>

            <!-- Play Button - ✅ KEEPING EXACT ORIGINAL DESIGN WITH DYNAMIC BUTTON TEXT -->
            <div class="mb-12">
                <Button
                    @click="openVideoModal"
                    size="lg"
                    class="group relative transform rounded-full border-2 border-white/30 bg-white/10 px-8 py-6 text-lg font-semibold text-white shadow-2xl backdrop-blur-sm transition-all duration-300 hover:scale-105 hover:border-white/50 hover:bg-white/20 hover:text-white hover:shadow-white/25"
                >
                    <!-- Play Icon with Animation - ✅ KEEPING ORIGINAL ANIMATION -->
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <!-- Pulsing ring animation - ✅ KEEPING ORIGINAL -->
                            <div class="absolute inset-0 animate-ping rounded-full bg-white/30"></div>
                            <div class="relative rounded-full bg-white/20 p-3 transition-colors duration-300 group-hover:bg-white/30">
                                <Play class="ml-0.5 h-6 w-6 fill-white text-white" />
                            </div>
                        </div>
                        <!-- Dynamic button text -->
                        <span>{{ currentWelcome.buttonText }}</span>
                    </div>
                </Button>
            </div>
        </div>
    </section>

    <!-- Video Modal - ✅ KEEPING EXACT ORIGINAL DESIGN -->
    <Teleport to="body">
        <div v-if="isVideoModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click="closeVideoModal">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>

            <!-- Modal Content -->
            <div class="relative mx-auto w-full max-w-5xl overflow-hidden rounded-lg bg-black shadow-2xl" @click.stop>
                <!-- Close Button -->
                <Button
                    @click="closeVideoModal"
                    variant="ghost"
                    size="sm"
                    class="absolute top-4 right-4 z-50 rounded-full border-white/20 bg-black/50 p-2 text-white hover:bg-black/70 hover:text-white"
                >
                    <X class="h-4 w-4" />
                </Button>

                <!-- Video Container -->
                <div class="relative w-full" style="padding-bottom: 56.25%">
                    <!-- 16:9 aspect ratio -->
                    <iframe
                        v-if="isVideoModalOpen"
                        class="absolute top-0 left-0 h-full w-full"
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

    <!-- ✅ Debug Info for dynamic functionality (enable for debugging) -->
    <div v-if="false" class="absolute top-4 left-4 z-20 rounded bg-black/50 p-2 text-xs text-white">
        <p><strong>Has props:</strong> {{ !!props.welcomeItems }}</p>
        <p><strong>Props length:</strong> {{ props.welcomeItems?.length || 0 }}</p>
        <p><strong>Using:</strong> {{ (props.welcomeItems?.length || 0) > 0 ? 'Dynamic Data' : 'Static Welcome' }}</p>
        <p><strong>Title:</strong> {{ currentWelcome.title }}</p>
        <p><strong>Video ID:</strong> {{ videoId }}</p>
    </div>
</template>

<style scoped>
/* ✅ KEEPING ALL YOUR ORIGINAL STYLES EXACTLY */
/* Custom animations */
@keyframes float {
    0%,
    100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
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
    0% {
        opacity: 1;
        transform: translateY(0);
    }
    100% {
        opacity: 0;
        transform: translateY(20px);
    }
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
