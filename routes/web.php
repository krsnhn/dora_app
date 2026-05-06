<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\TravelerDashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MemoryController;
use App\Http\Controllers\BackpackController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\AgencyDirectoryController;
use App\Http\Controllers\Agency\AgencyDashboardController;
use App\Http\Controllers\Agency\TourPackageController;
use App\Http\Controllers\Agency\InquiryController as AgencyInquiryController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AgencyDirectoryController as AdminAgencyDirectoryController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\DestinationController as AdminDestinationController;
use App\Http\Controllers\Admin\FeedbackModerationController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');

// Destinations (public)
Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{destination}', [DestinationController::class, 'show'])->name('destinations.show');

// Auth
require __DIR__.'/auth.php';

// Traveler routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', TravelerDashboardController::class)->name('traveler.dashboard');
    Route::get('/agencies', [AgencyDirectoryController::class, 'index'])->name('agencies.index');
    Route::get('/agencies/{user}', [AgencyDirectoryController::class, 'show'])->name('agencies.show');

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{destination}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    

    // Memories
    Route::get('/memories', [MemoryController::class, 'index'])->name('memories.index');
    Route::post('/memories', [MemoryController::class, 'store'])->name('memories.store');
    Route::patch('/memories/albums/{album}', [MemoryController::class, 'updateAlbum'])->name('memories.albums.update');
    Route::delete('/memories/albums/{album}', [MemoryController::class, 'destroyAlbum'])->name('memories.albums.destroy');
    Route::delete('/memories/{memory}', [MemoryController::class, 'destroy'])->name('memories.destroy');


// Backpack Routes
Route::middleware(['auth'])->prefix('backpack')->name('backpack.')->group(function () {
    Route::get('/', [BackpackController::class, 'index'])->name('index');
    Route::post('/', [BackpackController::class, 'store'])->name('store');
    Route::patch('/{item}/toggle', [BackpackController::class, 'toggle'])->name('toggle');
    Route::delete('/{item}', [BackpackController::class, 'destroy'])->name('destroy');
    Route::delete('/clear-checked', [BackpackController::class, 'clearChecked'])->name('clear-checked');
    Route::post('/add-category', [BackpackController::class, 'addCategory'])->name('add-category');
    Route::post('/add-group', [BackpackController::class, 'addGroup'])->name('add-group');
    Route::patch('/group/{group}', [BackpackController::class, 'updateGroup'])->name('update-group');
    Route::delete('/category/{category}', [BackpackController::class, 'deleteCategory'])->name('delete-category');
    Route::delete('/group/{group}', [BackpackController::class, 'deleteGroup'])->name('delete-group');
});
    // Inquiries
    Route::get('/my-inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::post('/inquiries/{package}', [InquiryController::class, 'store'])->name('inquiries.store');

    // Feedback
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Agency routes
Route::middleware(['auth', 'agency'])->prefix('agency')->name('agency.')->group(function () {
    Route::get('/dashboard', [AgencyDashboardController::class, 'index'])->name('dashboard');
    Route::get('/destinations/create', [AgencyDashboardController::class, 'createDestinationRequest'])->name('destinations.create');
    Route::post('/destinations', [AgencyDashboardController::class, 'storeDestinationRequest'])->name('destinations.store');

    Route::resource('packages', TourPackageController::class)->except(['show']);
    
    Route::get('/inquiries', [AgencyInquiryController::class, 'index'])->name('inquiries.index');
    Route::patch('/inquiries/{inquiry}/status', [AgencyInquiryController::class, 'updateStatus'])->name('inquiries.status');
});

Route::middleware('auth')->get('/agency/pending', [AgencyDashboardController::class, 'pending'])->name('agency.pending');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/verify-agency', [UserManagementController::class, 'verifyAgency'])->name('users.verify-agency');
    Route::patch('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');

    // Destinations
    Route::get('/destinations', [AdminDestinationController::class, 'index'])->name('destinations.index');
    Route::get('/destinations/create', [AdminDestinationController::class, 'create'])->name('destinations.create');
    Route::post('/destinations', [AdminDestinationController::class, 'store'])->name('destinations.store');
    Route::get('/destinations/{destination}/edit', [AdminDestinationController::class, 'edit'])->name('destinations.edit');
    Route::put('/destinations/{destination}', [AdminDestinationController::class, 'update'])->name('destinations.update');
    Route::patch('/destinations/{destination}/toggle', [AdminDestinationController::class, 'toggleApproval'])->name('destinations.toggle');
    Route::post('/destination-requests/{destinationRequest}/review', [AdminDestinationController::class, 'reviewRequest'])->name('destination-requests.review');

    // Feedback moderation
    Route::get('/feedback', [FeedbackModerationController::class, 'index'])->name('feedback.index');
    Route::patch('/feedback/{feedback}', [FeedbackModerationController::class, 'update'])->name('feedback.update');

    // Agency directory
    Route::get('/agencies', [AdminAgencyDirectoryController::class, 'index'])->name('agencies.index');
    Route::get('/agencies/{user}', [AdminAgencyDirectoryController::class, 'show'])->name('agencies.show');
});
