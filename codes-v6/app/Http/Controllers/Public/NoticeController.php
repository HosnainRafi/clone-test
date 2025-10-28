<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Inertia\Inertia;

class NoticeController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $allNotices = collect($siteData['settings']['noticeItems'] ?? [])->sortByDesc('date');

        $paginatedNotices = new LengthAwarePaginator(
            $allNotices->forPage(Paginator::resolveCurrentPage(), 10),
            $allNotices->count(),
            10,
            Paginator::resolveCurrentPage(),
            ['path' => Paginator::resolveCurrentPath()]
        );

        return Inertia::render('Notice/IndexAll', [
            'notices' => $paginatedNotices,
            'menuItems' => $siteData['settings']['menuItems'] ?? []
        ]);
    }

    public function show(Request $request, $slug)
    {
        $siteData = $this->getSiteData($request);
        $noticeCollection = collect($siteData['settings']['noticeItems'] ?? []);

        $notice = $noticeCollection->firstWhere('link', 'notices/' . $slug);

        if (!$notice) {
            abort(404);
        }

        $latestNotices = $noticeCollection->where('link', '!=', $notice['link'])
                                         ->sortByDesc('date')
                                         ->take(5);

        return Inertia::render('Notice/Show', [
            'notice' => $notice,
            'latestNotices' => $latestNotices->values()->all(),
            'menuItems' => $siteData['settings']['menuItems'] ?? []
        ]);
    }
}
