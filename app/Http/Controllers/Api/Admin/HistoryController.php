<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
  public function index(Request $request)
  {
    $payload = $request->validate([
      'page'      => 'nullable|integer|min:1',
      'limit'     => 'nullable|integer|min:1',
      'search'    => 'nullable|string|max:255',
      'sort_by'   => 'nullable|string|max:255',
      'username'  => 'nullable|string|max:255',
      'sort_type' => 'nullable|string|in:asc,desc',
    ]);

    $page      = $payload['page'] ?? 1;
    $limit     = $payload['limit'] ?? 10;
    $search    = $payload['search'] ?? null;
    $offset    = ($page - 1) * $limit;
    $sort_by   = $payload['sort_by'] ?? 'id';
    $sort_type = $payload['sort_type'] ?? 'asc';

    $query = \App\Models\History::query();

    if ($search) {
      $query->where('content', 'like', '%' . $search . '%')
        ->orWhere('username', 'like', '%' . $search . '%');
    }

    if ($payload['username']) {
      $query->where('username', $payload['username']);
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