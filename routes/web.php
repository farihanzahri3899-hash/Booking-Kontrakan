<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\Owner\BookingManageController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\NotificationController;
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

// Authenticated User Routes (Tenants & General)
Route::middleware('auth')->group(function () {
    // Bookings
    Route::get('/booking/{property:slug}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/booking/{property:slug}', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/pesanan-saya', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/pesanan-saya/{booking_code}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/pesanan-saya/{booking}/bukti-bayar', [BookingController::class, 'uploadPaymentProof'])->name('bookings.upload-proof');
    Route::post('/pesanan-saya/{booking}/batal', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/kontrakan/{property:slug}/review', [ReviewController::class, 'store'])->name('reviews.store');

    // Wishlist / Favorit
    Route::get('/favorit', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorit/{property}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // Tenant Complaints / Laporan Kendala
    Route::get('/laporan-saya', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/laporan-saya/buat', [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/laporan-saya', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/laporan-saya/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');

    // In-App Chat / Messaging
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/kirim', [ChatController::class, 'send'])->name('chat.send');
    Route::post('/chat/mulai/{property}', [ChatController::class, 'start'])->name('chat.start');
    Route::get('/chat/{conversation}/messages', [ChatController::class, 'getMessages'])->name('chat.messages');

    // Digital KYC / Verifikasi Identitas
    Route::get('/profil/verifikasi', [KycController::class, 'index'])->name('kyc.index');
    Route::post('/profil/verifikasi', [KycController::class, 'submit'])->name('kyc.submit');

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

    // Owner Reply to Review
    Route::post('/ulasan/{review}/balas', [ReviewController::class, 'reply'])->name('reviews.reply');

    // Owner Complaint Management
    Route::get('/laporan', [ComplaintController::class, 'ownerIndex'])->name('complaints.index');
    Route::get('/laporan/{complaint}', [ComplaintController::class, 'ownerShow'])->name('complaints.show');
    Route::post('/laporan/{complaint}/tanggapi', [ComplaintController::class, 'ownerRespond'])->name('complaints.respond');

    // Notifications Management
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifikasi/{id}/baca', [NotificationController::class, 'markAsReadAndRedirect'])->name('notifications.read-redirect');
    Route::post('/notifikasi/{id}/baca', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifikasi/baca-semua', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifikasi/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// Admin Exclusive Protected Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/users/{user}/grant-owner', [AdminDashboardController::class, 'grantOwner'])->name('users.grant-owner');
    Route::post('/users/{user}/revoke-owner', [AdminDashboardController::class, 'revokeOwner'])->name('users.revoke-owner');
    Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('users.destroy');

    // Admin KYC Management
    Route::get('/kyc', [AdminDashboardController::class, 'kycIndex'])->name('kyc.index');
    Route::post('/kyc/{user}/approve', [AdminDashboardController::class, 'approveKyc'])->name('kyc.approve');
    Route::post('/kyc/{user}/reject', [AdminDashboardController::class, 'rejectKyc'])->name('kyc.reject');

    // Admin Sync Notifications
    Route::post('/sync-notifications', [AdminDashboardController::class, 'syncNotifications'])->name('sync-notifications');
});
