<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\VisitController;

// CSRF token route
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});

// Authentication routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Headmaster registration route
Route::post('/headmaster/register', [AuthController::class, 'headmasterRegister']);

// Create admin user (remove this route after creating admin)
Route::get('/create-admin', [AuthController::class, 'createAdmin']);

// Public routes (no authentication required)
Route::get('/schools/token/{token}', [SchoolController::class, 'getByToken'])->name('school.access');
Route::post('/visits/{visit}/feedback', [VisitController::class, 'submitFeedback'])->name('visit.feedback');
Route::get('/visits/{visit}/pdf', [VisitController::class, 'generatePdf'])->name('visit.pdf');

// Protected routes (both admin and headmaster)
Route::middleware(['auth'])->group(function () {
    // Headmaster API routes
    Route::get('/headmaster/user', [AuthController::class, 'headmasterUser']);
    Route::get('/headmaster/school', [AuthController::class, 'headmasterSchool']);
    Route::get('/headmaster/visits', [AuthController::class, 'headmasterVisits']);

    // Admin API routes
    Route::prefix('api')->group(function () {
        Route::apiResource('schools', SchoolController::class);
        Route::apiResource('visits', VisitController::class);
        Route::put('/visits/{visit}/status', [VisitController::class, 'updateStatus']);
        Route::delete('/schools/{school}/invite', [SchoolController::class, 'deleteInvite']);
    });
});

// Public API routes (no authentication required)
Route::get('/api/visits/share/{token}', [VisitController::class, 'getByShareToken'])->name('visit.share.api');
Route::post('/api/visits/share/{token}/feedback', [VisitController::class, 'submitFeedback'])->name('visit.feedback.api');

// Vue.js SPA routes (specific routes first)
Route::get('/headmaster/dashboard', function () {
    return view('app');
});

Route::get('/visits/share/{token}', function () {
    return view('app');
})->name('visit.share');

// Vue.js SPA route (catch-all for all other Vue routes)
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$');
