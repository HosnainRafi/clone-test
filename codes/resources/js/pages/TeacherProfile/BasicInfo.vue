<script setup lang="ts">
import TeacherProfileLayout from '@/layouts/TeacherProfileLayout.vue';
import { useToast } from '@/composables/useToast';
import { Save } from 'lucide-vue-next';
import { ref } from 'vue';

const toast = useToast();

interface Teacher {
    id: number;
    site_id: number;
    name: string;
    designation?: string;
    department?: string;
    profile_image?: string;
    email?: string;
    phone_number?: string;
    office_location?: string;
    website_url?: string;
    is_active: boolean;
}

interface Props {
    teacher: Teacher;
}

const props = defineProps<Props>();

const form = ref<Teacher>({ ...props.teacher });
const saving = ref(false);
const uploading = ref(false);
const imagePreview = ref<string | null>(props.teacher.profile_image || null);

const onProfileImageSelected = async (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];

    if (!file) return;

    // Show preview
    const reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);

    // Upload image
    uploading.value = true;
    const formData = new FormData();
    formData.append('image', file);

    try {
        const response = await fetch('/admin/teacher/profile/upload-image', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
        });

        const data = await response.json();

        if (data.success) {
            form.value.profile_image = data.url;
            imagePreview.value = data.url;
            toast.success('Profile image uploaded successfully!');
        } else {
            toast.error('Failed to upload image');
        }
    } catch (error) {
        console.error('Error uploading image:', error);
        toast.error('Error uploading image');
    } finally {
        uploading.value = false;
    }
};

const saveBasicInfo = async () => {
    saving.value = true;

    try {
        const response = await fetch('/admin/teacher/profile/basic-info', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify(form.value),
        });

        const data = await response.json();

        if (data.success) {
            toast.success('Basic information updated successfully!');
        } else {
            toast.error('Failed to update basic information');
        }
    } catch (error) {
        console.error('Error saving:', error);
        toast.error('Error saving basic information');
    } finally {
        saving.value = false;
    }
};
</script>

<template>
    <TeacherProfileLayout :teacher="{ id: props.teacher.id, name: props.teacher.name }">
        <div class="shadow-default rounded-sm border border-stroke bg-white dark:border-strokedark dark:bg-boxdark">
            <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                <h3 class="font-semibold text-black dark:text-white">Basic Information</h3>
            </div>

            <div class="p-6.5">
                <form @submit.prevent="saveBasicInfo" class="space-y-6">
                    <!-- Profile Image -->
                    <div class="flex flex-col items-center gap-4 sm:flex-row">
                        <div class="flex-shrink-0">
                            <div class="relative h-32 w-32 overflow-hidden rounded-full bg-gray-200">
                                <img v-if="imagePreview" :src="imagePreview" alt="Profile" class="h-full w-full object-cover" />
                                <div v-else class="flex h-full w-full items-center justify-center text-4xl font-bold text-gray-500">
                                    {{ form.name?.charAt(0) || '?' }}
                                </div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <label class="mb-2 block text-sm font-medium text-black dark:text-white">Profile Image</label>
                            <input
                                type="file"
                                accept="image/*"
                                @change="onProfileImageSelected"
                                class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                :disabled="uploading"
                            />
                            <p class="mt-1 text-xs text-gray-500">Recommended size: 400x400px. Max file size: 2MB</p>
                            <p v-if="uploading" class="mt-2 text-sm text-primary">Uploading...</p>
                        </div>
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="mb-2.5 block font-medium text-black dark:text-white"> Name <span class="text-meta-1">*</span> </label>
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            placeholder="Enter your full name"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                        />
                    </div>

                    <!-- Designation and Department -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2.5 block font-medium text-black dark:text-white">Designation</label>
                            <input
                                v-model="form.designation"
                                type="text"
                                placeholder="e.g., Professor, Associate Professor"
                                class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            />
                        </div>
                        <div>
                            <label class="mb-2.5 block font-medium text-black dark:text-white">Department</label>
                            <input
                                v-model="form.department"
                                type="text"
                                placeholder="e.g., Computer Science"
                                class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            />
                        </div>
                    </div>

                    <!-- Email and Phone -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2.5 block font-medium text-black dark:text-white">Email</label>
                            <input
                                v-model="form.email"
                                type="email"
                                placeholder="your.email@example.com"
                                class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            />
                        </div>
                        <div>
                            <label class="mb-2.5 block font-medium text-black dark:text-white">Phone Number</label>
                            <input
                                v-model="form.phone_number"
                                type="text"
                                placeholder="+1 (555) 123-4567"
                                class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            />
                        </div>
                    </div>

                    <!-- Office Location -->
                    <div>
                        <label class="mb-2.5 block font-medium text-black dark:text-white">Office Location</label>
                        <input
                            v-model="form.office_location"
                            type="text"
                            placeholder="e.g., Building A, Room 301"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                        />
                    </div>

                    <!-- Website URL -->
                    <div>
                        <label class="mb-2.5 block font-medium text-black dark:text-white">Personal Website</label>
                        <input
                            v-model="form.website_url"
                            type="url"
                            placeholder="https://your-website.com"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                        />
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-4">
                        <button
                            type="submit"
                            :disabled="saving || uploading"
                            class="hover:bg-opacity-90 inline-flex items-center justify-center gap-2 rounded bg-primary px-6 py-3 font-medium text-white transition disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <Save class="h-5 w-5" />
                            <span>{{ saving ? 'Saving...' : 'Save Changes' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </TeacherProfileLayout>
</template>
