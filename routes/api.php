<?php

use App\Modules\Job\Controllers\JobController;
use App\Modules\User\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // public access
    Route::post('/register', [UserController::class, 'create']);
    Route::post('/login', [UserController::class, 'authenticate']);

    Route::get('/jobs', [JobController::class, 'index']);
    Route::get('/jobs/{job_id}', [JobController::class, 'show']);
    Route::get('/my/jobs/search', [JobController::class, 'searchJobs']);
    Route::post('/jobs/{job_id}/apply', [JobController::class, 'application']);

    //authenticated
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [UserController::class, 'getUser']);
        Route::get('/logout', [UserController::class, 'logout']);
        Route::post('/my/jobs', [JobController::class, 'store']);
        Route::get('/my/jobs', [JobController::class, 'getMyJobs']);
        Route::patch('/my/jobs/{job_id}', [JobController::class, 'update']);
        Route::get('/my/jobs/{job_id}/applications', [JobController::class, 'getAllApplications']);
        Route::delete('/my/jobs/{job_id}', [JobController::class, 'destroy']);
    });
});
