<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens;
  use HasFactory;
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'email',
    'username',
    'password',
    'fullname',
    'phone',
    'avatar',
    'balance',
    'balance_1',
    'total_deposit',
    'currency_code',
    'total_withdraw',
    'status',
    'rank',
    'role',
    'language',
    'referral_by',
    'referral_code',
    'referral_percent',
    'access_token',
    'ip_address',
    'register_by',
    'last_login_at',
    'last_login_ip',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'password'          => 'hashed',
  ];

  // History
  public function histories()
  {
    return $this->hasMany(History::class);
  }

  // Transaction
  public function transactions()
  {
    return $this->hasMany(Transaction::class);
  }

  public function isAdmin()
  {
    return $this->role === 'admin' && $this->status === 'active';
  }

  // Referral
  public function referrals()
  {
    return $this->hasMany(AffiliateUser::class, 'username', 'username');
  }

  public function referrer()
  {
    return $this->hasOne(AffiliateUser::class, 'to_username', 'username');
  }

  // Affiliate
  public function affiliate()
  {
    return $this->hasOne(Affiliate::class, 'code', 'referral_code');
  }
}
