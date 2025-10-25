<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class MenuController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        // Load existing menu items from site data
        $menuItems = [];
        if (isset($siteData['settings']['menuItems'])) {
            $menuItems = $siteData['settings']['menuItems'];
        }

        Log::info('Menu admin loaded', [
            'siteId' => $siteId,
            'menuItemsCount' => count($menuItems)
        ]);

        return Inertia::render('Menu/Index', [
            'siteData' => $siteData,
            'menuItems' => $menuItems,
            'siteId' => $siteId
        ]);
    }

    public function save(Request $request)
    {
        Log::info('Menu save request received', [
            'input' => $request->all(),
            'siteData' => $this->getSiteData($request)
        ]);

        // Validate menu structure
        $validationRules = [
            'menuItems' => 'required|array',
            'menuItems.*.title' => 'required|string',
            'menuItems.*.col' => 'required|integer',
            'siteId' => 'required|integer'
        ];

        // Additional validation for each menu item
        $menuItems = $request->input('menuItems');
        foreach ($menuItems as $index => $item) {
            if (!isset($item['title']) || !isset($item['col'])) {
                return $this->errorResponse(
                    "Menu item at index {$index} is missing required fields (title, col)",
                    422
                );
            }

            // Set empty array if subItems is missing
            if (!isset($item['subItems']) || !is_array($item['subItems'])) {
                $menuItems[$index]['subItems'] = [];
            }
        }

        // Update the request with cleaned menu items
        $request->merge(['menuItems' => $menuItems]);

        return $this->handleSave($request, 'menuItems', 'menuItems', $validationRules);
    }
}
