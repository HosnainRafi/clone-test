<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class HeroCarouselController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        // Load existing hero slides from site data
        $heroSlides = [];
        if (isset($siteData['settings']['heroSlides'])) {
            $heroSlides = $siteData['settings']['heroSlides'];
        }

        Log::info('Hero carousel admin loaded', [
            'siteId' => $siteId,
            'slidesCount' => count($heroSlides)
        ]);

        return Inertia::render('HeroCarousel/Index', [
            'siteData' => $siteData,
            'heroSlides' => $heroSlides,
            'siteId' => $siteId
        ]);
    }

    public function save(Request $request)
    {
        Log::info('Hero carousel save request received', [
            'input' => $request->all(),
            'siteData' => $this->getSiteData($request)
        ]);

        // Validate slide structure
        $heroSlides = $request->input('heroSlides');
        foreach ($heroSlides as $index => $slide) {
            if (!isset($slide['title']) || !isset($slide['subtitle']) ||
                !isset($slide['ctaText']) || !isset($slide['ctaLink'])) {
                return $this->errorResponse(
                    "Hero slide at index {$index} is missing required fields (title, subtitle, ctaText, ctaLink)",
                    422
                );
            }
        }

        $validationRules = [
            'heroSlides' => 'required|array',
            'heroSlides.*.title' => 'required|string',
            'heroSlides.*.subtitle' => 'required|string',
            'heroSlides.*.ctaText' => 'required|string',
            'heroSlides.*.ctaLink' => 'required|string',
            'siteId' => 'required|integer'
        ];

        return $this->handleSave($request, 'heroSlides', 'heroSlides', $validationRules);
    }
}
