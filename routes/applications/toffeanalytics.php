<?php

// use App\Http\Controllers\TigerWeb\CampaignController;
use App\Http\Controllers\ToffeAnalytics\CampaignManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToffeAnalytics\DashboardController;
use App\Http\Controllers\ToffeAnalytics\AgencyController;
use App\Http\Controllers\ToffeAnalytics\BrandController;
use App\Http\Controllers\ToffeAnalytics\CampaignController;

Route::middleware('auth')->group(function () {
    // ---------------------- route start's from here --------------------//


    Route::get("/ads", [DashboardController::class, 'lineItem']);
    // Route::get("/campaign-management", [CampaignManagementController::class, 'lineItem']);

    Route::prefix('all-campaign')->group(function () {
        Route::get("/", [CampaignManagementController::class, 'allCampaign'])->name('toffee.all.campaign.list')->middleware('acl:all-campaign');
        Route::get("/show/{id}/{startDate}/{endDate}/status", [CampaignManagementController::class, 'show'])->name('toffee.single.campaign.detail')->middleware('acl:all-campaign');
        Route::post("/campaign-range-data", [CampaignManagementController::class, 'campaignRangeData'])->name('toffee.campaign.range.data');

    });


    Route::prefix('brand')->group(function () {
        Route::get("/", [BrandController::class, 'agencies'])->name('brand_list')->middleware('acl:brand');
        Route::get("/create", [BrandController::class, 'create'])->name('brand_create');
        Route::post("/save", [BrandController::class, 'store'])->name('brand_save');
        Route::get("/show/{id}", [BrandController::class, 'show'])->name('brand_detail');
        Route::get("/edit/{id}", [BrandController::class, 'edit'])->name('brand_edit');
        Route::get("/delete/{id}", [BrandController::class, 'delete'])->name('brand_delete');
        Route::get("/delete-brand-user-map/{id}/{brand_id}", [BrandController::class, 'deleteBrandUserMap'])->name('brand_user_map_delete');
    });

    Route::prefix('agency')->group(function () {
        Route::get("/", [AgencyController::class, 'agencies'])->name('agency_list')->middleware('acl:agency');
        Route::get("/create", [AgencyController::class, 'create'])->name('agency_create');
        Route::post("/save", [AgencyController::class, 'store'])->name('agency_save');
        Route::get("/show/{id}", [AgencyController::class, 'show'])->name('agency_detail');
        Route::get("/edit/{id}", [AgencyController::class, 'edit'])->name('agency_edit');
        Route::get("/delete/{id}", [AgencyController::class, 'delete'])->name('agency_delete');
        Route::get("/delete-agency-user-map/{id}/{agency_id}", [AgencyController::class, 'deleteAgencyUserMap'])->name('agency_user_map_delete');
    });

    Route::prefix('toffee_campaign')->group(function () {
        Route::get("/{userId}", [CampaignController::class, 'campaigns'])->name('toffee_campaign_list');
        Route::get("/create/{userId}", [CampaignController::class, 'create'])->name('toffee_campaign_create');
        Route::post("/save", [CampaignController::class, 'store'])->name('toffee_campaign_save');
        Route::get("/show/{id}", [CampaignController::class, 'show'])->name('toffee_campaign_detail');
        Route::get("/edit/{id}", [CampaignController::class, 'edit'])->name('toffee_campaign_edit');
        Route::get("/delete/{id}", [CampaignController::class, 'delete'])->name('toffee_campaign_delete');
    });

    // Route::prefix('brand')->group(function () {
    //     Route::get("/", [SearchController::class, 'search'])->name('search_list')->middleware('acl:tms-search');
    //     Route::get("/create", [SearchController::class, 'create'])->name('search_create');
    //     Route::post("/save", [SearchController::class, 'store'])->name('search_save');
    //     Route::get("/show/{id}", [SearchController::class, 'show'])->name('search_detail');
    //     Route::get("/edit/{id}", [SearchController::class, 'edit'])->name('search_edit');
    //     Route::get("/delete/{id}", [SearchController::class, 'delete'])->name('search_delete');
    // });

});
