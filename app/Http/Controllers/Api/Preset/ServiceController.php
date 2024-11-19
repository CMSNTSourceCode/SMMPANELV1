<?php

namespace App\Http\Controllers\Api\Preset;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ServiceController extends Controller
{
  public function index(Request $request)
  {

    $services = Service::where('status', true)->get();

    $services = collect($services);

    $services = $services->map(function ($service) {
      $service->price_formatted = formatCurrencyF($service->price);
      $service->text = $service->name;
      return $service;
    });

    return response()->json([
      'data'    => $services,
      'status'  => 200,
      'message' => 'Get list service success',
    ]);
  }

  public function info(Request $request)
  {
    $payload = $request->validate([
      'service_id' => 'required|integer',
    ]);

    $service = Service::where('id', $payload['service_id'])->first();

    if ($service === null) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy dịch vụ này, vui lòng thử lại',
      ], 400);
    }

    return response()->json([
      'data'    => $service,
      'status'  => 200,
      'message' => 'Get service info success',
    ]);
  }
}
