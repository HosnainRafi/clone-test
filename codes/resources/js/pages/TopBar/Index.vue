<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router, usePage } from '@inertiajs/vue3';
import { Trash2 } from 'lucide-vue-next';
import { ref, computed } from 'vue';

// Define interfaces
interface TopBarLink {
    id?: number;
    title: string;
    href: string;
}

interface LoginLink {
    title: string;
    href: string;
}

interface PageProps {
    siteData: any;
    topBarLinks: TopBarLink[];
    loginLink: LoginLink | null;
    contactEmail: string | null;
    address: string | null;
    siteId: number | null;
}

const props = defineProps<PageProps>();
const page = usePage();

const siteData = computed(() => {
  return props.siteData || page.props.siteData || null
})

// State management
const { siteId } = props;
const topBarLinks = ref<TopBarLink[]>(props.topBarLinks.map((link, index) => ({ ...link, id: Date.now() + index })));
// FIX: Default to empty strings instead of "LOGIN".
// This works with the controller change to correctly show empty fields if null.
const loginLink = ref<LoginLink>(props.loginLink || { title: '', href: '' });
const contactEmail = ref<string>(props.contactEmail || '');
const address = ref<string>(props.address || '');

const isSaving = ref(false);

// Functions to manage university top bar links
const addLink = () => {
    topBarLinks.value.push({
        id: Date.now(),
        title: 'New Link',
        href: 'https://',
    });
};

const removeLink = (id: number) => {
    topBarLinks.value = topBarLinks.value.filter((link) => link.id !== id);
};

// Function to save the configuration
const saveConfiguration = () => {
    if (!siteId) return;

    const payload = {
        siteId,
        topBarLinks: topBarLinks.value.map(({ title, href }) => ({ title, href })),
        // FIX: Send `null` if both title and href are empty.
        // This allows the controller to validate correctly and save `null` to the DB.
        loginLink: loginLink.value.title || loginLink.value.href ? loginLink.value : null,
        contactEmail: contactEmail.value,
        address: address.value,
    };

    // POST to the admin route so it matches the server route defined in routes/web.php
    router.post('/admin/topbar', payload, {
        preserveState: true,
        onStart: () => (isSaving.value = true),
        onFinish: () => (isSaving.value = false),
    });
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
                        <h4 class="text-xl font-semibold text-black dark:text-white">Manage Top Bar Configuration</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Site: {{ props.siteData?.name }} ({{ props.siteData?.domain }})</p>
                    </div>
                </div>
            </div>

            <!-- Status Messages -->
            <div v-if="$page.props.flash?.success" class="mb-4 rounded-md border border-success bg-success/10 px-4 py-3 text-success">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error" class="mb-4 rounded-md border border-danger bg-danger/10 px-4 py-3 text-danger">
                {{ $page.props.flash.error }}
            </div>

            <!-- University Links Section -->
            <div v-if="!siteData" class="mb-6 rounded-sm border border-stroke bg-gray-50 p-4 dark:bg-meta-4">
                <div class="mb-4 flex items-start justify-between">
                    <div class="flex-1">
                        <div class="mb-3">
                            <label class="mb-2.5 block font-medium text-black dark:text-white"> University Links (for non-department pages) </label>
                            <div v-for="link in topBarLinks" :key="link.id" class="mb-4 grid grid-cols-1 items-center gap-4 md:grid-cols-8">
                                <div class="md:col-span-3">
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Link Title</label>
                                    <input
                                        v-model="link.title"
                                        type="text"
                                        placeholder="Title"
                                        :class="{
                                            'border-danger': !link.title,
                                            'border-stroke': link.title,
                                        }"
                                        class="w-full rounded border-[1.5px] bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                    />
                                </div>
                                <div class="md:col-span-4">
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Link URL (href)</label>
                                    <input
                                        v-model="link.href"
                                        type="text"
                                        placeholder="https://..."
                                        :class="{
                                            'border-danger': !link.href,
                                            'border-stroke': link.href,
                                        }"
                                        class="w-full rounded border-[1.5px] bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                    />
                                </div>
                                <div class="flex h-full items-end md:col-span-1">
                                    <button @click="removeLink(link.id!)" class="hover:text-opacity-90 p-2 text-danger">
                                        <Trash2 class="h-5 w-5" />
                                    </button>
                                </div>
                            </div>
                            <button
                                @click="addLink"
                                class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-center font-medium text-white"
                            >
                                Add University Link
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Contact Info Section -->
            <div class="mb-6 rounded-sm border border-stroke bg-gray-50 p-4 dark:bg-meta-4">
                <div class="flex-1">
                    <div class="mb-3">
                        <label class="mb-2.5 block font-medium text-black dark:text-white"> Department Contact Info (for department pages) </label>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-black dark:text-white">Contact Email</label>
                                <input
                                    v-model="contactEmail"
                                    type="email"
                                    placeholder="email@example.com"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-black dark:text-white">Address</label>
                                <input
                                    v-model="address"
                                    type="text"
                                    placeholder="Location, City, Country"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Login Link Section -->
            <div v-if="!siteData" class="mb-6 rounded-sm border border-stroke bg-gray-50 p-4 dark:bg-meta-4">
                <div class="flex-1">
                    <div class="mb-3">
                        <label class="mb-2.5 block font-medium text-black dark:text-white"> Login Link (common) </label>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-black dark:text-white">Login Title</label>
                                <input
                                    v-model="loginLink.title"
                                    type="text"
                                    placeholder="Title"
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-black dark:text-white">Login URL (href)</label>
                                <input
                                    v-model="loginLink.href"
                                    type="text"
                                    placeholder="https://..."
                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium transition outline-none focus:border-primary active:border-primary"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Actions -->
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <span v-if="isSaving" class="text-warning"> 📝 Saving changes... </span>
                    <span v-else class="text-success"> ✅ Ready to save </span>
                </div>

                <div class="flex gap-2">
                    <button
                        @click="saveConfiguration"
                        :disabled="isSaving"
                        :class="{
                            'hover:bg-opacity-90 bg-primary': !isSaving,
                            'cursor-not-allowed bg-gray-400': isSaving,
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
                        {{ isSaving ? 'Saving...' : 'Save Top Bar Configuration' }}
                    </button>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>
