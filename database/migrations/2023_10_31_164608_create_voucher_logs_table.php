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
    Schema::create('voucher_logs', function (Blueprint $table) {
      $table->id();
      $table->string('code');
      $table->string('type');
      $table->integer('value');
      $table->string('status');
      $table->string('ref_id');
      $table->string('content')->nullable();
      $table->string('sys_note')->nullable();
      $table->string('username');
      $table->string('ip_address');
      $table->string('domain')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('voucher_logs');
  }
};
