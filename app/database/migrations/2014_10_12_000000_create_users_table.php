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
            $table->id(); // bigint unsigned, auto increment, primary key
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->text('avatar')->nullable();
            $table->string('gender', 100);
            $table->rememberToken(); // varchar(100) nullable
            $table->timestamps();    // created_at, updated_at (nullable)
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
