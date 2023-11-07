<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DND\BaseDNDController;
use App\Http\Controllers\DND\BulkUploadDNDController;

Route::middleware('auth')->group(function () {

    Route::prefix('dnd')->group(function () {
        Route::get("/", [BaseDNDController::class, 'dnd'])->name('dnd');
        Route::post("dnd-on-off", [BaseDNDController::class, 'dndOnOff'])->name('dnd.onoff');
    });
    Route::prefix('dnd-bulk')->group(function () {
        Route::get("/", [BulkUploadDNDController::class, 'uploadBulk'])->name('dnd-bulk');
        Route::get("/export", [BulkUploadDNDController::class, 'export'])->name('dnd.export');

    });

});
