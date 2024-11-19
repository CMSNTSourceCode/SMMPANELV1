<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('menu_links', function (Blueprint $table) {
      $table->id();
      $table->text('image');
      $table->string('name');
      $table->string('link');
      $table->string('target')->default('_blank');
      $table->string('domain')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('menu_links');
  }
};
