<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListPlatform extends Model
{
  use HasFactory;

  protected $fillable = [
    'slug',
    'name',
    'image',
    'status',
    'priority',
  ];
}
