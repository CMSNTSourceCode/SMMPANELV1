<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use App\Models\CardList;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WalletLog;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class DepositController extends Controller
{
  public function check(Request $request)
  {
    if (prj_key() !== $request->input('key') && !$request->has('cmsntdev')) {
      return response()->json([
        'status'  => 400,
        'message' => 'Secret key is invalid',
      ], 400);
    }
    $type = $request->input('type', null);

    if (Cache::has('cron_deposit_' . $type)) {
      return response()->json([
        'status'  => 400,
        'message' => 'Please stop spamming, wait 30 seconds',
      ], 400);
    }

    Cache::put('cron_deposit_' . $type, true, 30);

    $show             = $request->input('show', false);
    $debug            = $request->input('debug', false);
    $debug_1          = $request->input('debug_1', false);
    $api_name         = null;
    $api_token        = null;
    $transactions     = [];
    $account_number   = null;
    $account_password = null;

    if ($type === 'vietcombank') {
      $config = Helper::getApiConfig('web2m_' . $type);

      if (!isset($config['api_token'])) {
        return response()->json([
          'status'  => 400,
          'message' => 'API Token is not set',
        ], 400);
      }
      $api_name         = 'historyapivcbv3';
      $api_token        = $config['api_token'];
      $account_number   = $config['account_number'] ?? null;
      $account_password = $config['account_password'] ?? null;

    } else if ($type === 'tpbank') {
      $config = Helper::getApiConfig('web2m_' . $type);

      if (!isset($config['api_token'])) {
        return response()->json([
          'status'  => 400,
          'message' => 'API Token is not set',
        ], 400);
      }
      $api_name         = 'historyapitpbv3';
      $api_token        = $config['api_token'];
      $account_number   = $config['account_number'] ?? null;
      $account_password = $config['account_password'] ?? null;

    } elseif ($type === 'acb') {
      $config = Helper::getApiConfig('web2m_' . $type);

      if (!isset($config['api_token'])) {
        return response()->json([
          'status'  => 400,
          'message' => 'API Token is not set',
        ], 400);
      }
      $api_name         = 'historyapiacbv3';
      $api_token        = $config['api_token'];
      $account_number   = $config['account_number'] ?? null;
      $account_password = $config['account_password'] ?? null;

    } elseif ($type === 'mbbank') {
      $config = Helper::getApiConfig('web2m_' . $type);

      if (!isset($config['api_token'])) {
        return response()->json([
          'status'  => 400,
          'message' => 'API Token is not set',
        ], 400);
      }
      $api_name         = 'historyapimbv3';
      $api_token        = $config['api_token'];
      $account_number   = $config['account_number'] ?? null;
      $account_password = $config['account_password'] ?? null;
    } elseif ($type === 'acb') {
      $config = Helper::getApiConfig('web2m_' . $type);

      if (!isset($config['api_token'])) {
        return response()->json([
          'status'  => 400,
          'message' => 'API Token is not set',
        ], 400);
      }
      $api_name         = 'historyapiacbv3';
      $api_token        = $config['api_token'];
      $account_number   = $config['account_number'] ?? null;
      $account_password = $config['account_password'] ?? null;
    } elseif ($type === 'bidv') {
      $config = Helper::getApiConfig('web2m_' . $type);

      if (!isset($config['api_token'])) {
        return response()->json([
          'status'  => 400,
          'message' => 'API Token is not set',
        ], 400);
      }
      $api_name         = 'historyapibidvv3';
      $api_token        = $config['api_token'];
      $account_number   = $config['account_number'] ?? null;
      $account_password = $config['account_password'] ?? null;
    } elseif ($type === 'momo') {
      $config = Helper::getApiConfig('web2m_' . $type);

      if (!isset($config['api_token'])) {
        return response()->json([
          'status'  => 400,
          'message' => 'API Token is not set',
        ], 400);
      }
      $api_name  = 'historyapimomo';
      $api_token = $config['api_token'];
    } elseif ($type === 'card') {
      return $this->checkCard($request);
    }

    $info             = Helper::getConfig('deposit_info');
    $prefix           = $info['prefix'] ?? 'GCI';
    $discount         = (int) ($info['discount'] ?? 0);
    $min_amount       = (int) ($info['min_amount'] ?? 0);
    $list_transaction = [];

    if ($api_name === 'historyapimomo') {
      $response = Http::get('https://api.web2m.com/historyapimomo/' . $api_token);

      if ($response->failed()) {
        return response()->json([
          'data' => $response->json(),
          'code' => $response->status(),
        ], 400);
      }

      $transactions = $response->json('momoMsg')['tranList'] ?? [];

      foreach ($transactions as $value) {
        $list_transaction[] = [
          'type'            => 'IN',
          'amount'          => $value['amount'],
          'description'     => $value['comment'] ?? '',
          'transactionID'   => $value['tranId'],
          'transactionDate' => $value['clientTime'] ?? null,
        ];
      }
    } else {
      $response = Http::get("https://api.web2m.com/{$api_name}/{$account_password}/{$account_number}/{$api_token}");

      $transactions = $response->json('transactions') ?? [];

      foreach ($transactions as $value) {
        $list_transaction[] = [
          'type'            => $value['type'],
          'amount'          => $value['amount'],
          'description'     => $value['description'],
          'transactionID'   => $value['transactionID'],
          'transactionDate' => $value['transactionDate'],
        ];
      }
    }

    foreach ($list_transaction as $transaction) {
      Helper::addBankLog([
        'code'       => (string) $transaction['transactionID'] ?? null,
        'name'       => strtoupper($type),
        'type'       => $transaction['type'] ?? 'MM',
        'value'      => $transaction['description'],
        'amount'     => $transaction['amount'],
        'trans_date' => $transaction['transactionDate'],
      ]);
    }


    foreach ($list_transaction as $key => $transaction) {
      if ($transaction['type'] !== 'IN') {
        unset($list_transaction[$key]);
        continue;
      }

      if (!str_contains(strtolower($transaction['description']), strtolower($prefix))) {
        unset($list_transaction[$key]);
        continue;
      }

    }

    if ($debug) {
      return response()->json([
        'data' => $response->json(),
        'code' => $response->status(),
      ], 200);
    }

    if ($debug_1) {
      return response()->json([
        'data' => $list_transaction,
        'code' => $response->status(),
      ], 200);
    }

    if (count($transactions) === 0) {
      return response()->json([
        'data'    => $response->json(),
        'status'  => 200,
        'message' => 'No transactions found #1',
      ], 200);
    }

    if (count($list_transaction) === 0) {
      return response()->json([
        'data'    => $show ? $response->json() : [],
        'status'  => 200,
        'message' => 'No transactions found #2',
      ], 200);
    }

    // foreach ($transactions as $item) {
    //   $invoiceId = Helper::parseOrderId($item['description'], $prefix);

    //   if ($invoiceId === null || $invoiceId === 0) {
    //     if ($show) {
    //       echo 'Không tìm thấy hoá đơn trong giao dịch #' . $item['transactionID'] . ' / ' . $item['description'] . '<br />';
    //     }

    //     continue;
    //   }

    //   $invoice = Invoice::where('code', $prefix . $invoiceId)->where('status', 'Processing')->first();

    //   if ($invoice === null) {
    //     if ($show) {
    //       echo 'Không tìm thấy hóa đơn #' . $invoiceId . ' trong giao dịch hệ thống [MySQL]<br />';
    //     }

    //     continue;
    //   }

    //   if ($invoice->amount > $item['amount']) {
    //     if ($show) {
    //       echo 'Số tiền giao dịch #' . $item['transactionID'] . ' không đủ để thanh toán hóa đơn #' . $invoiceId . '<br />';
    //     }

    //     continue;
    //   }

    //   $user = User::find($invoice->user_id);

    //   if ($user === null) {
    //     if ($show) {
    //       echo 'Không tìm thấy user #' . $invoice->user_id . ' trong giao dịch hệ thống [MySQL]<br />';
    //     }

    //     continue;
    //   }

    //   $code            = 'DPO-' . Helper::randomString(7);
    //   $amount          = (float) $item['amount'];
    //   $description     = $item['description'];
    //   $transactionID   = $item['transactionID'];
    //   $transactionDate = $item['transactionDate'];

    //   $exists = $this->checkInvoice($transactionID);

    //   if ($exists !== null) {
    //     if ($show) {
    //       echo 'Giao dịch #' . $transactionID . ' đã tồn tại trong hệ thống [MySQL]<br />';
    //     }

    //     continue;
    //   }

    //   if (is_numeric($discount) && $discount > 0) {
    //     $amount = $amount + ($amount * $discount) / 100;
    //   }

    //   $user->increment('balance', $amount);
    //   $user->increment('total_deposit', $amount);

    //   $user->transactions()->create([
    //     'code'           => $code,
    //     'amount'         => $amount,
    //     'order_id'       => $transactionID,
    //     'balance_after'  => $user->balance,
    //     'balance_before' => $user->balance - $amount,
    //     'type'           => 'deposit',
    //     'extras'         => $item,
    //     'status'         => 'paid',
    //     'content'        => 'AUTO Deposit ' . strtoupper($type) . ' - ' . $transactionID . '; Discount ' . $discount . '%',
    //     'user_id'        => $user->id,
    //     'username'       => $user->username,
    //   ]);

    //   $invoice->update([
    //     'status'      => 'Completed',
    //     'paid_at'     => now(),
    //     'expired_at'  => now(),
    //     'description' => 'AUTO Deposit ' . strtoupper($type) . ' - ' . $transactionID . '; Discount ' . $discount . '%',
    //   ]);

    //   if ($show) {
    //     echo 'Giao dịch #' . $transactionID . ', số tiền ' . formatCurrency($amount) . ' thành công<br />';
    //   }
    // }

    foreach ($list_transaction as $item) {
      $userId = Helper::parseOrderId($item['description'], $prefix);

      if ($userId === null || $userId === 0) {
        if ($show) {
          echo 'Không tìm thấy user id trong giao dịch #' . $item['transactionID'] . ' / ' . $item['description'] . '<br />';
        }

        continue;
      }

      $user = User::find($userId);

      if ($user === null) {
        if ($show) {
          echo 'Không tìm thấy user #' . $userId . ' trong giao dịch hệ thống [MySQL]<br />';
        }

        continue;
      }

      $code            = 'ATM-' . Helper::randomString(7, true);
      $realAmount      = (float) $item['amount'];
      $description     = $item['description'];
      $transactionID   = (string) $item['transactionID'];
      $transactionDate = $item['transactionDate'];

      $exists = $this->checkInvoice($transactionID);

      if ($exists !== null) {
        if ($show) {
          echo 'Giao dịch #' . $transactionID . ' đã tồn tại trong hệ thống [MySQL]<br />';
        }

        continue;
      }

      $amount     = $realAmount;
      $onDiscount = false;


      if ($discount > 0 && $amount >= $min_amount) {
        $amount     = $amount + ($amount * $discount) / 100;
        $onDiscount = true;
      }

      $user->increment('balance', $amount);
      $user->increment('total_deposit', $amount);

      $user->transactions()->create([
        'code'           => $code,
        'amount'         => $amount,
        'real_amount'    => $realAmount,
        'order_id'       => (string) $transactionID,
        'balance_after'  => $user->balance,
        'balance_before' => $user->balance - $amount,
        'type'           => 'deposit',
        'extras'         => $item,
        'status'         => 'paid',
        'content'        => 'AUTO Deposit ' . strtoupper($type) . ' - ' . $transactionID . ' - Rev: ' . formatCurrency($realAmount) . ' - Discount: ' . ($onDiscount ? $discount : 0) . '%',
        'user_id'        => $user->id,
        'username'       => $user->username,
      ]);


      $this->updateRank($user->id);
      $this->updateCommision($user->id, $realAmount);

      if ($show) {
        echo 'Giao dịch #' . $transactionID . ', số tiền ' . formatCurrency($amount) . ' thành công<br />';
      }
    }

    if ($show === false) {
      return response()->json([
        'data'    => [
          'total_valid' => count($list_transaction),
        ],
        'status'  => 200,
        'message' => 'Completed check transactions',
      ], 200);
    } else {
      return 'Completed check transactions';
    }
  }

  private function checkCard(Request $request)
  {
    if (prj_key() !== $request->input('key')) {
      return response()->json([
        'status'  => 400,
        'message' => 'Secret key is invalid',
      ], 400);
    }

    $config = Helper::getApiConfig('charging_card');

    if (!isset($config['api_url']) || !isset($config['partner_id']) || !isset($config['partner_key'])) {
      return response()->json([
        'status'  => 400,
        'message' => 'API Token is not set',
      ], 400);
    }

    $cards = CardList::where('status', 'Processing')->get();

    if (count($cards) === 0) {
      return response()->json([
        'status'  => 200,
        'message' => 'No cards found',
      ], 200);
    }

    foreach ($cards as $item) {
      $fees = $config['fees'][strtoupper($item->type)] ?? 20;

      $result = Http::post($config['api_url'] . '/chargingws/v2', [
        'telco'      => strtoupper($item->type),
        'code'       => $item->code,
        'serial'     => $item->serial,
        'amount'     => $item->amount,
        'request_id' => $item->request_id,
        'partner_id' => $config['partner_id'],
        'sign'       => md5($config['partner_key'] . $item->code . $item->serial),
        'command'    => 'check',
      ])->json();

      if (!isset($result['status'])) {
        continue;
      }

      switch ($result['status']) {
        case 1:
          $client = User::find($item->user_id);
          if ($client === null) {
            echo '<span style="color: green">' . $item->id . '</span>/<span style="color: red">' . $item->serial . '</span> => KHÔNG TÌM THẤY USER';
            break;
          }

          $amount = $result['declared_value'];

          $real_amount = $amount - ($amount * $fees) / 100;

          $code = 'CARD-' . Helper::randomString(6, true);

          $client->increment('balance', $real_amount);
          $client->increment('total_deposit', $real_amount);

          $client->transactions()->create([
            'code'           => $code,
            'amount'         => $real_amount,
            'balance_after'  => $client->balance,
            'balance_before' => $client->balance - $real_amount,
            'type'           => 'deposit',
            'extras'         => [
              'card_id' => $item->id,
            ],
            'status'         => 'paid',
            'content'        => 'Nạp thẻ thành công #' . $item->serial . '; phí ' . $fees . '%',
            'user_id'        => $client->id,
            'username'       => $client->username,
          ]);

          $this->updateRank($client->id);
          $this->updateCommision($client->id, $real_amount);

          $item->update([
            'value'            => $amount,
            'status'           => 'Completed',
            'amount'           => $real_amount,
            'content'          => $result['message'],
            'transaction_code' => $code,
          ]);

          echo '<span style="color: green">ID: ' . $item->id . '</span>; <span style="color: red">' . $item->serial . '</span> => ' . ($result['message'] ?? 'Unknow error') . '<br />';
          break;
        case 2:
          $item->update([
            'status'  => 'Cancelled',
            'amount'  => 0,
            'content' => $result['message'] ?? 'Unknow error',
          ]);
          echo 'ID: <span style="color: green">' . $item->id . '</span>; SERIAL: <span style="color: red">' . $item->serial . '</span> => ' . ($result['message'] ?? 'Unknow error') . '<br />';
          break;
        case 3:
          $item->update([
            'status'  => 'Error',
            'amount'  => 0,
            'content' => $result['message'] ?? 'Unknow error',
          ]);
          echo 'ID: <span style="color: green">' . $item->id . '</span>; SERIAL: <span style="color: red">' . $item->serial . '</span> => ' . ($result['message'] ?? 'Unknow error') . '<br />';
          break;
        case 4:
          echo ' Hệ thống bảo trì';
          break;
        case 99:
          echo 'ID: <span style="color: green">' . $item->id . '</span>; SERIAL: <span style="color: red">' . $item->serial . '</span> => ' . ($result['message'] ?? 'Unknow error') . '<br />';
          break;
        default:
          echo '<span style="color: green">' . $item->id . '</span>/<span style="color: red">' . $item->serial . '</span> => ' . ($result['message'] ?? 'Unknow error') . '<br />';
          break;
      }
    }
  }

  public function cardCallback(Request $request)
  {
    if (prj_key() !== $request->input('key')) {
      return response()->json([
        'status'  => 400,
        'message' => 'Secret key is invalid',
      ], 400);
    }
    // file_put_contents(base_path('data.json'), $request->all());

    $validate = Validator::make($request->all(), [
      'status'         => 'required|integer',
      'message'        => 'required|string',
      'request_id'     => 'required',
      'declared_value' => 'required|integer',
      'value'          => 'required|integer',
      'amount'         => 'required|integer',
      'code'           => 'required|string',
      'serial'         => 'required|string',
      'telco'          => 'required|string',
      'trans_id'       => 'required|integer',
      'callback_sign'  => 'required|string',
    ]);


    if ($validate->fails()) {
      return response()->json([
        'status'  => 400,
        'message' => 'Dữ liệu không hợp lệ',
      ], 400);
    }

    $payload = $request->all();

    $config = Helper::getApiConfig('charging_card');

    if (!isset($config['partner_key']) || !isset($config['fees'])) {
      return response()->json([
        'status'  => 400,
        'message' => 'API Token is not set',
      ], 400);
    }

    $fees = $config['fees'][$payload['telco']] ?? 20;

    $item = CardList::where('request_id', $payload['request_id'])
      ->where('order_id', $payload['trans_id'])
      ->where('status', 'Processing')
      ->first();

    if ($item === null) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy giao dịch này',
      ], 400);
    }

    $sign = md5($config['partner_key'] . $payload['code'] . $payload['serial']);

    if ($sign !== $payload['callback_sign']) {
      return response()->json([
        'status'  => 400,
        'message' => 'Sai chữ ký',
      ], 400);
    }

    switch ($payload['status']) {
      case 1:
        $client = User::find($item->user_id);
        if ($client === null) {
          return response()->json([
            'status'  => 400,
            'message' => 'Không tìm thấy user',
          ], 400);
        }

        $amount = $payload['declared_value'];

        $real_amount = $amount - ($amount * $fees) / 100;

        $code = 'CARD-' . Helper::randomString(6, true);

        $client->increment('balance', $real_amount);
        $client->increment('total_deposit', $real_amount);

        $client->transactions()->create([
          'code'           => $code,
          'amount'         => $real_amount,
          'balance_after'  => $client->balance,
          'balance_before' => $client->balance - $real_amount,
          'type'           => 'deposit',
          'extras'         => [
            'card_id' => $item->id,
          ],
          'status'         => 'paid',
          'content'        => 'Nạp thẻ thành công #' . $item->serial . '; phí ' . $fees . '%',
          'user_id'        => $client->id,
          'username'       => $client->username,
        ]);

        $item->update([
          'value'            => $amount,
          'status'           => 'Completed',
          'amount'           => $real_amount,
          'content'          => $payload['message'],
          'transaction_code' => $code,
        ]);

        $this->updateRank($client->id);
        $this->updateCommision($client->id, $real_amount);

        return response()->json([
          'data'    => [
            'code'    => $code,
            'amount'  => $real_amount,
            'balance' => $client->balance,
          ],
          'status'  => 200,
          'message' => 'Nạp thẻ thành công',
        ], 200);
      case 2:
        $item->update([
          'status'  => 'Cancelled',
          'amount'  => 0,
          'content' => $payload['message'] ?? 'Unknow error',
        ]);

        return response()->json([
          'data'    => [
            'id'     => $item->id,
            'serial' => $item->serial,
          ],
          'status'  => 400,
          'message' => $payload['message'] ?? 'Unknow error',
        ], 400);
      case 3:
        $item->update([
          'status'  => 'Error',
          'amount'  => 0,
          'content' => $payload['message'] ?? 'Unknow error',
        ]);

        return response()->json([
          'data'    => [
            'id'     => $item->id,
            'serial' => $item->serial,
          ],
          'status'  => 400,
          'message' => $payload['message'] ?? 'Unknow error',
        ], 400);
      case 4:
        echo ' Hệ thống bảo trì';
        break;
      default:
        return response()->json([
          'data'    => [
            'id'     => $item->id,
            'serial' => $item->serial,
            'status' => $payload['status'],
          ],
          'status'  => 400,
          'message' => $payload['message'] ?? 'Unknow error',
        ], 400);
    }
  }

  protected function checkInvoice($transactionID)
  {
    return Transaction::where('order_id', $transactionID)->where('type', 'deposit')->first();
  }


  public function fpaymentCallback(Request $request)
  {
    $payload = $request->validate([
      'request_id'     => 'required|string',
      'token'          => 'required|string',
      'received'       => 'required|numeric',
      'status'         => 'required|string',
      'from_address'   => 'nullable|string',
      'transaction_id' => 'nullable|string',
    ]);

    $token     = $payload['token'];
    $status    = $payload['status'];
    $address   = $payload['from_address'];
    $transId   = $payload['transaction_id'];
    $exchange  = Helper::getApiConfig('fpayment', 'exchange');
    $received  = (double) number_format($payload['received'], 3);
    $requestId = $payload['request_id'];

    //
    $invoice = Invoice::where('request_id', $requestId)->where('status', 'Processing')->first();

    if ($invoice === null) {
      return response()->json([
        'status'  => 400,
        'message' => 'Invoice not found',
      ], 400);
    }

    $user = User::find($invoice->user_id);

    if ($user === null) {
      $invoice->update([
        'status'      => 'Cancelled',
        'description' => 'Không tìm thấy người dùng',
      ]);
      return response()->json([
        'status'  => 400,
        'message' => 'User not found',
      ], 400);
    }

    if ($status === 'completed') {
      $realAmount = $invoice->amount; //$received * ($exchange ?? 23000);

      $invoice->update([
        'status'      => 'Completed',
        'paid_at'     => now(),
        'expired_at'  => now(),
        'description' => 'Deposit FPayment - ' . $transId . ' - Rev ' . $received . '$',
      ]);

      $user->increment('balance', $realAmount);
      $user->increment('total_deposit', $realAmount);

      $user->transactions()->create([
        'code'           => 'FPM-' . Helper::randomString(7),
        'amount'         => $realAmount,
        'order_id'       => $transId,
        'balance_after'  => $user->balance,
        'balance_before' => $user->balance - $realAmount,
        'type'           => 'deposit',
        'extras'         => $payload,
        'status'         => 'paid',
        'content'        => 'Thanh toán hoá đơn #' . $invoice->code,
        'user_id'        => $user->id,
        'username'       => $user->username,
      ]);

      $this->updateRank($user->id);
      $this->updateCommision($user->id, $realAmount);

      return response()->json([
        'data'    => [
          'balance' => $realAmount,
        ],
        'status'  => 200,
        'message' => 'Thanh toán thành công',
      ], 200);
    } else if ($status === 'expired') {
      $invoice->update([
        'status'      => 'Expired',
        'description' => 'KHÔNG NHẬN ĐƯỢC THANH TOÁN',
      ]);

      return response()->json([
        'status'  => 400,
        'message' => 'Thanh toán thất bại',
      ], 400);
    }
  }

  public function pmCallback(Request $request)
  {
    $payload = $request->validate([
      'PAYMENT_ID'        => 'required|string',
      'PAYEE_ACCOUNT'     => 'required|string',
      'PAYMENT_AMOUNT'    => 'required|numeric',
      'PAYMENT_UNITS'     => 'required|string',
      'PAYMENT_BATCH_NUM' => 'required|string',
      'PAYER_ACCOUNT'     => 'required|string',
      'TIMESTAMPGMT'      => 'required|string',
      'V2_HASH'           => 'required|string',
    ]);

    $config = Helper::getApiConfig('perfect_money');

    if (!isset($config['account_id'])) {
      return response()->json([
        'status'  => 400,
        'message' => 'Chưa cấu hình tài khoản Perfect Money.',
      ], 400);
    }

    $string    = $payload['PAYMENT_ID'] . ':' . $payload['PAYEE_ACCOUNT'] . ':' . $payload['PAYMENT_AMOUNT'] . ':' . $payload['PAYMENT_UNITS'] . ':' . $payload['PAYMENT_BATCH_NUM'] . ':' . $payload['PAYER_ACCOUNT'] . ':' . strtoupper(md5($config['passphrase'])) . ':' . $payload['TIMESTAMPGMT'];
    $hashed    = strtoupper(md5($string));
    $amount    = (double) $payload['PAYMENT_AMOUNT'];
    $requestId = $payload['PAYMENT_ID'];

    if ($payload['V2_HASH'] !== $hashed) {
      return response()->json([
        'status'  => 400,
        'message' => 'Invalid hash',
      ], 400);
    }

    $invoice = Invoice::where('request_id', $requestId)->where('type', 'perfect_money')->where('status', 'Processing')->first();

    if ($invoice === null) {
      return response()->json([
        'status'  => 400,
        'message' => 'Invoice not found',
      ], 400);
    }

    $balance = $amount * ($config['exchange'] ?? 23000);

    $user = User::find($invoice->user_id);

    if ($user === null) {
      $invoice->update([
        'status'      => 'Cancelled',
        'description' => 'Không tìm thấy người dùng',
      ]);
      return response()->json([
        'status'  => 400,
        'message' => 'User not found',
      ], 400);
    }

    $invoice->update([
      'status'      => 'Completed',
      'paid_at'     => now(),
      'expired_at'  => now(),
      'description' => 'Deposit Perfect Money - ' . $payload['PAYMENT_BATCH_NUM'] . ' - Rev ' . $amount . '$',
    ]);

    $user->increment('balance', $balance);
    $user->increment('total_deposit', $balance);

    $user->transactions()->create([
      'code'           => 'PM-' . Helper::randomString(7),
      'amount'         => $balance,
      'order_id'       => $payload['PAYMENT_BATCH_NUM'],
      'balance_after'  => $user->balance,
      'balance_before' => $user->balance - $balance,
      'type'           => 'deposit',
      'extras'         => $payload,
      'status'         => 'paid',
      'content'        => 'Thanh toán hoá đơn #' . $invoice->code,
      'user_id'        => $user->id,
      'username'       => $user->username,
    ]);

    $this->updateRank($user->id);
    $this->updateCommision($user->id, $balance);

    return response()->json([
      'data'    => [
        'balance' => $balance,
      ],
      'status'  => 200,
      'message' => 'Thanh toán thành công',
    ], 200);
  }

  private function updateRank($id)
  {
    $user = User::find($id);

    if ($user === null) {
      return null;
    }

    $newRank = Helper::getRankByDeposit($user->total_deposit, $user->rank);

    if ($newRank === $user->rank) {
      return null;
    }

    return $user->update([
      'rank' => $newRank,
    ]);
  }

  private static function updateCommision($userId, $amount)
  {
    $user = User::find($userId);

    if ($user === null) {
      return;
    }

    $affliate = $user->referrer;

    if ($affliate === null) {
      return;
    }

    $parent = $affliate->parent;

    if ($parent === null) {
      return;
    }

    $percent    = setting('comm_percent', 10);
    $commission = ($amount * $percent) / 100;

    $affliate->increment('total_deposit', $amount);
    $affliate->increment('total_commission', $commission);

    $parent->increment('balance_1', $commission);

    $log = WalletLog::create([
      'type'           => 'commission',
      'amount'         => $commission,
      'status'         => 'Completed',
      'user_id'        => $parent->id,
      'order_id'       => 'COM-' . Helper::randomString(7, true),
      'username'       => $parent->username,
      'sys_note'       => $user->username,
      'user_note'      => 'Referral commission; Rev ' . $percent . '%',
      'user_action'    => 'increment',
      'ip_address'     => '127.0.0.1',
      'balance_after'  => $parent->balance_1,
      'balance_before' => $parent->balance_1 - $commission
    ]);

    return $log;
  }
}
