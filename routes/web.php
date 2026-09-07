<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PickupRequestController as AdminPickupRequestController;
use App\Http\Controllers\Admin\PointExchangeController as AdminPointExchangeController;
use App\Http\Controllers\Admin\RewardController as AdminRewardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\WasteCategoryController as AdminWasteCategoryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Collector\DashboardController as CollectorDashboardController;
use App\Http\Controllers\Collector\PickupRequestController as CollectorPickupRequestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Resident\DashboardController as ResidentDashboardController;
use App\Http\Controllers\Resident\NewsController;
use App\Http\Controllers\Resident\NotificationController;
use App\Http\Controllers\Resident\PickupRequestController;
use App\Http\Controllers\Resident\PointExchangeController;
use App\Http\Controllers\Resident\PointHistoryController;
use App\Http\Controllers\Resident\RewardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| Bisa diakses semua orang termasuk Pengunjung (Guest) tanpa login.
| User yang sudah login diarahkan ke dashboard sesuai rolenya.
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Guest Only Routes (Login & Register)
|--------------------------------------------------------------------------
| Registrasi publik HANYA untuk role Resident. Admin & Collector dibuat
| lewat Seeder (lihat database/seeders/AdminSeeder.php & CollectorSeeder.php).
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Resident Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:resident'])->prefix('resident')->name('resident.')->group(function () {
    Route::get('/', [ResidentDashboardController::class, 'index'])->name('dashboard');

    Route::get('/pickup-requests', [PickupRequestController::class, 'index'])->name('pickup-requests.index');
    Route::get('/pickup-requests/create', [PickupRequestController::class, 'create'])->name('pickup-requests.create');
    Route::post('/pickup-requests', [PickupRequestController::class, 'store'])->name('pickup-requests.store');
    Route::get('/pickup-requests/{pickupRequest}', [PickupRequestController::class, 'show'])->name('pickup-requests.show');
    Route::delete('/pickup-requests/{pickupRequest}', [PickupRequestController::class, 'destroy'])->name('pickup-requests.destroy');

    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::get('/rewards/saldo', [RewardController::class, 'saldo'])->name('rewards.saldo');
    Route::get('/rewards/barang', [RewardController::class, 'barang'])->name('rewards.barang');
    Route::post('/rewards/{reward}/exchange', [PointExchangeController::class, 'store'])->name('rewards.exchange');
    Route::get('/point-exchanges', [PointExchangeController::class, 'index'])->name('point-exchanges.index');

    Route::get('/point-histories', [PointHistoryController::class, 'index'])->name('point-histories.index');

    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('waste-categories', AdminWasteCategoryController::class);
    Route::resource('rewards', AdminRewardController::class);

    Route::resource('pickup-requests', AdminPickupRequestController::class)->only(['index', 'show']);
    Route::patch('pickup-requests/{pickupRequest}/approve', [AdminPickupRequestController::class, 'approve'])->name('pickup-requests.approve');
    Route::patch('pickup-requests/{pickupRequest}/reject', [AdminPickupRequestController::class, 'reject'])->name('pickup-requests.reject');

    Route::resource('point-exchanges', AdminPointExchangeController::class)->only(['index']);
    Route::patch('point-exchanges/{pointExchange}/approve', [AdminPointExchangeController::class, 'approve'])->name('point-exchanges.approve');
    Route::patch('point-exchanges/{pointExchange}/reject', [AdminPointExchangeController::class, 'reject'])->name('point-exchanges.reject');

    Route::resource('news', AdminNewsController::class);
    Route::resource('users', AdminUserController::class)->only(['index', 'show']);
});

/*
|--------------------------------------------------------------------------
| Collector Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:collector'])->prefix('collector')->name('collector.')->group(function () {
    Route::get('/', [CollectorDashboardController::class, 'index'])->name('dashboard');

    Route::get('/pickup-requests', [CollectorPickupRequestController::class, 'index'])->name('pickup-requests.index');
    Route::get('/pickup-requests/{pickupRequest}', [CollectorPickupRequestController::class, 'show'])->name('pickup-requests.show');
    Route::patch('/pickup-requests/{pickupRequest}/status', [CollectorPickupRequestController::class, 'updateStatus'])->name('pickup-requests.status');

    Route::get('/history', [CollectorPickupRequestController::class, 'history'])->name('history');
});
