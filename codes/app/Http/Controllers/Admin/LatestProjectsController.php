<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class LatestProjectsController extends BaseController
{
    /**
     * Display the latest projects management page.
     */
    public function index(Request $request): Response
    {
        try {
            $siteId = $this->getSiteId($request);
            
            // Get latest projects data from database or provide defaults
            $latestProjectsData = $this->siteContentService->getSiteSettings($siteId, 'latest_projects');
            
            // If no data exists, provide defaults
            if (empty($latestProjectsData)) {
                $latestProjectsData = [
                    'isVisible' => true,
                    'sectionTitle' => 'Latest Projects',
                    'sectionSubtitle' => 'Discover the innovative projects developed by our talented students under expert faculty supervision',
                    'showCarousel' => true,
                    'autoPlay' => false,
                    'autoPlayDelay' => 5000,
                    'maxProjects' => 6,
                    'projects' => [
                        [
                            'id' => 1,
                            'title' => 'E-Learning Platform',
                            'description' => 'A comprehensive online learning management system with real-time collaboration features, video conferencing, and advanced analytics for tracking student progress.',
                            'category' => 'Web Development',
                            'status' => 'Completed',
                            'year' => '2024',
                            'duration' => '6 months',
                            'supervisor' => 'Dr. Ahmed Rahman',
                            'team' => ['Sakib Ahmed', 'Rashida Khan', 'Fahim Hasan'],
                            'technologies' => ['Vue.js', 'Laravel', 'MySQL', 'WebRTC'],
                            'achievements' => ['Best Project Award 2024', 'Innovation Excellence'],
                            'image' => '/images/project/elearning.jpg',
                            'fallbackGradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                            'isActive' => true,
                            'order' => 1
                        ],
                        [
                            'id' => 2,
                            'title' => 'Smart Campus IoT System',
                            'description' => 'An intelligent campus management system using IoT sensors for monitoring energy consumption, security, and environmental conditions across university facilities.',
                            'category' => 'IoT',
                            'status' => 'Ongoing',
                            'year' => '2024',
                            'duration' => '8 months',
                            'supervisor' => 'Prof. Sultana Begum',
                            'team' => ['Mahmud Hassan', 'Nafisa Rahman', 'Karim Ahmed', 'Fatema Khatun'],
                            'technologies' => ['Arduino', 'Raspberry Pi', 'Node.js', 'MongoDB', 'MQTT'],
                            'achievements' => ['Research Excellence Grant'],
                            'image' => '/images/project/iot-campus.jpg',
                            'fallbackGradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                            'isActive' => true,
                            'order' => 2
                        ],
                        [
                            'id' => 3,
                            'title' => 'Mobile Health Tracker',
                            'description' => 'A cross-platform mobile application for health monitoring with AI-powered recommendations, medication reminders, and integration with wearable devices.',
                            'category' => 'Mobile App',
                            'status' => 'Completed',
                            'year' => '2023',
                            'duration' => '5 months',
                            'supervisor' => 'Dr. Mohammad Ali',
                            'team' => ['Raihan Uddin', 'Sumiya Akter'],
                            'technologies' => ['React Native', 'Firebase', 'TensorFlow', 'SQLite'],
                            'achievements' => ['Health Innovation Award', 'Top Mobile App 2023'],
                            'image' => '/images/project/health-app.jpg',
                            'fallbackGradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                            'isActive' => true,
                            'order' => 3
                        ],
                        [
                            'id' => 4,
                            'title' => 'Blockchain Voting System',
                            'description' => 'A secure and transparent digital voting platform using blockchain technology to ensure vote integrity and real-time result verification.',
                            'category' => 'Blockchain',
                            'status' => 'Research',
                            'year' => '2024',
                            'duration' => '7 months',
                            'supervisor' => 'Dr. Rashida Sultana',
                            'team' => ['Tanvir Ahmed', 'Samira Khatun', 'Rafiq Islam'],
                            'technologies' => ['Solidity', 'Web3.js', 'Ethereum', 'React', 'Node.js'],
                            'achievements' => ['Security Excellence Recognition'],
                            'image' => '/images/project/blockchain-voting.jpg',
                            'fallbackGradient' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                            'isActive' => true,
                            'order' => 4
                        ],
                        [
                            'id' => 5,
                            'title' => 'Green Energy Monitor',
                            'description' => 'A comprehensive system for monitoring and optimizing renewable energy sources in smart grids with predictive analytics and automated control systems.',
                            'category' => 'Embedded Systems',
                            'status' => 'Completed',
                            'year' => '2023',
                            'duration' => '9 months',
                            'supervisor' => 'Dr. Mahmuda Khatun',
                            'team' => ['Sharif Ahmed', 'Ruma Begum', 'Habib Rahman', 'Salma Akter'],
                            'technologies' => ['C++', 'Arduino', 'Python', 'InfluxDB', 'Grafana'],
                            'achievements' => ['Green Technology Award 2023', 'Research Publication'],
                            'image' => '/images/project/green-energy.jpg',
                            'fallbackGradient' => 'linear-gradient(135deg, #96fbc4 0%, #f9f586 100%)',
                            'isActive' => true,
                            'order' => 5
                        ]
                    ]
                ];
            }

            return Inertia::render('LatestProject/Index', [
                'latestProjectsData' => $latestProjectsData,
                'siteData' => $request->get('siteData'),
                'siteId' => $siteId
            ]);

        } catch (\Exception $e) {
            return Inertia::render('LatestProject/Index', [
                'latestProjectsData' => [
                    'isVisible' => true,
                    'sectionTitle' => 'Latest Projects',
                    'sectionSubtitle' => 'Discover the innovative projects developed by our talented students under expert faculty supervision',
                    'showCarousel' => true,
                    'autoPlay' => false,
                    'autoPlayDelay' => 5000,
                    'maxProjects' => 6,
                    'projects' => []
                ],
                'error' => 'Failed to load latest projects data: ' . $e->getMessage(),
                'siteData' => $request->get('siteData'),
                'siteId' => null
            ]);
        }
    }

    /**
     * Save the latest projects configuration.
     */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'siteId' => 'required|integer',
            'latestProjectsData' => 'required|array',
            'latestProjectsData.isVisible' => 'required|boolean',
            'latestProjectsData.sectionTitle' => 'required|string|max:255',
            'latestProjectsData.sectionSubtitle' => 'required|string|max:500',
            'latestProjectsData.showCarousel' => 'required|boolean',
            'latestProjectsData.autoPlay' => 'required|boolean',
            'latestProjectsData.autoPlayDelay' => 'required|integer|min:1000',
            'latestProjectsData.maxProjects' => 'required|integer|min:1|max:50',
            
            // Projects validation
            'latestProjectsData.projects' => 'required|array',
            'latestProjectsData.projects.*.id' => 'required|integer',
            'latestProjectsData.projects.*.title' => 'required|string|max:255',
            'latestProjectsData.projects.*.description' => 'required|string|max:1000',
            'latestProjectsData.projects.*.category' => 'required|string|max:100',
            'latestProjectsData.projects.*.status' => ['required', 'string', Rule::in(['Completed', 'Ongoing', 'Research'])],
            'latestProjectsData.projects.*.year' => 'required|string|max:10',
            'latestProjectsData.projects.*.duration' => 'required|string|max:50',
            'latestProjectsData.projects.*.supervisor' => 'required|string|max:255',
            'latestProjectsData.projects.*.image' => 'nullable|string|max:500',
            'latestProjectsData.projects.*.fallbackGradient' => 'required|string|max:255',
            'latestProjectsData.projects.*.isActive' => 'required|boolean',
            'latestProjectsData.projects.*.order' => 'required|integer',
            
            // Team validation (simplified to array of strings)
            'latestProjectsData.projects.*.team' => 'required|array|min:1',
            'latestProjectsData.projects.*.team.*' => 'required|string|max:255',
            
            // Technologies validation
            'latestProjectsData.projects.*.technologies' => 'required|array|min:1',
            'latestProjectsData.projects.*.technologies.*' => 'required|string|max:50',
            
            // Achievements validation (optional)
            'latestProjectsData.projects.*.achievements' => 'nullable|array',
            'latestProjectsData.projects.*.achievements.*' => 'required|string|max:255',
            
            // Optional URLs
            'latestProjectsData.projects.*.githubUrl' => 'nullable|string|max:500',
            'latestProjectsData.projects.*.demoUrl' => 'nullable|string|max:500'
        ]);

        try {
            $siteId = $validated['siteId'];
            
            // Save the data using SiteContentService
            $this->siteContentService->saveSiteSettings($siteId, 'latest_projects', $validated['latestProjectsData']);

            // Return JSON response for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Latest Projects section updated successfully!'
                ]);
            }

            // Return redirect for regular form submissions
            return redirect()
                ->back()
                ->with('success', 'Latest Projects section updated successfully!');

        } catch (\Exception $e) {
            // Return JSON error response for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update Latest Projects section: ' . $e->getMessage()
                ], 500);
            }

            // Return redirect with errors for regular form submissions
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to update Latest Projects section: ' . $e->getMessage()])
                ->withInput();
        }
    }
}