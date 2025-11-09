<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class AtAGlanceController extends BaseController
{
    public function index(Request $request)
    {
        try {
            // Get site ID using the same pattern as other controllers
            $siteId = $this->getSiteId($request);

            // Get at-a-glance data from site settings
            $atAGlanceData = $this->siteContentService->getSiteSettings($siteId, 'atAGlanceData');

            // Always render the AtAGlance Index page for both main and department sites
            return Inertia::render('AtAGlance/Index', [
                'atAGlanceData' => empty($atAGlanceData) ? $this->getDefaultAtAGlanceData() : $atAGlanceData,
                'siteId' => $siteId
            ]);
        } catch (\Exception $e) {
            Log::error('AtAGlance index error: ' . $e->getMessage());

            return Inertia::render('AtAGlance/Index', [
                'atAGlanceData' => $this->getDefaultAtAGlanceData(),
                'siteId' => null
            ]);
        }
    }

    public function save(Request $request)
    {
        $validationRules = [
            'atAGlanceData' => 'required|array',
            'atAGlanceData.sectionTitle' => 'required|string|max:255',
            'atAGlanceData.sectionSubtitle' => 'required|string|max:500',
            'atAGlanceData.isVisible' => 'required|boolean',
            'atAGlanceData.animationDuration' => 'required|integer|min:500|max:10000',
            'atAGlanceData.animationDelay' => 'required|integer|min:0|max:2000',
            'atAGlanceData.statistics' => 'required|array',
            'atAGlanceData.statistics.*.id' => 'required|integer',
            'atAGlanceData.statistics.*.icon' => 'required|string|max:50',
            'atAGlanceData.statistics.*.number' => 'required|integer|min:0',
            'atAGlanceData.statistics.*.suffix' => 'nullable|string|max:10',
            'atAGlanceData.statistics.*.label' => 'required|string|max:100',
            'atAGlanceData.statistics.*.description' => 'required|string|max:200',
            'atAGlanceData.statistics.*.color' => 'required|string|in:blue,green,purple,orange,red,indigo',
            'atAGlanceData.statistics.*.bgGradient' => 'required|string|max:100',
            'atAGlanceData.statistics.*.isActive' => 'required|boolean',
            'atAGlanceData.statistics.*.order' => 'required|integer|min:1',
            'siteId' => 'required|integer|exists:sites,id'
        ];

        return $this->handleSave($request, 'atAGlanceData', 'atAGlanceData', $validationRules);
    }

    private function getDefaultAtAGlanceData()
    {
        return [
            'sectionTitle' => 'At a Glance',
            'sectionSubtitle' => 'Key statistics about the Department of Computer Science and Engineering at MBSTU',
            'isVisible' => true,
            'animationDuration' => 2500,
            'animationDelay' => 200,
            'statistics' => [
                [
                    'id' => 1,
                    'icon' => 'GraduationCap',
                    'number' => 19,
                    'suffix' => '+',
                    'label' => 'Current Teachers',
                    'description' => 'Experienced faculty members',
                    'color' => 'blue',
                    'bgGradient' => 'from-blue-500 to-blue-600',
                    'isActive' => true,
                    'order' => 1
                ],
                [
                    'id' => 2,
                    'icon' => 'Users',
                    'number' => 250,
                    'suffix' => '+',
                    'label' => 'Current Students',
                    'description' => 'Active enrolled students',
                    'color' => 'green',
                    'bgGradient' => 'from-green-500 to-green-600',
                    'isActive' => true,
                    'order' => 2
                ],
                [
                    'id' => 3,
                    'icon' => 'Eye',
                    'number' => 66483,
                    'suffix' => '+',
                    'label' => 'Total Visitors',
                    'description' => 'Unique website visitors',
                    'color' => 'purple',
                    'bgGradient' => 'from-purple-500 to-purple-600',
                    'isActive' => true,
                    'order' => 3
                ],
                [
                    'id' => 4,
                    'icon' => 'MousePointer',
                    'number' => 387405,
                    'suffix' => '+',
                    'label' => 'Total Visits',
                    'description' => 'Total website visits',
                    'color' => 'orange',
                    'bgGradient' => 'from-orange-500 to-orange-600',
                    'isActive' => true,
                    'order' => 4
                ]
            ]
        ];
    }
}