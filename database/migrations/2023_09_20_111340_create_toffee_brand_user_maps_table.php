<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('toffee_brand_user_maps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id');
            $table->uuid('user_id');
            $table->uuid('created_by');
            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('toffee_brands');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toffee_brand_user_maps');
    }
};
