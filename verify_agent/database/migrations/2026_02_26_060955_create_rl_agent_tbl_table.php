<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rl_agent_tbl', function (Blueprint $table) {
            $table->id()->comment('Primary Key');
            $table->tinyInteger('usertype')->comment('0-Admin, 1-Agent')->nullable();
            $table->string('agent_id', length: 50)->nullable();
            $table->tinyInteger('priority')->comment('0-High, 1-Medium, 2-Low')->nullable();
            $table->string('agent_name', length: 100)->nullable();
            $table->string('father_name', length: 100)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('location', length: 100)->nullable();
            $table->string('constituency', length: 100)->nullable();
            $table->string('district', length: 100)->nullable();
            $table->string('mobile_number', length: 15)->nullable();
            $table->text('address')->nullable();
            $table->string('rera_no')->nullable();
            $table->string('agent_aadhar')->nullable();
            $table->string('agent_photo')->nullable();
            $table->string('username', length: 50)->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0-Inactive, 1-Active');
            $table->tinyInteger('trash')->default(0)->comment('0-Inactive, 1-active');
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
        Schema::dropIfExists('rl_agent_tbl');
    }
};
