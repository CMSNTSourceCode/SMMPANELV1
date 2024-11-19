<?php

use App\Http\Middleware\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  $user = $request->user();

  $user->balance_formatted = formatCurrencyF($user->balance);

  return $user;
});

// Deposit Routes
Route::middleware('auth:sanctum')->prefix('/deposit')->group(function () {
  Route::post('/paypal-confirm', [App\Http\Controllers\Api\Deposit\PaypalController::class, 'index']);
});

// Routes Services
Route::prefix('/preset-data')->group(function () {
  Route::get('/platforms', [App\Http\Controllers\Api\Preset\PlatformController::class, 'index']);
  Route::get('/services', [App\Http\Controllers\Api\Preset\ServiceController::class, 'index']);
  Route::get('/service-info', [App\Http\Controllers\Api\Preset\ServiceController::class, 'info']);
  Route::get('/categories', [App\Http\Controllers\Api\Preset\CategoryController::class, 'index']);
});


// Users Routes
Route::middleware('auth:sanctum')->prefix('/users')->group(function () {
  // Profiles Routes
  Route::get('/histories', [App\Http\Controllers\Api\User\HistoryController::class, 'index']);
  Route::get('/transactions', [App\Http\Controllers\Api\User\TransactionController::class, 'index']);
  // Invoices Routes
  Route::get('/invoices', [App\Http\Controllers\Api\User\InvoiceController::class, 'index']);
  Route::get('/invoices/{id}', [App\Http\Controllers\Api\User\InvoiceController::class, 'show']);
  Route::post('/invoices', [App\Http\Controllers\Api\User\InvoiceController::class, 'store']);
  // Banking For Deposit Routes
  Route::get('/banks', [App\Http\Controllers\Api\User\BankingController::class, 'index']);

  // Affiliate Routes
  Route::prefix('/affiliates')->group(function () {
    Route::post('/withdraw', [App\Http\Controllers\Api\User\AffiliateController::class, 'withdraw']);
  });

  // Vouchers Routes
  Route::prefix('/vouchers')->group(function () {
    Route::post('/redeem', [App\Http\Controllers\Api\User\VoucherController::class, 'redeem']);
  });

  // Deposits Routes
  Route::get('/card-list', [App\Http\Controllers\Api\User\DepositController::class, 'cardList']);
  Route::post('/send-card', [App\Http\Controllers\Api\User\DepositController::class, 'sendCard']);
});

// Orders Controller
Route::middleware('auth:sanctum')->prefix('/orders')->group(function () {
  // [GET] /api/orders - get orders
  Route::get('/', [App\Http\Controllers\Api\Service\OrderController::class, 'index']);
  // [GET] /api/orders/get-by-id - get order by id
  Route::get('/get-by-id', [App\Http\Controllers\Api\Service\OrderController::class, 'getById']);
  // [GET] /api/orders/get-by-ids - get orders by ids
  Route::get('/get-by-ids', [App\Http\Controllers\Api\Service\OrderController::class, 'getByIds']);
  // [POST] /api/orders/store - store order
  Route::post('/store', [App\Http\Controllers\Api\Service\OrderController::class, 'store']);
  // [POST/GET] /api/orders/:id/:action - action order
  Route::post('/{id}/{action}', [App\Http\Controllers\Api\Service\OrderController::class, 'action']);
});

// ToolController
Route::prefix('/tools')->group(function () {
  // [GET] /api/tools/facebook/get-uid
  Route::get('/facebook/get-uid', [App\Http\Controllers\Api\ToolController::class, 'getUidFacebook']);
  // [GET] /api/tools/get-form/:form_type
  Route::get('/get-form/{form_type}', [App\Http\Controllers\Api\ToolController::class, 'getForm']);
  // [POST] /api/tools/calculate
  Route::post('/calculate', [App\Http\Controllers\Api\ToolController::class, 'calculate']);
});

// Admin Routes
Route::middleware(['auth:sanctum', Admin::class])->prefix('/admin')->group(function () {
  // User Routes
  Route::prefix('/users')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\Admin\UserController::class, 'index']);
  });
  // Transaction Routes
  Route::prefix('/transactions')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\Admin\TransactionController::class, 'index']);
    Route::get('/list-card', [App\Http\Controllers\Api\Admin\TransactionController::class, 'listCard']);
  });
  // History Routes
  Route::prefix('/histories')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\Admin\HistoryController::class, 'index']);
  });
  // Service Routes
  Route::prefix('/services')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\Admin\ServiceController::class, 'index']);
  });
  // Tools Routes
  Route::prefix('/tools')->group(function () {
    Route::post('/upload', [App\Http\Controllers\Api\Tools\UploadController::class, 'index']);
  });
});

// SMM Panel API v2
Route::get('/v2', [App\Http\Controllers\Api\V2Controller::class, 'process'])->name('api.v2');
Route::post('/v2', [App\Http\Controllers\Api\V2Controller::class, 'process'])->name('api.v2');
