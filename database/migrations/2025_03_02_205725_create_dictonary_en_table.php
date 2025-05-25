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
        Schema::create('dictonary_en', function (Blueprint $table) {
            $table->id();
            $table->string('word',50)->nullable();
            $table->string('ts',50)->nullable();
            $table->string('translate')->nullable();
            $table->string('audio',50)->nullable();
            $table->unsignedSmallInteger('sentences')->default(0);
            $table->unsignedSmallInteger('is_public')->default(1);
            $table->unsignedSmallInteger('is_test')->default(1);
            $table->unsignedSmallInteger('wgroup')->default(0);
            $table->unsignedInteger('freq')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictonary_en');
    }
};
