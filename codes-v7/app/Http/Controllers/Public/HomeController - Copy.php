<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\BaseController;
use App\Models\News;
use App\Models\Publication;
use App\Services\ComponentService;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
        //console.log('HomeController index method called',$request->all());
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;
        $componentService = new ComponentService();

        // Load all the site content using ComponentService
        $menuItems = $siteData['settings']['menuItems'] ?? [];
        $heroSlides = $componentService->getComponentDataForFrontend($siteId, 'HeroCarousel') ?? [];
        $headlines = $componentService->getComponentDataForFrontend($siteId, 'HeadlineMarquee') ?? [];
        $messageFromItems = $componentService->getComponentDataForFrontend($siteId, 'MessageFrom') ?? [];
        $facultyItems = $componentService->getComponentDataForFrontend($siteId, 'FacultiesCarousel') ?? [];
        $welcomeItems = $componentService->getComponentDataForFrontend($siteId, 'WelcomeSection') ?? [];
        $campusLifeItems = $componentService->getComponentDataForFrontend($siteId, 'CampusLife') ?? [];
        $glanceItems = $componentService->getComponentDataForFrontend($siteId, 'AtAGlance') ?? $this->getDefaultGlanceItems();

        // Get news items from database instead of settings
        $siteId = $siteData['id'] ?? 1;
        $newsItems = News::forSite($siteId)
            ->active()
            ->published()
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10) // Limit to 10 items for homepage
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'excerpt' => $item->excerpt,
                    'image' => $item->image,
                    'date' => $item->published_at?->format('Y-m-d') ?? $item->created_at->format('Y-m-d'),
                    'category' => $item->category,
                    'link' => $item->link && !str_starts_with($item->link, '/news/') ? $item->link : '/news/' . $item->slug, // Use external link if provided, otherwise generate internal link
                    'isActive' => $item->is_active,
                    'slug' => $item->slug,
                ];
            })
            ->toArray();

        $eventItems = $componentService->getComponentDataForFrontend($siteId, 'DynamicEvents') ?? [];
        $noticeItems = $componentService->getComponentDataForFrontend($siteId, 'DynamicNotices') ?? [];
        // Get publications from database
        $publicationItems = Publication::forSite($siteId)
            ->where('is_active', true)
            ->orderBy('year', 'desc')
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                // authors stored as CSV in DB — convert to array
                $authors = [];
                if (!empty($item->authors)) {
                    if (is_array($item->authors)) {
                        $authors = $item->authors;
                    } else {
                        $authors = array_map('trim', explode(',', $item->authors));
                    }
                }

                $attachments = $item->attachments ?? [];
                $meta = is_array($attachments) ? ($attachments['meta'] ?? []) : (is_object($attachments) ? ($attachments->meta ?? []) : []);

                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'abstract' => $item->abstract ?? '',
                    'authors' => $authors,
                    'correspondingAuthor' => $item->publisher ?? '',
                    'journal' => $item->journal ?? '',
                    'journalRank' => $item->citation ?? 'Q4',
                    'impactFactor' => $meta['impactFactor'] ?? null,
                    'publishDate' => $item->published_at?->format('Y-m-d') ?? null,
                    'volume' => $item->volume,
                    'issue' => $item->issue,
                    'pages' => $item->pages,
                    'doi' => $item->doi,
                    'category' => $item->publication_type ?? 'article',
                    'keywords' => $item->keywords ?? [],
                    'citations' => $meta['citations'] ?? 0,
                    'downloads' => $meta['downloads'] ?? 0,
                    'openAccess' => $meta['openAccess'] ?? false,
                    'featured' => $meta['featured'] ?? false,
                    'fallbackGradient' => $meta['fallbackGradient'] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'pdfUrl' => $attachments['pdfUrl'] ?? null,
                    'journalUrl' => $attachments['journalUrl'] ?? ($item->url ?? null),
                    'link' => $item->link ?? null,
                    'image' => $item->image ?? null,
                ];
            })
            ->toArray();
        $footerData = $componentService->getComponentDataForFrontend($siteId, 'Footer') ?? [];

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
