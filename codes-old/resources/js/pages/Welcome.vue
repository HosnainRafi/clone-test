<script setup lang="ts">
import Navbar from "@/components/user/Navbar.vue";
import HeroCarousel from "@/components/user/HeroCarousel.vue";
import HeadlineMarquee from "@/components/user/HeadlineMarquee.vue";
import NewsEventsNoticeBoard from "@/components/user/NewsEventsNoticeBoard.vue";
import MessageFrom from "@/components/user/MessageFrom.vue";
import AtAGlance from "@/components/user/AtAGlance.vue";
import LatestProjects from "@/components/user/LatestProjects.vue";
import TechNews from "@/components/user/TechNews.vue";
import TopPublication from "@/components/user/TopPublication.vue";
import Team from "@/components/user/Team.vue";
import FAQ from "@/components/user/FAQ.vue";
import Footer from "@/components/user/Footer.vue";

defineProps<{
  message: string;
  data: any;
}>();
</script>

<template>
<!--  <h1>{{ message }}</h1> -->
<pre class="bg-gray-100 p-4 rounded">
  <code>{{ JSON.stringify(data, null, 2) }}</code>
</pre>
  <Navbar :message="message" />
  <HeroCarousel />
  <HeadlineMarquee />
  <MessageFrom />
  <NewsEventsNoticeBoard />
  <AtAGlance />
  <LatestProjects />
  <TechNews />
  <TopPublication />
  <!-- <Team />
  <FAQ /> -->
  <Footer />
</template>
