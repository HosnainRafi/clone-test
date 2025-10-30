<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

// --- INTERFACES & PROPS ---
// Re-use the interfaces from DynamicFooter.vue
interface FooterLink {
    text: string;
    href: string;
}

interface SocialLink {
    platform: 'facebook' | 'twitter' | 'linkedin' | 'youtube' | 'website';
    href: string;
}

interface FooterData {
    universityName: string;
    universityFullName: string;
    universitySlogan?: string;
    logoUrl: string;
    address: string;
    phone: string;
    email: string;
    academicLinksTitle: string;
    academicLinks: FooterLink[];
    usefulLinksTitle: string;
    usefulLinks: FooterLink[];
    socialLinks: SocialLink[];
    copyrightText: string;
    liaisonOfficeTitle?: string;
    liaisonOfficeAddress?: string;
}

interface PageProps {
    footerData: FooterData | null; // From Controller
    siteId: number | null;
}

const props = defineProps<PageProps>();

// --- STATE MANAGEMENT ---
// Provide a default structure if footerData is initially null
const defaultFooterData: FooterData = {
    universityName: 'MBSTU',
    universityFullName: 'Mawlana Bhashani Science and Technology University',
    universitySlogan: '',
    logoUrl: '/images/university/logo/MBSTU_logo.png',
    address: '',
    phone: '',
    email: '',
    academicLinksTitle: 'Academics',
    academicLinks: [],
    usefulLinksTitle: 'Useful Links',
    usefulLinks: [],
    socialLinks: [],
    copyrightText: `© ${new Date().getFullYear()} MBSTU`,
    liaisonOfficeTitle: 'Dhaka Liaison Office:',
    liaisonOfficeAddress: '',
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
const addLink = (listType: 'academic' | 'useful') => {
    const list = listType === 'academic' ? localFooterData.value.academicLinks : localFooterData.value.usefulLinks;
    list.push({ text: 'New Link', href: '#' });
};
const removeLink = (listType: 'academic' | 'useful', index: number) => {
    const list = listType === 'academic' ? localFooterData.value.academicLinks : localFooterData.value.usefulLinks;
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
    if (!localFooterData.value.universityName || !localFooterData.value.universityFullName || !localFooterData.value.email) {
        return showMessage('Please fill in required fields: University Name, Full Name, Email.', 'error');
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
            // Optionally reload only the footer data prop if Inertia setup allows
            router.reload({ only: ['footerData'] });
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
                        <h4 class="text-xl font-semibold text-black dark:text-white">Footer Section Management</h4>
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
                    <h5 class="text-lg font-medium text-black dark:text-white">Basic Information</h5>
                    <div class="grid grid-cols-1 gap-4.5 sm:grid-cols-2">
                        <div>
                            <label class="form-label">University Name <span class="text-meta-1">*</span></label
                            ><input v-model="localFooterData.universityName" type="text" class="form-input" required />
                        </div>
                        <div>
                            <label class="form-label">University Full Name <span class="text-meta-1">*</span></label
                            ><input v-model="localFooterData.universityFullName" type="text" class="form-input" required />
                        </div>
                    </div>
                    <div>
                        <label class="form-label">University Slogan/Description</label
                        ><textarea v-model="localFooterData.universitySlogan" rows="3" class="form-input"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Logo URL</label
                        ><input v-model="localFooterData.logoUrl" type="text" class="form-input" placeholder="e.g., /images/logo.png" />
                    </div>

                    <h5 class="pt-4 text-lg font-medium text-black dark:text-white">Contact Details</h5>
                    <div class="grid grid-cols-1 gap-4.5 sm:grid-cols-3">
                        <div><label class="form-label">Address</label><input v-model="localFooterData.address" type="text" class="form-input" /></div>
                        <div><label class="form-label">Phone</label><input v-model="localFooterData.phone" type="text" class="form-input" /></div>
                        <div>
                            <label class="form-label">Email <span class="text-meta-1">*</span></label
                            ><input v-model="localFooterData.email" type="email" class="form-input" required />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <div class="space-y-4 rounded border border-stroke p-4 dark:border-strokedark">
                            <h5 class="text-lg font-medium text-black dark:text-white">Academic Links</h5>
                            <div>
                                <label class="form-label">Section Title</label
                                ><input v-model="localFooterData.academicLinksTitle" type="text" class="form-input" />
                            </div>
                            <div v-for="(link, index) in localFooterData.academicLinks" :key="`academic-${index}`" class="flex items-end gap-2">
                                <div class="flex-1">
                                    <label class="form-label text-xs">Link Text</label
                                    ><input v-model="link.text" type="text" class="form-input text-sm" />
                                </div>
                                <div class="flex-1">
                                    <label class="form-label text-xs">URL (href)</label
                                    ><input v-model="link.href" type="text" class="form-input text-sm" />
                                </div>
                                <button type="button" @click="removeLink('academic', index)" class="action-btn !h-[44px] bg-danger !px-3 text-xs">
                                    X
                                </button>
                            </div>
                            <button type="button" @click="addLink('academic')" class="action-btn bg-primary text-sm">Add Academic Link</button>
                        </div>

                        <div class="space-y-4 rounded border border-stroke p-4 dark:border-strokedark">
                            <h5 class="text-lg font-medium text-black dark:text-white">Useful Links</h5>
                            <div>
                                <label class="form-label">Section Title</label
                                ><input v-model="localFooterData.usefulLinksTitle" type="text" class="form-input" />
                            </div>
                            <div v-for="(link, index) in localFooterData.usefulLinks" :key="`useful-${index}`" class="flex items-end gap-2">
                                <div class="flex-1">
                                    <label class="form-label text-xs">Link Text</label
                                    ><input v-model="link.text" type="text" class="form-input text-sm" />
                                </div>
                                <div class="flex-1">
                                    <label class="form-label text-xs">URL (href)</label
                                    ><input v-model="link.href" type="text" class="form-input text-sm" />
                                </div>
                                <button type="button" @click="removeLink('useful', index)" class="action-btn !h-[44px] bg-danger !px-3 text-xs">
                                    X
                                </button>
                            </div>
                            <button type="button" @click="addLink('useful')" class="action-btn bg-primary text-sm">Add Useful Link</button>
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
                                <label class="form-label text-xs">Full URL</label
                                ><input v-model="link.href" type="url" class="form-input text-sm" placeholder="https://..." />
                            </div>
                            <button type="button" @click="removeSocialLink(index)" class="action-btn !h-[44px] bg-danger !px-3 text-xs">X</button>
                        </div>
                        <button type="button" @click="addSocialLink" class="action-btn bg-primary text-sm">Add Social Link</button>
                    </div>

                    <h5 class="pt-4 text-lg font-medium text-black dark:text-white">Footer Bottom</h5>
                    <div>
                        <label class="form-label">Copyright Text</label
                        ><input v-model="localFooterData.copyrightText" type="text" class="form-input" />
                    </div>
                    <div class="grid grid-cols-1 gap-4.5 sm:grid-cols-2">
                        <div>
                            <label class="form-label">Liaison Office Title (Optional)</label
                            ><input v-model="localFooterData.liaisonOfficeTitle" type="text" class="form-input" />
                        </div>
                        <div>
                            <label class="form-label">Liaison Office Address (Optional)</label
                            ><input v-model="localFooterData.liaisonOfficeAddress" type="text" class="form-input" />
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
                        {{ isSaving ? 'Saving...' : 'Save Footer Changes' }}
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
