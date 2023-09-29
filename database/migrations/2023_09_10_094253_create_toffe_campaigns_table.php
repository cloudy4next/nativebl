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
        Schema::create('toffee_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_id');
            $table->string('campaign_name');
            $table->unsignedBigInteger('agency_id');
            $table->unsignedBigInteger('brand_id');
            $table->uuid('user_id');
            $table->uuid('created_by');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->foreign('agency_id')->references('id')->on('toffee_agencies');
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
        Schema::dropIfExists('toffee_campaigns');
    }
};
