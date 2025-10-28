<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class FooterController extends BaseController
{
    public function index(Request $request)
    {
        try {
            // Get site ID using the same pattern as other controllers
            $siteId = $this->getSiteId($request);

            // Get footer data from site settings
            $footerData = $this->siteContentService->getSiteSettings($siteId, 'footerData');

            // If no footer data, provide defaults
            if (empty($footerData)) {
                $footerData = $this->getDefaultFooterData();
            }

            return Inertia::render('Footer/Index', [
                'footerData' => $footerData,
                'siteId' => $siteId
            ]);
        } catch (\Exception $e) {
            Log::error('Footer index error: ' . $e->getMessage());

            return Inertia::render('Footer/Index', [
                'footerData' => $this->getDefaultFooterData(),
                'siteId' => null
            ]);
        }
    }

    public function save(Request $request)
    {
        $validationRules = [
            'footerData' => 'required|array',
            'footerData.universityName' => 'required|string|max:255',
            'footerData.universityFullName' => 'required|string|max:255',
            'footerData.email' => 'required|email|max:255',
            'siteId' => 'required|integer|exists:sites,id'
        ];

        return $this->handleSave($request, 'footerData', 'footerData', $validationRules);
    }

    private function getDefaultFooterData()
    {
        return [
            'universityName' => 'MBSTU',
            'universityFullName' => 'Mawlana Bhashani Science and Technology University',
            'universitySlogan' => 'Excellence in Science and Technology Education',
            'logoUrl' => '/images/university/logo/MBSTU_logo.png',
            'address' => 'Santosh, Tangail-1902, Bangladesh',
            'phone' => '+880-921-55399',
            'email' => 'registrar@mbstu.ac.bd',
            'academicLinksTitle' => 'Academics',
            'academicLinks' => [],
            'usefulLinksTitle' => 'Useful Links',
            'usefulLinks' => [],
            'socialLinks' => [],
            'copyrightText' => '© ' . date('Y') . ' Mawlana Bhashani Science and Technology University',
        ];
    }
}
