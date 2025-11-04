<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Inertia\Inertia;

class NoticeController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        $allNotices = Notice::forSite($siteId)
            ->orderBy('published_at', 'desc')
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'excerpt' => $item->excerpt,
                    'content' => $item->content,
                    'image' => $item->image,
                    'link' => $item->link,
                    'isActive' => $item->is_active,
                    'displayOrder' => $item->sort_order,
                    'date' => $item->published_at?->format('Y-m-d') ?? $item->created_at->format('Y-m-d'),
                ];
            })
            ->values();

        $paginatedNotices = new LengthAwarePaginator(
            $allNotices->forPage(Paginator::resolveCurrentPage(), 10),
            $allNotices->count(),
            10,
            Paginator::resolveCurrentPage(),
            ['path' => Paginator::resolveCurrentPath()]
        );

        return Inertia::render('Notice/IndexAll', [
            'notices' => $paginatedNotices,
            'menuItems' => $siteData['settings']['menuItems'] ?? [],
            'siteData' => $siteData,
        ]);
    }

    public function show(Request $request, $slug)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        // Try to find a notice by link ending with the slug (preserves existing link-based routing)
        $searchedLink = '%/' . $slug;

        $notice = Notice::forSite($siteId)
            ->where('link', 'like', $searchedLink)
            ->orWhere('link', '=', '/notices/' . $slug)
            ->first();

        if (!$notice) {
            abort(404);
        }

        $latestNotices = Notice::forSite($siteId)
            ->where('id', '!=', $notice->id)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'title' => $item->title,
                'excerpt' => $item->excerpt,
                'link' => $item->link,
                'date' => $item->published_at?->format('Y-m-d') ?? $item->created_at->format('Y-m-d'),
            ])
            ->values()
            ->all();

        return Inertia::render('Notice/Show', [
            'notice' => [
                'id' => $notice->id,
                'title' => $notice->title,
                'content' => $notice->content,
                'excerpt' => $notice->excerpt,
                'image' => $notice->image,
                'link' => $notice->link,
                'date' => $notice->published_at?->format('Y-m-d') ?? $notice->created_at->format('Y-m-d'),
                'priority' => $notice->priority ?? 'medium',
                'department' => $notice->department ?? 'Administration',
                'validUntil' => $notice->valid_until?->format('Y-m-d'),
                'category' => $notice->category ?? 'General',
                'attachments' => $notice->attachments ?? []
            ],
            'latestNotices' => $latestNotices,
            'menuItems' => $siteData['settings']['menuItems'] ?? [],
            'siteData' => $siteData,
        ]);
    }
}
