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
        Schema::create('toffee_agency_user_maps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agency_id');
            $table->uuid('user_id');
            $table->uuid('created_by');
            $table->timestamps();

            $table->foreign('agency_id')->references('id')->on('toffee_agencies');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toffee_agency_user_maps');
    }
};
