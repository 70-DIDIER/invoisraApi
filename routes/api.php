<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentItemController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::get('auth/google/redirect', [SocialiteController::class, 'redirect']);
    Route::get('auth/google/callback', [SocialiteController::class, 'callback']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);

        Route::get('company', [CompanyController::class, 'show']);
        Route::post('company', [CompanyController::class, 'store']);
        Route::put('company', [CompanyController::class, 'update']);

        Route::post('company/upload', [FileController::class, 'upload']);
        Route::delete('company/upload/{type}', [FileController::class, 'destroy']);

        Route::apiResource('clients', ClientController::class);

        Route::apiResource('documents', DocumentController::class);

        Route::get('documents/{document}/download', [DocumentController::class, 'download']);
        Route::post('documents/{document}/email', [DocumentController::class, 'email']);

        Route::get('documents/{document}/items', [DocumentItemController::class, 'index']);
        Route::post('documents/{document}/items', [DocumentItemController::class, 'store']);
        Route::get('documents/{document}/items/{item}', [DocumentItemController::class, 'show']);
        Route::put('documents/{document}/items/{item}', [DocumentItemController::class, 'update']);
        Route::delete('documents/{document}/items/{item}', [DocumentItemController::class, 'destroy']);
    });
});
