<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use App\Models\Event;
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
        $siteId = $siteData['id'] ?? 1;

        $allEvents = Event::forSite($siteId)
            ->orderBy('starts_at', 'desc')
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
                    'starts_at' => $item->starts_at?->format('Y-m-d') ?? null,
                    'ends_at' => $item->ends_at?->format('Y-m-d') ?? null,
                ];
            })
            ->values();

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
    $siteId = $siteData['id'] ?? 1;

    $searchedLink = '%/' . $slug;

    $event = Event::forSite($siteId)
        ->where('link', 'like', $searchedLink)
        ->orWhere('link', '=', '/events/' . $slug)
        ->first();

    if (!$event) {
        abort(404);
    }

    $latestEvents = Event::forSite($siteId)
        ->where('id', '!=', $event->id)
        ->orderBy('starts_at', 'desc')
        ->take(5)
        ->get()
        ->map(fn($item) => [
            'id' => $item->id,
            'title' => $item->title,
            'excerpt' => $item->excerpt,
            'link' => $item->link,
            'starts_at' => $item->starts_at?->format('Y-m-d') ?? null,
        ])
        ->values();

    return Inertia::render('Event/Show', [
        'event' => [
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->content,
            'date' => $event->starts_at?->format('Y-m-d') ?? null,
            'time' => $event->starts_at?->format('H:i A') ?? '09:00 AM',
            'endDate' => $event->ends_at?->format('Y-m-d') ?? null,
            'venue' => $event->venue ?? 'MBSTU Campus',
            'category' => $event->category ?? 'University Event',
            'status' => $event->starts_at?->isFuture() ? 'upcoming' : 'completed',
            'registration' => $event->registration ?? 'Not Required',
            'fee' => $event->fee ?? 'Free',
            'organizer' => $event->organizer ?? 'MBSTU',
            'participants' => $event->participants ?? 'Open to All',
            'link' => $event->link,
            'image' => $event->image,
            'excerpt' => $event->excerpt,
        ],
        'latestEvents' => $latestEvents,
        'menuItems' => $siteData['settings']['menuItems'] ?? [],
        'siteData' => $siteData,
    ]);
}

}
