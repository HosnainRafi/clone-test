<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

// --- INTERFACES & PROPS ---
interface NewsItem {
    id: number;
    title: string;
    summary: string;
    content: string;
    image: string;
    fallbackGradient: string;
    category: string;
    author: string;
    authorRole: string;
    publishDate: string;
    readTime: string;
    views: number;
    comments: number;
    tags: string[];
    trending: boolean;
    source: string;
    sourceUrl?: string;
    isActive: boolean;
    order: number;
}

interface TechNewsData {
    sectionTitle: string;
    sectionSubtitle: string;
    isVisible: boolean;
    maxNews: number;
    showCarousel: boolean;
    autoPlay: boolean;
    autoPlayDelay: number;
    sortBy: 'date' | 'views' | 'trending';
    sortOrder: 'asc' | 'desc';
    news: NewsItem[];
}

interface PageProps {
    techNewsData: TechNewsData | null; // From Controller
    siteId: number | null;
}

const props = defineProps<PageProps>();

// --- STATE MANAGEMENT ---
// Provide a default structure if techNewsData is initially null
const defaultTechNewsData: TechNewsData = {
    sectionTitle: 'Latest Technology News & Insights',
    sectionSubtitle: 'Stay updated with the latest developments in technology, research breakthroughs, and achievements from MBSTU Computer Science & Engineering department.',
    isVisible: true,
    maxNews: 6,
    showCarousel: true,
    autoPlay: false,
    autoPlayDelay: 5000,
    sortBy: 'date',
    sortOrder: 'desc',
    news: [
        {
            id: 1,
            title: "AI Revolution in Computer Science Education: New Curriculum at MBSTU",
            summary: "MBSTU CSE Department introduces cutting-edge AI and Machine Learning courses to prepare students for the future of technology.",
            content: "The Computer Science and Engineering Department at MBSTU has announced a comprehensive curriculum overhaul that integrates artificial intelligence and machine learning across all levels of study.",
            image: "/images/news/ai-education.jpg",
            fallbackGradient: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
            category: "Education",
            author: "Dr. Rahman Ahmed",
            authorRole: "Head of Department",
            publishDate: "2024-03-15",
            readTime: "5 min",
            views: 1250,
            comments: 23,
            tags: ["AI", "Education", "Curriculum", "Future Tech"],
            trending: true,
            source: "MBSTU News",
            sourceUrl: "#",
            isActive: true,
            order: 1
        },
        {
            id: 2,
            title: "Breakthrough in Quantum Computing Research by MBSTU Faculty",
            summary: "Faculty members publish groundbreaking research on quantum algorithms that could revolutionize computational efficiency.",
            content: "A team of researchers from MBSTU's Computer Science Department has made significant progress in quantum computing algorithms, publishing their findings in top-tier international journals.",
            image: "/images/news/quantum-computing.jpg",
            fallbackGradient: "linear-gradient(135deg, #f093fb 0%, #f5576c 100%)",
            category: "Research",
            author: "Prof. Fatima Khan",
            authorRole: "Research Director",
            publishDate: "2024-03-12",
            readTime: "7 min",
            views: 892,
            comments: 15,
            tags: ["Quantum Computing", "Research", "Innovation", "Algorithms"],
            trending: true,
            source: "Tech Research Today",
            sourceUrl: "#",
            isActive: true,
            order: 2
        }
    ]
};

// Initialize data with proper formatting
const initializeNewsData = () => {
    const data = JSON.parse(JSON.stringify(props.techNewsData || defaultTechNewsData));
    // Ensure arrays are properly formatted
    data.news.forEach((item: any) => {
        if (!Array.isArray(item.tags)) {
            item.tags = [];
        }
        if (typeof item.views !== 'number') {
            item.views = 0;
        }
        if (typeof item.comments !== 'number') {
            item.comments = 0;
        }
    });
    return data;
};

const localTechNewsData = ref<TechNewsData>(initializeNewsData());

// Watch for prop changes
watch(() => props.techNewsData, (newData) => {
    if (newData) {
        localTechNewsData.value = initializeNewsData();
    }
}, { deep: true });

const isSaving = ref(false);
const message = ref('');
const messageType = ref<'success' | 'error' | ''>();

// --- COMPUTED & WATCHERS ---
const originalDataString = computed(() => JSON.stringify(props.techNewsData || defaultTechNewsData));
const currentDataString = computed(() => JSON.stringify(localTechNewsData.value));
const hasUnsavedChanges = computed(() => currentDataString.value !== originalDataString.value);

// Watch for prop changes to reset local state if needed (e.g., after save)
watch(
    () => props.techNewsData,
    (newData) => {
        const processedData = JSON.parse(JSON.stringify(newData || defaultTechNewsData));
        // Ensure arrays are properly formatted
        processedData.news.forEach((item: any) => {
            if (!Array.isArray(item.tags)) {
                item.tags = [];
            }
            if (typeof item.views !== 'number') {
                item.views = 0;
            }
            if (typeof item.comments !== 'number') {
                item.comments = 0;
            }
        });
        localTechNewsData.value = processedData;
    },
    { deep: true },
);

// --- METHODS ---
const showMessage = (msg: string, type: 'success' | 'error') => {
    message.value = msg;
    messageType.value = type;
    setTimeout(() => {
        message.value = '';
    }, 5000);
};

// Methods to manage news items
const addNewsItem = () => {
    const newId = Math.max(0, ...localTechNewsData.value.news.map(n => n.id)) + 1;
    const newOrder = Math.max(0, ...localTechNewsData.value.news.map(n => n.order)) + 1;
    localTechNewsData.value.news.push({
        id: newId,
        title: 'New Technology News',
        summary: 'News summary goes here...',
        content: 'Full news content goes here...',
        image: '/images/news/default.jpg',
        fallbackGradient: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        category: 'Technology',
        author: 'Author Name',
        authorRole: 'Author Role',
        publishDate: new Date().toISOString().split('T')[0],
        readTime: '5 min',
        views: 0,
        comments: 0,
        tags: ['Technology'],
        trending: false,
        source: 'MBSTU News',
        sourceUrl: '#',
        isActive: true,
        order: newOrder
    });
};

const removeNewsItem = (index: number) => {
    if (confirm('Remove this news item?')) {
        localTechNewsData.value.news.splice(index, 1);
    }
};

const moveNewsUp = (index: number) => {
    if (index > 0) {
        const temp = localTechNewsData.value.news[index];
        localTechNewsData.value.news[index] = localTechNewsData.value.news[index - 1];
        localTechNewsData.value.news[index - 1] = temp;
        
        // Update order values
        localTechNewsData.value.news[index].order = index + 1;
        localTechNewsData.value.news[index - 1].order = index;
    }
};

const moveNewsDown = (index: number) => {
    if (index < localTechNewsData.value.news.length - 1) {
        const temp = localTechNewsData.value.news[index];
        localTechNewsData.value.news[index] = localTechNewsData.value.news[index + 1];
        localTechNewsData.value.news[index + 1] = temp;
        
        // Update order values
        localTechNewsData.value.news[index].order = index + 1;
        localTechNewsData.value.news[index + 1].order = index + 2;
    }
};

// Methods to manage tags
const addTag = (newsItem: NewsItem) => {
    newsItem.tags.push('New Tag');
};

const removeTag = (newsItem: NewsItem, index: number) => {
    if (newsItem.tags.length > 1) {
        newsItem.tags.splice(index, 1);
    }
};

const resetToOriginal = () => {
    if (confirm('Discard unsaved changes?')) {
        localTechNewsData.value = JSON.parse(JSON.stringify(props.techNewsData || defaultTechNewsData));
        showMessage('Changes discarded.', 'success');
    }
};

const validateAndSave = async () => {
    if (!props.siteId) return showMessage('Site ID is missing.', 'error');
    if (!hasUnsavedChanges.value) return showMessage('No changes to save.', 'success');

    isSaving.value = true;
    try {
        const response = await fetch('/admin/tech-news-section', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                Accept: 'application/json',
            },
            body: JSON.stringify({ techNewsData: localTechNewsData.value, siteId: props.siteId }),
        });
        const result = await response.json();
        if (response.ok && result.success) {
            showMessage(result.message, 'success');
            // Optionally reload only the tech news data prop if Inertia setup allows
            router.reload({ only: ['techNewsData'] });
        } else {
            showMessage(result.message || 'Server error during save.', 'error');
        }
    } catch (error: any) {
        showMessage(`Network or saving error: ${error.message || 'Unknown error'}`, 'error');
    } finally {
        isSaving.value = false;
    }
};

// Available category options
const categoryOptions = [
    'Education', 'Research', 'Innovation', 'Achievement', 'Partnership', 
    'Open Source', 'Technology', 'AI/ML', 'Cybersecurity', 'Software Development',
    'Hardware', 'Networking', 'Database', 'Web Development', 'Mobile Development'
];

// Available gradient options
const gradientOptions = [
    { name: 'Blue Purple', value: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' },
    { name: 'Pink Red', value: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)' },
    { name: 'Blue Cyan', value: 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)' },
    { name: 'Orange Yellow', value: 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)' },
    { name: 'Teal Pink', value: 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)' },
    { name: 'Green Yellow', value: 'linear-gradient(135deg, #96fbc4 0%, #f9f586 100%)' }
];

// Available sort options
const sortByOptions = ['date', 'views', 'trending'];
const sortOrderOptions = ['asc', 'desc'];
</script>

<template>
    <DefaultLayout>
        <div
            class="shadow-default rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 sm:px-7.5 xl:pb-1 dark:border-strokedark dark:bg-boxdark"
        >
            <div class="mb-6">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h4 class="text-xl font-semibold text-black dark:text-white">Tech News Management</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Site ID: {{ props.siteId || 'N/A' }}
                            <span v-if="hasUnsavedChanges" class="ml-2 text-warning">• Unsaved Changes</span>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button v-if="hasUnsavedChanges" @click="resetToOriginal" class="inline-flex items-center justify-center rounded-md bg-gray-600 px-4 py-2 text-center text-sm font-medium text-white transition-all duration-200 hover:bg-gray-700 hover:shadow-md hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:scale-95">Discard Changes</button>
                    </div>
                </div>
                <div
                    v-if="message"
                    :class="messageType === 'success' ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger'"
                    class="mb-4 rounded-md border px-4 py-3"
                >
                    {{ message }}
                </div>
            </div>

            <form @submit.prevent="validateAndSave">
                <!-- Section Settings -->
                <div class="space-y-6 rounded-sm border border-stroke bg-gray-50 p-6 dark:border-strokedark dark:bg-meta-4">
                    <h3 class="text-lg font-semibold text-black dark:text-white">Section Settings</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="mb-2.5 block font-medium text-black dark:text-white">Section Title</label>
                            <input v-model="localTechNewsData.sectionTitle" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" required />
                        </div>
                        <div>
                            <label class="mb-2.5 block font-medium text-black dark:text-white">Max News to Show</label>
                            <input v-model.number="localTechNewsData.maxNews" type="number" min="1" max="20" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />
                        </div>
                    </div>

                    <div>
                        <label class="mb-2.5 block font-medium text-black dark:text-white">Section Subtitle</label>
                        <textarea v-model="localTechNewsData.sectionSubtitle" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary min-h-[80px]" rows="3"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="flex items-center">
                            <label class="flex items-center">
                                <input v-model="localTechNewsData.isVisible" type="checkbox" class="mr-2" />
                                <span class="text-sm font-medium text-black dark:text-white">Section Visible</span>
                            </label>
                        </div>
                        <div class="flex items-center">
                            <label class="flex items-center">
                                <input v-model="localTechNewsData.showCarousel" type="checkbox" class="mr-2" />
                                <span class="text-sm font-medium text-black dark:text-white">Show as Carousel</span>
                            </label>
                        </div>
                        <div class="flex items-center">
                            <label class="flex items-center">
                                <input v-model="localTechNewsData.autoPlay" type="checkbox" class="mr-2" />
                                <span class="text-sm font-medium text-black dark:text-white">Auto Play</span>
                            </label>
                        </div>
                        <div v-if="localTechNewsData.autoPlay">
                            <label class="mb-2.5 block font-medium text-black dark:text-white">Auto Play Delay (ms)</label>
                            <input v-model.number="localTechNewsData.autoPlayDelay" type="number" min="1000" max="10000" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="mb-2.5 block font-medium text-black dark:text-white">Sort By</label>
                            <select v-model="localTechNewsData.sortBy" class="w-full rounded border-[1.5px] border-stroke bg-white px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                <option v-for="sort in sortByOptions" :key="sort" :value="sort">{{ sort.charAt(0).toUpperCase() + sort.slice(1) }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2.5 block font-medium text-black dark:text-white">Sort Order</label>
                            <select v-model="localTechNewsData.sortOrder" class="w-full rounded border-[1.5px] border-stroke bg-white px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                <option v-for="order in sortOrderOptions" :key="order" :value="order">{{ order.charAt(0).toUpperCase() + order.slice(1) }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- News Management -->
                <div class="mt-8 space-y-6 rounded-sm border border-stroke bg-gray-50 p-6 dark:border-strokedark dark:bg-meta-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-black dark:text-white">News Items</h3>
                        <button type="button" @click="addNewsItem" class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-center text-sm font-medium text-white transition-all duration-200 hover:bg-blue-700 hover:shadow-md hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:scale-95">Add News Item</button>
                    </div>

                    <div v-if="localTechNewsData.news.length === 0" class="text-center py-8 text-gray-500">
                        No news items added yet. Click "Add News Item" to get started.
                    </div>

                    <div v-else class="space-y-6">
                        <div
                            v-for="(newsItem, index) in localTechNewsData.news"
                            :key="newsItem.id"
                            class="border border-stroke rounded-lg p-6 bg-white dark:border-strokedark dark:bg-boxdark"
                        >
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="font-medium text-black dark:text-white">News Item #{{ index + 1 }}</h4>
                                <div class="flex gap-2">
                                    <button type="button" @click="moveNewsUp(index)" :disabled="index === 0" class="inline-flex items-center justify-center rounded-md px-2 py-1 text-center text-xs font-medium text-white transition-all duration-200 bg-gray-600 hover:bg-gray-700 hover:shadow-sm hover:scale-110 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-gray-600 disabled:hover:scale-100 focus:outline-none focus:ring-1 focus:ring-gray-500 active:scale-95">↑</button>
                                    <button type="button" @click="moveNewsDown(index)" :disabled="index === localTechNewsData.news.length - 1" class="inline-flex items-center justify-center rounded-md px-2 py-1 text-center text-xs font-medium text-white transition-all duration-200 bg-gray-600 hover:bg-gray-700 hover:shadow-sm hover:scale-110 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-gray-600 disabled:hover:scale-100 focus:outline-none focus:ring-1 focus:ring-gray-500 active:scale-95">↓</button>
                                    <button type="button" @click="removeNewsItem(index)" class="inline-flex items-center justify-center rounded-md px-2 py-1 text-center text-xs font-medium text-white transition-all duration-200 bg-red-600 hover:bg-red-700 hover:shadow-sm hover:scale-110 focus:outline-none focus:ring-1 focus:ring-red-500 active:scale-95">Remove</button>
                                </div>
                            </div>

                            <!-- Basic News Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">News Title</label>
                                    <input v-model="newsItem.title" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" required />
                                </div>
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Author</label>
                                    <input v-model="newsItem.author" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" required />
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="mb-2.5 block font-medium text-black dark:text-white">Summary</label>
                                <textarea v-model="newsItem.summary" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary min-h-[80px]" rows="3" required></textarea>
                            </div>

                            <div class="mb-6">
                                <label class="mb-2.5 block font-medium text-black dark:text-white">Full Content</label>
                                <textarea v-model="newsItem.content" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary min-h-[120px]" rows="5" required></textarea>
                            </div>

                            <!-- News Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Category</label>
                                    <select v-model="newsItem.category" class="w-full rounded border-[1.5px] border-stroke bg-white px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                        <option v-for="category in categoryOptions" :key="category" :value="category">{{ category }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Author Role</label>
                                    <input v-model="newsItem.authorRole" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />
                                </div>
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Publish Date</label>
                                    <input v-model="newsItem.publishDate" type="date" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />
                                </div>
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Read Time</label>
                                    <input v-model="newsItem.readTime" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" placeholder="e.g., 5 min" />
                                </div>
                            </div>

                            <!-- Engagement Stats -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Views</label>
                                    <input v-model.number="newsItem.views" type="number" min="0" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />
                                </div>
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Comments</label>
                                    <input v-model.number="newsItem.comments" type="number" min="0" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />
                                </div>
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Source</label>
                                    <input v-model="newsItem.source" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />
                                </div>
                            </div>

                            <!-- Image and Gradient -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Image Path</label>
                                    <input v-model="newsItem.image" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" placeholder="/images/news/news.jpg" />
                                </div>
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Fallback Gradient</label>
                                    <select v-model="newsItem.fallbackGradient" class="w-full rounded border-[1.5px] border-stroke bg-white px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                        <option v-for="gradient in gradientOptions" :key="gradient.value" :value="gradient.value">{{ gradient.name }}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Source URL -->
                            <div class="mb-6">
                                <label class="mb-2.5 block font-medium text-black dark:text-white">Source URL (Optional)</label>
                                <input v-model="newsItem.sourceUrl" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" placeholder="https://example.com" />
                            </div>

                            <!-- Tags -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="font-medium text-black dark:text-white">Tags</label>
                                    <button type="button" @click="addTag(newsItem)" class="inline-flex items-center justify-center rounded-md bg-green-600 px-3 py-1 text-center text-xs font-medium text-white transition-all duration-200 hover:bg-green-700 hover:scale-105 focus:outline-none focus:ring-1 focus:ring-green-500 active:scale-95">Add Tag</button>
                                </div>
                                <div class="space-y-2">
                                    <div v-for="(tag, tagIndex) in newsItem.tags" :key="tagIndex" class="flex gap-2">
                                        <input v-model="newsItem.tags[tagIndex]" type="text" class="flex-1 rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />
                                        <button type="button" @click="removeTag(newsItem, tagIndex)" :disabled="newsItem.tags.length === 1" class="inline-flex items-center justify-center rounded-md px-2 py-1 text-center text-xs font-medium text-white transition-all duration-200 bg-red-600 hover:bg-red-700 hover:scale-110 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-1 focus:ring-red-500 active:scale-95">×</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Controls -->
                            <div class="flex items-center gap-6">
                                <label class="flex items-center">
                                    <input v-model="newsItem.trending" type="checkbox" class="mr-2" />
                                    <span class="text-sm font-medium text-black dark:text-white">Trending</span>
                                </label>
                                <label class="flex items-center">
                                    <input v-model="newsItem.isActive" type="checkbox" class="mr-2" />
                                    <span class="text-sm font-medium text-black dark:text-white">Active</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="mt-8 flex justify-end">
                    <button
                        type="submit"
                        :disabled="isSaving || !hasUnsavedChanges"
                        class="inline-flex items-center justify-center rounded-md bg-blue-600 px-6 py-3 text-center font-medium text-white transition-all duration-200 hover:bg-blue-700 hover:shadow-lg hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-600 disabled:hover:scale-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:scale-95"
                    >
                        {{ isSaving ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </div>
    </DefaultLayout>
</template>

<style scoped>
/* Removed unused CSS classes since we're using inline Tailwind classes for better maintainability */
</style>