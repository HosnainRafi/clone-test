<script setup lang="ts">
import { BookOpen, Building, Calendar, Heart, Home, Shield, Trophy, Users } from 'lucide-vue-next';
import { computed } from 'vue';

// Interface for a single glance item
interface GlanceItem {
    id: number;
    label: string;
    value: string;
    iconName: string;
    iconColor: string;
    isActive: boolean;
    displayOrder: number;
}

// Props from the parent component
const props = defineProps<{
    glanceItems?: GlanceItem[];
}>();

// Icon mapping
const iconMap = {
    Users,
    Trophy,
    Home,
    Calendar,
    BookOpen,
    Heart,
    Building,
    Shield,
} as any;

// Use provided items or an empty array
const activeItems = computed(() => {
    if (props.glanceItems && props.glanceItems.length > 0) {
        return props.glanceItems
            .filter((item) => item.isActive)
            .sort((a, b) => a.displayOrder - b.displayOrder)
            .slice(0, 4); // Ensure only a maximum of 4 are shown
    }
    return [];
});

const getIconComponent = (iconName: string) => {
    return iconMap[iconName] || Users;
};
</script>

<template>
    <div v-if="activeItems.length > 0" class="mt-12 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 p-8">
        <div class="mb-6 text-center">
            <h3 class="mb-2 text-2xl font-bold text-[hsl(var(--secondary))]">Campus Life at a Glance</h3>
            <p class="text-muted-foreground">Experience the vibrant MBSTU community</p>
        </div>

        <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
            <div v-for="item in activeItems" :key="item.id" class="text-center">
                <div
                    class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-full text-white"
                    :style="{ backgroundColor: item.iconColor }"
                >
                    <component :is="getIconComponent(item.iconName)" class="h-6 w-6" />
                </div>
                <div class="text-2xl font-bold text-[hsl(var(--secondary))]">{{ item.value }}</div>
                <div class="text-sm text-muted-foreground">{{ item.label }}</div>
            </div>
        </div>
    </div>
</template>
