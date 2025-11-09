<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TeacherProfileController extends BaseController
{
    /**
     * Get the authenticated teacher's profile
     */
    private function getTeacherProfile(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        // TODO: Replace with actual authentication when login system is implemented
        // For now, get the first teacher or a specific one
        $teacherId = $request->query('teacher_id') ?? $request->session()->get('teacher_id');

        if ($teacherId) {
            $teacher = Teacher::where('id', $teacherId)
                ->where('site_id', $siteId)
                ->first();
        } else {
            // Get first teacher for testing purposes
            $teacher = Teacher::where('site_id', $siteId)->first();
        }

        if (!$teacher) {
            abort(404, 'Teacher profile not found. Please create a teacher first in /admin/teachers');
        }

        // Store teacher_id in session for subsequent requests
        $request->session()->put('teacher_id', $teacher->id);

        return $teacher;
    }

    /**
     * Teacher profile index - redirect to basic info
     */
    public function index(Request $request)
    {
        return redirect()->route('teacher.profile.basic-info');
    }

    /**
     * Show basic info form
     */
    public function basicInfo(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        return Inertia::render('TeacherProfile/BasicInfo', [
            'teacher' => [
                'id' => $teacher->id,
                'site_id' => $teacher->site_id,
                'name' => $teacher->name,
                'designation' => $teacher->designation,
                'department' => $teacher->department,
                'profile_image' => $teacher->profile_image,
                'email' => $teacher->email,
                'phone_number' => $teacher->phone_number,
                'office_location' => $teacher->office_location,
                'website_url' => $teacher->website_url,
                'is_active' => (bool) $teacher->is_active,
            ],
        ]);
    }

    /**
     * Update basic info
     */
    public function updateBasicInfo(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:50',
            'office_location' => 'nullable|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'profile_image' => 'nullable|string',
        ]);

        // Update slug if name changed
        if ($teacher->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $teacher->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Basic information updated successfully',
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'designation' => $teacher->designation,
                'department' => $teacher->department,
                'profile_image' => $teacher->profile_image,
                'email' => $teacher->email,
                'phone_number' => $teacher->phone_number,
                'office_location' => $teacher->office_location,
                'website_url' => $teacher->website_url,
            ],
        ]);
    }

    /**
     * Show about me form
     */
    public function about(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        return Inertia::render('TeacherProfile/About', [
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'about_me' => $teacher->about_me,
            ],
        ]);
    }

    /**
     * Update about me
     */
    public function updateAbout(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        $validated = $request->validate([
            'about_me' => 'nullable|string',
        ]);

        $teacher->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'About section updated successfully',
        ]);
    }

    /**
     * Show research interests form
     */
    public function researchInterests(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        return Inertia::render('TeacherProfile/ResearchInterests', [
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'research_interests' => $teacher->research_interests ?? [],
            ],
        ]);
    }

    /**
     * Update research interests
     */
    public function updateResearchInterests(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        $validated = $request->validate([
            'research_interests' => 'nullable|array',
            'research_interests.*' => 'string',
        ]);

        $teacher->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Research interests updated successfully',
        ]);
    }

    /**
     * Show education form
     */
    public function education(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        return Inertia::render('TeacherProfile/Education', [
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'education' => $teacher->education ?? [],
            ],
        ]);
    }

    /**
     * Update education
     */
    public function updateEducation(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        $validated = $request->validate([
            'education' => 'nullable|array',
            'education.*.degree' => 'required|string',
            'education.*.field' => 'required|string',
            'education.*.university' => 'required|string',
            'education.*.year' => 'required|string',
        ]);

        $teacher->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Education updated successfully',
        ]);
    }

    /**
     * Show experience form
     */
    public function experience(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        return Inertia::render('TeacherProfile/Experience', [
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'experience' => $teacher->experience ?? [],
            ],
        ]);
    }

    /**
     * Update experience
     */
    public function updateExperience(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        $validated = $request->validate([
            'experience' => 'nullable|array',
            'experience.*.role' => 'required|string',
            'experience.*.institution' => 'required|string',
            'experience.*.period' => 'required|string',
        ]);

        $teacher->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Experience updated successfully',
        ]);
    }

    /**
     * Show publications form
     */
    public function publications(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        return Inertia::render('TeacherProfile/Publications', [
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'publications' => $teacher->publications ?? [],
            ],
        ]);
    }

    /**
     * Update publications
     */
    public function updatePublications(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        $validated = $request->validate([
            'publications' => 'nullable|array',
            'publications.*.title' => 'required|string',
            'publications.*.authors' => 'nullable|string',
            'publications.*.journal' => 'nullable|string',
            'publications.*.conference' => 'nullable|string',
            'publications.*.year' => 'nullable|integer',
            'publications.*.link' => 'nullable|url',
            'publications.*.category' => 'nullable|string',
        ]);

        $teacher->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Publications updated successfully',
        ]);
    }

    /**
     * Show projects form
     */
    public function projects(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        return Inertia::render('TeacherProfile/Projects', [
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'projects' => $teacher->projects ?? [],
            ],
        ]);
    }

    /**
     * Update projects
     */
    public function updateProjects(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        $validated = $request->validate([
            'projects' => 'nullable|array',
            'projects.*.title' => 'required|string',
            'projects.*.role' => 'nullable|string',
            'projects.*.funding_source' => 'nullable|string',
            'projects.*.status' => 'nullable|string',
        ]);

        $teacher->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Projects updated successfully',
        ]);
    }

    /**
     * Show courses form
     */
    public function courses(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        return Inertia::render('TeacherProfile/Courses', [
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'courses_taught' => $teacher->courses_taught ?? [],
            ],
        ]);
    }

    /**
     * Update courses
     */
    public function updateCourses(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        $validated = $request->validate([
            'courses_taught' => 'nullable|array',
            'courses_taught.*.code' => 'nullable|string',
            'courses_taught.*.title' => 'required|string',
        ]);

        $teacher->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Courses updated successfully',
        ]);
    }

    /**
     * Show awards form
     */
    public function awards(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        return Inertia::render('TeacherProfile/Awards', [
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'awards' => $teacher->awards ?? [],
            ],
        ]);
    }

    /**
     * Update awards
     */
    public function updateAwards(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        $validated = $request->validate([
            'awards' => 'nullable|array',
            'awards.*.title' => 'required|string',
            'awards.*.event' => 'nullable|string',
            'awards.*.year' => 'nullable|string',
        ]);

        $teacher->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Awards updated successfully',
        ]);
    }

    /**
     * Show social links form
     */
    public function socialLinks(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        return Inertia::render('TeacherProfile/SocialLinks', [
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'social_links' => $teacher->social_links ?? [],
            ],
        ]);
    }

    /**
     * Update social links
     */
    public function updateSocialLinks(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        $validated = $request->validate([
            'social_links' => 'nullable|array',
            'social_links.linkedin' => 'nullable|url',
            'social_links.google_scholar' => 'nullable|url',
            'social_links.researchgate' => 'nullable|url',
        ]);

        $teacher->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Social links updated successfully',
        ]);
    }

    /**
     * Upload profile image
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $teacher = $this->getTeacherProfile($request);
        $siteId = $teacher->site_id;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($teacher->profile_image) {
                $oldPath = str_replace('/storage/', '', parse_url($teacher->profile_image, PHP_URL_PATH));
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs("teachers/{$siteId}", $filename, 'public');

            $fullUrl = asset(Storage::url($path));

            $teacher->update(['profile_image' => $fullUrl]);

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'url' => $fullUrl,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No image file provided',
        ], 422);
    }

    /**
     * Delete teacher profile
     */
    public function destroy(Request $request)
    {
        $teacher = $this->getTeacherProfile($request);

        // Delete profile image if exists
        if ($teacher->profile_image) {
            $path = str_replace('/storage/', '', parse_url($teacher->profile_image, PHP_URL_PATH));
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $teacher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Profile deleted successfully',
        ]);
    }
}
