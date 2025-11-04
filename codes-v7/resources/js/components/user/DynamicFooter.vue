<script setup lang="ts">
import { Facebook, Globe, Linkedin, Mail, MapPin, Phone, Twitter, Youtube } from 'lucide-vue-next';
import { computed } from 'vue';

// --- 1. Define Footer Data Structure ---
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
    universitySlogan?: string; // Optional slogan/short description
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

// --- 2. Define Component Props ---
const props = defineProps<{
    footerData: FooterData | null; // Allow null if data might not be loaded
}>();

// --- 3. Helper to get Social Icon and Colors ---
const getSocialDetails = (platform: string) => {
    switch (platform) {
        case 'facebook':
            return { icon: Facebook, color: 'bg-[#1877F2] hover:bg-[#166fe5]' };
        case 'twitter':
            return { icon: Twitter, color: 'bg-[#1DA1F2] hover:bg-[#1a91da]' };
        case 'linkedin':
            return { icon: Linkedin, color: 'bg-[#0A66C2] hover:bg-[#095ab0]' };
        case 'youtube':
            return { icon: Youtube, color: 'bg-[#FF0000] hover:bg-[#e60000]' };
        case 'website':
            return { icon: Globe, color: 'bg-green-600 hover:bg-green-700' };
        default:
            return { icon: Globe, color: 'bg-gray-600 hover:bg-gray-700' };
    }
};

// Provide default data if prop is null
const defaultFooterData: FooterData = {
    universityName: 'MBSTU',
    universityFullName: 'Mawlana Bhashani Science and Technology University',
    universitySlogan: 'Default Slogan: Striving for Excellence.',
    logoUrl: '/images/university/logo/MBSTU_logo.png',
    address: 'Santosh, Tangail-1902, Bangladesh',
    phone: '+880-XXX-XXXXXX',
    email: 'info@mbstu.ac.bd',
    academicLinksTitle: 'Academics',
    academicLinks: [{ text: 'Academic Programs', href: '#' }],
    usefulLinksTitle: 'Useful Links',
    usefulLinks: [{ text: 'ICT Cell', href: '#' }],
    socialLinks: [{ platform: 'website', href: '#' }],
    copyrightText: `© ${new Date().getFullYear()} MBSTU`,
    liaisonOfficeTitle: 'Dhaka Liaison Office:',
    liaisonOfficeAddress: 'Default Liaison Address',
};

const data = computed(() => props.footerData || defaultFooterData);
</script>

<template>
    <footer id="footer" class="mt-24 border-t border-gray-200 bg-gradient-to-b from-gray-50 to-gray-100">
        <div class="container py-16">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                <div class="lg:col-span-2">
                    <div class="mb-6 flex items-center gap-2">
                        <img :src="data.logoUrl" alt="University Logo" class="h-10 w-10 object-contain" />
                        <div>
                            <h3 class="text-xl font-bold text-[hsl(var(--secondary))]">{{ data.universityName }}</h3>
                            <p class="text-sm text-gray-600">{{ data.universityFullName }}</p>
                        </div>
                    </div>

                    <p v-if="data.universitySlogan" class="mb-6 leading-relaxed text-gray-600">
                        {{ data.universitySlogan }}
                    </p>

                    <div class="space-y-3">
                        <div class="flex items-center text-gray-600">
                            <MapPin class="mr-3 h-4 w-4 flex-shrink-0 text-[hsl(var(--secondary))]" />
                            <span class="text-sm">{{ data.address }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <Phone class="mr-3 h-4 w-4 flex-shrink-0 text-[hsl(var(--secondary))]" />
                            <span class="text-sm">{{ data.phone }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <Mail class="mr-3 h-4 w-4 flex-shrink-0 text-[hsl(var(--secondary))]" />
                            <span class="text-sm">{{ data.email }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="mb-4 text-lg font-bold text-[hsl(var(--secondary))]">{{ data.academicLinksTitle }}</h4>
                    <div class="space-y-2">
                        <a
                            v-for="link in data.academicLinks"
                            :key="link.href"
                            :href="link.href"
                            class="block text-sm text-gray-600 transition-colors duration-200 hover:text-[hsl(var(--secondary))]"
                        >
                            {{ link.text }}
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="mb-4 text-lg font-bold text-[hsl(var(--secondary))]">{{ data.usefulLinksTitle }}</h4>
                    <div class="space-y-2">
                        <a
                            v-for="link in data.usefulLinks"
                            :key="link.href"
                            :href="link.href"
                            class="block text-sm text-gray-600 transition-colors duration-200 hover:text-[hsl(var(--secondary))]"
                        >
                            {{ link.text }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-12 border-t border-gray-300 pt-8">
                <div class="flex flex-col items-center justify-between md:flex-row">
                    <div class="mb-6 flex items-center space-x-4 md:mb-0">
                        <span class="text-sm font-medium text-gray-600">Follow Us:</span>
                        <div class="flex space-x-3">
                            <a
                                v-for="social in data.socialLinks"
                                :key="social.platform"
                                :href="social.href"
                                target="_blank"
                                :class="[getSocialDetails(social.platform).color]"
                                class="flex h-10 w-10 items-center justify-center rounded-lg transition-colors duration-200"
                                :aria-label="`Visit our ${social.platform} page`"
                            >
                                <component :is="getSocialDetails(social.platform).icon" class="h-5 w-5 text-white" />
                            </a>
                        </div>
                    </div>

                    <div class="text-center md:text-right">
                        <p class="mb-2 text-sm text-gray-600">
                            {{ data.copyrightText }}
                        </p>
                        <div v-if="data.liaisonOfficeAddress" class="space-y-1 text-xs text-gray-500">
                            <div v-if="data.liaisonOfficeTitle" class="flex items-center justify-center space-x-4 md:justify-end">
                                <span class="text-sm">{{ data.liaisonOfficeTitle }}</span>
                            </div>
                            <div>
                                <span>{{ data.liaisonOfficeAddress }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>
