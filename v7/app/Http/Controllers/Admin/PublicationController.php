<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class PublicationController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        $publicationItems = $siteData['settings']['publicationItems'] ?? [];

        return Inertia::render('TopPublication/Index', [
            'publicationItems' => $publicationItems,
            'siteId' => $siteId
        ]);
    }

    public function save(Request $request)
    {
        $validationRules = [
            'publicationItems' => 'required|array',
            'siteId' => 'required|integer'
        ];

        return $this->handleSave($request, 'publicationItems', 'publicationItems', $validationRules);
    }
}
