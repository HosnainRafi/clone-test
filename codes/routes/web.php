<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\SubdomainMiddleware;

Route::middleware(SubdomainMiddleware::class)
    ->get('/', function (Illuminate\Http\Request $request) {
        $data = (object) [
            'siteData' => $request->get('siteData'),
            'theme' => $request->get('theme'),
            'components' => $request->get('components', collect()),
            'viewType' => $request->get('viewType'),
            'fullDomain' => $request->get('fullDomain'),
            'page' => $request->get('page'),
        ];
        return Inertia::render('Welcome',[
            'message' => 'Hello World !',
            'data' => $data,
        ]);
    })->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard/Index');
})->name('dashboard');

Route::get('/form-elements', function () {
    return Inertia::render('Forms/FormElementsView');
})->name('form-elements');

Route::get('/form-layout', function () {
    return Inertia::render('Forms/FormLayoutView');
})->name('form-layout');

Route::get('/tables', function () {
    return Inertia::render('Tables/TablesView');
})->name('tables');

Route::get('/signin', function () {
    return Inertia::render('Authentication/SigninView');
})->name('signin');

Route::get('/signup', function () {
    return Inertia::render('Authentication/SignupView');
})->name('signup');

Route::get('/alerts', function () {
    return Inertia::render('UiElements/AlertsView');
})->name('alerts');

Route::get('/buttons', function () {
    return Inertia::render('UiElements/ButtonsView');
})->name('buttons');

Route::get('/charts', function () {
    return Inertia::render('Charts/BasicChartView');
})->name('charts');

Route::get('/calendar', function () {
    return Inertia::render('Calendar/CalendarView');
})->name('calendar');

Route::get('/profile', function () {
    return Inertia::render('Profile/ProfileView');
})->name('profile');

Route::get('/settings', function () {
    return Inertia::render('Settings/SettingsView');
})->name('settings');