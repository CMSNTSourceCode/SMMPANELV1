<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WalletLog;
use App\Helpers\Helper;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
  public function withdraw(Request $request)
  {
    $message     = [
      'amount.required'      => 'Vui lòng nhập số tiền muốn rút.',
      'amount.integer'       => 'Số tiền muốn rút phải là số.',
      'withdraw_to.required' => 'Vui lòng chọn kênh rút tiền.',
      'withdraw_to.string'   => 'Kênh rút tiền không hợp lệ.',
      'withdraw_to.in'       => 'Kênh rút tiền không hợp lệ.',
    ];
    $payload     = $request->validate([
      'amount'      => 'required|integer',
      'withdraw_to' => 'required|string|in:bank,wallet'
    ], $message);
    $withdraw_to = $payload['withdraw_to'];

    if ($withdraw_to === 'bank') {
      $payload = array_merge($request->validate([
        'bank_name'      => 'required|string|max:12',
        'user_note'      => 'nullable|string|max:255',
        'account_name'   => 'required|string|max:128',
        'account_number' => 'required|string|max:128',
      ]), $payload);
    }

    $user = User::findOrFail(auth()->user()->id);

    if ($user->balance_1 < $payload['amount']) {
      return response()->json([
        'status'  => 400,
        'message' => 'Số dư hoa hồng không đủ để thực hiện giao dịch này.',
      ], 400);
    }

    $config = Helper::getConfig('affiliate_config');

    $min_withdraw    = $config['min_withdraw'] ?? 0;
    $max_withdraw    = $config['max_withdraw'] ?? 0;
    $withdraw_status = $config['withdraw_status'] ?? 0;

    if (!$withdraw_status) {
      return response()->json([
        'status'  => 400,
        'message' => 'Chức năng rút hoa hồng đang tạm khóa, vui lòng thử lại sau.',
      ], 400);
    }

    if ($payload['amount'] < $min_withdraw) {
      return response()->json([
        'status'  => 400,
        'message' => 'Số tiền rút tối thiểu là ' . number_format($min_withdraw) . 'đ.',
      ], 400);
    }

    if ($payload['amount'] > $max_withdraw) {
      return response()->json([
        'status'  => 400,
        'message' => 'Số tiền rút tối đa là ' . number_format($max_withdraw) . 'đ.',
      ], 400);
    }

    if ($withdraw_to === 'wallet') {
      if (!$user->decrement('balance_1', $payload['amount'])) {
        return response()->json([
          'status'  => 400,
          'message' => 'Có lỗi xảy ra, vui lòng thử lại sau.',
        ], 400);
      }

      $log = WalletLog::create([
        'type'           => 'affiliate',
        'amount'         => $payload['amount'],
        'status'         => 'Completed',
        'sys_note'       => 'Rút tiền về tài khoản website',
        'user_id'        => $user->id,
        'username'       => $user->username,
        'user_note'      => $payload['user_note'] ?? '',
        'order_id'       => Helper::randomNumber(10),
        'request_id'     => Helper::randomString(10),
        'ip_address'     => request()->ip(),
        'user_action'    => $user->username,
        'balance_after'  => $user->balance_1,
        'balance_before' => $user->balance_1 + $payload['amount'],
        'channel_charge' => $withdraw_to,
      ]);

      $user->increment('balance', $payload['amount']);
      $user->increment('total_withdraw', $payload['amount']);

      $user->transactions()->create([
        'code'           => 'AFF-' . Helper::randomString(7, true),
        'amount'         => $payload['amount'],
        'balance_before' => $user->balance - $payload['amount'],
        'balance_after'  => $user->balance,
        'type'           => 'pay-affiliate',
        'status'         => 'paid',
        'content'        => '[Affiliate] Tạo lệnh rút tiền về tài khoản #' . $log->id,
        'extras'         => [],
        'username'       => $user->username,
        'domain'         => $user->domain,
      ]);


      return response()->json([
        'status'  => 200,
        'message' => 'Rút tiền thành công, số dư mới là ' . formatCurrency($user->balance) . '.',
      ], 200);
    } else if ($withdraw_to === 'bank') {
      $bank = Helper::getListBank($payload['bank_name']);

      if ($bank === null) {
        return response()->json([
          'status'  => 400,
          'message' => 'Không tìm thấy ngân hàng mà bạn đã chọn, hãy xem lại.',
        ], 400);
      }

      if (!$user->decrement('balance_1', $payload['amount'])) {
        return response()->json([
          'status'  => 400,
          'message' => 'Có lỗi xảy ra, vui lòng thử lại sau.',
        ], 400);
      }



      $log = WalletLog::create([
        'type'            => 'affiliate',
        'amount'          => $payload['amount'],
        'status'          => 'Pending',
        'sys_note'        => $bank['shortName'] . '|' . $payload['account_number'] . '|' . $payload['account_name'],
        'user_id'         => $user->id,
        'username'        => $user->username,
        'user_note'       => $payload['user_note'] ?? '',
        'order_id'        => Helper::randomNumber(10),
        'request_id'      => Helper::randomString(10),
        'ip_address'      => request()->ip(),
        'user_action'     => $user->username,

        'balance_after'   => $user->balance_1,
        'balance_before'  => $user->balance_1 + $payload['amount'],
        'channel_charge'  => $withdraw_to,
        'withdraw_detail' => [
          'bank_code'      => $payload['bank_name'],
          'bank_name'      => $bank['shortName'],
          'account_number' => $payload['account_number'],
          'account_name'   => $payload['account_name'],
        ],
      ]);

      return response()->json([
        'status'  => 200,
        'message' => 'Lệnh rút tiền #' . $log->order_id . ' đã được tạo, vui lòng đợi.',
      ], 200);
    } else {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy kênh rút tiền mà bạn đã chọn, hãy xem lại.',
      ], 400);
    }
  }
}
