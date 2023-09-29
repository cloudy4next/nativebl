<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TigerWeb\ArticleCategoryController;
use App\Http\Controllers\TigerWeb\ArticleController;
use App\Http\Controllers\TigerWeb\CampaignController;
use App\Http\Controllers\TigerWeb\TagKeyController;
use App\Http\Controllers\TigerWeb\ArticleTagController;
use App\Http\Controllers\TigerWeb\ArticleReviewController;
use App\Http\Controllers\TigerWeb\DailyNewsController;
use App\Http\Controllers\TigerWeb\VasCpController;
use App\Http\Controllers\TigerWeb\VasServiceController;
use App\Http\Controllers\TigerWeb\VasServiceOptionController;
use App\Http\Controllers\TigerWeb\VasServicePriceController;
use App\Http\Controllers\TigerWeb\FaqController;

Route::middleware('auth')->group(function () {

    Route::prefix('article')->group(function () {
        Route::get("/list", [ArticleController::class, 'listWithSearch'])->name('articles');
        Route::get("/", [ArticleController::class, 'articles'])->name('article_list');
        Route::get("/create", [ArticleController::class, 'create'])->name('article_create');
        Route::post("/save", [ArticleController::class, 'store'])->name('article_save');
        Route::get("/show/{id}", [ArticleController::class, 'show'])->name('article_detail');
        Route::get("/edit/{id}", [ArticleController::class, 'edit'])->name('article_edit');
        Route::get("/delete/{id}", [ArticleController::class, 'delete'])->name('article_delete');
        Route::get("/correction", [ArticleController::class, 'correction'])->name('article_correction');
        Route::get("/raise-ticket/{id}", [ArticleController::class, 'raiseTicketAgainstArticle'])->name('article_raise_ticket');
    });

    Route::prefix('article-category')->group(function () {
        Route::get("/list", [ArticleCategoryController::class, 'listWithSearch'])->name('article_categories');
        Route::get("/", [ArticleCategoryController::class, 'articleCategories'])->name('article_category_list');
        Route::get("/create", [ArticleCategoryController::class, 'create'])->name('article_category_create');
        Route::post("/save", [ArticleCategoryController::class, 'store'])->name('article_category_save');
        Route::get("/show/{id}", [ArticleCategoryController::class, 'show'])->name('article_category_detail');
        Route::get("/edit/{id}", [ArticleCategoryController::class, 'edit'])->name('article_category_edit');
        Route::get("/delete/{id}", [ArticleCategoryController::class, 'delete'])->name('article_category_delete');
        Route::get("/update/{id}", [ArticleCategoryController::class, 'updateArticleCategory'])->name('article_category_update');
    });


    Route::prefix('campaign')->group(function () {
        Route::get("/list", [CampaignController::class, 'listWithSearch'])->name('campaigns');
        Route::get("/", [CampaignController::class, 'campaigns'])->name('campaign_list');
        Route::get("/create", [CampaignController::class, 'create'])->name('campaign_create');
        Route::post("/save", [CampaignController::class, 'store'])->name('campaign_save');
        Route::get("/show/{id}", [CampaignController::class, 'show'])->name('campaign_detail');
        Route::get("/edit/{id}", [CampaignController::class, 'edit'])->name('campaign_edit');
        Route::get("/delete/{id}", [CampaignController::class, 'delete'])->name('campaign_delete');
    });

    Route::prefix('faq')->group(function () {
        Route::get("/{faq_type}/{faq_type_id}", [FaqController::class, 'faqs'])->name('faq_list');
        Route::get("{faq_type}/{faq_type_id}/create", [FaqController::class, 'create'])->name('faq_create');
        Route::post("/save", [FaqController::class, 'store'])->name('faq_save');
        Route::get("/show/{id}", [FaqController::class, 'show'])->name('faq_detail');
        Route::get("{faq_type}/{faq_type_id}/edit/{id}", [FaqController::class, 'edit'])->name('faq_edit');
        Route::get("{faq_type}/{faq_type_id}/delete/{id}", [FaqController::class, 'delete'])->name('faq_delete');
    });

    Route::prefix('tag-key')->group(function () {
        Route::get("/list", [TagKeyController::class, 'listWithSearch'])->name('tag_keys');
        Route::get("/", [TagKeyController::class, 'tagKeys'])->name('tag_key_list');
        Route::get("/create", [TagKeyController::class, 'create'])->name('tag_key_create');
        Route::post("/save", [TagKeyController::class, 'store'])->name('tag_key_save');
        Route::get("/show/{id}", [TagKeyController::class, 'show'])->name('tag_key_detail');
        Route::get("/edit/{id}", [TagKeyController::class, 'edit'])->name('tag_key_edit');
        Route::get("/delete/{id}", [TagKeyController::class, 'delete'])->name('tag_key_delete');
    });

    Route::prefix('article-tag')->group(function () {
        Route::get("/list", [ArticleTagController::class, 'listWithSearch'])->name('article_tags');
        Route::get("/", [ArticleTagController::class, 'articleTags'])->name('article_tag_list');
        Route::get("/create", [ArticleTagController::class, 'create'])->name('article_tag_create');
        Route::post("/save", [ArticleTagController::class, 'store'])->name('article_tag_save');
        Route::get("/show/{id}", [ArticleTagController::class, 'show'])->name('article_tag_detail');
        Route::get("/edit/{id}", [ArticleTagController::class, 'edit'])->name('article_tag_edit');
        Route::get("/delete/{id}", [ArticleTagController::class, 'delete'])->name('article_tag_delete');
    });

    Route::prefix('article-review')->group(function () {
        Route::get("/list", [ArticleReviewController::class, 'listWithSearch'])->name('article_reviews');
        Route::get("/", [ArticleReviewController::class, 'articleReviews'])->name('article_review_list');
        Route::get("/create", [ArticleReviewController::class, 'create'])->name('article_review_create');
        Route::post("/save", [ArticleReviewController::class, 'store'])->name('article_review_save');
        Route::get("/show/{id}", [ArticleReviewController::class, 'show'])->name('article_review_detail');
        Route::get("/edit/{id}", [ArticleReviewController::class, 'edit'])->name('article_review_edit');
        // Route::get("/raise-ticket/{id}", [ArticleReviewController::class, 'raiseTicket'])->name('article_raise-ticket');
        Route::get("/raise-approve-ticket/{id}", [ArticleReviewController::class, 'raiseApproveTicket'])->name('article_raise_approve_ticket');
        Route::get("/delete/{id}", [ArticleReviewController::class, 'delete'])->name('article_review_delete');
    });

    Route::prefix('daily-news')->group(function () {
        Route::get("/list", [DailyNewsController::class, 'listWithSearch'])->name('daily_news');
        Route::get("/", [DailyNewsController::class, 'dailyNews'])->name('daily_news_list');
        Route::get("/create", [DailyNewsController::class, 'create'])->name('daily_news_create');
        Route::post("/save", [DailyNewsController::class, 'store'])->name('daily_news_save');
        Route::get("/show/{id}", [DailyNewsController::class, 'show'])->name('daily_news_detail');
        Route::get("/edit/{id}", [DailyNewsController::class, 'edit'])->name('daily_news_edit');
        Route::get("/delete/{id}", [DailyNewsController::class, 'delete'])->name('daily_news_delete');
    });

    Route::prefix('vas-cp')->group(function () {
        Route::get("/list", [VasCpController::class, 'listWithSearch'])->name('vas_cp');
        Route::get("/", [VasCpController::class, 'vasCp'])->name('vas_cp_list');
        Route::get("/create", [VasCpController::class, 'create'])->name('vas_cp_create');
        Route::post("/save", [VasCpController::class, 'store'])->name('vas_cp_save');
        Route::get("/show/{id}", [VasCpController::class, 'show'])->name('vas_cp_detail');
        Route::get("/edit/{id}", [VasCpController::class, 'edit'])->name('vas_cp_edit');
        Route::get("/delete/{id}", [VasCpController::class, 'delete'])->name('vas_cp_delete');
    });

    Route::prefix('vas-service')->group(function () {
        Route::get("/list", [VasServiceController::class, 'listWithSearch'])->name('vas_service');
        Route::get("/", [VasServiceController::class, 'vasServices'])->name('vas_service_list');
        Route::get("/create", [VasServiceController::class, 'create'])->name('vas_service_create');
        Route::post("/save", [VasServiceController::class, 'store'])->name('vas_service_save');
        Route::get("/show/{id}", [VasServiceController::class, 'show'])->name('vas_service_detail');
        Route::get("/edit/{id}", [VasServiceController::class, 'edit'])->name('vas_service_edit');
        Route::get("/delete/{id}", [VasServiceController::class, 'delete'])->name('vas_service_delete');
    });

    Route::prefix('vas-service-option')->group(function () {
        Route::get("/list", [VasServiceOptionController::class, 'listWithSearch'])->name('vas_service_option');
        Route::get("/", [VasServiceOptionController::class, 'vasServiceOptions'])->name('vas_service_option_list');
        Route::get("/create", [VasServiceOptionController::class, 'create'])->name('vas_service_option_create');
        Route::post("/save", [VasServiceOptionController::class, 'store'])->name('vas_service_option_save');
        Route::get("/show/{id}", [VasServiceOptionController::class, 'show'])->name('vas_service_option_detail');
        Route::get("/edit/{id}", [VasServiceOptionController::class, 'edit'])->name('vas_service_option_edit');
        Route::get("/delete/{id}", [VasServiceOptionController::class, 'delete'])->name('vas_service_option_delete');
    });

    Route::prefix('vas-service-price')->group(function () {
        Route::get("/list", [VasServicePriceController::class, 'listWithSearch'])->name('vas_service_price');
        Route::get("/", [VasServicePriceController::class, 'vasServiceprices'])->name('vas_service_price_list');
        Route::get("/create", [VasServicePriceController::class, 'create'])->name('vas_service_price_create');
        Route::post("/save", [VasServicePriceController::class, 'store'])->name('vas_service_price_save');
        Route::get("/show/{id}", [VasServicePriceController::class, 'show'])->name('vas_service_price_detail');
        Route::get("/edit/{id}", [VasServicePriceController::class, 'edit'])->name('vas_service_price_edit');
        Route::get("/delete/{id}", [VasServicePriceController::class, 'delete'])->name('vas_service_price_delete');
    });
});
