<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Helpers\Helper;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
  public function index()
  {
    $langs = Language::all();

    return view('admin.languages.index', compact('langs'));
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'flag'    => 'nullable|file|mimes:png,jpg,jpeg,svg|max:5048',
      'code'    => 'required|string|max:10',
      'name'    => 'nullable|string|max:20',
      'status'  => 'required|boolean',
      'default' => 'required|boolean',
    ]);

    $flag            = $request->file('flag');
    $payload['code'] = strtolower($payload['code']);

    if ($flag) {
      $payload['flag'] = Helper::uploadFile($flag, 'public', 'flags');
    }

    if (!isset($payload['flag'])) {
      $payload['flag'] = 'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.3/flags/4x3/' . strtolower($payload['code']) . '.svg';
    }

    if (!isset($payload['name'])) {
      $payload['name'] = Helper::getCountryName($payload['code'], 'en');
    }

    if ($payload['default'] === "1") {
      Language::where('default', true)->update(['default' => false]);
    }

    $lang = Language::create($payload);

    if ($lang) {
      Helper::addHistory("Thêm ngôn ngữ $lang->name");

      return redirect()->back()->with('success', 'Thêm ngôn ngữ thành công');
    }

    return redirect()->back()->with('error', 'Thêm ngôn ngữ thất bại');
  }

  public function update(Request $request, $id)
  {
    $payload = $request->validate([
      'flag'    => 'nullable|file|mimes:png,jpg,jpeg,svg|max:5048',
      'code'    => 'required|string|max:10',
      'name'    => 'nullable|string|max:20',
      'status'  => 'required|boolean',
      'default' => 'required|boolean',
    ]);

    $lang = Language::findOrFail($id);

    $flag            = $request->file('flag');
    $payload['code'] = strtolower($payload['code']);

    if ($flag && $flag !== $lang->flag) {
      $payload['flag'] = Helper::uploadFile($flag, 'public', 'flags');
    }

    if (!isset($payload['flag'])) {
      $payload['flag'] = 'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.3/flags/4x3/' . strtolower($payload['code']) . '.svg';
    }

    if (!isset($payload['name'])) {
      $payload['name'] = Helper::getCountryName($payload['code'], 'en');
    }

    if ($payload['default'] === "1") {
      Language::where('default', true)->update(['default' => false]);
    }

    $lang->update($payload);

    Helper::addHistory("Cập nhật ngôn ngữ $lang->name");

    return redirect()->back()->with('success', 'Cập nhật ngôn ngữ thành công');

  }

  public function delete(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|integer',
    ]);

    $lang = Language::findOrFail($payload['id']);

    if ($lang->default) {
      return redirect()->back()->with('error', 'Không thể xóa ngôn ngữ mặc định');
    }

    $lang->delete();

    Helper::addHistory("Xóa ngôn ngữ $lang->name");

    Language::where('default', true)->update(['default' => false]);

    // reset default language
    $default = Language::first();

    if ($default) {
      $default->update(['default' => true]);
    }

    // return redirect()->back()->with('success', 'Xóa ngôn ngữ thành công');
    return response()->json([
      'status'  => 200,
      'message' => 'Xóa ngôn ngữ thành công',
    ]);
  }
}
