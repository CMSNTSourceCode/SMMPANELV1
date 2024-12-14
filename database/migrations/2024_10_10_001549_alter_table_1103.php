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
    // alter table api_providers add rate_per_1k boolean default false not null after status;
    if (Schema::hasTable('api_providers') && !Schema::hasColumn('api_providers', 'rate_per_1k')) {
      Schema::table('api_providers', function (Blueprint $table) {
        $table->boolean('rate_per_1k')->default(true)->after('status');
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    //
  }
};
