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
    // alter table api_providers modify `key` varchar(2000) not null;
    if (Schema::hasTable('api_providers') && !Schema::hasColumn('api_providers', 'key')) {
      Schema::table('api_providers', function (Blueprint $table) {
        $table->string('key', 2000)->change();
      });
    }

    // alter table categories add platform_id int null after priority;
    if (Schema::hasTable('categories') && !Schema::hasColumn('categories', 'platform_id')) {
      Schema::table('categories', function (Blueprint $table) {
        $table->integer('platform_id')->nullable()->after('priority');
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
