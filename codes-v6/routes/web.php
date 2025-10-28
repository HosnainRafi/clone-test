<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\HeroCarouselController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\NoticeController as AdminNoticeController;
use App\Http\Controllers\Admin\PublicationController;
use App\Http\Controllers\Admin\MessageFromController;
use App\Http\Controllers\Admin\HeadlineMarqueeController;
use App\Http\Controllers\Admin\FacultiesController;
use App\Http\Controllers\Admin\WelcomeSectionController;
use App\Http\Controllers\Admin\CampusLifeController;
use App\Http\Controllers\Admin\FooterController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\EventController;
use App\Http\Controllers\Public\NoticeController;
use Illuminate\Http\Request;
use Inertia\Inertia;

// Apply SubdomainMiddleware to all routes that need site data
Route::middleware('subdomain')->group(function () {

    // ==========================================
    // PUBLIC ROUTES
    // ==========================================

    // Home & Department Routes
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/department', [HomeController::class, 'department'])->name('department');

    // Public Content Routes
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');
    Route::get('/notices', [NoticeController::class, 'index'])->name('notices.index');
    Route::get('/notices/{slug}', [NoticeController::class, 'show'])->name('notices.show');

    // ==========================================
    // ADMIN CONTENT MANAGEMENT ROUTES
    // ==========================================

    // Menu Management
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    Route::post('/menu/save', [MenuController::class, 'save'])->name('menu.save');

    // Hero Carousel Management
    Route::get('/hero-carousel', [HeroCarouselController::class, 'index'])->name('hero-carousel');
    Route::post('/hero-carousel/save', [HeroCarouselController::class, 'save'])->name('hero-carousel.save');

    // Message From Management
    Route::get('/message-from', [MessageFromController::class, 'index'])->name('message-from');
    Route::post('/message-from/save', [MessageFromController::class, 'save'])->name('message-from.save');

    // Headline Marquee Management
    Route::get('/headline-marquee', [HeadlineMarqueeController::class, 'index'])->name('headline-marquee');
    Route::post('/headline-marquee/save', [HeadlineMarqueeController::class, 'save'])->name('headline-marquee.save');

    // Faculties Management
    Route::get('/faculties', [FacultiesController::class, 'index'])->name('faculties');
    Route::post('/faculties/save', [FacultiesController::class, 'save'])->name('faculties.save');

    // Welcome Section Management
    Route::get('/welcome-section', [WelcomeSectionController::class, 'index'])->name('welcome-section');
    Route::post('/welcome-section/save', [WelcomeSectionController::class, 'save'])->name('welcome-section.save');

    // Campus Life Management
    Route::get('/campus-life-section', [CampusLifeController::class, 'index'])->name('campus-life.index');
    Route::post('/campus-life/save', [CampusLifeController::class, 'save'])->name('campus-life.save');

    // Campus Glance Management
    Route::get('/campus-glance', [CampusLifeController::class, 'glance'])->name('campus-glance.index');
    Route::post('/campus-glance/save', [CampusLifeController::class, 'saveGlance'])->name('campus-glance.save');

    // News Section Management
    Route::get('/news-section', [AdminNewsController::class, 'index'])->name('news-section.index');
    Route::post('/news-section/save', [AdminNewsController::class, 'save'])->name('news-section.save');

    // Events Section Management
    Route::get('/events-section', [AdminEventController::class, 'index'])->name('events.admin');
    Route::post('/events-section/save', [AdminEventController::class, 'save'])->name('events.save');

    // Notices Section Management
    Route::get('/notices-section', [AdminNoticeController::class, 'index'])->name('notices.admin');
    Route::post('/notices-section/save', [AdminNoticeController::class, 'save'])->name('notices.save');

    // Publications Management
    Route::get('/publications-section', [PublicationController::class, 'index'])->name('publications.admin');
    Route::post('/publications-section/save', [PublicationController::class, 'save'])->name('publications.save');

    // Footer Management
    Route::get('/footer-section', [FooterController::class, 'index'])->name('footer.admin');
    Route::post('/footer-section/save', [FooterController::class, 'save'])->name('footer.save');

    // ==========================================
    // INERTIA/VUE PAGE ROUTES (MOVED INSIDE MIDDLEWARE)
    // ==========================================

    // Dashboard and Admin UI Routes (need site data for layout)
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard/Index');
    })->name('dashboard');

    Route::get('/signin', function () {
        return Inertia::render('Authentication/SigninView');
    })->name('signin');

    Route::get('/signup', function () {
        return Inertia::render('Authentication/SignupView');
    })->name('signup');

    Route::get('/profile', function () {
        return Inertia::render('Profile/ProfileView');
    })->name('profile');

    Route::get('/settings', function () {
        return Inertia::render('Settings/SettingsView');
    })->name('settings');

}); // END OF SUBDOMAIN MIDDLEWARE GROUP

// ==========================================
// DEVELOPMENT/TESTING ROUTES (NO INERTIA)
// ==========================================

Route::get('/test-db', function () {
    try {
        $sites = \Illuminate\Support\Facades\DB::table('sites')->get();
        return response()->json([
            'success' => true,
            'message' => 'Database connection successful',
            'sites_count' => $sites->count(),
            'sites' => $sites->take(3)
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
})->name('test.db');

Route::get('/test', function () {
    return '<html><body style="padding:40px;font-family:Arial"><h1 style="color:green">Docker Production Test</h1><p>If you can see this, your Docker environment is working perfectly!</p><p><a href="/">Back to main site</a></p></body></html>';
})->name('test');
