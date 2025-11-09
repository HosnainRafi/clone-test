<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class WelcomeSectionController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        $welcomeItems = $siteData['settings']['welcomeItems'] ?? [];

        return Inertia::render('Welcome/Index', [
            'siteData' => $siteData,
            'welcomeItems' => $welcomeItems,
            'siteId' => $siteId
        ]);
    }

    public function save(Request $request)
    {
        $welcomeItems = $request->input('welcomeItems');

        // Simplified validation - only essential fields
        foreach ($welcomeItems as $index => $item) {
            if (!isset($item['title']) || !isset($item['buttonText'])) {
                return $this->errorResponse(
                    "Welcome item at index {$index} is missing required fields (title, buttonText)",
                    422
                );
            }
        }

        $validationRules = [
            'welcomeItems' => 'required|array',
            'siteId' => 'required|integer'
        ];

        return $this->handleSave($request, 'welcomeItems', 'welcomeItems', $validationRules);
    }
}
