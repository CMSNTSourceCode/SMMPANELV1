<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
  public function index(Request $request)
  {
    $config = Helper::getConfig('currency_settings');

    return view('admin.settings.currency', [
      'pageTitle' => 'Currency Settings',
    ], compact('config'));
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'currency_code'                     => 'required|string|max:12',
      'currency_symbol'                   => 'required|string|max:12',
      'currency_position'                 => 'required|in:left,right',
      'currency_thousand_separator'       => 'required|in:dot,comma,space',
      'currency_decimal_separator'        => 'required|in:dot,comma',
      'currency_decimal'                  => 'required|integer|in:0,1,2,3,4',
      'default_price_percentage_increase' => 'required|integer',
      'auto_rounding_x_decimal_places'    => 'required|in:1,2,3,4',
      'new_currecry_rate'                 => 'required|numeric',
    ]);

    $config = Helper::setConfig('currency_settings', $payload);

    Helper::addHistory("Cập nhật cài đặt tiền tệ; " . $payload['currency_code'] . '-' . $payload['currency_symbol']);

    return response()->json([
      'status'  => 200,
      'message' => 'Currency settings updated successfully.',
    ]);
  }
}
