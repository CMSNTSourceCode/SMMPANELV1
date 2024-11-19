<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
  public function index(Request $request)
  {
    $payload = $request->validate([
      'type'       => 'nullable|string|in:deposit',
      'page'       => 'nullable|integer',
      'limit'      => 'nullable|integer',
      'search'     => 'nullable|string',
      'sort_by'    => 'nullable|string',
      'end_date'   => 'nullable|date',
      'sort_type'  => 'nullable|string|in:asc,desc',
      'start_date' => 'nullable|date',
    ]);
    $type    = $payload['type'] ?? null;
    $query   = Transaction::where('user_id', $request->user()->id);

    if ($type) {
      $query = $query->where('type', $type);
    }

    if (isset($payload['search'])) {
      $query = $query->where('code', 'like', '%' . $payload['search'] . '%')
        ->orWhere('content', 'like', '%' . $payload['search'] . '%');
    }

    if (isset($payload['sort_by'])) {
      $query = $query->orderBy($payload['sort_by'], $payload['sort_type'] ?? 'asc');
    }

    if (isset($payload['start_date'])) {
      $query = $query->whereDate('created_at', '>=', $payload['start_date']);
    }

    if (isset($payload['end_date'])) {
      $query = $query->whereDate('created_at', '<=', $payload['end_date']);
    }

    $meta = [
      'page'  => (int) ($payload['page'] ?? 1),
      'limit' => (int) ($payload['limit'] ?? 10),
      'total' => $query->count(),
    ];

    $data = $query->skip(($meta['page'] - 1) * $meta['limit'])->take($meta['limit']);

    return response()->json([
      'data'    => [
        'meta' => $meta,
        'data' => $data->get(),
      ],
      'status'  => 200,
      'message' => 'Lấy danh sách giao dịch thành công',
    ], 200);

  }
}
