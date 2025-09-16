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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_path');
            $table->string('thumbnail')->nullable();
            $table->integer('duration')->default(0); // in seconds
            $table->bigInteger('views_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->integer('dislikes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->boolean('is_nsfw')->default(false);
            $table->boolean('is_published')->default(false);
            $table->enum('visibility', ['public', 'unlisted', 'private'])->default('public');
            $table->json('tags')->nullable();
            $table->timestamps();
            
            $table->index(['channel_id']);
            $table->index(['is_nsfw']);
            $table->index(['is_published']);
            $table->index(['visibility']);
            $table->index(['views_count']);
            $table->index(['created_at']);
            $table->index(['title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};