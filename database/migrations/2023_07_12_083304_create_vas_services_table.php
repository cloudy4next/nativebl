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
        Schema::create('vas_services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('article_category_id');
            $table->string('service_name');
            $table->string('short_code', 150);
            $table->unsignedInteger('article_id');
            $table->unsignedInteger('vas_cp_id');
            $table->uuid('created_by');
            $table->timestamp('created_at');

            $table->foreign('article_category_id')->references('id')->on('article_categories');
            $table->foreign('article_id')->references('id')->on('articles');
            $table->foreign('vas_cp_id')->references('id')->on('vas_cp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vas_services');
    }
};
