<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;

class SubdomainMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the host and parse subdomain
        $host = $request->getHost();

        // Default view type
        $viewType = 'default';

        // Check for localhost domains with different prefixes
        if (str_contains($host, 'ict')) {
            $viewType = 'ict';
        } elseif (str_contains($host, 'cse')) {
            $viewType = 'cse';
        }

        // Debug information
        logger("Host: {$host}, ViewType: {$viewType}");

        // Get site data based on subdomain
        $site = \App\Models\Site::where('domain', $host)->first();
        $theme = null;
        $components = collect();
        logger('Site data: ' . json_encode($site));
        if ($site) {
            $theme = \App\Models\Theme::where('id', $site->theme_id)->first();
            logger('Theme data: ' . json_encode($theme));
            if ($theme) {
                $components = \App\Models\Component::where('theme_id', $theme->id)->get();

                // Parse theme content if it exists
                if ($theme->content) {
                    // Content is already decoded as array due to JSON casting in the model
                    $themeContent = is_array($theme->content) ? $theme->content : json_decode($theme->content, true);
                    if ($themeContent) {
                        $theme->name = $themeContent['websiteTitle'] ?? $theme->name;
                        $theme->banner_title = $themeContent['bannerTitle'] ?? null;
                        $theme->banner_subtitle = $themeContent['bannerSubtitle'] ?? null;
                        $theme->nav_items = $themeContent['navItems'] ?? null;
                        $theme->footer_copyright = $themeContent['footerCopyright'] ?? null;
                        $theme->footer_contact = $themeContent['footerContact'] ?? null;
                    }
                }
            }
        }
        // Get the request path
        $path = $request->getPathInfo();
        // Log path information
        logger("Path: {$path}");
        // Find matching page by slug
        $page = \App\Models\Page::where('slug', trim($path, '/'))->first();
        logger('Page data: ' . json_encode($page));

        // Inject data into the request for controller access
        $request->merge([
            'siteData' => $site,
            'theme' => $theme,
            'components' => $components,
            'viewType' => $viewType,
            'fullDomain' => $host,
            'page' => $page,
            'themeName' => $site ? $site->theme_name : null,
        ]);

        // Share data with all views
        View::share('siteData', $site);
        View::share('theme', $theme);
        View::share('components', $components);
        View::share('viewType', $viewType);
        View::share('fullDomain', $host);
        View::share('themeName', $site ? $site->theme_name : null);
        return $next($request);
    }
}
