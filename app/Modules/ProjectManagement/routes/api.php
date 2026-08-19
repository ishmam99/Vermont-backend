<?php

use Illuminate\Support\Facades\Route;
use Modules\ProjectManagement\src\Controllers\LeadController;
use Modules\ProjectManagement\Controllers\ProjectController;
use Modules\ProjectManagement\Controllers\MilestoneController;
use Modules\ProjectManagement\Controllers\TaskController;
use Modules\ProjectManagement\Controllers\TimesheetController;
use Modules\ProjectManagement\Controllers\ExpenseController;
use Modules\ProjectManagement\Controllers\FileController;
use Modules\ProjectManagement\Controllers\RiskController;

Route::prefix('api/v1')->middleware('api')->group(function () {
    Route::prefix('ProjectManagement')->middleware('auth:sanctum')->group(function () {

        Route::apiResource('projects', ProjectController::class);
        Route::apiResource('milestones', MilestoneController::class);
        Route::apiResource('tasks', TaskController::class);
        Route::apiResource('timesheets', TimesheetController::class);
        Route::apiResource('expenses', ExpenseController::class);
        Route::apiResource('files', FileController::class);
        Route::apiResource('risks', RiskController::class);
    });
});
