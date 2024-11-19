<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('posts', function (Blueprint $table) {
      $table->id();
      $table->string('title')->nullable();
      $table->string('slug')->unique();
      $table->json('meta_data')->nullable();
      $table->string('status')->default('draft');
      $table->string('type')->default('post');
      $table->string('thumbnail')->nullable();
      $table->longText('content')->nullable();
      $table->text('description')->nullable();
      $table->string('author_id');
      $table->string('author_name');
      $table->string('domain')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('posts');
  }
};
