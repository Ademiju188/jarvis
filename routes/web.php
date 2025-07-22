<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\VisitController;
use Illuminate\Support\Facades\Auth;

// CSRF token route
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});

// Authentication routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Headteacher registration route
Route::post('/headteacher/register', [AuthController::class, 'headteacherRegister']);

// Create admin user (remove this route after creating admin)
Route::get('/create-admin', [AuthController::class, 'createAdmin']);

// Public routes (no authentication required)
Route::get('/schools/token/{token}', [SchoolController::class, 'getByToken'])->name('school.access');
Route::post('/visits/{visit}/feedback', [VisitController::class, 'submitFeedback'])->name('visit.feedback');
Route::get('/visits/{visit}/pdf', [VisitController::class, 'generatePdf'])->name('visit.pdf');

// Protected routes (both admin and headteacher)
Route::middleware(['auth'])->group(function () {
    // Headteacher API routes
    Route::get('/headteacher/user', [AuthController::class, 'headteacherUser']);
    Route::get('/headteacher/school', [AuthController::class, 'headteacherSchool']);
    Route::get('/headteacher/visits', [AuthController::class, 'headteacherVisits']);

    // API routes for authenticated users
    Route::prefix('api')->group(function () {
        Route::apiResource('schools', SchoolController::class);
        Route::apiResource('visits', VisitController::class);
        Route::put('/visits/{visit}/status', [VisitController::class, 'updateStatus']);
        Route::delete('/schools/{school}/invite', [SchoolController::class, 'deleteInvite']);
        Route::post('/visits/{visit}/feedback', [VisitController::class, 'submitFeedbackById']);
        Route::get('/visits/share/{token}', [VisitController::class, 'getByShareToken'])->name('visit.share.api');
        Route::post('/visits/share/{token}/feedback', [VisitController::class, 'submitFeedback'])->name('visit.feedback.api');

        // Headteacher management routes
        Route::get('/headteachers', [AuthController::class, 'getAllHeadteachers']);
        Route::delete('/headteachers/{headteacher}', [AuthController::class, 'deleteHeadteacher']);
    });

    // Dashboard routes
    Route::get('/headteacher/dashboard', function () {
        return view('app');
    })->name('headteacher.dashboard');
});

// Admin-only routes
Route::middleware(['auth', 'admin.only'])->group(function () {
    Route::get('/dashboard', function () {
        return view('app');
    });
});

// Vue.js SPA routes (specific routes first)
Route::get('/register', function () {
    return view('app');
})->name('register');

// Vue.js SPA route (catch-all for all other Vue routes)
Route::get('/visits/share/{token}', function () {
    return view('app');
})->name('visit.share');

// Vue.js SPA route (catch-all for all other Vue routes)
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$');

// Authenticated user info for SPA auth check
Route::middleware('auth')->get('/api/user', function () {
    return response()->json(Auth::user());
});

// Profile update route for authenticated users
Route::middleware('auth')->put('/api/user/profile', [AuthController::class, 'updateProfile']);
