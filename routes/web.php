<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\TonerController;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
    Route::get('logout', [TonerController::class, 'logout']);

    // Apply 'checkMenuAccess' middleware to a specific group of routes
    Route::middleware(['menu.access'])->group(function () {
        Route::get('/role', [RoleController::class, 'index'])->name('index-role');
    });
    Route::get('/role/list', [RoleController::class, 'getRoles'])->name('list-roles');
    Route::get('/role/{id}', [RoleController::class, 'getById'])->name('edit-role');
    Route::post('/role', [RoleController::class, 'store'])->name('store-role');
    Route::put('/role/{id}', [RoleController::class, 'update'])->name('update-role');
    Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('delete-role');

    Route::get('{any}', [TonerController::class, 'index']);
    Route::get('components/{any}', [TonerController::class, 'components']);
});

