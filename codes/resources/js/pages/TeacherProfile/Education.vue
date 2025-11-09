<script setup lang="ts">
import TeacherProfileLayout from '@/layouts/TeacherProfileLayout.vue';
import { useToast } from '@/composables/useToast';
import { Plus, Save, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';

const toast = useToast();

interface Education {
    degree: string;
    field: string;
    university: string;
    year: string;
}

interface Teacher {
    id: number;
    name: string;
    education: Education[];
}

interface Props {
    teacher: Teacher;
}

const props = defineProps<Props>();

const form = ref<{ education: Education[] }>({
    education: props.teacher.education.length > 0 ? [...props.teacher.education] : [],
});

const saving = ref(false);

const addEducation = () => {
    form.value.education.push({
        degree: '',
        field: '',
        university: '',
        year: new Date().getFullYear().toString(),
    });
};

const removeEducation = (index: number) => {
    if (confirm('Are you sure you want to remove this education entry?')) {
        form.value.education.splice(index, 1);
    }
};

const saveEducation = async () => {
    saving.value = true;

    try {
        const response = await fetch('/admin/teacher/profile/education', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify(form.value),
        });

        const data = await response.json();

        if (data.success) {
            toast.success('Education updated successfully!');
        } else {
            toast.error('Failed to update education');
        }
    } catch (error) {
        console.error('Error saving:', error);
        toast.error('Error saving education');
    } finally {
        saving.value = false;
    }
};
</script>

<template>
    <TeacherProfileLayout :teacher="{ id: props.teacher.id, name: props.teacher.name }">
        <div class="shadow-default rounded-sm border border-stroke bg-white dark:border-strokedark dark:bg-boxdark">
            <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                <h3 class="font-semibold text-black dark:text-white">Education</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">List your academic qualifications and degrees.</p>
            </div>

            <div class="p-6.5">
                <form @submit.prevent="saveEducation" class="space-y-6">
                    <!-- Add Education Button -->
                    <div class="flex justify-end">
                        <button
                            type="button"
                            @click="addEducation"
                            class="hover:bg-opacity-90 inline-flex items-center gap-2 rounded bg-primary px-4 py-2 text-sm font-medium text-white transition"
                        >
                            <Plus class="h-4 w-4" />
                            <span>Add Education</span>
                        </button>
                    </div>

                    <!-- Education List -->
                    <div class="space-y-4">
                        <div
                            v-for="(edu, index) in form.education"
                            :key="index"
                            class="rounded-lg border border-stroke bg-gray-50 p-5 dark:border-strokedark dark:bg-meta-4"
                        >
                            <div class="mb-4 flex items-center justify-between">
                                <h4 class="font-medium text-black dark:text-white">Education #{{ index + 1 }}</h4>
                                <button
                                    type="button"
                                    @click="removeEducation(index)"
                                    class="hover:bg-opacity-90 inline-flex items-center gap-1 rounded bg-danger px-3 py-1.5 text-sm text-white transition"
                                >
                                    <Trash2 class="h-4 w-4" />
                                    <span>Remove</span>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <!-- Degree and Field -->
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-black dark:text-white">
                                            Degree <span class="text-meta-1">*</span>
                                        </label>
                                        <input
                                            v-model="edu.degree"
                                            type="text"
                                            required
                                            placeholder="e.g., Ph.D., M.Sc., B.Sc."
                                            class="w-full rounded border-[1.5px] border-stroke bg-white px-4 py-2.5 transition outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                        />
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-black dark:text-white">
                                            Field of Study <span class="text-meta-1">*</span>
                                        </label>
                                        <input
                                            v-model="edu.field"
                                            type="text"
                                            required
                                            placeholder="e.g., Computer Science"
                                            class="w-full rounded border-[1.5px] border-stroke bg-white px-4 py-2.5 transition outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                        />
                                    </div>
                                </div>

                                <!-- University and Year -->
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-black dark:text-white">
                                            University <span class="text-meta-1">*</span>
                                        </label>
                                        <input
                                            v-model="edu.university"
                                            type="text"
                                            required
                                            placeholder="e.g., University of XYZ"
                                            class="w-full rounded border-[1.5px] border-stroke bg-white px-4 py-2.5 transition outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                        />
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-black dark:text-white">
                                            Year <span class="text-meta-1">*</span>
                                        </label>
                                        <input
                                            v-model="edu.year"
                                            type="text"
                                            required
                                            placeholder="e.g., 2020 or 2018-2020"
                                            class="w-full rounded border-[1.5px] border-stroke bg-white px-4 py-2.5 transition outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div
                            v-if="form.education.length === 0"
                            class="rounded-lg border-2 border-dashed border-stroke bg-gray-50 p-12 text-center dark:border-strokedark dark:bg-meta-4"
                        >
                            <p class="text-gray-600 dark:text-gray-400">No education entries added yet.</p>
                            <p class="mt-1 text-sm text-gray-500">Click "Add Education" to get started.</p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-4 border-t border-stroke pt-6 dark:border-strokedark">
                        <button
                            type="submit"
                            :disabled="saving"
                            class="hover:bg-opacity-90 inline-flex items-center justify-center gap-2 rounded bg-primary px-6 py-3 font-medium text-white transition disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <Save class="h-5 w-5" />
                            <span>{{ saving ? 'Saving...' : 'Save Education' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </TeacherProfileLayout>
</template>
