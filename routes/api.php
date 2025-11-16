<?php

use App\Http\Controllers\Api\CompanyController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('company', CompanyController::class)->except(['update']);
    Route::get('/company/{id}/versions', [CompanyController::class, 'versions']);
});
