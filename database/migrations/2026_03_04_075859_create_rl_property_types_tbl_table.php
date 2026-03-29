<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rl_property_types_tbl', function (Blueprint $table) {
            $table->id();
            $table->string('property_type_name'); // e.g., Apartment, Villa, Plot, Commercial
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->tinyInteger('trash')->default(0)->comment('1=Trashed, 0=Not Trashed');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rl_property_types_tbl');
    }
};