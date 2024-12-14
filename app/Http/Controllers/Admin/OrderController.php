<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
  public function index(Request $request)
  {
    $services = Service::where('status', true)->get();

    return view('admin.orders.index', [
      'pageTitle' => 'Orders Manager',
    ], compact('services'));

  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'            => 'required|integer',
      // 'quantity'      => 'required|integer',
      // 'src_type'      => 'required|string',
      'src_place'     => 'required|boolean',
      'object_id'     => 'required|string',
      'src_status'    => 'nullable|string',
      'start_number'  => 'required|integer',
      'order_status'  => 'required|string',
      'success_count' => 'required|integer',
    ]);

    $order = Order::where('id', $payload['id'])->firstOrFail();

    //
    if (!!$payload['src_place'] === false) {
      $payload['src_id']     = -1;
      $payload['src_place']  = false;
      $payload['src_status'] = 'Pending';
      // $payload['order_status'] = 'Pending';
    } else {
      $payload['src_place']  = true;
      $payload['src_status'] = 'Success';
    }

    if ($payload['order_status'] === 'Refund' && $order->order_status !== 'Refund') {
      $client = User::find($order->user_id);

      if ($client) {
        $client->increment('balance', $order->total_payment);

        Transaction::create([
          'code'           => $order->order_code,
          'amount'         => $order->total_payment,
          'balance_before' => $client->balance - $order->total_payment,
          'balance_after'  => $client->balance,
          'type'           => 'refund-order',
          'status'         => 'paid',
          'content'        => '[' . $order->order_code . '] Đơn hàng bị hoàn bởi quản trị viên',
          'extras'         => [
            'id'         => $order->id,
            'src_id'     => $order->src_id,
            'order_code' => $order->order_code,
          ],
          'domain'         => $client->domain,
          'user_id'        => $client->id,
          'username'       => $client->username,
          'order_id'       => $order->id,
        ]);
      }
    }

    $order->update($payload);


    Helper::addHistory('Cập nhật đơn hàng #' . $order->order_code . ' thành công');

    return response()->json([
      'data'    => [
        'id' => $order->id,
      ],
      'status'  => 200,
      'message' => 'Order updated successfully.',
    ]);
  }

  public function delete(Request $request)
  {
    if ($request->has('ids')) {
      $payload = $request->validate([
        'ids' => 'required|array',
      ]);

      $ids = array_map('intval', $payload['ids']);

      $orders = Order::whereIn('id', $ids)->get();

      foreach ($orders as $order) {
        $order->delete();
      }

      Helper::addHistory('Thực hiện xoá đơn hàng hàng loạt, Đơn: ' . implode(', ', $orders->pluck('id')->toArray()));

      return response()->json([
        'status'  => 200,
        'message' => 'Orders deleted successfully.',
      ]);
    }

    $payload = $request->validate([
      'id' => 'required|integer',
    ]);

    $order = Order::where('id', $payload['id'])->firstOrFail();
    $order->delete();

    Helper::addHistory('Xoá đơn hàng #' . $order->order_code . ' thành công');

    return response()->json([
      'status'  => 200,
      'message' => 'Order deleted successfully.',
    ]);
  }
}
