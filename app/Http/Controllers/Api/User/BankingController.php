<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankingController extends Controller
{
  public function index(Request $request)
  {
    $query = BankAccount::where('status', true);

    return response()->json([
      'data'    => $query->get(),
      'status'  => 200,
      'message' => 'Lấy danh sách ngân hàng thành công',
    ], 200);
  }
}
