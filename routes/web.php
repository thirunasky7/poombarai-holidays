<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ShortController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('/stays', [FrontController::class, 'rooms'])->name('rooms');
Route::get('/cars', [FrontController::class, 'cars'])->name('cars');
Route::get('/places', [FrontController::class, 'places'])->name('places');
Route::get('/contact', [FrontController::class, 'contact'])->name('contact');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'show'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware(['auth', 'superadmin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::resource('pages', PageController::class)->only(['index', 'edit', 'update']);
        Route::resource('banners', BannerController::class)->except(['show']);
        Route::resource('shorts', ShortController::class)->except(['show'])->parameters(['shorts' => 'short']);
        Route::resource('rooms', RoomController::class)->except(['show']);
        Route::resource('vehicles', VehicleController::class)->except(['show']);
        Route::resource('packages', PackageController::class)->except(['show']);
        Route::resource('destinations', DestinationController::class)->except(['show']);
        Route::resource('plans', PlanController::class)->except(['show']);
        Route::resource('reviews', ReviewController::class)->except(['show']);
        Route::resource('faqs', FaqController::class)->except(['show']);
    });
});
