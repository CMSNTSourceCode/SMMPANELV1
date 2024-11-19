<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\CardList;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{

  public function card(Request $request)
  {
    $config = Helper::getApiConfig('charging_card');

    if (!isset($config['api_url']) || !isset($config['partner_id']) || !isset($config['partner_key'])) {
      return redirect()->back()->with('error', __t('Chưa cấu hình API nạp thẻ.'));
    }

    $card_fees = $config['fees'] ?? [];

    $invoices = CardList::where('user_id', $request->user()->id)->orderBy('id', 'desc')->limit(200)->get();

    return view('account.deposits.card', [
      'pageTitle' => __t('Nạp Tiền Tài Khoản Bằng Thẻ Cào'),
    ], compact('config', 'invoices', 'card_fees'));
  }

  public function paypal()
  {
    $config = Helper::getApiConfig('paypal');

    if (!isset($config['client_id'])) {
      return redirect()->back()->with('error', 'Chưa cấu hình ví tiền điện tử.');
    }

    $invoices = Invoice::where('user_id', auth()->id())->where('type', 'paypal')->simplePaginate(10);

    return view('account.deposits.paypal', [
      'pageTitle' => 'Nạp Tiền Tài Khoản Bằng Paypal',
    ], compact('config', 'invoices'));
  }

  public function crypto(Request $request)
  {
    $config = Helper::getApiConfig('fpayment');

    if (!isset($config['address_wallet'])) {
      return redirect()->back()->with('error', __t('Chưa cấu hình ví tiền điện tử.'));
    }

    $invoices = Invoice::where('user_id', $request->user()->id)->where('type', 'fpayment')->orderBy('id', 'desc')->get();

    return view('account.deposits.crypto', [
      'pageTitle' => __t('Nạp Tiền Tài Khoản Bằng Crypto'),
    ], compact('config', 'invoices'));
  }

  public function transfer(Request $request)
  {
    $user           = Auth::user();
    $banks          = BankAccount::where('status', true)->get();
    $config         = Helper::getConfig('deposit_info');
    $transactions   = Transaction::where('user_id', $user->id)->where('type', 'deposit')->orderBy('id', 'desc')->limit(200)->get();
    $deposit_prefix = ($config['prefix'] ?? '') . $request->user()->id;
    return view('account.deposits.index', [
      'pageTitle' => __t('Nạp Tiền Tài Khoản Bằng Chuyển Khoản'),
    ], compact('user', 'banks', 'config', 'transactions', 'deposit_prefix'));
  }

  public function perfectMoney(Request $request)
  {
    $config = Helper::getApiConfig('perfect_money');

    if (!isset($config['account_id'])) {
      return redirect()->back()->with('error', 'Chưa cấu hình tài khoản Perfect Money.');
    }

    $user      = $request->user();
    $invoice   = Invoice::where('user_id', $user->id)->where('type', 'perfect_money')->where('status', 'processing')->first();
    $requestId = Helper::randomString(10);

    if ($invoice === null) {
      $invoice = Invoice::create([
        'code'        => 'PM-' . Helper::randomString(7, true),
        'type'        => 'perfect_money',
        'status'      => 'processing',
        'amount'      => 0,
        'user_id'     => $user->id,
        'username'    => $user->username,
        'currency'    => 'USD',
        'request_id'  => $requestId,
        'description' => 'Nạp tiền tài khoản bằng Perfect Money',
      ]);
    }

    $params = [
      'API_URL'        => 'https://perfectmoney.is/api/step1.asp',
      'PAYMENT_ID'     => $invoice->request_id,
      // mã giao dịch không trùng lặp để lưu lên hệ thống
      'PAYEE_ACCOUNT'  => $config['account_id'],
      // mã tài khoản Perfect Money
      'PAYMENT_UNITS'  => 'USD',
      // đơn vị tiền tệ,
      'PAYEE_NAME'     => $user->username,
      // tên người thanh toán
      'PAYMENT_URL'    => route('account.deposits.perfect-money'),
      // URL của hoá đơn
      'NOPAYMENT_URL'  => route('account.deposits.perfect-money'),
      // URL của hoá đơn
      'STATUS_URL'     => route('cron.deposit.pm-callback'),
      // Webhook callback
      'SUGGESTED_MEMO' => 'Payment - ' . $invoice->code
    ];

    $invoices = Invoice::where('user_id', $request->user()->id)->where('type', 'perfect_money')->get();

    return view('account.deposits.perfect_money', [
      'pageTitle' => 'Nạp Tiền Tài Khoản Bằng Perfect Money',
    ], compact('config', 'invoices', 'invoice', 'params'));
  }

}
