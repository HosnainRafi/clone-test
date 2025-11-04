<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\HeroCarouselController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\NoticeController as AdminNoticeController;
use App\Http\Controllers\Admin\PublicationController;
use App\Http\Controllers\Admin\MessageFromController;
use App\Http\Controllers\Admin\HeadlineMarqueeController;
use App\Http\Controllers\Admin\FacultiesController;
use App\Http\Controllers\Admin\WelcomeSectionController;
use App\Http\Controllers\Admin\CampusLifeController;
use App\Http\Controllers\Admin\FooterController;
use App\Http\Controllers\Admin\TopBarController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\EventController;
use App\Http\Controllers\Public\NoticeController;
use App\Http\Controllers\Api\NewsController as ApiNewsController;
use App\Models\Component;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Middleware\SubdomainMiddleware;

require __DIR__.'/admin.php';
// Apply SubdomainMiddleware to all routes that need site data
Route::middleware(['web', 'subdomain'])->group(function () {

    // ==========================================
    // PUBLIC ROUTES
    // ==========================================

    // Home & Department Routes
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/department', [HomeController::class, 'department'])->name('department');

    // Public Content Routes
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');
    Route::get('/notices', [NoticeController::class, 'index'])->name('notices.index');
    Route::get('/notices/{slug}', [NoticeController::class, 'show'])->name('notices.show');

    // ==========================================
    // API ROUTES
    // ==========================================

    // News API Routes
    Route::prefix('api/news')->group(function () {
        Route::get('/', [ApiNewsController::class, 'index'])->name('api.news.index');
        Route::post('/', [ApiNewsController::class, 'store'])->name('api.news.store');
        Route::get('/featured', [ApiNewsController::class, 'featured'])->name('api.news.featured');
        Route::get('/category/{category}', [ApiNewsController::class, 'byCategory'])->name('api.news.byCategory');
        Route::get('/{id}', [ApiNewsController::class, 'show'])->name('api.news.show');
        Route::get('/slug/{slug}', [ApiNewsController::class, 'showBySlug'])->name('api.news.showBySlug');
        Route::put('/{id}', [ApiNewsController::class, 'update'])->name('api.news.update');
        Route::delete('/{id}', [ApiNewsController::class, 'destroy'])->name('api.news.destroy');
    });

    // ==========================================
    // ADMIN CONTENT MANAGEMENT ROUTES
    // ==========================================
    Route::prefix('admin')->group(function () {
        // Menu Management
        Route::get('/menu', [MenuController::class, 'index'])->name('admin.menu');
        Route::post('/menu', [MenuController::class, 'save'])->name('admin.menu.save');

        // Topbar Management
        Route::get('/topbar', [TopBarController::class, 'index'])->name('admin.topbar.index');
        Route::post('/topbar', [TopBarController::class, 'save'])->name('admin.topbar.save');

        // Hero Carousel Management
        Route::get('/hero-carousel', [HeroCarouselController::class, 'index'])->name('admin.hero-carousel');
        Route::post('/hero-carousel', [HeroCarouselController::class, 'save'])->name('admin.hero-carousel.save');

        // Hero Carousel Management
        Route::get('/hero-carousel', [HeroCarouselController::class, 'index'])->name('admin.hero-carousel');
        Route::post('/hero-carousel', [HeroCarouselController::class, 'save'])->name('admin.hero-carousel.save');

        // Message From Management
        Route::get('/message-from', [MessageFromController::class, 'index'])->name('admin.message-from');
        Route::post('/message-from', [MessageFromController::class, 'save'])->name('admin.message-from.save');

        // Headline Marquee Management
        Route::get('/headline-marquee', [HeadlineMarqueeController::class, 'index'])->name('admin.headline-marquee');
        Route::post('/headline-marquee', [HeadlineMarqueeController::class, 'save'])->name('admin.headline-marquee.save');

        // Faculties Management
        Route::get('/faculties', [FacultiesController::class, 'index'])->name('admin.faculties');
        Route::post('/faculties', [FacultiesController::class, 'save'])->name('admin.faculties.save');

        // Welcome Section Management
        Route::get('/welcome-section', [WelcomeSectionController::class, 'index'])->name('admin.welcome-section');
        Route::post('/welcome-section', [WelcomeSectionController::class, 'save'])->name('admin.welcome-section.save');

        // Campus Life Management
        Route::get('/campus-life-section', [CampusLifeController::class, 'index'])->name('admin.campus-life.index');
        Route::post('/campus-life-section', [CampusLifeController::class, 'save'])->name('admin.campus-life.save');

        // Campus Glance Management
        Route::get('/campus-glance', [CampusLifeController::class, 'glance'])->name('admin.campus-glance.index');
        Route::post('/campus-glance', [CampusLifeController::class, 'saveGlance'])->name('admin.campus-glance.save');

        // News Section Management
        Route::get('/news-section', [AdminNewsController::class, 'index'])->name('admin.news-section.index');
        Route::post('/news-section', [AdminNewsController::class, 'save'])->name('admin.news-section.save');

        // Events Section Management
        Route::get('/events-section', [AdminEventController::class, 'index'])->name('admin.events.admin');
        Route::post('/events-section', [AdminEventController::class, 'save'])->name('admin.events.save');

        // Notices Section Management
        Route::get('/notices-section', [AdminNoticeController::class, 'index'])->name('admin.notices.admin');
        Route::post('/notices-section', [AdminNoticeController::class, 'save'])->name('admin.notices.save');

        // Publications Management
        Route::get('/publications-section', [PublicationController::class, 'index'])->name('admin.publications.admin');
        Route::post('/publications-section', [PublicationController::class, 'save'])->name('admin.publications.save');

        // Footer Management
        Route::get('/footer-section', [FooterController::class, 'index'])->name('admin.footer.admin');
        Route::post('/footer-section', [FooterController::class, 'save'])->name('admin.footer.save');

        // Dashboard
        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard/Index');
        })->name('admin.dashboard');

        // Profile and Settings
        Route::get('/profile', function () {
            return Inertia::render('Profile/ProfileView');
        })->name('admin.profile');

        Route::get('/settings', function () {
            return Inertia::render('Settings/SettingsView');
        })->name('admin.settings');
    });
    }); // End admin routes group

    // ==========================================
    // INERTIA/VUE PAGE ROUTES (MOVED INSIDE MIDDLEWARE)
    // ==========================================

    // Auth Routes
    Route::get('/signin', function () {
        return Inertia::render('Authentication/SigninView');
    })->name('signin');

    Route::get('/signup', function () {
        return Inertia::render('Authentication/SignupView');
    })->name('signup');

    // Admin Routes Group
    Route::prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard/Index');
        })->name('admin.dashboard');

        // Profile and Settings
        Route::get('/profile', function () {
            return Inertia::render('Profile/ProfileView');
        })->name('admin.profile');

        Route::get('/settings', function () {
            return Inertia::render('Settings/SettingsView');
        })->name('admin.settings');

}); // END OF SUBDOMAIN MIDDLEWARE GROUP

// ==========================================
// DEVELOPMENT/TESTING ROUTES (NO INERTIA)
// ==========================================

Route::get('/test-db', function () {
    try {
        $sites = \Illuminate\Support\Facades\DB::table('sites')->get();
        return response()->json([
            'success' => true,
            'message' => 'Database connection successful',
            'sites_count' => $sites->count(),
            'sites' => $sites->take(3)
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
})->name('test.db');

        // Pages Management Routes (admin)
        Route::get('/admin/pages', function () {
            return Inertia::render('Pages');
        })->name('admin.pages.index');

        Route::get('/admin/pages/create', function () {
            return Inertia::render('PageEditor');
        })->name('admin.pages.create');

        Route::get('/admin/pages/{id}/edit', function ($id) {
            return Inertia::render('PageEditor', [
                'pageId' => $id
            ]);
        })->name('admin.pages.edit');

        // Themes Management Routes
        Route::get('/admin/themes', function () {
            return Inertia::render('Themes');
        })->name('admin.themes.index');

        // Keep singular /admin/theme redirect for backward-compatibility
        Route::get('/admin/theme', function () {
            return redirect('/admin/themes');
        })->name('admin.theme.redirect');

// API route to fetch pages for current domain
Route::get('/api/pages', function (Illuminate\Http\Request $request) {
    try {
        // Get current domain or site context
        $domain = $request->getHost();

        // Fetch pages from database - using actual table columns
        $pages = \DB::table('pages')
            ->select('id', 'name as title', 'slug', 'description', 'content', 'created_at', 'updated_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($pages);
    } catch (\Exception $e) {
        \Log::error('Error fetching pages: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to fetch pages'], 500);
    }
});

// API route to fetch a single page
Route::get('/api/pages/{id}', function ($id) {
    try {
        $page = \DB::table('pages')
            ->select('id', 'name as title', 'slug', 'description', 'content', 'created_at', 'updated_at')
            ->where('id', $id)
            ->first();

        if (!$page) {
            return response()->json(['error' => 'Page not found'], 404);
        }

        // Load components from components table instead of pages.content
        $pageComponents = Component::forSite(1) // Default site_id
            ->where('page_id', $page->id)
            ->where('is_homepage_component', false)
            ->active()
            ->orderBy('sort_order')
            ->get();

        // Build components array and componentSettings from components table
        $components = [];
        $componentSettings = [];

        foreach ($pageComponents as $component) {
            $componentId = $component->content['component_id'] ?? null;
            if ($componentId) {
                // Add to components array for frontend
                $components[] = [
                    'id' => $componentId,
                    'type' => $component->name,
                    'order' => $component->sort_order
                ];

                // Add to componentSettings
                $componentSettings[$componentId] = $component->content['data'] ?? [];
            }
        }

        return response()->json([
            'id' => $page->id,
            'title' => $page->title,
            'slug' => $page->slug,
            'description' => $page->description,
            'components' => $components,
            'componentSettings' => $componentSettings,
            'created_at' => $page->created_at,
            'updated_at' => $page->updated_at
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching page: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to fetch page'], 500);
    }
});

// API route to create a new page
Route::post('/api/pages', function (Illuminate\Http\Request $request) {
    try {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'components' => 'sometimes|array',
            'components.*.id' => 'sometimes|string',
            'components.*.type' => 'sometimes|string',
            'components.*.order' => 'sometimes|integer',
            'componentSettings' => 'sometimes|array'
        ]);

        // Sort components by order to maintain proper sequence
        $components = $validated['components'] ?? [];
        usort($components, function($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        // Prepare content with ordered components and settings
        // Ensure componentSettings is an object, not an array
        $componentSettings = $validated['componentSettings'] ?? [];
        if (empty($componentSettings)) {
            $componentSettings = [];
        }

        // Don't store component data in pages table anymore - store only basic page info
        $siteId = $request->get('siteData.id', 1);

        $pageId = \DB::table('pages')->insertGetId([
            'name' => $validated['title'], // Using 'name' column
            'slug' => $validated['slug'],
            'description' => null,
            'content' => json_encode(['components' => [], 'componentSettings' => []]), // Empty structure for backward compatibility
            'site_id' => $siteId,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Save component data to components table using ComponentService
        $componentService = new \App\Services\ComponentService();

        \Log::info('Saving components for page', [
            'page_id' => $pageId,
            'components_count' => count($components),
            // 'component_settings_count' => count($componentSettings)
        ]);

        // For new pages, we don't need to clean up existing components, but we'll keep the logic consistent
        $currentComponentIds = array_column($components, 'id');
        $deletedCount = $componentService->deleteRemovedPageComponents($siteId, $pageId, $currentComponentIds);

        \Log::info('Component cleanup completed for new page', [
            'page_id' => $pageId,
            'deleted_count' => $deletedCount
        ]);

        foreach ($components as $component) {
            $componentId = $component['id'] ?? null;
            $componentType = $component['type'] ?? null;
            $componentData = $componentSettings[$componentId] ?? [];
            $sortOrder = $component['order'] ?? 0;

            if ($componentId && $componentType) {
                try {
                    // Always save component, even if no data yet (for structure)
                    $saved = $componentService->saveDynamicPageComponent($siteId, $pageId, $componentId, $componentType, $componentData, $sortOrder);
                    \Log::info('Component saved successfully', [
                        'component_id' => $componentId,
                        'component_type' => $componentType,
                        'sort_order' => $sortOrder,
                        'saved_db_id' => $saved?->id ?? null,
                        'saved_content' => $saved?->content ?? null
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to save component', [
                        'component_id' => $componentId,
                        'component_type' => $componentType,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
        }

        $page = \DB::table('pages')
            ->select('id', 'name as title', 'slug', 'description', 'content', 'created_at', 'updated_at')
            ->where('id', $pageId)
            ->first();

        // Parse and return components for frontend
        $pageData = [
            'id' => $page->id,
            'title' => $page->title,
            'slug' => $page->slug,
            'description' => $page->description,
            'components' => $components,
            'componentSettings' => $componentSettings,
            'created_at' => $page->created_at,
            'updated_at' => $page->updated_at
        ];

        return response()->json($pageData, 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        \Log::error('Error creating page: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to create page'], 500);
    }
});

// Test route to verify API is working
Route::post('/api/test-save', function (Illuminate\Http\Request $request) {
    \Log::info('Test save route hit', ['data' => $request->all()]);
    return response()->json(['success' => true, 'message' => 'Test route working']);
});

// API route to update a page
Route::put('/api/pages/{id}', function (Illuminate\Http\Request $request, $id) {
    try {
        \Log::info('Update page request received', [
            'id' => $id,
            'request_data' => $request->all()
        ]);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $id,
            'components' => 'sometimes|array',
            'components.*.id' => 'sometimes|string',
            'components.*.type' => 'sometimes|string',
            'components.*.order' => 'sometimes|integer',
            'componentSettings' => 'sometimes|array'
        ]);

        // Sort components by order to maintain proper sequence
        $components = $validated['components'] ?? [];
        usort($components, function($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        // Prepare content with ordered components and settings
        // Ensure componentSettings is an array
        $componentSettings = $validated['componentSettings'] ?? [];
        if (empty($componentSettings)) {
            $componentSettings = [];
        }

        $content = [
            'components' => $components,
            'componentSettings' => $componentSettings
        ];

        \Log::info('Prepared content for saving', [
            'content' => $content,
            'componentSettings' => $componentSettings
        ]);

        \DB::table('pages')->where('id', $id)->update([
            'name' => $validated['title'], // Using 'name' column
            'slug' => $validated['slug'],
            'content' => json_encode(['components' => [], 'componentSettings' => []]), // Empty structure for backward compatibility
            'updated_at' => now()
        ]);

    // Save component data to components table using ComponentService
    $siteId = $request->get('siteData.id', 1);
    $componentService = new \App\Services\ComponentService();

        \Log::info('Updating components for page', [
            'page_id' => $id,
            'components_count' => count($components),
            'component_settings_count' => count((array)$componentSettings)
        ]);

        // First, delete components that are no longer present
        $currentComponentIds = array_column($components, 'id');
        $deletedCount = $componentService->deleteRemovedPageComponents($siteId, $id, $currentComponentIds);

        \Log::info('Component cleanup completed', [
            'page_id' => $id,
            'deleted_count' => $deletedCount
        ]);

        // Then save/update existing components
        foreach ($components as $component) {
            $componentId = $component['id'] ?? null;
            $componentType = $component['type'] ?? null;
            $componentData = $componentSettings[$componentId] ?? [];
            $sortOrder = $component['order'] ?? 0;

            if ($componentId && $componentType) {
                try {
                    // Always save component, even if no data yet (for structure)
                    $saved = $componentService->saveDynamicPageComponent($siteId, $id, $componentId, $componentType, $componentData, $sortOrder);
                    \Log::info('Component updated successfully', [
                        'component_id' => $componentId,
                        'component_type' => $componentType,
                        'sort_order' => $sortOrder,
                        'saved_db_id' => $saved?->id ?? null,
                        'saved_content' => $saved?->content ?? null
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to update component', [
                        'component_id' => $componentId,
                        'component_type' => $componentType,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
        }

        $page = \DB::table('pages')
            ->select('id', 'name as title', 'slug', 'description', 'content', 'created_at', 'updated_at')
            ->where('id', $id)
            ->first();

        // Parse and return components for frontend
        $pageData = [
            'id' => $page->id,
            'title' => $page->title,
            'slug' => $page->slug,
            'description' => $page->description,
            'components' => $components,
            'componentSettings' => $componentSettings,
            'created_at' => $page->created_at,
            'updated_at' => $page->updated_at
        ];

        return response()->json($pageData);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        \Log::error('Error updating page: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to update page'], 500);
    }
});

// API route to delete a page
Route::delete('/api/pages/{id}', function ($id) {
    try {
        // First, delete all components associated with this page
        $componentService = new \App\Services\ComponentService();
        $siteId = 1; // Default site_id, adjust as needed

        // Get all components for this page and delete them
        $existingComponents = \App\Models\Component::forSite($siteId)
            ->where('page_id', $id)
            ->where('is_homepage_component', false)
            ->get();

        $deletedComponentsCount = 0;
        foreach ($existingComponents as $component) {
            $component->delete(); // Soft delete
            $deletedComponentsCount++;
        }

        \Log::info('Page deletion - components cleaned up', [
            'page_id' => $id,
            'deleted_components_count' => $deletedComponentsCount
        ]);

        // Then delete the page
        \DB::table('pages')->where('id', $id)->delete();

        return response()->json([
            'message' => 'Page deleted successfully',
            'deleted_components_count' => $deletedComponentsCount
        ]);
    } catch (\Exception $e) {
        \Log::error('Error deleting page: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to delete page'], 500);
    }
});

Route::get('/test', function () {
    return '<html><body style="padding:40px;font-family:Arial;"><h1 style="color:green;">✅ Docker Production Test</h1><p>If you can see this, your Docker environment is working perfectly!</p><p><a href="/">← Back to main site</a></p></body></html>';
})->name('test');

// Test route to check pages table
Route::get('/test-pages', function () {
    try {
        // Check if pages table exists and show structure
        $pages = \DB::table('pages')->get();
        $tableInfo = \DB::select("DESCRIBE pages");

        return response()->json([
            'success' => true,
            'table_structure' => $tableInfo,
            'pages_count' => $pages->count(),
            'pages' => $pages->take(5) // Show first 5 pages
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// Test route to check single page API
Route::get('/test-page/{id}', function ($id) {
    try {
        $page = \DB::table('pages')
            ->select('id', 'name as title', 'slug', 'description', 'content', 'created_at', 'updated_at')
            ->where('id', $id)
            ->first();

        if (!$page) {
            return response()->json(['error' => 'Page not found'], 404);
        }

        return response()->json([
            'success' => true,
            'page' => $page,
            'raw_page' => \DB::table('pages')->where('id', $id)->first()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// Public page route - This should be at the end to catch all remaining routes
Route::middleware(SubdomainMiddleware::class)
    ->get('/{slug}', function (Illuminate\Http\Request $request, $slug) {
        try {
            $siteData = $request->get('siteData');
            $menuItems = [];
            if (isset($siteData['settings']['menuItems'])) {
                $menuItems = $siteData['settings']['menuItems'];
            } else {
                \Log::error('Menu items not found in site data');
            }

            $data = (object) [
                'siteData' => $siteData,
                'theme' => $request->get('theme'),
                'components' => $request->get('components', collect()),
                'viewType' => $request->get('viewType'),
                'fullDomain' => $request->get('fullDomain'),
                'page' => $request->get('page'),
                'menuItems' => $menuItems,
            ];
            // Get current domain or site context
            $domain = request()->getHost();

            // Find page by slug for the current site
            $page = \DB::table('pages')
                ->select('id', 'name as title', 'slug', 'description', 'content', 'created_at', 'updated_at')
                ->where('slug', $slug)
                ->where('site_id', 1) // Adjust based on your site logic
                ->first();

            if (!$page) {
                // Page not found, render custom 404 page with site data
                return Inertia::render('NotFound', [
                    'message' => 'Page not found',
                    'data' => $data,
                    'requestedUrl' => '/' . $slug
                ]);
            }

            // Load components directly from components table using ComponentService
            $componentService = new \App\Services\ComponentService();
            $siteId = 1; // Default site_id, adjust as needed

            // Get all components for this page from components table
            $pageComponents = Component::forSite($siteId)
                ->where('page_id', $page->id)
                ->where('is_homepage_component', false)
                ->active()
                ->orderBy('sort_order')
                ->get();

            // Build components array and componentSettings from components table
            $components = [];
            $componentSettings = [];

            foreach ($pageComponents as $component) {
                $componentId = $component->content['component_id'] ?? null;
                if ($componentId) {
                    // Add to components array for frontend
                    $components[] = [
                        'id' => $componentId,
                        'type' => $component->name,
                        'order' => $component->sort_order
                    ];

                    // Add to componentSettings
                    $componentSettings[$componentId] = $component->content['data'] ?? [];
                }
            }

            // Add componentSettings to data object
            $data->componentSettings = $componentSettings;

            // Return the page view with components and all site data
            return Inertia::render('PublicPage', [
                'message' => 'Page loaded successfully',
                'data' => $data,
                'page' => [
                    'id' => $page->id,
                    'title' => $page->title,
                    'slug' => $page->slug,
                    'description' => $page->description,
                    'components' => $components,
                    'created_at' => $page->created_at,
                    'updated_at' => $page->updated_at
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading public page: ' . $e->getMessage());
            abort(500);
        }
    })->where('slug', '[a-zA-Z0-9\-_]+');
