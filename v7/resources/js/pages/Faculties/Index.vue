<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// Define interfaces
interface FacultyItem {
    id?: number;
    name: string;
    shortName: string;
    description: string;
    image: string;
    fallbackGradient: string;
    link: string;
    departments: string[];
    stats: {
        departments: number;
        students: string;
        faculty: string;
        programs: number;
    };
    highlights: string[];
    featured?: boolean;
    established?: string;
    ranking?: string;
    dean?: {
        name: string;
        title: string;
        email?: string;
    };
    researchAreas: string[];
    achievements: string[];
    iconName: string;
    isActive: boolean;
    displayOrder: number;
}

interface PageProps {
    siteData: any;
    facultyItems: FacultyItem[];
    siteId: number | null;
}

// Receive props from Laravel
const props = defineProps<PageProps>();

// Extract siteId for easier access
const { siteId } = props;

// Available icons
const availableIcons = [
    { value: 'Calculator', label: '🧮 Calculator (Engineering/Tech)' },
    { value: 'Microscope', label: '🔬 Microscope (Life Sciences)' },
    { value: 'Globe', label: '🌐 Globe (Science/Global Studies)' },
    { value: 'Briefcase', label: '💼 Briefcase (Business)' },
    { value: 'Users', label: '👥 Users (Social Science)' },
    { value: 'BookText', label: '📖 BookText (Arts/Literature)' },
    { value: 'Heart', label: '❤️ Heart (Medicine/Health)' },
    { value: 'Stethoscope', label: '🩺 Stethoscope (Medical)' },
    { value: 'Building', label: '🏢 Building (General)' },
    { value: 'GraduationCap', label: '🎓 Graduation Cap (Education)' },
    { value: 'BookOpen', label: '📚 Book Open (Academic)' },
    { value: 'Award', label: '🏆 Award (Excellence)' },
];

// Available ranking options
const rankingOptions = ['Top Rated', 'Excellence', 'Premier', 'Growing', 'Emerging', 'Cultural Hub', 'Specialized'];

// State management
const facultyData = ref<FacultyItem[]>([]);
const isSaving = ref(false);
const message = ref('');
const messageType = ref<'success' | 'error' | ''>('');

// Initialize faculties from props
const initializeFaculties = () => {
    if (props.facultyItems && props.facultyItems.length > 0) {
        // Add IDs to existing items if they don't have them
        facultyData.value = props.facultyItems.map((item, index) => ({
            ...item,
            id: item.id || Date.now() + index,
            departments: item.departments || [],
            highlights: item.highlights || [],
            researchAreas: item.researchAreas || [],
            achievements: item.achievements || [],
            stats: item.stats || { departments: 0, students: '0', faculty: '0', programs: 0 },
            dean: item.dean || undefined,
        }));
    } else {
        // Default faculty if none exist
        facultyData.value = [
            {
                id: 1,
                name: 'Faculty of Engineering',
                shortName: 'Engineering',
                description:
                    'Leading innovation in technology and engineering with cutting-edge research, industry partnerships, and world-class laboratories preparing students for the digital future.',
                image: '/images/faculties/engineering.jpg',
                fallbackGradient: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%)',
                link: '/faculty-of-engineering',
                departments: [
                    'Computer Science and Engineering (CSE)',
                    'Information and Communication Technology (ICT)',
                    'Electrical and Electronic Engineering (EEE)',
                    'Civil Engineering (CE)',
                ],
                stats: {
                    departments: 4,
                    students: '1,250+',
                    faculty: '48+',
                    programs: 12,
                },
                highlights: ['State-of-the-art laboratories', 'Industry collaboration', 'Research excellence', 'International partnerships'],
                researchAreas: [
                    'Artificial Intelligence & Machine Learning',
                    'Robotics & Automation',
                    'Renewable Energy Systems',
                    'Smart Infrastructure',
                ],
                achievements: [
                    '15+ International Research Publications (2024)',
                    'IEEE Student Branch Chapter',
                    'Industry Collaboration with 20+ Companies',
                    '90% Graduate Employment Rate',
                ],
                featured: true,
                established: '2002',
                ranking: 'Top Rated',
                dean: {
                    name: 'Prof. Dr. Mohammad Motiur Rahman',
                    title: 'Dean, Faculty of Engineering',
                    email: 'dean.engineering@mbstu.ac.bd',
                },
                iconName: 'Calculator',
                isActive: true,
                displayOrder: 1,
            },
        ];
    }
};

// Initialize on component mount
initializeFaculties();

// Computed properties
const hasUnsavedChanges = computed(() => {
    return JSON.stringify(facultyData.value) !== JSON.stringify(props.facultyItems);
});

const isValidConfiguration = computed(() => {
    try {
        // Check if all faculties have required fields
        for (const item of facultyData.value) {
            if (!item.name || !item.shortName || !item.description) {
                return false;
            }
        }

        // Try to serialize to JSON
        JSON.stringify(facultyData.value);
        return true;
    } catch {
        return false;
    }
});

const activeFacultiesCount = computed(() => {
    return facultyData.value.filter((f) => f.isActive).length;
});

const sortedFaculties = computed(() => {
    return [...facultyData.value].sort((a, b) => a.displayOrder - b.displayOrder);
});

// Methods
const addFaculty = () => {
    const newFaculty: FacultyItem = {
        id: Date.now(),
        name: 'New Faculty Name',
        shortName: 'Faculty',
        description: 'Enter faculty description here...',
        image: '/images/faculties/default.jpg',
        fallbackGradient: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%)',
        link: '#',
        departments: [],
        stats: {
            departments: 0,
            students: '0',
            faculty: '0',
            programs: 0,
        },
        highlights: [],
        researchAreas: [],
        achievements: [],
        featured: false,
        established: new Date().getFullYear().toString(),
        ranking: 'Growing',
        dean: {
            name: '',
            title: '',
            email: '',
        },
        iconName: 'Building',
        isActive: true,
        displayOrder: facultyData.value.length + 1,
    };
    facultyData.value.push(newFaculty);
};

const removeFaculty = (id: number) => {
    const index = facultyData.value.findIndex((item) => item.id === id);
    if (index > -1) {
        facultyData.value.splice(index, 1);
        // Reorder remaining items
        facultyData.value.forEach((item, idx) => {
            item.displayOrder = idx + 1;
        });
    }
};

const moveUp = (id: number) => {
    const index = facultyData.value.findIndex((item) => item.id === id);
    if (index > 0) {
        [facultyData.value[index], facultyData.value[index - 1]] = [facultyData.value[index - 1], facultyData.value[index]];
        // Update display orders
        facultyData.value.forEach((item, idx) => {
            item.displayOrder = idx + 1;
        });
    }
};

const moveDown = (id: number) => {
    const index = facultyData.value.findIndex((item) => item.id === id);
    if (index < facultyData.value.length - 1) {
        [facultyData.value[index], facultyData.value[index + 1]] = [facultyData.value[index + 1], facultyData.value[index]];
        // Update display orders
        facultyData.value.forEach((item, idx) => {
            item.displayOrder = idx + 1;
        });
    }
};

const toggleActive = (id: number) => {
    const item = facultyData.value.find((f) => f.id === id);
    if (item) {
        item.isActive = !item.isActive;
    }
};

// Array management helpers
const addToArray = (itemId: number, field: 'departments' | 'highlights' | 'researchAreas' | 'achievements', value: string) => {
    const item = facultyData.value.find((f) => f.id === itemId);
    if (item && value.trim()) {
        if (!item[field]) {
            item[field] = [];
        }
        (item[field] as string[]).push(value.trim());
    }
};

const removeFromArray = (itemId: number, field: 'departments' | 'highlights' | 'researchAreas' | 'achievements', index: number) => {
    const item = facultyData.value.find((f) => f.id === itemId);
    if (item && item[field]) {
        (item[field] as string[]).splice(index, 1);
    }
};

const validateAndSave = async () => {
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
        const dataToSave = facultyData.value.map((item) => ({
            name: item.name,
            shortName: item.shortName,
            description: item.description,
            image: item.image,
            fallbackGradient: item.fallbackGradient,
            link: item.link,
            departments: item.departments,
            stats: item.stats,
            highlights: item.highlights,
            featured: item.featured,
            established: item.established,
            ranking: item.ranking,
            dean: item.dean,
            researchAreas: item.researchAreas,
            achievements: item.achievements,
            iconName: item.iconName,
            isActive: item.isActive,
            displayOrder: item.displayOrder,
        }));

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const response = await fetch('/faculties/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                Accept: 'application/json',
            },
            body: JSON.stringify({
                facultyItems: dataToSave,
                siteId: props.siteId,
            }),
        });

        const responseText = await response.text();
        let result;
        try {
            result = JSON.parse(responseText);
        } catch (parseError) {
            showMessage('Server returned invalid response', 'error');
            return;
        }

        if (response.ok && result.success) {
            showMessage(result.message, 'success');
            setTimeout(() => {
                router.reload();
            }, 1500);
        } else {
            showMessage(result.message || 'Save failed', 'error');
        }
    } catch (err) {
        showMessage('Network error occurred', 'error');
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
    initializeFaculties();
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
                        <h4 class="text-xl font-semibold text-black dark:text-white">Faculties Management</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Site ID: {{ siteId || 'Not Available' }} | Status:
                            <span :class="isValidConfiguration ? 'text-success' : 'text-danger'">
                                {{ isValidConfiguration ? 'Valid' : 'Invalid Configuration' }}
                            </span>
                            <span v-if="hasUnsavedChanges" class="ml-2 text-warning">• Unsaved Changes</span>
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                            Total Faculties: {{ facultyData.length }} | Active: {{ activeFacultiesCount }}
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
                            @click="addFaculty"
                            class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-center font-medium text-white"
                        >
                            Add Faculty
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

            <!-- Faculties List -->
            <div class="space-y-6">
                <div v-if="facultyData.length === 0" class="py-8 text-center text-gray-500 dark:text-gray-400">
                    No faculties configured. Click "Add Faculty" to get started.
                </div>

                <div
                    v-for="(item, index) in sortedFaculties"
                    :key="item.id"
                    class="rounded-sm border border-stroke bg-gray-50 p-6 dark:bg-meta-4"
                    :class="{
                        'opacity-50': !item.isActive,
                        'border-l-4 border-l-primary': item.isActive,
                    }"
                >
                    <div class="mb-4 flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-2xl font-bold text-primary">
                                {{ item.displayOrder }}
                            </div>
                            <div>
                                <h5 class="text-lg font-medium text-black dark:text-white">
                                    {{ item.name }}
                                </h5>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ item.shortName }}</span>
                                    <span class="text-xs text-gray-500">•</span>
                                    <span :class="item.isActive ? 'text-green-600' : 'text-red-600'" class="text-xs font-semibold">
                                        {{ item.isActive ? 'Active' : 'Inactive' }}
                                    </span>
                                    <span v-if="item.featured" class="rounded bg-yellow-100 px-2 py-1 text-xs text-yellow-800">Featured</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button
                                @click="moveUp(item.id!)"
                                :disabled="index === 0"
                                class="inline-flex items-center justify-center rounded-md bg-blue-500 px-2 py-1 text-center text-xs font-medium text-white hover:bg-blue-600 disabled:bg-gray-400"
                            >
                                ↑
                            </button>
                            <button
                                @click="moveDown(item.id!)"
                                :disabled="index === sortedFaculties.length - 1"
                                class="inline-flex items-center justify-center rounded-md bg-blue-500 px-2 py-1 text-center text-xs font-medium text-white hover:bg-blue-600 disabled:bg-gray-400"
                            >
                                ↓
                            </button>
                            <button
                                @click="toggleActive(item.id!)"
                                :class="item.isActive ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600'"
                                class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md px-3 py-2 text-center font-medium text-white"
                            >
                                {{ item.isActive ? 'Deactivate' : 'Activate' }}
                            </button>
                            <button
                                @click="removeFaculty(item.id!)"
                                class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-danger px-3 py-2 text-center font-medium text-white"
                            >
                                Remove
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- Left Column: Basic Info -->
                        <div class="space-y-4">
                            <!-- Name -->
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white"> Faculty Name * </label>
                                <input
                                    v-model="item.name"
                                    type="text"
                                    :class="{
                                        'border-danger': !item.name,
                                        'border-stroke': item.name,
                                    }"
                                    class="w-full rounded border-[1.5px] bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                    placeholder="Faculty of Engineering"
                                />
                                <p v-if="!item.name" class="mt-1 text-sm text-danger">Faculty name is required</p>
                            </div>

                            <!-- Short Name -->
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white"> Short Name * </label>
                                <input
                                    v-model="item.shortName"
                                    type="text"
                                    :class="{
                                        'border-danger': !item.shortName,
                                        'border-stroke': item.shortName,
                                    }"
                                    class="w-full rounded border-[1.5px] bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                    placeholder="Engineering"
                                />
                                <p v-if="!item.shortName" class="mt-1 text-sm text-danger">Short name is required</p>
                            </div>

                            <!-- Link -->
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white"> Faculty Link </label>
                                <input
                                    v-model="item.link"
                                    type="text"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                    placeholder="/faculty-of-engineering"
                                />
                            </div>

                            <!-- Icon Selection -->
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white"> Icon </label>
                                <select
                                    v-model="item.iconName"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                >
                                    <option v-for="icon in availableIcons" :key="icon.value" :value="icon.value">
                                        {{ icon.label }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Right Column: Advanced Info -->
                        <div class="space-y-4">
                            <!-- Image URL -->
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white"> Image URL </label>
                                <input
                                    v-model="item.image"
                                    type="text"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                    placeholder="/images/faculties/faculty.jpg"
                                />
                            </div>

                            <!-- Fallback Gradient -->
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white"> Fallback Gradient </label>
                                <input
                                    v-model="item.fallbackGradient"
                                    type="text"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                    placeholder="linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)"
                                />
                            </div>

                            <!-- Established & Ranking -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-black dark:text-white"> Established Year </label>
                                    <input
                                        v-model="item.established"
                                        type="text"
                                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm font-medium transition outline-none focus:border-primary active:border-primary"
                                        placeholder="2002"
                                    />
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-black dark:text-white"> Ranking </label>
                                    <select
                                        v-model="item.ranking"
                                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm font-medium transition outline-none focus:border-primary active:border-primary"
                                    >
                                        <option value="">No Ranking</option>
                                        <option v-for="ranking in rankingOptions" :key="ranking" :value="ranking">
                                            {{ ranking }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Toggles -->
                            <div class="flex items-center gap-6">
                                <label class="flex cursor-pointer items-center">
                                    <input v-model="item.featured" type="checkbox" class="sr-only" />
                                    <div class="relative">
                                        <div class="block h-6 w-10 rounded-full bg-gray-600"></div>
                                        <div
                                            :class="item.featured ? 'translate-x-4 bg-primary' : 'translate-x-0 bg-gray-400'"
                                            class="absolute top-1 left-1 h-4 w-4 rounded-full transition"
                                        ></div>
                                    </div>
                                    <div class="ml-3 text-sm font-medium text-black dark:text-white">Featured</div>
                                </label>

                                <label class="flex cursor-pointer items-center">
                                    <input v-model="item.isActive" type="checkbox" class="sr-only" />
                                    <div class="relative">
                                        <div class="block h-6 w-10 rounded-full bg-gray-600"></div>
                                        <div
                                            :class="item.isActive ? 'translate-x-4 bg-primary' : 'translate-x-0 bg-gray-400'"
                                            class="absolute top-1 left-1 h-4 w-4 rounded-full transition"
                                        ></div>
                                    </div>
                                    <div class="ml-3 text-sm font-medium text-black dark:text-white">Active</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <label class="mb-2 block font-medium text-black dark:text-white"> Description * </label>
                        <textarea
                            v-model="item.description"
                            rows="4"
                            :class="{
                                'border-danger': !item.description,
                                'border-stroke': item.description,
                            }"
                            class="w-full rounded border-[1.5px] bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                            placeholder="Enter faculty description..."
                        ></textarea>
                        <p v-if="!item.description" class="mt-1 text-sm text-danger">Description is required</p>
                    </div>

                    <!-- Stats -->
                    <div class="mt-6">
                        <h6 class="mb-4 text-lg font-semibold text-black dark:text-white">Statistics</h6>
                        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-black dark:text-white">Departments</label>
                                <input
                                    v-model.number="item.stats.departments"
                                    type="number"
                                    min="0"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm transition outline-none focus:border-primary"
                                />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-black dark:text-white">Students</label>
                                <input
                                    v-model="item.stats.students"
                                    type="text"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm transition outline-none focus:border-primary"
                                    placeholder="1,250+"
                                />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-black dark:text-white">Faculty</label>
                                <input
                                    v-model="item.stats.faculty"
                                    type="text"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm transition outline-none focus:border-primary"
                                    placeholder="48+"
                                />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-black dark:text-white">Programs</label>
                                <input
                                    v-model.number="item.stats.programs"
                                    type="number"
                                    min="0"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm transition outline-none focus:border-primary"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Dean Information -->
                    <div class="mt-6">
                        <h6 class="mb-4 text-lg font-semibold text-black dark:text-white">Dean Information</h6>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-black dark:text-white">Dean Name</label>
                                <input
                                    v-model="item.dean!.name"
                                    type="text"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm transition outline-none focus:border-primary"
                                    placeholder="Prof. Dr. John Doe"
                                />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-black dark:text-white">Dean Title</label>
                                <input
                                    v-model="item.dean!.title"
                                    type="text"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm transition outline-none focus:border-primary"
                                    placeholder="Dean, Faculty of Engineering"
                                />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-black dark:text-white">Dean Email</label>
                                <input
                                    v-model="item.dean!.email"
                                    type="email"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm transition outline-none focus:border-primary"
                                    placeholder="dean@mbstu.ac.bd"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Arrays (Departments, Highlights, Research Areas, Achievements) -->
                    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Departments -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-black dark:text-white"> Departments </label>
                            <div class="space-y-2">
                                <div v-for="(dept, dIndex) in item.departments" :key="dIndex" class="flex items-center gap-2">
                                    <input
                                        v-model="item.departments[dIndex]"
                                        type="text"
                                        class="flex-1 rounded border-[1.5px] border-stroke bg-transparent px-2 py-1 text-xs transition outline-none focus:border-primary"
                                        placeholder="Computer Science and Engineering (CSE)"
                                    />
                                    <button
                                        @click="removeFromArray(item.id!, 'departments', dIndex)"
                                        class="rounded px-2 py-1 text-xs text-red-500 hover:text-red-700"
                                    >
                                        ✕
                                    </button>
                                </div>
                                <button
                                    @click="addToArray(item.id!, 'departments', 'New Department')"
                                    class="hover:text-primary-dark rounded border border-primary px-2 py-1 text-xs text-primary"
                                >
                                    + Add Department
                                </button>
                            </div>
                        </div>

                        <!-- Highlights -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-black dark:text-white"> Highlights </label>
                            <div class="space-y-2">
                                <div v-for="(highlight, hIndex) in item.highlights" :key="hIndex" class="flex items-center gap-2">
                                    <input
                                        v-model="item.highlights[hIndex]"
                                        type="text"
                                        class="flex-1 rounded border-[1.5px] border-stroke bg-transparent px-2 py-1 text-xs transition outline-none focus:border-primary"
                                        placeholder="State-of-the-art laboratories"
                                    />
                                    <button
                                        @click="removeFromArray(item.id!, 'highlights', hIndex)"
                                        class="rounded px-2 py-1 text-xs text-red-500 hover:text-red-700"
                                    >
                                        ✕
                                    </button>
                                </div>
                                <button
                                    @click="addToArray(item.id!, 'highlights', 'New Highlight')"
                                    class="hover:text-primary-dark rounded border border-primary px-2 py-1 text-xs text-primary"
                                >
                                    + Add Highlight
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Research Areas & Achievements -->
                    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Research Areas -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-black dark:text-white"> Research Areas </label>
                            <div class="space-y-2">
                                <div v-for="(area, rIndex) in item.researchAreas" :key="rIndex" class="flex items-center gap-2">
                                    <input
                                        v-model="item.researchAreas[rIndex]"
                                        type="text"
                                        class="flex-1 rounded border-[1.5px] border-stroke bg-transparent px-2 py-1 text-xs transition outline-none focus:border-primary"
                                        placeholder="Artificial Intelligence & Machine Learning"
                                    />
                                    <button
                                        @click="removeFromArray(item.id!, 'researchAreas', rIndex)"
                                        class="rounded px-2 py-1 text-xs text-red-500 hover:text-red-700"
                                    >
                                        ✕
                                    </button>
                                </div>
                                <button
                                    @click="addToArray(item.id!, 'researchAreas', 'New Research Area')"
                                    class="hover:text-primary-dark rounded border border-primary px-2 py-1 text-xs text-primary"
                                >
                                    + Add Research Area
                                </button>
                            </div>
                        </div>

                        <!-- Achievements -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-black dark:text-white"> Achievements </label>
                            <div class="space-y-2">
                                <div v-for="(achievement, aIndex) in item.achievements" :key="aIndex" class="flex items-center gap-2">
                                    <input
                                        v-model="item.achievements[aIndex]"
                                        type="text"
                                        class="flex-1 rounded border-[1.5px] border-stroke bg-transparent px-2 py-1 text-xs transition outline-none focus:border-primary"
                                        placeholder="15+ International Research Publications (2024)"
                                    />
                                    <button
                                        @click="removeFromArray(item.id!, 'achievements', aIndex)"
                                        class="rounded px-2 py-1 text-xs text-red-500 hover:text-red-700"
                                    >
                                        ✕
                                    </button>
                                </div>
                                <button
                                    @click="addToArray(item.id!, 'achievements', 'New Achievement')"
                                    class="hover:text-primary-dark rounded border border-primary px-2 py-1 text-xs text-primary"
                                >
                                    + Add Achievement
                                </button>
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
                        {{ isSaving ? 'Saving...' : 'Save Faculties Configuration' }}
                    </button>
                </div>
            </div>

            <!-- JSON Preview (for debugging) -->
            <details class="mt-6">
                <summary class="cursor-pointer text-sm font-medium text-gray-600 hover:text-primary dark:text-gray-400">
                    View JSON Configuration
                </summary>
                <pre class="mt-2 max-h-60 overflow-auto rounded bg-gray-100 p-4 text-xs dark:bg-gray-800">{{
                    JSON.stringify(facultyData, null, 2)
                }}</pre>
            </details>
        </div>
    </DefaultLayout>
</template>
