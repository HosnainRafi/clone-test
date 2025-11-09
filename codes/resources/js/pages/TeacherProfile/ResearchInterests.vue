<script setup lang="ts">
import TeacherProfileLayout from '@/layouts/TeacherProfileLayout.vue';
import { useToast } from '@/composables/useToast';
import { Plus, Save, X } from 'lucide-vue-next';
import { ref } from 'vue';

const toast = useToast();

interface Teacher {
    id: number;
    name: string;
    research_interests: string[];
}

interface Props {
    teacher: Teacher;
}

const props = defineProps<Props>();

const form = ref<{ research_interests: string[] }>({
    research_interests: props.teacher.research_interests.length > 0 ? [...props.teacher.research_interests] : [],
});

const saving = ref(false);
const newInterest = ref('');

const addInterest = () => {
    if (newInterest.value.trim()) {
        form.value.research_interests.push(newInterest.value.trim());
        newInterest.value = '';
    }
};

const removeInterest = (index: number) => {
    form.value.research_interests.splice(index, 1);
};

const saveResearchInterests = async () => {
    saving.value = true;

    try {
        const response = await fetch('/admin/teacher/profile/research-interests', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify(form.value),
        });

        const data = await response.json();

        if (data.success) {
            toast.success('Research interests updated successfully!');
        } else {
            toast.error('Failed to update research interests');
        }
    } catch (error) {
        console.error('Error saving:', error);
        toast.error('Error saving research interests');
    } finally {
        saving.value = false;
    }
};
</script>

<template>
    <TeacherProfileLayout :teacher="{ id: props.teacher.id, name: props.teacher.name }">
        <div class="shadow-default rounded-sm border border-stroke bg-white dark:border-strokedark dark:bg-boxdark">
            <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                <h3 class="font-semibold text-black dark:text-white">Research Interests</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Add keywords and topics that represent your research areas.</p>
            </div>

            <div class="p-6.5">
                <form @submit.prevent="saveResearchInterests" class="space-y-6">
                    <!-- Add Interest Input -->
                    <div>
                        <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">Add Research Interest</label>
                        <div class="flex gap-2">
                            <input
                                v-model="newInterest"
                                type="text"
                                placeholder="e.g., Machine Learning, Data Science"
                                @keypress.enter.prevent="addInterest"
                                class="flex-1 rounded border-[1.5px] border-stroke bg-transparent px-4 py-2.5 transition outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                            />
                            <button
                                type="button"
                                @click="addInterest"
                                class="hover:bg-opacity-90 inline-flex items-center gap-2 rounded bg-primary px-4 py-2.5 text-sm font-medium text-white transition"
                            >
                                <Plus class="h-4 w-4" />
                                <span>Add</span>
                            </button>
                        </div>
                        <p class="mt-1.5 text-xs text-gray-500">Press Enter or click Add to include the interest</p>
                    </div>

                    <!-- Current Interests -->
                    <div>
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Current Research Interests ({{ form.research_interests.length }})
                        </label>
                        <div v-if="form.research_interests.length > 0" class="flex flex-wrap gap-2">
                            <span
                                v-for="(interest, index) in form.research_interests"
                                :key="index"
                                class="inline-flex items-center gap-2 rounded-full border border-success bg-success/10 px-4 py-2 text-sm font-medium text-success transition-colors hover:bg-success/20"
                            >
                                {{ interest }}
                                <button type="button" @click="removeInterest(index)" class="rounded-full transition-colors hover:bg-success/30">
                                    <X class="h-4 w-4" />
                                </button>
                            </span>
                        </div>
                        <div
                            v-else
                            class="rounded-lg border-2 border-dashed border-stroke bg-gray-50 p-8 text-center dark:border-strokedark dark:bg-meta-4"
                        >
                            <p class="text-gray-600 dark:text-gray-400">No research interests added yet.</p>
                            <p class="mt-1 text-sm text-gray-500">Add your first research interest above.</p>
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
                            <span>{{ saving ? 'Saving...' : 'Save Research Interests' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </TeacherProfileLayout>
</template>
