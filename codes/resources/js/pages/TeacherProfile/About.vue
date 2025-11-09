<script setup lang="ts">
import TiptapEditor from '@/components/TiptapEditor.vue';
import TeacherProfileLayout from '@/layouts/TeacherProfileLayout.vue';
import { useToast } from '@/composables/useToast';
import { Save } from 'lucide-vue-next';
import { ref } from 'vue';

const toast = useToast();

interface Teacher {
    id: number;
    name: string;
    about_me?: string;
}

interface Props {
    teacher: Teacher;
}

const props = defineProps<Props>();

const form = ref<{ about_me: string }>({
    about_me: props.teacher.about_me || '',
});

const saving = ref(false);

const saveAbout = async () => {
    saving.value = true;

    try {
        const response = await fetch('/admin/teacher/profile/about', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify(form.value),
        });

        const data = await response.json();

        if (data.success) {
            toast.success('About section updated successfully!');
        } else {
            toast.error('Failed to update about section');
        }
    } catch (error) {
        console.error('Error saving:', error);
        toast.error('Error saving about section');
    } finally {
        saving.value = false;
    }
};
</script>

<template>
    <TeacherProfileLayout :teacher="{ id: props.teacher.id, name: props.teacher.name }">
        <div class="shadow-default rounded-sm border border-stroke bg-white dark:border-strokedark dark:bg-boxdark">
            <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                <h3 class="font-semibold text-black dark:text-white">About Me</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Share your biography, research interests, and professional background.</p>
            </div>

            <div class="p-6.5">
                <form @submit.prevent="saveAbout" class="space-y-6">
                    <!-- About Me Editor -->
                    <div>
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">About Me</label>
                        <TiptapEditor v-model="form.about_me" placeholder="Tell us about yourself..." />
                        <p class="mt-2 text-xs text-gray-500">Use the editor to format your text with headings, lists, and links.</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-4">
                        <button
                            type="submit"
                            :disabled="saving"
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
