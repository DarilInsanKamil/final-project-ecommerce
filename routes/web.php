<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // User Profile
    Route::get('/profile', [\App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');
    Route::put('/profile', [\App\Http\Controllers\UserController::class, 'updateProfile'])->name('user.profile.update');

    // Booking Wizard (auth required)
    Route::get('/book', [BookingController::class, 'createWizard'])->name('booking.wizard');
    Route::post('/book/slots', [BookingController::class, 'getAvailableSlots'])->name('booking.slots');
    Route::post('/book', [BookingController::class, 'store'])->name('booking.store');

    // Payments
    Route::get('/payment/{booking}/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/{booking}/check', [PaymentController::class, 'checkStatus'])->name('payment.check');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services');

// Webhook (no auth, external callback)
Route::post('/webhook/pak-kasir', [PaymentController::class, 'webhook'])->name('webhook.pakkasir');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // For MVP, skipping complex auth guard middleware, just basic layout simulation 
    // or standard auth middleware if users use Auth::routes()
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/services', [AdminController::class, 'services'])->name('services');
    Route::post('/services', [AdminController::class, 'storeService'])->name('services.store');
    Route::put('/services/{service}', [AdminController::class, 'updateService'])->name('services.update');
    Route::delete('/services/{service}', [AdminController::class, 'deleteService'])->name('services.destroy');
    
    Route::get('/staff', [AdminController::class, 'staff'])->name('staff');
    Route::post('/staff', [AdminController::class, 'storeStaff'])->name('staff.store');
    Route::put('/staff/{staff}', [AdminController::class, 'updateStaff'])->name('staff.update');
    Route::delete('/staff/{staff}', [AdminController::class, 'deleteStaff'])->name('staff.destroy');
    Route::get('/staff/{staff}/schedule', [AdminController::class, 'getStaffSchedule'])->name('staff.schedule.get');
    Route::put('/staff/{staff}/schedule', [AdminController::class, 'updateStaffSchedule'])->name('staff.schedule.update');
    
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::patch('/bookings/{booking}/status', [AdminController::class, 'updateBookingStatus'])->name('bookings.status');

    // Admin User Creation (Staff / Admin)
    Route::get('/users/create', [AuthController::class, 'showAdminRegister'])->name('users.create');
    Route::post('/users/create', [AuthController::class, 'adminRegister'])->name('users.store');
});
