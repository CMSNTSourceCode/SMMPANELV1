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
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('username');
      $table->string('password');
      $table->string('fullname')->nullable();
      $table->string('email')->unique();
      $table->string('phone')->nullable();
      $table->string('avatar')->nullable();
      $table->double('balance')->default(0);
      $table->double('balance_1')->default(0);
      $table->double('total_deposit')->default(0);
      $table->double('total_withdraw')->default(0);
      $table->string('currency_code', 10)->default('VND');
      $table->string('status')->default('active');
      $table->string('rank')->default('bronze');
      $table->string('role')->default('user');
      $table->string('language', 10)->default('vn');
      $table->string('referral_by')->nullable();
      $table->string('referral_code')->nullable();
      $table->integer('referral_percent')->default(10);
      $table->timestamp('email_verified_at')->nullable();
      $table->string('access_token')->nullable();
      $table->string('ip_address')->nullable();
      $table->string('register_by')->nullable();
      $table->string('domain')->nullable();
      $table->rememberToken();
      $table->timestamps();
    });

    Schema::create('password_reset_tokens', function (Blueprint $table) {
      $table->string('email')->primary();
      $table->string('token');
      $table->timestamp('created_at')->nullable();
    });

    Schema::create('sessions', function (Blueprint $table) {
      $table->string('id')->primary();
      $table->foreignId('user_id')->nullable()->index();
      $table->string('ip_address', 45)->nullable();
      $table->text('user_agent')->nullable();
      $table->longText('payload');
      $table->integer('last_activity')->index();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('users');
    Schema::dropIfExists('password_reset_tokens');
    Schema::dropIfExists('sessions');
  }
};
