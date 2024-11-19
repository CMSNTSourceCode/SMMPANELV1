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
    'balance',
    'status',
    'auto_sync',
    'description',
    'currency_code',
    'exchange_rate',
    'price_percentage_increase',
  ];

  protected $casts = [
    'status'                    => 'boolean',
    'auto_sync'                 => 'boolean',
    'price_percentage_increase' => 'float',
  ];
}
