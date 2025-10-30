<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = News::query();

        // Filter by site if provided
        if ($request->has('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        // Filter by category if provided
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by active status
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        // Filter by featured status
        if ($request->has('featured')) {
            $query->where('is_featured', $request->boolean('featured'));
        }

        // Only show published items by default
        if (!$request->has('include_unpublished')) {
            $query->published();
        }

        // Order by published date or created date
        $query->orderBy('published_at', 'desc')
              ->orderBy('created_at', 'desc');

        // Pagination
        $perPage = $request->get('per_page', 10);
        $news = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $news->items(),
            'pagination' => [
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage(),
                'per_page' => $news->perPage(),
                'total' => $news->total(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'meta_data' => 'nullable|array',
            'site_id' => 'nullable|integer|exists:sites,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $news = News::create([
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'image' => $request->image,
            'category' => $request->category,
            'link' => $request->link,
            'is_active' => $request->get('is_active', true),
            'is_featured' => $request->get('is_featured', false),
            'published_at' => $request->published_at ?? now(),
            'expires_at' => $request->expires_at,
            'meta_data' => $request->meta_data,
            'created_by' => Auth::id(),
            'site_id' => $request->site_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'News item created successfully',
            'data' => $news->fresh()
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $news = News::findOrFail($id);
        
        // Increment view count
        $news->incrementViews();

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    /**
     * Display the specified resource by slug.
     */
    public function showBySlug(string $slug): JsonResponse
    {
        $news = News::where('slug', $slug)->firstOrFail();
        
        // Increment view count
        $news->incrementViews();

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $news = News::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'meta_data' => 'nullable|array',
            'site_id' => 'nullable|integer|exists:sites,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = $request->only([
            'title', 'excerpt', 'content', 'image', 'category', 'link',
            'is_active', 'is_featured', 'published_at', 'expires_at', 'meta_data', 'site_id'
        ]);

        $updateData['updated_by'] = Auth::id();

        $news->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'News item updated successfully',
            'data' => $news->fresh()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $news = News::findOrFail($id);
        $news->delete();

        return response()->json([
            'success' => true,
            'message' => 'News item deleted successfully'
        ]);
    }

    /**
     * Get featured news items.
     */
    public function featured(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 5);
        
        $news = News::featured()
            ->active()
            ->published()
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    /**
     * Get news by category.
     */
    public function byCategory(Request $request, string $category): JsonResponse
    {
        $perPage = $request->get('per_page', 10);
        
        $news = News::byCategory($category)
            ->active()
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $news->items(),
            'pagination' => [
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage(),
                'per_page' => $news->perPage(),
                'total' => $news->total(),
            ]
        ]);
    }
}
