<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TeacherController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;
        $siteType = $request->get('siteType', 'university');

        // Build the query based on site type
        $query = Teacher::query();

        if ($siteType === 'university') {
            // University admin sees ALL teachers
            $query->orderBy('site_id', 'asc');
        } elseif ($siteType === 'department') {
            // Department admin sees only teachers from their department/site
            $query->forSite($siteId);
        } else {
            // Faculty shouldn't access this route, but if they do, show nothing
            $query->where('id', -1); // No results
        }

        $teachers = $query
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'site_id' => $t->site_id,
                    'slug' => $t->slug,
                    'name' => $t->name,
                    'designation' => $t->designation,
                    'department' => $t->department,
                    'profile_image' => $t->profile_image,
                    'email' => $t->email,
                    'phone_number' => $t->phone_number,
                    'office_location' => $t->office_location,
                    'website_url' => $t->website_url,
                    'about_me' => $t->about_me,
                    'research_interests' => $t->research_interests ?? [],
                    'education' => $t->education ?? [],
                    'experience' => $t->experience ?? [],
                    'publications' => $t->publications ?? [],
                    'projects' => $t->projects ?? [],
                    'courses_taught' => $t->courses_taught ?? [],
                    'awards' => $t->awards ?? [],
                    'social_links' => $t->social_links ?? [],
                    'is_active' => (bool) $t->is_active,
                    'sort_order' => (int) $t->sort_order,
                ];
            })
            ->toArray();

        // Get department sites for dropdown
        $departments = \App\Models\Site::where('site_type', 'department')
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($site) {
                return [
                    'id' => $site->id,
                    'name' => $site->name,
                    'subdomain' => $site->subdomain,
                ];
            })
            ->toArray();

        return Inertia::render('Teacher/Index', [
            'teachers' => $teachers,
            'siteId' => $siteId,
            'siteType' => $siteType,
            'departments' => $departments,
        ]);
    }

    public function save(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $currentSiteId = $siteData['id'] ?? 1;
        $siteType = $request->get('siteType', 'university');

        $payload = $request->input('teachers', []);
        if (is_string($payload)) {
            $decoded = json_decode($payload, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $payload = $decoded;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid teachers data format',
                ], 422);
            }
        }

        if (!is_array($payload)) {
            return response()->json([
                'success' => false,
                'message' => 'Teachers must be an array',
            ], 422);
        }

        try {
            \DB::beginTransaction();

            $incomingIds = [];

            foreach ($payload as $item) {
                if (empty($item['name'])) {
                    // skip items without a name
                    continue;
                }

                $id = isset($item['id']) ? (int) $item['id'] : null;

                // Use the site_id from the item (selected department) or current site
                $teacherSiteId = isset($item['site_id']) ? (int) $item['site_id'] : $currentSiteId;

                $data = [
                    'site_id' => $teacherSiteId,
                    'name' => $item['name'],
                    'designation' => $item['designation'] ?? null,
                    'department' => $item['department'] ?? null,
                    'profile_image' => $item['profile_image'] ?? null,
                    'email' => $item['email'] ?? null,
                    'phone_number' => $item['phone_number'] ?? null,
                    'office_location' => $item['office_location'] ?? null,
                    'website_url' => $item['website_url'] ?? null,
                    'about_me' => $item['about_me'] ?? null,
                    'research_interests' => $item['research_interests'] ?? [],
                    'education' => $item['education'] ?? [],
                    'experience' => $item['experience'] ?? [],
                    'publications' => $item['publications'] ?? [],
                    'projects' => $item['projects'] ?? [],
                    'courses_taught' => $item['courses_taught'] ?? [],
                    'awards' => $item['awards'] ?? [],
                    'social_links' => $item['social_links'] ?? [],
                    'is_active' => isset($item['is_active']) ? (bool) $item['is_active'] : (isset($item['isActive']) ? (bool) $item['isActive'] : true),
                    'sort_order' => isset($item['sort_order']) ? (int) $item['sort_order'] : (isset($item['displayOrder']) ? (int) $item['displayOrder'] : 0),
                    'updated_by' => auth()->id(),
                ];

                if ($id) {
                    // For updates, find the teacher by ID (no site restriction for university admin)
                    $query = Teacher::where('id', $id);

                    // Department admin can only update their own department's teachers
                    if ($siteType === 'department') {
                        $query->where('site_id', $currentSiteId);
                    }

                    $existing = $query->first();
                    if ($existing) {
                        $existing->update($data);
                        $saved = $existing;
                    } else {
                        $saved = Teacher::create(array_merge($data, [
                            'created_by' => auth()->id(),
                        ]));
                    }
                } else {
                    $saved = Teacher::create(array_merge($data, [
                        'created_by' => auth()->id(),
                    ]));
                }

                $incomingIds[] = $saved->id;
            }

            // Remove teachers not present in payload
            // For university: delete any teacher not in the list
            // For department: only delete teachers from current department
            $deleteQuery = Teacher::whereNotIn('id', $incomingIds);

            if ($siteType === 'department') {
                $deleteQuery->where('site_id', $currentSiteId);
            }

            if (count($incomingIds) > 0) {
                $deleteQuery->delete();
            } else {
                // If no teachers in payload, delete all (based on site type)
                if ($siteType === 'department') {
                    Teacher::where('site_id', $currentSiteId)->delete();
                } else {
                    Teacher::truncate();
                }
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Teachers saved successfully',
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Teachers save error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save teachers: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function uploadImage(Request $request)
    {
        try {
            $siteData = $this->getSiteData($request);
            $siteId = $siteData['id'] ?? 1;

            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ]);

            $file = $request->file('file');
            $folder = 'teachers/' . $siteId;
            $path = $file->store($folder, 'public');
            // Use asset() helper with Storage::url() to get full URL with domain
            $url = asset(Storage::url($path));

            return response()->json([
                'success' => true,
                'url' => $url,
                'path' => $path,
                'message' => 'Image uploaded successfully',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Teacher image upload failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
