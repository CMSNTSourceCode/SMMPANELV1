<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiConfig extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'value',
    'domain',
    'username'
  ];

  protected $casts = [
    'value' => 'array',
  ];
}
