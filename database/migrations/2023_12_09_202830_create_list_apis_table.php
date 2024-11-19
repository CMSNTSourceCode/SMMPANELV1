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
    Schema::create('list_apis', function (Blueprint $table) {
      $table->id();
      $table->string('name')->nullable();
      $table->string('api_url');
      $table->string('api_token');
      $table->string('api_status')->default(true);
      $table->boolean('status')->default(true);
      $table->double('balance', 11, 2)->default(0);
      $table->dateTime('last_check')->nullable();
      $table->string('domain')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('list_apis');
  }
};
