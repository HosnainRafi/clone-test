<script setup lang="ts">
import DynamicCampusLife from '@/components/user/DynamicCampusLife.vue';
import DynamicFacultiesCarousel from '@/components/user/DynamicFacultiesCarousel.vue';
import DynamicHeadlineMarquee from '@/components/user/DynamicHeadlineMarquee.vue';
import DynamicHeroCarousel from '@/components/user/DynamicHeroCarousel.vue';
import DynamicMessageForm from '@/components/user/DynamicMessageForm.vue';
import DynamicNavbar from '@/components/user/DynamicNavbar.vue';
import DynamicWelcome from '@/components/user/DynamicWelcome.vue';
import Footer from '@/components/user/Footer2.vue';
import NewsEventsNoticeBoard from '@/components/user/NewsEventsNoticeBoard.vue';
import TopPublication from '@/components/user/TopPublication.vue';

// Define the props interface to match the data structure from Laravel
interface MenuItemProps {
    title: string;
    col: number;
    subItems: {
        title: string;
        description: string;
        href: string;
    }[];
}
// Define the hero slide interface
interface HeroSlide {
    id?: number;
    title: string;
    subtitle: string;
    description: string;
    image: string;
    fallbackGradient: string;
    ctaText: string;
    ctaLink: string;
    secondaryCta?: {
        text: string;
        link: string;
    } | null;
}

interface HeadlineItem {
    id?: number;
    type: 'announcement' | 'news' | 'event' | 'research' | 'achievement' | 'notice';
    text: string;
    link: string;
    priority: 'high' | 'medium' | 'low';
    isActive: boolean;
    icon?: string;
}

interface MessageFromItem {
    id?: number;
    name: string;
    title: string;
    message: string;
    image: string;
    fallbackGradient: string;
    designation?: string;
    department?: string;
    email?: string;
    phone?: string;
    fax?: string;
    office?: string;
    address?: string;
    officeTime?: string;
    experience?: string;
    qualifications?: string[];
    specializations?: string[];
    achievements?: string[];
    isActive: boolean;
    displayOrder: number;
    type: 'vice_chancellor' | 'pro_vice_chancellor' | 'dean' | 'director' | 'chairman' | 'other';
}

interface FacultyItem {
    id?: number;
    name: string;
    shortName: string;
    description: string;
    image: string;
    fallbackGradient: string;
    link: string;
    departments: string[];
    stats: {
        departments: number;
        students: string;
        faculty: string;
        programs: number;
    };
    highlights: string[];
    featured?: boolean;
    established?: string;
    ranking?: string;
    dean?: {
        name: string;
        title: string;
        email?: string;
    };
    researchAreas: string[];
    achievements: string[];
    iconName: string;
    isActive: boolean;
    displayOrder: number;
}

interface WelcomeItem {
    id?: number;
    title: string;
    subtitle?: string;
    description?: string;
    backgroundImage: string;
    fallbackGradient: string;
    videoId?: string;
    videoTitle?: string;
    buttonText: string;
    buttonLink?: string;
    secondaryButtonText?: string;
    secondaryButtonLink?: string;
    showPlayButton: boolean;
    overlayOpacity: number;
    textColor: string;
    buttonStyle: 'default' | 'outline' | 'ghost';
    animationEnabled: boolean;
    minHeight: string;
    titleSize: 'small' | 'medium' | 'large' | 'xl';
    isActive: boolean;
    displayOrder: number;
}

interface CampusLifeItem {
    id: number;
    title: string;
    category: string;
    description: string;
    image: string;
    fallbackGradient: string;
    iconName: string;
    features: string[];
    stats?: { label: string; value: string }[];
    link: string;
    featured?: boolean;
    isActive: boolean;
    displayOrder: number;
}

interface GlanceItem {
    id: number;
    label: string;
    value: string;
    iconName: string;
    iconColor: string;
    isActive: boolean;
    displayOrder: number;
}

interface PageProps {
    message: string;
    data: {
        siteData: any;
        theme: any;
        components: any;
        viewType: any;
        fullDomain: string;
        page: any;
        menuItems: MenuItemProps[];
        heroSlides?: HeroSlide[];
        headlines?: HeadlineItem[];
        messageFromItems?: MessageFromItem[];
        facultyItems?: FacultyItem[];
        welcomeItems?: WelcomeItem[];
        campusLifeItems?: CampusLifeItem[];
        glanceItems?: GlanceItem[];
    };
}

// Receive props from Inertia
const props = defineProps<PageProps>();

// Extract the data for easier access
const { message, data } = props;
// console.log('=== University.vue Debug ===');
// console.log('Full props from Laravel:', props);
// console.log('data object:', data);
// console.log('data.headlines:', data.headlines);
// console.log('data.headlines exists:', !!data.headlines);
// console.log('data.headlines length:', data.headlines?.length || 0);
// Log the received data for debugging
console.log('Received data from Laravel:', { message, data });

// Use menu items from Laravel data, with fallback to empty array
const menuItems = data.menuItems || [];
const heroSlides = data.heroSlides || [];
const headlines = data.headlines || [];
const messageFromItems = data.messageFromItems || [];
const facultyItems = data.facultyItems || [];
const welcomeItems = data.welcomeItems || [];
const campusLifeItems = data.campusLifeItems || [];
const glanceItems = data.glanceItems || [];
</script>

<template>
    <!-- Debug info (remove in production) -->
    <!-- <div v-if="true" class="p-4 bg-gray-100 text-sm border-b-2 border-blue-500">
    <p><strong>Message:</strong> {{ message }}</p>
    <p><strong>Full Domain:</strong> {{ data.fullDomain }}</p>
    <p><strong>View Type:</strong> {{ data.viewType }}</p>
    <p><strong>Site Data:</strong> {{ JSON.stringify(data.siteData) }}</p>
    <p><strong>Menu Items Count:</strong> {{ menuItems.length }}</p>
    <p><strong>Menu Items:</strong> {{ JSON.stringify(menuItems, null, 2) }}</p>
  </div> -->

    <DynamicNavbar :menuItems="menuItems" />
    <DynamicHeroCarousel :slides="heroSlides" />
    <DynamicHeadlineMarquee :headlines="headlines" />
    <DynamicMessageForm :messageFromItems="messageFromItems" />
    <DynamicFacultiesCarousel :facultyItems="facultyItems" />
    <DynamicWelcome :welcomeItems="welcomeItems" />
    <DynamicCampusLife :campus-life-items="campusLifeItems" :glance-items="glanceItems" />
    <!-- <HeroCarousel /> -->
    <!-- <HeadlineMarquee /> -->
    <!-- <MessageFrom /> -->
    <!-- <FacultiesCarousel /> -->
    <!-- <Welcome /> -->
    <!-- <CampusLife /> -->
    <NewsEventsNoticeBoard />
    <TopPublication />
    <Footer />
</template>
