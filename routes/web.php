<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\InvoiceController as CustomerInvoiceController;
use App\Http\Controllers\Customer\MerchantController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Merchant\DashboardController as MerchantDashboardController;
use App\Http\Controllers\Merchant\FoodItemController;
use App\Http\Controllers\Merchant\InvoiceController as MerchantInvoiceController;
use App\Http\Controllers\Merchant\OrderController as MerchantOrderController;
use App\Http\Controllers\Merchant\ProfileController as MerchantProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration routes
Route::get('/register/merchant', [RegisterController::class, 'showMerchantRegistrationForm'])->name('register.merchant')->middleware('guest');
Route::post('/register/merchant', [RegisterController::class, 'registerMerchant'])->name('register.merchant.submit')->middleware('guest');
Route::get('/register/customer', [RegisterController::class, 'showCustomerRegistrationForm'])->name('register.customer')->middleware('guest');
Route::post('/register/customer', [RegisterController::class, 'registerCustomer'])->name('register.customer.submit')->middleware('guest');

// Merchant routes
Route::prefix('merchant')->middleware(['auth', 'role:merchant'])->name('merchant.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [MerchantDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [MerchantProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [MerchantProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [MerchantProfileController::class, 'update'])->name('profile.update');

    // Food Items
    Route::resource('food-items', FoodItemController::class);

    // Orders
    Route::get('/orders', [MerchantOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [MerchantOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [MerchantOrderController::class, 'updateStatus'])->name('orders.update-status');

    // Invoices
    Route::get('/invoices', [MerchantInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [MerchantInvoiceController::class, 'show'])->name('invoices.show');
    Route::put('/invoices/{invoice}/status', [MerchantInvoiceController::class, 'updateStatus'])->name('invoices.update-status');
});

// Customer routes
Route::prefix('customer')->middleware(['auth', 'role:customer'])->name('customer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [CustomerProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [CustomerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');

    // Merchants
    Route::get('/merchants', [MerchantController::class, 'index'])->name('merchants.index');
    Route::get('/merchants/{merchant}', [MerchantController::class, 'show'])->name('merchants.show');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Orders
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [CustomerOrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [CustomerOrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');

    // Invoices
    Route::get('/invoices', [CustomerInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [CustomerInvoiceController::class, 'show'])->name('invoices.show');
    Route::put('/invoices/{invoice}/mark-as-paid', [CustomerInvoiceController::class, 'markAsPaid'])->name('invoices.mark-as-paid');
});