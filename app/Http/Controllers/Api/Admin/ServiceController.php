<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
  public function index(Request $request)
  {
    $payload = $request->validate([
      'page'        => 'nullable|integer|min:1',
      'limit'       => 'nullable|integer|min:1',
      'search'      => 'nullable|string|max:255',
      'sort_by'     => 'nullable|string|max:255',
      'category_id' => 'nullable|integer',
      'provider_id' => 'nullable|integer',
      'sort_type'   => 'nullable|string|in:asc,desc',
    ]);

    $page      = $payload['page'] ?? 1;
    $limit     = $payload['limit'] ?? 10;
    $search    = $payload['search'] ?? null;
    $offset    = ($page - 1) * $limit;
    $sort_by   = $payload['sort_by'] ?? 'id';
    $sort_type = $payload['sort_type'] ?? 'asc';

    $query = \App\Models\Service::query();

    if ($search) {
      $query->where('name', 'like', '%' . $search . '%');
    }

    if ($payload['category_id']) {
      $query->where('category_id', $payload['category_id']);
    }

    if ($payload['provider_id']) {
      $query->where('api_provider_id', $payload['provider_id']);
    }

    $total = $query->count();

    $data = $query->skip($offset)
      ->take($limit)
      ->orderBy($sort_by, $sort_type)
      ->with('provider')->with('category')
      ->get();

    // add provider
    $data = $data->map(function ($item) {
      $item->makeVisible(['api_service_id', 'api_provider_id', 'created_at']);
      return $item;
    });

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
