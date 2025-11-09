<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Tender; // 1. Use the new Tender model
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TenderController extends BaseController
{
    /**
     * Display a listing of the tenders.
     */
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        // 2. Load tenders from the DB for this site
        $tenderItems = Tender::forSite($siteId)
            ->orderBy('published_at', 'desc')
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($item) {
                // 3. Map Tender model properties to the format the frontend expects
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'tender_number' => $item->tender_number,
                    'description' => $item->description,
                    'published_at' => $item->published_at?->format('Y-m-d'),
                    'closing_at' => $item->closing_at?->format('Y-m-d'),
                    'attachments' => $item->attachments ?? [],
                    'isActive' => $item->is_active,
                    'displayOrder' => $item->sort_order,
                ];
            })
            ->toArray();

        // 4. Render the Tender/Index.vue component
        return Inertia::render('Tender/Index', [
            'tenderItems' => $tenderItems,
            'siteId' => $siteId
        ]);
    }

    /**
     * Save the tender data from the admin panel.
     */
    public function save(Request $request)
    {
        Log::info('Tender section save request received', [
            'input' => $request->all(),
        ]);

        $siteId = $request->input('siteId', 1);
        $tenderItems = $request->input('tenderItems', []);

        // Decode JSON string if data is sent via multipart/form-data
        if (is_string($tenderItems)) {
            $decoded = json_decode($tenderItems, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $tenderItems = $decoded;
            } else {
                return response()->json(['success' => false, 'message' => 'Invalid tender items data format'], 422);
            }
        }

        if (!is_array($tenderItems)) {
            return response()->json(['success' => false, 'message' => 'Tender items must be an array'], 422);
        }

        try {
            \DB::beginTransaction();

            $incomingIds = [];
            $validationErrors = [];

            // 5. Look for files grouped as tenderFiles[<index>][]
            $tenderFiles = $request->file('tenderFiles', []);

            foreach ($tenderItems as $idx => $item) {
                if (empty($item['title'])) {
                    continue;
                }

                $publishedAt = isset($item['published_at']) ? Carbon::parse($item['published_at'])->startOfDay() : now();
                $closingAt = isset($item['closing_at']) ? Carbon::parse($item['closing_at'])->startOfDay() : null;

                $attachments = is_array($item['attachments']) ? $item['attachments'] : [];

                // Process uploaded files for this tender
                if (isset($tenderFiles[$idx]) && is_array($tenderFiles[$idx])) {
                    foreach ($tenderFiles[$idx] as $file) {
                        if (!$file || !$file->isValid()) {
                            $validationErrors[] = "Invalid file upload at index {$idx}";
                            continue;
                        }

                        // You can customize validation as needed
                        if ($file->getSize() > 10 * 1024 * 1024) { // 10MB max
                            $validationErrors[] = "File '{$file->getClientOriginalName()}' exceeds 10MB size limit";
                            continue;
                        }

                        try {
                            // 6. Store files in a dedicated 'tenders' folder
                            $path = $file->store('tenders', 'public');
                            $attachments[] = Storage::url($path);
                        } catch (\Exception $e) {
                            Log::error("File upload failed: " . $e->getMessage());
                            $validationErrors[] = "Failed to upload file '{$file->getClientOriginalName()}'";
                        }
                    }
                }

                // Prepare data for saving
                $dataToSave = [
                    'title' => $item['title'],
                    'tender_number' => $item['tender_number'] ?? null,
                    'description' => $item['description'] ?? null,
                    'published_at' => $publishedAt,
                    'closing_at' => $closingAt,
                    'attachments' => $attachments,
                    'is_active' => $item['isActive'] ?? true,
                    'sort_order' => $item['displayOrder'] ?? 0,
                    'site_id' => $siteId,
                ];

                // Use updateOrCreate for clean create/update logic
                $tender = Tender::updateOrCreate(
                    [
                        'id' => $item['id'] ?? null, // Match by ID if it exists
                    ],
                    $dataToSave + ['updated_by' => auth()->id()] // Add user ID on update
                );

                // Set created_by only for new records
                if ($tender->wasRecentlyCreated) {
                    $tender->created_by = auth()->id();
                    $tender->save();
                }

                $incomingIds[] = $tender->id;
            }

            if (!empty($validationErrors)) {
                \DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'File validation failed',
                    'errors' => $validationErrors
                ], 422);
            }

            // 7. Remove tenders that were not in the submitted payload
            Tender::forSite($siteId)->whereNotIn('id', $incomingIds)->delete();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tender section saved successfully!'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Tender section save error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save tender configuration: ' . $e->getMessage()
            ], 500);
        }
    }
}
