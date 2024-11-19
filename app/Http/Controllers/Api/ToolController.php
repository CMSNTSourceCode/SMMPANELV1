<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Libraries\BaseAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ToolController extends Controller
{
  //
  public function getUidFacebook(Request $request)
  {
    $payload = $request->validate([
      'link' => 'required|string|max:500',
    ]);

    $response = Http::get('https://api.subvip.top/tools/facebook/get-uid', [
      'link' => $payload['link'],
    ]);

    if ($response->failed()) {
      return response()->json([
        'status'  => 400,
        'message' => $response->json('message', 'Lỗi không xác định!'),
      ], 400);
    }

    $result = $response->json();

    return response()->json([
      'status'  => 200,
      'message' => 'Lấy UID thành công!',
      'data'    => $result['data']['id'],
    ], 200);
  }

  public function getForm(Request $request, $form_type)
  {

    return view('services.extras.form-' . $form_type);
  }

  // tinh tien
  public function calculate(Request $request)
  {
    $payload  = $request->validate([
      'price'     => 'required|numeric|regex:/^[0-9]+$/',
      'quantity'  => 'required|integer|regex:/^[0-9]+$/',
      'form_type' => 'required|string|max:255',
      'charge_by' => 'required|string|max:255',
      'num_post'  => 'nullable|integer|regex:/^[0-9]+$/',
      'duration'  => 'nullable|integer|regex:/^[0-9]+$/',
    ]);
    $price    = (float) ($payload['price'] ?? 0);
    $quantity = (int) ($payload['quantity'] ?? 0);
    $formType = $payload['form_type'];
    $chargeBy = $payload['charge_by'];

    // base price
    $payment = $price * $quantity;

    // form_type la fb_viplike
    if ($formType === 'fb_viplike') {
      $num_post = (int) ($payload['num_post'] ?? 0);
      $duration = (int) ($payload['duration'] ?? 0);

      $payment = $quantity * $price * $duration * $num_post;
    }

    return response()->json([
      'data'    => $payment,
      'status'  => 200,
      'message' => 'Thành công',
    ]);
  }
}
