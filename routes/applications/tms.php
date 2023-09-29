<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TMS\SearchController;
use App\Http\Controllers\TMS\DailyReportController;
use App\Http\Controllers\TMS\HourlyReportController;
use App\Http\Controllers\TMS\ReportController;


Route::middleware('auth')->group(function () {


    Route::prefix('tms-search')->group(function () {

        Route::get("/", [SearchController::class, 'search'])->name('search_list')->middleware('acl:tms-search');
        Route::get("/create", [SearchController::class, 'create'])->name('search_create');
        Route::post("/save", [SearchController::class, 'store'])->name('search_save');
        Route::get("/show/{id}", [SearchController::class, 'show'])->name('search_detail');
        Route::get("/edit/{id}", [SearchController::class, 'edit'])->name('search_edit');
        Route::get("/delete/{id}", [SearchController::class, 'delete'])->name('search_delete');
    });

    Route::prefix('tms-report')->group(function () {

        Route::get("/", [ReportController::class, 'report'])->name('report_list')->middleware('acl:tms-report');
        Route::get("/create", [ReportController::class, 'create'])->name('report_create');
        Route::post("/save", [ReportController::class, 'store'])->name('report_save');
        Route::get("/show/{id}", [ReportController::class, 'show'])->name('report_detail');
        Route::get("/edit/{id}", [ReportController::class, 'edit'])->name('report_edit');
        Route::get("/delete/{id}", [ReportController::class, 'delete'])->name('report_delete');
    });


    Route::prefix('tms-daily-report')->group(function () {

        Route::get("/", [DailyReportController::class, 'dailyReport'])->name('daily_report_list')->middleware('acl:tms-daily-report');
        Route::get("/create", [DailyReportController::class, 'create'])->name('daily_report_create');
        Route::post("/save", [DailyReportController::class, 'store'])->name('daily_report_save');
        Route::get("/show/{id}", [DailyReportController::class, 'show'])->name('daily_report_detail');
        Route::get("/edit/{id}", [DailyReportController::class, 'edit'])->name('daily_report_edit');
        Route::get("/delete/{id}", [DailyReportController::class, 'delete'])->name('daily_report_delete');
    });
    Route::prefix('tms-hourly-report')->group(function () {

        Route::get("/", [HourlyReportController::class, 'hourlyReport'])->name('hourly_report_list')->middleware('acl:tms-hourly-report');
        Route::get("/create", [HourlyReportController::class, 'create'])->name('hourly_report_create');
        Route::post("/save", [HourlyReportController::class, 'store'])->name('hourly_report_save');
        Route::get("/show/{id}", [HourlyReportController::class, 'show'])->name('hourly_report_detail');
        Route::get("/edit/{id}", [HourlyReportController::class, 'edit'])->name('hourly_report_edit');
        Route::get("/delete/{id}", [HourlyReportController::class, 'delete'])->name('hourly_report_delete');
    });

});
