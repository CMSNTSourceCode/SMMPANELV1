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
    Schema::table('api_providers', function (Blueprint $table) {
      //
      if (!Schema::hasColumn('api_providers', 'price_percentage_increase')) {
        $table->integer('price_percentage_increase')->default(0);
      }
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('api_providers', function (Blueprint $table) {
      //
      $table->dropColumn('price_percentage_increase');
    });
  }
};
