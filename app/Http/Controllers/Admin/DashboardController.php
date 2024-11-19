<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Helper;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function index(Request $request)
  {

    $stats = [];

    // Users stats
    $stats['users'] = [
      'total'               => User::count(),
      'today'               => User::whereDate('created_at', date('Y-m-d'))->count(),
      'balance'             => User::sum('balance'),
      'total_deposit'       => User::sum('total_deposit'),
      'total_deposit_today' => Transaction::where('type', 'deposit')->whereDate('created_at', date('Y-m-d'))->sum('amount'),
      'total_deposit_month' => Transaction::where('type', 'deposit')->whereMonth('created_at', date('Y-m-d'))->sum('amount'),
    ];

    // Users translate
    $stats['t_users'] = [
      'total'               => [
        'label' => __t('Tổng thành viên'),
        'color' => 'danger',
      ],
      'today'               => [
        'label' => __t('Đăng ký hôm nay'),
        'color' => 'primary',
      ],
      'balance'             => [
        'label'  => __t('Tổng số dư'),
        'color'  => 'success',
        'format' => 'currency',
      ],
      'total_deposit'       => [
        'label'  => __t('Tổng tiền nạp'),
        'color'  => 'warning',
        'format' => 'currency',
      ],
      'total_deposit_today' => [
        'label'  => __t('Nạp Hôm Nay'),
        'color'  => 'info',
        'format' => 'currency',
      ],
      'total_deposit_month' => [
        'label'  => __t('Nạp Tháng :month', ['month' => date('m/Y')]),
        'color'  => 'info',
        'format' => 'currency',
      ],
    ];


    // Orders stats
    $stats['orders']   = [
      'total_orders'         => Order::count(),
      'total_orders_payment' => Order::whereDate('created_at', date('Y-m-d'))->sum('total_payment'),
      'total_orders_month'   => Order::whereMonth('created_at', date('Y-m-d'))->count(),
      'total_orders_week'    => Order::whereBetween('created_at', [date('Y-m-d', strtotime('last monday')), date('Y-m-d', strtotime('next sunday'))])->count(),
      'total_orders_today'   => Order::whereDate('created_at', date('Y-m-d'))->count(),
      'total_orders_pending' => Order::where('order_status', 'Pending')->count(),
    ];
    $stats['t_orders'] = [
      'total_orders'         => [
        'label' => __t('Tổng Đơn Hàng'),
        'color' => 'danger',
      ],
      'total_orders_week'    => [
        'label' => __t('Đơn Hàng Tuần Này'),
        'color' => 'primary',
      ],
      'total_orders_today'   => [
        'label' => __t('Đơn Hàng Hôm Nay'),
        'color' => 'info',
      ],
      'total_orders_month'   => [
        'label' => __t('Đơn hàng tháng :month', ['month' => date('m/Y')]),
        'color' => 'info',
      ],
      'total_orders_pending' => [
        'label' => __t('Đơn hàng chờ xử lý'),
        'color' => 'warning',
      ],
      'total_orders_payment' => [
        'label'  => __t('Tổng thanh toán'),
        'color'  => 'success',
        'format' => 'currency',
      ],
    ];
    // Orders Revenue/Profit stats
    $stats['revenue_profit']   = [
      // total
      'total_revenue'       => Order::sum('total_payment'),
      'total_profit'        => Order::sum('total_payment') - Order::sum('src_cost'),

      // month
      'total_revenue_month' => Order::whereMonth('created_at', date('m'))->sum('total_payment'),
      'total_profit_month'  => Order::whereMonth('created_at', date('m'))->sum('total_payment') - Order::whereMonth('created_at', date('m'))->sum('src_cost'),

      // today
      'total_revenue_today' => Order::whereDate('created_at', date('Y-m-d'))->sum('total_payment'),
      'total_profit_today'  => Order::whereDate('created_at', date('Y-m-d'))->sum('total_payment') - Order::whereDate('created_at', date('Y-m-d'))->sum('src_cost'),
    ];
    $stats['t_revenue_profit'] = [
      'total_revenue'       => [
        'label'  => __t('Tổng Doanh Thu'),
        'color'  => 'success',
        'format' => 'currency',
      ],
      'total_profit'        => [
        'label'  => __t('Tổng Lợi Nhuận'),
        'color'  => 'warning',
        'format' => 'currency',
      ],
      'total_revenue_month' => [
        'label'  => __t('Doanh thu tháng :month', ['month' => date('m/Y')]),
        'color'  => 'info',
        'format' => 'currency',
      ],
      'total_profit_month'  => [
        'label'  => __t('Lợi nhuận tháng :month', ['month' => date('m/Y')]),
        'color'  => 'primary',
        'format' => 'currency',
      ],
      'total_revenue_today' => [
        'label'  => __t('Doanh thu hôm nay'),
        'color'  => 'warning',
        'format' => 'currency',
      ],
      'total_profit_today'  => [
        'label'  => __t('Lợi nhuận hôm nay'),
        'color'  => 'danger',
        'format' => 'currency',
      ],
    ];



    // Orders chart
    $chartPieColors = ['#f6ad55', '#68d391', '#4fd1c5', '#63b3ed', '#9f7aea', '#C40C0C', '#ed64a6'];
    $chartPieLabels = ["Pending", "Processing", "Completed", "Cancelled", "Refund", "Error", "Others"];
    $chartPieData   = [];

    foreach ($chartPieLabels as $chartPieLabel) {
      $chartPieData[] = Order::where('order_status', $chartPieLabel)->count();
    }

    // example chart
    $chartCategories = [];

    for ($i = 1; $i <= date('d'); $i++) {
      $chartCategories[] = date('Y-m-d', strtotime(date('Y-m') . '-' . $i));
    }

    // Orders chart
    $chartOrders = [
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
      $chartOrders[0]['data'][] = Order::where('order_status', 'Pending')->whereDate('created_at', $chartCategory)->count();
      $chartOrders[1]['data'][] = Order::where('order_status', 'Processing')->whereDate('created_at', $chartCategory)->count();
      $chartOrders[2]['data'][] = Order::where('order_status', 'Completed')->whereDate('created_at', $chartCategory)->count();
      $chartOrders[3]['data'][] = Order::where('order_status', 'Cancelled')->whereDate('created_at', $chartCategory)->count();
      $chartOrders[4]['data'][] = Order::where('order_status', 'Refund')->whereDate('created_at', $chartCategory)->count();
      $chartOrders[5]['data'][] = Order::where('order_status', 'Error')->whereDate('created_at', $chartCategory)->count();
      $chartOrders[6]['data'][] = Order::whereNotIn('order_status', $chartPieLabels)->whereDate('created_at', $chartCategory)->count();
    }

    // Revenue chart
    $chartProfit  = [];
    $chartRevenue = [];


    foreach ($chartCategories as $chartCategory) {

      if (date('d', strtotime($chartCategory)) > date('d')) {
        continue;
      }

      $chartProfit[]  = 1;
      $chartRevenue[] = 2;
    }

    //
    $chartSpent   = [];
    $chartDeposit = [];

    foreach ($chartCategories as $chartCategory) {
      $chartSpent[]   = Transaction::where('type', '!=', 'deposit')->whereDate('created_at', $chartCategory)->sum('amount');
      $chartDeposit[] = Transaction::where('type', 'deposit')->whereDate('created_at', $chartCategory)->sum('amount');
    }

    return view('admin.dashboard', compact('stats', 'chartCategories', 'chartProfit', 'chartRevenue', 'chartSpent', 'chartDeposit', 'chartPieColors', 'chartPieLabels', 'chartPieData', 'chartOrders', 'chartOrdersColors'));
  }
}
