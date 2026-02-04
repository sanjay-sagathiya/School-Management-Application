<?php

use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
	Route::get('/', function () {
		return view('dashboard');
	})->name('dashboard');

	Route::get('/profile', function () {
		return view('profile');
	})->name('profile');

	Route::resource('/teachers', TeacherController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);

	Route::resource('/announcements', AnnouncementController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);

	Route::get('/notifications', [NotificationController::class, 'notifications'])->name('notifications');
	Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});
