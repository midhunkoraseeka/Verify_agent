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
        Schema::create('rl_ads_tbl', function (Blueprint $table) {
            $table->id();
            $table->string('ad_title');
            $table->string('ad_type'); // Property Promotion, Agent Spotlight, etc.
            
            // Timing & Links
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('external_url')->nullable();
            
            // Content
            $table->text('ad_text')->nullable();
            
            // Media (Consistent with Properties naming)
            $table->string('ad_image')->nullable();
            $table->string('ad_video')->nullable();
            
            
            // Status & Tracking (Consistent with Properties)
            $table->tinyInteger('status')->default(1); // 1: Active, 0: Pending, etc.
            $table->tinyInteger('trash')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rl_ads_tbl');
    }
};