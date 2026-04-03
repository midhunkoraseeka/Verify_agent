<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('districts', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('state_id');
        $table->string('district_name');
        $table->tinyInteger('status')->default(1);
        $table->tinyInteger('trash')->default(0);
        $table->unsignedBigInteger('created_by')->nullable(); // New Column
        $table->unsignedBigInteger('updated_by')->nullable(); // New Column
        $table->timestamps();

        $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rl_district');
    }
};
