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
        Schema::create('article_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('article_id');
            $table->string('review_status', 50)->comment("APPROVED, NEED CORRECTION");
            $table->text('review_comments');
            $table->timestamp('created_at');
            $table->uuid('created_by');

            $table->foreign('article_id')->references('id')->on('articles');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_reviews');
    }
};
