<?php

use App\Http\Controllers\TigerWeb\ArticleAdvanceSearchController;
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

    // Article Routes
    Route::prefix('article')->group(function () {
        Route::get("/list", [ArticleController::class, 'listWithSearch'])->name('articles')->middleware('acl:tigerweb-all-article');
        Route::get("/", [ArticleController::class, 'articles'])->name('article_list')->middleware('acl:tigerweb-all-article');
        Route::get("/create", [ArticleController::class, 'create'])->name('article_create')->middleware('acl:tigerweb-article-create');
        Route::post("/save", [ArticleController::class, 'store'])->name('article_save')->middleware('acl:tigerweb-article-create');
        Route::get("/show/{id}", [ArticleController::class, 'show'])->name('article_detail')->middleware('acl:tigerweb-article-show');
        Route::get("/edit/{id}", [ArticleController::class, 'edit'])->name('article_edit')->middleware('acl:tigerweb-article-edit');
        Route::get("/delete/{id}", [ArticleController::class, 'delete'])->name('article_delete')->middleware('acl:tigerweb-article-delete');
        Route::get("/archive/{id}", [ArticleController::class, 'articleArchive'])->name('article_archive');
        Route::get("/correction", [ArticleController::class, 'correction'])->name('article_correction')->middleware('acl:tigerweb-article-correction');
        Route::get("/raise-ticket/{id}", [ArticleController::class, 'raiseTicketAgainstArticle'])->name('article_raise_ticket')->middleware('acl:tigerweb-article-raise-ticket');
        Route::post("/article_review_submit", [ArticleController::class, 'article_review_submit'])->name('article_review_submit')->middleware('acl:tigerweb-article-review-submit');
        Route::get("/advance-search", [ArticleAdvanceSearchController::class, 'search'])->name('advance_search')->middleware('acl:tigerweb-article-advance-search');
        Route::get("/{slug}", [ArticleController::class, 'viewArticleSlug'])->name('article_view_slug')->middleware('acl:tigerweb-article-agent-view');
        Route::get("/view/{id}", [ArticleController::class, 'viewArticle'])->name('article_view_detail')->middleware('acl:tigerweb-article-agent-view');
        Route::get("/{slug}", [ArticleController::class, 'viewArticleSlug'])->name('article_view_slug');
    });

    // Article Category Routes
    Route::prefix('article-category')->group(function () {
        Route::get("/list", [ArticleCategoryController::class, 'listWithSearch'])->name('article_categories')->middleware('acl:tigerweb-article-category-list');
        Route::get("/", [ArticleCategoryController::class, 'articleCategories'])->name('article_category_list')->middleware('acl:tigerweb-article-category-list');
        Route::get("/create", [ArticleCategoryController::class, 'create'])->name('article_category_create')->middleware('acl:tigerweb-article-category-create');
        Route::post("/save", [ArticleCategoryController::class, 'store'])->name('article_category_save')->middleware('acl:tigerweb-article-category-save');
        Route::get("/show/{id}", [ArticleCategoryController::class, 'show'])->name('article_category_detail')->middleware('acl:tigerweb-article-category-show');
        Route::get("/edit/{id}", [ArticleCategoryController::class, 'edit'])->name('article_category_edit')->middleware('acl:tigerweb-article-category-edit');
        Route::get("/delete/{id}", [ArticleCategoryController::class, 'delete'])->name('article_category_delete')->middleware('acl:tigerweb-article-category-delete');
        Route::get("/update/{id}", [ArticleCategoryController::class, 'updateArticleCategory'])->name('article_category_update')->middleware('acl:tigerweb-article-category-update');
    });

    // Campaign Routes
    Route::prefix('campaign')->group(function () {
        Route::get("/list", [CampaignController::class, 'listWithSearch'])->name('campaigns')->middleware('acl:tigerweb-campaign-list');
        Route::get("/", [CampaignController::class, 'campaigns'])->name('campaign_list')->middleware('acl:tigerweb-campaign-list');
        Route::get("/create", [CampaignController::class, 'create'])->name('campaign_create')->middleware('acl:tigerweb-campaign-create');
        Route::post("/save", [CampaignController::class, 'store'])->name('campaign_save')->middleware('acl:tigerweb-campaign-save');
        Route::get("/show/{id}", [CampaignController::class, 'show'])->name('campaign_detail')->middleware('acl:tigerweb-campaign-show');
        Route::get("/edit/{id}", [CampaignController::class, 'edit'])->name('campaign_edit')->middleware('acl:tigerweb-campaign-edit');
        Route::get("/delete/{id}", [CampaignController::class, 'delete'])->name('campaign_delete')->middleware('acl:tigerweb-campaign-delete');
    });

    // FAQ Routes
    Route::prefix('faq')->group(function () {
        Route::get("/{faq_type}/{faq_type_id}", [FaqController::class, 'faqs'])->name('faq_list')->middleware('acl:tigerweb-faq-list');
        Route::get("{faq_type}/{faq_type_id}/create", [FaqController::class, 'create'])->name('faq_create')->middleware('acl:tigerweb-faq-create');
        Route::post("/save", [FaqController::class, 'store'])->name('faq_save')->middleware('acl:tigerweb-faq-save');
        Route::get("{faq_type}/{faq_type_id}/show/{id}", [FaqController::class, 'show'])->name('faq_detail')->middleware('acl:tigerweb-faq-show');
        Route::get("{faq_type}/{faq_type_id}/edit/{id}", [FaqController::class, 'edit'])->name('faq_edit')->middleware('acl:tigerweb-faq-edit');
        Route::get("{faq_type}/{faq_type_id}/delete/{id}", [FaqController::class, 'delete'])->name('faq_delete')->middleware('acl:tigerweb-faq-delete');
    });

    // Tag Key Routes
    Route::prefix('tag-key')->group(function () {
        Route::get("/list", [TagKeyController::class, 'listWithSearch'])->name('tag_keys')->middleware('acl:tigerweb-tag-key-list');
        Route::get("/", [TagKeyController::class, 'tagKeys'])->name('tag_key_list')->middleware('acl:tigerweb-tag-key-list');
        Route::get("/create", [TagKeyController::class, 'create'])->name('tag_key_create')->middleware('acl:tigerweb-tag-key-create');
        Route::post("/save", [TagKeyController::class, 'store'])->name('tag_key_save')->middleware('acl:tigerweb-tag-key-save');
        Route::get("/show/{id}", [TagKeyController::class, 'show'])->name('tag_key_detail')->middleware('acl:tigerweb-tag-key-show');
        Route::get("/edit/{id}", [TagKeyController::class, 'edit'])->name('tag_key_edit')->middleware('acl:tigerweb-tag-key-edit');
        Route::get("/delete/{id}", [TagKeyController::class, 'delete'])->name('tag_key_delete')->middleware('acl:tigerweb-tag-key-delete');
    });

    // Article Tag Routes
    Route::prefix('article-tag')->group(function () {
        Route::get("/list", [ArticleTagController::class, 'listWithSearch'])->name('article_tags')->middleware('acl:tigerweb-article-tag-list');
        Route::get("/", [ArticleTagController::class, 'articleTags'])->name('article_tag_list')->middleware('acl:tigerweb-article-tag-list');
        Route::get("/create", [ArticleTagController::class, 'create'])->name('article_tag_create')->middleware('acl:tigerweb-article-tag-create');
        Route::post("/save", [ArticleTagController::class, 'store'])->name('article_tag_save')->middleware('acl:tigerweb-article-tag-save');
        Route::get("/show/{id}", [ArticleTagController::class, 'show'])->name('article_tag_detail')->middleware('acl:tigerweb-article-tag-show');
        Route::get("/edit/{id}", [ArticleTagController::class, 'edit'])->name('article_tag_edit')->middleware('acl:tigerweb-article-tag-edit');
        Route::get("/delete/{id}", [ArticleTagController::class, 'delete'])->name('article_tag_delete')->middleware('acl:tigerweb-article-tag-delete');
    });

    // Article Review Routes
    Route::prefix('article-review')->group(function () {
        Route::get("/list", [ArticleReviewController::class, 'listWithSearch'])->name('article_reviews')->middleware('acl:tigerweb-article-review-list');
        Route::get("/", [ArticleReviewController::class, 'articleReviews'])->name('article_review_list')->middleware('acl:tigerweb-article-review-list');
        Route::get("/create", [ArticleReviewController::class, 'create'])->name('article_review_create')->middleware('acl:tigerweb-article-review-create');
        Route::post("/save", [ArticleReviewController::class, 'store'])->name('article_review_save')->middleware('acl:tigerweb-article-review-save');
        Route::get("/show/{id}", [ArticleReviewController::class, 'show'])->name('article_review_detail')->middleware('acl:tigerweb-article-review-show');
        Route::get("/edit/{id}", [ArticleReviewController::class, 'edit'])->name('article_review_edit')->middleware('acl:tigerweb-article-review-edit');
        Route::get("/raise-approve-ticket/{id}", [ArticleReviewController::class, 'raiseApproveTicket'])->name('article_raise_approve_ticket')->middleware('acl:tigerweb-article-review-raise-approve-ticket');
        Route::get("/delete/{id}", [ArticleReviewController::class, 'delete'])->name('article_review_delete')->middleware('acl:tigerweb-article-review-delete');
    });

    // Daily News Routes
    Route::prefix('daily-news')->group(function () {
        Route::get("/list", [DailyNewsController::class, 'listWithSearch'])->name('daily_news')->middleware('acl:tigerweb-daily-news-list');
        Route::get("/", [DailyNewsController::class, 'dailyNews'])->name('daily_news_list')->middleware('acl:tigerweb-daily-news-list');
        Route::get("/create", [DailyNewsController::class, 'create'])->name('daily_news_create')->middleware('acl:tigerweb-daily-news-create');
        Route::post("/save", [DailyNewsController::class, 'store'])->name('daily_news_save')->middleware('acl:tigerweb-daily-news-save');
        Route::get("/show/{id}", [DailyNewsController::class, 'show'])->name('daily_news_detail')->middleware('acl:tigerweb-daily-news-show');
        Route::get("/edit/{id}", [DailyNewsController::class, 'edit'])->name('daily_news_edit')->middleware('acl:tigerweb-daily-news-edit');
        Route::get("/delete/{id}", [DailyNewsController::class, 'delete'])->name('daily_news_delete')->middleware('acl:tigerweb-daily-news-delete');
    });

    // VAS CP Routes
    Route::prefix('vas-cp')->group(function () {
        Route::get("/list", [VasCpController::class, 'listWithSearch'])->name('vas_cp')->middleware('acl:tigerweb-vas-cp-list');
        Route::get("/", [VasCpController::class, 'vasCp'])->name('vas_cp_list')->middleware('acl:tigerweb-vas-cp-list');
        Route::get("/create", [VasCpController::class, 'create'])->name('vas_cp_create')->middleware('acl:tigerweb-vas-cp-create');
        Route::post("/save", [VasCpController::class, 'store'])->name('vas_cp_save')->middleware('acl:tigerweb-vas-cp-save');
        Route::get("/show/{id}", [VasCpController::class, 'show'])->name('vas_cp_detail')->middleware('acl:tigerweb-vas-cp-show');
        Route::get("/edit/{id}", [VasCpController::class, 'edit'])->name('vas_cp_edit')->middleware('acl:tigerweb-vas-cp-edit');
        Route::get("/delete/{id}", [VasCpController::class, 'delete'])->name('vas_cp_delete')->middleware('acl:tigerweb-vas-cp-delete');
    });

    // VAS Service Routes
    Route::prefix('vas-service')->group(function () {
        Route::get("/list", [VasServiceController::class, 'listWithSearch'])->name('vas_service')->middleware('acl:tigerweb-vas-service-list');
        Route::get("/", [VasServiceController::class, 'vasServices'])->name('vas_service_list')->middleware('acl:tigerweb-vas-service-list');
        Route::get("/create", [VasServiceController::class, 'create'])->name('vas_service_create')->middleware('acl:tigerweb-vas-service-create');
        Route::post("/save", [VasServiceController::class, 'store'])->name('vas_service_save')->middleware('acl:tigerweb-vas-service-save');
        Route::get("/show/{id}", [VasServiceController::class, 'show'])->name('vas_service_detail')->middleware('acl:tigerweb-vas-service-show');
        Route::get("/edit/{id}", [VasServiceController::class, 'edit'])->name('vas_service_edit')->middleware('acl:tigerweb-vas-service-edit');
        Route::get("/delete/{id}", [VasServiceController::class, 'delete'])->name('vas_service_delete')->middleware('acl:tigerweb-vas-service-delete');
    });

    // VAS Service Option Routes
    Route::prefix('vas-service-option')->group(function () {
        Route::get("/list", [VasServiceOptionController::class, 'listWithSearch'])->name('vas_service_option')->middleware('acl:tigerweb-vas-service-option-list');
        Route::get("/", [VasServiceOptionController::class, 'vasServiceOptions'])->name('vas_service_option_list')->middleware('acl:tigerweb-vas-service-option-list');
        Route::get("/create", [VasServiceOptionController::class, 'create'])->name('vas_service_option_create')->middleware('acl:tigerweb-vas-service-option-create');
        Route::post("/save", [VasServiceOptionController::class, 'store'])->name('vas_service_option_save')->middleware('acl:tigerweb-vas-service-option-save');
        Route::get("/show/{id}", [VasServiceOptionController::class, 'show'])->name('vas_service_option_detail')->middleware('acl:tigerweb-vas-service-option-show');
        Route::get("/edit/{id}", [VasServiceOptionController::class, 'edit'])->name('vas_service_option_edit')->middleware('acl:tigerweb-vas-service-option-edit');
        Route::get("/delete/{id}", [VasServiceOptionController::class, 'delete'])->name('vas_service_option_delete')->middleware('acl:tigerweb-vas-service-option-delete');
    });

    // VAS Service Price Routes
    Route::prefix('vas-service-price')->group(function () {
        Route::get("/list", [VasServicePriceController::class, 'listWithSearch'])->name('vas_service_price')->middleware('acl:tigerweb-vas-service-price-list');
        Route::get("/", [VasServicePriceController::class, 'vasServiceprices'])->name('vas_service_price_list')->middleware('acl:tigerweb-vas-service-price-list');
        Route::get("/create", [VasServicePriceController::class, 'create'])->name('vas_service_price_create')->middleware('acl:tigerweb-vas-service-price-create');
        Route::post("/save", [VasServicePriceController::class, 'store'])->name('vas_service_price_save')->middleware('acl:tigerweb-vas-service-price-save');
        Route::get("/show/{id}", [VasServicePriceController::class, 'show'])->name('vas_service_price_detail')->middleware('acl:tigerweb-vas-service-price-show');
        Route::get("/edit/{id}", [VasServicePriceController::class, 'edit'])->name('vas_service_price_edit')->middleware('acl:tigerweb-vas-service-price-edit');
        Route::get("/delete/{id}", [VasServicePriceController::class, 'delete'])->name('vas_service_price_delete')->middleware('acl:tigerweb-vas-service-price-delete');
    });

});
