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
    // alter table services add image varchar(255) null after type;
    if (Schema::hasTable('services') && !Schema::hasColumn('services', 'image')) {
      Schema::table('services', function (Blueprint $table) {
        $table->string('image', 255)->nullable()->after('type');
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
