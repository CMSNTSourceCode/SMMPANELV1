<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;

class V2Controller extends Controller
{
  public function index(Request $reqeust)
  {
    return response()->json([
      'error' => 'Method Not Allowed',
    ], 405);
  }

  public function process(Request $request)
  {
    $validate = Validator::make($request->all(), [
      'key'    => 'required|string|max:255',
      'action' => 'required|string|in:services,add,status,refill,refill_status,balance',
    ]);

    if ($validate->fails()) {
      return response()->json([
        'error' => $validate->errors()->first(),
      ], 400);
    }

    //Checking the request action is services
    $action = $request->action;

    return $this->$action($request);
  }

  //Place new order
  private function add(Request $request)
  {
    $validate = Validator::make($request->all(), [
      'link'     => 'required|string|max:255',
      'service'  => 'required|integer',
      'quantity' => 'required|integer|min:1',
    ]);

    if ($validate->fails()) {
      return response()->json([
        'error' => $validate->errors()->first(),
      ], 400);
    }

    $payload = $validate->validated();

    // fields
    $service     = Service::where('id', $payload['service'])->first();
    $discount    = 0;
    $quantity    = (int) $payload['quantity'];
    $object_id   = (string) $payload['link'];
    $commentsArr = [];

    if (!$service) {
      return response()->json(['error' => 'Invalid Service Id'], 400);
    }

    $user = User::where('access_token', $request->input('key'))->first();

    $executed = RateLimiter::attempt(
      'send-message:' . $user->id,
      $perMinute = 1,
      function () {
        // Send message...
  
      },
      $decayRate = 1,
    );

    if (!$executed) {
      return response()->json(['error' => 'Too many requests'], 429);
    }


    if (!$user) {
      return response()->json(['error' => 'Invalid API Key'], 401);
    }

    if ($user->status !== 'active') {
      return response()->json(['error' => 'Your account is not active'], 401);
    }

    if ($service->min_buy > 0 && $quantity < $service->min_buy) {
      return response()->json(['error' => 'Minimum quantity is ' . $service->min_buy], 400);
    }

    if ($service->max_buy > 0 && $quantity > $service->max_buy) {
      return response()->json(['error' => 'Maximum quantity is ' . $service->max_buy], 400);
    }

    // check types
    if ($service->type === 'custom_comments') {
      $validateCustom = Validator::make($request->all(), [
        'comments' => 'required|string|max:2048',
      ]);

      if ($validateCustom->fails()) {
        return response()->json([
          'error' => $validateCustom->errors()->first(),
        ], 400);
      }

      $payload = array_merge($payload, $validateCustom->validated());

      $commentsArr = Helper::text2array($payload['comments']);

      if (count($commentsArr) === 0) {
        return response()->json(['error' => 'Invalid comments'], 400);
      }

      $quantity = count($commentsArr);
    }

    // total payment
    $discount     = Helper::getDiscountByRank($user->rank);
    $price_per    = (double) $service->price_per;
    $totalPayment = $quantity * $price_per;

    if ($totalPayment <= 0) {
      return response()->json(['error' => 'Invalid payment'], 400);
    }

    if ($discount > 0) {
      $preCheck = $totalPayment - ($totalPayment * $discount / 100);

      if ($preCheck > 0) {
        $totalPayment = $preCheck;
      }

    }

    if ($user->balance < $totalPayment) {
      return response()->json(['error' => 'Insufficient balance'], 400);
    }

    if (!$user->decrement('balance', $totalPayment)) {
      return response()->json(['error' => 'Failed to place order'], 400);
    }

    $orderPid  = str()->uuid();
    $orderSid  = time();
    $orderCode = 'UC-' . Helper::randomString(8, true);

    $srcCost = 0;

    if ($service->add_type === 'api') {
      $srcCost = ($service->original_price * $totalPayment) / $service->price;
      // $srcCost = $service->original_price * $quantity / 1000;
    }

    $orderData = [
      // 'pid'           => $orderPid,
      // 'sid'           => $orderSid,

      // source
      'src_id'        => -1,
      'src_name'      => $service->add_type === 'api' ? $service->api_provider_id : 'manual',
      'src_type'      => $service->add_type === 'api' ? $service->api_service_id : -1,
      'src_cost'      => $srcCost,
      'src_status'    => 'Pending',

      // order info
      'order_code'    => $orderCode,
      'order_note'    => '',
      'order_status'  => 'Pending',

      // basic info
      'quantity'      => $quantity,
      'object_id'     => $object_id,
      'start_number'  => 0,
      'success_count' => 0,

      // payment info
      'price_per'     => $price_per / 1000,
      'total_payment' => $totalPayment,
      'currency_code' => cur_setting('currency_code', 'USD'),

      // system info
      'ip_addr'       => $request->ip(),
      'domain'        => Helper::getDomain(),
      'utm_source'    => 'api',
      'extra_data'    => [
        'quantity'  => $quantity,
        'object_id' => $object_id,
        'comments'  => $commentsArr,
      ],

      // user info
      'user_id'       => $user->id,
      'username'      => $user->username,

      // service info
      'service_id'    => $service->id,
      'category_id'   => $service->category_id,
      'service_name'  => $service->name,
    ];

    // tiep tuc hoan thanh don hang neu khong co loi xay ra
    $order = Order::create($orderData);

    Transaction::create([
      'code'           => $orderData['order_code'],
      'amount'         => $totalPayment,
      'balance_before' => $user->balance + $totalPayment,
      'balance_after'  => $user->balance,
      'type'           => 'new-order',
      'status'         => 'paid',
      'content'        => '[' . $orderCode . '] Order-API; ServiceID ' . $service->id . '; Quantity ' . $quantity . '; Discount ' . $discount . '%; ObjectID ' . $object_id,
      'extras'         => [
        'id'         => $order->id,
        'src_id'     => $orderData['src_id'],
        'order_code' => $orderData['order_code'],
      ],
      'domain'         => $user->domain,
      'user_id'        => $user->id,
      'username'       => $user->username,
      'order_id'       => $order->id,
    ]);

    $content = "🎉 Đơn hàng mới được tạo thành công (API)!\n\n";
    // $content .= "🔖 Mã đơn hàng: " . $order->order_code . "\n";
    $content .= "🔗 ID đơn hàng: " . $order->id . "\n";
    $content .= "📦 Dịch vụ: ID " . $service->id . " - " . $service->name . "\n";
    $content .= "🎫 Danh mục: " . $service->category->name . "\n";
    $content .= "🔢 Số lượng: " . $quantity . "\n";
    $content .= "💰 Tổng thanh toán: " . number_format($totalPayment, 0, ',', '.') . " " . cur_setting('currency_code', 'VND') . "\n";
    $content .= "🕒 Thời gian: " . date('d/m/Y H:i:s') . "\n";
    $content .= "👤 Người dùng: " . $user->username . "\n";
    $content .= "🔗 Object ID: " . $object_id . "\n";
    $content .= "\n\n";

    Helper::sendMessageTelegramAuto($content);

    return response()->json(['order' => $order->id]);
  }

  // Get Services
  private function services(Request $request)
  {
    $services      = Service::where('status', true)->with('category')->get();
    $modifyService = [];

    foreach ($services as $service) {
      $modifyService[] = [
        "name"     => $service->name,
        "rate"     => (double) $service->price,
        "min"      => (int) $service->min_buy,
        "max"      => (int) $service->max_buy,
        "type"     => ucfirst($service->type),
        "refill"   => $service->refill,
        "cancel"   => $service->cancel,
        "service"  => $service->id,
        "category" => $service->category->name,
      ];
    }

    return response()->json($modifyService);
  }

  //Order Status
  private function status(Request $request)
  {
    $user = User::where('access_token', $request->input('key'))->first();

    if ($user === null) {
      return response()->json(['error' => 'Invalid API Key'], 401);
    }

    if ($request->has('orders')) {
      $validate = Validator::make($request->all(), [
        'orders' => 'required|string',
      ]);

      if ($validate->fails()) {
        return response()->json([
          'error' => $validate->errors()->first(),
        ], 400);
      }
    } else {
      $validate = Validator::make($request->all(), [
        'order' => 'required|integer',
      ]);

      if ($validate->fails()) {
        return response()->json([
          'error' => $validate->errors()->first(),
        ], 400);
      }
    }

    $payload = $validate->validated();

    if ($request->has('orders')) {
      $ids = explode(',', $payload['orders']);
      $ids = array_map('intval', $ids);

      if (count($ids) > 100) {
        return response()->json(['error' => 'Maximum 100 orders'], 400);
      }

      $orders = Order::where('user_id', $user->id)->whereIn('id', $ids)->get();

      if ($orders->count() === 0) {
        return response()->json(['error' => 'Invalid Order Ids'], 400);
      }

      $modifyOrders = [];

      foreach ($orders as $order) {
        $modifyOrders[$order->id] = [
          'charge'        => ($order->total_payment),
          'status'        => ucfirst($order->order_status),
          'remains'       => (int) ($order->quantity - $order->success_count),
          'currency'      => $order->currency_code,
          'start_counter' => (int) $order->start_number,
        ];
      }

      return response()->json($modifyOrders);
    } else {
      //Service
      $order = Order::where('user_id', $user->id)->where('id', $payload['order'])->first();

      if (!$order) {
        return response()->json(['error' => 'Invalid Order Id'], 400);
      }

      return response()->json([
        'charge'        => ($order->total_payment),
        'status'        => ucfirst($order->order_status),
        'remains'       => (int) ($order->quantity - $order->success_count),
        'currency'      => $order->currency_code,
        'start_counter' => (int) $order->start_number,
      ]);
    }
  }

  // Balance
  private function balance(Request $request)
  {
    $user = User::where('access_token', $request->input('key'))->select(['balance'])->first();

    if (!$user) {
      return response()->json(['error' => 'Invalid API Key'], 401);
    }

    return response()->json([
      'balance'  => $user->balance,
      'balancef' => number_format($user->balance),
      'currency' => 'VND',
    ]);
  }
}
