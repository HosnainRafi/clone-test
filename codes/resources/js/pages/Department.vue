<script setup lang="ts">
import Navbar from "@/components/user/Navbar.vue";
import DynamicHeroCarousel from '@/components/user/DynamicHeroCarousel.vue';
import DynamicHeadlineMarquee from '@/components/user/DynamicHeadlineMarquee.vue';
import DynamicNewsEventsNoticeBoard from '@/components/user/DynamicNewsEventsNoticeBoard.vue';
import DynamicMessageForm from '@/components/user/DynamicMessageForm.vue';
import AtAGlance from "@/components/user/AtAGlance.vue";
import LatestProjects from "@/components/user/LatestProjects.vue";
import TechNews from "@/components/user/TechNews.vue";
import DynamicTopPublication from '@/components/user/DynamicTopPublication.vue';
import Footer from "@/components/user/Footer.vue";

const props = defineProps<{
  message: string;
  data: any;
}>();

const menuItems = props.data.menuItems || [];
const address = props.data.address || '';
const contactEmail = props.data.contactEmail || '';
const siteTitle = props.data.siteTitle || '';
const heroSlides = props.data.heroSlides || [];
const headlines = props.data.headlines || [];
const messageFromItems = props.data.messageFromItems || [];
const newsItems = props.data.newsItems || [];
const eventItems = props.data.eventItems || [];
const noticeItems = props.data.noticeItems || [];
const publicationItems = props.data.publicationItems || [];
const footerData = props.data.footerData;
const atAGlanceData = props.data.atAGlanceData;
const latestProjectsData = props.data.latestProjects;
const techNewsData = props.data.techNews;

</script>

<template>
  <Navbar :message="message" :menuItems="menuItems" :address="address" :contactEmail="contactEmail" :siteTitle="siteTitle" />
  <DynamicHeroCarousel :slides="heroSlides" />
  <DynamicHeadlineMarquee :headlines="headlines" />
  <DynamicMessageForm :messageFromItems="messageFromItems" />
  <DynamicNewsEventsNoticeBoard :news-items="newsItems" :event-items="eventItems" :notice-items="noticeItems" />
  <AtAGlance :atAGlanceData="atAGlanceData" />
  <LatestProjects :latestProjectsData="latestProjectsData" />
  <TechNews :techNewsData="techNewsData" />
  <DynamicTopPublication :publication-items="publicationItems" />
  <Footer :footerData="footerData" />
</template>
