<script setup lang="ts">
import { computed, ref } from 'vue';

// ✅ Define interface for dynamic functionality
interface HeadlineItem {
    id?: number;
    type: 'announcement' | 'news' | 'event' | 'research' | 'achievement' | 'notice';
    text: string;
    link: string;
    priority: 'high' | 'medium' | 'low';
    isActive?: boolean;
    icon?: string;
}

// ✅ Accept props for dynamic headlines
const props = defineProps<{
    headlines?: HeadlineItem[];
}>();

// Simple marquee implementation
const marqueeRef = ref<HTMLElement>();
const isPaused = ref(false);

// ✅ Keep your original static headlines as default fallback
const staticHeadlines: HeadlineItem[] = [
    {
        id: 1,
        type: 'announcement',
        text: '🎓 Admission Open for Fall 2025 - Apply now for undergraduate and graduate programs',
        link: '/admission',
        priority: 'high',
    },
    {
        id: 2,
        type: 'news',
        text: '🏆 CSE Department wins National Programming Contest 2025',
        link: '/news/programming-contest-2025',
        priority: 'medium',
    },
    {
        id: 3,
        type: 'event',
        text: '📅 International Conference on AI & Machine Learning - March 15-17, 2025',
        link: '/events/ai-conference-2025',
        priority: 'high',
    },
    {
        id: 4,
        type: 'research',
        text: '🔬 New Research Lab inaugurated for Cybersecurity and Blockchain Technology',
        link: '/research/cybersecurity-lab',
        priority: 'medium',
    },
    {
        id: 5,
        type: 'achievement',
        text: '🌟 Dr. Rahman receives Best Faculty Award for outstanding research contribution',
        link: '/faculty/dr-rahman-award',
        priority: 'medium',
    },
    {
        id: 6,
        type: 'announcement',
        text: '📚 New Digital Library resources available - Access 10,000+ technical journals',
        link: '/library/digital-resources',
        priority: 'low',
    },
];

// ✅ Use provided headlines or fallback to static data - DYNAMIC FUNCTIONALITY
const headlines = computed(() => {
    if (props.headlines && props.headlines.length > 0) {
        // Only show active headlines, add defaults for missing fields
        return props.headlines
            .filter((h) => h.isActive !== false)
            .map((headline, index) => ({
                id: headline.id || index + 1,
                type: headline.type || 'announcement',
                text: headline.text || 'No headline text',
                link: headline.link || '#',
                priority: headline.priority || 'medium',
                icon: headline.icon || getDefaultIcon(headline.type || 'announcement'),
            }));
    }
    return staticHeadlines;
});

// Get default icon based on type
const getDefaultIcon = (type: string): string => {
    const iconMap: Record<string, string> = {
        announcement: '📢',
        news: '📰',
        event: '📅',
        research: '🔬',
        achievement: '🏆',
        notice: '📋',
    };
    return iconMap[type] || '📢';
};

// Filter high priority headlines for main marquee
const priorityHeadlines = computed(() => headlines.value.filter((h: HeadlineItem) => h.priority === 'high'));
const allHeadlines = computed(() => headlines.value);

// ✅ Debug logging for dynamic functionality
console.log('HeadlineMarquee - Props headlines:', props.headlines);
console.log('HeadlineMarquee - Computed headlines:', headlines.value);
</script>

<template>
    <!-- ✅ KEEPING EXACT ORIGINAL DESIGN AND STYLING -->
    <div class="border-b bg-[hsl(var(--quaternary))] py-3 text-white shadow-lg">
        <div class="mx-auto max-w-7xl px-4">
            <!-- Breaking News Label - ORIGINAL DESIGN -->
            <div class="flex items-center">
                <div class="mr-4 flex-shrink-0">
                    <span class="inline-flex animate-pulse items-center rounded-full bg-red-600 px-3 py-1 text-xs font-bold text-white">
                        <span class="mr-2 h-2 w-2 animate-ping rounded-full bg-white"></span>
                        LATEST
                    </span>
                </div>

                <!-- Main Marquee - ORIGINAL DESIGN WITH DYNAMIC DATA -->
                <div class="flex-1 overflow-hidden">
                    <div ref="marqueeRef" class="marquee-container" @mouseenter="isPaused = true" @mouseleave="isPaused = false">
                        <div class="marquee-content text-sm md:text-base" :class="{ paused: isPaused }">
                            <div class="flex items-center space-x-8">
                                <div v-for="headline in allHeadlines" :key="headline.id" class="flex items-center space-x-2 whitespace-nowrap">
                                    <a
                                        :href="headline.link"
                                        class="font-medium text-[hsl(var(--secondary))] transition-colors duration-200 hover:text-[hsl(var(--primary))]"
                                    >
                                        {{ headline.text }}
                                    </a>
                                    <span class="mx-4 text-blue-300">•</span>
                                </div>
                                <!-- Duplicate content for seamless loop -->
                                <div
                                    v-for="headline in allHeadlines"
                                    :key="`duplicate-${headline.id}`"
                                    class="flex items-center space-x-2 whitespace-nowrap"
                                >
                                    <a
                                        :href="headline.link"
                                        class="font-medium transition-colors duration-200 hover:text-blue-200"
                                        :class="{
                                            'text-yellow-300': headline.priority === 'high',
                                            'text-green-300': headline.priority === 'medium',
                                            'text-blue-100': headline.priority === 'low',
                                        }"
                                    >
                                        {{ headline.text }}
                                    </a>
                                    <span class="mx-4 text-blue-300">•</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions - ORIGINAL DESIGN -->
                <div class="ml-4 hidden flex-shrink-0 items-center space-x-2 md:flex">
                    <a
                        href="/notices"
                        class="rounded-md bg-[hsl(var(--secondary))] px-3 py-1 text-xs font-medium transition-colors duration-200 hover:bg-[hsl(var(--primary))]"
                    >
                        All Notices
                    </a>
                    <a
                        href="/news"
                        class="rounded-md bg-[hsl(var(--secondary))] px-3 py-1 text-xs font-medium transition-colors duration-200 hover:bg-[hsl(var(--primary))]"
                    >
                        All News
                    </a>
                </div>
            </div>
        </div>

        <!-- ✅ Debug Info for dynamic functionality (enable for debugging) -->
        <div v-if="false" class="absolute top-4 right-4 z-50 rounded bg-black/70 p-2 text-xs text-white">
            <div>Headlines count: {{ headlines.length }}</div>
            <div>Has props: {{ !!props.headlines }}</div>
            <div>Props length: {{ props.headlines?.length || 0 }}</div>
            <div>Using: {{ (props.headlines?.length || 0) > 0 ? 'Dynamic Data' : 'Static Headlines' }}</div>
        </div>
    </div>
</template>

<style scoped>
/* ✅ KEEPING ALL YOUR ORIGINAL STYLES EXACTLY */
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
    0%,
    100% {
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

/* ✅ Additional styles for better dynamic data handling */
/* Priority-based colors for dynamic headlines */
.text-yellow-300 {
    color: rgb(253 224 71);
}

.text-green-300 {
    color: rgb(134 239 172);
}

.text-blue-100 {
    color: rgb(219 234 254);
}

/* Ensure dynamic content displays properly */
.marquee-content div {
    display: flex;
    align-items: center;
}

/* Loading state for when no headlines */
.marquee-container:has(.marquee-content:empty)::after {
    content: 'Loading headlines...';
    color: rgba(255, 255, 255, 0.6);
    font-style: italic;
    display: block;
    text-align: center;
    padding: 0.5rem;
}

/* Accessibility for dynamic content */
@media (prefers-reduced-motion: reduce) {
    .marquee-content {
        animation: none;
        transform: none;
        justify-content: flex-start;
        overflow-x: auto;
    }

    .marquee-container {
        overflow-x: auto;
    }
}
</style>
