<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Helpers\Helper;
use Illuminate\Http\Request;

class BankController extends Controller
{
  public function index()
  {
    $banks = BankAccount::get();

    return view('admin.banks.index', compact('banks'));
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'name'   => 'required|string',
      'image'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'owner'  => 'required|string',
      'qrcode' => 'nullable|string',
      'number' => 'required|string',
      'branch' => 'nullable|string',
    ]);

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'));
    } else {
      $payload['image'] = null;
    }

    BankAccount::create($payload);

    Helper::addHistory('Thêm tài khoản ' . $payload['number'] . ', ngân hàng ' . $payload['name']);

    return redirect()->back()->with('success', 'Thêm tài khoản ngân hàng thành công.');
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'     => 'required|integer',
      'name'   => 'required|string',
      'image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'owner'  => 'required|string',
      'qrcode' => 'nullable|string',
      'number' => 'required|string',
      'branch' => 'nullable|string',
    ]);

    $bank = BankAccount::findOrFail($request->id);

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'));
    } else {
      $payload['image'] = $bank->image;
    }

    $bank->update($payload);

    Helper::addHistory('Cập nhật tài khoản ngân hàng ' . $payload['number'] . ' #' . $payload['id']);

    return redirect()->back()->with('success', 'Cập nhật tài khoản ngân hàng #' . $payload['id'] . ' thành công.');
  }

  public function delete(Request $request)
  {
    $request->validate([
      'id' => 'required|integer',
    ]);

    $bank = BankAccount::findOrFail($request->id);

    $bank->delete();

    Helper::addHistory('Xóa tài khoản ngân hàng ' . $bank->number . ' #' . $bank->id);

    return response()->json([
      'status'  => 200,
      'message' => 'Xóa tài khoản ngân hàng thành công.',
    ]);
  }
}
