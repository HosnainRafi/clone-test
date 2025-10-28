<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class HeadlineMarqueeController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        $headlines = $siteData['settings']['headlines'] ?? [];

        return Inertia::render('HeadlineMarquee/Index', [
            'siteData' => $siteData,
            'headlines' => $headlines,
            'siteId' => $siteId
        ]);
    }

    public function save(Request $request)
    {
        $headlines = $request->input('headlines');

        // Validate headline structure
        foreach ($headlines as $index => $headline) {
            if (!isset($headline['text']) || !isset($headline['type']) ||
                !isset($headline['priority'])) {
                return $this->errorResponse(
                    "Headline at index {$index} is missing required fields (text, type, priority)",
                    422
                );
            }
        }

        $validationRules = [
            'headlines' => 'required|array',
            'siteId' => 'required|integer'
        ];

        return $this->handleSave($request, 'headlines', 'headlines', $validationRules);
    }
}
