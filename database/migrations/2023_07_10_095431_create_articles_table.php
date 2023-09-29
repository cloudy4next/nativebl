<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->unsignedInteger('article_category_id');
            $table->string('slug');
            $table->integer('ref_id')->nullable()->unsigned();
            $table->mediumText('content');
            $table->string('complaint_path')->nullable();
            $table->string('review_status', 50)->default('IN-PROGRESS')->comment("IN-PROGRESS, APPROVED, NEED CORRECTION");
            $table->uuid('created_by');
            $table->uuid('updated_by')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();

            $table->foreign('article_category_id')->references('id')->on('article_categories');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
