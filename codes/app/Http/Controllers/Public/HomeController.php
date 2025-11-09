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
        // Determine site id and load model/settings
        $siteId = $this->getSiteId($request);
        $site = \App\Models\Site::findOrFail($siteId);
        $siteSettings = $site->settings ?? [];

        $componentService = new ComponentService();

        // Prefer component service for components, fallback to site settings
        $menuItems = $siteSettings['menuItems'] ?? [];
        $heroSlides = $componentService->getComponentDataForFrontend($siteId, 'HeroCarousel') ?? ($siteSettings['heroSlides'] ?? []);
        $headlines = $componentService->getComponentDataForFrontend($siteId, 'HeadlineMarquee') ?? ($siteSettings['headlines'] ?? []);
        $messageFromItems = $componentService->getComponentDataForFrontend($siteId, 'MessageFrom') ?? ($siteSettings['messageFromItems'] ?? []);
        $facultyItems = $componentService->getComponentDataForFrontend($siteId, 'FacultiesCarousel') ?? ($siteSettings['facultyItems'] ?? []);
        $welcomeItems = $componentService->getComponentDataForFrontend($siteId, 'WelcomeSection') ?? ($siteSettings['welcomeItems'] ?? []);
        $campusLifeItems = $componentService->getComponentDataForFrontend($siteId, 'CampusLife') ?? ($siteSettings['campusLifeItems'] ?? []);
        $glanceItems = $componentService->getComponentDataForFrontend($siteId, 'AtAGlance') ?? ($siteSettings['glanceItems'] ?? $this->getDefaultGlanceItems());
        $eventItems = $componentService->getComponentDataForFrontend($siteId, 'DynamicEvents') ?? ($siteSettings['eventItems'] ?? []);
        $noticeItems = $componentService->getComponentDataForFrontend($siteId, 'DynamicNotices') ?? ($siteSettings['noticeItems'] ?? []);
        $footerData = $componentService->getComponentDataForFrontend($siteId, 'Footer') ?? ($siteSettings['footerData'] ?? []);
        $address = $siteSettings['address'] ?? "";
        $contactEmail = $siteSettings['contactEmail'] ?? "";
        $siteTitle = $siteSettings['siteTitle'] ?? "";
        $atAGlanceData = $siteSettings['atAGlanceData'] ?? null;
        $latestProjects =  $siteSettings['latest_projects'] ?? null;
        $techNews =  $siteSettings['tech_news'] ?? null;

        // Get news items from database (more authoritative than settings)
        $newsItems = News::forSite($siteId)
            ->active()
            ->published()
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'excerpt' => $item->excerpt,
                    'image' => $item->image,
                    'date' => $item->published_at?->format('Y-m-d') ?? $item->created_at->format('Y-m-d'),
                    'category' => $item->category,
                    'link' => $item->link && !str_starts_with($item->link, '/news/') ? $item->link : '/news/' . $item->slug,
                    'isActive' => $item->is_active,
                    'slug' => $item->slug,
                ];
            })
            ->toArray();

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

        // Optional debug logging (lightweight)
        Log::info('Home page loaded', [
            'siteId' => $siteId,
            'menuItemsCount' => count($menuItems),
            'heroSlidesCount' => count($heroSlides),
            'newsItemsCount' => count($newsItems)
        ]);

        $data = [
            'siteData' => [
                'id' => $site->id ?? null,
                'name' => $site->name ?? '',
                'domain' => $site->domain ?? '',
                'settings' => $siteSettings,
            ],
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
            'address' => $address,
            'contactEmail' => $contactEmail,
            'siteTitle' => $siteTitle,
            'atAGlanceData' => $atAGlanceData,
            'latestProjects' => $latestProjects,
            'techNews' => $techNews,
        ];

        // dd(1234567890, $data);

        $subdomain = $request->input('siteData')['subdomain'];

        if ($subdomain) {
            return Inertia::render('Department', [
                'message' => '',
                'data' => $data
            ]);
        }

        return Inertia::render('University', [
            'message' => 'Welcome to the University',
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
