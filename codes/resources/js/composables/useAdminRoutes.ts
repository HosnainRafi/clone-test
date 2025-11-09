import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export function useAdminRoutes() {
    const page = usePage();

    const siteData = computed(() => page.props.siteData || null);
    const siteType = computed(() => {
        if (siteData.value) {
            return (siteData.value as any).site_type || 'university';
        }
        return 'university';
    });

    const adminPrefix = computed(() => {
        switch (siteType.value) {
            case 'department':
                return '/admin/department';
            case 'faculty':
                return '/admin/faculty';
            default:
                return '/admin/university';
        }
    });

    /**
     * Generate admin route with proper prefix
     */
    const adminRoute = (path: string): string => {
        const cleanPath = path.replace(/^\/+/, ''); // Remove leading slashes
        return `${adminPrefix.value}/${cleanPath}`;
    };

    /**
     * Check if current route is active
     */
    const isActiveRoute = (path: string): boolean => {
        const currentPath = page.url;
        const cleanPath = path.replace(/^\/+/, '');
        return currentPath.includes(cleanPath);
    };

    return {
        siteType,
        adminPrefix,
        adminRoute,
        isActiveRoute,
    };
}
