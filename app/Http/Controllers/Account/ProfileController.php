<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\CurrencyList;
use App\Models\History;
use App\Models\Transaction;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProfileController extends Controller
{
  public function index()
  {
    $user                = User::find(auth()->user()->id);
    $stats               = [
      'balance'          => formatCurrency($user->balance),
      'total_spent'      => formatCurrency($user->total_deposit - $user->balance),
      'total_deposit'    => formatCurrency($user->total_deposit),
      'deposit_in_month' => formatCurrency(Transaction::where('user_id', $user->id)->where('type', 'deposit')->whereMonth('created_at', date('m'))->sum('amount')),
    ];
    $histories           = History::where('user_id', $user->id)->orderBy('id', 'desc')->limit(100)->get();
    $totalDepositInMonth = Transaction::where('user_id', $user->id)->where('type', 'deposit')->whereMonth('created_at', date('m'))->sum('amount');



    return view('account.profile.index', [
      'pageTitle' => __t('Thông tin tài khoản'),
    ], compact('user', 'stats', 'histories', 'totalDepositInMonth'));
  }

  public function transactions()
  {
    // Chart data
    $chartCategories = [];

    for ($i = 1; $i <= date('d'); $i++) {
      $chartCategories[] = date('Y-m-d', strtotime(date('Y-m') . '-' . $i));
    }

    $chartSpent   = [];
    $chartDeposit = [];

    foreach ($chartCategories as $chartCategory) {
      $chartSpent[]   = Transaction::where('type', 'new-order')->whereDate('created_at', $chartCategory)->where('user_id', auth()->id())->sum('amount');
      $chartDeposit[] = Transaction::where('type', 'deposit')->whereDate('created_at', $chartCategory)->where('user_id', auth()->id())->sum('amount');
    }

    return view('account.profile.transactions', [
      'pageTitle' => __t('Lịch Sử Giao Dịch'),
    ], compact('chartCategories', 'chartSpent', 'chartDeposit'));
  }

  public function tokenUpdate(Request $request)
  {
    $user = User::find(auth()->user()->id);

    $user->tokens()->delete();

    $user->update([
      'access_token' => explode('|', $user->createToken('access_token')->plainTextToken)[1],
    ]);

    Helper::addHistory(__t('Thay đổi access_token tài khoản thành công'));

    return response()->json([
      'data'    => [
        'access_token' => $user->access_token,
      ],
      'status'  => 200,
      'message' => __t('Cập nhật access_token thành công'),
    ]);
  }

  public function currencyUpdate(Request $request)
  {
    $payload = $request->validate([
      'id' => 'nullable|integer',
    ]);

    Cache::forget('cur_setting');
    Cache::forget('cur_user_setting');

    $user = User::find(auth()->user()->id);

    if (is_null($payload['id'])) {
      $user->update([
        'currency_code' => cur_setting('currency_code'),
      ]);

      return response()->json([
        'status'  => 200,
        'message' => __t('Đã khôi phục loại tiền tệ mặc định'),
      ]);
    }

    $currency = CurrencyList::find($payload['id']);

    if (!$currency) {
      return response()->json([
        'status'  => 404,
        'message' => __t('Loại tiền tệ không tồn tại'),
      ], 404);
    }

    if ($user->currency_code === $currency->currency_code) {
      return response()->json([
        'data'    => [
          'new'     => $currency->currency_code,
          'current' => $user->currency_code,
        ],
        'status'  => 400,
        'message' => __t('Loại tiền tệ không có thay đổi'),
      ], 400);
    }

    $user->update([
      'currency_code' => $currency->currency_code,
    ]);

    return response()->json([
      'status'  => 200,
      'message' => __t('Cập nhật loại tiền tệ thành công'),
    ]);
  }

  public function passwordUpdate(Request $request)
  {
    $payload = $request->validate([
      'old_password'     => 'required|string|min:6',
      'new_password'     => 'required|string|min:6',
      'confirm_password' => 'required|string|min:6',
    ]);

    if (env('PRJ_DEMO_MODE', false) === true) {
      return redirect()->back()->withErrors([
        'old_password' => __t('Chức năng này không hoạt động trong chế độ demo'),
      ]);
    }

    $user = User::find(auth()->user()->id);

    if (!password_verify($payload['old_password'], $user->password)) {
      return redirect()->back()->withErrors([
        'old_password' => __t('Mật khẩu cũ không chính xác'),
      ]);
    }

    if ($payload['new_password'] !== $payload['confirm_password']) {
      return redirect()->back()->withErrors([
        'confirm_password' => __t('Mật khẩu xác nhận không chính xác'),
      ]);
    }

    $user->password = bcrypt($payload['new_password']);

    if ($user->save()) {
      $user->tokens()->delete();

      $user->update([
        'access_token' => explode('|', $user->createToken('access_token')->plainTextToken)[1],
      ]);
    }

    Helper::addHistory('Thay đổi mật khẩu thành công');

    return redirect()->back()->with('success', __t('Cập nhật mật khẩu thành công'));
  }
}
