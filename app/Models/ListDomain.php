<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListDomain extends Model
{
  use HasFactory;

  protected $fillable = [
    'domain',
    'user_id',
    'username',
    'status'
  ];
}
