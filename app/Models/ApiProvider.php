<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiProvider extends Model
{
  use HasFactory;

  protected $fillable = [
    'pid',
    'url',
    'key',
    'name',
    'type',
    'status',
    'balance',
    'auto_sync',
    'rate_per_1k',
    'description',
    'currency_code',
    'exchange_rate',
    'price_percentage_increase',
  ];

  protected $casts = [
    'status'                    => 'boolean',
    'auto_sync'                 => 'boolean',
    'rate_per_1k'               => 'boolean',
    'price_percentage_increase' => 'float',
  ];
}
