<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  use HasFactory;

  protected $fillable = [
    'title',
    'slug',
    'meta_data',
    'status',
    'type',
    'thumbnail',
    'content',
    'description',
    'author_id',
    'author_name',
  ];

  protected $casts = [
    'status'    => 'boolean',
    'meta_data' => 'array',
  ];

  public static function generateSlug($title)
  {
    $slug = str()->slug($title);

    if (self::where('slug', $slug)->exists()) {
      $slug .= '-' . rand(1000, 9999);
    }

    return $slug;
  }

}
