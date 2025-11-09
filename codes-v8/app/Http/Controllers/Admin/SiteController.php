<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class SiteController extends Controller
{
    /**
     * Display a listing of the sites.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');
            $status = $request->get('status');
            
            $query = DB::table('sites')
                ->select(
                    'id', 
                    'name', 
                    'description', 
                    'domain', 
                    'subdomain', 
                    'theme_id', 
                    'theme_name', 
                    'is_active as status',
                    'settings',
                    'created_at', 
                    'updated_at'
                );
            
            // Apply search filter
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('domain', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }
            
            // Apply status filter
            if ($status !== null && $status !== '') {
                $isActive = $status === 'active' ? 1 : 0;
                $query->where('is_active', $isActive);
            }
            
            $sites = $query->orderBy('created_at', 'desc')
                ->paginate($perPage);
            
            // Transform data for frontend
            $sites->getCollection()->transform(function ($site) {
                // Parse settings to get menuItems
                $settings = $site->settings ? json_decode($site->settings, true) : [];
                $menuItems = $settings['menuItems'] ?? [];
                
                return [
                    'id' => $site->id,
                    'name' => $site->name,
                    'domain' => $site->domain,
                    'description' => $site->description,
                    'theme_name' => $site->theme_name,
                    'status' => $site->status ? 'active' : 'inactive',
                    'menuItems' => $menuItems,
                    'created_at' => $site->created_at,
                    'updated_at' => $site->updated_at,
                ];
            });
                
            return Inertia::render('Site/Index', [
                'sites' => $sites,
                'filters' => [
                    'search' => $search,
                    'status' => $status,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching sites: ' . $e->getMessage());
            
            // Show detailed error in development, generic message in production
            $errorMessage = config('app.debug') 
                ? 'Failed to load sites: ' . $e->getMessage()
                : 'Failed to load sites. Please try again later.';
            
            // For index method, render empty state with error instead of redirect
            return Inertia::render('Site/Index', [
                'sites' => (object) [
                    'data' => [],
                    'links' => [],
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 15,
                    'total' => 0,
                    'from' => 0,
                    'to' => 0,
                ],
                'filters' => [
                    'search' => $request->get('search'),
                    'status' => $request->get('status'),
                ],
                'error' => $errorMessage
            ]);
        }
    }

    /**
     * Show the form for creating a new site.
     */
    public function create(): Response|RedirectResponse
    {
        try {
            $themes = [
                ['id' => 1, 'name' => 'default', 'description' => 'The default theme'],
                ['id' => 2, 'name' => 'light-green', 'description' => 'A light green theme'],
                ['id' => 3, 'name' => 'rose', 'description' => 'A beautiful rose theme'],
                ['id' => 4, 'name' => 'ocean', 'description' => 'A calming ocean theme'],
                ['id' => 5, 'name' => 'orange', 'description' => 'A vibrant orange theme']
            ];

            return Inertia::render('Site/Create', [
                'themes' => $themes
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading create form: ' . $e->getMessage());
            
            $errorMessage = config('app.debug') 
                ? 'Failed to load create form: ' . $e->getMessage()
                : 'Failed to load create form';
                
            return redirect()->route('admin.sites.index')
                ->with('error', $errorMessage);
        }
    }

    /**
     * Store a newly created site in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'domain' => 'required|string|max:255|unique:sites,domain',
                'subdomain' => 'nullable|string|max:255',
                'theme_id' => 'nullable|integer|exists:themes,id',
                'theme_name' => 'nullable|string|max:100',
                'status' => 'required|in:active,inactive,maintenance',
                'menuItems' => 'nullable|string'
            ]);

            // Process menuItems JSON
            $settings = [];
            if ($validated['menuItems']) {
                $menuItems = json_decode($validated['menuItems'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw ValidationException::withMessages([
                        'menuItems' => ['Invalid JSON format']
                    ]);
                }
                $settings['menuItems'] = $menuItems;
            }

            $siteData = [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'domain' => $validated['domain'],
                'subdomain' => $validated['subdomain'],
                'theme_id' => $validated['theme_id'] ?? null,
                'theme_name' => $validated['theme_name'] ?? 'default',
                'is_active' => $validated['status'] === 'active',
                'settings' => json_encode($settings),
                'created_by' => Auth::id() ?? 1,
                'updated_by' => Auth::id() ?? 1,
                'created_at' => now(),
                'updated_at' => now()
            ];

            $siteId = DB::table('sites')->insertGetId($siteData);
            
            return redirect()->route('admin.sites.show', $siteId)
                ->with('success', 'Site created successfully');
                
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating site: ' . $e->getMessage());
            
            $errorMessage = config('app.debug') 
                ? 'Failed to create site: ' . $e->getMessage()
                : 'Failed to create site';
                
            return back()->with('error', $errorMessage)->withInput();
        }
    }

    /**
     * Display the specified site.
     */
    public function show($id): Response|RedirectResponse
    {
        try {
            $site = DB::table('sites')->where('id', $id)->first();
            
            if (!$site) {
                return redirect()->route('admin.sites.index')
                    ->with('error', 'Site not found');
            }

            // Parse settings and menuItems
            $settings = $site->settings ? json_decode($site->settings, true) : [];
            $menuItems = $settings['menuItems'] ?? [];

            // Transform site data
            $siteData = [
                'id' => $site->id,
                'name' => $site->name,
                'domain' => $site->domain,
                'description' => $site->description,
                'theme_name' => $site->theme_name,
                'status' => $site->is_active ? 'active' : 'inactive',
                'menuItems' => $menuItems,
                'created_at' => $site->created_at,
                'updated_at' => $site->updated_at,
            ];

            return Inertia::render('Site/Show', [
                'site' => $siteData
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching site: ' . $e->getMessage());
            
            $errorMessage = config('app.debug') 
                ? 'Failed to load site: ' . $e->getMessage()
                : 'Failed to load site';
                
            return redirect()->route('admin.sites.index')
                ->with('error', $errorMessage);
        }
    }

    /**
     * Show the form for editing the specified site.
     */
    public function edit($id): Response|RedirectResponse
    {
        try {
            $site = DB::table('sites')->where('id', $id)->first();
            
            if (!$site) {
                return redirect()->route('admin.sites.index')
                    ->with('error', 'Site not found');
            }

            // Parse settings and menuItems for editing
            $settings = $site->settings ? json_decode($site->settings, true) : [];
            $menuItems = $settings['menuItems'] ?? [];

            $themes = [
                ['id' => 1, 'name' => 'default', 'description' => 'The default theme'],
                ['id' => 2, 'name' => 'light-green', 'description' => 'A light green theme'],
                ['id' => 3, 'name' => 'rose', 'description' => 'A beautiful rose theme'],
                ['id' => 4, 'name' => 'ocean', 'description' => 'A calming ocean theme'],
                ['id' => 5, 'name' => 'orange', 'description' => 'A vibrant orange theme']
            ];

            // Transform site data
            $siteData = [
                'id' => $site->id,
                'name' => $site->name,
                'domain' => $site->domain,
                'description' => $site->description,
                'theme_name' => $site->theme_name,
                'status' => $site->is_active ? 'active' : 'inactive',
                'menuItems' => json_encode($menuItems, JSON_PRETTY_PRINT),
                'created_at' => $site->created_at,
                'updated_at' => $site->updated_at,
            ];

            return Inertia::render('Site/Edit', [
                'site' => $siteData,
                'themes' => $themes
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching site for edit: ' . $e->getMessage());
            
            $errorMessage = config('app.debug') 
                ? 'Failed to load site for editing: ' . $e->getMessage()
                : 'Failed to load site for editing';
                
            return redirect()->route('admin.sites.index')
                ->with('error', $errorMessage);
        }
    }

    /**
     * Update the specified site in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'domain' => 'required|string|max:255|unique:sites,domain,' . $id,
                'subdomain' => 'nullable|string|max:255',
                'theme_id' => 'nullable|integer|exists:themes,id',
                'theme_name' => 'nullable|string|max:100',
                'status' => 'required|in:active,inactive,maintenance',
                'menuItems' => 'nullable|string'
            ]);

            // Check if site exists
            $existingSite = DB::table('sites')->where('id', $id)->first();
            if (!$existingSite) {
                return redirect()->route('admin.sites.index')
                    ->with('error', 'Site not found');
            }

            // Process settings and menuItems
            $currentSettings = $existingSite->settings ? json_decode($existingSite->settings, true) : [];
            
            if ($validated['menuItems']) {
                $menuItems = json_decode($validated['menuItems'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw ValidationException::withMessages([
                        'menuItems' => ['Invalid JSON format']
                    ]);
                }
                $currentSettings['menuItems'] = $menuItems;
            }

            $updateData = [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'domain' => $validated['domain'],
                'subdomain' => $validated['subdomain'],
                'theme_id' => $validated['theme_id'] ?? null,
                'theme_name' => $validated['theme_name'] ?? 'default',
                'is_active' => $validated['status'] === 'active',
                'settings' => json_encode($currentSettings),
                'updated_by' => Auth::id() ?? 1,
                'updated_at' => now()
            ];

            DB::table('sites')->where('id', $id)->update($updateData);
            
            return redirect()->route('admin.sites.show', $id)
                ->with('success', 'Site updated successfully');
                
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating site: ' . $e->getMessage());
            
            $errorMessage = config('app.debug') 
                ? 'Failed to update site: ' . $e->getMessage()
                : 'Failed to update site';
                
            return back()->with('error', $errorMessage)->withInput();
        }
    }

    /**
     * Remove the specified site from storage.
     */
    public function destroy($id)
    {
        try {
            $site = DB::table('sites')->where('id', $id)->first();
            
            if (!$site) {
                return redirect()->route('admin.sites.index')
                    ->with('error', 'Site not found');
            }

            DB::table('sites')->where('id', $id)->delete();
            
            return redirect()->route('admin.sites.index')
                ->with('success', 'Site deleted successfully');
                
        } catch (\Exception $e) {
            Log::error('Error deleting site: ' . $e->getMessage());
            
            $errorMessage = config('app.debug') 
                ? 'Failed to delete site: ' . $e->getMessage()
                : 'Failed to delete site';
                
            return redirect()->route('admin.sites.index')
                ->with('error', $errorMessage);
        }
    }

    /**
     * API Methods
     */

    /**
     * Get all sites with pagination (API endpoint).
     */
    public function apiIndex(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');
            $status = $request->get('status');
            
            $query = DB::table('sites')
                ->select('id', 'name', 'description', 'domain', 'subdomain', 'theme_id', 'theme_name', 'is_active', 'created_at', 'updated_at');
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('domain', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            if ($status !== null && $status !== '') {
                $isActive = $status === 'active' ? 1 : 0;
                $query->where('is_active', $isActive);
            }
            
            $sites = $query->orderBy('created_at', 'desc')
                ->paginate($perPage);
                
            return response()->json($sites);
        } catch (\Exception $e) {
            Log::error('Error fetching sites via API: ' . $e->getMessage());
            
            $errorMessage = config('app.debug') 
                ? 'Failed to fetch sites: ' . $e->getMessage()
                : 'Failed to fetch sites';
                
            return response()->json(['error' => $errorMessage], 500);
        }
    }

    /**
     * Get single site with full details (API endpoint).
     */
    public function apiShow($id)
    {
        try {
            $site = DB::table('sites')->where('id', $id)->first();
                
            if (!$site) {
                return response()->json(['error' => 'Site not found'], 404);
            }
            
            // Parse settings and menuItems
            $settings = $site->settings ? json_decode($site->settings, true) : [];
            $menuItems = $settings['menuItems'] ?? [];
            
            $response = [
                'id' => $site->id,
                'name' => $site->name,
                'description' => $site->description,
                'domain' => $site->domain,
                'subdomain' => $site->subdomain,
                'theme_id' => $site->theme_id,
                'theme_name' => $site->theme_name,
                'is_active' => $site->is_active,
                'settings' => $settings,
                'menuItems' => $menuItems,
                'created_at' => $site->created_at,
                'updated_at' => $site->updated_at
            ];
            
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error fetching site via API: ' . $e->getMessage());
            
            $errorMessage = config('app.debug') 
                ? 'Failed to fetch site: ' . $e->getMessage()
                : 'Failed to fetch site';
                
            return response()->json(['error' => $errorMessage], 500);
        }
    }

    /**
     * Create new site (API endpoint).
     */
    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'domain' => 'required|string|max:255|unique:sites,domain',
                'subdomain' => 'nullable|string|max:255',
                'theme_id' => 'nullable|integer|exists:themes,id',
                'theme_name' => 'nullable|string|max:100',
                'is_active' => 'boolean',
                'settings' => 'nullable|array',
                'menuItems' => 'nullable|array'
            ]);

            // Process settings and menuItems
            $settings = $validated['settings'] ?? [];
            
            if (isset($validated['menuItems'])) {
                $settings['menuItems'] = $validated['menuItems'];
            }

            $siteData = [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'domain' => $validated['domain'],
                'subdomain' => $validated['subdomain'],
                'theme_id' => $validated['theme_id'],
                'theme_name' => $validated['theme_name'] ?? 'default',
                'is_active' => $validated['is_active'] ?? true,
                'settings' => json_encode($settings),
                'created_by' => Auth::id() ?? 1,
                'updated_by' => Auth::id() ?? 1,
                'created_at' => now(),
                'updated_at' => now()
            ];

            $siteId = DB::table('sites')->insertGetId($siteData);
            
            // Return the created site
            $createdSite = DB::table('sites')->where('id', $siteId)->first();
            
            return response()->json([
                'message' => 'Site created successfully',
                'site' => $createdSite
            ], 201);
                
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error creating site via API: ' . $e->getMessage());
            
            $errorMessage = config('app.debug') 
                ? 'Failed to create site: ' . $e->getMessage()
                : 'Failed to create site';
                
            return response()->json(['error' => $errorMessage], 500);
        }
    }

    /**
     * Update site (API endpoint).
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'domain' => 'required|string|max:255|unique:sites,domain,' . $id,
                'subdomain' => 'nullable|string|max:255',
                'theme_id' => 'nullable|integer|exists:themes,id',
                'theme_name' => 'nullable|string|max:100',
                'is_active' => 'boolean',
                'settings' => 'nullable|array',
                'menuItems' => 'nullable|array'
            ]);

            // Check if site exists
            $existingSite = DB::table('sites')->where('id', $id)->first();
            if (!$existingSite) {
                return response()->json(['error' => 'Site not found'], 404);
            }

            // Process settings and menuItems
            $currentSettings = $existingSite->settings ? json_decode($existingSite->settings, true) : [];
            
            if (isset($validated['settings'])) {
                $currentSettings = array_merge($currentSettings, $validated['settings']);
            }
            
            if (isset($validated['menuItems'])) {
                $currentSettings['menuItems'] = $validated['menuItems'];
            }

            $updateData = [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'domain' => $validated['domain'],
                'subdomain' => $validated['subdomain'],
                'theme_id' => $validated['theme_id'] ?? null,
                'theme_name' => $validated['theme_name'],
                'is_active' => $validated['is_active'] ?? true,
                'settings' => json_encode($currentSettings),
                'updated_by' => Auth::id() ?? 1,
                'updated_at' => now()
            ];

            DB::table('sites')->where('id', $id)->update($updateData);
            
            // Return updated site
            $updatedSite = DB::table('sites')->where('id', $id)->first();
            
            return response()->json([
                'message' => 'Site updated successfully',
                'site' => $updatedSite
            ]);
                
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error updating site via API: ' . $e->getMessage());
            
            $errorMessage = config('app.debug') 
                ? 'Failed to update site: ' . $e->getMessage()
                : 'Failed to update site';
                
            return response()->json(['error' => $errorMessage], 500);
        }
    }

    /**
     * Delete site (API endpoint).
     */
    public function apiDestroy($id)
    {
        try {
            $site = DB::table('sites')->where('id', $id)->first();
            
            if (!$site) {
                return response()->json(['error' => 'Site not found'], 404);
            }

            DB::table('sites')->where('id', $id)->delete();
            
            return response()->json(['message' => 'Site deleted successfully']);
                
        } catch (\Exception $e) {
            Log::error('Error deleting site via API: ' . $e->getMessage());
            
            $errorMessage = config('app.debug') 
                ? 'Failed to delete site: ' . $e->getMessage()
                : 'Failed to delete site';
                
            return response()->json(['error' => $errorMessage], 500);
        }
    }

    /**
     * Get themes for dropdown (API endpoint).
     */
    public function getThemes()
    {
        try {
            $themes = DB::table('themes')
                ->select('id', 'name', 'description')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
                
            return response()->json($themes);
        } catch (\Exception $e) {
            Log::error('Error fetching themes: ' . $e->getMessage());
            
            $errorMessage = config('app.debug') 
                ? 'Failed to fetch themes: ' . $e->getMessage()
                : 'Failed to fetch themes';
                
            return response()->json(['error' => $errorMessage], 500);
        }
    }

    /**
     * Validate domain uniqueness (API endpoint).
     */
    public function validateDomain(Request $request)
    {
        try {
            $domain = $request->input('domain');
            $excludeId = $request->input('exclude_id');
            
            $query = DB::table('sites')->where('domain', $domain);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            
            $exists = $query->exists();
            
            return response()->json([
                'available' => !$exists,
                'message' => $exists ? 'Domain already exists' : 'Domain is available'
            ]);
        } catch (\Exception $e) {
            Log::error('Error validating domain: ' . $e->getMessage());
            
            $errorMessage = config('app.debug') 
                ? 'Domain validation failed: ' . $e->getMessage()
                : 'Validation failed';
                
            return response()->json(['error' => $errorMessage], 500);
        }
    }

    /**
     * Toggle site status (API endpoint).
     */
    public function toggleStatus($id)
    {
        try {
            $site = DB::table('sites')->where('id', $id)->first();
            
            if (!$site) {
                return response()->json(['error' => 'Site not found'], 404);
            }

            $newStatus = !$site->is_active;
            
            DB::table('sites')->where('id', $id)->update([
                'is_active' => $newStatus,
                'updated_by' => Auth::id() ?? 1,
                'updated_at' => now()
            ]);
            
            return response()->json([
                'message' => 'Site status updated successfully',
                'is_active' => $newStatus
            ]);
                
        } catch (\Exception $e) {
            Log::error('Error toggling site status: ' . $e->getMessage());
            
            $errorMessage = config('app.debug') 
                ? 'Failed to update site status: ' . $e->getMessage()
                : 'Failed to update site status';
                
            return response()->json(['error' => $errorMessage], 500);
        }
    }

    /**
     * Validate menuItems JSON structure (API endpoint).
     */
    public function validateMenuItems(Request $request)
    {
        try {
            $menuItemsJson = $request->input('menuItems');
            
            if (!$menuItemsJson) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Menu items JSON is required'
                ]);
            }
            
            // Try to decode JSON
            $menuItems = json_decode($menuItemsJson, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Invalid JSON format: ' . json_last_error_msg()
                ]);
            }
            
            // Validate structure based on SiteSeeder format
            if (!is_array($menuItems)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Menu items must be an array'
                ]);
            }
            
            foreach ($menuItems as $index => $item) {
                if (!isset($item['title']) || !isset($item['col'])) {
                    return response()->json([
                        'valid' => false,
                        'message' => "Menu item at index {$index} is missing required fields (title, col)"
                    ]);
                }
                
                if (!isset($item['subItems']) || !is_array($item['subItems'])) {
                    return response()->json([
                        'valid' => false,
                        'message' => "Menu item at index {$index} must have subItems array"
                    ]);
                }
                
                // Validate subItems structure
                foreach ($item['subItems'] as $subIndex => $subItem) {
                    if (!isset($subItem['title']) || !isset($subItem['description']) || !isset($subItem['href'])) {
                        return response()->json([
                            'valid' => false,
                            'message' => "Sub-item at index {$index}.{$subIndex} is missing required fields (title, description, href)"
                        ]);
                    }
                }
            }
            
            return response()->json([
                'valid' => true,
                'message' => 'Menu items JSON is valid',
                'itemCount' => count($menuItems),
                'totalSubItems' => array_sum(array_map(function($item) {
                    return count($item['subItems'] ?? []);
                }, $menuItems))
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error validating menu items: ' . $e->getMessage());
            
            $errorMessage = config('app.debug') 
                ? 'Validation failed: ' . $e->getMessage()
                : 'Menu validation failed';
                
            return response()->json([
                'valid' => false,
                'message' => $errorMessage
            ]);
        }
    }
}