<?php

use App\Http\Controllers\{ CategoryController, DashboardController, InstallerController, ProductsController, PurchaseController, SettingsController, SupplierController };
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public & Guest Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('welcome'));

Route::middleware(['installed'])->group(function () {
    Route::get('/login', fn() => view('auth.login'))->name('login');
    
    Route::post('/login', function (Request $request) {
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->intended('/dashboard');
        }
        return back()->with('error', 'Email atau password salah');
    });
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Installer Routes
|--------------------------------------------------------------------------
*/

Route::prefix('install')->name('installer.')->group(function () {
    
    // Step 1: Database Configuration
    Route::get('/database', [InstallerController::class, 'database'])->name('database');
    Route::post('/database', [InstallerController::class, 'storeDatabase'])->name('storeDatabase');

    // Step 2: Store Information
    Route::get('/store', [InstallerController::class, 'store'])->name('store');
    Route::post('/store', [InstallerController::class, 'storeStore'])->name('storeStore');

    // Step 3: Admin Account
    Route::get('/admin', [InstallerController::class, 'admin'])->name('admin');
    Route::post('/admin', [InstallerController::class, 'storeAdmin'])->name('storeAdmin');

    // Step 4: Progress View
    Route::get('/final', [InstallerController::class, 'final'])->name('install');

    // Step 5: Ajax Process
    Route::get('/process', [InstallerController::class, 'install'])->name('install_process');
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Main App)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'installed', 'auto-migrate'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    
    Route::post('promos/check', [PromoController::class, 'checkPromo'])->name('promos.check');

    // Resource Controllers
    Route::resource('products', ProductsController::class);
    Route::resource('categories', CategoryController::class)->names('category');
    Route::resource('suppliers', SupplierController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::resource('sales', SalesController::class);
    Route::resource('expenses', ExpensesController::class);
    Route::resource('promos', PromoController::class);
    Route::resource('users', UserController::class);
    

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
});