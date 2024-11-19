<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Helper;

class Order extends Model
{
  use HasFactory;

  protected $fillable = [
    'sid',
    'pid',
    'src_id',
    'src_name',
    'src_resp',
    'src_type',
    'src_cost',
    'src_place',
    'src_status',
    'order_code',
    'object_id',
    'quantity',
    'order_status',
    'ip_addr',
    'domain',
    'start_number',
    'success_count',
    'runs',
    'interval',
    'price_per',
    'total_payment',
    'currency_code',
    'order_actions',
    'order_note',
    'extra_note',
    'extra_data',
    'utm_source',
    'user_id',
    'username',
    'category',
    'server_id',
    'service_id',
    'category_id',
    'server_name',
    'service_slug',
    'service_name',

    'updated_at',
    'created_at',
  ];

  protected $hidden = [
    // 'src_id',
    // 'src_name',
    // 'src_type',
    // 'src_cost',
    // 'extra_note'
  ];

  protected $casts = [
    'src_resp'      => 'array',
    'src_place'     => 'boolean',
    'category'      => 'array',
    'extra_data'    => 'array',
    'order_actions' => 'array',
  ];

  protected $appends = [
    'date_str',
    'profit_str',
    'payment_str',
    'update_date_str',
    'show_action_btn',
    'percent_complete',
    'order_status_str',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function getDateStrAttribute()
  {
    return $this->created_at->format('d/m/Y H:i');
  }

  public function getUpdateDateStrAttribute()
  {
    return $this->updated_at->format('d/m/Y H:i');
  }

  public function getPaymentStrAttribute()
  {
    $payment = $this->total_payment;

    return formatCurrency($payment);
  }

  public function getProfitStrAttribute()
  {
    $role = auth()->user()->role ?? 'user';

    if ($role !== 'admin') {
      return 0;
    }

    $apiPay = $this->src_cost;
    $srcPay = $this->total_payment;

    return (double) ($srcPay - $apiPay);
  }

  public function provider()
  {
    return $this->belongsTo(ApiProvider::class, 'src_name', 'id');
  }

  public function getOrderActionsAttribute()
  {
    $actions = [
      'can_update'   => false,
      'can_resume'   => false,
      'can_refund'   => false,
      'can_warranty' => false,
    ];

    $srcCanUpdate   = in_array($this->src_name, []) ? true : true;
    $srcCanResume   = in_array($this->src_name, []) ? true : false;
    $srcCanRefund   = in_array($this->src_name, []) ? true : false;
    $srcCanWarranty = in_array($this->src_name, []) ? true : false;

    // update
    if ($srcCanUpdate && !in_array($this->order_status, ['Completed', 'WaitForRefund', 'Refund', 'Cancelled'])) {
      $actions['can_update'] = true;
    }
    // resume
    if ($srcCanResume && in_array($this->order_status, ['Paused'])) {
      $actions['can_resume'] = true;
    }
    // refund
    if ($srcCanRefund && in_array($this->order_status, ['Running', 'Processing', 'Holding', 'Paused'])) {
      $actions['can_refund'] = true;
    }
    // warranty
    if ($srcCanWarranty && in_array($this->order_status, ['Completed'])) {
      // in 7 days
      $now = time();
      $end = strtotime($this->updated_at) + 7 * 24 * 60 * 60;
      if ($now < $end) {
        $actions['can_warranty'] = true;
      }
    }


    return $actions;
  }

  public function getShowActionBtnAttribute()
  {
    $actions = $this->order_actions;

    if ($actions['can_update'] || $actions['can_resume'] || $actions['can_refund'] || $actions['can_warranty']) {
      return true;
    }

    return false;
  }

  public function getPercentCompleteAttribute()
  {
    $percent = $this->success_count / $this->quantity * 100;

    return round($percent, 2) . '%';
  }

  public function getOrderStatusStrAttribute()
  {
    return Helper::formatOrderStatus($this->order_status);
  }

  public function scopeDomain($query)
  {
    return $query->where('domain', Helper::getDomain());
  }
}
