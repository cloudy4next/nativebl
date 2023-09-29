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
        Schema::create('toffe_campagin_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->string('individual_date');
            $table->string('impression');
            $table->string('clicks');
            $table->string('complete_views');
            $table->string('active_viewable_impression');
            $table->string('viewable_impression');
            $table->string('ctr');
            $table->string('completion_rate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toffe_campagin_reports');
    }
};
