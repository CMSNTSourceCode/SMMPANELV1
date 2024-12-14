<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Libraries\SMMApi;
use App\Models\ApiProvider;
use App\Models\Category;
use App\Models\ListPlatform;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ProviderController extends Controller
{
  //
  public function index(Request $request)
  {

    $records = ApiProvider::orderBy('id', 'desc')->paginate(10);

    return view('admin.providers.index', [
      'pageTitle' => 'Provider: API Manager',
    ], compact('records'));
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'name'                      => 'required|string|max:255',
      'url'                       => 'required|string|url',
      'key'                       => 'required|string|max:2048',
      'status'                    => 'required|boolean',
      'description'               => 'nullable|string',
      'exchange_rate'             => 'nullable|numeric',
      'price_percentage_increase' => 'nullable|numeric',
    ]);

    $result = Http::asForm()->post($payload['url'], ['key' => $payload['key'], 'action' => 'balance'])->json();

    if (isset($result['error'])) {
      return response()->json([
        'status'  => 422,
        'message' => 'Invalid API URL or Key. Please check again.',
      ], 422);
    }

    if (!isset($result['balance']) || !isset($result['currency'])) {
      return response()->json([
        'status'  => 422,
        'message' => 'Invalid API URL or Key. Please check again.',
      ], 422);
    }

    $balance = round((float) $result['balance'], 2);

    $api = ApiProvider::create(array_merge($payload, [
      'pid'           => str()->uuid(),
      'balance'       => $balance,
      'currency_code' => $result['currency'],
    ]));

    Helper::addHistory('Thêm API Provider ' . $api->name . ' thành công');

    return response()->json([
      'data'    => $api,
      'status'  => 200,
      'message' => 'API Provider added successfully.',
    ]);
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'                        => 'required|integer',
      'name'                      => 'required|string|max:255',
      'url'                       => 'required|string|url',
      'key'                       => 'required|string|max:2048',
      'status'                    => 'required|boolean',
      'description'               => 'nullable|string',
      'exchange_rate'             => 'nullable|numeric',
      'price_percentage_increase' => 'nullable|numeric',
    ]);

    $provider = ApiProvider::find($payload['id']);

    if (!$provider) {
      return response()->json([
        'status'  => 400,
        'message' => 'API này không tồn tại hoặc đã bị xoá.',
      ], 400);
    }

    $provider->update($payload);

    Helper::addHistory('Cập nhật API Provider ' . $provider->name . ' thành công');

    return response()->json([
      'data'    => $provider,
      'status'  => 200,
      'message' => 'API Provider updated successfully.',
    ]);
  }


  public function balanceUpdate(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|integer',
    ]);

    $provider = ApiProvider::find($payload['id']);

    if (!$provider) {
      return response()->json([
        'status'  => 400,
        'message' => 'API này không tồn tại hoặc đã bị xoá.',
      ], 400);
    }

    $response = Http::asForm()->post($provider->url, ['key' => $provider->key, 'action' => 'balance']);

    if ($response->failed()) {
      return response()->json([
        'status'  => 422,
        'message' => 'Invalid API URL or Key. Please check again.',
      ], 422);
    }

    $result = $response->json();

    $balance = round((float) $result['balance'], 2);

    $provider->update([
      'balance'       => $balance,
      'currency_code' => $result['currency'],
    ]);

    return response()->json([
      'data'    => $provider,
      'status'  => 200,
      'message' => 'API Provider balance updated successfully.',
    ]);
  }

  public function priceUpdate(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|integer',
    ]);

    $provider = ApiProvider::find($payload['id']);

    // forget cache: preset_services, preset_categories
    Cache::forget('preset_services'); // forget cache: preset_services
    Cache::forget('categories'); // forget cache: preset_categories

    if (!$provider) {
      return response()->json([
        'status'  => 400,
        'message' => __t('API này không tồn tại hoặc đã bị xoá'),
      ], 400);
    }

    $currency = cur_setting();

    $currency_code                     = $currency['currency_code'] ?? 'USD';
    $auto_rounding_x_decimal_places    = $currency['auto_rounding_x_decimal_places'] ?? 2;
    $default_price_percentage_increase = (int) ($provider->price_percentage_increase ?? $currency['default_price_percentage_increase']);

    $app = new SMMApi();

    // $balance  = $app->balance($provider->toArray());
    $services = $app->services($provider->toArray());

    if (count($services) === 0) {
      return response()->json([
        'status'  => 400,
        'message' => __t('Không tìm thấy dịch vụ nào từ API'),
      ], 400);
    }

    $updated = 0;

    foreach (Service::where('api_provider_id', $provider->id)->get() as $service) {
      $api_service = collect($services)->where('service', $service->api_service_id)->first();

      $price = $api_service['rate'] ?? 0; // 5.3


      $price = normalizeRate($price);

      if ($provider->currency_code === 'VND') {
        // $price = $price * 1000;
      }

      // convert to default currency
      $price = convert_currency($price, $provider->exchange_rate, $provider->currency_code, $currency_code);

      // new rate update
      $new_rate = $price + ($price * $default_price_percentage_increase / 100);
      $new_rate = round($new_rate, $auto_rounding_x_decimal_places);

      $saved = $service->update([
        'price'          => $new_rate,
        'original_price' => $price,
      ]);

      if ($saved) {
        $updated++;
      }
    }

    return response()->json([
      'status'  => 200,
      'message' => __t('Đã cập nhật :count dịch vụ từ API (Tỷ giá: :ex_change; Tăng :percent %).', ['count' => $updated, 'ex_change' => number_format($provider->exchange_rate), 'percent' => $default_price_percentage_increase]),
    ]);
  }

  public function delete(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|integer',
    ]);

    $provider = ApiProvider::find($payload['id']);

    if (!$provider) {
      return response()->json([
        'status'  => 400,
        'message' => 'API này không tồn tại hoặc đã bị xoá.',
      ], 400);
    }

    $provider->delete();

    Helper::addHistory('Xoá API Provider ' . $provider->name . ' thành công');

    return response()->json([
      'status'  => 200,
      'message' => 'API Provider đã được xoá thành công.',
    ]);
  }

  public function autoSync(Request $request)
  {
    $payload = $request->validate([
      'id'     => 'required|integer',
      'status' => 'required|boolean',
    ]);

    $provider = ApiProvider::find($payload['id']);

    if (!$provider) {
      return response()->json([
        'status'  => 400,
        'message' => 'API này không tồn tại hoặc đã bị xoá.',
      ], 400);
    }

    $provider->update([
      'auto_sync' => !!$payload['status'],
    ]);

    return response()->json([
      'status'  => 200,
      'message' => 'API Provider auto sync updated successfully.',
    ]);
  }

  public function importServices(Request $request)
  {
    $provider   = null;
    $platforms  = ListPlatform::where('status', true)->get();
    $providers  = ApiProvider::where('status', true)->get();
    $categories = Category::where('status', true)->get();

    if ($request->has('provider_id') && is_numeric($request->input('provider_id'))) {
      $provider = ApiProvider::where('id', $request->input('provider_id'))->firstOrFail()->toArray();

      $app          = new SMMApi();
      $api_services = $app->services($provider);
    } else {
      $api_services = [];
    }


    return view('admin.providers.import', [
      'pageTitle' => 'Provider: Import Services',
    ], compact('provider', 'providers', 'platforms', 'categories', 'api_services'));
  }

  public function storeServices(Request $request)
  {
    $payload = $request->validate([
      'provider_id'               => 'required|integer',
      'category_id'               => 'required|integer',
      'checked_ids'               => 'required|array',
      'price_percentage_increase' => 'required|numeric',
    ]);

    $provider = ApiProvider::find($payload['provider_id']);

    if (!$provider) {
      return response()->json([
        'status'  => 400,
        'message' => __t('API này không tồn tại hoặc đã bị xoá.'),
      ], 400);
    }

    $category = Category::find($payload['category_id']);

    if (!$category) {
      return response()->json([
        'status'  => 400,
        'message' => __t('Chuyên mục này không tồn tại hoặc đã bị xoá.'),
      ], 400);
    }

    $currency = cur_setting();

    $currency_code                     = $currency['currency_code'] ?? 'USD';
    $auto_rounding_x_decimal_places    = $currency['auto_rounding_x_decimal_places'] ?? 2;
    $default_price_percentage_increase = $payload['price_percentage_increase'] ?? 25;

    $app = new SMMApi();

    $services = $app->services($provider->toArray());

    if (count($services) === 0) {
      return response()->json([
        'status'  => 400,
        'message' => __t('Không tìm thấy dịch vụ nào từ API.'),
      ], 400);
    }

    $added = 0;

    $available_services = collect($services)->whereIn('service', $payload['checked_ids']);

    foreach ($available_services as $value) {
      $exists = Service::where('api_service_id', $value['service'])->where('api_provider_id', $provider->id)->first();

      if ($exists) {
        continue;
      }

      $price = $value['rate'] ?? 0;

      // convert to default currency
      $price = convert_currency($price, $provider->exchange_rate, $provider->currency_code, $currency_code);

      // new rate update
      $new_rate    = $price + ($price * $default_price_percentage_increase / 100);
      $new_rate    = round($new_rate, $auto_rounding_x_decimal_places);
      $maxInt32Bit = 2147483647;

      $service = Service::create([
        'pid'             => str()->uuid(),
        'name'            => $value['name'],
        'descr'           => '',
        'type'            => strtolower(str_replace(' ', '_', $value['type'])),
        'price'           => $new_rate,
        'original_price'  => $value['rate'],
        'refill'          => isset($value['refill']) ? !!$value['refill'] : false,
        'min_buy'         => $value['min'] > $maxInt32Bit ? 0 : $value['min'],
        'max_buy'         => $value['max'] > $maxInt32Bit ? 0 : $value['max'],
        'add_type'        => 'api',
        'status'          => true,
        'dripfeed'        => false,
        'deny_duplicates' => true,
        'api_service_id'  => $value['service'],
        'api_provider_id' => $provider->id,
        'category_id'     => $category->id,
      ]);

      if ($service) {
        $added++;
      }
    }

    if ($added === 0) {
      return response()->json([
        'status'  => 400,
        'message' => __t('Không có dịch vụ nào được thêm vào, có thể do bị trùng.'),
      ], 400);
    }

    return response()->json([
      'status'  => 200,
      'message' => __t('Đã thêm thành công :count dịch vụ từ API.', ['count' => $added]),
    ]);
  }

  public function bulkStoreServices(Request $request)
  {
    $payload = $request->validate([
      'platform_id' => 'required|integer',
      'provider_id' => 'required|integer',
    ]);

    $platform = ListPlatform::find($payload['platform_id']);

    if (!$platform) {
      return response()->json([
        'status'  => 400,
        'message' => __t('Nền tảng này không tồn tại hoặc đã bị xoá.'),
      ], 400);
    }

    $provider = ApiProvider::find($payload['provider_id']);

    if (!$provider) {
      return response()->json([
        'status'  => 400,
        'message' => __t('API này không tồn tại hoặc đã bị xoá.'),
      ], 400);
    }

    $currency = cur_setting();

    $currency_code                     = $currency['currency_code'] ?? 'USD';
    $auto_rounding_x_decimal_places    = $currency['auto_rounding_x_decimal_places'] ?? 2;
    $default_price_percentage_increase = $currency['default_price_percentage_increase'] ?? 25;

    $app = new SMMApi();

    $services = $app->services($provider->toArray());

    if (count($services) === 0) {
      return response()->json([
        'status'  => 400,
        'message' => __t('Không tìm thấy dịch vụ nào từ API.'),
      ], 400);
    }

    $added = 0;

    foreach ($services as $service) {
      $category = Category::where('name', $service['category'])->first();

      if (!$category) {
        $category = Category::create([
          'pid'         => str()->uuid(),
          'name'        => $service['category'],
          'descr'       => 'Auto generated category',
          'image'       => null,
          'status'      => true,
          'priority'    => 0,
          'platform_id' => $platform->id,
        ]);
      }

      $price = $service['rate'] ?? 0;

      // convert to default currency
      $price = convert_currency($price, $provider->exchange_rate, $provider->currency_code, $currency_code);

      // new rate update
      $new_rate    = $price + ($price * $default_price_percentage_increase / 100);
      $new_rate    = round($new_rate, $auto_rounding_x_decimal_places);
      $maxInt32Bit = 2147483647;

      $service = $category->services()->create([
        // 'sid'             => time(),
        'pid'             => str()->uuid(),
        'name'            => $service['name'],
        'descr'           => '',
        'type'            => strtolower(str_replace(' ', '_', $service['type'])),
        'price'           => $new_rate,
        'original_price'  => $service['rate'],
        'refill'          => isset($service['refill']) ? !!$service['refill'] : false,
        'min_buy'         => $service['min'] > $maxInt32Bit ? 0 : $service['min'],
        'max_buy'         => $service['max'] > $maxInt32Bit ? 0 : $service['max'],
        'add_type'        => 'api',
        'status'          => true,
        'dripfeed'        => $service['dripfeed'] ?? false,
        'deny_duplicates' => true,
        'api_service_id'  => $service['service'],
        'api_provider_id' => $provider->id,
        'category_id'     => $category->id,
      ]);

      if ($service) {
        $added++;
      }
    }

    return response()->json([
      'status'  => 200,
      'message' => __t('Đã thêm thành công :count dịch vụ từ API.', ['count' => $added]),
    ]);
  }
}
