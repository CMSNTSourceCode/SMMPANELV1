<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
  public function index(Request $request)
  {
    $payload = $request->validate([
      'page'       => 'nullable|integer|min:1',
      'type'       => 'nullable|string',
      'limit'      => 'nullable|integer|min:1',
      'search'     => 'nullable|string|max:255',
      'sort_by'    => 'nullable|string|max:255',
      'end_date'   => 'nullable|date',
      'order_id'   => 'nullable|string|max:255',
      'username'   => 'nullable|string|max:255',
      'sort_type'  => 'nullable|string|in:asc,desc',
      'start_date' => 'nullable|date',
    ]);

    $page      = $payload['page'] ?? 1;
    $limit     = $payload['limit'] ?? 10;
    $search    = $payload['search'] ?? null;
    $offset    = ($page - 1) * $limit;
    $sort_by   = $payload['sort_by'] ?? 'id';
    $sort_type = $payload['sort_type'] ?? 'asc';

    $query = \App\Models\Transaction::query();

    if ($search) {
      $query->where(function ($q) use ($search) {
        $q->where('code', 'like', '%' . $search . '%')
          ->orWhere('type', 'like', '%' . $search . '%')
          ->orWhere('username', 'like', '%' . $search . '%')
          ->orWhere('content', 'like', '%' . $search . '%');
      });
    }

    if ($payload['type']) {
      $query->where('type', $payload['type']);
    }

    if ($payload['username']) {
      $query->where('username', $payload['username']);
    }

    if ($payload['order_id']) {
      $query->where('order_id', $payload['order_id']);
    }

    if ($payload['start_date']) {
      $query->whereDate('created_at', '>=', $payload['start_date']);
    }

    if ($payload['end_date']) {
      $query->whereDate('created_at', '<=', $payload['end_date']);
    }

    $total = $query->count();

    $data = $query->skip($offset)
      ->take($limit)
      ->orderBy($sort_by, $sort_type)
      ->get();

    return response()->json([
      'data'    => [
        'meta' => [
          'page'  => (int) $page,
          'total' => (int) $total,
          'limit' => (int) $limit,
        ],
        'data' => $data,
      ],
      'status'  => 200,
      'message' => 'Get data success',
    ]);
  }

  public function listCard(Request $request)
  {
    $payload = $request->validate([
      'page'       => 'nullable|integer|min:1',
      'type'       => 'nullable|string',
      'limit'      => 'nullable|integer|min:1',
      'search'     => 'nullable|string|max:255',
      'sort_by'    => 'nullable|string|max:255',
      'end_date'   => 'nullable|date',
      'order_id'   => 'nullable|string|max:255',
      'username'   => 'nullable|string|max:255',
      'sort_type'  => 'nullable|string|in:asc,desc',
      'start_date' => 'nullable|date',
    ]);

    $page      = $payload['page'] ?? 1;
    $limit     = $payload['limit'] ?? 10;
    $search    = $payload['search'] ?? null;
    $offset    = ($page - 1) * $limit;
    $sort_by   = $payload['sort_by'] ?? 'id';
    $sort_type = $payload['sort_type'] ?? 'asc';

    $query = \App\Models\CardList::query();

    if ($search) {
      $query->where(function ($q) use ($search) {
        $q->where('code', 'like', '%' . $search . '%')
          ->orWhere('type', 'like', '%' . $search . '%')
          ->orWhere('username', 'like', '%' . $search . '%')
          ->orWhere('order_id', 'like', '%' . $search . '%');
      });
    }

    if ($payload['type']) {
      $query->where('type', $payload['type']);
    }

    if ($payload['username']) {
      $query->where('username', $payload['username']);
    }

    if ($payload['order_id']) {
      $query->where('order_id', $payload['order_id']);
    }

    if ($payload['start_date']) {
      $query->whereDate('created_at', '>=', $payload['start_date']);
    }

    if ($payload['end_date']) {
      $query->whereDate('created_at', '<=', $payload['end_date']);
    }

    $total = $query->count();

    $data = $query->skip($offset)
      ->take($limit)
      ->orderBy($sort_by, $sort_type)
      ->get();

    return response()->json([
      'data'    => [
        'meta' => [
          'page'  => (int) $page,
          'total' => (int) $total,
          'limit' => (int) $limit,
        ],
        'data' => $data,
      ],
      'status'  => 200,
      'message' => 'Get data success',
    ]);
  }
}
