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
    Schema::create('services', function (Blueprint $table) {
      $table->id();
      $table->string('pid');
      $table->string('name');
      $table->longText('descr')->nullable();
      $table->string('type')->default('default');
      $table->string('image')->nullable();
      $table->double('price');
      $table->double('original_price')->nullable();
      $table->boolean('refill')->default(false);
      $table->string('refill_type')->default('manual');
      $table->integer('min_buy')->nullable();
      $table->integer('max_buy')->nullable();
      $table->string('add_type')->default('manual');
      $table->boolean('status')->default(true);
      $table->integer('dripfeed')->default(0);
      $table->boolean('deny_duplicates')->default(true);
      $table->string('api_service_id')->nullable();
      $table->string('api_provider_id')->nullable();
      $table->integer('category_id')->nullable()->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('services');
  }
};
