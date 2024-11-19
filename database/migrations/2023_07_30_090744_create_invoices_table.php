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
    Schema::create('invoices', function (Blueprint $table) {
      $table->id();
      $table->string('code')->unique();
      $table->string('type');
      $table->string('status');
      $table->decimal('amount', 12, 2);
      $table->string('user_id');
      $table->string('username');
      $table->string('trans_id')->nullable();
      $table->string('order_id')->nullable();
      $table->string('request_id')->nullable();
      $table->string('currency')->default('VND');
      $table->string('description')->nullable();
      $table->json('payment_details')->nullable();
      $table->datetime('paid_at')->nullable();
      $table->datetime('expired_at')->nullable();
      $table->string('domain')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('invoices');
  }
};
