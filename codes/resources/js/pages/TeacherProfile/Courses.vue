<script setup lang="ts">
import TeacherProfileLayout from '@/layouts/TeacherProfileLayout.vue';
import { useToast } from '@/composables/useToast';
import { Plus, Save, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';

const toast = useToast();

interface Course {
    code?: string;
    title: string;
}

interface Teacher {
    id: number;
    name: string;
    courses_taught: Course[];
}

interface Props {
    teacher: Teacher;
}

const props = defineProps<Props>();

const form = ref<{ courses_taught: Course[] }>({
    courses_taught: props.teacher.courses_taught.length > 0 ? [...props.teacher.courses_taught] : [],
});

const saving = ref(false);

const addCourse = () => {
    form.value.courses_taught.push({
        code: '',
        title: '',
    });
};

const removeCourse = (index: number) => {
    if (confirm('Are you sure you want to remove this course?')) {
        form.value.courses_taught.splice(index, 1);
    }
};

const saveCourses = async () => {
    saving.value = true;

    try {
        const response = await fetch('/admin/teacher/profile/courses', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify(form.value),
        });

        const data = await response.json();

        if (data.success) {
            toast.success('Courses updated successfully!');
        } else {
            toast.error('Failed to update courses');
        }
    } catch (error) {
        console.error('Error saving:', error);
        toast.error('Error saving courses');
    } finally {
        saving.value = false;
    }
};
</script>

<template>
    <TeacherProfileLayout :teacher="{ id: props.teacher.id, name: props.teacher.name }">
        <div class="shadow-default rounded-sm border border-stroke bg-white dark:border-strokedark dark:bg-boxdark">
            <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                <h3 class="font-semibold text-black dark:text-white">Courses Taught</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">List the courses you teach or have taught.</p>
            </div>

            <div class="p-6.5">
                <form @submit.prevent="saveCourses" class="space-y-6">
                    <div class="flex justify-end">
                        <button
                            type="button"
                            @click="addCourse"
                            class="hover:bg-opacity-90 inline-flex items-center gap-2 rounded bg-primary px-4 py-2 text-sm font-medium text-white transition"
                        >
                            <Plus class="h-4 w-4" />
                            <span>Add Course</span>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div
                            v-for="(course, index) in form.courses_taught"
                            :key="index"
                            class="rounded-lg border border-stroke bg-gray-50 p-5 dark:border-strokedark dark:bg-meta-4"
                        >
                            <div class="mb-4 flex items-center justify-between">
                                <h4 class="font-medium text-black dark:text-white">Course #{{ index + 1 }}</h4>
                                <button
                                    type="button"
                                    @click="removeCourse(index)"
                                    class="hover:bg-opacity-90 inline-flex items-center gap-1 rounded bg-danger px-3 py-1.5 text-sm text-white transition"
                                >
                                    <Trash2 class="h-4 w-4" />
                                    <span>Remove</span>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-black dark:text-white">Course Code</label>
                                    <input
                                        v-model="course.code"
                                        type="text"
                                        placeholder="e.g., CS-101"
                                        class="w-full rounded border-[1.5px] border-stroke bg-white px-4 py-2.5 transition outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                    />
                                </div>
                                <div class="md:col-span-2">
                                    <label class="mb-2 block text-sm font-medium text-black dark:text-white">
                                        Course Title <span class="text-meta-1">*</span>
                                    </label>
                                    <input
                                        v-model="course.title"
                                        type="text"
                                        required
                                        placeholder="e.g., Introduction to Programming"
                                        class="w-full rounded border-[1.5px] border-stroke bg-white px-4 py-2.5 transition outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                    />
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="form.courses_taught.length === 0"
                            class="rounded-lg border-2 border-dashed border-stroke bg-gray-50 p-12 text-center dark:border-strokedark dark:bg-meta-4"
                        >
                            <p class="text-gray-600 dark:text-gray-400">No courses added yet.</p>
                            <p class="mt-1 text-sm text-gray-500">Click "Add Course" to get started.</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 border-t border-stroke pt-6 dark:border-strokedark">
                        <button
                            type="submit"
                            :disabled="saving"
                            class="hover:bg-opacity-90 inline-flex items-center justify-center gap-2 rounded bg-primary px-6 py-3 font-medium text-white transition disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <Save class="h-5 w-5" />
                            <span>{{ saving ? 'Saving...' : 'Save Courses' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </TeacherProfileLayout>
</template>
