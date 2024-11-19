<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;

class OrderController extends Controller
{
  public function index(Request $request)
  {
    $services = Service::where('status', true)->get();

    $orderStatus = [
      'Pending'    => Helper::formatOrderStatus('Pending', 'text'),
      'Processing' => Helper::formatOrderStatus('Processing', 'text'),
      'Completed'  => Helper::formatOrderStatus('Completed', 'text'),
      'Cancelled'  => Helper::formatOrderStatus('Cancelled', 'text'),
      'Refund'     => Helper::formatOrderStatus('Refund', 'text'),
    ];

    return view('account.orders.index', [
      'pageTitle' => 'Orders',
    ], compact('services', 'orderStatus'));
  }
}
