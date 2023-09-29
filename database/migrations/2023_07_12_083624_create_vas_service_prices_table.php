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
        Schema::create('vas_service_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vas_service_id');
            $table->string('price');
            $table->string('frequency');
            $table->uuid('created_by');
            $table->timestamp('created_at');

            $table->foreign('vas_service_id')->references('id')->on('vas_services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vas_service_prices');
    }
};
