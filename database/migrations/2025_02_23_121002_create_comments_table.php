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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id',)->default(0);
            $table->unsignedTinyInteger('parent_id')->default(0);
            $table->string('page_tab',50)->nullable();
            $table->unsignedInteger('page_id',)->default(0);
            $table->string('page_url')->nullable();
            $table->string('username',50)->nullable();
            $table->string('email')->nullable();
            $table->string('avatar')->nullable();
            $table->text('comment')->nullable();
            $table->boolean('moderation')->default(1);
            $table->boolean('notify')->default(0);
            $table->unsignedInteger('likes',)->default(0);
            $table->unsignedInteger('dislikes',)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
