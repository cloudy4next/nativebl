<?php

// use App\Http\Controllers\TigerWeb\CampaignController;
use App\Http\Controllers\ToffeAnalytics\CampaignManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToffeAnalytics\DashboardController;
use App\Http\Controllers\ToffeAnalytics\AgencyController;
use App\Http\Controllers\ToffeAnalytics\BrandController;
use App\Http\Controllers\ToffeAnalytics\CampaignController;
use App\Http\Controllers\ToffeAnalytics\UserCampaingController;

Route::middleware('auth')->group(function () {
    // ---------------------- route start's from here --------------------//


    Route::get("/ads", [DashboardController::class, 'lineItem']);
    // Route::get("/campaign-management", [CampaignManagementController::class, 'lineItem']);

    Route::prefix('all-campaign')->group(function () {
        Route::get("/", [CampaignManagementController::class, 'allCampaign'])->name('toffee.all.campaign.list')->middleware('acl:toffee-all-campaign');
        Route::get("/show/{id}/{status}/{impression}/{clicks}/{ctr}/{view}", [UserCampaingController::class, 'singleCampaign'])->name('toffee.single.campaign.detail')->middleware('acl:toffee-all-campaign');
        Route::post("/campaign-range-data", [CampaignManagementController::class, 'campaignRangeData'])->name('toffee.campaign.range.data')->middleware('acl:toffee-all-campaign');
        Route::get("/export/{id}/{status}/{impression}/{clicks}/{ctr}/{view}", [UserCampaingController::class, 'export'])->name('toffee.campaign.export')->middleware('acl:toffee-all-campaign');
    });


    Route::prefix('brand')->group(function () {
        Route::get("/", [BrandController::class, 'agencies'])->name('brand_list')->middleware('acl:toffee-brand');
        Route::get("/create", [BrandController::class, 'create'])->name('brand_create')->middleware('acl:toffee-brand-create');
        Route::post("/save", [BrandController::class, 'store'])->name('brand_save')->middleware('acl:toffee-brand-create');
        Route::get("/show/{id}", [BrandController::class, 'show'])->name('brand_detail')->middleware('acl:toffee-brand-show');
        Route::get("/edit/{id}", [BrandController::class, 'edit'])->name('brand_edit')->middleware('acl:toffee-brand-edit');
        Route::get("/delete/{id}", [BrandController::class, 'delete'])->name('brand_delete')->middleware('acl:toffee-brand-delete');
        Route::get("/delete-brand-user-map/{id}/{brand_id}", [BrandController::class, 'deleteBrandUserMap'])->name('brand_user_map_delete')->middleware('acl:toffee-brand-user-map-delete');
    });

    Route::prefix('agency')->group(function () {
        Route::get("/", [AgencyController::class, 'agencies'])->name('agency_list')->middleware('acl:toffee-agency');
        Route::get("/create", [AgencyController::class, 'create'])->name('agency_create')->middleware('acl:toffee-agency-create');
        Route::post("/save", [AgencyController::class, 'store'])->name('agency_save')->middleware('acl:toffee-agency-create');
        Route::get("/show/{id}", [AgencyController::class, 'show'])->name('agency_detail')->middleware('acl:toffee-agency-show');
        Route::get("/edit/{id}", [AgencyController::class, 'edit'])->name('agency_edit')->middleware('acl:toffee-agency-edit');
        Route::get("/delete/{id}", [AgencyController::class, 'delete'])->name('agency_delete')->middleware('acl:toffee-agency-delete');
        Route::get("/delete-agency-user-map/{id}/{agency_id}", [AgencyController::class, 'deleteAgencyUserMap'])->name('agency_user_map_delete')->middleware('acl:toffee-agency-user-map-delete');
    });

    Route::prefix('map-campaigns')->group(function () {
        Route::get("/", [CampaignController::class, 'campaigns'])->name('toffee_campaign_list')->middleware('acl:toffee-map-campaigns');
        Route::get("/create", [CampaignController::class, 'create'])->name('toffee_campaign_create')->middleware('acl:toffee-map-campaigns-create');
        Route::post("/save", [CampaignController::class, 'store'])->name('toffee_campaign_save')->middleware('acl:toffee-map-campaigns-create');
        Route::get("/show/{id}", [CampaignController::class, 'show'])->name('toffee_campaign_detail')->middleware('acl:toffee-map-campaigns-show');
        Route::get("/edit/{id}", [CampaignController::class, 'edit'])->name('toffee_campaign_edit')->middleware('acl:toffee-map-campaigns-edit');
        Route::get("/delete/{id}", [CampaignController::class, 'delete'])->name('toffee_campaign_delete')->middleware('acl:toffee-map-campaigns-delete');
    });

});
