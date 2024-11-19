<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardList extends Model
{
  use HasFactory;

  protected $fillable = [
    'type',
    'code',
    'serial',
    'value',
    'amount',
    'status',
    'domain',
    'user_id',
    'username',
    'discount',
    'sys_note',
    'content',
    'order_id',
    'request_id',
    'channel_charge',
    'transaction_code',
  ];

  protected $appends = [
    'status_str',
    'status_html',
  ];

  public function getStatusStrAttribute()
  {
    return Helper::formatStatus($this->status, 'text');
  }

  public function getStatusHtmlAttribute()
  {
    return Helper::formatStatus($this->status, 'html');
  }
}
