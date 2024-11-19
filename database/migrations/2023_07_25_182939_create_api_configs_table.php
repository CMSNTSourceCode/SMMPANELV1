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
    Schema::create('api_configs', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->json('value')->nullable();
      $table->string('domain')->nullable();
      $table->string('username')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('api_configs');
  }
};
