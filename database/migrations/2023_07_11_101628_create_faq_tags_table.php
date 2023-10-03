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
        Schema::create('faq_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tag_key_id');
            $table->unsignedInteger('faq_id');
            $table->timestamp('created_at')->nullable();

            $table->foreign('tag_key_id')->references('id')->on('tag_keys');
            $table->foreign('faq_id')->references('id')->on('faqs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faq_tags');
    }
};
