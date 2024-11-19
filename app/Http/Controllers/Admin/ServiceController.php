<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Libraries\SMMApi;
use App\Models\ApiProvider;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ServiceController extends Controller
{
  //
  public function index(Request $request)
  {
    $services   = [];
    $providers  = ApiProvider::all();
    $categories = Category::all();

    return view("admin.services.index", [
      "services"   => $services,
      "pageTitle"  => "Danh sách dịch vụ",
      'providers'  => $providers,
      "categories" => $categories,
    ]);
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'name'            => 'required|string|max:255',
      'image'           => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:50048',
      'category_id'     => 'required|integer|exists:categories,id',
      'mode'            => 'required|string|in:api,manual,option',
      'service_type'    => 'nullable|string',
      'api_provider_id' => 'nullable|string',
      'api_service_id'  => 'nullable|integer',
      'original_rate'   => 'nullable|numeric',
      'min_buy'         => 'required|integer',
      'max_buy'         => 'required|integer',
      'price'           => 'required|numeric',
      'status'          => 'required|boolean',
      'descr'           => 'nullable|string',
    ]);

    // $category = Category::where('id', $payload['category_id'])->firstOrFail();

    if ($payload['mode'] === 'api') {
      if (!$request->has('api_provider_id') || !$request->has('api_service_id')) {
        return response()->json([
          'status'  => 400,
          'message' => 'Provider and Service are required.',
        ], 400);
      }
    } else if ($payload['mode'] === 'option') {
      if (!in_array($payload['api_provider_id'], ['subvip'])) {
        return response()->json([
          'status'  => 400,
          'message' => 'Provider is not valid.',
        ], 400);
      }
    }

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'), 'public', 'provider');
    }

    $service = Service::create(array_merge($payload, [
      'pid'            => str()->uuid(),
      'type'           => $payload['service_type'],
      'image'          => $payload['image'] ?? null,
      'descr'          => Helper::htmlPurifier($payload['descr']),
      'add_type'       => $payload['mode'],
      'original_price' => $payload['price'],
    ]));


    Helper::addHistory('Thêm dịch vụ #' . $service->id);

    // return response()->json([
    //   'status'  => 200,
    //   'message' => 'Service added successfully.',
    // ]);

    return redirect()->back()->with('success', 'Dịch vụ đã được thêm thành công');
  }

  public function show(Request $request, $id)
  {
    $service    = Service::where('id', $id)->firstOrFail();
    $services   = Service::orderBy("id", "desc")->get();
    $categories = Category::where('status', true)->get();

    return view('admin.services.show', [
      'pageTitle' => 'Chi tiết dịch vụ #' . $service->id,
    ], compact('service', 'services', 'categories'));
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'              => 'required|integer',
      'name'            => 'required|string|max:255',
      'image'           => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:50048',
      'category_id'     => 'required|integer',
      'mode'            => 'required|string|in:api,manual',
      'service_type'    => 'nullable|string',
      'api_provider_id' => 'nullable|integer',
      'api_service_id'  => 'nullable|integer',
      'original_rate'   => 'nullable|numeric',
      'min_buy'         => 'required|integer',
      'max_buy'         => 'required|integer',
      'price'           => 'required|numeric',
      'status'          => 'required|boolean',
      'descr'           => 'nullable|string',
    ]);

    $service  = Service::where('id', $payload['id'])->firstOrFail();
    $category = Category::where('id', $payload['category_id'])->firstOrFail();

    if ($payload['mode'] === 'api') {
      if (!$request->has('api_provider_id') || !$request->has('api_service_id')) {
        return response()->json([
          'status'  => 400,
          'message' => 'Provider and Service are required.',
        ], 400);
      }
    }

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'), 'public', 'provider');
    }

    $service->update(array_merge($payload, [
      'type'           => $payload['service_type'],
      'image'          => $payload['image'] ?? $service->image,
      'descr'          => Helper::htmlPurifier($payload['descr']),
      'add_type'       => $payload['mode'],
      'original_price' => $payload['price'],
    ]));

    Helper::addHistory('Cập nhật dịch vụ #' . $service->id);

    // return response()->json([
    //   'status'  => 200,
    //   'message' => 'Service updated successfully.',
    // ]);
    return redirect()->back()->with('success', 'Dịch vụ đã được cập nhật thành công');
  }

  public function updateStatus(Request $request)
  {
    $payload = $request->validate([
      'id'     => 'required|integer',
      'status' => 'required|boolean',
    ]);

    $service = Service::where('id', $payload['id'])->firstOrFail();

    $service->update([
      'status' => $payload['status'],
    ]);

    return response()->json([
      'status'  => 200,
      'message' => 'Service status updated successfully.',
    ]);
  }


  public function delete(Request $request)
  {

    if ($request->has('ids')) {
      $payload = $request->validate([
        'ids' => 'required|array',
      ]);

      $ids = array_map('intval', $payload['ids']);

      $services = Service::whereIn('id', $ids)->get();

      foreach ($services as $service) {
        $service->delete();
      }

      Helper::addHistory(__t('Thực hiện thao tác xóa nhiều dịch vụ cùng lúc; số lượng: :count', ['count' => $services->count()]));

      return response()->json([
        'status'  => 200,
        'message' => 'Services deleted successfully.',
      ]);
    }

    $payload = $request->validate([
      'id' => 'required|integer',
    ]);

    $service = Service::where('id', $payload['id'])->firstOrFail();
    $service->delete();

    Helper::addHistory('Xóa dịch vụ #' . $service->name);

    return response()->json([
      'status'  => 200,
      'message' => 'Service deleted successfully.',
    ]);
  }

  public function changeCategory(Request $request)
  {
    $payload = $request->validate([
      'ids'         => 'required|array',
      'category_id' => 'required|integer',
    ]);

    $ids = array_map('intval', $payload['ids']);

    $services = Service::whereIn('id', $ids)->get();

    foreach ($services as $service) {
      $service->update([
        'category_id' => $payload['category_id'],
      ]);
    }

    Helper::addHistory(__t('Thực hiện thao tác thay đổi danh mục cho nhiều dịch vụ cùng lúc; số lượng: :count', ['count' => $services->count()]));

    return response()->json([
      'status'  => 200,
      'message' => 'Categories changed successfully.',
    ]);
  }


  public function forms(Request $request, $type)
  {
    if ($request->method() === 'POST') {
      $payload = $request->validate([
        'action'      => 'required|string|in:get-services',
        'provider_id' => 'required|integer',
      ]);

      $provider = ApiProvider::where('id', $payload['provider_id'])->firstOrFail();

      switch ($payload['action']) {
        case 'get-services':
          $app = new SMMApi();
          $items_provider_service = $app->services($provider->toArray());
          $xhtml_option = '<option value="0">Choose Service</option>';
          if ($items_provider_service) {
            if (!in_array($provider['type'], ['realfans'])) {
              usort($items_provider_service, function ($a, $b) {
                return (int) $a['service'] - (int) $b['service'];
              });
            }
            foreach ($items_provider_service as $key => $item) {
              $data_attr = null;
              foreach ($item as $attr => $value) {
                if (in_array($attr, ['min', 'max', 'dripfeed', 'refill'])) {
                  $value = (int) $value;
                }
                if ($attr == 'type') {
                  $value = service_type_format($value);
                }
                $data_attr .= ' data-' . $attr . '="' . $value . '"';
              }
              $data_attr .= ' data-name="' . $item['name'] . '"';
              $class_selected = ($request->input('provider_service_id', null) == $item['service']) ? 'selected' : '';
              $xhtml_option .= sprintf(
                '<option %s value="%s" %s>ID%s - (%s) - %s</option>',
                $class_selected,
                $item['service'],
                $data_attr,
                $item['service'],
                number_format((double) $item['rate']),
                $item['name'],
                // truncate_string($item['name'], 60)
              );
            }
          }
          return $xhtml_option;

        default:
          return response()->json([
            'status'  => 400,
            'message' => 'Action is not valid.',
          ], 400);
      }
    }
    return view('admin.services.forms.' . $type);
  }
}
