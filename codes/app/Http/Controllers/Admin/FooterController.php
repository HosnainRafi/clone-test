<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class FooterController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        $footerData = $siteData['settings']['footerData'] ?? [];

        return Inertia::render('Footer/Index', [
            'footerData' => $footerData,
            'siteId' => $siteId
        ]);
    }

    public function save(Request $request)
    {
        $validationRules = [
            'footerData' => 'required|array',
            'footerData.universityName' => 'required|string|max:255',
            'footerData.universityFullName' => 'required|string|max:255',
            'footerData.email' => 'required|email|max:255',
            'siteId' => 'required|integer|exists:sites,id'
        ];

        return $this->handleSave($request, 'footerData', 'footerData', $validationRules);
    }
}
