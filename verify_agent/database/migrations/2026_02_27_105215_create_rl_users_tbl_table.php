<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('rl_users_tbl', function (Blueprint $table) {
        $table->id(); 
        $table->string('first_name', 100)->nullable();
        $table->string('last_name', 100)->nullable();
        $table->string('email', 150)->nullable();
        $table->string('mobile_number', 15)->nullable();
        $table->string('city', 100)->nullable();
        $table->string('constituency', 100)->nullable();
        $table->string('pincode', 10)->nullable();
        $table->text('address')->nullable();
        $table->string('username', 50)->nullable();
        $table->string('password')->nullable();
        $table->tinyInteger('status')->default(1);
        $table->tinyInteger('trash')->default(0);
        $table->unsignedBigInteger('created_by')->nullable();
        $table->unsignedBigInteger('updated_by')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('rl_users_tbl');
    }
};