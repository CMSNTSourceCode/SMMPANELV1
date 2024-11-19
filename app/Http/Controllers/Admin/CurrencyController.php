<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CurrencyList;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
  //
  public function index(Request $request)
  {
    $records = CurrencyList::all();

    return view('admin.currency.index', compact('records'), [
      'pageTitle' => 'Quản lý tiền tệ',
    ]);
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'currency_code'               => 'required|string|max:12',
      'currency_symbol'             => 'required|string|max:12',
      'currency_thousand_separator' => 'required|in:dot,comma,space',
      'currency_decimal_separator'  => 'required|in:dot,comma',
      'currency_decimal'            => 'required|integer|in:0,1,2,3,4',
      'new_currecry_rate'           => 'required|numeric',
    ]);

    CurrencyList::create($payload);

    return response()->json([
      'status'  => 200,
      'message' => 'Currency added successfully.',
    ]);
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'                          => 'required|integer',
      'currency_code'               => 'required|string|max:12',
      'currency_symbol'             => 'required|string|max:12',
      'currency_thousand_separator' => 'required|in:dot,comma,space',
      'currency_decimal_separator'  => 'required|in:dot,comma',
      'currency_decimal'            => 'required|integer|in:0,1,2,3,4',
      'new_currecry_rate'           => 'required|numeric',
    ]);

    $currency = CurrencyList::find($payload['id']);
    $currency->update($payload);

    return response()->json([
      'status'  => 200,
      'message' => 'Currency updated successfully.',
    ]);
  }

  public function delete(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|integer',
    ]);

    $currency = CurrencyList::find($payload['id'])->delete();

    Helper::addHistory(__t('Đã xoá loại tiền tệ :name', ['name' => $currency->currency_code]));

    return response()->json([
      'status'  => 200,
      'message' => 'Currency deleted successfully.',
    ]);
  }
}
