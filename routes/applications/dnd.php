<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DND\BaseDNDController;
use App\Http\Controllers\DND\BulkUploadDNDController;

Route::middleware('auth')->group(function () {

    Route::prefix('dnd')->group(function () {
        Route::get("/", [BaseDNDController::class, 'dndShow'])->name('dnd')->middleware('acl:dnd-list');
        Route::post("dnd-on-off", [BaseDNDController::class, 'dndOnOff'])->name('dnd.onoff')->middleware('acl:dnd-on-off');
    });
    Route::prefix('dnd-bulk')->group(function () {
        Route::get("/", [BulkUploadDNDController::class, 'uploadBulk'])->name('dnd-bulk')->middleware('acl:dnd-bulk');
        Route::get("/export", [BulkUploadDNDController::class, 'export'])->name('dnd.export')->middleware('acl:dnd-export');
        Route::get("/upload", [BulkUploadDNDController::class, 'getUploadView'])->name('dnd.upload')->middleware('acl:dnd-upload');
        Route::post("/upload-save", [BulkUploadDNDController::class, 'saveUploadData'])->name('dnd.upload.save')->middleware('acl:dnd-upload');
    });
});
