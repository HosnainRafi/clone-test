<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// Define interfaces
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

interface PageProps {
    siteData: any;
    heroSlides: HeroSlide[];
    siteId: number | null;
}

// Receive props from Laravel
const props = defineProps<PageProps>();

// Extract siteId for easier access
const { siteId } = props;

// State management - renamed to avoid duplicate key issue
const heroSlidesData = ref<HeroSlide[]>([]);
const isSaving = ref(false);
const message = ref('');
const messageType = ref<'success' | 'error' | ''>('');

// Initialize hero slides from props
const initializeHeroSlides = () => {
    if (props.heroSlides && props.heroSlides.length > 0) {
        // Add IDs to existing items if they don't have them
        heroSlidesData.value = props.heroSlides.map((slide, index) => ({
            ...slide,
            id: slide.id || Date.now() + index,
        }));
    } else {
        // Default hero slides if none exist
        heroSlidesData.value = [
            {
                id: 1,
                title: 'Welcome to MBSTU',
                subtitle: 'Mawlana Bhashani Science and Technology University',
                description:
                    'A leading public university in Bangladesh dedicated to advancing science, technology, and innovation for national development.',
                image: '/images/carousel/mbstu-campus.jpg',
                fallbackGradient: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%)',
                ctaText: 'About MBSTU',
                ctaLink: '/about',
                secondaryCta: {
                    text: 'Admission',
                    link: '/admission',
                },
            },
            {
                id: 2,
                title: 'Academic Excellence',
                subtitle: '7 Faculties & 33 Departments',
                description:
                    'Offering undergraduate and graduate programs across diverse fields including Engineering, Science, Agriculture, and Social Sciences.',
                image: '/images/carousel/academic-building.jpg',
                fallbackGradient: 'linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%)',
                ctaText: 'Explore Programs',
                ctaLink: '/academic',
                secondaryCta: null,
            },
            {
                id: 3,
                title: 'Research & Innovation',
                subtitle: 'Advancing Knowledge',
                description:
                    'Pioneering research in emerging technologies, sustainable development, and scientific breakthroughs that shape the future.',
                image: '/images/carousel/research-lab.jpg',
                fallbackGradient: 'linear-gradient(135deg, #7c3aed 0%, #8b5cf6 50%, #a78bfa 100%)',
                ctaText: 'View Research',
                ctaLink: '/research',
                secondaryCta: null,
            },
        ];
    }
};

// Initialize on component mount
initializeHeroSlides();

// Computed properties
const hasUnsavedChanges = computed(() => {
    return JSON.stringify(heroSlidesData.value) !== JSON.stringify(props.heroSlides);
});

const isValidConfiguration = computed(() => {
    try {
        // Check if all slides have required fields
        for (const slide of heroSlidesData.value) {
            if (!slide.title || !slide.subtitle || !slide.description || !slide.ctaText || !slide.ctaLink) {
                return false;
            }
        }

        // Try to serialize to JSON
        JSON.stringify(heroSlidesData.value);
        return true;
    } catch {
        return false;
    }
});

// Methods
const addHeroSlide = () => {
    const newSlide: HeroSlide = {
        id: Date.now(),
        title: 'New Slide Title',
        subtitle: 'Slide Subtitle',
        description: 'Description for the new slide.',
        image: '/images/carousel/default.jpg',
        fallbackGradient: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%)',
        ctaText: 'Learn More',
        ctaLink: '#',
        secondaryCta: null,
    };
    heroSlidesData.value.push(newSlide);
};

const removeHeroSlide = (id: number) => {
    const index = heroSlidesData.value.findIndex((slide) => slide.id === id);
    if (index > -1) {
        heroSlidesData.value.splice(index, 1);
    }
};

const addSecondaryCta = (slideId: number) => {
    const slide = heroSlidesData.value.find((slide) => slide.id === slideId);
    if (slide) {
        slide.secondaryCta = {
            text: 'Secondary Action',
            link: '#',
        };
    }
};

const removeSecondaryCta = (slideId: number) => {
    const slide = heroSlidesData.value.find((slide) => slide.id === slideId);
    if (slide) {
        slide.secondaryCta = null;
    }
};

const validateAndSave = async () => {
    console.log('validateAndSave called', {
        siteId: props.siteId,
        isValid: isValidConfiguration.value,
        slidesCount: heroSlidesData.value.length,
    });

    if (!props.siteId) {
        showMessage('Site ID is required to save configuration', 'error');
        return;
    }

    if (!isValidConfiguration.value) {
        showMessage('Please fix validation errors before saving', 'error');
        return;
    }

    isSaving.value = true;

    try {
        // Prepare data for saving (remove IDs as they're only for frontend)
        const dataToSave = heroSlidesData.value.map((slide) => ({
            title: slide.title,
            subtitle: slide.subtitle,
            description: slide.description,
            image: slide.image,
            fallbackGradient: slide.fallbackGradient,
            ctaText: slide.ctaText,
            ctaLink: slide.ctaLink,
            secondaryCta: slide.secondaryCta,
        }));

        console.log('Sending save request', {
            url: '/hero-carousel/save',
            data: { heroSlides: dataToSave, siteId: props.siteId },
        });

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        console.log('CSRF Token:', csrfToken);

        const response = await fetch('/hero-carousel/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                Accept: 'application/json',
            },
            body: JSON.stringify({
                heroSlides: dataToSave,
                siteId: props.siteId,
            }),
        });

        console.log('Response status:', response.status);

        const responseText = await response.text();
        console.log('Raw response:', responseText);

        let result;
        try {
            result = JSON.parse(responseText);
        } catch (parseError) {
            console.error('Failed to parse response as JSON:', parseError);
            showMessage('Server returned invalid response: ' + responseText.substring(0, 100), 'error');
            return;
        }

        console.log('Parsed result:', result);

        if (response.ok && result.success) {
            showMessage(result.message, 'success');
            // Optionally reload the page to get fresh data
            setTimeout(() => {
                router.reload();
            }, 1500);
        } else {
            showMessage(result.message || 'Save failed with status: ' + response.status, 'error');
        }
    } catch (err) {
        console.error('Save error:', err);
        showMessage('Network error: ' + (err instanceof Error ? err.message : 'Unknown error'), 'error');
    } finally {
        isSaving.value = false;
    }
};

const showMessage = (msg: string, type: 'success' | 'error') => {
    message.value = msg;
    messageType.value = type;
    setTimeout(() => {
        message.value = '';
        messageType.value = '';
    }, 5000);
};

const resetToOriginal = () => {
    initializeHeroSlides();
    showMessage('Configuration reset to original state', 'success');
};
</script>

<template>
    <DefaultLayout>
        <div
            class="shadow-default rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 sm:px-7.5 xl:pb-1 dark:border-strokedark dark:bg-boxdark"
        >
            <!-- Header with Status -->
            <div class="mb-6">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h4 class="text-xl font-semibold text-black dark:text-white">Hero Carousel Management</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Site ID: {{ siteId || 'Not Available' }} | Status:
                            <span :class="isValidConfiguration ? 'text-success' : 'text-danger'">
                                {{ isValidConfiguration ? 'Valid' : 'Invalid Configuration' }}
                            </span>
                            <span v-if="hasUnsavedChanges" class="ml-2 text-warning">• Unsaved Changes</span>
                        </p>
                        <!-- Debug Info -->
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                            Debug: Valid={{ isValidConfiguration }} | Saving={{ isSaving }} | SiteID={{ !!siteId }} | Button Enabled={{
                                isValidConfiguration && !isSaving && !!siteId
                            }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button
                            @click="resetToOriginal"
                            v-if="hasUnsavedChanges"
                            class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-secondary px-4 py-2 text-center font-medium text-white"
                        >
                            Reset
                        </button>
                        <button
                            @click="addHeroSlide"
                            class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-center font-medium text-white"
                        >
                            Add Hero Slide
                        </button>
                    </div>
                </div>

                <!-- Status Messages -->
                <div
                    v-if="message"
                    :class="{
                        'border-success bg-success/10 text-success': messageType === 'success',
                        'border-danger bg-danger/10 text-danger': messageType === 'error',
                    }"
                    class="mb-4 rounded-md border px-4 py-3"
                >
                    {{ message }}
                </div>
            </div>

            <!-- Hero Slides -->
            <div class="space-y-6">
                <div v-if="heroSlidesData.length === 0" class="py-8 text-center text-gray-500 dark:text-gray-400">
                    No hero slides configured. Click "Add Hero Slide" to get started.
                </div>

                <div
                    v-for="(slide, slideIndex) in heroSlidesData"
                    :key="slide.id"
                    class="rounded-sm border border-stroke bg-gray-50 p-4 dark:bg-meta-4"
                >
                    <div class="mb-4 flex items-start justify-between">
                        <h5 class="text-lg font-medium text-black dark:text-white">Slide {{ slideIndex + 1 }}</h5>
                        <button
                            @click="removeHeroSlide(slide.id!)"
                            class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-danger px-3 py-2 text-center font-medium text-white"
                        >
                            Remove Slide
                        </button>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <!-- Left Column: Basic Info -->
                        <div class="space-y-4">
                            <div>
                                <label class="mb-2.5 block font-medium text-black dark:text-white"> Slide Title * </label>
                                <input
                                    v-model="slide.title"
                                    type="text"
                                    :class="{
                                        'border-danger': !slide.title,
                                        'border-stroke': slide.title,
                                    }"
                                    class="w-full rounded border-[1.5px] bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                    placeholder="Enter slide title"
                                />
                                <p v-if="!slide.title" class="mt-1 text-sm text-danger">Title is required</p>
                            </div>

                            <div>
                                <label class="mb-2.5 block font-medium text-black dark:text-white"> Subtitle * </label>
                                <input
                                    v-model="slide.subtitle"
                                    type="text"
                                    :class="{
                                        'border-danger': !slide.subtitle,
                                        'border-stroke': slide.subtitle,
                                    }"
                                    class="w-full rounded border-[1.5px] bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                    placeholder="Enter slide subtitle"
                                />
                                <p v-if="!slide.subtitle" class="mt-1 text-sm text-danger">Subtitle is required</p>
                            </div>

                            <div>
                                <label class="mb-2.5 block font-medium text-black dark:text-white"> Description * </label>
                                <textarea
                                    v-model="slide.description"
                                    rows="4"
                                    :class="{
                                        'border-danger': !slide.description,
                                        'border-stroke': slide.description,
                                    }"
                                    class="w-full rounded border-[1.5px] bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                    placeholder="Enter slide description"
                                ></textarea>
                                <p v-if="!slide.description" class="mt-1 text-sm text-danger">Description is required</p>
                            </div>

                            <div>
                                <label class="mb-2.5 block font-medium text-black dark:text-white"> Background Image URL </label>
                                <input
                                    v-model="slide.image"
                                    type="text"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                    placeholder="/images/carousel/slide.jpg"
                                />
                            </div>

                            <div>
                                <label class="mb-2.5 block font-medium text-black dark:text-white"> Fallback Gradient </label>
                                <input
                                    v-model="slide.fallbackGradient"
                                    type="text"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                    placeholder="linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)"
                                />
                            </div>
                        </div>

                        <!-- Right Column: CTA & Actions -->
                        <div class="space-y-4">
                            <div>
                                <label class="mb-2.5 block font-medium text-black dark:text-white"> Primary CTA Text * </label>
                                <input
                                    v-model="slide.ctaText"
                                    type="text"
                                    :class="{
                                        'border-danger': !slide.ctaText,
                                        'border-stroke': slide.ctaText,
                                    }"
                                    class="w-full rounded border-[1.5px] bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                    placeholder="Learn More"
                                />
                                <p v-if="!slide.ctaText" class="mt-1 text-sm text-danger">CTA text is required</p>
                            </div>

                            <div>
                                <label class="mb-2.5 block font-medium text-black dark:text-white"> Primary CTA Link * </label>
                                <input
                                    v-model="slide.ctaLink"
                                    type="text"
                                    :class="{
                                        'border-danger': !slide.ctaLink,
                                        'border-stroke': slide.ctaLink,
                                    }"
                                    class="w-full rounded border-[1.5px] bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                    placeholder="/about"
                                />
                                <p v-if="!slide.ctaLink" class="mt-1 text-sm text-danger">CTA link is required</p>
                            </div>

                            <!-- Secondary CTA -->
                            <div class="rounded border border-stroke bg-white p-3 dark:bg-boxdark">
                                <div class="mb-3 flex items-center justify-between">
                                    <h6 class="font-medium text-black dark:text-white">Secondary CTA</h6>
                                    <button
                                        v-if="!slide.secondaryCta"
                                        @click="addSecondaryCta(slide.id!)"
                                        class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-success px-3 py-1 text-sm font-medium text-white"
                                    >
                                        Add Secondary CTA
                                    </button>
                                    <button
                                        v-else
                                        @click="removeSecondaryCta(slide.id!)"
                                        class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-danger px-3 py-1 text-sm font-medium text-white"
                                    >
                                        Remove Secondary CTA
                                    </button>
                                </div>

                                <div v-if="slide.secondaryCta" class="space-y-3">
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-black dark:text-white"> Text </label>
                                        <input
                                            v-model="slide.secondaryCta.text"
                                            type="text"
                                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm transition outline-none focus:border-primary active:border-primary"
                                            placeholder="Secondary Action"
                                        />
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-black dark:text-white"> Link </label>
                                        <input
                                            v-model="slide.secondaryCta.link"
                                            type="text"
                                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm transition outline-none focus:border-primary active:border-primary"
                                            placeholder="/contact"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Actions -->
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <span v-if="!isValidConfiguration" class="text-danger"> ⚠️ Please fix validation errors before saving </span>
                    <span v-else-if="hasUnsavedChanges" class="text-warning"> 📝 You have unsaved changes </span>
                    <span v-else class="text-success"> ✅ Configuration is valid and saved </span>
                </div>

                <div class="flex gap-2">
                    <button
                        @click="validateAndSave"
                        :disabled="!isValidConfiguration || isSaving || !siteId"
                        :class="{
                            'hover:bg-opacity-90 bg-primary': isValidConfiguration && !isSaving && siteId,
                            'cursor-not-allowed bg-gray-400': !isValidConfiguration || isSaving || !siteId,
                        }"
                        class="inline-flex items-center justify-center rounded-md px-6 py-3 text-center font-medium text-white transition-colors"
                    >
                        <svg
                            v-if="isSaving"
                            class="mr-3 -ml-1 h-5 w-5 animate-spin text-white"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                        {{ isSaving ? 'Saving...' : 'Save Hero Carousel Configuration' }}
                    </button>
                </div>
            </div>

            <!-- JSON Preview (for debugging) -->
            <details class="mt-6">
                <summary class="cursor-pointer text-sm font-medium text-gray-600 hover:text-primary dark:text-gray-400">
                    View JSON Configuration
                </summary>
                <pre class="mt-2 max-h-60 overflow-auto rounded bg-gray-100 p-4 text-xs dark:bg-gray-800">{{
                    JSON.stringify(heroSlidesData, null, 2)
                }}</pre>
            </details>
        </div>
    </DefaultLayout>
</template>
