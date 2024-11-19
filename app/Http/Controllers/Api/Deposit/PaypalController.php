<?php

namespace App\Http\Controllers\Api\Deposit;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalHttp\HttpException;

class PaypalController extends Controller
{
  public function index(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|string',
    ]);
    $user    = User::findOrFail($request->user()->id);
    $config  = Helper::getApiConfig('paypal');
    $orderId = $payload['id'];

    if (!isset($config['client_id']) || !isset($config['client_secret'])) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy cấu hình cổng thanh toán Paypal',
      ], 400);
    }

    // Uncomment nếu dùng trong môi trường production
    $environment = new ProductionEnvironment($config['client_id'], $config['client_secret']);
    // $environment = new SandboxEnvironment($config['client_id'], $config['client_secret']);

    $client    = new PayPalHttpClient($environment);
    $orderData = $request->input();

    $request = new OrdersGetRequest($orderId);

    try {
      $response = $client->execute($request);
      if ($response->statusCode != 200) {
        return response()->json([
          'status'  => 400,
          'message' => 'Đơn hàng không hợp lệ hoặc chưa thanh toán',
        ], 400);
      }
      $order = $response->result;
      if ($order->status != 'COMPLETED') {
        return response()->json([
          'status'  => 400,
          'message' => 'Đơn hàng này chưa được thanh toán',
        ], 400);
      }
      $orderDetail = $order->purchase_units[0];

      $exists = Invoice::where('type', 'paypal')->where('trans_id', $order->id)->first();

      if ($exists !== null) {
        return response()->json([
          'status'  => 400,
          'message' => 'Đơn hàng này đã được xử lý trước đó',
        ], 400);
      }

      $amount = ($config['exchange'] ?? 23000) * $orderDetail->amount->value;

      $invoice = Invoice::create([
        'code'            => 'PPA-' . Helper::randomString(7, true),
        'type'            => 'paypal',
        'status'          => 'completed',
        'amount'          => $amount,
        'user_id'         => $user->id,
        'username'        => $user->username,
        'trans_id'        => $order->id,
        'request_id'      => $order->id,
        'currency'        => 'USD',
        'description'     => 'Paypal rev ' . $orderDetail->amount->value . '$',
        'payment_details' => [],
        'paid_at'         => now(),
        'expired_at'      => now(),
      ]);

      $user->increment('balance', $amount);
      $user->increment('total_deposit', $amount);

      $user->transactions()->create([
        'code'           => $invoice->code,
        'amount'         => (int) $amount,
        'order_id'       => $order->id,
        'balance_after'  => $user->balance,
        'balance_before' => $user->balance - $amount,
        'type'           => 'deposit',
        'extras'         => $payload,
        'status'         => 'paid',
        'content'        => 'Paypal Ref #' . $order->id . ', invoice #' . $invoice->id,
        'user_id'        => $user->id,
        'username'       => $user->username,
      ]);

      return response()->json([
        'status'  => 200,
        // 'message' => 'Đã nạp ' . $orderDetail->amount->value . '$ (~' . Helper::formatCurrency($amount) . ') thành công.',
        // en
        'message' => 'Successfully deposited ' . $orderDetail->amount->value . '$ (~' . Helper::formatCurrency($amount) . ').',
      ], 200);
    } catch (HttpException $e) {
      return response()->json([
        'status'  => 400,
        'message' => $e->getMessage(),
      ], 400);
    } catch (Exception $e) {
      return response()->json([
        'status'  => 500,
        'message' => $e->getMessage(),
      ], 500);
    }

  }
}
