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
    Schema::create('api_providers', function (Blueprint $table) {
      $table->id();
      $table->string('pid');
      $table->string('url');
      $table->string('key', 2024);
      $table->string('name');
      $table->string('type')->default('standard');
      $table->double('balance')->default(0);
      $table->boolean('status')->default(true);
      $table->boolean('auto_sync')->default(false);
      $table->string('description')->nullable();
      $table->string('currency_code')->default('USD');
      $table->integer('exchange_rate')->default(1);
      $table->integer('price_percentage_increase')->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('api_providers');
  }
};
