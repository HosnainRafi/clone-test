<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class FacultiesController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        $facultyItems = $siteData['settings']['facultyItems'] ?? [];

        return Inertia::render('Faculties/Index', [
            'siteData' => $siteData,
            'facultyItems' => $facultyItems,
            'siteId' => $siteId
        ]);
    }

    public function save(Request $request)
    {
        $facultyItems = $request->input('facultyItems');

        // Validate faculty structure
        foreach ($facultyItems as $index => $item) {
            if (!isset($item['name']) || !isset($item['shortName']) ||
                !isset($item['description'])) {
                return $this->errorResponse(
                    "Faculty item at index {$index} is missing required fields (name, shortName, description)",
                    422
                );
            }
        }

        $validationRules = [
            'facultyItems' => 'required|array',
            'siteId' => 'required|integer'
        ];

        return $this->handleSave($request, 'facultyItems', 'facultyItems', $validationRules);
    }
}
