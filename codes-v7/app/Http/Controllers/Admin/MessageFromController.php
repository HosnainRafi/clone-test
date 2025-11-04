<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class MessageFromController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        $messageFromItems = $siteData['settings']['messageFromItems'] ?? [];

        return Inertia::render('MessageFrom/Index', [
            'siteData' => $siteData,
            'messageFromItems' => $messageFromItems,
            'siteId' => $siteId
        ]);
    }

    public function save(Request $request)
    {
        $messageFromItems = $request->input('messageFromItems');

        // Validate message structure
        foreach ($messageFromItems as $index => $item) {
            if (!isset($item['name']) || !isset($item['title']) ||
                !isset($item['message']) || !isset($item['type'])) {
                return $this->errorResponse(
                    "Message item at index {$index} is missing required fields (name, title, message, type)",
                    422
                );
            }
        }

        $validationRules = [
            'messageFromItems' => 'required|array',
            'siteId' => 'required|integer'
        ];

        return $this->handleSave($request, 'messageFromItems', 'messageFromItems', $validationRules);
    }
}
