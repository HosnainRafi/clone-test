<script setup lang="ts">
import DynamicCampusLife from '@/components/user/DynamicCampusLife.vue';
import DynamicFacultiesCarousel from '@/components/user/DynamicFacultiesCarousel.vue';
import DynamicFooter from '@/components/user/DynamicFooter.vue';
import DynamicHeadlineMarquee from '@/components/user/DynamicHeadlineMarquee.vue';
import DynamicHeroCarousel from '@/components/user/DynamicHeroCarousel.vue';
import DynamicMessageForm from '@/components/user/DynamicMessageForm.vue';
import DynamicNavbar from '@/components/user/DynamicNavbar.vue';
import DynamicNewsEventsNoticeBoard from '@/components/user/DynamicNewsEventsNoticeBoard.vue';
import DynamicTopPublication from '@/components/user/DynamicTopPublication.vue';
import DynamicWelcome from '@/components/user/DynamicWelcome.vue';

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

interface NewsItem {
    id: number;
    title: string;
    excerpt: string;
    image: string;
    date: string;
    category: string;
    link: string;
    isActive: boolean;
}

interface NoticeItem {
    id: number;
    title: string;
    content: string;
    date: string;
    category: string;
    priority: 'high' | 'medium' | 'low';
    department: string;
    validUntil: string;
    attachments: string[];
    isActive: boolean;
    displayOrder: number;
    link: string;
}

interface EventItem {
    id: number;
    title: string;
    description: string;
    date: string;
    time: string;
    endDate: string;
    venue: string;
    category: string;
    status: 'upcoming' | 'completed' | 'postponed';
    registration: string;
    fee: string;
    organizer: string;
    participants: string;
    link: string;
    isActive: boolean;
}

interface PublicationProps {
    id: number;
    title: string;
    abstract: string;
    authors: string[];
    correspondingAuthor: string;
    journal: string;
    journalRank: string;
    impactFactor: number;
    publishDate: string;
    volume?: string;
    issue?: string;
    pages?: string;
    doi?: string;
    category: string;
    keywords: string[];
    citations: number;
    downloads: number;
    openAccess: boolean;
    featured: boolean;
    fallbackGradient: string;
    pdfUrl?: string;
    journalUrl?: string;
}
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
        newsItems?: NewsItem[];
        eventItems?: EventItem[];
        noticeItems?: NoticeItem[];
        publicationItems?: PublicationProps[];
        footerData?: FooterData;
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
const newsItems = data.newsItems || [];
const eventItems = data.eventItems || [];
const noticeItems = data.noticeItems || [];
const publicationItems = data.publicationItems || [];
// Ensure footerData is an object or null (component expects object); componentService may return an array or empty
const footerData = typeof data.footerData === 'object' && data.footerData !== null && !Array.isArray(data.footerData) ? data.footerData : null;
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
    <!-- <DynamicNews :news-items="newsItems" />
    <DynamicEvent :event-items="eventItems" /> -->
    <!-- <NewsEventsNoticeBoard /> -->
    <!-- <DynamicNotices :notice-items="noticeItems" /> -->
    <DynamicNewsEventsNoticeBoard :news-items="newsItems" :event-items="eventItems" :notice-items="noticeItems" />
    <!-- <TopPublication /> -->
    <DynamicTopPublication :publication-items="publicationItems" />
    <DynamicFooter :footer-data="footerData" />
    <!-- <Footer /> -->
</template>
