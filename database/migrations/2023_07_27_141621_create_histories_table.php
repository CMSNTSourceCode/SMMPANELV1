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
    Schema::create('histories', function (Blueprint $table) {
      $table->id();
      $table->string('role');
      $table->integer('user_id');
      $table->string('username');
      $table->string('content');
      $table->json('data')->nullable();
      $table->string('ip_address')->nullable();
      $table->string('domain')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('histories');
  }
};
