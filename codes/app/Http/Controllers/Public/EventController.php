<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Inertia\Inertia;

class EventController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $allEvents = collect($siteData['settings']['eventItems'] ?? [])->sortByDesc('date');

        $paginatedEvents = new LengthAwarePaginator(
            $allEvents->forPage(Paginator::resolveCurrentPage(), 9),
            $allEvents->count(),
            9,
            Paginator::resolveCurrentPage(),
            ['path' => Paginator::resolveCurrentPath()]
        );

        return Inertia::render('Event/IndexAll', [
            'events' => $paginatedEvents,
            'menuItems' => $siteData['settings']['menuItems'] ?? []
        ]);
    }

    public function show(Request $request, $slug)
    {
        $siteData = $this->getSiteData($request);
        $eventCollection = collect($siteData['settings']['eventItems'] ?? []);

        $event = $eventCollection->firstWhere('link', 'events/' . $slug);

        if (!$event) {
            abort(404);
        }

        $latestEvents = $eventCollection->where('link', '!=', $event['link'])
                                       ->sortByDesc('date')
                                       ->take(5);

        return Inertia::render('Event/Show', [
            'event' => $event,
            'latestEvents' => $latestEvents->values()->all(),
            'menuItems' => $siteData['settings']['menuItems'] ?? [],
            'siteData' => $siteData
        ]);
    }
}
