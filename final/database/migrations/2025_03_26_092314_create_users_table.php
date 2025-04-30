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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('email_verification_token')->nullable();
            $table->string('password');
            $table->string('username')->unique();
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable()->default('profile/avatar/profile.png');
            
            // Foreign Keys
            $table->unsignedBigInteger('role_id'); 
            $table->unsignedBigInteger('department_id');

            // Define foreign key constraints
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

