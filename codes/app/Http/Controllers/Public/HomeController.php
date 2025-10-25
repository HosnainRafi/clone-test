<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);

        // Load all the site content
        $menuItems = $siteData['settings']['menuItems'] ?? [];
        $heroSlides = $siteData['settings']['heroSlides'] ?? [];
        $headlines = $siteData['settings']['headlines'] ?? [];
        $messageFromItems = $siteData['settings']['messageFromItems'] ?? [];
        $facultyItems = $siteData['settings']['facultyItems'] ?? [];
        $welcomeItems = $siteData['settings']['welcomeItems'] ?? [];
        $campusLifeItems = $siteData['settings']['campusLifeItems'] ?? [];
        $glanceItems = $siteData['settings']['glanceItems'] ?? $this->getDefaultGlanceItems();
        $newsItems = $siteData['settings']['newsItems'] ?? [];
        $eventItems = $siteData['settings']['eventItems'] ?? [];
        $noticeItems = $siteData['settings']['noticeItems'] ?? [];
        $publicationItems = $siteData['settings']['publicationItems'] ?? [];
        $footerData = $siteData['settings']['footerData'] ?? [];

        Log::info('Home page loaded', [
            'menuItemsCount' => count($menuItems),
            'heroSlidesCount' => count($heroSlides),
            'newsItemsCount' => count($newsItems)
        ]);

        $data = [
            'siteData' => $siteData,
            'theme' => $request->get('theme'),
            'components' => collect($request->get('components', [])),
            'viewType' => $request->get('viewType'),
            'fullDomain' => $request->get('fullDomain'),
            'page' => $request->get('page'),
            'menuItems' => $menuItems,
            'heroSlides' => $heroSlides,
            'headlines' => $headlines,
            'messageFromItems' => $messageFromItems,
            'facultyItems' => $facultyItems,
            'welcomeItems' => $welcomeItems,
            'campusLifeItems' => $campusLifeItems,
            'glanceItems' => $glanceItems,
            'newsItems' => $newsItems,
            'eventItems' => $eventItems,
            'noticeItems' => $noticeItems,
            'publicationItems' => $publicationItems,
            'footerData' => $footerData,
        ];

        return Inertia::render('University', [
            'message' => 'Hello World - Changed View!',
            'data' => $data
        ]);
    }

    public function department()
    {
        return Inertia::render('Department');
    }

    private function getDefaultGlanceItems()
    {
        return [
            [
                'id' => 1,
                'label' => 'Active Students',
                'value' => '5000+',
                'iconName' => 'Users',
                'iconColor' => '#3b82f6',
                'isActive' => true,
                'displayOrder' => 1
            ],
            [
                'id' => 2,
                'label' => 'Student Clubs',
                'value' => '25+',
                'iconName' => 'Trophy',
                'iconColor' => '#10b981',
                'isActive' => true,
                'displayOrder' => 2
            ],
            [
                'id' => 3,
                'label' => 'Residential Halls',
                'value' => '6',
                'iconName' => 'Home',
                'iconColor' => '#8b5cf6',
                'isActive' => true,
                'displayOrder' => 3
            ],
            [
                'id' => 4,
                'label' => 'Annual Events',
                'value' => '100+',
                'iconName' => 'Calendar',
                'iconColor' => '#f59e0b',
                'isActive' => true,
                'displayOrder' => 4
            ]
        ];
    }
}
