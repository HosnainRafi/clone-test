<?php

namespace App\Services;

use App\Models\Component;
use App\Models\Site;
use Illuminate\Support\Facades\Log;
use Exception;

class ComponentService
{
    /**
     * Save homepage component data to the components table
     */
    public function saveHomepageComponent($siteId, $componentName, $data)
    {
        try {
            $type = Component::nameToType($componentName);

            // Check if component already exists for this site (homepage components have page_id = null)
            $component = Component::forSite($siteId)
                ->whereNull('page_id')
                ->ofType($type)
                ->homepage()
                ->first();

            if ($component) {
                // Update existing component
                $component->update([
                    'content' => $data,
                    'updated_by' => auth()->id(),
                ]);
            } else {
                // Create new component
                $component = Component::create([
                    'name' => $componentName,
                    'type' => $type,
                    'content' => $data,
                    'site_id' => $siteId,
                    'page_id' => null, // Homepage components have page_id = null
                    'is_homepage_component' => true,
                    'is_active' => true,
                    'sort_order' => 0,
                    'created_by' => auth()->id(),
                ]);
            }

            Log::info("Homepage component data saved", [
                'site_id' => $siteId,
                'component_name' => $componentName,
                'type' => $type,
                'component_id' => $component->id
            ]);

            return $component;

        } catch (Exception $e) {
            Log::error("Error saving homepage component data: " . $e->getMessage(), [
                'site_id' => $siteId,
                'component_name' => $componentName
            ]);
            throw $e;
        }
    }

    /**
     * Load homepage component data from the components table
     */
    public function loadHomepageComponentData($siteId, $componentName)
    {
        try {
            $type = Component::nameToType($componentName);

            $component = Component::forSite($siteId)
                ->whereNull('page_id')
                ->ofType($type)
                ->homepage()
                ->active()
                ->first();

            return $component ? $component->content : null;

        } catch (Exception $e) {
            Log::error("Error loading homepage component data: " . $e->getMessage(), [
                'site_id' => $siteId,
                'component_name' => $componentName
            ]);
            return null;
        }
    }

    /**
     * Load all homepage components for a site
     */
    public function loadHomepageComponents($siteId)
    {
        try {
            $components = Component::forSite($siteId)
                ->whereNull('page_id')
                ->homepage()
                ->active()
                ->orderBy('sort_order')
                ->get();

            $result = [];
            foreach ($components as $component) {
                $result[$component->type] = $component->content ?? [];
            }

            return $result;

        } catch (Exception $e) {
            Log::error("Error loading homepage components: " . $e->getMessage(), [
                'site_id' => $siteId
            ]);
            return [];
        }
    }

    /**
     * Migrate component data from sites.settings to components table
     */
    public function migrateFromSiteSettings($siteId)
    {
        try {
            $site = Site::find($siteId);
            if (!$site) {
                throw new Exception("Site not found with ID: {$siteId}");
            }

            $settings = $site->settings ?: [];
            $migratedCount = 0;

            // Define component mappings
            $componentMappings = [
                'heroSlides' => 'HeroCarousel',
                'headlines' => 'HeadlineMarquee',
                'messageFromItems' => 'MessageFrom',
                'facultyItems' => 'FacultiesCarousel',
                'welcomeItems' => 'WelcomeSection',
                'campusLifeItems' => 'CampusLife',
                'glanceItems' => 'AtAGlance',
                'newsItems' => 'DynamicNews',
                'eventItems' => 'DynamicEvents',
                'noticeItems' => 'DynamicNotices',
                'publicationItems' => 'TopPublication',
                'footerData' => 'Footer',
            ];

            foreach ($componentMappings as $settingKey => $componentName) {
                if (isset($settings[$settingKey]) && !empty($settings[$settingKey])) {
                    $this->saveHomepageComponent($siteId, $componentName, $settings[$settingKey]);
                    $migratedCount++;
                }
            }

            Log::info("Component migration completed", [
                'site_id' => $siteId,
                'migrated_count' => $migratedCount
            ]);

            return $migratedCount;

        } catch (Exception $e) {
            Log::error("Error migrating component data: " . $e->getMessage(), [
                'site_id' => $siteId
            ]);
            throw $e;
        }
    }

    /**
     * Save dynamic page component data to the components table
     */
    public function saveDynamicPageComponent($siteId, $pageId, $componentId, $componentType, $componentData, $sortOrder = 0)
    {
        try {
            $type = Component::nameToType($componentType);

            // Check if component already exists for this page
            $component = Component::forSite($siteId)
                ->where('page_id', $pageId)
                ->where('content->component_id', $componentId)
                ->ofType($type)
                ->first();

            if ($component) {
                // Update existing component
                $component->update([
                    'content' => array_merge($component->content ?? [], [
                        'component_id' => $componentId,
                        'data' => $componentData
                    ]),
                    'sort_order' => $sortOrder,
                    'updated_by' => auth()->id(),
                ]);
            } else {
                // Create new component
                $component = Component::create([
                    'name' => $componentType,
                    'type' => $type,
                    'content' => [
                        'component_id' => $componentId,
                        'data' => $componentData
                    ],
                    'site_id' => $siteId,
                    'page_id' => $pageId, // Use the page_id column
                    'is_homepage_component' => false, // Dynamic page components are not homepage components
                    'is_active' => true,
                    'sort_order' => $sortOrder,
                    'created_by' => auth()->id(),
                ]);
            }

            return $component;

        } catch (Exception $e) {
            Log::error("Error saving dynamic page component data: " . $e->getMessage(), [
                'site_id' => $siteId,
                'page_id' => $pageId,
                'component_id' => $componentId,
                'component_type' => $componentType
            ]);
            throw $e;
        }
    }

    /**
     * Load dynamic page component data from the components table
     */
    public function loadDynamicPageComponent($siteId, $pageId, $componentId, $componentType)
    {
        try {
            $type = Component::nameToType($componentType);

            $component = Component::forSite($siteId)
                ->where('page_id', $pageId)
                ->where('content->component_id', $componentId)
                ->ofType($type)
                ->active()
                ->first();

            return $component ? $component->content['data'] ?? null : null;

        } catch (Exception $e) {
            Log::error("Error loading dynamic page component data: " . $e->getMessage(), [
                'site_id' => $siteId,
                'page_id' => $pageId,
                'component_id' => $componentId,
                'component_type' => $componentType
            ]);
            return null;
        }
    }

    /**
     * Load all components for a specific page
     */
    public function loadPageComponents($siteId, $pageId)
    {
        try {
            $components = Component::forSite($siteId)
                ->where('page_id', $pageId)
                ->where('is_homepage_component', false)
                ->active()
                ->orderBy('sort_order')
                ->get();

            $result = [];
            foreach ($components as $component) {
                $componentId = $component->content['component_id'] ?? null;
                if ($componentId) {
                    $result[$componentId] = $component->content['data'] ?? [];
                }
            }

            return $result;

        } catch (Exception $e) {
            Log::error("Error loading page components: " . $e->getMessage(), [
                'site_id' => $siteId,
                'page_id' => $pageId
            ]);
            return [];
        }
    }

    /**
     * Migrate existing page components from pages.content to components table
     */
    public function migratePageComponentsFromContent($pageId)
    {
        try {
            $page = \App\Models\Page::find($pageId);
            if (!$page) {
                throw new Exception("Page not found with ID: {$pageId}");
            }

            $content = $page->content ?: [];
            $components = $content['components'] ?? [];
            $componentSettings = $content['componentSettings'] ?? [];

            $migratedCount = 0;
            $siteId = $page->site_id ?? 1;

            foreach ($components as $component) {
                $componentId = $component['id'] ?? null;
                $componentType = $component['type'] ?? null;
                $sortOrder = $component['order'] ?? 0;

                if ($componentId && $componentType) {
                    // Get component settings
                    $componentData = $componentSettings[$componentId] ?? [];

                    // Save to components table with sort order
                    $this->saveDynamicPageComponent($siteId, $pageId, $componentId, $componentType, $componentData, $sortOrder);
                    $migratedCount++;
                }
            }

            Log::info("Page components migration completed", [
                'page_id' => $pageId,
                'migrated_count' => $migratedCount
            ]);

            return $migratedCount;

        } catch (Exception $e) {
            Log::error("Error migrating page components: " . $e->getMessage(), [
                'page_id' => $pageId
            ]);
            throw $e;
        }
    }

    /**
     * Get component data for frontend (backward compatibility)
     */
    public function getComponentDataForFrontend($siteId, $componentName)
    {
        // Special-case: load dynamic events/notices from their dedicated tables if present
        if ($componentName === 'DynamicEvents') {
            try {
                $events = \App\Models\Event::forSite($siteId)
                    ->orderBy('starts_at', 'desc')
                    ->orderBy('sort_order', 'asc')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'title' => $item->title,
                            'excerpt' => $item->excerpt,
                            'content' => $item->content,
                            'image' => $item->image,
                            'link' => $item->link,
                            'isActive' => $item->is_active,
                            'displayOrder' => $item->sort_order,
                            'starts_at' => $item->starts_at?->format('Y-m-d') ?? null,
                            'ends_at' => $item->ends_at?->format('Y-m-d') ?? null,
                        ];
                    })
                    ->values()
                    ->all();

                return $events;
            } catch (\Exception $e) {
                Log::error('Failed to load events from DB: ' . $e->getMessage());
            }
        }

        if ($componentName === 'DynamicNotices') {
            try {
                $notices = \App\Models\Notice::forSite($siteId)
                    ->orderBy('published_at', 'desc')
                    ->orderBy('sort_order', 'asc')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'title' => $item->title,
                            'excerpt' => $item->excerpt,
                            'content' => $item->content,
                            'image' => $item->image,
                            'link' => $item->link,
                            'isActive' => $item->is_active,
                            'displayOrder' => $item->sort_order,
                            'date' => $item->published_at?->format('Y-m-d') ?? $item->created_at->format('Y-m-d'),
                            'priority' => $item->priority ?? 'medium',
                            'department' => $item->department ?? null,
                            'attachments' => $item->attachments ?? [],
                        ];
                    })
                    ->values()
                    ->all();

                return $notices;
            } catch (\Exception $e) {
                Log::error('Failed to load notices from DB: ' . $e->getMessage());
            }
        }

        // Try to load from components table first (homepage components)
        $data = $this->loadHomepageComponentData($siteId, $componentName);

        if ($data !== null) {
            return $data;
        }

        // Fallback to sites.settings for backward compatibility
        $site = Site::find($siteId);
        if ($site && $site->settings) {
            $settings = $site->settings;
            $settingKey = $this->getSettingKeyForComponent($componentName);

            if (isset($settings[$settingKey])) {
                return $settings[$settingKey];
            }
        }

        return null;
    }

    /**
     * Delete components that are no longer present in the page
     */
    public function deleteRemovedPageComponents($siteId, $pageId, $currentComponentIds)
    {
        try {
            // Get all existing components for this page
            $existingComponents = Component::forSite($siteId)
                ->where('page_id', $pageId)
                ->where('is_homepage_component', false)
                ->get();

            $deletedCount = 0;

            foreach ($existingComponents as $component) {
                $componentId = $component->content['component_id'] ?? null;

                // If this component is not in the current list, delete it
                if ($componentId && !in_array($componentId, $currentComponentIds)) {
                    $component->delete(); // This will soft delete due to SoftDeletes trait
                    $deletedCount++;

                    Log::info('Component deleted from page', [
                        'site_id' => $siteId,
                        'page_id' => $pageId,
                        'component_id' => $componentId,
                        'component_type' => $component->name
                    ]);
                }
            }

            Log::info('Component cleanup completed', [
                'site_id' => $siteId,
                'page_id' => $pageId,
                'deleted_count' => $deletedCount
            ]);

            return $deletedCount;

        } catch (Exception $e) {
            Log::error("Error deleting removed page components: " . $e->getMessage(), [
                'site_id' => $siteId,
                'page_id' => $pageId
            ]);
            throw $e;
        }
    }

    /**
     * Get setting key for component name
     */
    private function getSettingKeyForComponent($componentName)
    {
        $mappings = [
            'HeroCarousel' => 'heroSlides',
            'HeadlineMarquee' => 'headlines',
            'MessageFrom' => 'messageFromItems',
            'FacultiesCarousel' => 'facultyItems',
            'WelcomeSection' => 'welcomeItems',
            'CampusLife' => 'campusLifeItems',
            'AtAGlance' => 'glanceItems',
            'DynamicNews' => 'newsItems',
            'DynamicEvents' => 'eventItems',
            'DynamicNotices' => 'noticeItems',
            'TopPublication' => 'publicationItems',
            'Footer' => 'footerData',
        ];

        return $mappings[$componentName] ?? null;
    }
}
