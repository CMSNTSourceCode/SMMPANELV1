<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  use HasFactory;

  protected $fillable = [
    'pid',
    'name',
    'descr',
    'image',
    'status',
    'priority',
    'platform_id',
  ];

  protected $hidden = [
    'pid',
    'priority',
    'created_at',
    'updated_at',
  ];

  protected $casts = [
    'status'      => 'boolean',
    'platform_id' => 'integer',
  ];

  public function services()
  {
    return $this->hasMany(Service::class);
  }

  public function platform()
  {
    return $this->belongsTo(related: ListPlatform::class);
  }
}
