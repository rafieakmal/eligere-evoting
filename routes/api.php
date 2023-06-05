<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UserController::class)->group(function () {
    Route::GET('/get-code', 'code');
    Route::GET('/check-phone', 'checkPhone');
    Route::POST('/registration', 'registration');
    Route::POST('/login-account', 'login');
    Route::POST('/reset-password', 'sendPasswordResetLink')->name('password.reset');
    Route::POST('/confirm-reset-password', 'reset')->name('password.request');
    Route::POST('/upload', 'upload');
    Route::POST('/ktp', 'ktp');
    Route::POST('/notification', 'notification');
    Route::POST('/send-code', 'sendCode');
});

Route::controller(VoteController::class)->group(function () {
    Route::GET('/vote/show-all', 'index');
    Route::POST('/vote/add-data', 'create');
    Route::POST('/vote/insert-vote', 'insert');
    Route::POST('/vote/delete', 'delete');
    Route::POST('/vote/insert-password', 'insertPassword');
    Route::POST('/vote/add-options', 'options');
    Route::GET('/vote/update-option/{id}', 'updateOption');
    Route::GET('/vote/find/{slug}', 'showData');
    Route::GET('/vote/show-passwords/{slug}', 'showPasswords');
    Route::GET('/vote/data/u/{id}', 'getData');
    Route::GET('/vote/show-options/{slug}', 'showOptions');
    Route::GET('/vote/check-user/{user}/{slug}', 'checkUser');
    Route::GET('/my-ip', 'ip');
});
