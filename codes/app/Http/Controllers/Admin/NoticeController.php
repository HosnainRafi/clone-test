<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Notice;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class NoticeController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        // Load notices from DB for this site
        $noticeItems = Notice::forSite($siteId)
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
                    'validUntil' => $item->valid_until?->format('Y-m-d') ?? null,
                    'priority' => $item->priority ?? null,
                    'department' => $item->department ?? null,
                    'attachments' => $item->attachments ?? [],
                    'category' => $item->category ?? null,
                ];
            })
            ->toArray();

        return Inertia::render('Notice/Index', [
            'noticeItems' => $noticeItems,
            'siteId' => $siteId
        ]);
    }

    public function save(Request $request)
    {
        Log::info('Notice section save request received', [
            'input' => $request->all(),
            'siteData' => $this->getSiteData($request)
        ]);

        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;
        $noticeItems = $request->input('noticeItems', []);

        // Decode JSON string from multipart/form-data
        if (is_string($noticeItems)) {
            $decoded = json_decode($noticeItems, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $noticeItems = $decoded;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid notice items data format'
                ], 422);
            }
        }

        if (!is_array($noticeItems)) {
            return response()->json([
                'success' => false,
                'message' => 'Notice items must be an array'
            ], 422);
        }

        try {
            \DB::beginTransaction();

            $incomingIds = [];
            $validationErrors = [];

            // Support uploaded files grouped as noticeFiles[<index>][] if frontend sends them
            $noticeFiles = $request->file('noticeFiles', []);

            foreach ($noticeItems as $idx => $item) {
                if (empty($item['title'])) {
                    continue;
                }

                $publishedAt = isset($item['date']) ? Carbon::parse($item['date'])->startOfDay() : now();
                $validUntil = null;
                if (!empty($item['validUntil'])) {
                    $validUntil = Carbon::parse($item['validUntil'])->startOfDay();
                }

                // Attachments: start with provided string array if any
                $attachments = is_array($item['attachments']) ? $item['attachments'] : [];

                // Process uploaded files for this notice
                if (isset($noticeFiles[$idx]) && is_array($noticeFiles[$idx])) {
                    foreach ($noticeFiles[$idx] as $fileKey => $file) {
                        if (!$file || !$file->isValid()) {
                            $validationErrors[] = "Invalid file upload at index {$idx}";
                            continue;
                        }

                        // Validate file type
                        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
                        if (!in_array($file->getMimeType(), $allowedTypes)) {
                            $validationErrors[] = "File '{$file->getClientOriginalName()}' must be PDF, JPG, or PNG";
                            continue;
                        }

                        // Validate file size (5MB max)
                        if ($file->getSize() > 5 * 1024 * 1024) {
                            $validationErrors[] = "File '{$file->getClientOriginalName()}' exceeds 5MB size limit";
                            continue;
                        }

                        try {
                            $path = $file->store('notices', 'public');
                            $attachments[] = Storage::url($path);
                        } catch (\Exception $e) {
                            Log::error("File upload failed: " . $e->getMessage());
                            $validationErrors[] = "Failed to upload file '{$file->getClientOriginalName()}'";
                        }
                    }
                }

                $itemId = isset($item['id']) ? intval($item['id']) : null;
                $saved = null;

                if ($itemId) {
                    $existing = Notice::forSite($siteId)->where('id', $itemId)->first();
                    if ($existing) {
                        $existing->update([
                            'title' => $item['title'],
                            'excerpt' => $item['excerpt'] ?? '',
                            'content' => $item['content'] ?? '',
                            'image' => $item['image'] ?? '',
                            'link' => $item['link'] ?? '',
                            'is_active' => $item['isActive'] ?? true,
                            'sort_order' => $item['displayOrder'] ?? 0,
                            'published_at' => $publishedAt,
                            'valid_until' => $validUntil,
                            'priority' => $item['priority'] ?? null,
                            'department' => $item['department'] ?? null,
                            'attachments' => $attachments,
                            'category' => $item['category'] ?? null,
                            'updated_by' => auth()->id(),
                        ]);
                        $saved = $existing;
                    }
                }

                if (!$saved) {
                    $saved = Notice::create([
                        'title' => $item['title'],
                        'excerpt' => $item['excerpt'] ?? '',
                        'content' => $item['content'] ?? '',
                        'image' => $item['image'] ?? '',
                        'link' => $item['link'] ?? '',
                        'is_active' => $item['isActive'] ?? true,
                        'sort_order' => $item['displayOrder'] ?? 0,
                        'published_at' => $publishedAt,
                        'valid_until' => $validUntil,
                        'priority' => $item['priority'] ?? null,
                        'department' => $item['department'] ?? null,
                        'attachments' => $attachments,
                        'category' => $item['category'] ?? null,
                        'site_id' => $siteId,
                        'created_by' => auth()->id(),
                    ]);
                }

                $incomingIds[] = $saved->id;
            }

            // If any validation errors occurred, rollback and return them
            if (!empty($validationErrors)) {
                \DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'File validation failed',
                    'errors' => $validationErrors
                ], 422);
            }

            // Remove notices not present in the incoming payload
            if (count($incomingIds) > 0) {
                Notice::forSite($siteId)->whereNotIn('id', $incomingIds)->delete();
            } else {
                Notice::forSite($siteId)->delete();
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Notice section configuration saved successfully!'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Notice section save error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save notice configuration: ' . $e->getMessage()
            ], 500);
        }
    }
}
