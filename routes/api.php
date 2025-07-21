<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VisitController;
use App\Http\Controllers\Api\SchoolController;

Route::middleware('api')->group(function () {
    Route::apiResource('schools', SchoolController::class);
    Route::apiResource('visits', VisitController::class);
});
