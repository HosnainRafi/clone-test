<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\News;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class NewsController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        // Get news items from the database instead of settings
        $newsItems = News::forSite($siteId)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'excerpt' => $item->excerpt,
                    'image' => $item->image,
                    'date' => $item->published_at?->format('Y-m-d') ?? $item->created_at->format('Y-m-d'),
                    'category' => $item->category,
                    'link' => $item->link && !str_starts_with($item->link, '/news/') ? $item->link : '/news/' . $item->slug, // Use external link if provided, otherwise generate internal link
                    'isActive' => $item->is_active,
                    'displayOrder' => $item->sort_order,
                    'is_featured' => $item->is_featured,
                    'slug' => $item->slug,
                    'content' => $item->content,
                    'views_count' => $item->views_count,
                ];
            })
            ->toArray();

        return Inertia::render('News/Index', [
            'newsItems' => $newsItems,
            'siteId' => $siteId
        ]);
    }

    public function save(Request $request)
    {
        Log::info('News section save request received', [
            'input' => $request->all(),
            'siteData' => $this->getSiteData($request)
        ]);

        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;
        $newsItems = $request->input('newsItems', []);

        try {
            // Clear existing news for this site
            News::forSite($siteId)->delete();

            // Create new news items
            foreach ($newsItems as $item) {
                if (!empty($item['title'])) {
                    News::create([
                        'title' => $item['title'],
                        'excerpt' => $item['excerpt'] ?? '',
                        'content' => $item['content'] ?? '',
                        'image' => $item['image'] ?? '',
                        'category' => $item['category'] ?? '',
                        'link' => $item['link'] ?? '', // Keep external links if provided
                        'is_active' => $item['isActive'] ?? true,
                        'is_featured' => $item['is_featured'] ?? false,
                        'sort_order' => $item['displayOrder'] ?? 0,
                        'published_at' => isset($item['date']) ? \Carbon\Carbon::parse($item['date']) : now(),
                        'site_id' => $siteId,
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'News section configuration saved successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('News section save error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save news configuration: ' . $e->getMessage()
            ], 500);
        }
    }
}
