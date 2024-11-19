<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletLog extends Model
{
  use HasFactory;

  protected $fillable = [
    'type',
    'amount',
    'status',
    'user_id',
    'username',
    'sys_note',
    'user_note',
    'order_id',
    'request_id',
    'user_action',
    'channel_charge',
    'withdraw_detail',
    'balance_before',
    'balance_after',
    'ip_address'
  ];

  protected $casts = [
    'withdraw_detail' => 'array',
  ];

  protected $appends = [
    'prefix',
    'status_str',
    'status_html',
  ];

  public function getPrefixAttribute()
  {
    return $this->balance_after > $this->balance_before ? '+' : '-';
  }

  public function getStatusStrAttribute()
  {
    return Helper::formatStatus($this->status, 'text');
  }

  public function getStatusHtmlAttribute()
  {
    return Helper::formatStatus($this->status, 'html');
  }
}
