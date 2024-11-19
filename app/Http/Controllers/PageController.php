<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Affiliate;
use App\Models\Category;
use App\Models\Order;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WalletLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{

  public function statistics(Request $request)
  {
    // Chart data
    $chartCategories = [];

    for ($i = 1; $i <= date('d'); $i++) {
      $chartCategories[] = date('Y-m-d', strtotime(date('Y-m') . '-' . $i));
    }

    // Chart pie
    $chartPieColors = ['#f6ad55', '#68d391', '#4fd1c5', '#63b3ed', '#9f7aea', '#C40C0C', '#ed64a6'];
    $chartPieLabels = ["Pending", "Processing", "Completed", "Cancelled", "Refund", "Error", "Others"];
    $chartPieData   = [];

    foreach ($chartPieLabels as $chartPieLabel) {
      $chartPieData[] = Order::where('order_status', $chartPieLabel)->where('user_id', auth()->id())->count();
    }

    //
    $chartOrders       = [
      [
        'name' => 'Pending',
        'data' => [],
      ],
      [
        'name' => 'Processing',
        'data' => [],
      ],
      [
        'name' => 'Completed',
        'data' => [],
      ],
      [
        'name' => 'Cancelled',
        'data' => [],
      ],
      [
        'name' => 'Refund',
        'data' => [],
      ],
      [
        'name' => 'Error',
        'data' => [],
      ],
      [
        'name' => 'Others',
        'data' => [],
      ],
    ];
    $chartOrdersColors = ['#f6ad55', '#68d391', '#4fd1c5', '#63b3ed', '#9f7aea', '#C40C0C', '#ed64a6'];
    foreach ($chartCategories as $chartCategory) {
      $chartOrders[0]['data'][] = Order::where('order_status', 'Pending')->whereDate('created_at', $chartCategory)->where('user_id', auth()->id())->count();
      $chartOrders[1]['data'][] = Order::where('order_status', 'Processing')->whereDate('created_at', $chartCategory)->where('user_id', auth()->id())->count();
      $chartOrders[2]['data'][] = Order::where('order_status', 'Completed')->whereDate('created_at', $chartCategory)->where('user_id', auth()->id())->count();
      $chartOrders[3]['data'][] = Order::where('order_status', 'Cancelled')->whereDate('created_at', $chartCategory)->where('user_id', auth()->id())->count();
      $chartOrders[4]['data'][] = Order::where('order_status', 'Refund')->whereDate('created_at', $chartCategory)->where('user_id', auth()->id())->count();
      $chartOrders[5]['data'][] = Order::where('order_status', 'Error')->whereDate('created_at', $chartCategory)->where('user_id', auth()->id())->count();
      $chartOrders[6]['data'][] = Order::whereNotIn('order_status', $chartPieLabels)->whereDate('created_at', $chartCategory)->where('user_id', auth()->id())->count();
    }

    // percent of deposit month
    $percentDeposit              = 0;
    $totalDepositInMonth         = Transaction::where('type', 'deposit')->whereMonth('created_at', date('m'))->where('user_id', auth()->id())->sum('amount');
    $totalDepositInPreviousMonth = Transaction::where('type', 'deposit')->whereMonth('created_at', date('m', strtotime('-1 month')))->where('user_id', auth()->id())->sum('amount');

    if ($totalDepositInPreviousMonth > 0) {
      $percentDeposit = (($totalDepositInMonth - $totalDepositInPreviousMonth) / $totalDepositInPreviousMonth) * 100;
    }

    // percent of order month
    $percentOrder              = 0;
    $totalOrderInMonth         = Order::whereMonth('created_at', date('m'))->where('user_id', auth()->id())->count();
    $totalOrderInPreviousMonth = Order::whereMonth('created_at', date('m', strtotime('-1 month')))->where('user_id', auth()->id())->count();

    if ($totalOrderInPreviousMonth > 0) {
      $percentOrder = (($totalOrderInMonth - $totalOrderInPreviousMonth) / $totalOrderInPreviousMonth) * 100;
    }

    //
    $chartCount = [];

    foreach ($chartCategories as $chartCategory) {
      $chartCount[] = Order::where('user_id', auth()->id())->whereDate('created_at', $chartCategory)->count();
    }

    $chartSpent   = [];
    $chartDeposit = [];

    foreach ($chartCategories as $chartCategory) {
      $chartSpent[]   = Transaction::where('type', 'new-order')->whereDate('created_at', $chartCategory)->where('user_id', auth()->id())->sum('amount');
      $chartDeposit[] = Transaction::where('type', 'deposit')->whereDate('created_at', $chartCategory)->where('user_id', auth()->id())->sum('amount');
    }

    return view('pages.statistics', [
      'pageTitle' => 'Statistics',
    ], compact('totalDepositInMonth', 'percentDeposit', 'totalOrderInMonth', 'percentOrder', 'chartCategories', 'chartPieColors', 'chartPieLabels', 'chartPieData', 'chartOrders', 'chartOrdersColors', 'chartCount', 'chartSpent', 'chartDeposit'));
  }

  public function affiliates(Request $request)
  {
    $user      = User::findOrFail(auth()->id());
    $config    = Helper::getConfig('affiliate_config');
    $histories = WalletLog::where('user_id', auth()->id())->orderBy('id', 'desc')->limit(100)->get();

    if (!$user->referral_code) {
      $user->referral_code = str()->random(12);
      $user->save();
    }

    $affiliate = Affiliate::where('username', $user->username)->firstOrCreate(['code' => $user->referral_code, 'username' => $user->username]);

    $referrals = $user->referrals()->orderBy('id', 'desc')->get() ?? [];

    return view('pages.affiliates', compact('user', 'config', 'histories', 'affiliate', 'referrals'));
  }

  public function apiDocs(Request $request)
  {
    return view('pages.api_docs', [
      'pageTitle' => 'API Docs',
    ]);
  }

  public function services(Request $request)
  {
    if (Cache::has('services_groupped')) {
      $groupped = Cache::get('services_groupped');
    } else {
      $services = Service::where('status', true)->get();

      $groupped = $services->groupBy('category.name');

      Cache::put('services_groupped', $groupped, 60 * 5);
    }

    return view('pages.services', [
      'pageTitle' => 'Services',
    ], compact('groupped'));
  }

  public function termsOfService(Request $reqeust)
  {
    return view('pages.terms_of_service', [
      'pageTitle' => 'Terms of Service',
    ]);
  }
}
