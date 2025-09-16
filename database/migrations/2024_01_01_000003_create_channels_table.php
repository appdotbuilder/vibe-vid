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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('avatar')->nullable();
            $table->string('banner')->nullable();
            $table->integer('subscribers_count')->default(0);
            $table->integer('videos_count')->default(0);
            $table->boolean('is_verified')->default(false);
            $table->boolean('allow_nsfw')->default(false);
            $table->timestamps();
            
            $table->index(['user_id']);
            $table->index(['slug']);
            $table->index(['created_at']);
            $table->index(['subscribers_count']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};