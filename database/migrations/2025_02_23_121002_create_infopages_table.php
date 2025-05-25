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
        Schema::create('infopages', function (Blueprint $table) {
            $table->id();
            $table->string('mtitle',150)->nullable();
            $table->string('mdesc')->nullable();
            $table->string('mkey',150)->nullable();
            $table->string('title')->nullable();
            $table->string('url',150)->nullable();
            $table->text('content')->nullable();
            $table->boolean('is_public')->default(1);
            $table->boolean('is_index')->default(1);
            $table->boolean('is_sistem')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infopages');
    }
};
