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
    Schema::create('wallet_logs', function (Blueprint $table) {
      $table->id();
      $table->string('type')->default('default');
      $table->integer('amount');
      $table->string('status')->default('Pending');
      $table->string('user_id');
      $table->string('username');
      $table->string('sys_note')->nullable();
      $table->string('user_note')->nullable();
      $table->string('order_id')->nullable();
      $table->string('request_id')->nullable();
      $table->string('user_action');
      $table->json('withdraw_detail')->nullable();
      $table->string('channel_charge')->nullable();
      $table->integer('balance_before')->default(0);
      $table->integer('balance_after')->default(0);
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
    Schema::dropIfExists('wallet_logs');
  }
};
