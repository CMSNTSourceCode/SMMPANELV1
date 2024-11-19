<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('card_lists', function (Blueprint $table) {
      $table->id();
      $table->string('type');
      $table->string('code');
      $table->string('serial');
      $table->integer('value');
      $table->integer('amount');
      $table->string('status');
      $table->string('user_id');
      $table->string('username');
      $table->integer('discount')->default(30);
      $table->string('sys_note')->nullable();
      $table->string('content')->nullable();
      $table->string('order_id')->nullable();
      $table->string('request_id')->nullable();
      $table->string('channel_charge')->nullable();
      $table->string('transaction_code')->nullable();
      $table->string('domain')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('card_lists');
  }
};
