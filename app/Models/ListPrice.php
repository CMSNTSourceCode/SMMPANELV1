<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListPrice extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'value',
  ];

  protected $casts = [
    'value' => 'array',
  ];
}