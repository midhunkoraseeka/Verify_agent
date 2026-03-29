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
        Schema::create('rl_properties_tbl', function (Blueprint $table) {
            $table->id(); // Use this as the unique identifier
            $table->string('property_type'); // villa, flat, plot, etc.
            
            // Common Fields
            $table->string('location')->nullable();
            $table->text('location_highlights')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->string('price_type')->nullable(); 
            $table->string('size')->nullable(); 
            $table->string('facing')->nullable(); 
            
            // Structure Specific
            $table->string('bhk_type')->nullable(); 
            $table->string('floors')->nullable(); 
            $table->string('community_type')->nullable(); 
            $table->string('road_size')->nullable();
            $table->string('car_parking')->nullable();
            $table->text('amenities')->nullable();
            
            // Land/Plot Specific
            $table->string('approved_by')->nullable(); 
            $table->string('rera_status')->nullable(); 
            $table->string('land_type')->nullable(); 
            $table->string('owner_type')->nullable(); 
            $table->string('conversion_type')->nullable(); 
            
            // Media & Status
            $table->text('images')->nullable(); 
            $table->string('video')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('trash')->default(0);
            $table->tinyInteger('created_by')->nullable();
            $table->tinyInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rl_properties_tbl');
    }
};