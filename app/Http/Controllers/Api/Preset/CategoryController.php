<?php

namespace App\Http\Controllers\Api\Preset;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
  public function index(Request $request)
  {

    if (Cache::has("preset_categories")) {
      return response()->json([
        'data'    => Cache::get('preset_categories'),
        'status'  => 200,
        'message' => 'Get list category success',
      ]);
    }

    $categories = Category::where('status', true);

    if ($request->has('platform_id')) {
      $categories->where('platform_id', $request->input('platform_id'));
    }

    $categories = $categories->orderBy('priority', 'desc')->orderBy('id', 'desc')->get();

    if ($categories->isEmpty()) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy danh mục nào hợp lệ',
      ], 400);
    }

    Cache::put('preset_categories', $categories, 180);

    return response()->json([
      'data'    => $categories,
      'status'  => 200,
      'message' => 'Get list category success',
    ]);
  }
}
