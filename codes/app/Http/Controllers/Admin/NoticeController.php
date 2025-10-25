<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class NoticeController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        $noticeItems = $siteData['settings']['noticeItems'] ?? [];

        return Inertia::render('Notice/Index', [
            'noticeItems' => $noticeItems,
            'siteId' => $siteId
        ]);
    }

    public function save(Request $request)
    {
        $validationRules = [
            'noticeItems' => 'required|array',
            'siteId' => 'required|integer'
        ];

        return $this->handleSave($request, 'noticeItems', 'noticeItems', $validationRules);
    }
}
