<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class EventController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        $eventItems = $siteData['settings']['eventItems'] ?? [];

        return Inertia::render('Event/Index', [
            'eventItems' => $eventItems,
            'siteId' => $siteId
        ]);
    }

    public function save(Request $request)
    {
        $validationRules = [
            'eventItems' => 'required|array',
            'siteId' => 'required|integer'
        ];

        return $this->handleSave($request, 'eventItems', 'eventItems', $validationRules);
    }
}
