<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class NewsController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        $newsItems = $siteData['settings']['newsItems'] ?? [];

        return Inertia::render('News/Index', [
            'newsItems' => $newsItems,
            'siteId' => $siteId
        ]);
    }

    public function save(Request $request)
    {
        Log::info('News section save request received', [
            'input' => $request->all(),
            'siteData' => $this->getSiteData($request)
        ]);

        $validationRules = [
            'newsItems' => 'required|array',
            'siteId' => 'required|integer'
        ];

        return $this->handleSave($request, 'newsItems', 'newsItems', $validationRules);
    }
}
