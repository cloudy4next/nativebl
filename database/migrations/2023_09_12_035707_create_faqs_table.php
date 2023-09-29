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
        Schema::create('faqs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('faq_type', 100)->comment("ARTICLE, VAS, CAMPAIGN");
            $table->unsignedInteger('ref_id')->comment("Foreign key of ARTICLE, VAS, CAMPAIGN");
            $table->text('question');
            $table->text('answer');
            $table->timestamp('created_at')->nullable();
            $table->uuid('created_by');

            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
