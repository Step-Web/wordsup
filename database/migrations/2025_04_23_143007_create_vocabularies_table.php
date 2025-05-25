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
        Schema::create('vocabularies', function (Blueprint $table) {
            $table->id();
            $table->enum('lang',['en','de','ru'])->default('en');
            $table->unsignedInteger('user_id')->default(0);
            $table->text('all_words');
            $table->text('correct_words');
            $table->unsignedSmallInteger('correct');
            $table->unsignedSmallInteger('score');
            $table->unsignedSmallInteger('vocabulary');
            $table->unsignedSmallInteger('maxscore');
            $table->unsignedSmallInteger('total');
            $table->unsignedTinyInteger('bonus');
            $table->unsignedTinyInteger('cheat');
            $table->unsignedTinyInteger('time');
            $table->timestamp('added')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocabularies');
    }
};
