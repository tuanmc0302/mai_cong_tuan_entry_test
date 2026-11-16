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
        Schema::create('locations', function (Blueprint $table) {
            $table->id(); // bigint unsigned, auto increment, primary key
            $table->string('name', 255)->unique();
            $table->string('lat', 255);
            $table->string('lng', 255);
            $table->string('status', 255)->nullable();
            $table->timestamps();   // created_at, updated_at
            $table->softDeletes();  // deleted_at (nullable timestamp)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
