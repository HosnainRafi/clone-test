<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class TechNewsController extends BaseController
{
    /**
     * Display the tech news management page.
     */
    public function index(Request $request): Response
    {
        try {
            $siteId = $this->getSiteId($request);
            
            // Get tech news data from database or provide defaults
            $techNewsData = $this->siteContentService->getSiteSettings($siteId, 'tech_news');
            
            // If no data exists, provide defaults
            if (empty($techNewsData)) {
                $techNewsData = [
                    'sectionTitle' => 'Latest Technology News & Insights',
                    'sectionSubtitle' => 'Stay updated with the latest developments in technology, research breakthroughs, and achievements from MBSTU Computer Science & Engineering department.',
                    'isVisible' => true,
                    'maxNews' => 6,
                    'showCarousel' => true,
                    'autoPlay' => false,
                    'autoPlayDelay' => 5000,
                    'sortBy' => 'date',
                    'sortOrder' => 'desc',
                    'news' => [
                        [
                            'id' => 1,
                            'title' => 'AI Revolution in Computer Science Education: New Curriculum at MBSTU',
                            'summary' => 'MBSTU CSE Department introduces cutting-edge AI and Machine Learning courses to prepare students for the future of technology.',
                            'content' => 'The Computer Science and Engineering Department at MBSTU has announced a comprehensive curriculum overhaul that integrates artificial intelligence and machine learning across all levels of study.',
                            'image' => '/images/news/ai-education.jpg',
                            'fallbackGradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                            'category' => 'Education',
                            'author' => 'Dr. Rahman Ahmed',
                            'authorRole' => 'Head of Department',
                            'publishDate' => '2024-03-15',
                            'readTime' => '5 min',
                            'views' => 1250,
                            'comments' => 23,
                            'tags' => ['AI', 'Education', 'Curriculum', 'Future Tech'],
                            'trending' => true,
                            'source' => 'MBSTU News',
                            'sourceUrl' => '#',
                            'isActive' => true,
                            'order' => 1
                        ],
                        [
                            'id' => 2,
                            'title' => 'Breakthrough in Quantum Computing Research by MBSTU Faculty',
                            'summary' => 'Faculty members publish groundbreaking research on quantum algorithms that could revolutionize computational efficiency.',
                            'content' => 'A team of researchers from MBSTU\'s Computer Science Department has made significant progress in quantum computing algorithms, publishing their findings in top-tier international journals.',
                            'image' => '/images/news/quantum-computing.jpg',
                            'fallbackGradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                            'category' => 'Research',
                            'author' => 'Prof. Fatima Khan',
                            'authorRole' => 'Research Director',
                            'publishDate' => '2024-03-12',
                            'readTime' => '7 min',
                            'views' => 892,
                            'comments' => 15,
                            'tags' => ['Quantum Computing', 'Research', 'Innovation', 'Algorithms'],
                            'trending' => true,
                            'source' => 'Tech Research Today',
                            'sourceUrl' => '#',
                            'isActive' => true,
                            'order' => 2
                        ],
                        [
                            'id' => 3,
                            'title' => 'Student Innovation: IoT-Based Smart Campus Management System',
                            'summary' => 'Final year students develop comprehensive IoT solution for campus automation and resource management.',
                            'content' => 'A group of final year CSE students has developed an innovative IoT-based system that integrates various campus services including library management, energy optimization, and security monitoring.',
                            'image' => '/images/news/smart-campus.jpg',
                            'fallbackGradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                            'category' => 'Innovation',
                            'author' => 'Sarah Ahmed',
                            'authorRole' => 'Tech Journalist',
                            'publishDate' => '2024-03-10',
                            'readTime' => '4 min',
                            'views' => 634,
                            'comments' => 8,
                            'tags' => ['IoT', 'Smart Campus', 'Student Projects', 'Innovation'],
                            'trending' => false,
                            'source' => 'Campus Tech',
                            'sourceUrl' => '#',
                            'isActive' => true,
                            'order' => 3
                        ]
                    ]
                ];
            }

            return Inertia::render('TechNews/Index', [
                'techNewsData' => $techNewsData,
                'siteData' => $request->get('siteData'),
                'siteId' => $siteId
            ]);

        } catch (\Exception $e) {
            return Inertia::render('TechNews/Index', [
                'techNewsData' => [
                    'sectionTitle' => 'Latest Technology News & Insights',
                    'sectionSubtitle' => 'Stay updated with the latest developments in technology, research breakthroughs, and achievements from MBSTU Computer Science & Engineering department.',
                    'isVisible' => true,
                    'maxNews' => 6,
                    'showCarousel' => true,
                    'autoPlay' => false,
                    'autoPlayDelay' => 5000,
                    'sortBy' => 'date',
                    'sortOrder' => 'desc',
                    'news' => []
                ],
                'error' => 'Failed to load tech news data: ' . $e->getMessage(),
                'siteData' => $request->get('siteData'),
                'siteId' => null
            ]);
        }
    }

    /**
     * Save the tech news configuration.
     */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'siteId' => 'required|integer',
            'techNewsData' => 'required|array',
            'techNewsData.sectionTitle' => 'required|string|max:255',
            'techNewsData.sectionSubtitle' => 'required|string|max:500',
            'techNewsData.isVisible' => 'required|boolean',
            'techNewsData.maxNews' => 'required|integer|min:1|max:50',
            'techNewsData.showCarousel' => 'required|boolean',
            'techNewsData.autoPlay' => 'required|boolean',
            'techNewsData.autoPlayDelay' => 'required|integer|min:1000',
            'techNewsData.sortBy' => ['required', 'string', Rule::in(['date', 'views', 'trending'])],
            'techNewsData.sortOrder' => ['required', 'string', Rule::in(['asc', 'desc'])],
            
            // News validation
            'techNewsData.news' => 'required|array',
            'techNewsData.news.*.id' => 'required|integer',
            'techNewsData.news.*.title' => 'required|string|max:255',
            'techNewsData.news.*.summary' => 'required|string|max:500',
            'techNewsData.news.*.content' => 'required|string|max:2000',
            'techNewsData.news.*.category' => 'required|string|max:100',
            'techNewsData.news.*.author' => 'required|string|max:255',
            'techNewsData.news.*.authorRole' => 'required|string|max:255',
            'techNewsData.news.*.publishDate' => 'required|date',
            'techNewsData.news.*.readTime' => 'required|string|max:50',
            'techNewsData.news.*.views' => 'required|integer|min:0',
            'techNewsData.news.*.comments' => 'required|integer|min:0',
            'techNewsData.news.*.source' => 'required|string|max:255',
            'techNewsData.news.*.image' => 'nullable|string|max:500',
            'techNewsData.news.*.fallbackGradient' => 'required|string|max:255',
            'techNewsData.news.*.trending' => 'required|boolean',
            'techNewsData.news.*.isActive' => 'required|boolean',
            'techNewsData.news.*.order' => 'required|integer',
            
            // Tags validation
            'techNewsData.news.*.tags' => 'required|array|min:1',
            'techNewsData.news.*.tags.*' => 'required|string|max:50',
            
            // Optional URLs
            'techNewsData.news.*.sourceUrl' => 'nullable|string|max:500'
        ]);

        try {
            $siteId = $validated['siteId'];
            
            // Save the data using SiteContentService
            $this->siteContentService->saveSiteSettings($siteId, 'tech_news', $validated['techNewsData']);

            // Return JSON response for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tech News section updated successfully!'
                ]);
            }

            // Return redirect for regular form submissions
            return redirect()
                ->back()
                ->with('success', 'Tech News section updated successfully!');

        } catch (\Exception $e) {
            // Return JSON error response for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update Tech News section: ' . $e->getMessage()
                ], 500);
            }

            // Return redirect with errors for regular form submissions
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to update Tech News section: ' . $e->getMessage()])
                ->withInput();
        }
    }
}