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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('npk')->unique();
            $table->string('employee_name', 100);
            $table->string('email')->unique();
            $table->string('employee_position', 50);
            $table->string('mobile_number', 15);
            $table->unsignedBigInteger("user_crt");
            $table->unsignedBigInteger("user_mdf");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_crt')->references('id')->on('users')->onDelete("cascade");
            $table->foreign('user_mdf')->references('id')->on('users')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
