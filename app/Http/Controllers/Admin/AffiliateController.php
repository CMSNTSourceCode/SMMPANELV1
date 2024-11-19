<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\AffiliateUser;
use App\Models\User;
use App\Models\WalletLog;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
  public function index()
  {
    $referrals  = AffiliateUser::all();
    $histories  = WalletLog::where('type', 'affiliate')->get();
    $affiliates = Affiliate::all();

    $totalAmount          = $histories->sum('amount');
    $totalAmountPending   = $histories->where('status', 'Pending')->sum('amount');
    $totalAmountCompleted = $histories->where('status', 'Completed')->sum('amount');
    $totalAmountCancelled = $histories->where('status', 'Cancelled')->sum('amount');


    $commissions = WalletLog::where('type', 'commission')->get();

    return view('admin.affiliate.index', compact('referrals', 'histories', 'totalAmount', 'totalAmountPending', 'totalAmountCompleted', 'totalAmountCancelled', 'commissions', 'affiliates'));
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'        => 'required|integer|exists:wallet_logs',
      'status'    => 'required|string|in:Completed,Cancelled,Pending',
      'user_note' => 'nullable|string|max:255',
      // 'sys_note'  => 'nullable|string|max:255',
    ]);

    $history = WalletLog::findOrFail($payload['id']);

    if ($history->status !== 'Pending') {
      return redirect()->back()->with('error', 'Không thể cập nhật lịch sử này nữa.');
    }

    $history->update([
      'status'    => $payload['status'],
      'user_note' => $payload['user_note'],
      // 'sys_note'  => $payload['sys_note'],
    ]);

    return redirect()->back()->with('success', 'Cập nhật lịch sử thành công.');
  }
}
