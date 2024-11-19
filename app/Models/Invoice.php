<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
  use HasFactory;

  protected $fillable = [
    'code',
    'type',
    'status',
    'amount',
    'user_id',
    'username',
    'currency',
    'trans_id',
    'order_id',
    'request_id',
    'description',
    'payment_details',
    'paid_at',
    'expired_at',
  ];

  protected $hidden = [
    'trans_id',
    'order_id',
    'request_id',
  ];

  protected $casts = [
    'paid_at'         => 'datetime',
    'expired_at'      => 'datetime',
    'payment_details' => 'array',
  ];

  protected $appends = [
    'is_paid',
    'is_expired',
    'expired_str',
    'status_class',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public static function generateCode()
  {
    $code = 'SNV3-' . rand(100000, 999999);
    if (self::where('code', $code)->exists()) {
      return self::generateCode();
    }

    return $code;
  }

  public function getIsPaidAttribute()
  {
    return $this->status === 'Completed';
  }

  public function getIsExpiredAttribute()
  {
    if ($this->expired_at === null) {
      return true;
    }

    if ($this->getIsPaidAttribute()) {
      return true;
    }

    if ($this->status === 'Expired') {
      return true;
    }

    if ($this->status === 'Cancelled') {
      return true;
    }

    $isPast = $this->expired_at->isPast();

    if ($isPast && $this->status === 'Processing') {
      $this->update(['status' => 'Expired']);
    }

    return $isPast;
  }

  public function getExpiredStrAttribute()
  {
    return Helper::getRemainingHours($this->expired_at, '%h giờ');
  }

  public function getStatusClassAttribute()
  {
    if ($this->status === 'Paid') {
      return 'success';
    }

    if ($this->status === 'Pending') {
      return 'warning';
    }

    if ($this->status === 'Expired') {
      return 'danger';
    }

    if ($this->status === 'Cancelled') {
      return 'error';
    }

    return 'error';
  }
}
