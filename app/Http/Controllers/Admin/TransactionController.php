<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
  public function index()
  {
    return view('admin.transactions.index', [
      'pageTitle' => __t('Admin: Lịch sử giao dịch'),
    ]);
  }

  public function cards()
  {
    $cards = \App\Models\CardList::orderBy('id', 'desc')->limit(2000)->get();

    return view('admin.transactions.cards', [
      'pageTitle' => __t('Admin: Lịch sử nạp thẻ'),
    ], compact('cards'));
  }

  public function bankLogs()
  {
    $logs = \App\Models\BankLog::orderBy('id', 'desc')->limit(2000)->get();

    // group by name
    $logs = $logs->groupBy('name');

    return view('admin.transactions.bank-logs', [
      'pageTitle' => __t('Admin: Lịch sử chuyển khoản'),
    ], compact('logs'));
  }
}
