<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SubdomainMiddleware;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

// Apply web middleware with subdomain detection
Route::middleware(['web', SubdomainMiddleware::class])->group(function () {
    // Main route - use Vue template with subdomain info
    Route::get('/', function (Request $request) {
        
        // Get data from middleware-injected request
        $siteData = $request->get('siteData');
        $theme = $request->get('theme');
        $components = $request->get('components');
        $viewType = $request->get('viewType');
        $fullDomain = $request->get('fullDomain');
        
        // Set default values or use theme data
        $websiteTitle = $theme->name ?? 'MBSTU';
        $bannerTitle = $theme->banner_title ?? 'Mawlana Bhashani Science and Technology University';
        $bannerSubtitle = $theme->banner_subtitle ?? 'Excellence in Education and Research';
        $navItems = $theme->nav_items ?? [
            ['label' => 'Home', 'url' => '/'],
            ['label' => 'About', 'url' => '/about'],
            ['label' => 'Departments', 'url' => '/departments'],
            ['label' => 'Admissions', 'url' => '/admissions'],
            ['label' => 'Research', 'url' => '/research'],
            ['label' => 'Contact', 'url' => '/contact']
        ];
        $footerCopyright = $theme->footer_copyright ?? '© 2024 Mawlana Bhashani Science and Technology University. All rights reserved.';
        $footerContact = $theme->footer_contact ?? 'Santosh, Tangail-1902, Bangladesh | Phone: +880-921-55399 | Email: info@mbstu.ac.bd';

        return view('main', compact('websiteTitle', 'bannerTitle', 'bannerSubtitle', 'navItems', 'footerCopyright', 'footerContact', 'viewType', 'fullDomain', 'siteData', 'theme', 'components'));
    });
    
    // Fallback route for any other path with subdomain info
    Route::get('/{any}', function (Request $request) {
        // Get the host to determine subdomain
        $host = $request->getHost();
        
        // Default view type
        $viewType = 'default';
        
        // Set view type based on subdomain
        if (str_contains($host, 'ict.mbstu')) {
            $viewType = 'ict';
        } elseif (str_contains($host, 'cse.mbstu')) {
            $viewType = 'cse';
        }
        $page = $request->get('page');
        // Pass the view type to the Vue template
        return view('vue',compact('page'));
    })->where('any', '.*');
});