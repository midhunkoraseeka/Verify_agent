<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rl_road_sizes_tbl', function (Blueprint $table) {
            $table->id();
            $table->string('road_size_name'); // e.g., 20 Feet, 30 Feet, 40 Feet, 100 Feet
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->tinyInteger('trash')->default(0)->comment('1=Trashed, 0=Not Trashed');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rl_road_sizes_tbl');
    }
};