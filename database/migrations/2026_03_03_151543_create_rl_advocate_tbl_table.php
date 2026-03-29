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
    Schema::create('rl_advocates_tbl', function (Blueprint $table) {
        $table->id();
        $table->string('full_name');
        $table->string('mobile');
        $table->string('constituency')->nullable();
        $table->string('district')->nullable();
        $table->string('state')->nullable();
        $table->text('legal_services'); // Multi-select
        $table->text('office_location')->nullable();
        $table->string('aadhar')->nullable();
        $table->string('profile_photo')->nullable();
        $table->string('facebook')->nullable();
        $table->string('instagram')->nullable();
        $table->string('linkedin')->nullable();
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
        Schema::dropIfExists('rl_advocate_tbl');
    }
};
