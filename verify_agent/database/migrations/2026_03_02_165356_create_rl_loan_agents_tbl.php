<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rl_loan_agents_tbl', function (Blueprint $table) {
            $table->id();
            // Basic Info
            $table->string('full_name');
            $table->string('mobile');
            $table->string('bank_name');
            $table->string('bank_type'); // Private or Government
            
            // Location Info
            $table->string('constituency');
            $table->string('district');
            $table->string('state');
            $table->text('office_address');

            // Loan Data
            $table->text('loan_types'); // Stores comma-separated values from multiselect

            // Media
            $table->string('aadhar')->nullable();
            $table->string('profile_photo')->nullable();

            // Social Links
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();

            // System Fields
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('trash')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rl_loan_agents_tbl');
    }
};