<script setup lang="ts">
import DynamicNavbar from '@/components/user/DynamicNavbar.vue';
import Footer from '@/components/user/Footer2.vue';
import { Link } from '@inertiajs/vue3';
import { Download } from 'lucide-vue-next';

// --- INTERFACES & PROPS ---
interface TenderItem {
    id: number;
    title: string;
    tender_number: string;
    published_at: string;
    closing_at: string;
    attachments: string[]; // Expect an array of URLs for PDFs
    link: string; // Link to the tender's detail page
}

interface PaginatedTenders {
    data: TenderItem[];
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
}

interface PageProps {
    tenders: PaginatedTenders;
    menuItems: any[];
}

const props = defineProps<PageProps>();

const formattedDate = (dateString: string) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};

// Helper to get just the first PDF from the attachments array
const getFirstPdf = (attachments: string[]): string | null => {
    if (!attachments || attachments.length === 0) {
        return null;
    }
    // Find the first attachment that is a PDF
    return attachments.find((att) => att.toLowerCase().endsWith('.pdf')) || null;
};

console.log(props.tenders.data);
</script>

<template>
    <div>
        <DynamicNavbar :menuItems="props.menuItems" />
        <main class="bg-gray-50">
            <div class="container mx-auto px-4 py-12 sm:px-6 lg:px-8">
                <header class="mb-12 text-center">
                    <h1 class="mb-4 text-3xl font-bold text-[hsl(var(--secondary))] md:text-4xl">Tenders & Quotations</h1>
                    <p class="mx-auto max-w-3xl text-lg text-gray-600">Official invitations for bids and proposals from the university.</p>
                    <p class="mt-2 text-lg text-gray-600">
                        <Link href="/" class="hover:text-[hsl(var(--primary))] hover:underline">Home</Link>
                        <span class="mx-2">></span>
                        <span class="font-semibold text-[hsl(var(--secondary))]">Tenders</span>
                    </p>
                </header>

                <div v-if="props.tenders.data.length > 0" class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-md">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold tracking-wider text-gray-600 uppercase">Sl. No.</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold tracking-wider text-gray-600 uppercase">
                                        Tender Title
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold tracking-wider text-gray-600 uppercase">
                                        Published Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold tracking-wider text-gray-600 uppercase">Download</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold tracking-wider text-gray-600 uppercase">Details</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="(tender, index) in props.tenders.data" :key="tender.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap text-gray-900">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold whitespace-nowrap text-gray-800">
                                        {{ tender.title }}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-500">
                                        {{ formattedDate(tender.published_at) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-nowrap">
                                        <a
                                            v-if="getFirstPdf(tender.attachments)"
                                            :href="getFirstPdf(tender.attachments)!"
                                            target="_blank"
                                            class="inline-flex items-center gap-2 rounded-md bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700 transition-colors hover:bg-red-200"
                                        >
                                            <Download class="h-4 w-4" />
                                            <span>PDF</span>
                                        </a>
                                        <span v-else class="text-xs text-gray-400">N/A</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-nowrap">
                                        <Link
                                            :href="tender.link"
                                            class="inline-flex items-center gap-2 rounded-md bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700 transition-colors hover:bg-blue-200"
                                        >
                                            View Details
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination logic (remains the same) -->
                <nav v-if="props.tenders.links.length > 3" class="mt-8 flex items-center justify-between">
                    <!-- Previous, Next, and Page number links -->
                </nav>

                <div v-else-if="props.tenders.data.length === 0" class="py-16 text-center">
                    <h2 class="text-2xl font-semibold text-gray-700">No Active Tenders</h2>
                    <p class="mt-2 text-gray-500">There are no open tenders or quotations at this time. Please check back later.</p>
                </div>
            </div>
        </main>
        <Footer />
    </div>
</template>

<style scoped>
/* Optional: Add smooth transitions for links */
a,
.btn {
    transition: all 0.2s ease-in-out;
}
</style>
