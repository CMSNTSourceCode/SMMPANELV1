<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ListPlatform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PlatformController extends Controller
{
  //
  public function index(Request $request)
  {
    $platforms = ListPlatform::orderBy('id', 'desc')->get();

    return view('admin.platforms.index', [
      'categories' => $platforms,
      'pageTitle'  => 'Danh sách nền tảng',
    ]);
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'name'     => 'required|string|max:255',
      'image'    => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
      'status'   => 'required|boolean',
      'priority' => 'required|integer',
    ]);

    // remove preset_platforms cache
    Cache::forget('preset_platforms');

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'), 'public', 'provider');
    }

    $platform = ListPlatform::create(array_merge($payload, [
      'slug' => str()->uuid(),
    ]));

    Helper::addHistory('Thêm nền tảng ' . $platform->name . ' thành công');

    return redirect()->route('admin.platforms')->with('success', 'Thêm nền tảng thành công #' . $platform->id);
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'       => 'required|integer',
      'name'     => 'required|string|max:255',
      'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
      'status'   => 'required|boolean',
      'priority' => 'required|integer',
    ]);

    $platform = ListPlatform::find($payload['id']);

    // remove preset_platforms cache
    Cache::forget('preset_platforms');

    if (!$platform) {
      return redirect()->route('admin.platforms')->with('error', 'Không tìm thấy nền tảng #' . $payload['id']);
    }

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'), 'public', 'provider');
    } else {
      $payload['image'] = $platform['image'];
    }

    $platform->update($payload);

    Helper::addHistory('Cập nhật nền tảng ' . $platform['name'] . ' thành công');

    return redirect()->route('admin.platforms')->with('success', 'Cập nhật nền tảng thành công #' . $platform['id']);
  }

  public function delete(Request $request)
  {
    // remove preset_platforms cache
    Cache::forget('preset_platforms');

    if ($request->has('ids')) {
      $payload = $request->validate([
        'ids' => 'required|array',
      ]);

      $ids = array_map('intval', $payload['ids']);

      $services = ListPlatform::whereIn('id', $ids)->get();

      foreach ($services as $service) {
        $service->delete();
      }

      Helper::addHistory(__t('Thực hiện thao tác xóa nhiều chuyên mục cùng lúc; số lượng: :count', ['count' => $services->count()]));

      return response()->json([
        'status'  => 200,
        'message' => 'Categories deleted successfully.',
      ]);
    }

    $payload = $request->validate([
      'id' => 'required|integer',
    ]);

    $platform = ListPlatform::find($payload['id']);

    if (!$platform) {
      return redirect()->route('admin.platforms')->with('error', 'Không tìm thấy nền tảng cần xoá');
    }

    $platform->delete();

    Helper::addHistory('Xoá nền tảng ' . $platform->name . ' thành công');

    return response()->json([
      'status'  => 200,
      'message' => 'Platform deleted successfully.',
    ]);
  }
}
