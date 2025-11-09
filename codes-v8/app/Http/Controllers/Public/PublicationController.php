<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\BaseController;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Inertia\Inertia;

class PublicationController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        $allPublications = Publication::forSite($siteId)
            ->orderBy('year', 'desc')
            ->orderBy('published_at', 'desc')
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'authors' => $item->authors,
                    'abstract' => $item->abstract,
                    'journal' => $item->journal,
                    'conference' => $item->conference,
                    'publisher' => $item->publisher,
                    'year' => $item->year,
                    'type' => $item->publication_type,
                    'doi' => $item->doi,
                    'url' => $item->url,
                    'citation' => $item->citation,
                    'link' => $item->link,
                    'image' => $item->image,
                ];
            })
            ->values();

        $paginatedPublications = new LengthAwarePaginator(
            $allPublications->forPage(Paginator::resolveCurrentPage(), 10),
            $allPublications->count(),
            10,
            Paginator::resolveCurrentPage(),
            ['path' => Paginator::resolveCurrentPath()]
        );

        return Inertia::render('Publication/IndexAll', [
            'publications' => $paginatedPublications,
            'menuItems' => $siteData['settings']['menuItems'] ?? [],
            'siteData' => $siteData,
        ]);
    }

    public function show(Request $request, $slug)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        $searchedLink = '%/' . $slug;

        $publication = Publication::forSite($siteId)
            ->where('link', 'like', $searchedLink)
            ->orWhere('link', '=', '/publications/' . $slug)
            ->first();

        if (!$publication) {
            abort(404);
        }

        $latestPublications = Publication::forSite($siteId)
            ->where('id', '!=', $publication->id)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'title' => $item->title,
                'authors' => $item->authors,
                'journal' => $item->journal,
                'year' => $item->year,
                'link' => $item->link,
            ])
            ->values();

        return Inertia::render('Publication/Show', [
            'publication' => [
                'id' => $publication->id,
                'title' => $publication->title,
                'abstract' => $publication->abstract,
                'content' => $publication->content,
                'authors' => $publication->authors,
                'journal' => $publication->journal,
                'conference' => $publication->conference,
                'type' => $publication->publication_type,
                'doi' => $publication->doi,
                'url' => $publication->url,
                'publisher' => $publication->publisher,
                'year' => $publication->year,
                'volume' => $publication->volume,
                'issue' => $publication->issue,
                'pages' => $publication->pages,
                'citation' => $publication->citation,
                'keywords' => $publication->keywords,
                'attachments' => $publication->attachments,
                'link' => $publication->link,
                'image' => $publication->image,
            ],
            'latestPublications' => $latestPublications,
            'menuItems' => $siteData['settings']['menuItems'] ?? [],
            'siteData' => $siteData,
        ]);
    }
}
