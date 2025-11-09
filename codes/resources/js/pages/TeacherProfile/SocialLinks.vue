<script setup lang="ts">
import TeacherProfileLayout from '@/layouts/TeacherProfileLayout.vue';
import { useToast } from '@/composables/useToast';
import { FileText, GraduationCap, Linkedin, Save } from 'lucide-vue-next';
import { ref } from 'vue';

const toast = useToast();

interface SocialLinks {
    linkedin?: string;
    google_scholar?: string;
    researchgate?: string;
}

interface Teacher {
    id: number;
    name: string;
    social_links: SocialLinks;
}

interface Props {
    teacher: Teacher;
}

const props = defineProps<Props>();

const form = ref<{ social_links: SocialLinks }>({
    social_links: props.teacher.social_links || {},
});

const saving = ref(false);

const saveSocialLinks = async () => {
    saving.value = true;

    try {
        const response = await fetch('/admin/teacher/profile/social-links', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify(form.value),
        });

        const data = await response.json();

        if (data.success) {
            toast.success('Social links updated successfully!');
        } else {
            toast.error('Failed to update social links');
        }
    } catch (error) {
        console.error('Error saving:', error);
        toast.error('Error saving social links');
    } finally {
        saving.value = false;
    }
};
</script>

<template>
    <TeacherProfileLayout :teacher="{ id: props.teacher.id, name: props.teacher.name }">
        <div class="shadow-default rounded-sm border border-stroke bg-white dark:border-strokedark dark:bg-boxdark">
            <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                <h3 class="font-semibold text-black dark:text-white">Social & Academic Links</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Connect your professional and academic profiles.</p>
            </div>

            <div class="p-6.5">
                <form @submit.prevent="saveSocialLinks" class="space-y-6">
                    <!-- LinkedIn -->
                    <div class="rounded-lg border border-stroke bg-gray-50 p-5 dark:border-strokedark dark:bg-meta-4">
                        <div class="mb-4 flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100">
                                <Linkedin class="h-6 w-6 text-blue-600" />
                            </div>
                            <div>
                                <h4 class="font-semibold text-black dark:text-white">LinkedIn</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Professional networking platform</p>
                            </div>
                        </div>
                        <input
                            v-model="form.social_links.linkedin"
                            type="url"
                            placeholder="https://linkedin.com/in/yourprofile"
                            class="w-full rounded border-[1.5px] border-stroke bg-white px-4 py-2.5 transition outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                        />
                    </div>

                    <!-- Google Scholar -->
                    <div class="rounded-lg border border-stroke bg-gray-50 p-5 dark:border-strokedark dark:bg-meta-4">
                        <div class="mb-4 flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                                <GraduationCap class="h-6 w-6 text-red-600" />
                            </div>
                            <div>
                                <h4 class="font-semibold text-black dark:text-white">Google Scholar</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Research publications and citations</p>
                            </div>
                        </div>
                        <input
                            v-model="form.social_links.google_scholar"
                            type="url"
                            placeholder="https://scholar.google.com/citations?user=..."
                            class="w-full rounded border-[1.5px] border-stroke bg-white px-4 py-2.5 transition outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                        />
                    </div>

                    <!-- ResearchGate -->
                    <div class="rounded-lg border border-stroke bg-gray-50 p-5 dark:border-strokedark dark:bg-meta-4">
                        <div class="mb-4 flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-teal-100">
                                <FileText class="h-6 w-6 text-teal-600" />
                            </div>
                            <div>
                                <h4 class="font-semibold text-black dark:text-white">ResearchGate</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Academic research network</p>
                            </div>
                        </div>
                        <input
                            v-model="form.social_links.researchgate"
                            type="url"
                            placeholder="https://researchgate.net/profile/yourprofile"
                            class="w-full rounded border-[1.5px] border-stroke bg-white px-4 py-2.5 transition outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                        />
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-4 border-t border-stroke pt-6 dark:border-strokedark">
                        <button
                            type="submit"
                            :disabled="saving"
                            class="hover:bg-opacity-90 inline-flex items-center justify-center gap-2 rounded bg-primary px-6 py-3 font-medium text-white transition disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <Save class="h-5 w-5" />
                            <span>{{ saving ? 'Saving...' : 'Save Social Links' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </TeacherProfileLayout>
</template>
