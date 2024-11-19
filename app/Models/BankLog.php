<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankLog extends Model
{
  use HasFactory;

  protected $fillable = [
    'code',
    'name',
    'type',
    'value',
    'amount',
    'trans_date'
  ];
}
