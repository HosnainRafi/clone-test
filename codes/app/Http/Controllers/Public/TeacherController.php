<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\BaseController;
use App\Models\Teacher;
use App\Services\ComponentService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Inertia\Inertia;

class TeacherController extends BaseController
{
    /**
     * List all active teachers for the current site.
     */
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        $allTeachers = Teacher::forSite($siteId)
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'slug' => $t->slug,
                    'name' => $t->name,
                    'designation' => $t->designation,
                    'department' => $t->department,
                    'email' => $t->email,
                    'phone_number' => $t->phone_number,
                    'research_interests' => $t->research_interests,
                    'profile_image' => $t->profile_image,
                    'link' => !empty($t->slug) ? route('teachers.show', ['slug' => $t->slug]) : url('/teachers'),
                ];
            })
            ->values();

        $paginated = new LengthAwarePaginator(
            $allTeachers->forPage(Paginator::resolveCurrentPage(), 12),
            $allTeachers->count(),
            12,
            Paginator::resolveCurrentPage(),
            ['path' => Paginator::resolveCurrentPath()]
        );

        return Inertia::render('Teacher/IndexAll', [
            'teachers' => $paginated,
            'menuItems' => $siteData['settings']['menuItems'] ?? [],
            'siteData' => $siteData,
        ]);
    }

    /**
     * Show a single teacher profile by slug.
     */
    public function show(Request $request, string $slug)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        $teacher = Teacher::forSite($siteId)
            ->where('slug', $slug)
            ->first();

        if (!$teacher) {
            abort(404);
        }

        // Build sidebar: a few more teachers
        $latestTeachers = Teacher::forSite($siteId)
            ->where('id', '!=', $teacher->id)
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->take(8)
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'designation' => $t->designation,
                'slug' => $t->slug,
                'profile_image' => $t->profile_image,
            ])
            ->values()
            ->all();

        // Get footer data using ComponentService
        $componentService = app(ComponentService::class);
        $footerData = $componentService->getComponentDataForFrontend($siteId, 'Footer');

        return Inertia::render('Teacher/Show', [
            'teacher' => [
                'id' => $teacher->id,
                'site_id' => $teacher->site_id,
                'slug' => $teacher->slug,
                'name' => $teacher->name,
                'designation' => $teacher->designation,
                'department' => $teacher->department,
                'profile_image' => $teacher->profile_image,
                'email' => $teacher->email,
                'phone_number' => $teacher->phone_number,
                'office_location' => $teacher->office_location,
                'website_url' => $teacher->website_url,
                'about_me' => $teacher->about_me,
                'research_interests' => $teacher->research_interests ?? [],
                'education' => $teacher->education ?? [],
                'experience' => $teacher->experience ?? [],
                'publications' => $teacher->publications ?? [],
                'projects' => $teacher->projects ?? [],
                'courses_taught' => $teacher->courses_taught ?? [],
                'awards' => $teacher->awards ?? [],
                'social_links' => $teacher->social_links ?? [],
                'is_active' => (bool) $teacher->is_active,
                'sort_order' => (int) $teacher->sort_order,
            ],
            'latestTeachers' => $latestTeachers,
            'menuItems' => $siteData['settings']['menuItems'] ?? [],
            'siteData' => $siteData,
            'footerData' => $footerData,
        ]);
    }
}
