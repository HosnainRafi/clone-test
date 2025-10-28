<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class SiteContentService
{
    /**
     * Get site settings for a specific key or all settings
     */
    public function getSiteSettings($siteId, $key = null)
    {
        try {
            $site = DB::table('sites')->where('id', $siteId)->first();

            if (!$site) {
                throw new Exception("Site not found with ID: {$siteId}");
            }

            $settings = $site->settings ? json_decode($site->settings, true) : [];

            return $key ? ($settings[$key] ?? []) : $settings;

        } catch (Exception $e) {
            Log::error("Error getting site settings: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Save site settings for a specific key
     */
    public function saveSiteSettings($siteId, $key, $data)
    {
        try {
            // Check if site exists
            $site = DB::table('sites')->where('id', $siteId)->first();

            if (!$site) {
                throw new Exception("Site not found with ID: {$siteId}");
            }

            // Prepare the settings JSON
            $currentSettings = $site->settings ? json_decode($site->settings, true) : [];
            $currentSettings[$key] = $data;
            $newSettingsJson = json_encode($currentSettings);

            // Update the database
            $updated = DB::table('sites')
                ->where('id', $siteId)
                ->update([
                    'settings' => $newSettingsJson,
                    'updated_at' => now()
                ]);

            if (!$updated) {
                throw new Exception("Failed to update database");
            }

            Log::info("Site settings updated successfully", [
                'siteId' => $siteId,
                'key' => $key,
                'dataCount' => is_array($data) ? count($data) : 1
            ]);

            return true;

        } catch (Exception $e) {
            Log::error("Error saving site settings: " . $e->getMessage(), [
                'siteId' => $siteId,
                'key' => $key
            ]);
            throw $e;
        }
    }

    /**
     * Get site ID from request data
     */
    public function resolveSiteId($request)
    {
        $siteData = $request->get('siteData');
        $requestSiteId = $request->input('siteId');

        if ($siteData && isset($siteData['id'])) {
            return $siteData['id'];
        } elseif ($requestSiteId) {
            return $requestSiteId;
        }

        throw new Exception("No site ID found");
    }

    /**
     * Validate JSON structure
     */
    public function validateJson($data)
    {
        json_encode($data);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON format: " . json_last_error_msg());
        }

        return true;
    }
}
