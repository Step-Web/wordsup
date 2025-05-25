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
        Schema::create('sentences_en', function (Blueprint $table) {
            $table->id();
            $table->string('en',255)->nullable();
            $table->string('ru',255)->nullable();
            $table->unsignedSmallInteger('qty')->default(0);
            $table->unsignedInteger('tID')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sentences_en');
    }
};
