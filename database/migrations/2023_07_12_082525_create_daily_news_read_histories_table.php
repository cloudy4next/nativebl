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
        Schema::create('daily_news_read_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('daily_news_id');
            $table->uuid('agent_id');
            $table->unsignedInteger('updated_by')->nullable();
            $table->date('read_date')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('daily_news_id')->references('id')->on('daily_news');
            $table->foreign('agent_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_news_read_histories');
    }
};
