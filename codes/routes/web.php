<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\SubdomainMiddleware;
use Illuminate\Pagination\Paginator;

Route::middleware(SubdomainMiddleware::class)
    ->get('/', function (Illuminate\Http\Request $request) {

        $siteData = $request->get('siteData');
        $menuItems = [];
        $heroSlides = [];
        $headlines = [];
        $messageFromItems = [];
        $facultyItems = [];
        $welcomeItems = [];
        $campusLifeItems = [];
        $glanceItems = [];
        $newsItems = $siteData['settings']['newsItems'] ?? [];

        if (isset($siteData['settings']['menuItems'])) {
            $menuItems = $siteData['settings']['menuItems'];
        } else {
            \Log::error('Menu items not found in site data');
        }


          if (isset($siteData['settings']['heroSlides'])) {
            $heroSlides = $siteData['settings']['heroSlides'];
        } else {
            \Log::error('Hero slides not found in site data');
        }

        if (isset($siteData['settings']['headlines'])) {
            $headlines = $siteData['settings']['headlines'];
        } else {
            \Log::error(' headlines not found in site data');
        }

        if (isset($siteData['settings']['messageFromItems'])) {
            $messageFromItems = $siteData['settings']['messageFromItems'];
            \Log::info('MessageFromItems found:', ['count' => count($messageFromItems)]);
        } else {
            \Log::error('MessageFromItems not found in site data');
        }

         if (isset($siteData['settings']['facultyItems'])) {
            $facultyItems = $siteData['settings']['facultyItems'];
            \Log::info('FacultyItems found:', ['count' => count($facultyItems)]);
        } else {
            \Log::error('FacultyItems not found in site data');
        }

         if (isset($siteData['settings']['welcomeItems'])) {
            $welcomeItems = $siteData['settings']['welcomeItems'];
            \Log::info('WelcomeItems found:', ['count' => count($welcomeItems)]);
        } else {
            \Log::error('WelcomeItems not found in site data');
        }

        if (isset($siteData['settings']['campusLifeItems'])) {
            $campusLifeItems = $siteData['settings']['campusLifeItems'];
            \Log::info('CampusLifeItems found:', ['count' => count($campusLifeItems)]);
        } else {
            \Log::error('CampusLifeItems not found in site data, using default.');

        }

        if (isset($siteData['settings']['glanceItems'])) {
            $glanceItems = $siteData['settings']['glanceItems'];
            \Log::info('GlanceItems found:', ['count' => count($glanceItems)]);
        } else {
            \Log::error('GlanceItems not found in site data, using default.');
            // Default data if not found in settings
            $glanceItems = [
                ['id' => 1, 'label' => 'Active Students', 'value' => '5000+', 'iconName' => 'Users', 'iconColor' => '#3b82f6', 'isActive' => true, 'displayOrder' => 1],
                ['id' => 2, 'label' => 'Student Clubs', 'value' => '25+', 'iconName' => 'Trophy', 'iconColor' => '#10b981', 'isActive' => true, 'displayOrder' => 2],
                ['id' => 3, 'label' => 'Residential Halls', 'value' => '6+', 'iconName' => 'Home', 'iconColor' => '#8b5cf6', 'isActive' => true, 'displayOrder' => 3],
                ['id' => 4, 'label' => 'Annual Events', 'value' => '100+', 'iconName' => 'Calendar', 'iconColor' => '#f59e0b', 'isActive' => true, 'displayOrder' => 4],
            ];
        }
        if (isset($siteData['settings']['newsItems'])) {
            $newsItems = $siteData['settings']['newsItems'];
            \Log::info('newsItems found:', ['count' => count($newsItems)]);
        } else {
            \Log::error('newsItems not found in site data, using default.');
            // Default data if not found in settings

        }


        $data = (object) [
            'siteData' => $siteData,
            'theme' => $request->get('theme'),
            'components' => $request->get('components', collect()),
            'viewType' => $request->get('viewType'),
            'fullDomain' => $request->get('fullDomain'),
            'page' => $request->get('page'),
            'menuItems' => $menuItems,
            'heroSlides' => $heroSlides,
            'headlines' => $headlines,
            'messageFromItems' => $messageFromItems,
            'facultyItems' => $facultyItems,
            'welcomeItems' => $welcomeItems,
            'campusLifeItems' => $campusLifeItems,
            'glanceItems' => $glanceItems,
            'newsItems' => $newsItems,
        ];

        return Inertia::render('University',[
            'message' => 'Hello World - Changed View!',
            'data' => $data,
        ]);
    })->name('home');

Route::get('/department', function () {
    return Inertia::render('Department');
})->name('department');

Route::middleware(SubdomainMiddleware::class)
    ->get('/menu', function (Illuminate\Http\Request $request) {
        $siteData = $request->get('siteData');
        $menuItems = [];


        // Load existing menu items from site data
        if (isset($siteData['settings']['menuItems'])) {
            $menuItems = $siteData['settings']['menuItems'];
        }


        // Get site ID from various possible sources
        $siteId = $request->get('siteId') ??
                  ($siteData['id'] ?? null) ??
                  ($siteData['site_id'] ?? null) ??
                  1; // Fallback to 1 for development

        return Inertia::render('Menu/Index', [
            'siteData' => $siteData,
            'menuItems' => $menuItems,
            'siteId' => $siteId,
        ]);
    })->name('menu');

Route::middleware(SubdomainMiddleware::class)
    ->post('/menu/save', function (Illuminate\Http\Request $request) {
        \Log::info('Menu save request received', [
            'input' => $request->all(),
            'siteData' => $request->get('siteData')
        ]);

        // Get site data from middleware
        $siteData = $request->get('siteData');
        $menuItems = $request->input('menuItems');
        $requestSiteId = $request->input('siteId');

        // Determine the actual site ID
        $actualSiteId = null;
        if ($siteData && isset($siteData->id)) {
            $actualSiteId = $siteData->id;
        } elseif ($requestSiteId) {
            $actualSiteId = $requestSiteId;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No site ID found. Site data: ' . json_encode($siteData)
            ], 422);
        }

        \Log::info('Using site ID: ' . $actualSiteId);

        // Validate the request
        if (!$menuItems || !is_array($menuItems)) {
            return response()->json([
                'success' => false,
                'message' => 'Menu items are required and must be an array'
            ], 422);
        }

        // Validate JSON structure
        try {
            $jsonString = json_encode($menuItems);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid JSON format: ' . json_last_error_msg()
                ], 422);
            }

            // Validate menu structure (more lenient for testing)
            foreach ($menuItems as $index => $item) {
                if (!isset($item['title']) || !isset($item['col'])) {
                    return response()->json([
                        'success' => false,
                        'message' => "Menu item at index {$index} is missing required fields (title, col)"
                    ], 422);
                }

                if (!isset($item['subItems']) || !is_array($item['subItems'])) {
                    // Set empty array if subItems is missing
                    $menuItems[$index]['subItems'] = [];
                }
            }

            // Check if site exists
            $site = \DB::table('sites')->where('id', $actualSiteId)->first();
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'Site not found with ID: ' . $actualSiteId
                ], 404);
            }

            \Log::info('Found site', ['site' => $site]);

            // Prepare the settings JSON
            $currentSettings = $site->settings ? json_decode($site->settings, true) : [];
            $currentSettings['menuItems'] = $menuItems;
            $newSettingsJson = json_encode($currentSettings);

            \Log::info('Updating site settings', [
                'siteId' => $actualSiteId,
                'newSettings' => $newSettingsJson
            ]);

            // Update the site's menu configuration in database
            $updated = \DB::table('sites')->where('id', $actualSiteId)->update([
                'settings' => $newSettingsJson,
                'updated_at' => now()
            ]);

            \Log::info('Update result', ['updated' => $updated]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Menu configuration saved successfully!',
                    'siteId' => $actualSiteId,
                    'menuItemsCount' => count($menuItems)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update database'
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Menu save error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save menu configuration: ' . $e->getMessage()
            ], 500);
        }
    })->name('menu.save');






// Add these routes to web.php after the menu routes

Route::middleware(SubdomainMiddleware::class)
    ->get('/hero-carousel', function (Illuminate\Http\Request $request) {
        $siteData = $request->get('siteData');
        $heroSlides = [];

        // Load existing hero slides from site data
        if (isset($siteData['settings']['heroSlides'])) {
            $heroSlides = $siteData['settings']['heroSlides'];
        }

        // Get site ID from various possible sources
        $siteId = $request->get('siteId') ??
                  ($siteData['id'] ?? null) ??
                  ($siteData['site_id'] ?? null) ??
                  1; // Fallback to 1 for development

        return Inertia::render('HeroCarousel/Index', [
            'siteData' => $siteData,
            'heroSlides' => $heroSlides,
            'siteId' => $siteId,
        ]);
    })->name('hero-carousel');

Route::middleware(SubdomainMiddleware::class)
    ->post('/hero-carousel/save', function (Illuminate\Http\Request $request) {
        \Log::info('Hero carousel save request received', [
            'input' => $request->all(),
            'siteData' => $request->get('siteData')
        ]);

        // Get site data from middleware
        $siteData = $request->get('siteData');
        $heroSlides = $request->input('heroSlides');
        $requestSiteId = $request->input('siteId');

        // Determine the actual site ID
        $actualSiteId = null;
        if ($siteData && isset($siteData->id)) {
            $actualSiteId = $siteData->id;
        } elseif ($requestSiteId) {
            $actualSiteId = $requestSiteId;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No site ID found. Site data: ' . json_encode($siteData)
            ], 422);
        }

        \Log::info('Using site ID: ' . $actualSiteId);

        // Validate the request
        if (!$heroSlides || !is_array($heroSlides)) {
            return response()->json([
                'success' => false,
                'message' => 'Hero slides are required and must be an array'
            ], 422);
        }

        // Validate JSON structure
        try {
            $jsonString = json_encode($heroSlides);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid JSON format: ' . json_last_error_msg()
                ], 422);
            }

            // Validate slide structure
            foreach ($heroSlides as $index => $slide) {
                if (!isset($slide['title']) || !isset($slide['subtitle']) || !isset($slide['ctaText']) || !isset($slide['ctaLink'])) {
                    return response()->json([
                        'success' => false,
                        'message' => "Hero slide at index {$index} is missing required fields (title, subtitle, ctaText, ctaLink)"
                    ], 422);
                }
            }

            // Check if site exists
            $site = \DB::table('sites')->where('id', $actualSiteId)->first();
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'Site not found with ID: ' . $actualSiteId
                ], 404);
            }

            \Log::info('Found site', ['site' => $site]);

            // Prepare the settings JSON
            $currentSettings = $site->settings ? json_decode($site->settings, true) : [];
            $currentSettings['heroSlides'] = $heroSlides;
            $newSettingsJson = json_encode($currentSettings);

            \Log::info('Updating site settings', [
                'siteId' => $actualSiteId,
                'newSettings' => $newSettingsJson
            ]);

            // Update the site's hero carousel configuration in database
            $updated = \DB::table('sites')->where('id', $actualSiteId)->update([
                'settings' => $newSettingsJson,
                'updated_at' => now()
            ]);

            \Log::info('Update result', ['updated' => $updated]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Hero carousel configuration saved successfully!',
                    'siteId' => $actualSiteId,
                    'slidesCount' => count($heroSlides)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update database'
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Hero carousel save error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save hero carousel configuration: ' . $e->getMessage()
            ], 500);
        }
    })->name('hero-carousel.save');
// End of hero carousel routes


//MessageForm routes
Route::middleware(SubdomainMiddleware::class)
    ->get('/message-from', function (Illuminate\Http\Request $request) {
        $siteData = $request->get('siteData');
        $messageFromItems = [];

        // Load existing messages from site data
        if (isset($siteData['settings']['messageFromItems'])) {
            $messageFromItems = $siteData['settings']['messageFromItems'];
        }

        // Get site ID from various possible sources
        $siteId = $request->get('siteId') ??
                  ($siteData['id'] ?? null) ??
                  ($siteData['site_id'] ?? null) ??
                  1; // Fallback to 1 for development

        return Inertia::render('MessageFrom/Index', [
            'siteData' => $siteData,
            'messageFromItems' => $messageFromItems,
            'siteId' => $siteId,
        ]);
    })->name('message-from');

Route::middleware(SubdomainMiddleware::class)
    ->post('/message-from/save', function (Illuminate\Http\Request $request) {
        \Log::info('Message From save request received', [
            'input' => $request->all(),
            'siteData' => $request->get('siteData')
        ]);

        // Get site data from middleware
        $siteData = $request->get('siteData');
        $messageFromItems = $request->input('messageFromItems');
        $requestSiteId = $request->input('siteId');

        // Determine the actual site ID
        $actualSiteId = null;
        if ($siteData && isset($siteData->id)) {
            $actualSiteId = $siteData->id;
        } elseif ($requestSiteId) {
            $actualSiteId = $requestSiteId;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No site ID found. Site data: ' . json_encode($siteData)
            ], 422);
        }

        // Validate the request
        if (!$messageFromItems || !is_array($messageFromItems)) {
            return response()->json([
                'success' => false,
                'message' => 'Message items are required and must be an array'
            ], 422);
        }

        // Validate JSON structure
        try {
            $jsonString = json_encode($messageFromItems);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid JSON format: ' . json_last_error_msg()
                ], 422);
            }

            // Validate message structure
            foreach ($messageFromItems as $index => $item) {
                if (!isset($item['name']) || !isset($item['title']) || !isset($item['message']) || !isset($item['type'])) {
                    return response()->json([
                        'success' => false,
                        'message' => "Message item at index {$index} is missing required fields (name, title, message, type)"
                    ], 422);
                }
            }

            // Check if site exists
            $site = \DB::table('sites')->where('id', $actualSiteId)->first();
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'Site not found with ID: ' . $actualSiteId
                ], 404);
            }

            // Prepare the settings JSON
            $currentSettings = $site->settings ? json_decode($site->settings, true) : [];
            $currentSettings['messageFromItems'] = $messageFromItems;
            $newSettingsJson = json_encode($currentSettings);

            // Update the site's message configuration in database
            $updated = \DB::table('sites')->where('id', $actualSiteId)->update([
                'settings' => $newSettingsJson,
                'updated_at' => now()
            ]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Message From configuration saved successfully!',
                    'siteId' => $actualSiteId,
                    'messagesCount' => count($messageFromItems)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update database'
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Message From save error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save message configuration: ' . $e->getMessage()
            ], 500);
        }
    })->name('message-from.save');




    //headline marquee routes
Route::middleware(SubdomainMiddleware::class)
    ->get('/headline-marquee', function (Illuminate\Http\Request $request) {
        $siteData = $request->get('siteData');
        $headlines = [];

        // Load existing headlines from site data
        if (isset($siteData['settings']['headlines'])) {
            $headlines = $siteData['settings']['headlines'];
        }

        // Get site ID from various possible sources
        $siteId = $request->get('siteId') ??
                  ($siteData['id'] ?? null) ??
                  ($siteData['site_id'] ?? null) ??
                  1; // Fallback to 1 for development

        return Inertia::render('HeadlineMarquee/Index', [
            'siteData' => $siteData,
            'headlines' => $headlines,
            'siteId' => $siteId,
        ]);
    })->name('headline-marquee');

Route::middleware(SubdomainMiddleware::class)
    ->post('/headline-marquee/save', function (Illuminate\Http\Request $request) {
        \Log::info('Headline marquee save request received', [
            'input' => $request->all(),
            'siteData' => $request->get('siteData')
        ]);

        // Get site data from middleware
        $siteData = $request->get('siteData');
        $headlines = $request->input('headlines');
        $requestSiteId = $request->input('siteId');

        // Determine the actual site ID
        $actualSiteId = null;
        if ($siteData && isset($siteData->id)) {
            $actualSiteId = $siteData->id;
        } elseif ($requestSiteId) {
            $actualSiteId = $requestSiteId;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No site ID found. Site data: ' . json_encode($siteData)
            ], 422);
        }

        // Validate the request
        if (!$headlines || !is_array($headlines)) {
            return response()->json([
                'success' => false,
                'message' => 'Headlines are required and must be an array'
            ], 422);
        }

        // Validate JSON structure
        try {
            $jsonString = json_encode($headlines);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid JSON format: ' . json_last_error_msg()
                ], 422);
            }

            // Validate headline structure
            foreach ($headlines as $index => $headline) {
                if (!isset($headline['text']) || !isset($headline['type']) || !isset($headline['priority'])) {
                    return response()->json([
                        'success' => false,
                        'message' => "Headline at index {$index} is missing required fields (text, type, priority)"
                    ], 422);
                }
            }

            // Check if site exists
            $site = \DB::table('sites')->where('id', $actualSiteId)->first();
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'Site not found with ID: ' . $actualSiteId
                ], 404);
            }

            // Prepare the settings JSON
            $currentSettings = $site->settings ? json_decode($site->settings, true) : [];
            $currentSettings['headlines'] = $headlines;
            $newSettingsJson = json_encode($currentSettings);

            // Update the site's headline configuration in database
            $updated = \DB::table('sites')->where('id', $actualSiteId)->update([
                'settings' => $newSettingsJson,
                'updated_at' => now()
            ]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Headline marquee configuration saved successfully!',
                    'siteId' => $actualSiteId,
                    'headlinesCount' => count($headlines)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update database'
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Headline marquee save error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save headline configuration: ' . $e->getMessage()
            ], 500);
        }
    })->name('headline-marquee.save');






//FacultiesCarousel routes
Route::middleware(SubdomainMiddleware::class)
    ->get('/faculties', function (Illuminate\Http\Request $request) {
        $siteData = $request->get('siteData');
        $facultyItems = [];

        // Load existing faculties from site data
        if (isset($siteData['settings']['facultyItems'])) {
            $facultyItems = $siteData['settings']['facultyItems'];
        }

        // Get site ID from various possible sources
        $siteId = $request->get('siteId') ??
                  ($siteData['id'] ?? null) ??
                  ($siteData['site_id'] ?? null) ??
                  1; // Fallback to 1 for development

        return Inertia::render('Faculties/Index', [
            'siteData' => $siteData,
            'facultyItems' => $facultyItems,
            'siteId' => $siteId,
        ]);
    })->name('faculties');

Route::middleware(SubdomainMiddleware::class)
    ->post('/faculties/save', function (Illuminate\Http\Request $request) {
        \Log::info('Faculties save request received', [
            'input' => $request->all(),
            'siteData' => $request->get('siteData')
        ]);

        // Get site data from middleware
        $siteData = $request->get('siteData');
        $facultyItems = $request->input('facultyItems');
        $requestSiteId = $request->input('siteId');

        // Determine the actual site ID
        $actualSiteId = null;
        if ($siteData && isset($siteData->id)) {
            $actualSiteId = $siteData->id;
        } elseif ($requestSiteId) {
            $actualSiteId = $requestSiteId;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No site ID found. Site data: ' . json_encode($siteData)
            ], 422);
        }

        // Validate the request
        if (!$facultyItems || !is_array($facultyItems)) {
            return response()->json([
                'success' => false,
                'message' => 'Faculty items are required and must be an array'
            ], 422);
        }

        // Validate JSON structure
        try {
            $jsonString = json_encode($facultyItems);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid JSON format: ' . json_last_error_msg()
                ], 422);
            }

            // Validate faculty structure
            foreach ($facultyItems as $index => $item) {
                if (!isset($item['name']) || !isset($item['shortName']) || !isset($item['description'])) {
                    return response()->json([
                        'success' => false,
                        'message' => "Faculty item at index {$index} is missing required fields (name, shortName, description)"
                    ], 422);
                }
            }

            // Check if site exists
            $site = \DB::table('sites')->where('id', $actualSiteId)->first();
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'Site not found with ID: ' . $actualSiteId
                ], 404);
            }

            // Prepare the settings JSON
            $currentSettings = $site->settings ? json_decode($site->settings, true) : [];
            $currentSettings['facultyItems'] = $facultyItems;
            $newSettingsJson = json_encode($currentSettings);

            // Update the site's faculty configuration in database
            $updated = \DB::table('sites')->where('id', $actualSiteId)->update([
                'settings' => $newSettingsJson,
                'updated_at' => now()
            ]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Faculties configuration saved successfully!',
                    'siteId' => $actualSiteId,
                    'facultiesCount' => count($facultyItems)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update database'
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Faculties save error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save faculties configuration: ' . $e->getMessage()
            ], 500);
        }
    })->name('faculties.save');





//Welcome section routes
Route::middleware(SubdomainMiddleware::class)
    ->get('/welcome-section', function (Illuminate\Http\Request $request) {
        $siteData = $request->get('siteData');
        $welcomeItems = [];

        // Load existing welcome items from site data
        if (isset($siteData['settings']['welcomeItems'])) {
            $welcomeItems = $siteData['settings']['welcomeItems'];
        }

        // Get site ID from various possible sources
        $siteId = $request->get('siteId') ??
                  ($siteData['id'] ?? null) ??
                  ($siteData['site_id'] ?? null) ??
                  1; // Fallback to 1 for development

        return Inertia::render('Welcome/Index', [
            'siteData' => $siteData,
            'welcomeItems' => $welcomeItems,
            'siteId' => $siteId,
        ]);
    })->name('welcome-section');

// ✅ SIMPLIFIED: Welcome Section Save Route
Route::middleware(SubdomainMiddleware::class)
    ->post('/welcome-section/save', function (Illuminate\Http\Request $request) {
        \Log::info('Welcome save request received', [
            'input' => $request->all(),
            'siteData' => $request->get('siteData')
        ]);

        // Get site data from middleware
        $siteData = $request->get('siteData');
        $welcomeItems = $request->input('welcomeItems');
        $requestSiteId = $request->input('siteId');

        // Determine the actual site ID
        $actualSiteId = null;
        if ($siteData && isset($siteData->id)) {
            $actualSiteId = $siteData->id;
        } elseif ($requestSiteId) {
            $actualSiteId = $requestSiteId;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No site ID found. Site data: ' . json_encode($siteData)
            ], 422);
        }

        // Validate the request
        if (!$welcomeItems || !is_array($welcomeItems)) {
            return response()->json([
                'success' => false,
                'message' => 'Welcome items are required and must be an array'
            ], 422);
        }

        // ✅ SIMPLIFIED: Validate only essential fields
        try {
            foreach ($welcomeItems as $index => $item) {
                if (!isset($item['title']) || !isset($item['buttonText'])) {
                    return response()->json([
                        'success' => false,
                        'message' => "Welcome item at index {$index} is missing required fields (title, buttonText)"
                    ], 422);
                }
            }

            // Check if site exists
            $site = \DB::table('sites')->where('id', $actualSiteId)->first();
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'Site not found with ID: ' . $actualSiteId
                ], 404);
            }

            // Prepare the settings JSON
            $currentSettings = $site->settings ? json_decode($site->settings, true) : [];
            $currentSettings['welcomeItems'] = $welcomeItems;
            $newSettingsJson = json_encode($currentSettings);

            // Update the site's welcome configuration in database
            $updated = \DB::table('sites')->where('id', $actualSiteId)->update([
                'settings' => $newSettingsJson,
                'updated_at' => now()
            ]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Welcome section configuration saved successfully!',
                    'siteId' => $actualSiteId,
                    'welcomeItemsCount' => count($welcomeItems)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update database'
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Welcome save error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save welcome configuration: ' . $e->getMessage()
            ], 500);
        }
    })->name('welcome-section.save');






// Campus Life Section Routes
Route::middleware(SubdomainMiddleware::class)
    ->get('/campus-life-section', function (Illuminate\Http\Request $request) {
        $siteData = $request->get('siteData');
        $campusLifeItems = $siteData['settings']['campusLifeItems'] ?? [];
        $siteId = $siteData['id'] ?? null;

        return Inertia::render('CampusLife/Index', [
            'campusLifeItems' => $campusLifeItems,
            'siteId' => $siteId,
        ]);
    })->name('campus-life.index');

Route::middleware(SubdomainMiddleware::class)
    ->post('/campus-life/save', function (Illuminate\Http\Request $request) {
        \Log::info('Campus Life save request received', ['input' => $request->all()]);

        $requestSiteId = $request->input('siteId');
        $campusLifeItems = $request->input('campusLifeItems');

        if (!$requestSiteId) {
            return response()->json(['success' => false, 'message' => 'Site ID is required.'], 422);
        }

        try {
            $site = \DB::table('sites')->where('id', $requestSiteId)->first();
            if (!$site) {
                return response()->json(['success' => false, 'message' => 'Site not found.'], 404);
            }

            $currentSettings = json_decode($site->settings, true) ?: [];
            $currentSettings['campusLifeItems'] = $campusLifeItems;
            $newSettingsJson = json_encode($currentSettings);

            \DB::table('sites')->where('id', $requestSiteId)->update([
                'settings' => $newSettingsJson,
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Campus Life settings saved successfully!',
            ]);

        } catch (\Exception $e) {
            \Log::error('Campus Life save error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to save settings.'], 500);
        }
    })->name('campus-life.save');




Route::middleware(SubdomainMiddleware::class)
    ->get('/campus-glance', function (\Illuminate\Http\Request $request) { // ✅ Corrected this line
        $siteData = $request->get('siteData');
        $glanceItems = $siteData['settings']['glanceItems'] ?? [];
        $siteId = $siteData['id'] ?? null;

        return Inertia::render('CampusGlance/Index', [
            'glanceItems' => $glanceItems,
            'siteId' => $siteId,
        ]);
    })->name('campus-glance.index');


Route::middleware(SubdomainMiddleware::class)
    ->post('/campus-glance/save', function (Illuminate\Http\Request $request) {
        \Log::info('Campus Glance save request received', ['input' => $request->all()]);

        $requestSiteId = $request->input('siteId');
        $glanceItems = $request->input('glanceItems');

        if (!$requestSiteId) {
            return response()->json(['success' => false, 'message' => 'Site ID is required.'], 422);
        }

        try {
            $site = \DB::table('sites')->where('id', $requestSiteId)->first();
            if (!$site) {
                return response()->json(['success' => false, 'message' => 'Site not found.'], 404);
            }

            $currentSettings = json_decode($site->settings, true) ?: [];
            $currentSettings['glanceItems'] = $glanceItems;
            $newSettingsJson = json_encode($currentSettings);

            \DB::table('sites')->where('id', $requestSiteId)->update([
                'settings' => $newSettingsJson,
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Campus Glance settings have been saved successfully.',
            ]);

        } catch (\Exception $e) {
            \Log::error('Campus Glance save error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to save settings.'], 500);
        }
    })->name('campus-glance.save');

Route::middleware(SubdomainMiddleware::class)
    ->get('/news-section', function (\Illuminate\Http\Request $request) {
        $siteData = $request->get('siteData');
        $newsItems = $siteData['settings']['newsItems'] ?? [];
        $siteId = $siteData['id'] ?? null;

        return Inertia::render('News/Index', [
            'newsItems' => $newsItems,
            'siteId' => $siteId,
        ]);
    })->name('news-section.index');

// POST route to save the changes
Route::middleware(SubdomainMiddleware::class)
    // ✅ CORRECTED: Use the full namespace for the Request object
    ->post('/news-section/save', function (Illuminate\Http\Request $request) {
        \Log::info('News section save request received', [
            'input' => $request->all(),
            'siteData' => $request->get('siteData')
        ]);

        $siteData = $request->get('siteData');
        $newsItems = $request->input('newsItems');
        $requestSiteId = $request->input('siteId');

        $actualSiteId = null;
        if ($siteData && isset($siteData->id)) {
            $actualSiteId = $siteData->id;
        } elseif ($requestSiteId) {
            $actualSiteId = $requestSiteId;
        } else {
            return response()->json(['success' => false, 'message' => 'No site ID found'], 422);
        }

        if (!$newsItems || !is_array($newsItems)) {
            return response()->json(['success' => false, 'message' => 'News items are required'], 422);
        }

        try {
            $site = \DB::table('sites')->where('id', $actualSiteId)->first();
            if (!$site) {
                return response()->json(['success' => false, 'message' => 'Site not found'], 404);
            }

            $currentSettings = json_decode($site->settings, true) ?: [];
            $currentSettings['newsItems'] = $newsItems;
            $newSettingsJson = json_encode($currentSettings);

            \DB::table('sites')->where('id', $actualSiteId)->update([
                'settings' => $newSettingsJson,
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'News section configuration saved successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('News section save error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to save news configuration'], 500);
        }
    })->name('news-section.save');

Route::middleware(SubdomainMiddleware::class)
    ->get('/news/{slug}', function (Illuminate\Http\Request $request, $slug) {
        $siteData = $request->get('siteData');

        $allNewsItems = $siteData->settings['newsItems'] ?? [];
        $newsCollection = collect($allNewsItems);

        // Find the current article
        $newsArticle = $newsCollection->first(function ($item) use ($slug) {
            return basename($item['link']) === $slug;
        });

        if (!$newsArticle) {
            abort(404, 'News article not found.');
        }

        // ✅ NEW: Get the 5 most recent articles for the sidebar, excluding the current one
        $latestNews = $newsCollection
            ->where('link', '!=', $newsArticle['link']) // Exclude the current article
            ->sortByDesc('date') // Sort by most recent date
            ->take(5); // Take the top 5

        return Inertia::render('News/Show', [
            'newsArticle' => $newsArticle,
            'latestNews' => $latestNews->values()->all(), // Pass latest news to the view
            'menuItems' => $siteData->settings['menuItems'] ?? [],
            'siteData' => $siteData,
        ]);

    })->name('news.show');


Route::middleware(SubdomainMiddleware::class)
    ->get('/news', function (Illuminate\Http\Request $request) {
        $siteData = $request->get('siteData');

        // Get all news items and sort them by date
        $allNewsItems = collect($siteData->settings['newsItems'] ?? [])->sortByDesc('date');

        // Manually paginate the collection
        $perPage = 10;
        $currentPage = Paginator::resolveCurrentPage('page');
        $currentPageItems = $allNewsItems->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedNews = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $allNewsItems->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );

        return Inertia::render('News/IndexAll', [
            'news' => $paginatedNews, // Pass the paginated data
            'menuItems' => $siteData->settings['menuItems'] ?? [],
        ]);

    })->name('news.index');



Route::get('/dashboard', function () {
    return Inertia::render('Dashboard/Index');
})->name('dashboard');

Route::get('/form-elements', function () {
    return Inertia::render('Forms/FormElementsView');
})->name('form-elements');

Route::get('/form-layout', function () {
    return Inertia::render('Forms/FormLayoutView');
})->name('form-layout');

Route::get('/tables', function () {
    return Inertia::render('Tables/TablesView');
})->name('tables');

Route::get('/test-db', function () {
    try {
        // Test database connection
        $sites = \DB::table('sites')->get();
        return response()->json([
            'success' => true,
            'message' => 'Database connection successful',
            'sites_count' => $sites->count(),
            'sites' => $sites->take(3) // Show first 3 sites
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
});

Route::get('/signin', function () {
    return Inertia::render('Authentication/SigninView');
})->name('signin');

Route::get('/signup', function () {
    return Inertia::render('Authentication/SignupView');
})->name('signup');

Route::get('/alerts', function () {
    return Inertia::render('UiElements/AlertsView');
})->name('alerts');

Route::get('/buttons', function () {
    return Inertia::render('UiElements/ButtonsView');
})->name('buttons');

Route::get('/charts', function () {
    return Inertia::render('Charts/BasicChartView');
})->name('charts');

Route::get('/calendar', function () {
    return Inertia::render('Calendar/CalendarView');
})->name('calendar');

Route::get('/profile', function () {
    return Inertia::render('Profile/ProfileView');
})->name('profile');

Route::get('/settings', function () {
    return Inertia::render('Settings/SettingsView');
})->name('settings');

Route::get('/test', function () {
    return '<html><body style="padding:40px;font-family:Arial;"><h1 style="color:green;">✅ Docker Production Test</h1><p>If you can see this, your Docker environment is working perfectly!</p><p><a href="/">← Back to main site</a></p></body></html>';
})->name('test');
