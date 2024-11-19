<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherLog;
use App\Helpers\Helper;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
  public function index()
  {
    $users     = User::get();
    $vouchers  = Voucher::get();
    $histories = VoucherLog::limit(500)->get();

    return view('admin.vouchers.index', compact('vouchers', 'histories', 'users'));
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'code'        => 'required|unique:vouchers',
      'type'        => 'required|string',
      'value'       => 'required|numeric|min:1|max:100',
      'username'    => 'nullable|exists:users,username',
      'start_date'  => 'required|date',
      'expire_date' => 'required|date',
    ]);

    Voucher::create($payload);

    Helper::addHistory('Thêm mã giảm giá ' . $payload['code'] . ' vào hệ thống');

    return redirect()->back()->with('success', 'Thêm mã giảm giá thành công');
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'          => 'required|exists:vouchers,id',
      'code'        => 'required|unique:vouchers,code,' . $request->id . ',id',
      'type'        => 'required|string',
      'value'       => 'required|numeric|min:1|max:100',
      'username'    => 'nullable|exists:users,username',
      'start_date'  => 'required|date',
      'expire_date' => 'required|date',
    ]);

    $voucher = Voucher::find($payload['id']);

    $voucher->update($payload);

    Helper::addHistory('Thêm mã giảm giá ' . $payload['code'] . ' vào hệ thống');

    return redirect()->back()->with('success', 'Thêm mã giảm giá thành công');
  }

  public function delete(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|exists:vouchers,id'
    ]);

    $voucher = Voucher::find($payload['id']);

    $voucher->delete();

    Helper::addHistory('Xóa mã giảm giá ' . $voucher->code . ' khỏi hệ thống');

    return response()->json([
      'status'  => 200,
      'message' => 'Xóa mã giảm giá thành công'
    ], 200);
  }
}
