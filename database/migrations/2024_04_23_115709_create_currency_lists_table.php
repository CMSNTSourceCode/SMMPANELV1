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
    Schema::create('currency_lists', function (Blueprint $table) {
      $table->id();
      $table->string('currency_code');
      $table->string('currency_symbol');
      $table->string('currency_thousand_separator')->default('dot');
      $table->string('currency_decimal_separator')->default('dot');
      $table->integer('currency_decimal')->default(2);
      $table->integer('default_price_percentage_increase')->default(0);
      $table->integer('auto_rounding_x_decimal_places')->default(2);
      $table->boolean('is_auto_currency_convert')->default(false);
      $table->integer('new_currecry_rate')->default(1);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('currency_lists');
  }
};
