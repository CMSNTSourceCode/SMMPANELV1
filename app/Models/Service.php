<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
  use HasFactory;

  protected $fillable = [
    'sid',
    'pid',
    'name',
    'descr',
    'type',
    'price',
    'original_price',
    'image',
    'refill',
    'refill_type',
    'min_buy',
    'max_buy',
    'add_type',
    'status',
    'dripfeed',
    'deny_duplicates',
    'api_service_id',
    'api_provider_id',
    'category_id',
  ];

  protected $casts = [
    'price'          => 'double',
    'min_buy'        => 'integer',
    'max_buy'        => 'integer',
    'category_id'    => 'integer',
    'original_price' => 'double',
  ];

  protected $hidden = [
    'pid',
    'sid',
    'add_type',
    'api_service_id',
    'api_provider_id',
    'original_price',
    'created_at',
    'updated_at',
  ];

  protected $appends = [
    'price_per',
    'average_time',
    'display_name',
    'price_formatted',
  ];

  public function getDisplayNameAttribute()
  {
    return 'ID ' . $this->id . ' - ' . $this->name . ' - ' . show_price_format($this->price, true) . ' ' . __t('per 1000');
  }

  public function getAverageTimeAttribute()
  {
    $orders = Order::where('service_id', $this->id)
      ->where('order_status', 'Completed')
      ->where('quantity', '>=', 1000)
      ->orderBy('id', 'desc')
      ->limit(10)
      ->get();

    if (count($orders) === 0) {
      return 'New service';
    }

    $total_difference = 0;
    foreach ($orders as $order) {
      $total_difference += $order->created_at->diffInSeconds($order->updated_at);
    }

    $average_seconds = $total_difference / count($orders);

    // Chuyển đổi từ giây sang giờ, phút
    $hours   = floor($average_seconds / 3600);
    $minutes = floor(($average_seconds - ($hours * 3600)) / 60);

    // Hiển thị kết quả
    if ($hours > 0) {
      return $hours . ' hours ' . $minutes . ' minutes'; // Ví dụ: 2h 30m
    }

    if ($minutes > 0) {
      return $minutes . 'm'; // Ví dụ: 30m
    }

    return 'Instant service (under 5 minutes)';
  }

  public function getPricePerAttribute()
  {
    return (double) $this->price / 1000;
  }

  public function getPriceFormattedAttribute()
  {
    return show_price_format($this->price, true);
  }

  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function provider()
  {
    return $this->belongsTo(ApiProvider::class, 'api_provider_id', 'id');
  }
}
