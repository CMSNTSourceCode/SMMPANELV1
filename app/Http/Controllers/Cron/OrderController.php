<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;

use App\Libraries\SMMApi;
use App\Models\Config;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\ApiProvider;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
  public function placeOrder1(Request $request)
  {

    // rate limit 30s / request
    $key = 'time_cron_order' . $request->ip();

    if (Cache::has($key)) {
      return response()->json([
        'status'  => 200,
        'message' => 'Please wait 30 seconds to place orders',
      ], 200);
    }
    // set cache
    Cache::put($key, true, 30);

    $orders = Order::where('src_place', false)->where('src_id', -1)->get();

    foreach ($orders as $order) {
      $provider = $order->provider;

      if ($provider === null || $provider->status === false) {
        $order->update([
          'src_place'  => true,
          'src_status' => 'Error',
          'src_resp'   => ['error' => 'Provider not found or disabled.'],
        ]);
        continue;
      }

      $postData = [
        'key'      => $provider->key,
        'link'     => $order->object_id,
        'action'   => "add",
        'service'  => $order->src_type,
        'quantity' => $order->quantity,
        'comments' => implode("\n", $order->extra_data['comments'] ?? []),
      ];


      $result = Http::timeout(60)->asForm()->post($provider->url, $postData)->json();

      // if ($response->failed()) {
      //   $order->update([
      //     'src_place'  => true,
      //     'src_status' => 'Error',
      //     'src_resp'   => ['error' => 'Provider not found or disabled.', 'status' => $response->status()],
      //   ]);
      //   continue;
      // }
      // $result = $response->json();


      if (isset($result['error'])) {
        $order->update([
          'src_resp'     => $result,
          'src_place'    => true,
          'extra_note'   => $result['error'] ?? '',
          'src_status'   => 'Error',
          'order_status' => 'Error',
        ]);
        if (setting('auto_refund', false)) {
          $this->refundOrder($order->id, ['remains' => $order->quantity, 'start_count' => 0], 'Refund');
        }
      } else {
        $orderId = $result['order'] ?? null;
        if ($orderId === null) {
          $orderId = $result['order_id'] ?? null;
        }
        if ($orderId === null) {
          $orderId = -3;
        }
        $order->update([
          'src_id'       => $orderId,
          'src_resp'     => $result,
          'src_place'    => true,
          'src_status'   => 'Success',
          'order_status' => 'Processing',
        ]);
      }

      echo 'Order #' . $order->id . '; Place status: ' . $order->src_status . '; Data: ' . json_encode($result) . '<br>';
    }

    Config::firstOrCreate(['name' => 'time_cron_order'], ['value' => now()])->update(['value' => now()]);

    // return $orders;
  }

  public function placeOrder(Request $request)
  {
    try {
      $requestId = $request->header('X-Request-ID') ?? Str::uuid();

      if (Cache::has('processed_request_' . $requestId)) {
        return response()->json(['status' => 200, 'message' => 'Request already processed']);
      }

      $rateLimitKey = 'time_cron_order_' . $request->ip();
      if (Cache::has($rateLimitKey)) {
        return response()->json(['status' => 429, 'message' => 'Please wait 30 seconds']);
      }

      DB::beginTransaction();

      try {
        $orders = Order::where('src_place', false)
          ->where('src_id', -1)
          ->limit(5)
          ->lockForUpdate()
          ->get();

        if ($orders->isEmpty()) {
          DB::commit();
          return response()->json(['status' => 200, 'message' => 'No pending orders']);
        }

        Cache::put($rateLimitKey, true, 30);
        Cache::put('processed_request_' . $requestId, true, 60);

        foreach ($orders as $order) {
          $provider = $order->provider;

          if ($provider === null || $provider->status === false) {
            $order->update([
              'src_place'    => true,
              'src_status'   => 'Error',
              'src_resp'     => ['error' => 'Provider not found or disabled.'],
              'order_status' => 'Error',
            ]);
            continue;
          }

          $result = Http::timeout(60)
            ->asForm()
            ->post($provider->url, [
              'key'      => $provider->key,
              'link'     => $order->object_id,
              'action'   => "add",
              'service'  => $order->src_type,
              'quantity' => $order->quantity,
              'comments' => implode("\n", $order->extra_data['comments'] ?? []),
            ])
            ->json();

          if (isset($result['error']) && !isset($result['order']) || !isset($result['order_id'])) {
            $order->update([
              'src_resp'     => $result,
              'src_place'    => true,
              'extra_note'   => $result['error'] ?? $result['message'] ?? '',
              'src_status'   => 'Error',
              'order_status' => 'Error',
            ]);

            if (setting('auto_refund', false)) {
              $this->refundOrder($order->id, [
                'remains'     => $order->quantity,
                'start_count' => 0,
              ], 'Refund');
            }
          } else {
            $orderId = $result['order'] ?? $result['order_id'] ?? -3;
            $order->update([
              'src_id'       => $orderId,
              'src_resp'     => $result,
              'src_place'    => true,
              'src_status'   => 'Success',
              'order_status' => 'Processing',
            ]);
          }

          Log::info('Order processed', [
            'order_id'   => $order->id,
            'request_id' => $requestId,
            'status'     => $order->src_status,
          ]);
        }

        Config::firstOrCreate(['name' => 'time_cron_order'], ['value' => now()])
          ->update(['value' => now()]);

        DB::commit();

        return response()->json(['status' => 200, 'message' => 'Orders processed']);

      } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
      }

    } catch (\Exception $e) {
      Log::error('Order placement failed', [
        'error' => $e->getMessage(),
      ]);

      return response()->json([
        'status'  => 500,
        'message' => 'Internal server error',
      ], 500);
    }
  }

  public function updateOrder(Request $request)
  {
    $key = 'time_cron_order' . $request->ip();
    if (!Cache::has($key)) {
      Cache::put($key, true, 1);
    } else {
      return response()->json([
        'status'  => 200,
        'message' => 'Please wait 1 minute to update orders',
      ], 200);
    }

    // init
    $ids      = $request->input('ids', null);
    $limit    = $request->input('limit', 100);
    $minute   = $request->input('minute', 3);
    $source   = $request->input('source', null);
    $status   = $request->input('status', null);
    $username = $request->input('username', null);
    $print    = $request->input('print', false);
    $debug_1  = $request->input('debug_1', false);
    $debug_2  = $request->input('debug_2', false);

    // if ($source === null) {
    //   return response()->json([
    //     'status'  => 400,
    //     'message' => 'Source is required'
    //   ], 400);
    // }

    $query = Order::query()->where('src_id', '!=', -1)->where('src_status', 'Success');
    // get orders updated_at > $minutes ago
    $query->where('updated_at', '<', now()->subMinutes($minute));

    // get orders by status
    if ($status !== null) {
      $query->where('order_status', $status);
    } else {
      $query->whereNotIn('order_status', ['Completed', 'Refund', 'Canceled', 'Error']);
    }
    // get orders by username
    if ($username !== null) {
      $query->where('username', $username);
    }
    // get orders by id
    if ($ids) {
      $query->whereIn('id', explode(',', $ids));
    }
    //
    $orders = $query->limit($limit)->get();
    if ($debug_1) {
      return $orders;
    }
    if ($orders->count() === 0) {
      return response()->json([
        'data'    => $orders,
        'status'  => 200,
        'message' => 'No orders found',
      ], 200);
    }

    // group by provider
    $grouped = $orders->groupBy('src_name');


    foreach ($grouped as $provider => $value) {
      $provider = ApiProvider::where('id', $provider)->where('status', true)->first();

      if ($provider === null) {
        if ($print) {
          echo 'Provider #' . $provider . ' - Provider not found or disabled.<br>';
        }
        continue;
      }

      $app = new SMMApi();

      $order_ids = $value->pluck('src_id')->toArray();


      $list = $app->multiStatus($provider->toArray(), $order_ids);

      if (!is_array($list)) {
        return response()->json([
          'data'    => $list,
          'status'  => 400,
          'message' => 'Data is not array',
        ], 400);
      }

      foreach ($list as $id => $row) {
        $order = Order::where('src_id', $id)->first();

        if ($order === null) {
          if ($print) {
            echo 'Order #' . $id . ' - Order not found.<br>';
          }
          continue;
        }

        if (isset($row['error']) || !isset($row['status'])) {

          $order->update([
            'updated_at'   => now(),
            'extra_note'   => $row['error'],
            'order_status' => 'Error',
          ]);

          if ($print) {
            echo 'Order #' . $order->id . ' - ' . json_encode($row) . '<br>';
          }
        } else if (isset($row['status'])) {
          $status = 'Processing';

          switch (strtolower($row['status'])) {
            case 'in progress':
              $status = 'Running';
              break;
            case 'completed':
              $status = 'Completed';
              break;
            case 'pending':
              $status = 'Pending';
              break;
            case 'canceled':
              $status = 'Refund';
              break;
            case 'partial':
              $status = 'Partial';
            default:
              # code...
              $status = 'Processing';
              break;
          }

          if ($status === 'Refund' || $status === 'Partial') {
            $this->refundOrder($order->id, $row, $status);
          } else {
            $order->update([
              'updated_at'    => now(),
              'order_status'  => $status,
              'start_number'  => $row['start_count'] ?? 0,
              'success_count' => $order->quantity - $row['remains'],
            ]);
          }

          if ($print) {
            echo 'Order <span style="color: red">' . $order->id . '</span>; RefID: <span style="color: blue">' . $order->src_id . '</span>; Status: <span style="color: green">' . $status . '</span>; Quantity/Remains: <span style="color: red">' . $order->quantity . '</span>/<span style="color: blue">' . $row['remains'] . '</span><br>';
          }
          // echo 'Order #' . $order->id . ' - ' . json_encode($row) . '<br>';
        }
      }
    }
  }

  private function refundOrder($id, $data, $status = 'Refund')
  {
    $order = Order::where('id', $id)->first();

    if ($order === null) {
      return false;
    }

    if ($order->status === 'Refund' || $order->status === 'Partial') {
      return false;
    }

    if (!isset($data['remains']) || !is_numeric($data['remains']) || $data['remains'] < 0) {
      $data['remains'] = 0;
    }

    $remains = (int) $data['remains'];

    if ($status === 'Partial' && $remains === 0) {
      return $order->update([
        'order_status' => 'Cancelled',
        'extra_note'   => 'Partial order but remains is 0',
      ]);
    }

    $totalRefund = (double) ($order->total_payment / $order->quantity) * (int) $remains;

    if ($totalRefund < 0) {
      $totalRefund = 0;
    }

    $user = User::where('username', $order->username)->first();

    if ($user === null) {
      return false;
    }

    $user->increment('balance', $totalRefund);

    $order->update([
      'order_status'  => $status,
      'start_number'  => $data['start_count'] ?? 0,
      'total_payment' => $order->total_payment - $totalRefund,
      'success_count' => $order->quantity - $remains,
    ]);

    Transaction::create([
      'code'           => $order->order_code,
      'amount'         => $totalRefund,
      'balance_before' => $user->balance - $totalRefund,
      'balance_after'  => $user->balance,
      'type'           => 'refund-order',
      'status'         => 'paid',
      'content'        => '[' . $order->order_code . '] Refunded; Quantity remains: ' . $remains . '/(s)',
      'extras'         => [
        'id' => $order->id,
      ],
      'domain'         => $user->domain,
      'user_id'        => $user->id,
      'username'       => $user->username,
      'order_id'       => $order->id,
    ]);

    return true;
  }

  public function restore(Request $request)
  {
    echo "Restore users...<br>";
    $users = DB::table("users1")->orderBy('id', 'asc')->limit(10000)->get();
    foreach ($users as $row) {
      $create = User::create([
        'id'            => $row->id,
        'username'      => $row->username,
        'password'      => $row->password ? $row->password : bcrypt(time()),
        'fullname'      => $row->fullname,
        'email'         => $row->email,
        'balance'       => $row->money,
        'total_deposit' => $row->total_money,
        'created_at'    => $row->created_at,
        'ip_address'    => $row->ip,
      ]);

      if ($create) {
        echo "Success: " . $row->username . "<br>";
        DB::table("users1")->where('username', $row->username)->delete();
      } else {
        echo "Failed: " . $row->username . "<br>";
      }
    }
  }
}
