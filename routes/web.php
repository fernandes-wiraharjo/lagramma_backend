<?php

use App\Http\Controllers\OTPController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ModifierController;
use App\Http\Controllers\SalesTypeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoleMenuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TonerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HamperSettingController;
use Illuminate\Support\Facades\Route;

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

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

Auth::routes();

Route::get('/register-otp-verify', [OTPController::class, 'showVerifyForm'])->name('register.otp.verify');
Route::post('/register-otp-verify', [OTPController::class, 'verify'])->name('register.otp.verify.submit');
Route::post('/register-otp-resend', [OTPController::class, 'resendOtp'])->name('register.otp.resend');
Route::get('/login-otp-verify', [OTPController::class, 'showLoginOtpForm'])->name('login.otp.form');
Route::post('/login-otp-verify', [OTPController::class, 'verifyOtpLogin'])->name('login.otp.verify');
Route::post('/login-otp-resend', [OTPController::class, 'resendOtpLogin'])->name('login.otp.resend');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
    Route::get('logout', [TonerController::class, 'logout']);

    // Apply 'checkMenuAccess' middleware to a specific group of routes
    Route::middleware(['menu.access'])->group(function () {
        Route::get('/role', [RoleController::class, 'index'])->name('index-role');
        Route::get('/role-menu', [RoleMenuController::class, 'index'])->name('index-role-menu');
        Route::get('/user', [UserController::class, 'index'])->name('index-user');
        Route::get('/category', [CategoryController::class, 'index'])->name('index-category');
        Route::get('/modifier', [ModifierController::class, 'index'])->name('index-modifier');
        Route::get('/modifier-option', [ModifierController::class, 'indexModifierOption'])->name('index-modifier-option');
        Route::get('/sales-type', [SalesTypeController::class, 'index'])->name('index-sales-type');
        Route::get('/product', [ProductController::class, 'index'])->name('index-product');
        Route::get('/hampers-setting', [ProductController::class, 'indexHamperSetting']);
        Route::get('/my-account', [AccountController::class, 'index'])->name('index-my-account');
        Route::get('/account-setting', [AccountController::class, 'indexSetting'])->name('index-setting-account');
    });

    //role
    Route::get('/role/list', [RoleController::class, 'getRoles'])->name('list-role');
    Route::get('/role/{id}', [RoleController::class, 'getById'])->name('edit-role');
    Route::post('/role', [RoleController::class, 'store'])->name('store-role');
    Route::put('/role/{id}', [RoleController::class, 'update'])->name('update-role');
    Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('delete-role');

    //role menu
    Route::get('/role-menu/list', [RoleMenuController::class, 'get'])->name('list-role-menu');
    Route::get('/role-menu/{roleId}', [RoleMenuController::class, 'getByRoleId'])->name('edit-role-menu');
    Route::post('/role-menu', [RoleMenuController::class, 'store'])->name('store-role-menu');
    Route::put('/role-menu/{roleId}', [RoleMenuController::class, 'update'])->name('update-role-menu');
    Route::delete('/role-menu/{roleId}', [RoleMenuController::class, 'destroy'])->name('delete-role-menu');

    //user
    Route::get('/user/list', [UserController::class, 'get'])->name('list-user');
    Route::get('/user/{id}', [UserController::class, 'edit'])->name('edit-user');
    Route::post('/user', [UserController::class, 'store'])->name('store-user');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('update-user');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('delete-user');

    //category
    Route::get('/category/list', [CategoryController::class, 'get'])->name('list-category');
    Route::post('/category/sync', [CategoryController::class, 'sync'])->name('sync-category');
    Route::post('/category/{id}/toggle-active', [CategoryController::class, 'toggleActive']);

    //modifier and modifier option
    Route::get('/modifier/list', [ModifierController::class, 'get'])->name('list-modifier');
    Route::post('/modifier/sync', [ModifierController::class, 'sync'])->name('sync-modifier');
    Route::post('/modifier/{id}/toggle-active', [ModifierController::class, 'toggleActive']);
    Route::get('/modifier-option/list', [ModifierController::class, 'getModifierOption'])->name('list-modifier-option');
    Route::post('/modifier-option/{id}/toggle-active', [ModifierController::class, 'toggleActiveModifierOption']);

    //sales type
    Route::get('/sales-type/list', [SalesTypeController::class, 'get'])->name('list-sales-type');
    Route::post('/sales-type/sync', [SalesTypeController::class, 'sync'])->name('sync-sales-type');
    Route::post('/sales-type/{id}/toggle-active', [SalesTypeController::class, 'toggleActive']);

    //product
    Route::get('/product/list', [ProductController::class, 'get'])->name('list-product');
    Route::post('/product/sync', [ProductController::class, 'sync'])->name('sync-product');
    Route::post('/product/{id}/toggle-active', [ProductController::class, 'toggleActive']);
    Route::post('/product/{id}/update', [ProductController::class, 'update'])->name('update-product');

    //product deactivate by date
    Route::prefix('product-deactivate-by-date')->group(function () {
        Route::get('{idProduct}', [ProductController::class, 'indexDeactivateDate']);
        Route::get('{idProduct}/list', [ProductController::class, 'getDeactivateDate']);
        Route::get('by-id/{id}', [ProductController::class, 'getDeactivateDateById']);
        Route::post('{idProduct}', [ProductController::class, 'storeDeactivateDate']);
        Route::put('{id}', [ProductController::class, 'updateDeactivateDate']);
        Route::delete('{id}', [ProductController::class, 'destroyDeactivateDate']);
    });

    //product image
    Route::prefix('product-image')->group(function () {
        Route::get('{idProduct}', [ProductController::class, 'indexImage']);
        Route::post('{idProduct}', [ProductController::class, 'storeImage']);
        Route::post('set-main/{id}', [ProductController::class, 'setMainImage']);
        Route::delete('{id}', [ProductController::class, 'destroyImage']);
    });

    //product variant
    Route::get('/product-variant/{idProduct}', [ProductController::class, 'indexVariant'])->name('index-product-variant');
    Route::get('/product-variant/{idProduct}/list', [ProductController::class, 'getVariant'])->name('list-product-variant');
    Route::post('/product-variant/{id}/toggle-active', [ProductController::class, 'toggleActiveVariant']);

    Route::prefix('hampers-setting')->group(function () {
        Route::get('/list', [ProductController::class, 'getHamperSetting']);
        Route::get('/{id}', [ProductController::class, 'editHamperSetting']);
        Route::post('/', [ProductController::class, 'storeHamperSetting']);
        Route::put('/{id}', [ProductController::class, 'updateHamperSetting']);
        Route::delete('/{id}', [ProductController::class, 'destroyHamperSetting']);
    });

    Route::prefix('account')->group(function () {
        Route::get('/komerce/search-region', [AccountController::class, 'searchRegion']);
        Route::post('/', [AccountController::class, 'update'])->name('account.update');
        Route::post('/addresses', [AccountController::class, 'storeAddress'])->name('addresses.store');
        Route::get('/addresses/{userAddress}', [AccountController::class, 'editAddress'])->name('addresses.edit');
        Route::put('/addresses/{userAddress}', [AccountController::class, 'updateAddress'])->name('addresses.update');
        Route::delete('/addresses/{userAddress}', [AccountController::class, 'destroyAddress'])->name('addresses.destroy');
    });

    //template
    Route::get('{any}', [TonerController::class, 'index']);
    Route::get('components/{any}', [TonerController::class, 'components']);
});

