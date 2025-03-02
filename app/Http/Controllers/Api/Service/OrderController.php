<?php

namespace App\Http\Controllers\Api\Service;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Libraries\SMMApi;
use App\Models\Order;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class OrderController extends Controller
{
  public function index(Request $request)
  {
    $payload = $request->validate([
      'page'       => 'nullable|integer|regex:/^[0-9]+$/',
      'limit'      => 'nullable|integer|regex:/^[0-9]+$/',
      'search'     => 'nullable|string|max:255',
      'slug'       => 'nullable|string|max:255',
      'category'   => 'nullable|string|max:255',
      'username'   => 'nullable|string|max:255',
      'status'     => 'nullable|string|in:Pending,Processing,Completed,WaitingForRefund,Refund,Error,Running',
      'sortBy'     => 'nullable|string|max:255',
      'sortType'   => 'nullable|string|in:asc,desc',
      'service_id' => 'nullable|integer|regex:/^[0-9]+$/',
      'end_date'   => 'nullable|date',
      'start_date' => 'nullable|date',
    ]);

    $limit     = $payload['limit'] ?? 10;
    $page      = $payload['page'] ?? 1;
    $search    = $payload['search'] ?? null;
    $slug      = $payload['slug'] ?? null;
    $category  = $payload['category'] ?? null;
    $username  = $payload['username'] ?? null;
    $status    = $payload['status'] ?? null;
    $sortBy    = $payload['sortBy'] ?? 'id';
    $sortType  = $payload['sortType'] ?? 'desc';
    $serviceId = $payload['service_id'] ?? null;

    $user = User::find($request->user()->id);

    $query = Order::query(); //Order::domain();

    if ($user->role !== 'admin') {
      $query->where('user_id', $user->id);
    }

    $executed = RateLimiter::attempt(
      'get-order:' . $user->id,
      $perMinute = 1,
      function () {
        // Send message...
      },
      $decayRate = 1,
    );

    if (!$executed) {
      return response()->json(['error' => 'Too many requests'], 429);
    }

    if ($serviceId !== null) {
      $query->where('service_id', $serviceId);
    }

    if ($slug !== null) {
      $query->where('service_slug', $slug);
    }

    if ($category !== null) {
      $query->where('category', $category);
    }

    if ($username !== null) {
      $query->where('username', $username);
    }

    if ($search !== null) {
      $query = $query->where(function ($query) use ($search, $user) {
        $query->where('id', 'like', '%' . $search . '%')
          ->where('order_code', 'like', '%' . $search . '%')
          ->orWhere('object_id', 'like', '%' . $search . '%')
          ->orWhere('username', 'like', '%' . $search . '%')
          ->orWhere('order_note', 'like', '%' . $search . '%');

        if ($user->isAdmin()) {
          $query->orWhere('src_id', 'like', '%' . $search . '%');
        }
      });
    }

    if ($status !== null) {
      $query->where('order_status', $status);
      // $query->whereIn('order_status', $status);
    }

    if (isset($payload['start_date'])) {
      $query->whereDate('created_at', '>=', $payload['start_date']);
    }

    if (isset($payload['end_date'])) {
      $query->whereDate('created_at', '<=', $payload['end_date']);
    }

    $total = $query->count();

    $rows = $query->orderBy($sortBy, $sortType)
      ->take($limit)
      ->skip(($page - 1) * $limit)
      ->get();

    if ($user->isAdmin()) {
      $rows = $rows->map(function ($row) {
        $row->provider_name = $row->provider?->name ?? null;
        return $row;
      });
    }

    return response()->json([
      'data'    => [
        'meta' => [
          'page'  => (int) $page,
          'limit' => (int) $limit,
          'total' => (int) $total,
        ],
        'data' => $rows,
      ],
      'status'  => 200,
      'message' => 'Get list orders successfully!',
    ]);
  }

  public function getById(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|integer|regex:/^[0-9]+$/',
    ]);

    $id = $payload['id'];

    $user = User::find($request->user()->id);

    $order = Order::query();

    if ($user->isAdmin() === false) {
      $order->where('user_id', $user->id);
    }

    $order = $order->where('id', $id)->first();

    if ($order === null) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy đơn hàng này, vui lòng kiểm tra lại!',
      ], 400);
    }

    if ($user->isAdmin()) {
      $order->provider_name = $order->provider?->name ?? null;
    }

    return response()->json([
      'data'    => $order,
      'status'  => 200,
      'message' => 'Lấy thông tin đơn hàng thành công!',
    ]);
  }

  public function getByIds(Request $request)
  {
    $payload = $request->validate([
      'ids' => 'required|string',
    ]);

    $ids = $payload['ids'];

    if ($ids === null) {
      return response()->json([
        'status'  => 400,
        'message' => 'Vui lòng nhập danh sách ID đơn hàng!',
      ], 400);
    }

    $ids = explode(',', $ids);

    if (count($ids) > 200) {
      return response()->json([
        'status'  => 400,
        'message' => 'Vui lòng không nhập quá 200 ID đơn hàng!',
      ], 400);
    }

    $ids = array_filter($ids, function ($id) {
      return is_numeric($id);
    });
    $ids = array_map(function ($id) {
      return (int) $id;
    }, $ids);
    $ids = array_unique($ids);

    $orders = Order::where('user_id', $request->user()->id)->whereIn('id', $ids)->get([
      'id',
      'order_code',
      'object_id',
      'quantity',
      'status',
      'server_id',
      'total_payment',
      'start_number',
      'success_count',
      'created_at',
    ]);

    return response()->json([
      'data'    => $orders,
      'status'  => 200,
      'message' => 'Lấy danh sách đơn hàng thành công!',
    ]);
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'quantity'    => 'required|integer|min:1|regex:/^[0-9]+$/',
      'object_id'   => 'required|string|max:255',
      'service_id'  => 'required|integer|regex:/^[0-9]+$/',
      'order_note'  => 'nullable|string|max:255',
      'is_multiple' => 'nullable|boolean',
    ]);

    $executed = RateLimiter::attempt(
      'store-order:' . $request->user()->id,
      $perMinute = 1,
      function () {
        // Send message...
      },
      $decayRate = 1,
    );

    if (!$executed) {
      return response()->json(['status' => 429, 'message' => 'Too many requests'], 429);
    }

    $user = User::find($request->user()?->id ?? null);
    if ($user === null) {
      return response()->json([
        'status'  => 400,
        'message' => __t('CAN_NOT_FIND_USER'),
      ], 400);
    }

    if ($user->status !== 'active') {
      return response()->json([
        'status'  => 403,
        'message' => __t('ACCOUNT_IS_LOCKED'),
      ], 403);
    }

    $service = Service::where('status', true)->find($payload['service_id']);

    if ($service === null) {
      return response()->json([
        'status'  => 400,
        'message' => __t('CAN_NOT_FIND_SERVICE'),
      ], 400);
    }

    // fields
    $discount     = 0;
    $quantity     = $payload['quantity'];
    $price_per    = (double) $service->price;
    $object_id    = $payload['object_id'];
    $service_id   = $payload['service_id'];
    $is_multiple  = $payload['is_multiple'] ?? false;
    $commentsArr  = [];
    $objectsIdArr = [];

    //
    if ($is_multiple) {
      $objectsIdArr = Helper::text2array($payload['object_id']);

      if (!is_array($objectsIdArr) || count($objectsIdArr) === 0) {
        return response()->json([
          'status'  => 422,
          'message' => __t('PLEASE_ENTER_AT_LEAST_ONE_OBJECT_ID'),
        ], 422);
      }

      if (count($objectsIdArr) === 1) {
        $object_id   = $objectsIdArr[0];
        $is_multiple = false;
      }
    } else {
      $objectsIdArr[] = $object_id;
    }

    if (count($objectsIdArr) > 50) {
      return response()->json([
        'status'  => 400,
        'message' => __t('MAXIMUM_OBJECT_ID_IS_50'),
      ], 400);
    }

    // custom types
    if ($service->type === 'custom_comments' || $service->type === 'custom_comment') {
      $payload = array_merge($payload, $request->validate([
        'comments' => 'required|string|max:5000',
      ]));

      $commentsArr = Helper::text2array($payload['comments']);

      if (count($commentsArr) === 0) {
        return response()->json([
          'status'  => 422,
          'message' => __t('PLEASE_ENTER_AT_LEAST_ONE_COMMENT'),
        ], 422);
      }

      $quantity = count($commentsArr);
    }

    if ($service->min_buy > 0 && $quantity < $service->min_buy) {
      return response()->json([
        'status'  => 400,
        'message' => __t('MINIMUM_QUANTITY_IS') . ' ' . $service->min_buy,
      ], 400);
    }

    if ($service->max_buy > 0 && $quantity > $service->max_buy) {
      return response()->json([
        'status'  => 400,
        'message' => __t('MAXIMUM_QUANTITY_IS') . ' ' . $service->max_buy,
      ], 400);
    }

    $discount = Helper::getDiscountByRank($user->rank);

    $totalPayment = ($quantity / 1000) * $price_per;

    if ($is_multiple) {
      $totalPayment = $totalPayment * count($objectsIdArr);
    }

    $totalPayment = (double) number_format($totalPayment, 4, '.', '');

    if ($totalPayment < 0) {
      return response()->json([
        'status'  => 400,
        'message' => __t('INVALID_PAYMENT_AMOUNT'),
      ], 400);
    }

    if ($discount > 0) {
      $preCheck = $totalPayment - ($totalPayment * $discount / 100);

      if ($preCheck > 0) {
        $totalPayment = $preCheck;
      }

    }

    if ($user->balance < $totalPayment) {
      return response()->json([
        'status'  => 403,
        'message' => __t('INSUFFICIENT_BALANCE'),
      ], 403);
    }

    if ($user->decrement('balance', $totalPayment) === false) {
      return response()->json([
        'status'  => 500,
        'message' => __t('PAYMENT_ERROR'),
      ], 500);
    }

    $created = [];

    foreach ($objectsIdArr as $object_id) {
      $orderPid  = str()->uuid();
      $orderSid  = time();
      $orderCode = 'LC-' . Helper::randomString(8, true);

      $srcCost = 0;

      if ($service->add_type === 'api') {
        $srcCost = ($service->original_price * $totalPayment) / $service->original_price;
      }

      // one order of multiple orders (count($objectsIdArr) > 1)
      $totalPaymentOfOrder = $totalPayment;

      if ($is_multiple) {
        $totalPaymentOfOrder = $totalPayment / count($objectsIdArr);
      }

      $orderData = [
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
        'total_payment' => $totalPaymentOfOrder,
        'currency_code' => cur_setting('currency_code', 'VND'),

        // system info
        'ip_addr'       => $request->ip(),
        'domain'        => Helper::getDomain(),
        'utm_source'    => 'web',
        'extra_data'    => [
          'discount'  => $discount,
          'quantity'  => $quantity,
          'comments'  => $commentsArr,
          'object_id' => $object_id,
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
      $created[] = Order::create($orderData);
    }

    $created = collect($created);

    if ($is_multiple) {
      $content = 'ServiceID ' . $service->id . ', Quantity: ' . $quantity . '; ids: ' . $created->pluck('id')->join(', ');
    } else {
      $content = 'ServiceID ' . $service->id . ', Quantity: ' . $quantity . '; id: ' . $created[0]->id;
    }

    $trans = Transaction::create([
      'code'           => $orderData['order_code'],
      'amount'         => $totalPayment,
      'balance_before' => $user->balance + $totalPayment,
      'balance_after'  => $user->balance,
      'type'           => 'new-order',
      'status'         => 'paid',
      'content'        => $content,
      'extras'         => [],
      'domain'         => $user->domain,
      'user_id'        => $user->id,
      'username'       => $user->username,
      'order_id'       => null,
    ]);

    if (!$is_multiple) {
      $content = "🎉 Đơn hàng mới được tạo thành công!\n\n";
      // $content .= "🔖 Mã đơn hàng: " . $order->order_code . "\n";
      $content .= "🔗 ID đơn hàng: " . $created[0]->id . "\n";
      $content .= "📦 Dịch vụ: ID " . $service->id . " - " . $service->name . "\n";
      $content .= "🎫 Danh mục: " . $service->category->name . "\n";
      $content .= "🔢 Số lượng: " . $quantity . "\n";
      $content .= "💰 Tổng thanh toán: " . number_format($totalPayment, 0, ',', '.') . " " . cur_setting('currency_code', 'VND') . "\n";
      $content .= "🕒 Thời gian: " . date('d/m/Y H:i:s') . "\n";
      $content .= "👤 Người dùng: " . $user->username . "\n";
      $content .= "🔗 Object ID: " . $object_id . "\n";

      $content .= "💳 Giao dịch: " . number_format($trans->balance_before, 0, ',', '.') . " - " . number_format($trans->amount, 0, ',', '.') . " = " . number_format($trans->balance_after, 0, ',', '.') . " " . cur_setting('currency_code', 'VND') . "\n";
      $content .= "\n\n";

      try {
        Helper::sendMessageTelegramAuto($content);
      } catch (\Exception $e) {
        // do nothing
      }
    }

    $output = [];

    if (!$is_multiple) {
      $output = [
        'transaction'  => [
          'code'   => $trans->code,
          'amount' => $trans->amount,
        ],
        'order_detail' => [
          'id'   => $created[0]->id,
          'code' => $created[0]->order_code,
        ],
      ];
    } else {
      $output = [
        'transaction'  => [
          'code'   => $trans->code,
          'amount' => $trans->amount,
        ],
        'order_detail' => $created->pluck('id'),
      ];
    }

    return response()->json([
      'data'    => $output,
      'status'  => 201,
      'message' => __t('ORDER_CREATED_SUCCESSFULLY'),
    ], 201);
  }

  public function action(Request $request, $id, $action)
  {
    $user = User::find($request->user()->id);
    if ($user === null) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy người dùng này, vui lòng kiểm tra lại!',
      ], 400);
    }
    //
    $order = Order::query();

    if ($user->isAdmin() === false) {
      $order->where('user_id', $user->id);
    }

    $order = $order->where('id', $id)->whereNotIn('order_status', ['Partial', 'WaitingForRefund', 'Completed'])->first();

    if ($order === null) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy đơn hàng này, vui lòng kiểm tra lại!',
      ], 400);
    }
    // action - call api

    if ($order->src_place && $action === 'update') {
      $provider = $order->provider;

      if ($provider === null) {
        return response()->json([
          'status'  => 400,
          'message' => 'Can\'t not process this order, please check again!',
        ], 400);
      }

      $app = new SMMApi();

      $result = $app->status($provider->toArray(), $order->src_id);

      if (isset($result['error'])) {
        $order->update([
          'extra_note' => $result['error'] ?? '',
        ]);

        return response()->json([
          'status'  => 400,
          'message' => 'Order not found or disabled!',
        ], 400);
      }

      $status = 'Processing';

      switch (strtolower($result['status'])) {
        case 'in progress':
          $status = 'Processing';
          break;
        case 'completed':
          $status = 'Completed';
          break;
        case 'pending':
          $status = 'Pending';
          break;
        // case 'partial':
        //   $status = 'WaitingForRefund';
        // case 'canceled':
        //   $status = 'WaitingForRefund';
        //   break;
        default:
          # code...
          $status = $result['status'];
          break;
      }

      $order->update([
        'order_status'  => $status,
        'start_number'  => $result['start_count'] ?? 0,
        'success_count' => $order->quantity - $result['remains'],
      ]);

      return response()->json([
        'status'  => 200,
        'message' => 'Order status updated successfully!',
      ], 200);
    }


    return response()->json([
      'data'    => [],
      'status'  => 400,
      'message' => 'Function is not implemented yet!',
    ], 400);
  }
}
