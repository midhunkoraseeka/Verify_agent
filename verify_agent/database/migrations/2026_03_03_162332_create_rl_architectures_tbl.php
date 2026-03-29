<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
    Schema::create('rl_architectures_tbl', function (Blueprint $table) {
        $table->id();
        $table->string('project_name');
        $table->string('project_type');
        $table->string('architect_name');
        $table->string('license_no')->nullable();
        $table->string('project_status');
        $table->date('submission_date');
        $table->date('approval_date')->nullable();
        $table->text('project_address')->nullable();
        $table->string('city')->nullable();
        $table->string('state')->nullable();
        $table->string('pincode')->nullable();
        $table->text('description')->nullable();
        $table->string('plans')->nullable(); // Stores file path
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
        Schema::dropIfExists('architectures');
    }
};
