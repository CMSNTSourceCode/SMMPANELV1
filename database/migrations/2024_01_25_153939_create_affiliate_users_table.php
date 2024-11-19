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
    Schema::create('affiliate_users', function (Blueprint $table) {
      $table->id();
      $table->string('code');
      $table->string('username');
      $table->string('to_username');
      $table->decimal('total_deposit', 12, 2)->default(0);
      $table->decimal('total_commission', 12, 2)->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('affiliate_users');
  }
};
