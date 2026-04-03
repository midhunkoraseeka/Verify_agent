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
    Schema::create('states', function (Blueprint $table) {
        $table->id();
        $table->string('state_name');
        $table->tinyInteger('status')->default(1); // 1: Active, 0: Inactive
        $table->tinyInteger('trash')->default(0);  // 1: In Trash
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
