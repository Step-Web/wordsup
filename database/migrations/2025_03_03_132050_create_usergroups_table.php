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
        Schema::create('usergroups', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable();
            $table->enum('lang',['en','de'])->default('en');
            $table->enum('type',['words','phrases'])->default('words');
            $table->unsignedTinyInteger('qty')->default(0);
            $table->string('name')->nullable();
            $table->string('color',8)->default('#0799d1');
            $table->unsignedInteger('owner_id')->default(0);
            $table->unsignedInteger('old_id')->default(0);
            $table->unsignedTinyInteger('is_public')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usergroups');
    }
};
