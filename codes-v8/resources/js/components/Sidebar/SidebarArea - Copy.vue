<script setup lang="ts">
import { useSidebarStore } from '@/stores/sidebar';
import { Link } from '@inertiajs/vue3';
import { onClickOutside } from '@vueuse/core';
import { ref } from 'vue';
import SidebarItem from './SidebarItem.vue';

const target = ref(null);

const sidebarStore = useSidebarStore();

onClickOutside(target, () => {
    sidebarStore.isSidebarOpen = false;
});

// Helper: return small inline SVG strings mapped to routes or labels
const iconFor = (key: string) => {
    const k = (key || '').toLowerCase();
    if (k.includes('dashboard')) {
        return `<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zM13 21h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>`;
    }
    if (k.includes('menu')) {
        return `<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>`;
    }
    if (k.includes('hero') || k.includes('carousel') || k.includes('slides')) {
        return `<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"><path d="M21 15V5a2 2 0 0 0-2-2H5C3.9 3 3 3.9 3 5v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2zM7 21h10v-4H7v4z"/></svg>`;
    }
    if (k.includes('headline') || k.includes('marquee')) {
        return `<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"><path d="M3 11h18M3 6h18M3 16h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>`;
    }
    if (k.includes('message')) {
        return `<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10z"/></svg>`;
    }
    if (k.includes('welcome') || k.includes('video')) {
        return `<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"><path d="M10 8l6 4-6 4V8zM21 19V5a2 2 0 0 0-2-2H5c-1.1 0-2 .9-2 2v14l4-4h12a2 2 0 0 0 2-2z"/></svg>`;
    }
    if (k.includes('campus') || k.includes('life')) {
        return `<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"><path d="M12 2l7 4v7c0 5-4 9-7 9s-7-4-7-9V6l7-4z"/></svg>`;
    }
    if (k.includes('glance')) {
        return `<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"><path d="M12 2l3 7h7l-6 4 3 7-7-5-7 5 3-7-6-4h7z"/></svg>`;
    }
    if (k.includes('news')) {
        return `<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"><path d="M3 5h18v14H3zM7 9h10M7 13h6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>`;
    }
    if (k.includes('event')) {
        return `<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"><path d="M7 10h10M7 14h4M17 3v4M7 3v4M3 8h18v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8z"/></svg>`;
    }
    if (k.includes('notice')) {
        return `<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 0-7 7v5l-2 2h18l-2-2V9a7 7 0 0 0-7-7zM12 22a2 2 0 0 0 2-2H10a2 2 0 0 0 2 2z"/></svg>`;
    }
    if (k.includes('publications') || k.includes('publication')) {
        return `<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"><path d="M6 2h9a2 2 0 0 1 2 2v16l-7-3-7 3V4a2 2 0 0 1 2-2z"/></svg>`;
    }
    if (k.includes('footer')) {
        return `<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"><path d="M2 3h20v4H2V3zm0 7h14v4H2v-4zm0 7h8v4H2v-4z"/></svg>`;
    }
    if (k.includes('pages') || k.includes('page')) {
        return `<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"><path d="M3 3h7v7H3V3zm0 11h7v7H3v-7zM14 3h7v7h-7V3zm0 11h7v7h-7v-7z"/></svg>`;
    }

    // Default icon (list)
    return `<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>`;
};

const menuGroups = ref([
    {
        name: 'MENU',
        menuItems: [
            { label: 'Dashboard', route: '/admin/dashboard', icon: iconFor('dashboard') },
            { label: 'Menu', route: '/admin/menu', icon: iconFor('menu') },
            { label: 'Hero Slides', route: '/admin/hero-carousel', icon: iconFor('hero-carousel') },
            { label: 'Headline Marquee', route: '/admin/headline-marquee', icon: iconFor('headline-marquee') },
            { label: 'Message From', route: '/admin/message-from', icon: iconFor('message-from') },
            { label: 'Welcome video', route: '/admin/welcome-section', icon: iconFor('welcome-section') },
            { label: 'Campus Life', route: '/admin/campus-life-section', icon: iconFor('campus-life-section') },
            { label: 'Campus Glance', route: '/admin/campus-glance', icon: iconFor('campus-glance') },
            { label: 'News Section', route: '/admin/news-section', icon: iconFor('news-section') },
            { label: 'Events Section', route: '/admin/events-section', icon: iconFor('events-section') },
            { label: 'Notices Section', route: '/admin/notices-section', icon: iconFor('notices-section') },
            { label: 'Publications Section', route: '/admin/publications-section', icon: iconFor('publications-section') },
            { label: 'Footer Section', route: '/admin/footer-section', icon: iconFor('footer-section') },
            { label: 'Pages', route: '/admin/pages', icon: iconFor('pages') },
        ],
    },
]);
</script>

<template>
    <aside
        class="absolute top-0 left-0 z-9999 flex h-screen w-72.5 flex-col overflow-y-hidden bg-black duration-300 ease-linear lg:static lg:translate-x-0 dark:bg-boxdark"
        :class="{
            'translate-x-0': sidebarStore.isSidebarOpen,
            '-translate-x-full': !sidebarStore.isSidebarOpen,
        }"
        ref="target"
    >
        <!-- SIDEBAR HEADER -->
        <div class="flex items-center justify-between gap-2 px-6 py-5.5 lg:py-6.5">
            <Link to="/">
                <img src="@/assets/images/logo/logo.svg" alt="Logo" />
            </Link>

            <button class="block lg:hidden" @click="sidebarStore.isSidebarOpen = false">
                <svg class="fill-current" width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M19 8.175H2.98748L9.36248 1.6875C9.69998 1.35 9.69998 0.825 9.36248 0.4875C9.02498 0.15 8.49998 0.15 8.16248 0.4875L0.399976 8.3625C0.0624756 8.7 0.0624756 9.225 0.399976 9.5625L8.16248 17.4375C8.31248 17.5875 8.53748 17.7 8.76248 17.7C8.98748 17.7 9.17498 17.625 9.36248 17.475C9.69998 17.1375 9.69998 16.6125 9.36248 16.275L3.02498 9.8625H19C19.45 9.8625 19.825 9.4875 19.825 9.0375C19.825 8.55 19.45 8.175 19 8.175Z"
                        fill=""
                    />
                </svg>
            </button>
        </div>
        <!-- SIDEBAR HEADER -->

        <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
            <!-- Sidebar Menu -->
            <nav class="mt-5 px-4 py-4 lg:mt-9 lg:px-6">
                <template v-for="menuGroup in menuGroups" :key="menuGroup.name">
                    <div>
                        <h3 class="mb-4 ml-4 text-sm font-medium text-bodydark2">{{ menuGroup.name }}</h3>

                        <ul class="mb-6 flex flex-col gap-1.5">
                            <SidebarItem v-for="(menuItem, index) in menuGroup.menuItems" :item="menuItem" :key="index" :index="index" />
                        </ul>
                    </div>
                </template>
            </nav>
            <!-- Sidebar Menu -->

            <!-- Promo Box -->
            <!-- <div
        class="mx-auto mb-10 w-full max-w-60 rounded-sm border border-strokedark bg-boxdark py-6 px-4 text-center shadow-default"
      >
        <h3 class="mb-1 font-semibold text-white">TailAdmin Pro</h3>
        <p class="mb-4 text-xs">Get All Dashboards and 300+ UI Elements</p>
        <a
          href="https://tailadmin.com/pricing"
          target="_blank"
          rel="nofollow"
          class="flex items-center justify-center rounded-md bg-primary p-2 font-medium text-white hover:bg-opacity-80"
        >
          Purchase Now
        </a>
      </div> -->
            <!-- Promo Box -->
        </div>
    </aside>
</template>
