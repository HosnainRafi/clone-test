<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Event;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class EventController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        // Load events from DB for this site
        $eventItems = Event::forSite($siteId)
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
                        // Return date-only fields to the frontend to match the admin payload shape
                        'date' => $item->starts_at?->format('Y-m-d') ?? null,
                        'endDate' => $item->ends_at?->format('Y-m-d') ?? null,
                    // Additional fields
                    'venue' => $item->venue,
                    'category' => $item->category,
                    'registration' => $item->registration,
                    'fee' => $item->fee,
                    'organizer' => $item->organizer,
                    'participants' => $item->participants,
                ];
            })
            ->toArray();

        return Inertia::render('Event/Index', [
            'eventItems' => $eventItems,
            'siteId' => $siteId
        ]);
    }

    /**
     * Try to parse a variety of incoming date/time formats into a Carbon instance.
     * Returns null if parsing fails or value is empty.
     */
    protected function parseDateValue($value)
    {
        if (empty($value)) {
            return null;
        }

        // Trim and normalize
        $value = trim((string) $value);

        // Common formats we expect from the frontend
        $formats = [
            'Y-m-d H:i:s',
            'Y-m-d H:i',
            'Y-m-d h:i A',
            'Y-m-d',
            'd-m-Y',
            'm/d/Y',
            'Y/m/d',
        ];

        foreach ($formats as $format) {
            try {
                $dt = \Carbon\Carbon::createFromFormat($format, $value);
                if ($dt) {
                    return $dt;
                }
            } catch (\Exception $e) {
                // ignore and try next
            }
        }

        // Fallback to Carbon::parse which can handle many human formats
        try {
            return \Carbon\Carbon::parse($value);
        } catch (\Exception $e) {
            Log::warning('Unable to parse date value for event: ' . $value);
            return null;
        }
    }

    public function save(Request $request)
    {
        Log::info('Event section save request received', [
            'input' => $request->all(),
            'siteData' => $this->getSiteData($request)
        ]);

        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;
        $eventItems = $request->input('eventItems', []);

        try {
            \DB::beginTransaction();

            $incomingDbIds = [];

            foreach ($eventItems as $item) {
                if (empty($item['title'])) {
                    continue;
                }

                // Normalize possible date fields coming from frontend using a robust parser
                $startsAt = null;
                $endsAt = null;

                if (!empty($item['starts_at'])) {
                    $startsAt = $this->parseDateValue($item['starts_at']);
                } elseif (!empty($item['date'])) {
                    // Frontend sends 'date' (and optionally 'time') but the requirements
                    // state we should store only the date part (no time). Parse and
                    // then normalize to startOfDay so DB stores a date-only value
                    $dateTime = $item['date'] . (!empty($item['time']) ? ' ' . $item['time'] : '');
                    $startsAt = $this->parseDateValue($dateTime);
                }

                if (!empty($item['ends_at'])) {
                    $endsAt = $this->parseDateValue($item['ends_at']);
                } elseif (!empty($item['endDate'])) {
                    $endsAt = $this->parseDateValue($item['endDate']);
                }

                // Normalize to date-only (no time). Use startOfDay to ensure DB stores
                // a consistent midnight timestamp but frontend will receive Y-m-d only.
                if ($startsAt instanceof \Carbon\Carbon) {
                    $startsAt = $startsAt->startOfDay();
                }

                if ($endsAt instanceof \Carbon\Carbon) {
                    $endsAt = $endsAt->startOfDay();
                }

                // If item has an ID and belongs to this site, update it. Otherwise create new.
                $itemId = isset($item['id']) ? intval($item['id']) : null;
                $saved = null;

                if ($itemId) {
                    $existing = Event::forSite($siteId)->where('id', $itemId)->first();
                    if ($existing) {
                        $existing->update([
                            'title' => $item['title'],
                            'excerpt' => $item['excerpt'] ?? $item['description'] ?? '',
                            'content' => $item['content'] ?? '',
                            'image' => $item['image'] ?? '',
                            'link' => $item['link'] ?? '',
                            'is_active' => $item['isActive'] ?? true,
                            'sort_order' => $item['displayOrder'] ?? $item['display_order'] ?? 0,
                            'starts_at' => $startsAt,
                            'ends_at' => $endsAt,
                            'venue' => $item['venue'] ?? null,
                            'category' => $item['category'] ?? null,
                            'registration' => $item['registration'] ?? null,
                            'fee' => $item['fee'] ?? null,
                            'organizer' => $item['organizer'] ?? null,
                            'participants' => $item['participants'] ?? null,
                            'updated_by' => auth()->id(),
                        ]);
                        $saved = $existing;
                    }
                }

                if (!$saved) {
                    $saved = Event::create([
                        'title' => $item['title'],
                        'excerpt' => $item['excerpt'] ?? $item['description'] ?? '',
                        'content' => $item['content'] ?? '',
                        'image' => $item['image'] ?? '',
                        'link' => $item['link'] ?? '',
                        'is_active' => $item['isActive'] ?? true,
                        'sort_order' => $item['displayOrder'] ?? $item['display_order'] ?? 0,
                        'starts_at' => $startsAt,
                        'ends_at' => $endsAt,
                        'venue' => $item['venue'] ?? null,
                        'category' => $item['category'] ?? null,
                        'registration' => $item['registration'] ?? null,
                        'fee' => $item['fee'] ?? null,
                        'organizer' => $item['organizer'] ?? null,
                        'participants' => $item['participants'] ?? null,
                        'site_id' => $siteId,
                        'created_by' => auth()->id(),
                    ]);
                }

                $incomingDbIds[] = $saved->id;
            }

            // Remove any events that were not included in the incoming payload
            if (count($incomingDbIds) > 0) {
                Event::forSite($siteId)->whereNotIn('id', $incomingDbIds)->delete();
            } else {
                // If no incoming items, remove all events for site
                Event::forSite($siteId)->delete();
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Event section configuration saved successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Event section save error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save event configuration: ' . $e->getMessage()
            ], 500);
        }
    }
}
