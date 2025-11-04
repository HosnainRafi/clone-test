<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\BaseController;
use App\Models\Tender; // Use the Tender model
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Inertia\Inertia;

class TenderController extends BaseController
{
    /**
     * Display a paginated list of all active tenders.
     */
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        // Get all active tenders from the database
        $allTenders = Tender::forSite($siteId)
            ->where('is_active', true)
            ->orderBy('published_at', 'desc')
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($item) {
                // Map data to the format expected by the frontend
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'tender_number' => $item->tender_number,
                    'description' => $item->description,
                    'published_at' => $item->published_at?->format('Y-m-d'),
                    'closing_at' => $item->closing_at?->format('Y-m-d'),
                    'attachments' => $item->attachments ?? [],
                // Generate a link to the 'show' route using the tender's slug (guard against missing slug)
                    'link' => !empty($item->slug)
                    ? route('tenders.show', ['slug' => $item->slug])
                    : url('/tenders'),
                ];
            })
            ->values();

        // Manually paginate the collection, just like in NoticeController
        $paginatedTenders = new LengthAwarePaginator(
            $allTenders->forPage(Paginator::resolveCurrentPage(), 10), // Get 10 items for the current page
            $allTenders->count(), // Total number of items
            10, // Items per page
            Paginator::resolveCurrentPage(), // The current page number
            ['path' => Paginator::resolveCurrentPath()] // The base URL for the paginator links
        );

        return Inertia::render('Tender/IndexAll', [
            'tenders' => $paginatedTenders,
            'menuItems' => $siteData['settings']['menuItems'] ?? [],
            'siteData' => $siteData,
        ]);
    }

    /**
     * Display a single tender and its details.
     */
    public function show(Request $request, $slug)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        // Find the tender by its unique slug for the given site
        $tender = Tender::forSite($siteId)
            ->where('slug', $slug)
            ->first();

        // If no tender is found, abort with a 404 error
        if (!$tender) {
            abort(404);
        }

        // Get the 5 most recent tenders to display in the sidebar
        $latestTenders = Tender::forSite($siteId)
            ->where('id', '!=', $tender->id) // Exclude the current tender
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'title' => $item->title,
                'slug' => $item->slug,
                'published_at' => $item->published_at?->format('Y-m-d'),
            ])
            ->values()
            ->all();

        // Render the single tender view, passing the tender and latest tenders as props
        return Inertia::render('Tender/Show', [
            'tender' => [
                'id' => $tender->id,
                'title' => $tender->title,
                'tender_number' => $tender->tender_number,
                'description' => $tender->description,
                'published_at' => $tender->published_at?->format('Y-m-d'),
                'closing_at' => $tender->closing_at?->format('Y-m-d'),
                'attachments' => $tender->attachments ?? [],
            ],
            'latestTenders' => $latestTenders,
            'menuItems' => $siteData['settings']['menuItems'] ?? [],
            'siteData' => $siteData,
        ]);
    }
}
