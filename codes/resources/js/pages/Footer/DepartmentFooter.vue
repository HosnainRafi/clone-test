<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

// --- INTERFACES & PROPS ---
interface FooterLink {
    text: string;
    href: string;
}

interface SocialLink {
    platform: 'facebook' | 'twitter' | 'linkedin' | 'youtube' | 'website';
    href: string;
}

interface FooterData {
    departmentName: string;
    departmentFullName: string;
    departmentDescription?: string;
    logoUrl: string;
    universityName: string;
    address: string;
    phone: string;
    email: string;
    website?: string;
    quickLinksTitle: string;
    quickLinks: FooterLink[];
    resourcesTitle: string;
    resourcesLinks: FooterLink[];
    academicLinksTitle?: string;
    academicLinks?: FooterLink[];
    socialLinks: SocialLink[];
    copyrightText: string;
    privacyPolicyUrl?: string;
    termsOfServiceUrl?: string;
    sitemapUrl?: string;
    showUniversityLink?: boolean;
    universityWebsite?: string;
}

interface PageProps {
    footerData: FooterData | null; // From Controller
    siteId: number | null;
}

const props = defineProps<PageProps>();

console.log('DepartmentFooter Props:', props);

// --- STATE MANAGEMENT ---
// Provide a default structure if FooterData is initially null
const defaultFooterData: FooterData = {
    departmentName: 'CSE',
    departmentFullName: 'Department of Computer Science & Engineering',
    departmentDescription: 'Leading innovation in computer science education and research, preparing students for the future of technology.',
    logoUrl: '/images/university/logo/MBSTU_logo.png',
    universityName: 'MBSTU',
    address: 'Santosh, Tangail-1902, Bangladesh',
    phone: '+880-921-55399',
    email: 'info.cse@mbstu.ac.bd',
    website: '',
    quickLinksTitle: 'Quick Links',
    quickLinks: [
        { text: 'About Department', href: '/about' },
        { text: 'Faculty Members', href: '/faculty' },
        { text: 'Academic Programs', href: '/programs' },
        { text: 'Research Areas', href: '/research' },
        { text: 'Laboratories', href: '/labs' },
        { text: 'Admissions', href: '/admissions' },
        { text: 'Publications', href: '/publications' }
    ],
    resourcesTitle: 'Resources',
    resourcesLinks: [
        { text: 'Digital Library', href: '/library' },
        { text: 'Course Curriculum', href: '/curriculum' },
        { text: 'Thesis Repository', href: '/thesis' },
        { text: 'Conferences', href: '/conferences' },
        { text: 'Academic Journals', href: '/journals' },
        { text: 'Events & Seminars', href: '/events' },
        { text: 'Career Services', href: '/career' }
    ],
    academicLinksTitle: 'Academic',
    academicLinks: [],
    socialLinks: [
        { platform: 'facebook', href: '#' },
        { platform: 'twitter', href: '#' },
        { platform: 'linkedin', href: '#' },
        { platform: 'youtube', href: '#' },
        { platform: 'website', href: 'https://mbstu.ac.bd' }
    ],
    copyrightText: `© ${new Date().getFullYear()} Department of Computer Science & Engineering, MBSTU`,
    privacyPolicyUrl: '/privacy',
    termsOfServiceUrl: '/terms',
    sitemapUrl: '/sitemap',
    showUniversityLink: true,
    universityWebsite: 'https://mbstu.ac.bd'
};

const localFooterData = ref<FooterData>(JSON.parse(JSON.stringify(props.footerData || defaultFooterData)));

const isSaving = ref(false);
const message = ref('');
const messageType = ref<'success' | 'error' | ''>();

// --- COMPUTED & WATCHERS ---
const originalDataString = computed(() => JSON.stringify(props.footerData || defaultFooterData));
const currentDataString = computed(() => JSON.stringify(localFooterData.value));
const hasUnsavedChanges = computed(() => currentDataString.value !== originalDataString.value);

// Watch for prop changes to reset local state if needed (e.g., after save)
watch(
    () => props.footerData,
    (newData) => {
        localFooterData.value = JSON.parse(JSON.stringify(newData || defaultFooterData));
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

// Methods to manage link lists
const addLink = (listType: 'quick' | 'resources' | 'academic') => {
    let list: FooterLink[];
    switch (listType) {
        case 'quick':
            list = localFooterData.value.quickLinks;
            break;
        case 'resources':
            list = localFooterData.value.resourcesLinks;
            break;
        case 'academic':
            if (!localFooterData.value.academicLinks) {
                localFooterData.value.academicLinks = [];
            }
            list = localFooterData.value.academicLinks;
            break;
        default:
            return;
    }
    list.push({ text: 'New Link', href: '#' });
};

const removeLink = (listType: 'quick' | 'resources' | 'academic', index: number) => {
    let list: FooterLink[];
    switch (listType) {
        case 'quick':
            list = localFooterData.value.quickLinks;
            break;
        case 'resources':
            list = localFooterData.value.resourcesLinks;
            break;
        case 'academic':
            if (!localFooterData.value.academicLinks) return;
            list = localFooterData.value.academicLinks;
            break;
        default:
            return;
    }
    list.splice(index, 1);
};

// Methods to manage social links
const addSocialLink = () => {
    localFooterData.value.socialLinks.push({ platform: 'website', href: '#' });
};
const removeSocialLink = (index: number) => {
    localFooterData.value.socialLinks.splice(index, 1);
};

const resetToOriginal = () => {
    if (confirm('Discard unsaved changes?')) {
        localFooterData.value = JSON.parse(JSON.stringify(props.footerData || defaultFooterData));
        showMessage('Changes discarded.', 'success');
    }
};

const validateAndSave = async () => {
    if (!props.siteId) return showMessage('Site ID is missing.', 'error');
    if (!hasUnsavedChanges.value) return showMessage('No changes to save.', 'success');

    // Basic validation example (add more specific checks if needed)
    if (!localFooterData.value.departmentName || !localFooterData.value.departmentFullName || !localFooterData.value.email) {
        return showMessage('Please fill in required fields: Department Name, Full Name, Email.', 'error');
    }

    isSaving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch('/admin/footer-section', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                Accept: 'application/json',
            },
            body: JSON.stringify({ footerData: localFooterData.value, siteId: props.siteId }),
        });
        const result = await response.json();
        if (response.ok && result.success) {
            showMessage(result.message, 'success');
            // Optionally reload only the department footer data prop if Inertia setup allows
            router.reload({ only: ['FooterData'] });
        } else {
            showMessage(result.message || 'Server error during save.', 'error');
        }
    } catch (error: any) {
        showMessage(`Network or saving error: ${error.message || 'Unknown error'}`, 'error');
    } finally {
        isSaving.value = false;
    }
};

const socialPlatforms: SocialLink['platform'][] = ['facebook', 'twitter', 'linkedin', 'youtube', 'website'];
</script>

<template>
    <DefaultLayout>
        <div
            class="shadow-default rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 sm:px-7.5 xl:pb-1 dark:border-strokedark dark:bg-boxdark"
        >
            <div class="mb-6">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h4 class="text-xl font-semibold text-black dark:text-white">Department Footer Management</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Site ID: {{ siteId || 'N/A' }}
                            <span v-if="hasUnsavedChanges" class="ml-2 text-warning">• Unsaved Changes</span>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button v-if="hasUnsavedChanges" @click="resetToOriginal" class="action-btn bg-secondary">Discard Changes</button>
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
                <div class="space-y-6 rounded-sm border border-stroke bg-gray-50 p-6 dark:border-strokedark dark:bg-meta-4">
                    <h5 class="text-lg font-medium text-black dark:text-white">Department Information</h5>
                    <div class="grid grid-cols-1 gap-4.5 sm:grid-cols-2">
                        <div>
                            <label class="form-label">Department Name <span class="text-meta-1">*</span></label>
                            <input v-model="localFooterData.departmentName" type="text" class="form-input" required />
                        </div>
                        <div>
                            <label class="form-label">Department Full Name <span class="text-meta-1">*</span></label>
                            <input v-model="localFooterData.departmentFullName" type="text" class="form-input" required />
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Department Description</label>
                        <textarea v-model="localFooterData.departmentDescription" rows="3" class="form-input"></textarea>
                    </div>
                    <div class="grid grid-cols-1 gap-4.5 sm:grid-cols-2">
                        <div>
                            <label class="form-label">Logo URL</label>
                            <input v-model="localFooterData.logoUrl" type="text" class="form-input" placeholder="e.g., /images/logo.png" />
                        </div>
                        <div>
                            <label class="form-label">University Name</label>
                            <input v-model="localFooterData.universityName" type="text" class="form-input" />
                        </div>
                    </div>

                    <h5 class="pt-4 text-lg font-medium text-black dark:text-white">Contact Details</h5>
                    <div class="grid grid-cols-1 gap-4.5 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label class="form-label">Address</label>
                            <input v-model="localFooterData.address" type="text" class="form-input" />
                        </div>
                        <div>
                            <label class="form-label">Phone</label>
                            <input v-model="localFooterData.phone" type="text" class="form-input" />
                        </div>
                        <div>
                            <label class="form-label">Email <span class="text-meta-1">*</span></label>
                            <input v-model="localFooterData.email" type="email" class="form-input" required />
                        </div>
                        <div>
                            <label class="form-label">Website</label>
                            <input v-model="localFooterData.website" type="url" class="form-input" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                        <!-- Quick Links -->
                        <div class="space-y-4 rounded border border-stroke p-4 dark:border-strokedark">
                            <h5 class="text-lg font-medium text-black dark:text-white">Quick Links</h5>
                            <div>
                                <label class="form-label">Section Title</label>
                                <input v-model="localFooterData.quickLinksTitle" type="text" class="form-input" />
                            </div>
                            <div v-for="(link, index) in localFooterData.quickLinks" :key="`quick-${index}`" class="flex items-end gap-2">
                                <div class="flex-1">
                                    <label class="form-label text-xs">Link Text</label>
                                    <input v-model="link.text" type="text" class="form-input text-sm" />
                                </div>
                                <div class="flex-1">
                                    <label class="form-label text-xs">URL (href)</label>
                                    <input v-model="link.href" type="text" class="form-input text-sm" />
                                </div>
                                <button type="button" @click="removeLink('quick', index)" class="action-btn !h-[44px] bg-danger !px-3 text-xs">
                                    X
                                </button>
                            </div>
                            <button type="button" @click="addLink('quick')" class="action-btn bg-primary text-sm">Add Quick Link</button>
                        </div>

                        <!-- Resources Links -->
                        <div class="space-y-4 rounded border border-stroke p-4 dark:border-strokedark">
                            <h5 class="text-lg font-medium text-black dark:text-white">Resources Links</h5>
                            <div>
                                <label class="form-label">Section Title</label>
                                <input v-model="localFooterData.resourcesTitle" type="text" class="form-input" />
                            </div>
                            <div v-for="(link, index) in localFooterData.resourcesLinks" :key="`resources-${index}`" class="flex items-end gap-2">
                                <div class="flex-1">
                                    <label class="form-label text-xs">Link Text</label>
                                    <input v-model="link.text" type="text" class="form-input text-sm" />
                                </div>
                                <div class="flex-1">
                                    <label class="form-label text-xs">URL (href)</label>
                                    <input v-model="link.href" type="text" class="form-input text-sm" />
                                </div>
                                <button type="button" @click="removeLink('resources', index)" class="action-btn !h-[44px] bg-danger !px-3 text-xs">
                                    X
                                </button>
                            </div>
                            <button type="button" @click="addLink('resources')" class="action-btn bg-primary text-sm">Add Resource Link</button>
                        </div>

                        <!-- Academic Links (Optional) -->
                        <div class="space-y-4 rounded border border-stroke p-4 dark:border-strokedark">
                            <h5 class="text-lg font-medium text-black dark:text-white">Academic Links (Optional)</h5>
                            <div>
                                <label class="form-label">Section Title</label>
                                <input v-model="localFooterData.academicLinksTitle" type="text" class="form-input" />
                            </div>
                            <div v-for="(link, index) in localFooterData.academicLinks || []" :key="`academic-${index}`" class="flex items-end gap-2">
                                <div class="flex-1">
                                    <label class="form-label text-xs">Link Text</label>
                                    <input v-model="link.text" type="text" class="form-input text-sm" />
                                </div>
                                <div class="flex-1">
                                    <label class="form-label text-xs">URL (href)</label>
                                    <input v-model="link.href" type="text" class="form-input text-sm" />
                                </div>
                                <button type="button" @click="removeLink('academic', index)" class="action-btn !h-[44px] bg-danger !px-3 text-xs">
                                    X
                                </button>
                            </div>
                            <button type="button" @click="addLink('academic')" class="action-btn bg-primary text-sm">Add Academic Link</button>
                        </div>
                    </div>

                    <h5 class="pt-4 text-lg font-medium text-black dark:text-white">Social Media Links</h5>
                    <div class="space-y-4 rounded border border-stroke p-4 dark:border-strokedark">
                        <div v-for="(link, index) in localFooterData.socialLinks" :key="`social-${index}`" class="flex items-end gap-2">
                            <div class="w-1/3">
                                <label class="form-label text-xs">Platform</label>
                                <select v-model="link.platform" class="form-input text-sm">
                                    <option v-for="p in socialPlatforms" :key="p" :value="p">{{ p.charAt(0).toUpperCase() + p.slice(1) }}</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="form-label text-xs">Full URL</label>
                                <input v-model="link.href" type="url" class="form-input text-sm" placeholder="https://..." />
                            </div>
                            <button type="button" @click="removeSocialLink(index)" class="action-btn !h-[44px] bg-danger !px-3 text-xs">X</button>
                        </div>
                        <button type="button" @click="addSocialLink" class="action-btn bg-primary text-sm">Add Social Link</button>
                    </div>

                    <h5 class="pt-4 text-lg font-medium text-black dark:text-white">Footer Bottom & Additional Settings</h5>
                    <div class="grid grid-cols-1 gap-4.5 sm:grid-cols-2">
                        <div>
                            <label class="form-label">Copyright Text</label>
                            <input v-model="localFooterData.copyrightText" type="text" class="form-input" />
                        </div>
                        <div class="flex items-center">
                            <input v-model="localFooterData.showUniversityLink" type="checkbox" id="showUniversityLink" class="mr-2" />
                            <label for="showUniversityLink" class="form-label !mb-0">Show University Link</label>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4.5 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label class="form-label">University Website</label>
                            <input v-model="localFooterData.universityWebsite" type="url" class="form-input" />
                        </div>
                        <div>
                            <label class="form-label">Privacy Policy URL</label>
                            <input v-model="localFooterData.privacyPolicyUrl" type="text" class="form-input" />
                        </div>
                        <div>
                            <label class="form-label">Terms of Service URL</label>
                            <input v-model="localFooterData.termsOfServiceUrl" type="text" class="form-input" />
                        </div>
                        <div>
                            <label class="form-label">Sitemap URL</label>
                            <input v-model="localFooterData.sitemapUrl" type="text" class="form-input" />
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button
                        type="submit"
                        :disabled="isSaving || !siteId || !hasUnsavedChanges"
                        class="save-btn"
                        :class="{
                            'hover:bg-opacity-90 bg-primary': hasUnsavedChanges,
                            'cursor-not-allowed bg-gray-400': isSaving || !siteId || !hasUnsavedChanges,
                        }"
                    >
                        {{ isSaving ? 'Saving...' : 'Save Department Footer Changes' }}
                    </button>
                </div>
            </form>
        </div>
    </DefaultLayout>
</template>

<style scoped>
/* Re-using styles from your other admin pages */
@reference '../../../../resources/css/app.css';
.action-btn {
    @apply inline-flex items-center justify-center rounded-md px-4 py-2 text-center text-sm font-medium text-white transition;
}
.form-label {
    @apply mb-2.5 block font-medium text-black dark:text-white;
}
.form-input {
    @apply w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary;
}
select.form-input {
    @apply appearance-none bg-whiter pr-8 dark:bg-form-input;
}
textarea.form-input {
    @apply min-h-[100px] py-2.5;
}
.save-btn {
    @apply inline-flex items-center justify-center rounded-md px-6 py-3 text-center font-medium text-white transition-colors;
}
.text-meta-1 {
    color: #dc3545;
}
</style>