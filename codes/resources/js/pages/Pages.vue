<template>
    <DefaultLayout>
        <!-- Breadcrumb -->
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black">Pages Management</h2>
            <nav>
                <ol class="flex items-center gap-2">
                    <li>
                        <Link class="font-medium" href="/admin/dashboard">Dashboard /</Link>
                    </li>
                    <li class="font-medium text-primary">Pages</li>
                </ol>
            </nav>
        </div>

        <!-- Create New Page Button -->
        <div class="mb-6 flex justify-end">
            <Link
                href="/admin/pages/create"
                class="hover:bg-opacity-90 inline-flex items-center justify-center rounded-md bg-primary px-6 py-3 text-center font-medium text-white lg:px-8 xl:px-10"
            >
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create New Page
            </Link>
        </div>

        <!-- Pages Table -->
        <div
            class="shadow-default rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 sm:px-7.5 xl:pb-1 dark:border-strokedark dark:bg-boxdark"
        >
            <div class="max-w-full overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-2 text-left dark:bg-meta-4">
                            <th class="min-w-[220px] px-4 py-4 font-medium text-black xl:pl-11 dark:text-white">Title</th>
                            <th class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white">Slug</th>
                            <th class="min-w-[120px] px-4 py-4 font-medium text-black dark:text-white">Created At</th>
                            <th class="px-4 py-4 font-medium text-black dark:text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="page in pages" :key="page.id" class="border-b border-stroke dark:border-strokedark">
                            <td class="border-b border-[#eee] px-4 py-5 pl-9 xl:pl-11 dark:border-strokedark">
                                <h5 class="font-medium text-black dark:text-white">
                                    {{ page.title }}
                                </h5>
                            </td>
                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                <p class="font-mono text-sm text-black dark:text-white">
                                    {{ page.slug }}
                                </p>
                            </td>
                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                <p class="text-black dark:text-white">
                                    {{ formatDate(page.created_at) }}
                                </p>
                            </td>
                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                <div class="flex items-center space-x-3.5">
                                    <Link :href="`/admin/pages/${page.id}/edit`" class="hover:text-primary" title="Edit Page">
                                        <svg
                                            class="fill-current"
                                            width="18"
                                            height="18"
                                            viewBox="0 0 18 18"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M16.1 2.9L15.1 1.9C14.6 1.4 13.8 1.4 13.3 1.9L12.2 3L15 5.8L16.1 4.7C16.6 4.2 16.6 3.4 16.1 2.9Z"
                                                fill=""
                                            />
                                            <path d="M11.2 4L14 6.8L5.8 15H3V12.2L11.2 4Z" fill="" />
                                        </svg>
                                    </Link>
                                    <button @click="deletePage(page)" class="hover:text-danger" title="Delete Page">
                                        <svg
                                            class="fill-current"
                                            width="18"
                                            height="18"
                                            viewBox="0 0 18 18"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M13.7535 2.47502H11.5879V1.9969C11.5879 1.15315 10.9129 0.478149 10.0691 0.478149H7.90352C7.05977 0.478149 6.38477 1.15315 6.38477 1.9969V2.47502H4.21914C3.40352 2.47502 2.72852 3.15002 2.72852 3.96565V4.8094C2.72852 5.42815 3.09414 5.9344 3.62852 6.1594L4.07852 15.4688C4.13477 16.6219 5.09102 17.5219 6.24414 17.5219H11.7004C12.8535 17.5219 13.8098 16.6219 13.866 15.4688L14.3441 6.13127C14.8785 5.90627 15.2441 5.3719 15.2441 4.78127V3.93752C15.2441 3.15002 14.5691 2.47502 13.7535 2.47502ZM7.67852 1.9969C7.67852 1.85627 7.79102 1.74377 7.93164 1.74377H10.0973C10.2379 1.74377 10.3504 1.85627 10.3504 1.9969V2.47502H7.70664V1.9969H7.67852ZM4.02227 3.96565C4.02227 3.85315 4.10664 3.74065 4.24727 3.74065H13.7535C13.866 3.74065 13.9785 3.82502 13.9785 3.96565V4.8094C13.9785 4.9219 13.8941 5.0344 13.7535 5.0344H4.24727C4.13477 5.0344 4.02227 4.95002 4.02227 4.8094V3.96565ZM11.7285 16.2563H6.27227C5.79414 16.2563 5.40039 15.8906 5.37227 15.3844L4.95039 6.2969H13.0785L12.6566 15.3844C12.6004 15.8625 12.2066 16.2563 11.7285 16.2563Z"
                                                fill=""
                                            />
                                            <path
                                                d="M9.00039 9.11255C8.66289 9.11255 8.35352 9.3938 8.35352 9.7313V13.7751C8.35352 14.1126 8.63477 14.3938 9.00039 14.3938C9.33789 14.3938 9.64727 14.1126 9.64727 13.7751V9.7313C9.64727 9.3938 9.33789 9.11255 9.00039 9.11255Z"
                                                fill=""
                                            />
                                            <path
                                                d="M11.2502 9.67504C10.8846 9.64692 10.6033 9.90004 10.5752 10.2657L10.4064 12.7407C10.3783 13.0782 10.6314 13.3875 10.9971 13.4157C11.0252 13.4157 11.0252 13.4157 11.0533 13.4157C11.3908 13.4157 11.6721 13.1625 11.6721 12.825L11.8408 10.35C11.8408 9.98442 11.5877 9.70317 11.2502 9.67504Z"
                                                fill=""
                                            />
                                            <path
                                                d="M6.72245 9.67504C6.38495 9.70317 6.1037 10.0125 6.13182 10.35L6.3287 12.825C6.35683 13.1625 6.63808 13.4157 6.94745 13.4157C6.97558 13.4157 6.97558 13.4157 7.0037 13.4157C7.34120 13.3875 7.62245 13.0782 7.59433 12.7407L7.39745 10.2657C7.39745 9.90004 7.08808 9.64692 6.72245 9.67504Z"
                                                fill=""
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteModal" class="bg-opacity-50 fixed inset-0 z-50 flex items-center justify-center bg-black">
            <div class="mx-4 w-full max-w-md rounded-lg bg-white p-6 dark:bg-boxdark">
                <div class="mb-4">
                    <h3 class="text-xl font-semibold text-black dark:text-white">Confirm Delete</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Are you sure you want to delete "{{ pageToDelete?.title }}"? This action cannot be undone.
                    </p>
                </div>

                <div class="flex justify-end space-x-4">
                    <button
                        @click="showDeleteModal = false"
                        class="hover:shadow-1 rounded border border-stroke px-6 py-2 text-black dark:border-strokedark dark:text-white"
                    >
                        Cancel
                    </button>
                    <button @click="confirmDelete" class="hover:bg-opacity-90 rounded bg-danger px-6 py-2 text-white">Delete</button>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

interface Page {
    id: number;
    title: string;
    slug: string;
    content: string;
    meta_description: string;
    status: 'draft' | 'published' | 'archived';
    created_at: string;
    updated_at: string;
}

// Reactive data
const pages = ref<Page[]>([]);
const showDeleteModal = ref(false);
const pageToDelete = ref<Page | null>(null);

// Methods
const fetchPages = async () => {
    try {
        console.log('Fetching pages from API...');
        const response = await fetch('/api/pages');
        console.log('API Response status:', response.status);

        if (response.ok) {
            const data = await response.json();
            console.log('Fetched pages:', data);
            pages.value = data;
        } else {
            const errorText = await response.text();
            console.error('Failed to fetch pages:', response.status, errorText);
            pages.value = [];
        }
    } catch (error) {
        console.error('Error fetching pages:', error);
        pages.value = [];
    }
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

const deletePage = (page: Page) => {
    pageToDelete.value = page;
    showDeleteModal.value = true;
};

const confirmDelete = async () => {
    if (!pageToDelete.value) return;

    try {
        const response = await fetch(`/api/pages/${pageToDelete.value.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
        });

        if (response.ok) {
            // Remove from local array
            pages.value = pages.value.filter((p) => p.id !== pageToDelete.value!.id);
        } else {
            console.error('Failed to delete page');
        }

        showDeleteModal.value = false;
        pageToDelete.value = null;
    } catch (error) {
        console.error('Error deleting page:', error);
    }
};

// Lifecycle
onMounted(() => {
    fetchPages();
});
</script>
