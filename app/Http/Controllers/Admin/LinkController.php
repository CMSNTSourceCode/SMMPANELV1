<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuLink;
use App\Helpers\Helper;
use Illuminate\Http\Request;

class LinkController extends Controller
{
  public function index()
  {
    $links = MenuLink::all();

    return view('admin.links.index', compact('links'));
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'name'   => 'required|string|max:128',
      'link'   => 'required|url',
      'image'  => 'required|file|image|mimes:jpeg,png,gif,webp,svg|max:10048',
      'target' => 'required|string|in:_self,_blank'
    ]);

    $payload['image'] = Helper::uploadFile($request->file('image'), 'public');

    MenuLink::create($payload);

    Helper::addHistory("Thêm mới liên kết: $request->name");

    return redirect()->back()->with('success', 'Thêm mới liên kết thành công.');
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'     => 'required|integer|exists:menu_links,id',
      'name'   => 'required|string|max:128',
      'link'   => 'required|url',
      'image'  => 'nullable|file|image|mimes:jpeg,png,gif,webp,svg|max:10048',
      'target' => 'required|string|in:_self,_blank'
    ]);

    $link = MenuLink::findOrFail($request->id);

    if ($request->has('image'))
      $payload['image'] = Helper::uploadFile($request->file('image'), 'public');
    else
      $payload['image'] = $link->image;

    $link->update($payload);


    Helper::addHistory("Cập nhật liên kết: $request->name");

    return redirect()->back()->with('success', 'Cập nhật liên kết thành công.');
  }


  public function delete(Request $request)
  {
    $request->validate([
      'id' => 'required|integer',
    ]);

    $link = MenuLink::findOrFail($request->id);

    $link->delete();

    Helper::addHistory('Xoá liên kết ' . $link->name . ' #' . $link->id);

    return response()->json([
      'status'  => 200,
      'message' => 'Xóa liên kết thành công.',
    ]);
  }

}
