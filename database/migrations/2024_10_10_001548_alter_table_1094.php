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
    // alter table api_providers add auto_sync boolean default false not null after status;
    if (Schema::hasTable('api_providers') && !Schema::hasColumn('api_providers', 'auto_sync')) {
      Schema::table('api_providers', function (Blueprint $table) {
        $table->boolean('auto_sync')->default(false)->after('status');
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
