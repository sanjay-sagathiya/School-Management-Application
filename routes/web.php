<?php

use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
	Route::get('/', function () {
		return view('dashboard');
	})->name('dashboard');

	Route::get('/profile', function () {
		return view('profile');
	})->name('profile');

	Route::resource('/teachers', TeacherController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);
});
