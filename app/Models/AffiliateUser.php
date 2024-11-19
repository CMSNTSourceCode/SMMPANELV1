<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateUser extends Model
{
  use HasFactory;

  protected $fillable = [
    'code',
    'username',
    'to_username',
    'total_deposit',
    'total_commission',
  ];

  protected $casts = [
    'total_deposit'    => 'float',
    'total_commission' => 'float',
  ];

  public function parent()
  {
    return $this->belongsTo(User::class, 'username', 'username');
  }

  public function affiliate()
  {
    return $this->belongsTo(Affiliate::class, 'code', 'code');
  }
}
