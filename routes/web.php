<?php

use Inertia\Inertia;
use App\Models\Child;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\GrowthController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FoodPlannerController;
use App\Http\Controllers\AntropometriApiController;
use App\Http\Controllers\NutritionController;

// =========================================
// Public Routes
// =========================================
Route::inertia('/', 'Home')->name('home');

// News Routes
Route::controller(NewsController::class)->group(function () {
    Route::get('/news', 'index')->name('article.index');
    Route::get('/news/{id}', 'show')->name('article.show');
});

// Location API Routes
Route::prefix('lokasi')->group(function () {
    Route::get('/kota/{id_provinsi}', [AuthController::class, 'getKota'])->name('lokasi.kota');
    Route::get('/kecamatan/{id_kota}', [AuthController::class, 'getKecamatan'])->name('lokasi.kecamatan');
    Route::get('/kelurahan/{id_kecamatan}', [AuthController::class, 'getKelurahan'])->name('lokasi.kelurahan');
});

// =========================================
// Guest Routes (Unauthenticated)
// =========================================
Route::middleware('guest')->group(function () {
    // Registration Routes
    Route::controller(AuthController::class)->group(function () {
        Route::get('/register', 'indexRegister')->name('register');
        Route::post('/register', 'register');
    });

    // Login Routes
    Route::controller(AuthController::class)->group(function () {
        Route::inertia('/login', 'Auth/Login')->name('login');
        Route::post('/login', 'login');
    });
});

// =========================================
// Authenticated Routes
// =========================================
Route::middleware('auth')->group(function () {
    // API Routes
    Route::get('/api/antropometri/{tipe}/{gender}', [AntropometriApiController::class, 'get'])->name('antropometri.get');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Growth Routes
    Route::get('/growth', [GrowthController::class, 'index'])->name('growth');

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Child Management Routes
    Route::controller(ChildController::class)->group(function () {
        Route::get('/child', 'index')->name('child.index');
        Route::get('/child/create', 'create')->name('child.create');
        Route::post('/child', 'store')->name('child.store');
        Route::get('/child/{child}/edit', 'edit')->name('child.edit');
        Route::put('/child/{child}', 'update')->name('child.update');
        Route::delete('/child/{child}', 'destroy')->name('child.destroy');
    });

    // Record Management Routes
    Route::controller(RecordController::class)->group(function () {
        Route::get('/records/{id_anak}', 'index')->name('records.index');
        Route::get('/records/create/{id_anak}', 'create')->name('records.create');
        Route::post('/records', 'store')->name('records.store');
        Route::get('/records/{record}/edit', 'edit')->name('records.edit');
        Route::put('/records/{id_tumbuhkembang}', 'update')->name('records.update');
        Route::delete('/records/{record}', 'destroy')->name('records.destroy');
    });

    // Nutrition Routes
    Route::controller(NutritionController::class)->group(function () {
        Route::get('/nutrition', 'index')->name('nutrition.index');
        Route::get('/nutrition/calculate-calories/{id_anak}', 'calculateCalories')->name('nutrition.calculate-calories');
    });

    // Z-Score Information Route
    Route::inertia('/zscore/info', 'ZScoreInfo')->name('zscore.info');
});
