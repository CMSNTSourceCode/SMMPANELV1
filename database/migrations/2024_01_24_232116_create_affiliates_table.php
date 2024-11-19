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
    Schema::create('affiliates', function (Blueprint $table) {
      $table->id();
      $table->string('code');
      $table->integer('clicks')->default(0);
      $table->integer('signups')->default(0);
      $table->string('username');
      $table->integer('purchases')->default(0);
      $table->integer('commissions')->default(0);
      $table->integer('total_deposit')->default(0);
      $table->integer('total_item_buy')->default(0);
      $table->integer('total_register')->default(0);
      $table->integer('total_boost_buy')->default(0);
      $table->integer('total_account_buy')->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('affiliates');
  }
};
