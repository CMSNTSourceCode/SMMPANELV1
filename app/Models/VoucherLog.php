<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherLog extends Model
{
  use HasFactory;

  protected $fillable = [
    'code',
    'type',
    'value',
    'ref_id',
    'status',
    'content',
    'sys_note',
    'username',
    'ip_address',
  ];
}
