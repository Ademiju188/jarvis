<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VisitController;
use App\Http\Controllers\Api\SchoolController;

// Main API routes
Route::prefix('api')->group(function () {
    // Schools
    Route::get('/schools', [SchoolController::class, 'index']);
    Route::post('/schools', [SchoolController::class, 'store']);
    Route::get('/schools/{school}', [SchoolController::class, 'show']);
    Route::put('/schools/{school}', [SchoolController::class, 'update']);
    Route::delete('/schools/{school}', [SchoolController::class, 'destroy']);

    // Visits
    Route::get('/visits', [VisitController::class, 'index']);
    Route::post('/visits', [VisitController::class, 'store']);
    Route::get('/visits/{visit}', [VisitController::class, 'show']);
    Route::put('/visits/{visit}', [VisitController::class, 'update']);
    Route::delete('/visits/{visit}', [VisitController::class, 'destroy']);

    // Special routes
    Route::get('/visits/{visit}/pdf', [VisitController::class, 'generatePdf']);
    Route::get('/schools/token/{token}', [SchoolController::class, 'getByToken']);
    Route::get('/visits/share/{token}', [VisitController::class, 'getByShareToken']);
    Route::post('/visits/{token}/feedback', [VisitController::class, 'submitFeedback']);
});

// Catch-all route for Vue.js SPA - must be last
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$');
