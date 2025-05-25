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
        Schema::create('wordlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('group_id')->default(0);
            $table->string('word',100)->nullable();
            $table->string('ts',100)->nullable();
            $table->string('translate',150)->nullable();
            $table->string('audio',50)->nullable();
            $table->string('example')->nullable();
            $table->unsignedInteger('freq')->default(0);
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('wordgroups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wordlists');
    }
};
