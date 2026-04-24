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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title', 100);
            $table->string('slug')->unique();
            $table->foreignId('content_classification_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('content_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('header_image')->nullable();
            $table->string('featured_image')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('youtube_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
