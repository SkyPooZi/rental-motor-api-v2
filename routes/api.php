<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(\App\Http\Controllers\AuthController::class)->name('auth.')->prefix('auth')->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::post('/register', 'register')->name('register');
    Route::get('/logout', 'logout')->name('logout');
    Route::post('/send-otp', 'sendOtp')->name('sendOtp');
    Route::post('/send-otp-new-email', 'sendOtpNewEmail')->name('sendOtpNewEmail');
    Route::post('/verify-otp-new-email', 'verifyOtpNewEmail')->name('verifyOtpNewEmail');
});

Route::get('/image/{context}/', \App\Http\Controllers\ImageGetterController::class);

Route::middleware(['web'])->group(function () {
    Route::controller(\App\Http\Controllers\AuthController::class)->name('login.')->prefix('login')->group(function () {
        Route::get('/google', 'redirectToGoogle')->name('redirectToGoogle');
        Route::get('/google/callback', 'handleGoogleCallback')->name('handleGoogleCallback');
        Route::get('/facebook', 'redirectToFacebook')->name('redirectToFacebook');
        Route::get('/facebook/callback', 'handleFacebookCallback')->name('handleFacebookCallback');
    });
}); 

Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(\App\Http\Controllers\DiscountController::class)->name('discount.')->prefix('discount')->group(function () {
        Route::get('/all', 'getAllWithSearch')->name('getAllWithSearch');
        Route::get('/detail/{id}', 'findById')->name('findById');
        Route::post('/create', 'createData')->name('createData');
        Route::update('/edit/{id}', 'updateData')->name('updateData');
        Route::delete('/delete/{id}', 'deleteData')->name('deleteData');
    });

    Route::controller(\App\Http\Controllers\ReviewController::class)->name('review.')->prefix('review')->group(function () {
        Route::get('/all', 'getAllWithSearch')->name('getAllWithSearch');
        Route::get('/detail/{id}', 'findById')->name('findById');
        Route::post('/create', 'createData')->name('createData');
        Route::update('/edit/{id}', 'updateData')->name('updateData');
        Route::delete('/delete/{id}', 'deleteData')->name('deleteData');
    });

    Route::controller(\App\Http\Controllers\FinanceController::class)->name('finance.')->prefix('finance')->group(function () {
        Route::get('/all', 'getAllWithSearch')->name('getAllWithSearch');
        Route::get('/detail/{id}', 'findById')->name('findById');
        Route::post('/create', 'createData')->name('createData');
        Route::update('/edit/{id}', 'updateData')->name('updateData');
        Route::delete('/delete/{id}', 'deleteData')->name('deleteData');
    });

    Route::controller(\App\Http\Controllers\HistoryController::class)->name('history.')->prefix('history')->group(function () {
        Route::get('/all', 'getAllWithSearch')->name('getAllWithSearch');
        Route::get('/detail/{id}', 'findById')->name('findById');
        Route::post('/create', 'createData')->name('createData');
        Route::update('/edit/{id}', 'updateData')->name('updateData');
        Route::delete('/delete/{id}', 'deleteData')->name('deleteData');
    });

    Route::controller(\App\Http\Controllers\MotorcycleListController::class)->name('motorcycleList.')->prefix('motorcycleList')->group(function () {
        Route::get('/all', 'getAllWithSearch')->name('getAllWithSearch');
        Route::get('/detail/{id}', 'findById')->name('findById');
        Route::post('/create', 'createData')->name('createData');
        Route::update('/edit/{id}', 'updateData')->name('updateData');
        Route::delete('/delete/{id}', 'deleteData')->name('deleteData');
    });

    Route::controller(\App\Http\Controllers\NotificationController::class)->name('notification.')->prefix('notification')->group(function () {
        Route::get('/all', 'getAllWithSearch')->name('getAllWithSearch');
        Route::get('/detail/{id}', 'findById')->name('findById');
        Route::post('/create', 'createData')->name('createData');
        Route::update('/edit/{id}', 'updateData')->name('updateData');
        Route::delete('/delete/{id}', 'deleteData')->name('deleteData');
    });

    Route::controller(\App\Http\Controllers\ChangeLogController::class)->name('changeLog.')->prefix('changeLog')->group(function () {
        Route::get('/all', 'getAllWithSearch')->name('getAllWithSearch');
        Route::get('/detail/{id}', 'findById')->name('findById');
        Route::post('/create', 'createData')->name('createData');
        Route::update('/edit/{id}', 'updateData')->name('updateData');
        Route::delete('/delete/{id}', 'deleteData')->name('deleteData');
    });

    Route::controller(\App\Http\Controllers\PaymentGatewayController::class)->name('paymentGateway.')->prefix('paymentGateway')->group(function () {
        Route::get('/all', 'getAllWithSearch')->name('getAllWithSearch');
        Route::get('/detail/{id}', 'findById')->name('findById');
        Route::post('/create', 'createData')->name('createData');
        Route::update('/edit/{id}', 'updateData')->name('updateData');
        Route::delete('/delete/{id}', 'deleteData')->name('deleteData');
    });

    Route::controller(\App\Http\Controllers\PaymentNotificationController::class)->name('paymentNotification.')->prefix('paymentNotification')->group(function () {
        Route::get('/all', 'getAllWithSearch')->name('getAllWithSearch');
        Route::get('/detail/{id}', 'findById')->name('findById');
        Route::post('/create', 'createData')->name('createData');
        Route::update('/edit/{id}', 'updateData')->name('updateData');
        Route::delete('/delete/{id}', 'deleteData')->name('deleteData');
    });
});
