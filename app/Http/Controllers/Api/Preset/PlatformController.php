<?php

namespace App\Http\Controllers\Api\Preset;

use App\Http\Controllers\Controller;
use App\Models\ListPlatform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PlatformController extends Controller
{
  public function index(Request $request)
  {

    if (Cache::has("preset_platforms")) {
      return response()->json([
        'data'    => Cache::get('preset_platforms'),
        'status'  => 200,
        'message' => 'Get list platform success',
      ]);
    }

    $query = ListPlatform::where('status', true)
      ->orderBy('priority', 'desc')
      ->orderBy('id', 'desc');

    $data = $query->get();

    if ($data->isEmpty()) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy nền tảng nào hợp lệ',
      ], 400);
    }

    Cache::put('preset_platforms', $data, 180);

    return response()->json([
      'data'    => $data,
      'status'  => 200,
      'message' => 'Get list platform success',
    ]);
  }
}
