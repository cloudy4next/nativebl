<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DBSSESim\ESimController;

Route::middleware('auth')->group(function () {


    Route::prefix('e-sim')->group(function () {
        Route::get("/", [ESimController::class, 'esim'])->middleware('acl:dbss-e-sim');

    });
});
