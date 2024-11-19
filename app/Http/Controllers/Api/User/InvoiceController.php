<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Invoice;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InvoiceController extends Controller
{
  public function index(Request $request)
  {
    $payload = $request->validate([
      'page'      => 'nullable|integer',
      'limit'     => 'nullable|integer',
      'search'    => 'nullable|string',
      'sort_by'   => 'nullable|string',
      'sort_type' => 'nullable|string|in:asc,desc',
    ]);

    $query = Invoice::where('user_id', $request->user()->id);

    if (isset($payload['search'])) {
      $query = $query->where('content', 'like', '%' . $payload['search'] . '%')
        ->orWhere('ip_address', 'like', '%' . $payload['search'] . '%');
    }

    if (isset($payload['sort_by'])) {
      $query = $query->orderBy($payload['sort_by'], $payload['sort_type'] ?? 'asc');
    }

    $meta = [
      'page'  => (int) ($payload['page'] ?? 1),
      'limit' => (int) ($payload['limit'] ?? 10),
      'total' => $query->count(),
    ];

    $data = $query->skip(($meta['page'] - 1) * $meta['limit'])->take($meta['limit']);

    return response()->json([
      'data'    => [
        'meta' => $meta,
        'data' => $data->get(),
      ],
      'status'  => 200,
      'message' => 'Get invoices successfully',
    ], 200);

  }

  public function show(Request $request, $id)
  {
    $invoice = Invoice::where('user_id', $request->user()->id)->findOrFail($id);

    return response()->json([
      'data'    => $invoice,
      'status'  => 200,
      'message' => 'Get invoice successfully',
    ], 200);
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'amount'  => 'required|integer|min:1',
      'channel' => 'required|string|in:perfect_money,fpayment,banking',
    ]);

    $user   = $request->user();
    $amount = (double) $payload['amount'];
    $config = Helper::getApiConfig($payload['channel']);


    if ($payload['channel'] === 'fpayment') {

      if (deposit_status('crypto') === false) {
        return response()->json([
          'status'  => 400,
          'message' => __t('Chức năng nạp tiền điện tử đang tạm bảo trì, vui lòng thử lại sau!'),
        ], 400);
      }

      if (!isset($config['address_wallet'])) {
        return response()->json([
          'status'  => 400,
          'message' => __t('Chưa cấu hình ví tiền điện tử'),
        ], 400);
      }

      $invoiceCount = Invoice::where('user_id', $user->id)->where('status', 'Processing')->where('type', 'fpayment')->count();
      if ($invoiceCount > 3) {
        return response()->json([
          'status'  => 400,
          'message' => __t('Bạn đã có 3 hóa đơn đang chờ xử lý, vui lòng chờ xử lý hoặc hủy bỏ hóa đơn cũ trước khi tạo hóa đơn mới'),
        ], 400);
      }

      $code      = 'UTM-' . Helper::randomString(7, true);
      $requestId = Helper::randomString(10);

      $createInvoice = Http::get('https://fpayment.co/api/AddInvoice.php', [
        'token_wallet'   => $config['token_wallet'],
        'address_wallet' => $config['address_wallet'],
        'name'           => 'Deposit - ' . $user->username,
        'description'    => 'order code #' . $code,
        'amount'         => $payload['amount'],
        'request_id'     => $requestId,
        'callback'       => route('cron.deposit.fpayment-callback'),
        'return_url'     => route('account.deposits.crypto'),
      ]);

      if ($createInvoice->failed()) {
        return response()->json([
          'status'  => 500,
          'message' => __t('Lỗi máy chủ, vui lòng liên hệ admin!'),
        ], 500);
      }

      $result = $createInvoice->json();

      if (isset($result['status']) && $result['status'] !== 'success') {
        return response()->json([
          'status'  => 500,
          'message' => $result['msg'] ?? __t('Lỗi máy chủ, vui lòng liên hệ admin!'),
        ], 500);
      }

      $ex_rate = $config['exchange'] ?? 24000;
      $amount  = $amount * $ex_rate;

      $data = $result['data'];

      $invoice = Invoice::create([
        'code'            => $code,
        'type'            => 'fpayment',
        'status'          => 'Processing',
        'amount'          => $amount,
        'user_id'         => $user->id,
        'username'        => $user->username,
        'trans_id'        => $data['trans_id'],
        'request_id'      => $requestId,
        'currency'        => cur_setting('currency_code', 'VND'),
        'description'     => 'Create Invoice Fpayment',
        'payment_details' => [
          'amount'      => $data['amount'],
          'trans_id'    => $data['trans_id'],
          'request_id'  => $data['request_id'],
          'url_payment' => $data['url_payment'],
        ],
        'paid_at'         => null,
        'expired_at'      => now()->addHours(6),
      ]);

      return response()->json([
        'data'    => [
          'code'        => $invoice->code,
          'payment_url' => $data['url_payment'],
        ],
        'status'  => 201,
        'message' => __t('Hoá đơn :amount (tỷ giá :ex_rate) đã được tạo thành công!', ['amount' => formatCurrency($amount), 'ex_rate' => number_format($ex_rate)]),
      ], 201);
    } else if ($payload['channel'] === 'banking') {
      $payload = $request->validate([
        'bank_id' => 'required|integer|exists:bank_accounts,id',
      ]);
      $user    = $request->user();
      $bank    = BankAccount::findOrFail($payload['bank_id']);

      //
      $invoiceCount = Invoice::where('user_id', $user->id)->where('status', 'Processing')->count();
      if ($invoiceCount > 3) {
        return response()->json([
          'status'  => 400,
          'message' => __t('Bạn đã có 3 hóa đơn đang chờ xử lý, vui lòng chờ xử lý hoặc hủy bỏ hóa đơn cũ trước khi tạo hóa đơn mới'),
        ], 400);
      }

      $info   = Helper::getConfig('deposit_info');
      $prefix = $info['prefix'] ?? 'GCI';

      $invoice = Invoice::create([
        'code'            => $prefix . time(),
        'type'            => 'deposit',
        'status'          => 'Processing',
        'amount'          => $amount,
        'user_id'         => $user->id,
        'username'        => $user->username,
        'currency'        => 'VND',
        'description'     => 'Create Invoice Banking',
        'payment_details' => [
          'name'   => $bank->name,
          'owner'  => $bank->owner,
          'number' => $bank->number,
        ],
        'paid_at'         => null,
        'expired_at'      => now()->addHours(6),
      ]);

      return response()->json([
        'data'    => $invoice,
        'status'  => 200,
        'message' => __t('Tạo hóa đơn nạp tiền thành công; vui lòng chuyển khoản theo thông tin bên dưới để hoàn tất giao dịch!'),
      ], 200);
    }
  }
}
