<script setup lang="ts">
import Navbar from "@/components/user/Navbar2.vue";
import Welcome from "@/components/user/Welcome.vue";
import HeroCarousel from "@/components/user/HeroCarousel2.vue";
import HeadlineMarquee from "@/components/user/HeadlineMarquee.vue";
import NewsEventsNoticeBoard from "@/components/user/NewsEventsNoticeBoard.vue";
import MessageFrom from "@/components/user/MessageFrom2.vue";
import FacultiesCarousel from "@/components/user/FacultiesCarousel.vue";
import CampusLife from "@/components/user/CampusLife.vue";
import TopPublication from "@/components/user/TopPublication.vue";
import Footer from "@/components/user/Footer2.vue";
import DynamicNavbar from "@/components/user/DynamicNavbar.vue";

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
  };
}

// Receive props from Inertia
const props = defineProps<PageProps>();

// Extract the data for easier access
const { message, data } = props;

// Log the received data for debugging
console.log('Received data from Laravel:', { message, data });

// Use menu items from Laravel data, with fallback to empty array
const menuItems = data.menuItems || [];
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
  <HeroCarousel />
  <HeadlineMarquee />
  <MessageFrom />
  <FacultiesCarousel />
  <Welcome />
  <CampusLife />
  <NewsEventsNoticeBoard />
  <TopPublication />
  <Footer />
</template>
