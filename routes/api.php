<?php

use Illuminate\Http\Request;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DeliveryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('/logout-store', function () {
    Auth::guard('web')->logout();
    request()->session()->regenerateToken();
    return response()->json(['message' => 'Logged out']);
});

Route::post('/invoice-webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
Route::put('/delivery-webhook', [DeliveryController::class, 'webhook'])->name('delivery.webhook');

// Route::get('/test-email', function () {
//     Mail::raw('This is a test email', function ($message) {
//         $message->to('fernandeswiraharjo@gmail.com')
//             ->subject('Test Email');
//     });

//     return 'Email sent!';
// });
