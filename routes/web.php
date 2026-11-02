<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Owner\BookingManageController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\PropertyManageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Public Home & Catalog
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kontrakan', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/kontrakan/{slug}', [PropertyController::class, 'show'])->name('properties.show');

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/quick-login/{role}', [AuthController::class, 'quickLogin'])->name('quick-login');

// Tenant Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/booking/{property:slug}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/booking/{property:slug}', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/pesanan-saya', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/pesanan-saya/{booking_code}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/pesanan-saya/{booking}/bukti-bayar', [BookingController::class, 'uploadPaymentProof'])->name('bookings.upload-proof');
    Route::post('/pesanan-saya/{booking}/batal', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/kontrakan/{property:slug}/review', [ReviewController::class, 'store'])->name('reviews.store');

    // Profile Management
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Owner & Admin Protected Routes
Route::middleware(['auth', 'owner'])->prefix('pemilik')->name('owner.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Property Management
    Route::get('/kontrakan', [PropertyManageController::class, 'index'])->name('properties.index');
    Route::get('/kontrakan/tambah', [PropertyManageController::class, 'create'])->name('properties.create');
    Route::post('/kontrakan', [PropertyManageController::class, 'store'])->name('properties.store');
    Route::get('/kontrakan/{property}/edit', [PropertyManageController::class, 'edit'])->name('properties.edit');
    Route::put('/kontrakan/{property}', [PropertyManageController::class, 'update'])->name('properties.update');
    Route::post('/kontrakan/{property}/status', [PropertyManageController::class, 'toggleAvailability'])->name('properties.toggle');
    Route::delete('/kontrakan/{property}', [PropertyManageController::class, 'destroy'])->name('properties.destroy');

    // Booking Requests Management
    Route::get('/permohonan-booking', [BookingManageController::class, 'index'])->name('bookings.index');
    Route::post('/permohonan-booking/{booking}/setujui', [BookingManageController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/permohonan-booking/{booking}/verifikasi-bayar', [BookingManageController::class, 'verifyPayment'])->name('bookings.verify-payment');
    Route::post('/permohonan-booking/{booking}/tolak', [BookingManageController::class, 'reject'])->name('bookings.reject');
    Route::post('/permohonan-booking/{booking}/selesaikan', [BookingManageController::class, 'complete'])->name('bookings.complete');
});
