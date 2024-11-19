<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
  use HasFactory;

  protected $fillable = [
    'role',
    'data',
    'domain',
    'user_id',
    'content',
    'username',
    'ip_address',
  ];

  protected $casts = [
    'user_id'    => 'integer',
    'username'   => 'string',
    'content'    => 'string',
    'role'       => 'string',
    'data'       => 'array',
    'ip_address' => 'string',
  ];
}
