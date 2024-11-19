<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
  use HasFactory;

  protected $fillable = [
    'code',
    'name',
    'flag',
    'status',
    'default',
  ];

  protected $casts = [
    'status'  => 'boolean',
    'default' => 'boolean',
  ];

  public function translates()
  {
    $path = resource_path("lang/{$this->code}.json");

    if (!file_exists($path)) {
      return [];
    }

    return json_decode(file_get_contents($path), true);
  }
}
