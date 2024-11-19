<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyList extends Model
{
  use HasFactory;

  protected $fillable = [
    'currency_code',
    'currency_symbol',
    'currency_thousand_separator',
    'currency_decimal_separator',
    'currency_decimal',
    'default_price_percentage_increase',
    'auto_rounding_x_decimal_places',
    'is_auto_currency_convert',
    'new_currecry_rate',
  ];
}
