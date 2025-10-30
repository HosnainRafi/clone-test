<script setup lang="ts">
import DynamicNavbar from '@/components/user/DynamicNavbar.vue'
import Footer from '@/components/user/Footer.vue'

// Props for menu items and other site data
interface MenuItemProps {
  title: string;
  col: number;
  subItems: {
    title: string;
    description: string;
    href: string;
  }[];
}

interface Props {
  menuItems?: MenuItemProps[]
  siteData?: any
}

const props = withDefaults(defineProps<Props>(), {
  menuItems: () => [],
  siteData: () => ({})
})
</script>

<template>
  <!-- ===== Public Site Wrapper Start ===== -->
  <div class="min-h-screen bg-white">
    <!-- ===== Dynamic Navbar Start ===== -->
    <DynamicNavbar :menuItems="menuItems" />
    <!-- ===== Dynamic Navbar End ===== -->

    <!-- ===== Main Content Start ===== -->
    <main>
      <slot></slot>
    </main>
    <!-- ===== Main Content End ===== -->

    <!-- ===== Footer Start ===== -->
    <Footer />
    <!-- ===== Footer End ===== -->
  </div>
  <!-- ===== Public Site Wrapper End ===== -->
</template>

<style scoped>
/* Ensure the layout takes full height */
.min-h-screen {
  min-height: 100vh;
}
</style>
