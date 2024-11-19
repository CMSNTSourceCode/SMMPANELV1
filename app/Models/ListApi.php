<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListApi extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'api_url',
    'api_token',
    'api_status',
    'status',
    'balance',
    'last_check',
    'domain'
  ];

  protected $casts = [
    'status'     => 'boolean',
    'api_status' => 'boolean',
    'last_check' => 'datetime',
  ];

  // ORM - Eloquent
  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope('domain', function ($query) {
      $query->where('domain', domain());
    });

    static::creating(function ($model) {
      $model->name   = parse_url($model->api_url)['host'];
      $model->domain = domain();
    });

    static::updating(function ($model) {
      $model->domain = domain();
    });

    static::deleting(function ($model) {
      $model->domain = domain();
    });
  }

  public function scopeDomain($query, $domain)
  {
    return $query->where('domain', $domain);
  }
}
