<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\BaseController;
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

        // Get all news items and sort them by date
        $allNewsItems = collect($siteData['settings']['newsItems'] ?? [])->sortByDesc('date');

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
        $allNewsItems = $siteData['settings']['newsItems'] ?? [];
        $newsCollection = collect($allNewsItems);

        // Find the current article
        $newsArticle = $newsCollection->first(function ($item) use ($slug) {
            return basename($item['link']) === $slug;
        });

        if (!$newsArticle) {
            abort(404, 'News article not found.');
        }

        // Get the 5 most recent articles for the sidebar, excluding the current one
        $latestNews = $newsCollection
            ->where('link', '!=', $newsArticle['link'])
            ->sortByDesc('date')
            ->take(5);

        return Inertia::render('News/Show', [
            'newsArticle' => $newsArticle,
            'latestNews' => $latestNews->values()->all(),
            'menuItems' => $siteData['settings']['menuItems'] ?? [],
            'siteData' => $siteData
        ]);
    }
}
