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
    Schema::create('rl_survey_services_tbl', function (Blueprint $table) {
        $table->id();
        $table->string('service_name'); // e.g., Land Survey, Boundary Survey
        $table->tinyInteger('status')->default(1); 
        $table->tinyInteger('trash')->default(0);
        $table->integer('created_by')->nullable();
        $table->integer('updated_by')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rl_survey_services_tbl');
    }
};
