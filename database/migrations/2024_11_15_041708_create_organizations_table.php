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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string("organizations_code",8);
            $table->string("organizations_level",10);
            $table->string("description",100);
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
        Schema::dropIfExists('organizations');
    }
};
