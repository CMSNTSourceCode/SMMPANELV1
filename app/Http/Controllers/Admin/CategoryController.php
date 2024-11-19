<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ListPlatform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
  //
  public function index(Request $request)
  {
    $platforms  = ListPlatform::orderBy('priority', 'desc')->orderBy('id', 'desc')->get();
    $categories = Category::orderBy('id', 'desc')->get();

    return view('admin.category.index', [
      'platforms'  => $platforms,
      'pageTitle'  => 'Danh sách danh mục',
      'categories' => $categories,
    ]);
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'name'        => 'required|string|max:255',
      'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
      'status'      => 'required|boolean',
      'priority'    => 'required|integer',
      'platform_id' => 'required|integer|exists:list_platforms,id',
    ]);

    // remove preset_platforms cache
    Cache::forget('preset_categories');

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'), 'public', 'provider');
    }

    $category = Category::create(array_merge($payload, [
      'pid' => str()->uuid(),
    ]));

    Helper::addHistory('Thêm danh mục ' . $category->name . ' thành công');

    return redirect()->route('admin.categories')->with('success', 'Thêm danh mục thành công #' . $category->id);
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'          => 'required|integer',
      'name'        => 'required|string|max:255',
      'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
      'status'      => 'required|boolean',
      'priority'    => 'required|integer',
      'platform_id' => 'required|integer|exists:list_platforms,id',
    ]);

    // remove preset_platforms cache
    Cache::forget('preset_categories');

    $category = Category::find($payload['id']);

    if (!$category) {
      return redirect()->route('admin.categories')->with('error', 'Không tìm thấy danh mục #' . $payload['id']);
    }

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'), 'public', 'provider');
    } else {
      $payload['image'] = $category->image;
    }

    $category->update($payload);

    Helper::addHistory('Cập nhật danh mục ' . $category->name . ' thành công');

    return redirect()->route('admin.categories')->with('success', 'Cập nhật danh mục thành công #' . $category->id);
  }

  public function delete(Request $request)
  {
    // remove preset_platforms cache
    Cache::forget('preset_categories');

    if ($request->has('ids')) {
      $payload = $request->validate([
        'ids' => 'required|array',
      ]);

      $ids = array_map('intval', $payload['ids']);

      $services = Category::whereIn('id', $ids)->get();

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

    $category = Category::find($payload['id']);

    if (!$category) {
      return redirect()->route('admin.categories')->with('error', 'Không tìm thấy danh mục cần xoá');
    }

    $category->delete();

    Helper::addHistory('Xoá danh mục ' . $category->name . ' thành công');

    return response()->json([
      'status'  => 200,
      'message' => 'Category deleted successfully.',
    ]);
  }
}
