<script setup lang="ts">
import { ChevronRight, ExternalLink, Mail, MapPin, Phone, User } from 'lucide-vue-next';
import { computed, ref } from 'vue';

// Define message interface
interface MessageFromItem {
    id?: number;
    name: string;
    title: string;
    message: string;
    image: string;
    fallbackGradient: string;
    designation?: string;
    department?: string;
    email?: string;
    phone?: string;
    fax?: string;
    office?: string;
    address?: string;
    officeTime?: string;
    experience?: string;
    qualifications?: string[];
    specializations?: string[];
    achievements?: string[];
    isActive: boolean;
    displayOrder: number;
    type: 'vice_chancellor' | 'pro_vice_chancellor' | 'dean' | 'director' | 'chairman' | 'other';
}

// Accept props for dynamic messages
const props = defineProps<{
    messageFromItems?: MessageFromItem[];
}>();

// ✅ FIXED: Image URL processor for broken/invalid URLs
const getValidImageUrl = (imageUrl: string): string | null => {
    if (!imageUrl || imageUrl.trim() === '' || imageUrl.includes('default')) {
        return null;
    }

    // ✅ Fix broken ImgBB URLs
    if (imageUrl.includes('ibb.co.com') || imageUrl.includes('ibb.co/')) {
        // Extract image ID and fix the URL
        let imageId = '';

        // Handle different ImgBB URL formats
        if (imageUrl.includes('ibb.co.com/')) {
            imageId = imageUrl.split('/').pop() || '';
        } else if (imageUrl.includes('ibb.co/')) {
            imageId = imageUrl.split('/').pop() || '';
        }

        if (imageId) {
            // Try different image formats
            return `https://i.ibb.co/${imageId}/image.png`; // or try .jpg
        }
    }

    // ✅ Fix other image hosting services
    if (imageUrl.includes('imgur.com') && !imageUrl.includes('i.imgur.com')) {
        const imageId = imageUrl.split('/').pop();
        return `https://i.imgur.com/${imageId}.jpg`;
    }

    // Return original URL if it's already a direct image URL
    if (imageUrl.match(/\.(jpg|jpeg|png|webp|gif)$/i) || imageUrl.includes('/images/')) {
        return imageUrl;
    }

    return null;
};

// Default message fallback - current Vice Chancellor data
const defaultMessages: MessageFromItem[] = [
    {
        id: 1,
        name: 'Professor Dr. Md. Anwarul Azim Akhand',
        title: 'Vice-Chancellor',
        message: `Welcome to Mawlana Bhashani Science and Technology University (MBSTU), a leading center for academic excellence and research in Bangladesh. MBSTU is named after the great visionary and advocate for the underprivileged, Mawlana Abdul Hamid Khan Bhashani. His legacy of dedication to justice, education, and empowerment continues to inspire our mission to create future leaders through scientific innovation, academic rigor, and social commitment.

At MBSTU, we believe that education is not just a means to personal advancement, but a tool for transforming society. Our dynamic curriculum, state-of-the-art facilities, and dedicated faculty aim to nurture creativity, critical thinking, and problem-solving skills among our students. We strive to ensure that our graduates are not only equipped with technical knowledge but also imbued with a sense of responsibility towards the community and the nation.

Our focus on research and innovation encourages students to explore new horizons in science and technology, driving progress in key areas such as computer science, information technology, biotechnology, criminology, engineering, environmental sciences, and many more. Through our diverse programs, we aim to foster a spirit of inquiry leading to solutions for the challenges of tomorrow.

As we grow and evolve, we remain committed to our core values of inclusivity, integrity, and academic excellence. We are proud of our vibrant campus community, where students from different backgrounds come together to share ideas and experiences in an environment of mutual respect and collaboration.

I invite you to explore our website to learn more about the opportunities and achievements that make MBSTU a place where dreams are nurtured and futures are shaped. Whether you are a prospective student, a parent, or a visitor, I welcome you to be a part of our journey as we continue to make strides in science, technology, and societal development.`,
        image: '/images/faculty/vc.jpg',
        fallbackGradient: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)',
        designation: 'Vice-Chancellor',
        department: 'Mawlana Bhashani Science and Technology University',
        email: 'vc@mbstu.ac.bd',
        phone: '+880921 55399',
        fax: '+880921 55400',
        office: 'Vice Chancellor Office, MBSTU',
        address: 'Santosh, Tangail - 1902',
        officeTime: 'Saturday - Wednesday: 9.00AM - 5.00PM',
        experience: '25+ years',
        qualifications: [
            'Ph.D. in Computer Science and Engineering',
            'M.Sc. in Computer Science and Engineering',
            'B.Sc. in Computer Science and Engineering',
        ],
        specializations: ['Artificial Intelligence', 'Machine Learning', 'Computer Vision', 'Pattern Recognition', 'Evolutionary Computing'],
        achievements: [
            'Leading MBSTU as the 12th oldest public university in Bangladesh',
            'Pioneer in science and technology education',
            'Advocate for research and innovation',
            'Champion of inclusive and quality education',
        ],
        isActive: true,
        displayOrder: 1,
        type: 'vice_chancellor',
    },
];

// ✅ FIXED: Process messages with proper image URL handling
const messages = computed(() => {
    if (props.messageFromItems && props.messageFromItems.length > 0) {
        return props.messageFromItems
            .filter((item) => item.isActive)
            .sort((a, b) => a.displayOrder - b.displayOrder)
            .map((item, index) => ({
                id: item.id || index + 1,
                name: item.name || 'Unknown Official',
                title: item.title || 'Official',
                message: item.message || 'No message available',
                image: getValidImageUrl(item.image), // ✅ FIXED: Process image URL
                fallbackGradient: item.fallbackGradient || 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)',
                designation: item.designation || item.title,
                department: item.department || 'MBSTU',
                email: item.email || '',
                phone: item.phone || '',
                fax: item.fax || '',
                office: item.office || '',
                address: item.address || '',
                officeTime: item.officeTime || '',
                experience: item.experience || '',
                qualifications: item.qualifications || [],
                specializations: item.specializations || [],
                achievements: item.achievements || [],
                isActive: item.isActive !== false,
                displayOrder: item.displayOrder || index + 1,
                type: item.type || 'other',
            }));
    }
    return defaultMessages.filter((item) => item.isActive);
});

// Current message for display (for multiple messages)
const currentMessageIndex = ref(0);
const currentMessage = computed(() => {
    if (messages.value.length === 0) return null;
    return messages.value[currentMessageIndex.value];
});

// Expandable message state
const isExpanded = ref(false);

const toggleMessage = () => {
    isExpanded.value = !isExpanded.value;
};

const nextMessage = () => {
    if (messages.value.length > 1) {
        currentMessageIndex.value = (currentMessageIndex.value + 1) % messages.value.length;
    }
};

const previousMessage = () => {
    if (messages.value.length > 1) {
        currentMessageIndex.value = currentMessageIndex.value > 0 ? currentMessageIndex.value - 1 : messages.value.length - 1;
    }
};

// Split message into paragraphs for better display
const getMessageParagraphs = (message: string) => {
    return message.split('\n\n').filter((p) => p.trim().length > 0);
};

// Debug logging
console.log('MessageFrom2 - Props messageFromItems:', props.messageFromItems);
console.log('MessageFrom2 - Computed messages:', messages.value);
console.log('MessageFrom2 - Current message:', currentMessage.value);
</script>

<template>
    <section class="container pt-24">
        <!-- No Messages State -->
        <div v-if="!currentMessage" class="py-12 text-center">
            <div class="mx-auto max-w-md">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700">
                    <User class="h-8 w-8 text-gray-400" />
                </div>
                <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">No Messages Available</h3>
                <p class="text-gray-500 dark:text-gray-400">There are currently no active messages from leadership to display.</p>
            </div>
        </div>

        <!-- Messages Display -->
        <div v-else>
            <!-- Section Header -->
            <div class="mb-12 text-center">
                <h2 class="mb-4 text-3xl font-bold text-[hsl(var(--secondary))] md:text-4xl">
                    {{
                        currentMessage.title === 'Vice-Chancellor' || currentMessage.title === 'Vice Chancellor'
                            ? 'Message from Vice-Chancellor'
                            : `Message from ${currentMessage.title}`
                    }}
                </h2>
                <p class="mx-auto max-w-2xl text-lg text-gray-600">
                    A warm welcome from the leadership of Mawlana Bhashani Science and Technology University.
                </p>
            </div>

            <!-- Multiple Messages Navigation -->
            <div v-if="messages.length > 1" class="mb-8 flex items-center justify-center space-x-4">
                <button
                    @click="previousMessage"
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 transition-colors hover:bg-[hsl(var(--primary))] hover:text-white"
                    aria-label="Previous message"
                >
                    ←
                </button>

                <div class="flex space-x-2">
                    <button
                        v-for="(_, index) in messages"
                        :key="index"
                        @click="currentMessageIndex = index"
                        :class="{
                            'w-8 bg-[hsl(var(--primary))]': index === currentMessageIndex,
                            'w-3 bg-gray-300': index !== currentMessageIndex,
                        }"
                        class="h-3 rounded-full transition-all duration-300"
                        :aria-label="`Show message from ${messages[index].name}`"
                    />
                </div>

                <button
                    @click="nextMessage"
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 transition-colors hover:bg-[hsl(var(--primary))] hover:text-white"
                    aria-label="Next message"
                >
                    →
                </button>
            </div>

            <!-- Main Message Card - ✅ KEEPING ORIGINAL DESIGN -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-gray-50 shadow-sm">
                <div class="lg:flex">
                    <!-- Left Side - Official Photo and Info -->
                    <div class="border-r border-gray-200 bg-white p-8 lg:w-1/3">
                        <!-- Official Photo - ✅ ULTIMATE FIXED VERSION -->
                        <div class="mb-6 text-center">
                            <div class="mx-auto h-32 w-32 overflow-hidden rounded-full border-4 border-gray-200 bg-white shadow-md">
                                <!-- ✅ FIXED: Always white background, conditional image -->
                                <div class="relative h-full w-full bg-white">
                                    <!-- Image overlay (only shows if image loads successfully) -->
                                    <img
                                        v-if="currentMessage.image"
                                        :src="currentMessage.image"
                                        :alt="currentMessage.name"
                                        class="absolute inset-0 z-10 h-full w-full object-cover"
                                        @error="
                                            console.log('Image failed to load:', currentMessage.image);
                                            $event.target.style.display = 'none';
                                        "
                                        @load="console.log('Image loaded successfully:', currentMessage.image)"
                                    />

                                    <!-- White background with User icon (always visible as fallback) -->
                                    <div class="absolute inset-0 z-5 flex h-full w-full items-center justify-center bg-white">
                                        <User class="h-16 w-16 text-gray-400" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Official Details -->
                        <div class="text-center">
                            <h3 class="mb-2 text-xl font-bold text-[hsl(var(--secondary))]">{{ currentMessage.name }}</h3>
                            <p class="mb-1 text-lg font-medium text-gray-600">{{ currentMessage.title }}</p>
                            <p class="mb-6 text-sm text-gray-500">{{ currentMessage.department }}</p>

                            <!-- Contact Information -->
                            <div class="space-y-2 text-sm text-gray-600">
                                <div v-if="currentMessage.email" class="flex items-center justify-center">
                                    <Mail class="mr-2 h-4 w-4 text-gray-400" />
                                    <span>{{ currentMessage.email }}</span>
                                </div>
                                <div v-if="currentMessage.phone" class="flex items-center justify-center">
                                    <Phone class="mr-2 h-4 w-4 text-gray-400" />
                                    <span>{{ currentMessage.phone }}</span>
                                </div>
                                <div v-if="currentMessage.office" class="flex items-center justify-center">
                                    <MapPin class="mr-2 h-4 w-4 text-gray-400" />
                                    <span>{{ currentMessage.office }}</span>
                                </div>
                            </div>

                            <!-- Experience Badge -->
                            <div
                                v-if="currentMessage.experience"
                                class="mt-6 inline-block rounded-full bg-[hsl(var(--tertiary))] px-4 py-2 text-sm font-medium text-[hsl(var(--primary))]"
                            >
                                {{ currentMessage.experience }} Experience
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Message Content -->
                    <div class="bg-white p-8 lg:w-2/3 lg:p-10">
                        <!-- Greeting -->
                        <div class="mb-6">
                            <h4 class="mb-4 text-2xl font-bold text-[hsl(var(--secondary))]">Message from {{ currentMessage.title }}</h4>
                        </div>

                        <!-- Message Content -->
                        <div class="prose prose-lg max-w-none">
                            <div class="space-y-4 leading-relaxed text-gray-600">
                                <p class="text-lg">
                                    {{ getMessageParagraphs(currentMessage.message)[0] }}
                                </p>

                                <!-- Expandable content -->
                                <div
                                    v-if="isExpanded && getMessageParagraphs(currentMessage.message).length > 1"
                                    class="space-y-4 transition-all duration-500 ease-in-out"
                                >
                                    <p
                                        v-for="(paragraph, index) in getMessageParagraphs(currentMessage.message).slice(1)"
                                        :key="index"
                                        class="text-gray-600"
                                    >
                                        {{ paragraph }}
                                    </p>
                                </div>

                                <!-- Read More/Less Button -->
                                <button
                                    v-if="getMessageParagraphs(currentMessage.message).length > 1"
                                    @click="toggleMessage"
                                    class="inline-flex items-center text-sm font-medium text-[hsl(var(--secondary))] transition-colors duration-200 hover:text-[hsl(var(--primary))]"
                                >
                                    {{ isExpanded ? 'Read Less' : 'Read More' }}
                                    <ChevronRight :class="['ml-1 h-4 w-4 transition-transform duration-200', isExpanded ? 'rotate-90' : '']" />
                                </button>
                            </div>
                        </div>

                        <!-- Closing Message -->
                        <div v-if="isExpanded" class="mt-6 rounded-lg border-l-4 border-blue-300 bg-blue-50 p-4">
                            <p class="mb-3 font-medium text-gray-700 italic">
                                "Together, let us build a brighter future through science and technology."
                            </p>
                            <p class="text-sm font-semibold text-gray-600">— {{ currentMessage.name }}</p>
                        </div>

                        <!-- Call to Action -->
                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a
                                :href="currentMessage.type === 'vice_chancellor' ? '/vice-chancellor' : `/${currentMessage.type.replace('_', '-')}`"
                                class="inline-flex items-center justify-center rounded-lg bg-[hsl(var(--secondary))] px-5 py-2.5 font-medium text-white transition-colors duration-200 hover:bg-[hsl(var(--primary))]"
                            >
                                Learn More
                                <ExternalLink class="ml-2 h-4 w-4" />
                            </a>
                            <a
                                :href="`/about/message-${currentMessage.type.replace('_', '-')}`"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-2.5 font-medium text-gray-700 transition-colors duration-200 hover:bg-gray-50"
                            >
                                Full Message
                                <ChevronRight class="ml-2 h-4 w-4" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information Cards -->
            <div v-if="isExpanded" class="mt-8 grid gap-6 md:grid-cols-2">
                <!-- Qualifications -->
                <div v-if="currentMessage.qualifications?.length" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h4 class="mb-4 flex items-center text-lg font-bold text-[hsl(var(--secondary))]">
                        <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-sm text-[hsl(var(--secondary))]">🎓</div>
                        Academic Qualifications
                    </h4>
                    <ul class="space-y-2">
                        <li
                            v-for="qualification in currentMessage.qualifications"
                            :key="qualification"
                            class="flex items-start text-sm text-gray-600"
                        >
                            <span class="mt-2 mr-3 h-2 w-2 flex-shrink-0 rounded-full bg-blue-400"></span>
                            {{ qualification }}
                        </li>
                    </ul>
                </div>

                <!-- Specializations -->
                <div
                    v-if="currentMessage.specializations?.length || currentMessage.achievements?.length"
                    class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm"
                >
                    <h4 class="mb-4 flex items-center text-lg font-bold text-[hsl(var(--secondary))]">
                        <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-green-50 text-sm text-green-600">🔬</div>
                        {{ currentMessage.specializations?.length ? 'Research Specializations' : 'Key Achievements' }}
                    </h4>

                    <!-- Specializations -->
                    <div v-if="currentMessage.specializations?.length" class="mb-6 flex flex-wrap gap-2">
                        <span
                            v-for="specialization in currentMessage.specializations"
                            :key="specialization"
                            class="rounded-full border border-green-200 bg-green-50 px-3 py-1 text-xs font-medium text-green-700"
                        >
                            {{ specialization }}
                        </span>
                    </div>

                    <!-- Achievements -->
                    <div v-if="currentMessage.achievements?.length">
                        <h5 class="mb-3 text-sm font-semibold text-[hsl(var(--secondary))]">Key Achievements:</h5>
                        <ul class="space-y-1 text-xs text-gray-600">
                            <li v-for="achievement in currentMessage.achievements" :key="achievement" class="flex items-start">
                                <span class="mr-2 text-green-500">✓</span>
                                {{ achievement }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- ✅ Debug section to verify image processing -->
        <!-- <div v-if="true" class="mt-8 rounded border border-yellow-300 bg-yellow-100 p-4 text-xs">
            <p><strong>Original Image URL:</strong> {{ props.messageFromItems?.[0]?.image }}</p>
            <p><strong>Processed Image URL:</strong> {{ currentMessage?.image }}</p>
            <p><strong>Image will show:</strong> {{ currentMessage?.image ? 'YES' : 'NO (showing User icon)' }}</p>
            <p><strong>Background:</strong> {{ currentMessage?.image ? 'White with image' : 'White with icon' }}</p>
        </div> -->
    </section>
</template>

<style scoped>
/* ✅ FIXED: Proper image layering and white background */
.relative img {
    background-color: white;
}

/* Ensure white background always shows */
.bg-white {
    background-color: white !important;
}

/* Remove any gradient backgrounds from photo container */
.rounded-full {
    background: white !important;
}

/* When image loads successfully, it covers the icon */
img[style*='display: none'] {
    display: none !important;
}

/* Smooth transitions for expandable content */
.transition-all {
    transition: all 0.5s ease-in-out;
}

.prose p {
    margin-bottom: 1rem;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .lg\:flex {
        flex-direction: column;
    }

    .lg\:w-1\/3,
    .lg\:w-2\/3 {
        width: 100%;
    }
}

/* Enhanced transitions for message switching */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
    .transition-all,
    .transition-colors,
    .transition-transform {
        transition: none;
    }
}

/* High contrast support */
@media (prefers-contrast: high) {
    .text-gray-600,
    .text-gray-700 {
        color: #000;
    }

    .bg-gray-50 {
        background-color: #f9f9f9;
        border: 1px solid #ccc;
    }
}

/* Force white background for photo area */
.shadow-md {
    background-color: white !important;
}

/* Override any conflicting styles */
[style*='background:'] .bg-white {
    background: white !important;
}
</style>
