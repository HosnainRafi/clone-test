<script setup lang="ts">
import TeacherProfileLayout from '@/layouts/TeacherProfileLayout.vue';
import { useToast } from '@/composables/useToast';
import { Plus, Save, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';

const toast = useToast();

interface Publication {
    title: string;
    authors?: string;
    journal?: string;
    conference?: string;
    year?: number;
    link?: string;
    category?: string;
}

interface Teacher {
    id: number;
    name: string;
    publications: Publication[];
}

interface Props {
    teacher: Teacher;
}

const props = defineProps<Props>();

const form = ref<{ publications: Publication[] }>({
    publications: props.teacher.publications.length > 0 ? [...props.teacher.publications] : [],
});

const saving = ref(false);

const addPublication = () => {
    form.value.publications.push({
        title: '',
        authors: '',
        journal: '',
        conference: '',
        year: new Date().getFullYear(),
        link: '',
        category: '',
    });
};

const removePublication = (index: number) => {
    if (confirm('Are you sure you want to remove this publication?')) {
        form.value.publications.splice(index, 1);
    }
};

const savePublications = async () => {
    saving.value = true;

    try {
        const response = await fetch('/admin/teacher/profile/publications', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify(form.value),
        });

        const data = await response.json();

        if (data.success) {
            toast.success('Publications updated successfully!');
        } else {
            toast.error('Failed to update publications');
        }
    } catch (error) {
        console.error('Error saving:', error);
        toast.error('Error saving publications');
    } finally {
        saving.value = false;
    }
};
</script>

<template>
    <TeacherProfileLayout :teacher="{ id: props.teacher.id, name: props.teacher.name }">
        <div class="shadow-default rounded-sm border border-stroke bg-white dark:border-strokedark dark:bg-boxdark">
            <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                <h3 class="font-semibold text-black dark:text-white">Publications</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Manage your research publications, journal articles, and conference papers.
                </p>
            </div>

            <div class="p-6.5">
                <form @submit.prevent="savePublications" class="space-y-6">
                    <!-- Add Publication Button -->
                    <div class="flex justify-end">
                        <button
                            type="button"
                            @click="addPublication"
                            class="hover:bg-opacity-90 inline-flex items-center gap-2 rounded bg-primary px-4 py-2 text-sm font-medium text-white transition"
                        >
                            <Plus class="h-4 w-4" />
                            <span>Add Publication</span>
                        </button>
                    </div>

                    <!-- Publications List -->
                    <div class="space-y-6">
                        <div
                            v-for="(pub, index) in form.publications"
                            :key="index"
                            class="rounded-lg border border-stroke bg-gray-50 p-5 dark:border-strokedark dark:bg-meta-4"
                        >
                            <div class="mb-4 flex items-center justify-between">
                                <h4 class="font-medium text-black dark:text-white">Publication #{{ index + 1 }}</h4>
                                <button
                                    type="button"
                                    @click="removePublication(index)"
                                    class="hover:bg-opacity-90 inline-flex items-center gap-1 rounded bg-danger px-3 py-1.5 text-sm text-white transition"
                                >
                                    <Trash2 class="h-4 w-4" />
                                    <span>Remove</span>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <!-- Title -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-black dark:text-white">
                                        Title <span class="text-meta-1">*</span>
                                    </label>
                                    <input
                                        v-model="pub.title"
                                        type="text"
                                        required
                                        placeholder="Publication title"
                                        class="w-full rounded border-[1.5px] border-stroke bg-white px-4 py-2.5 transition outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                    />
                                </div>

                                <!-- Authors and Category -->
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-black dark:text-white">Authors</label>
                                        <input
                                            v-model="pub.authors"
                                            type="text"
                                            placeholder="Author names"
                                            class="w-full rounded border-[1.5px] border-stroke bg-white px-4 py-2.5 transition outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                        />
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-black dark:text-white">Category</label>
                                        <select
                                            v-model="pub.category"
                                            class="w-full rounded border-[1.5px] border-stroke bg-white px-4 py-2.5 transition outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                        >
                                            <option value="">Select category</option>
                                            <option value="Journal Article">Journal Article</option>
                                            <option value="Conference Paper">Conference Paper</option>
                                            <option value="Book Chapter">Book Chapter</option>
                                            <option value="Book">Book</option>
                                            <option value="Technical Report">Technical Report</option>
                                            <option value="Thesis">Thesis</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Journal/Conference and Year -->
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-black dark:text-white">Journal/Conference</label>
                                        <input
                                            v-model="pub.journal"
                                            type="text"
                                            placeholder="Journal or Conference name"
                                            class="w-full rounded border-[1.5px] border-stroke bg-white px-4 py-2.5 transition outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                        />
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-black dark:text-white">Year</label>
                                        <input
                                            v-model.number="pub.year"
                                            type="number"
                                            placeholder="2024"
                                            class="w-full rounded border-[1.5px] border-stroke bg-white px-4 py-2.5 transition outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                        />
                                    </div>
                                </div>

                                <!-- Link -->
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-black dark:text-white">Publication Link</label>
                                    <input
                                        v-model="pub.link"
                                        type="url"
                                        placeholder="https://..."
                                        class="w-full rounded border-[1.5px] border-stroke bg-white px-4 py-2.5 transition outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div
                            v-if="form.publications.length === 0"
                            class="rounded-lg border-2 border-dashed border-stroke bg-gray-50 p-12 text-center dark:border-strokedark dark:bg-meta-4"
                        >
                            <p class="text-gray-600 dark:text-gray-400">No publications added yet.</p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-500">Click "Add Publication" to get started.</p>
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
                            <span>{{ saving ? 'Saving...' : 'Save Publications' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </TeacherProfileLayout>
</template>
