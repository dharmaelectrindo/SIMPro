<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string("description",50);
            $table->unsignedBigInteger("user_crt");
            $table->unsignedBigInteger("user_mdf");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_crt')->references('id')->on('users')->onDelete("cascade");
            $table->foreign('user_mdf')->references('id')->on('users')->onDelete("cascade");
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
