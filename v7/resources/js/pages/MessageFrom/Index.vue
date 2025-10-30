<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// Define interfaces
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

interface PageProps {
    siteData: any;
    messageFromItems: MessageFromItem[];
    siteId: number | null;
}

// Receive props from Laravel
const props = defineProps<PageProps>();

// Extract siteId for easier access
const { siteId } = props;

// Available types and their default info
const messageTypes = [
    { value: 'vice_chancellor', label: 'Vice Chancellor', defaultTitle: 'Vice Chancellor', defaultDept: 'MBSTU' },
    { value: 'pro_vice_chancellor', label: 'Pro Vice Chancellor', defaultTitle: 'Pro Vice Chancellor', defaultDept: 'MBSTU' },
    { value: 'dean', label: 'Dean', defaultTitle: 'Dean', defaultDept: 'Faculty' },
    { value: 'director', label: 'Director', defaultTitle: 'Director', defaultDept: 'Directorate' },
    { value: 'chairman', label: 'Chairman', defaultTitle: 'Chairman', defaultDept: 'Department' },
    { value: 'other', label: 'Other', defaultTitle: 'Official', defaultDept: 'MBSTU' },
];

// State management
const messageFromData = ref<MessageFromItem[]>([]);
const isSaving = ref(false);
const message = ref('');
const messageType = ref<'success' | 'error' | ''>('');

// Initialize messages from props
const initializeMessages = () => {
    if (props.messageFromItems && props.messageFromItems.length > 0) {
        // Add IDs to existing items if they don't have them
        messageFromData.value = props.messageFromItems.map((item, index) => ({
            ...item,
            id: item.id || Date.now() + index,
            qualifications: item.qualifications || [],
            specializations: item.specializations || [],
            achievements: item.achievements || [],
        }));
    } else {
        // Default message if none exist
        messageFromData.value = [
            {
                id: 1,
                name: 'Professor Dr. Md. Anwarul Azim Akhand',
                title: 'Vice Chancellor',
                message:
                    'Welcome to Mawlana Bhashani Science and Technology University (MBSTU), a leading center for academic excellence and research in Bangladesh. MBSTU is named after the great visionary and advocate for the underprivileged, Mawlana Abdul Hamid Khan Bhashani. His legacy of dedication to justice, education, and empowerment continues to inspire our mission to create future leaders through scientific innovation, academic rigor, and social commitment.\n\nAt MBSTU, we believe that education is not just a means to personal advancement, but a tool for transforming society. Our dynamic curriculum, state-of-the-art facilities, and dedicated faculty aim to nurture creativity, critical thinking, and problem-solving skills among our students.',
                image: '/images/faculty/vc.jpg',
                fallbackGradient: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%)',
                designation: 'Vice Chancellor',
                department: 'Mawlana Bhashani Science and Technology University',
                email: 'vc@mbstu.ac.bd',
                phone: '+880921 55399',
                office: 'Vice Chancellor Office, MBSTU',
                experience: '25+ years',
                qualifications: [
                    'Ph.D. in Computer Science and Engineering',
                    'M.Sc. in Computer Science and Engineering',
                    'B.Sc. in Computer Science and Engineering',
                ],
                specializations: ['Artificial Intelligence', 'Machine Learning', 'Computer Vision'],
                achievements: [
                    'Leading MBSTU as a center of excellence',
                    'Pioneer in science and technology education',
                    'Advocate for research and innovation',
                ],
                isActive: true,
                displayOrder: 1,
                type: 'vice_chancellor',
            },
        ];
    }
};

// Initialize on component mount
initializeMessages();

// Computed properties
const hasUnsavedChanges = computed(() => {
    return JSON.stringify(messageFromData.value) !== JSON.stringify(props.messageFromItems);
});

const isValidConfiguration = computed(() => {
    try {
        // Check if all messages have required fields
        for (const item of messageFromData.value) {
            if (!item.name || !item.title || !item.message || !item.type) {
                return false;
            }
        }

        // Try to serialize to JSON
        JSON.stringify(messageFromData.value);
        return true;
    } catch {
        return false;
    }
});

const activeMessagesCount = computed(() => {
    return messageFromData.value.filter((m) => m.isActive).length;
});

const sortedMessages = computed(() => {
    return [...messageFromData.value].sort((a, b) => a.displayOrder - b.displayOrder);
});

// Methods
const addMessage = () => {
    const newMessage: MessageFromItem = {
        id: Date.now(),
        name: 'New Official Name',
        title: 'Official Title',
        message: 'Enter the official message content here. This message will be displayed to visitors on the homepage.',
        image: '/images/officials/default.jpg',
        fallbackGradient: 'linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%)',
        designation: 'Official Designation',
        department: 'Department Name',
        email: '',
        phone: '',
        office: '',
        experience: '',
        qualifications: [],
        specializations: [],
        achievements: [],
        isActive: true,
        displayOrder: messageFromData.value.length + 1,
        type: 'other',
    };
    messageFromData.value.push(newMessage);
};

const removeMessage = (id: number) => {
    const index = messageFromData.value.findIndex((item) => item.id === id);
    if (index > -1) {
        messageFromData.value.splice(index, 1);
        // Reorder remaining items
        messageFromData.value.forEach((item, idx) => {
            item.displayOrder = idx + 1;
        });
    }
};

const moveUp = (id: number) => {
    const index = messageFromData.value.findIndex((item) => item.id === id);
    if (index > 0) {
        [messageFromData.value[index], messageFromData.value[index - 1]] = [messageFromData.value[index - 1], messageFromData.value[index]];
        // Update display orders
        messageFromData.value.forEach((item, idx) => {
            item.displayOrder = idx + 1;
        });
    }
};

const moveDown = (id: number) => {
    const index = messageFromData.value.findIndex((item) => item.id === id);
    if (index < messageFromData.value.length - 1) {
        [messageFromData.value[index], messageFromData.value[index + 1]] = [messageFromData.value[index + 1], messageFromData.value[index]];
        // Update display orders
        messageFromData.value.forEach((item, idx) => {
            item.displayOrder = idx + 1;
        });
    }
};

const toggleActive = (id: number) => {
    const item = messageFromData.value.find((m) => m.id === id);
    if (item) {
        item.isActive = !item.isActive;
    }
};

const updateType = (id: number, newType: string) => {
    const item = messageFromData.value.find((m) => m.id === id);
    if (item) {
        item.type = newType as any;
        const typeConfig = messageTypes.find((t) => t.value === newType);
        if (typeConfig) {
            item.title = typeConfig.defaultTitle;
            item.department = typeConfig.defaultDept;
        }
    }
};

// Array management helpers
const addToArray = (itemId: number, field: 'qualifications' | 'specializations' | 'achievements', value: string) => {
    const item = messageFromData.value.find((m) => m.id === itemId);
    if (item && value.trim()) {
        if (!item[field]) {
            item[field] = [];
        }
        item[field]!.push(value.trim());
    }
};

const removeFromArray = (itemId: number, field: 'qualifications' | 'specializations' | 'achievements', index: number) => {
    const item = messageFromData.value.find((m) => m.id === itemId);
    if (item && item[field]) {
        item[field]!.splice(index, 1);
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
        const dataToSave = messageFromData.value.map((item) => ({
            name: item.name,
            title: item.title,
            message: item.message,
            image: item.image,
            fallbackGradient: item.fallbackGradient,
            designation: item.designation,
            department: item.department,
            email: item.email,
            phone: item.phone,
            fax: item.fax,
            office: item.office,
            address: item.address,
            officeTime: item.officeTime,
            experience: item.experience,
            qualifications: item.qualifications,
            specializations: item.specializations,
            achievements: item.achievements,
            isActive: item.isActive,
            displayOrder: item.displayOrder,
            type: item.type,
        }));

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const response = await fetch('/message-from/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                Accept: 'application/json',
            },
            body: JSON.stringify({
                messageFromItems: dataToSave,
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
    initializeMessages();
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
                        <h4 class="text-xl font-semibold text-black dark:text-white">Message From Officials Management</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Site ID: {{ siteId || 'Not Available' }} | Status:
                            <span :class="isValidConfiguration ? 'text-success' : 'text-danger'">
                                {{ isValidConfiguration ? 'Valid' : 'Invalid Configuration' }}
                            </span>
                            <span v-if="hasUnsavedChanges" class="ml-2 text-warning">• Unsaved Changes</span>
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                            Total Messages: {{ messageFromData.length }} | Active: {{ activeMessagesCount }}
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
                            @click="addMessage"
                            class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-center font-medium text-white"
                        >
                            Add Message
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

            <!-- Messages List -->
            <div class="space-y-6">
                <div v-if="messageFromData.length === 0" class="py-8 text-center text-gray-500 dark:text-gray-400">
                    No messages configured. Click "Add Message" to get started.
                </div>

                <div
                    v-for="(item, index) in sortedMessages"
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
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ item.title }}</span>
                                    <span class="text-xs text-gray-500">•</span>
                                    <span :class="item.isActive ? 'text-green-600' : 'text-red-600'" class="text-xs font-semibold">
                                        {{ item.isActive ? 'Active' : 'Inactive' }}
                                    </span>
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
                                :disabled="index === sortedMessages.length - 1"
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
                                @click="removeMessage(item.id!)"
                                class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-danger px-3 py-2 text-center font-medium text-white"
                            >
                                Remove
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- Left Column: Basic Info -->
                        <div class="space-y-4">
                            <!-- Type Selection -->
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white"> Official Type * </label>
                                <select
                                    :value="item.type"
                                    @change="updateType(item.id!, ($event.target as HTMLSelectElement).value)"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                >
                                    <option v-for="type in messageTypes" :key="type.value" :value="type.value">
                                        {{ type.label }}
                                    </option>
                                </select>
                            </div>

                            <!-- Name -->
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white"> Full Name * </label>
                                <input
                                    v-model="item.name"
                                    type="text"
                                    :class="{
                                        'border-danger': !item.name,
                                        'border-stroke': item.name,
                                    }"
                                    class="w-full rounded border-[1.5px] bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                    placeholder="Prof. Dr. John Doe"
                                />
                                <p v-if="!item.name" class="mt-1 text-sm text-danger">Name is required</p>
                            </div>

                            <!-- Title -->
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white"> Official Title * </label>
                                <input
                                    v-model="item.title"
                                    type="text"
                                    :class="{
                                        'border-danger': !item.title,
                                        'border-stroke': item.title,
                                    }"
                                    class="w-full rounded border-[1.5px] bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                    placeholder="Vice Chancellor"
                                />
                                <p v-if="!item.title" class="mt-1 text-sm text-danger">Title is required</p>
                            </div>

                            <!-- Department -->
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white"> Department/Faculty </label>
                                <input
                                    v-model="item.department"
                                    type="text"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                    placeholder="Computer Science & Engineering"
                                />
                            </div>

                            <!-- Contact Info -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-black dark:text-white"> Email </label>
                                    <input
                                        v-model="item.email"
                                        type="email"
                                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm font-medium transition outline-none focus:border-primary active:border-primary"
                                        placeholder="email@mbstu.ac.bd"
                                    />
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-black dark:text-white"> Phone </label>
                                    <input
                                        v-model="item.phone"
                                        type="text"
                                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm font-medium transition outline-none focus:border-primary active:border-primary"
                                        placeholder="+880 XXX XXXXX"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Media & Display -->
                        <div class="space-y-4">
                            <!-- Image URL -->
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white"> Profile Image URL </label>
                                <input
                                    v-model="item.image"
                                    type="text"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                    placeholder="/images/officials/profile.jpg"
                                />
                            </div>

                            <!-- Fallback Gradient -->
                            <div>
                                <label class="mb-2 block font-medium text-black dark:text-white"> Fallback Gradient </label>
                                <input
                                    v-model="item.fallbackGradient"
                                    type="text"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                    placeholder="linear-gradient(135deg, #1e40af 0%, #3b82f6 100%)"
                                />
                            </div>

                            <!-- Office & Experience -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-black dark:text-white"> Office </label>
                                    <input
                                        v-model="item.office"
                                        type="text"
                                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm font-medium transition outline-none focus:border-primary active:border-primary"
                                        placeholder="VC Office, MBSTU"
                                    />
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-black dark:text-white"> Experience </label>
                                    <input
                                        v-model="item.experience"
                                        type="text"
                                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-3 py-2 text-sm font-medium transition outline-none focus:border-primary active:border-primary"
                                        placeholder="25+ years"
                                    />
                                </div>
                            </div>

                            <!-- Active Status Toggle -->
                            <div class="flex items-center">
                                <label class="flex cursor-pointer items-center">
                                    <input v-model="item.isActive" type="checkbox" class="sr-only" />
                                    <div class="relative">
                                        <div class="block h-8 w-14 rounded-full bg-gray-600"></div>
                                        <div
                                            :class="item.isActive ? 'translate-x-6 bg-primary' : 'translate-x-1 bg-gray-400'"
                                            class="absolute top-1 left-1 h-6 w-6 rounded-full transition"
                                        ></div>
                                    </div>
                                    <div class="ml-3 text-sm font-medium text-black dark:text-white">
                                        {{ item.isActive ? 'Active' : 'Inactive' }}
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Message Content -->
                    <div class="mt-6">
                        <label class="mb-2 block font-medium text-black dark:text-white"> Message Content * </label>
                        <textarea
                            v-model="item.message"
                            rows="6"
                            :class="{
                                'border-danger': !item.message,
                                'border-stroke': item.message,
                            }"
                            class="w-full rounded border-[1.5px] bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                            placeholder="Enter the official's message content here. Use double line breaks to separate paragraphs."
                        ></textarea>
                        <p v-if="!item.message" class="mt-1 text-sm text-danger">Message content is required</p>
                        <p class="mt-1 text-xs text-gray-500">Tip: Use double line breaks (Enter twice) to create separate paragraphs</p>
                    </div>

                    <!-- Optional Arrays -->
                    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-3">
                        <!-- Qualifications -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-black dark:text-white"> Qualifications </label>
                            <div class="space-y-2">
                                <div v-for="(qual, qIndex) in item.qualifications" :key="qIndex" class="flex items-center gap-2">
                                    <input
                                        v-model="item.qualifications![qIndex]"
                                        type="text"
                                        class="flex-1 rounded border-[1.5px] border-stroke bg-transparent px-2 py-1 text-xs transition outline-none focus:border-primary"
                                        placeholder="Ph.D. in Computer Science"
                                    />
                                    <button
                                        @click="removeFromArray(item.id!, 'qualifications', qIndex)"
                                        class="rounded px-2 py-1 text-xs text-red-500 hover:text-red-700"
                                    >
                                        ✕
                                    </button>
                                </div>
                                <button
                                    @click="addToArray(item.id!, 'qualifications', 'New Qualification')"
                                    class="hover:text-primary-dark rounded border border-primary px-2 py-1 text-xs text-primary"
                                >
                                    + Add Qualification
                                </button>
                            </div>
                        </div>

                        <!-- Specializations -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-black dark:text-white"> Specializations </label>
                            <div class="space-y-2">
                                <div v-for="(spec, sIndex) in item.specializations" :key="sIndex" class="flex items-center gap-2">
                                    <input
                                        v-model="item.specializations![sIndex]"
                                        type="text"
                                        class="flex-1 rounded border-[1.5px] border-stroke bg-transparent px-2 py-1 text-xs transition outline-none focus:border-primary"
                                        placeholder="Machine Learning"
                                    />
                                    <button
                                        @click="removeFromArray(item.id!, 'specializations', sIndex)"
                                        class="rounded px-2 py-1 text-xs text-red-500 hover:text-red-700"
                                    >
                                        ✕
                                    </button>
                                </div>
                                <button
                                    @click="addToArray(item.id!, 'specializations', 'New Specialization')"
                                    class="hover:text-primary-dark rounded border border-primary px-2 py-1 text-xs text-primary"
                                >
                                    + Add Specialization
                                </button>
                            </div>
                        </div>

                        <!-- Achievements -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-black dark:text-white"> Achievements </label>
                            <div class="space-y-2">
                                <div v-for="(ach, aIndex) in item.achievements" :key="aIndex" class="flex items-center gap-2">
                                    <input
                                        v-model="item.achievements![aIndex]"
                                        type="text"
                                        class="flex-1 rounded border-[1.5px] border-stroke bg-transparent px-2 py-1 text-xs transition outline-none focus:border-primary"
                                        placeholder="Leading research in AI"
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
                        {{ isSaving ? 'Saving...' : 'Save Messages Configuration' }}
                    </button>
                </div>
            </div>

            <!-- JSON Preview (for debugging) -->
            <details class="mt-6">
                <summary class="cursor-pointer text-sm font-medium text-gray-600 hover:text-primary dark:text-gray-400">
                    View JSON Configuration
                </summary>
                <pre class="mt-2 max-h-60 overflow-auto rounded bg-gray-100 p-4 text-xs dark:bg-gray-800">{{
                    JSON.stringify(messageFromData, null, 2)
                }}</pre>
            </details>
        </div>
    </DefaultLayout>
</template>
