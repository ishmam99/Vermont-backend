<?php

use Illuminate\Support\Facades\Route;
use Modules\BusinessDevelopment\src\Controllers\LeadController;
use Modules\BusinessDevelopment\Controllers\PartnerController;
use Modules\BusinessDevelopment\Controllers\MarketController;
use Modules\BusinessDevelopment\Controllers\CompetitorController;
use Modules\BusinessDevelopment\Controllers\StrategicProjectController;
use Modules\BusinessDevelopment\Controllers\MeetingController;
use Modules\BusinessDevelopment\Controllers\ReferralController;

Route::prefix('api/v1')->middleware('api')->group(function () {
    Route::prefix('BusinessDevelopment')->middleware('auth:sanctum')->group(function () {

        Route::apiResource('partners', PartnerController::class);
        Route::apiResource('markets', MarketController::class);
        Route::apiResource('competitors', CompetitorController::class);
        Route::apiResource('strategic-projects', StrategicProjectController::class);
        Route::apiResource('meetings', MeetingController::class);
        Route::apiResource('referrals', ReferralController::class);
    });
});
