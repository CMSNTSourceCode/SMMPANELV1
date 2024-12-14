<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use App\Libraries\SMMApi;
use App\Models\ApiProvider;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ServiceController extends Controller
{
  public function sync(Request $request)
  {
    if (Cache::has('cron_sync_prices')) {
      return response()->json([
        'status'  => 400,
        'message' => 'Please stop spamming, wait 30 seconds',
      ], 400);
    }

    Cache::put('cron_sync_prices', true, 1);

    $providers = ApiProvider::where('status', true)->where('auto_sync', true)->get();

    if ($providers->isEmpty()) {
      return response()->json([
        'status'  => 400,
        'message' => 'No provider found',
      ], 400);
    }

    $currency = cur_setting();

    $currency_code                  = $currency['currency_code'] ?? 'USD';
    $auto_rounding_x_decimal_places = $currency['auto_rounding_x_decimal_places'] ?? 2;

    foreach ($providers as $provider) {

      $app = new SMMApi();

      // $balance  = $app->balance($provider->toArray());
      $services = $app->services($provider->toArray());

      if (count($services) === 0) {
        echo "No services found for provider #{$provider->id}<br />";
        continue;
      }

      $updated    = 0;
      $percent_up = (int) ($provider->price_percentage_increase ?? $currency['default_price_percentage_increase']);

      foreach (Service::where('api_provider_id', $provider->id)->get() as $service) {
        $api_service = collect($services)->where('service', $service->api_service_id)->first();

        $price = $api_service['rate'] ?? 0;

        $price = normalizeRate($price);

        if ($provider->currency_code === 'VND') {
          // $price = $price * 1000;
        }

        // convert to default currency
        $price = convert_currency($price, $provider->exchange_rate, $provider->currency_code, $currency_code);

        // new rate update
        $new_rate = $price + ($price * $percent_up / 100);
        $new_rate = round($new_rate, $auto_rounding_x_decimal_places);

        $saved = $service->update([
          'price'          => $new_rate,
          'original_price' => $price,
        ]);

        if ($saved) {
          $updated++;
        }
      }

      echo "Updated {$updated} services for provider #{$provider->id}<br />";
    }

    // return $providers;
  }
}
