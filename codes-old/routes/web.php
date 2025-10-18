<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\SubdomainMiddleware;

Route::middleware(SubdomainMiddleware::class)
    ->get('/', function (Illuminate\Http\Request $request) {
        
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