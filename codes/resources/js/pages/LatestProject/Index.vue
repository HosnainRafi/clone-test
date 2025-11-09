<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

// --- INTERFACES & PROPS ---
interface ProjectData {
    id: number;
    title: string;
    description: string;
    image: string;
    fallbackGradient: string;
    category: string;
    status: 'Completed' | 'Ongoing' | 'Research';
    duration: string;
    team: string[];
    technologies: string[];
    supervisor: string;
    achievements?: string[];
    githubUrl?: string;
    demoUrl?: string;
    year: string;
    isActive: boolean;
    order: number;
}

interface LatestProjectsData {
    sectionTitle: string;
    sectionSubtitle: string;
    isVisible: boolean;
    maxProjects: number;
    showCarousel: boolean;
    autoPlay: boolean;
    autoPlayDelay: number;
    projects: ProjectData[];
}

interface PageProps {
    latestProjectsData: LatestProjectsData | null; // From Controller
    siteId: number | null;
}

const props = defineProps<PageProps>();

// --- STATE MANAGEMENT ---
// Provide a default structure if latestProjectsData is initially null
const defaultLatestProjectsData: LatestProjectsData = {
    sectionTitle: 'Latest Projects',
    sectionSubtitle: 'Discover the innovative projects developed by our talented students under expert faculty supervision',
    isVisible: true,
    maxProjects: 6,
    showCarousel: true,
    autoPlay: false,
    autoPlayDelay: 5000,
    projects: [
        {
            id: 1,
            title: "AI-Powered Student Performance Prediction System",
            description: "A machine learning system that analyzes student academic data to predict performance and recommend personalized learning paths. Uses ensemble learning techniques for improved accuracy.",
            image: "/images/projects/ai-prediction.jpg",
            fallbackGradient: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
            category: "Artificial Intelligence",
            status: "Completed",
            duration: "8 months",
            team: ["Aminul Islam", "Fatema Khatun", "Rohit Kumar"],
            technologies: ["Python", "TensorFlow", "Django", "PostgreSQL", "React"],
            supervisor: "Dr. Rahman Ahmed",
            achievements: ["Best Final Year Project 2024", "Published in IEEE Conference"],
            githubUrl: "https://github.com/cse-mbstu/ai-prediction",
            demoUrl: "https://ai-prediction-demo.mbstu.edu",
            year: "2024",
            isActive: true,
            order: 1
        },
        {
            id: 2,
            title: "Smart Campus Management System",
            description: "IoT-based campus management platform integrating attendance tracking, resource monitoring, and security systems with real-time analytics dashboard.",
            image: "/images/projects/smart-campus.jpg",
            fallbackGradient: "linear-gradient(135deg, #f093fb 0%, #f5576c 100%)",
            category: "Internet of Things",
            status: "Ongoing",
            duration: "6 months",
            team: ["Md. Karim", "Rashida Begum", "Arif Hassan", "Nusrat Jahan"],
            technologies: ["Arduino", "Node.js", "MongoDB", "Vue.js", "MQTT"],
            supervisor: "Prof. Sarah Khan",
            achievements: ["Innovation Award 2024"],
            githubUrl: "https://github.com/cse-mbstu/smart-campus",
            year: "2024",
            isActive: true,
            order: 2
        }
    ]
};

// Initialize data with proper formatting
const initializeProjectsData = () => {
    const data = JSON.parse(JSON.stringify(props.latestProjectsData || defaultLatestProjectsData));
    // Ensure team arrays are properly formatted
    data.projects.forEach((project: any) => {
        // Handle nested team structure (fix for backend data)
        if (project.team && typeof project.team === 'object' && project.team.team) {
            project.team = project.team.team;
        }
        // Handle comma-separated team string
        if (typeof project.team === 'string') {
            project.team = project.team.split(',').map((name: string) => name.trim());
        }
        // Ensure team is always an array
        if (!Array.isArray(project.team)) {
            project.team = ['Team Member'];
        }
        // Ensure other arrays are properly formatted
        if (!Array.isArray(project.technologies)) {
            project.technologies = ['Technology'];
        }
        if (!Array.isArray(project.achievements)) {
            project.achievements = [];
        }
    });
    return data;
};

const localLatestProjectsData = ref<LatestProjectsData>(initializeProjectsData());

// Watch for prop changes
watch(() => props.latestProjectsData, (newData) => {
    if (newData) {
        localLatestProjectsData.value = initializeProjectsData();
    }
}, { deep: true });

const isSaving = ref(false);
const message = ref('');
const messageType = ref<'success' | 'error' | ''>();

// --- COMPUTED & WATCHERS ---
const originalDataString = computed(() => JSON.stringify(props.latestProjectsData || defaultLatestProjectsData));
const currentDataString = computed(() => JSON.stringify(localLatestProjectsData.value));
const hasUnsavedChanges = computed(() => currentDataString.value !== originalDataString.value);

// Watch for prop changes to reset local state if needed (e.g., after save)
watch(
    () => props.latestProjectsData,
    (newData) => {
        const processedData = JSON.parse(JSON.stringify(newData || defaultLatestProjectsData));
        // Ensure team arrays are properly formatted
        processedData.projects.forEach((project: any) => {
            if (typeof project.team === 'string') {
                project.team = project.team.split(',').map((name: string) => name.trim());
            }
            if (!Array.isArray(project.team)) {
                project.team = ['Team Member'];
            }
            if (!Array.isArray(project.technologies)) {
                project.technologies = ['Technology'];
            }
            if (!Array.isArray(project.achievements)) {
                project.achievements = [];
            }
        });
        localLatestProjectsData.value = processedData;
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

// Methods to manage projects
const addProject = () => {
    const newId = Math.max(0, ...localLatestProjectsData.value.projects.map(p => p.id)) + 1;
    const newOrder = Math.max(0, ...localLatestProjectsData.value.projects.map(p => p.order)) + 1;
    localLatestProjectsData.value.projects.push({
        id: newId,
        title: 'New Project',
        description: 'Project description goes here...',
        image: '/images/projects/default.jpg',
        fallbackGradient: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        category: 'Computer Science',
        status: 'Ongoing',
        duration: '6 months',
        team: ['Student Name'],
        technologies: ['Technology'],
        supervisor: 'Faculty Name',
        achievements: [],
        githubUrl: '',
        demoUrl: '',
        year: new Date().getFullYear().toString(),
        isActive: true,
        order: newOrder
    });
};

const removeProject = (index: number) => {
    if (confirm('Remove this project?')) {
        localLatestProjectsData.value.projects.splice(index, 1);
    }
};

const moveProjectUp = (index: number) => {
    if (index > 0) {
        const temp = localLatestProjectsData.value.projects[index];
        localLatestProjectsData.value.projects[index] = localLatestProjectsData.value.projects[index - 1];
        localLatestProjectsData.value.projects[index - 1] = temp;
        
        // Update order values
        localLatestProjectsData.value.projects[index].order = index + 1;
        localLatestProjectsData.value.projects[index - 1].order = index;
    }
};

const moveProjectDown = (index: number) => {
    if (index < localLatestProjectsData.value.projects.length - 1) {
        const temp = localLatestProjectsData.value.projects[index];
        localLatestProjectsData.value.projects[index] = localLatestProjectsData.value.projects[index + 1];
        localLatestProjectsData.value.projects[index + 1] = temp;
        
        // Update order values
        localLatestProjectsData.value.projects[index].order = index + 1;
        localLatestProjectsData.value.projects[index + 1].order = index + 2;
    }
};

// Methods to manage team members
const addTeamMember = (project: ProjectData) => {
    project.team.push('New Team Member');
};

const removeTeamMember = (project: ProjectData, index: number) => {
    if (project.team.length > 1) {
        project.team.splice(index, 1);
    }
};

// Methods to manage technologies
const addTechnology = (project: ProjectData) => {
    project.technologies.push('New Technology');
};

const removeTechnology = (project: ProjectData, index: number) => {
    if (project.technologies.length > 1) {
        project.technologies.splice(index, 1);
    }
};

// Methods to manage achievements
const addAchievement = (project: ProjectData) => {
    if (!project.achievements) {
        project.achievements = [];
    }
    project.achievements.push('New Achievement');
};

const removeAchievement = (project: ProjectData, index: number) => {
    if (project.achievements) {
        project.achievements.splice(index, 1);
    }
};

const resetToOriginal = () => {
    if (confirm('Discard unsaved changes?')) {
        localLatestProjectsData.value = JSON.parse(JSON.stringify(props.latestProjectsData || defaultLatestProjectsData));
        showMessage('Changes discarded.', 'success');
    }
};

const validateAndSave = async () => {
    if (!props.siteId) return showMessage('Site ID is missing.', 'error');
    if (!hasUnsavedChanges.value) return showMessage('No changes to save.', 'success');

    isSaving.value = true;
    try {
        const response = await fetch('/admin/latest-projects-section', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                Accept: 'application/json',
            },
            body: JSON.stringify({ latestProjectsData: localLatestProjectsData.value, siteId: props.siteId }),
        });
        const result = await response.json();
        if (response.ok && result.success) {
            showMessage(result.message, 'success');
            // Optionally reload only the latest projects data prop if Inertia setup allows
            router.reload({ only: ['latestProjectsData'] });
        } else {
            showMessage(result.message || 'Server error during save.', 'error');
        }
    } catch (error: any) {
        showMessage(`Network or saving error: ${error.message || 'Unknown error'}`, 'error');
    } finally {
        isSaving.value = false;
    }
};

// Available status options
const statusOptions = ['Completed', 'Ongoing', 'Research'];

// Available categories
const categoryOptions = [
    'Artificial Intelligence', 'Machine Learning', 'Internet of Things', 'Blockchain',
    'Web Development', 'Mobile Development', 'Software Engineering', 'Data Science',
    'Computer Vision', 'Natural Language Processing', 'Cybersecurity', 'Database Systems',
    'Network Systems', 'Embedded Systems', 'Game Development', 'Augmented Reality',
    'Virtual Reality', 'Cloud Computing', 'DevOps', 'Computer Graphics'
];

// Available gradient options
const gradientOptions = [
    { name: 'Blue Purple', value: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' },
    { name: 'Pink Red', value: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)' },
    { name: 'Teal Pink', value: 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)' },
    { name: 'Orange Peach', value: 'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)' },
    { name: 'Purple Pink', value: 'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)' },
    { name: 'Blue Green', value: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }
];
</script>

<template>
    <DefaultLayout>
        <div
            class="shadow-default rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 sm:px-7.5 xl:pb-1 dark:border-strokedark dark:bg-boxdark"
        >
            <div class="mb-6">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h4 class="text-xl font-semibold text-black dark:text-white">Latest Projects Management</h4>
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
                            <input v-model="localLatestProjectsData.sectionTitle" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" required />
                        </div>
                        <div>
                            <label class="mb-2.5 block font-medium text-black dark:text-white">Max Projects to Show</label>
                            <input v-model.number="localLatestProjectsData.maxProjects" type="number" min="1" max="20" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />
                        </div>
                    </div>

                    <div>
                        <label class="mb-2.5 block font-medium text-black dark:text-white">Section Subtitle</label>
                        <textarea v-model="localLatestProjectsData.sectionSubtitle" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary min-h-[80px]" rows="3"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="flex items-center">
                            <label class="flex items-center">
                                <input v-model="localLatestProjectsData.isVisible" type="checkbox" class="mr-2" />
                                <span class="text-sm font-medium text-black dark:text-white">Section Visible</span>
                            </label>
                        </div>
                        <div class="flex items-center">
                            <label class="flex items-center">
                                <input v-model="localLatestProjectsData.showCarousel" type="checkbox" class="mr-2" />
                                <span class="text-sm font-medium text-black dark:text-white">Show as Carousel</span>
                            </label>
                        </div>
                        <div class="flex items-center">
                            <label class="flex items-center">
                                <input v-model="localLatestProjectsData.autoPlay" type="checkbox" class="mr-2" />
                                <span class="text-sm font-medium text-black dark:text-white">Auto Play</span>
                            </label>
                        </div>
                        <div v-if="localLatestProjectsData.autoPlay">
                            <label class="mb-2.5 block font-medium text-black dark:text-white">Auto Play Delay (ms)</label>
                            <input v-model.number="localLatestProjectsData.autoPlayDelay" type="number" min="1000" max="10000" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />
                        </div>
                    </div>
                </div>

                <!-- Projects Management -->
                <div class="mt-8 space-y-6 rounded-sm border border-stroke bg-gray-50 p-6 dark:border-strokedark dark:bg-meta-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-black dark:text-white">Projects</h3>
                        <button type="button" @click="addProject" class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-center text-sm font-medium text-white transition-all duration-200 hover:bg-blue-700 hover:shadow-md hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:scale-95">Add Project</button>
                    </div>

                    <div v-if="localLatestProjectsData.projects.length === 0" class="text-center py-8 text-gray-500">
                        No projects added yet. Click "Add Project" to get started.
                    </div>

                    <div v-else class="space-y-6">
                        <div
                            v-for="(project, index) in localLatestProjectsData.projects"
                            :key="project.id"
                            class="border border-stroke rounded-lg p-6 bg-white dark:border-strokedark dark:bg-boxdark"
                        >
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="font-medium text-black dark:text-white">Project #{{ index + 1 }}</h4>
                                <div class="flex gap-2">
                                    <button type="button" @click="moveProjectUp(index)" :disabled="index === 0" class="inline-flex items-center justify-center rounded-md px-2 py-1 text-center text-xs font-medium text-white transition-all duration-200 bg-gray-600 hover:bg-gray-700 hover:shadow-sm hover:scale-110 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-gray-600 disabled:hover:scale-100 focus:outline-none focus:ring-1 focus:ring-gray-500 active:scale-95">↑</button>
                                    <button type="button" @click="moveProjectDown(index)" :disabled="index === localLatestProjectsData.projects.length - 1" class="inline-flex items-center justify-center rounded-md px-2 py-1 text-center text-xs font-medium text-white transition-all duration-200 bg-gray-600 hover:bg-gray-700 hover:shadow-sm hover:scale-110 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-gray-600 disabled:hover:scale-100 focus:outline-none focus:ring-1 focus:ring-gray-500 active:scale-95">↓</button>
                                    <button type="button" @click="removeProject(index)" class="inline-flex items-center justify-center rounded-md px-2 py-1 text-center text-xs font-medium text-white transition-all duration-200 bg-red-600 hover:bg-red-700 hover:shadow-sm hover:scale-110 focus:outline-none focus:ring-1 focus:ring-red-500 active:scale-95">Remove</button>
                                </div>
                            </div>

                            <!-- Basic Project Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Project Title</label>
                                    <input v-model="project.title" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" required />
                                </div>
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Supervisor</label>
                                    <input v-model="project.supervisor" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" required />
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="mb-2.5 block font-medium text-black dark:text-white">Project Description</label>
                                <textarea v-model="project.description" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary min-h-[100px]" rows="4" required></textarea>
                            </div>

                            <!-- Project Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Category</label>
                                    <div class="relative">
                                        <select v-model="project.category" class="w-full rounded border-[1.5px] border-stroke bg-white px-5 py-3 pr-10 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary appearance-none">
                                            <option v-for="category in categoryOptions" :key="category" :value="category">{{ category }}</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Status</label>
                                    <div class="relative">
                                        <select v-model="project.status" class="w-full rounded border-[1.5px] border-stroke bg-white px-5 py-3 pr-10 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary appearance-none">
                                            <option v-for="status in statusOptions" :key="status" :value="status">{{ status }}</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Duration</label>
                                    <input v-model="project.duration" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" placeholder="e.g., 6 months" />
                                </div>
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Year</label>
                                    <input v-model="project.year" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />
                                </div>
                            </div>

                            <!-- Image and Gradient -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Image Path</label>
                                    <input v-model="project.image" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" placeholder="/images/projects/project.jpg" />
                                </div>
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Fallback Gradient</label>
                                    <div class="relative">
                                        <select v-model="project.fallbackGradient" class="w-full rounded border-[1.5px] border-stroke bg-white px-5 py-3 pr-10 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary appearance-none">
                                            <option v-for="gradient in gradientOptions" :key="gradient.value" :value="gradient.value">{{ gradient.name }}</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- URLs -->
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">GitHub URL (Optional)</label>
                                    <input v-model="project.githubUrl" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" placeholder="https://github.com/username/repo" />
                                </div>
                                <div>
                                    <label class="mb-2.5 block font-medium text-black dark:text-white">Demo URL (Optional)</label>
                                    <input v-model="project.demoUrl" type="text" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" placeholder="https://demo.example.com" />
                                </div>
                            </div>

                            <!-- Team Members -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="font-medium text-black dark:text-white">Team Members</label>
                                    <button type="button" @click="addTeamMember(project)" class="inline-flex items-center justify-center rounded-md bg-green-600 px-3 py-1 text-center text-xs font-medium text-white transition-all duration-200 hover:bg-green-700 hover:scale-105 focus:outline-none focus:ring-1 focus:ring-green-500 active:scale-95">Add Member</button>
                                </div>
                                <div class="space-y-2">
                                    <div v-for="(member, memberIndex) in project.team" :key="memberIndex" class="flex gap-2">
                                        <input v-model="project.team[memberIndex]" type="text" placeholder="Enter team member name" class="flex-1 rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />
                                        <button type="button" @click="removeTeamMember(project, memberIndex)" :disabled="project.team.length === 1" class="inline-flex items-center justify-center rounded-md px-2 py-1 text-center text-xs font-medium text-white transition-all duration-200 bg-red-600 hover:bg-red-700 hover:scale-110 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-1 focus:ring-red-500 active:scale-95">×</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Technologies -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="font-medium text-black dark:text-white">Technologies</label>
                                    <button type="button" @click="addTechnology(project)" class="inline-flex items-center justify-center rounded-md bg-purple-600 px-3 py-1 text-center text-xs font-medium text-white transition-all duration-200 hover:bg-purple-700 hover:scale-105 focus:outline-none focus:ring-1 focus:ring-purple-500 active:scale-95">Add Technology</button>
                                </div>
                                <div class="space-y-2">
                                    <div v-for="(tech, techIndex) in project.technologies" :key="techIndex" class="flex gap-2">
                                        <input v-model="project.technologies[techIndex]" type="text" class="flex-1 rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />
                                        <button type="button" @click="removeTechnology(project, techIndex)" :disabled="project.technologies.length === 1" class="inline-flex items-center justify-center rounded-md px-2 py-1 text-center text-xs font-medium text-white transition-all duration-200 bg-red-600 hover:bg-red-700 hover:scale-110 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-1 focus:ring-red-500 active:scale-95">×</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Achievements -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="font-medium text-black dark:text-white">Achievements (Optional)</label>
                                    <button type="button" @click="addAchievement(project)" class="inline-flex items-center justify-center rounded-md bg-yellow-600 px-3 py-1 text-center text-xs font-medium text-white transition-all duration-200 hover:bg-yellow-700 hover:scale-105 focus:outline-none focus:ring-1 focus:ring-yellow-500 active:scale-95">Add Achievement</button>
                                </div>
                                <div v-if="project.achievements && project.achievements.length > 0" class="space-y-2">
                                    <div v-for="(achievement, achievementIndex) in project.achievements" :key="achievementIndex" class="flex gap-2">
                                        <input v-model="project.achievements[achievementIndex]" type="text" class="flex-1 rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />
                                        <button type="button" @click="removeAchievement(project, achievementIndex)" class="inline-flex items-center justify-center rounded-md px-2 py-1 text-center text-xs font-medium text-white transition-all duration-200 bg-red-600 hover:bg-red-700 hover:scale-110 focus:outline-none focus:ring-1 focus:ring-red-500 active:scale-95">×</button>
                                    </div>
                                </div>
                                <div v-else class="text-sm text-gray-500 italic">No achievements added yet.</div>
                            </div>

                            <!-- Active Status -->
                            <div class="flex items-center">
                                <label class="flex items-center">
                                    <input v-model="project.isActive" type="checkbox" class="mr-2" />
                                    <span class="text-sm font-medium text-black dark:text-white">Project Active</span>
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