<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\BaseController;
use App\Models\News;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;

class NewsController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        // Get all news items from database and sort them by date
        $allNewsItems = News::forSite($siteId)
            ->active()
            ->published()
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
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
                    'slug' => $item->slug,
                    'isActive' => $item->is_active,
                ];
            });

        // Manually paginate the collection
        $perPage = 10;
        $currentPage = Paginator::resolveCurrentPage('page');
        $currentPageItems = $allNewsItems->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedNews = new LengthAwarePaginator(
            $currentPageItems,
            $allNewsItems->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );

        return Inertia::render('News/IndexAll', [
            'news' => $paginatedNews,
            'menuItems' => $siteData['settings']['menuItems'] ?? []
        ]);
    }

    public function show(Request $request, $slug)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        // Find the current article by slug
        $newsArticle = News::forSite($siteId)
            ->where('slug', $slug)
            ->active()
            ->published()
            ->first();

        if (!$newsArticle) {
            abort(404, 'News article not found.');
        }

        // Increment view count
        $newsArticle->incrementViews();

        // Get the 5 most recent articles for the sidebar, excluding the current one
        $latestNews = News::forSite($siteId)
            ->where('id', '!=', $newsArticle->id)
            ->active()
            ->published()
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('News/Show', [
            'newsArticle' => $newsArticle,
            'latestNews' => $latestNews,
            'menuItems' => $siteData['settings']['menuItems'] ?? [],
            'siteData' => $siteData
        ]);
    }
}
