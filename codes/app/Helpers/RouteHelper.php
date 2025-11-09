<?php

namespace App\Helpers;

class RouteHelper
{
    /**
     * Get the admin route prefix based on site type
     *
     * @param string|null $siteType
     * @return string
     */
    public static function getAdminPrefix(?string $siteType = null): string
    {
        if (!$siteType) {
            $siteType = self::getCurrentSiteType();
        }

        return match ($siteType) {
            'department' => 'admin/department',
            'faculty' => 'admin/faculty',
            default => 'admin/university',
        };
    }

    /**
     * Get the current site type from request
     *
     * @return string
     */
    public static function getCurrentSiteType(): string
    {
        $siteData = request()->get('siteData');

        if ($siteData) {
            return $siteData->site_type ?? 'university';
        }

        return 'university';
    }

    /**
     * Generate admin route with proper prefix
     *
     * @param string $path
     * @param string|null $siteType
     * @return string
     */
    public static function admin(string $path, ?string $siteType = null): string
    {
        $prefix = self::getAdminPrefix($siteType);
        $path = ltrim($path, '/');

        return "/{$prefix}/{$path}";
    }

    /**
     * Check if current route matches the given path
     *
     * @param string $path
     * @return bool
     */
    public static function isActive(string $path): bool
    {
        $currentPath = request()->path();
        $path = ltrim($path, '/');

        return str_contains($currentPath, $path);
    }
}
