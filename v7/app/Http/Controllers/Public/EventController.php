<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

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
            'menuItems' => $siteData['settings']['menuItems'] ?? [],
            'siteData' => $siteData,
        ]);
    }

   // app/Http/Controllers/Public/EventController.php

public function show(Request $request, $slug)
{
    $siteData = $this->getSiteData($request);
    $allEvents = $siteData['settings']['eventItems'] ?? [];
    $searchedForLink = '/events/' . $slug;

    $event = null;

    foreach ($allEvents as $item) {
        if (isset($item['link']) && trim($item['link']) === $searchedForLink) {
            $event = $item;
            break;
        }
    }

    if (!$event) {
        abort(404);
    }

    $allEventsCollection = collect($allEvents);

    $latestEvents = $allEventsCollection
        ->where('link', '!=', $event['link']) // Exclude the current event
        ->sortByDesc('date')
        ->take(5)
        ->values();

    return Inertia::render('Event/Show', [
        'event' => $event,
        'latestEvents' => $latestEvents,
        'menuItems' => $siteData['settings']['menuItems'] ?? [],
        'siteData' => $siteData, // <-- ADD THIS LINE
    ]);
}

}
