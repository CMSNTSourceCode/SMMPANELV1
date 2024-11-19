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
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->string('pid')->nullable();
      $table->string('sid')->nullable();
      $table->string('src_id');
      $table->string('src_name');
      $table->string('src_type');
      $table->string('src_cost');
      $table->json('src_resp')->nullable();
      $table->boolean('src_place')->default(false);
      $table->string('src_status')->default('Pending');
      $table->string('order_code');
      $table->string('object_id');
      $table->string('quantity');
      $table->integer('start_number');
      $table->integer('success_count');
      $table->integer('runs')->nullable();
      $table->integer('interval')->nullable();
      $table->decimal('price_per', 12, 2);
      $table->decimal('total_payment', 12, 2);
      $table->string('currency_code')->default('USD');
      $table->string('order_note')->nullable();
      $table->string('order_status');
      $table->json('order_actions')->nullable();
      $table->string('extra_note')->nullable();
      $table->json('extra_data')->nullable();
      $table->string('utm_source')->nullable();
      $table->integer('user_id');
      $table->string('username');
      $table->integer('server_id')->nullable();
      $table->integer('service_id');
      $table->string('server_name')->nullable();
      $table->string('service_slug')->nullable();
      $table->string('service_name');
      $table->string('category_id');
      $table->string('category_name')->nullable();
      $table->string('ip_addr')->nullable();
      $table->string('domain')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('orders');
  }
};
