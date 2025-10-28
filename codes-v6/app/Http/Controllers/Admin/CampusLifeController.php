<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class CampusLifeController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        $campusLifeItems = $siteData['settings']['campusLifeItems'] ?? [];

        return Inertia::render('CampusLife/Index', [
            'campusLifeItems' => $campusLifeItems,
            'siteId' => $siteId
        ]);
    }

    public function save(Request $request)
    {
        $validationRules = [
            'campusLifeItems' => 'required|array',
            'siteId' => 'required|integer'
        ];

        return $this->handleSave($request, 'campusLifeItems', 'campusLifeItems', $validationRules);
    }

    public function glance(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        $glanceItems = $siteData['settings']['glanceItems'] ?? [];

        return Inertia::render('CampusGlance/Index', [
            'glanceItems' => $glanceItems,
            'siteId' => $siteId
        ]);
    }

    public function saveGlance(Request $request)
    {
        $validationRules = [
            'glanceItems' => 'required|array',
            'siteId' => 'required|integer'
        ];

        return $this->handleSave($request, 'glanceItems', 'glanceItems', $validationRules);
    }
}
