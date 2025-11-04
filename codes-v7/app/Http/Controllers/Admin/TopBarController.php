<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use App\Models\Site; // Make sure to use your Site model

class TopBarController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;
        $settings = $siteData['settings'] ?? [];

        return Inertia::render('TopBar/Index', [
            'siteData' => $siteData,
            'topBarLinks' => $settings['topBarLinks'] ?? [],
            // FIX: Pass null instead of a default object.
            // This allows the Vue component to correctly handle an empty state.
            'loginLink' => $settings['loginLink'] ?? null,
            'contactEmail' => $settings['contactEmail'] ?? '',
            'address' => $settings['address'] ?? '',
            'siteId' => $siteId
        ]);
    }

    public function save(Request $request)
    {
        $validationRules = [
            'topBarLinks' => 'nullable|array',
            'topBarLinks.*.title' => 'required_with:topBarLinks|string',
            'topBarLinks.*.href' => 'required_with:topBarLinks|string|url',
            // FIX: Allow loginLink to be null, which we send from Vue if fields are empty
            'loginLink' => 'nullable|array',
            'loginLink.title' => 'required_with:loginLink|string',
            'loginLink.href' => 'required_with:loginLink|string|url',
            'contactEmail' => 'nullable|email',
            'address' => 'nullable|string',
            'siteId' => 'required|integer'
        ];

        $validated = $request->validate($validationRules);

        try {
            // Use findSite from BaseController or find directly
            $site = Site::findOrFail($validated['siteId']);

            $settings = $site->settings ?? [];

            // Update settings with new values
            $settings['topBarLinks'] = $validated['topBarLinks'] ?? [];
            // This will correctly save `null` if the payload sent `null`
            $settings['loginLink'] = $validated['loginLink'] ?? null;
            $settings['contactEmail'] = $validated['contactEmail'] ?? null;
            $settings['address'] = $validated['address'] ?? null;

            $site->settings = $settings;
            $site->save();

            return back()->with('success', 'Top bar configuration saved successfully.');

        } catch (\Exception $e) {
            Log::error('Error saving top bar configuration: ' . $e->getMessage(), ['siteId' => $validated['siteId']]);
            return back()->with('error', 'An error occurred while saving.');
        }
    }
}
