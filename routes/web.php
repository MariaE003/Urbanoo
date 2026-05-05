<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CitizenDashboardController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
})->name('home');
// reports
Route::get('/reports',[ReportController::class,'index'])->name('reports.index');
Route::post('/reports',[ReportController::class,'store'])->name('report')->middleware('auth');
Route::patch('/reports/{reportID}',[ReportController::class,'updateStatus'])->name('updateStatus')->middleware('auth');
Route::post('/reports/{reportID}', [ReportController::class, 'update'])->middleware('auth');
Route::delete('/reports/{reportID}', [ReportController::class, 'destroy'])->middleware('auth');
// last 3 days reports
Route::get('/reports/last', [ReportController::class, 'lastReports']);

// pour fetcher les category
Route::get('/categories', [CategoryController::class, 'listJson'])->name('categories.json');
Route::get('/services', [ServiceController::class, 'listJson'])->name('services.json');


// comments
Route::get('/reports/{reportId}/comments', [CommentController::class, 'commentByReport']);
Route::post('/comments', [CommentController::class, 'store'])->middleware('auth');
Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->middleware('auth');

//votes 
Route::post('/votes/toggle',[VoteController::class,'toggle'])->middleware('auth');
Route::get('/reports/{reportId}/votes/count',[VoteController::class,'countByReport']);


// la partie admin
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'statistique'])->name('admin.dashboard');
    Route::patch('/admin/users/{id}/toggle-status', [AdminDashboardController::class, 'toggleUserStatus'])->name('admin.users.toggleStatus');
});


// les notif
Route::middleware('auth')->group(function () {
    Route::get('/notifications/all', [NotificationController::class, 'all'])->name('notifications.all');
    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsReads'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

// crud categorie
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::get('/services', [ServiceController::class, 'index'])->name('admin.services.index');
    Route::post('/services', [ServiceController::class, 'store'])->name('admin.services.store');
    Route::put('/services/{id}', [ServiceController::class, 'update'])->name('admin.services.update');
    Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');
});

// dashboard citizen
Route::middleware('auth')->group(function () {
    Route::get('/citizen/dashboard', [CitizenDashboardController::class, 'index'])->name('citizen.dashboard');
});


// google
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);


// profil user
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';
