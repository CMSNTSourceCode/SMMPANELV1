<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VoucherLog;
use App\Helpers\Helper;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
  public function redeem(Request $request)
  {
    $payload = $request->validate([
      'code' => 'required|string|exists:vouchers,code',
    ]);

    $user = User::findOrFail(auth()->user()->id);
    $code = $payload['code'];

    $exists = VoucherLog::where('code', $code)->where('username', $user->username)->first();

    if ($exists) {
      return response()->json([
        'status'  => 400,
        'message' => 'Mã quà tặng này đã được sử dụng trên tài khoản này.',
      ], 400);
    }

    $ipExists = VoucherLog::where('code', $code)->where('ip_address', $request->ip())->first();
    if ($ipExists) {
      return response()->json([
        'status'  => 400,
        'message' => 'Mã quà tặng này đã được sử dụng trên thiết bị này.',
      ], 400);
    }


    $checkVoucher = Helper::checkVoucher($code, $user);

    if ($checkVoucher['error'] !== false) {
      return response()->json([
        'status'  => 400,
        'message' => $checkVoucher['message'],
      ], 400);
    }

    $value = $checkVoucher['value'];

    if ($user->status !== 'active') {
      return response()->json([
        'status'  => 400,
        'message' => 'Tài khoản của bạn đang bị khóa, vui lòng liên hệ admin để được hỗ trợ.',
      ], 400);
    }

    if (!$user->increment('balance', $value)) {
      return response()->json([
        'status'  => 400,
        'message' => 'Có lỗi xảy ra, vui lòng thử lại sau.',
      ], 400);
    }

    $trans = $user->transactions()->create([
      'code'           => 'GC-' . Helper::randomString(8, true),
      'amount'         => $value,
      'balance_before' => $user->balance - $value,
      'balance_after'  => $user->balance,
      'type'           => 'gift-code',
      'status'         => 'paid',
      'content'        => 'Nhận mã quà tặng: ' . $code,
      'extras'         => [],
      'domain'         => $user->domain,
      'username'       => $user->username,
    ]);

    VoucherLog::create([
      'code'       => $code,
      'type'       => $user->rank,
      'value'      => $value,
      'ref_id'     => $trans->code,
      'status'     => 'Completed',
      'content'    => 'Nhận được ' . formatCurrency($value),
      'sys_note'   => '',
      'username'   => $user->username,
      'ip_address' => $request->ip(),
    ]);

    return response()->json([
      'status'  => 200,
      'message' => 'Chúc mừng bạn đã nhận được ' . formatCurrency($value) . '.',
    ], 200);
  }
}
