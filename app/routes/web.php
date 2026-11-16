<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('postLogin');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendLink'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/api/locations', [LocationController::class, 'datatable'])
        ->name('api.locations.index');
    Route::resource('locations', LocationController::class)->except(['show']);
});