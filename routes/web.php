<?php

use App\Http\Middleware\Admin;
use App\Http\Middleware\CheckLastLogin;
use App\Libraries\Update;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('home');

Route::middleware('auth')->get('/set-locale/{locale}', App\Http\Controllers\SetLocaleController::class)->name('set-locale');

// Pages Route
Route::get('/join/{ref}', [App\Http\Controllers\HomeController::class, 'ref'])->name('ref');

Route::prefix('/pages')->group(function () {
  Route::middleware('auth')->get('/statistics', [App\Http\Controllers\PageController::class, 'statistics'])->name('pages.statistics');
  Route::middleware('auth')->get('/affiliates', [App\Http\Controllers\PageController::class, 'affiliates'])->name('pages.affiliates');
  Route::get('/services', [App\Http\Controllers\PageController::class, 'services'])->name('pages.services');
  Route::get('/api-docs', [App\Http\Controllers\PageController::class, 'apiDocs'])->name('pages.api-docs');
  Route::get('/privacy-policy', [App\Http\Controllers\PageController::class, 'privacyPolicy'])->name('pages.privacy');
});
Route::get('/terms-of-service', [App\Http\Controllers\PageController::class, 'termsOfService'])->name('pages.tos');

// Accounts Routes
Route::middleware(['auth', CheckLastLogin::class])->prefix('/account')->group(function () {
  // Profile Routes
  Route::prefix('/profile')->group(function () {
    Route::get('/', [App\Http\Controllers\Account\ProfileController::class, 'index'])->name('account.profile.index');
    Route::post('/update', [App\Http\Controllers\Account\ProfileController::class, 'update'])->name('account.profile.update');
    Route::post('/token-update', [App\Http\Controllers\Account\ProfileController::class, 'tokenUpdate'])->name('account.profile.token-update');
    Route::post('/currency-update', [App\Http\Controllers\Account\ProfileController::class, 'currencyUpdate'])->name('account.profile.currency-update');
    Route::post('/password-update', [App\Http\Controllers\Account\ProfileController::class, 'passwordUpdate'])->name('account.profile.password-update');
  });
  // Transaction Routes
  Route::get('/transactions', [App\Http\Controllers\Account\ProfileController::class, 'transactions'])->name('account.transactions');
  // Deposit Routes
  Route::prefix('/deposits')->group(function () {
    Route::get('/card', [App\Http\Controllers\Account\DepositController::class, 'card'])->name('account.deposits.card');
    Route::get('/crypto', [App\Http\Controllers\Account\DepositController::class, 'crypto'])->name('account.deposits.crypto');
    Route::get('/paypal', [App\Http\Controllers\Account\DepositController::class, 'paypal'])->name('account.deposits.paypal');
    Route::get('/transfer', [App\Http\Controllers\Account\DepositController::class, 'transfer'])->name('account.deposits.transfer');
    Route::get('/perfect-money', [App\Http\Controllers\Account\DepositController::class, 'perfectMoney'])->name('account.deposits.perfect-money');
  });
  // Orders Route
  Route::get('/orders', [App\Http\Controllers\Account\OrderController::class, 'index'])->name('account.orders');
});

// Services Routes
Route::middleware(['auth', CheckLastLogin::class])->prefix('/services')->group(function () {
  Route::get('/', [App\Http\Controllers\ServiceController::class, 'index'])->name('services.index');
});
// Orders Routes
Route::middleware('auth')->prefix('/orders')->group(function () { });

// Articles Routes
Route::prefix('/articles')->group(function () {
  Route::get('/', [App\Http\Controllers\Pages\ArticleController::class, 'index'])->name('articles.index');
  Route::get('/{slug}', [App\Http\Controllers\Pages\ArticleController::class, 'show'])->name('articles.show');
});

// Admin Routes
Route::middleware(['auth', Admin::class, CheckLastLogin::class])->prefix('/admin')->group(function () {
  // Dashboard
  Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
  Route::get('/update', [App\Http\Controllers\Admin\UpdateController::class, 'index'])->name('admin.update');
  // Settings
  Route::prefix('/settings')->group(function () {
    // GeneralController
    Route::prefix('/general')->group(function () {
      Route::get('/', [App\Http\Controllers\Admin\Settings\GeneralController::class, 'index'])->name('admin.settings.general');
      Route::post('/', [App\Http\Controllers\Admin\Settings\GeneralController::class, 'update'])->name('admin.settings.general.update');
    });
    // ApiController
    Route::prefix('/apis')->group(function () {
      Route::get('/', [App\Http\Controllers\Admin\Settings\ApiController::class, 'index'])->name('admin.settings.apis');
      Route::post('/', [App\Http\Controllers\Admin\Settings\ApiController::class, 'update'])->name('admin.settings.apis.update');
    });
    // NoticeController
    Route::prefix('/notices')->group(function () {
      Route::get('/', [App\Http\Controllers\Admin\Settings\NoticeController::class, 'index'])->name('admin.settings.notices');
      Route::post('/', [App\Http\Controllers\Admin\Settings\NoticeController::class, 'update'])->name('admin.settings.notices.update');
    });
    // PriceController
    Route::prefix('/prices')->group(function () {
      Route::get('/', [App\Http\Controllers\Admin\Settings\PriceController::class, 'index'])->name('admin.settings.prices');
      Route::post('/store', [App\Http\Controllers\Admin\Settings\PriceController::class, 'store'])->name('admin.settings.prices.store');
      Route::post('/update', [App\Http\Controllers\Admin\Settings\PriceController::class, 'update'])->name('admin.settings.prices.update');
      Route::post('/delete', [App\Http\Controllers\Admin\Settings\PriceController::class, 'delete'])->name('admin.settings.prices.delete');
    });
    // CurrencyController
    Route::prefix('/currencies')->group(function () {
      Route::get('/', [App\Http\Controllers\Admin\Settings\CurrencyController::class, 'index'])->name('admin.settings.currencies');
      Route::post('/store', [App\Http\Controllers\Admin\Settings\CurrencyController::class, 'store'])->name('admin.settings.currencies.store');
      Route::post('/update', [App\Http\Controllers\Admin\Settings\CurrencyController::class, 'update'])->name('admin.settings.currencies.update');
      Route::post('/delete', [App\Http\Controllers\Admin\Settings\CurrencyController::class, 'delete'])->name('admin.settings.currencies.delete');
    });
  });
  // Languages
  Route::prefix('/languages')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\LanguageController::class, 'index'])->name('admin.languages');
    Route::post('/store', [App\Http\Controllers\Admin\LanguageController::class, 'store'])->name('admin.languages.store');
    Route::post('/update/{id}', [App\Http\Controllers\Admin\LanguageController::class, 'update'])->name('admin.languages.update');
    Route::post('/delete', [App\Http\Controllers\Admin\LanguageController::class, 'delete'])->name('admin.languages.delete');
  });
  // Languages -> Translations
  Route::prefix('/translations')->group(function () {
    Route::get('/{id}', [App\Http\Controllers\Admin\TranslationController::class, 'index'])->name('admin.translations');
    Route::post('/update', [App\Http\Controllers\Admin\TranslationController::class, 'update'])->name('admin.translations.update');
    Route::post('/{id}/add-key', [App\Http\Controllers\Admin\TranslationController::class, 'addKey'])->name('admin.translations.addKey');
  });
  // Users
  Route::prefix('/users')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users');
    Route::get('/edit/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
    Route::post('/update/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
    Route::get('/login-to/{username}', [App\Http\Controllers\Admin\UserController::class, 'loginTo'])->name('admin.users.login-to');
  });
  // Banks
  Route::prefix('/banks/accounts')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\BankController::class, 'index'])->name('admin.banks');
    Route::post('/store', [App\Http\Controllers\Admin\BankController::class, 'store'])->name('admin.banks.store');
    Route::post('/update', [App\Http\Controllers\Admin\BankController::class, 'update'])->name('admin.banks.update');
    Route::post('/delete', [App\Http\Controllers\Admin\BankController::class, 'delete'])->name('admin.banks.delete');
  });
  // Invoices
  Route::prefix('/invoices')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\InvoiceController::class, 'index'])->name('admin.invoices');
    Route::post('/update', [App\Http\Controllers\Admin\InvoiceController::class, 'update'])->name('admin.invoices.update');
    Route::post('/delete', [App\Http\Controllers\Admin\InvoiceController::class, 'delete'])->name('admin.invoices.delete');
  });
  // Posts
  Route::prefix('/posts')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\PostController::class, 'index'])->name('admin.posts');
    Route::get('/create', [App\Http\Controllers\Admin\PostController::class, 'create'])->name('admin.posts.create');
    Route::post('/store', [App\Http\Controllers\Admin\PostController::class, 'store'])->name('admin.posts.store');
    Route::get('/edit/{id}', [App\Http\Controllers\Admin\PostController::class, 'show'])->name('admin.posts.show');
    Route::post('/update/{id}', [App\Http\Controllers\Admin\PostController::class, 'update'])->name('admin.posts.update');
    Route::post('/delete', [App\Http\Controllers\Admin\PostController::class, 'delete'])->name('admin.posts.delete');
  });
  // Links
  Route::prefix('/links')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\LinkController::class, 'index'])->name('admin.links');
    Route::post('/store', [App\Http\Controllers\Admin\LinkController::class, 'store'])->name('admin.links.store');
    Route::post('/update', [App\Http\Controllers\Admin\LinkController::class, 'update'])->name('admin.links.update');
    Route::post('/delete', [App\Http\Controllers\Admin\LinkController::class, 'delete'])->name('admin.links.delete');
  });
  // Affiliates
  Route::prefix('/affiliates')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\AffiliateController::class, 'index'])->name('admin.affiliates');
    Route::post('/update', [App\Http\Controllers\Admin\AffiliateController::class, 'update'])->name('admin.affiliates.update');
  });
  // Transactions
  Route::prefix('/transactions')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('admin.transactions');
    Route::get('/cards', [App\Http\Controllers\Admin\TransactionController::class, 'cards'])->name('admin.transactions.cards');
    Route::get('/bank-logs', [App\Http\Controllers\Admin\TransactionController::class, 'bankLogs'])->name('admin.transactions.bank-logs');
  });
  // Histories
  Route::prefix('/histories')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\HistoryController::class, 'index'])->name('admin.histories');
  });
  // Vouchers
  Route::prefix('/vouchers')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\VoucherController::class, 'index'])->name('admin.vouchers');
    Route::post('/store', [App\Http\Controllers\Admin\VoucherController::class, 'store'])->name('admin.vouchers.store');
    Route::post('/update', [App\Http\Controllers\Admin\VoucherController::class, 'update'])->name('admin.vouchers.update');
    Route::post('/delete', [App\Http\Controllers\Admin\VoucherController::class, 'delete'])->name('admin.vouchers.delete');
  });

  // Platforms
  Route::prefix('/platforms')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\PlatformController::class, 'index'])->name('admin.platforms');
    Route::post('/store', [App\Http\Controllers\Admin\PlatformController::class, 'store'])->name('admin.platforms.store');
    Route::post('/update', [App\Http\Controllers\Admin\PlatformController::class, 'update'])->name('admin.platforms.update');
    Route::post('/delete', [App\Http\Controllers\Admin\PlatformController::class, 'delete'])->name('admin.platforms.delete');
  });

  // Categories
  Route::prefix('/categories')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.categories');
    Route::post('/store', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.categories.store');
    Route::post('/update', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.categories.update');
    Route::post('/delete', [App\Http\Controllers\Admin\CategoryController::class, 'delete'])->name('admin.categories.delete');
  });

  // Services
  Route::prefix('/services')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\ServiceController::class, 'index'])->name('admin.services');
    Route::post('/store', [App\Http\Controllers\Admin\ServiceController::class, 'store'])->name('admin.services.store');
    Route::get('/edit/{id}', [App\Http\Controllers\Admin\ServiceController::class, 'show'])->name('admin.services.show');
    Route::post('/update', [App\Http\Controllers\Admin\ServiceController::class, 'update'])->name('admin.services.update');
    Route::post('/update-status', [App\Http\Controllers\Admin\ServiceController::class, 'updateStatus'])->name('admin.services.update-status');
    Route::post('/change-category', [App\Http\Controllers\Admin\ServiceController::class, 'changeCategory'])->name('admin.services.change-category');
    Route::post('/delete', [App\Http\Controllers\Admin\ServiceController::class, 'delete'])->name('admin.services.delete');
    // API Provider Forms
    Route::get('/load-forms/{type}', [App\Http\Controllers\Admin\ServiceController::class, 'forms'])->name('admin.providers.forms');
    Route::post('/load-forms/{type}', [App\Http\Controllers\Admin\ServiceController::class, 'forms'])->name('admin.providers.forms');
  });

  // API Providers
  Route::prefix('/providers')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\ProviderController::class, 'index'])->name('admin.providers');
    Route::post('/store', [App\Http\Controllers\Admin\ProviderController::class, 'store'])->name('admin.providers.store');
    Route::post('/update', [App\Http\Controllers\Admin\ProviderController::class, 'update'])->name('admin.providers.update');
    Route::post('/balance-update', [App\Http\Controllers\Admin\ProviderController::class, 'balanceUpdate'])->name('admin.providers.balance-update');
    Route::post('/price-update', [App\Http\Controllers\Admin\ProviderController::class, 'priceUpdate'])->name('admin.providers.price-update');
    Route::post('/delete', [App\Http\Controllers\Admin\ProviderController::class, 'delete'])->name('admin.providers.delete');
    Route::post('/auto-sync', [App\Http\Controllers\Admin\ProviderController::class, 'autoSync'])->name('admin.providers.auto-sync');
    Route::prefix('/import-services')->group(function () {
      Route::get('/', [App\Http\Controllers\Admin\ProviderController::class, 'importServices'])->name('admin.providers.import-services');
      Route::post('/store', [App\Http\Controllers\Admin\ProviderController::class, 'storeServices'])->name('admin.providers.import-services.store');
      Route::post('/bulk-store', [App\Http\Controllers\Admin\ProviderController::class, 'bulkStoreServices'])->name('admin.providers.import-services.bulk-store');
    });
  });

  // Currency Manager
  Route::prefix('/currency-manager')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\CurrencyController::class, 'index'])->name('admin.currency-manager');
    Route::post('/store', [App\Http\Controllers\Admin\CurrencyController::class, 'store'])->name('admin.currency-manager.store');
    Route::post('/update', [App\Http\Controllers\Admin\CurrencyController::class, 'update'])->name('admin.currency-manager.update');
    Route::post('/delete', [App\Http\Controllers\Admin\CurrencyController::class, 'delete'])->name('admin.currency-manager.delete');
  });

  // Orders
  Route::prefix('/orders')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders');
    Route::post('/update', [App\Http\Controllers\Admin\OrderController::class, 'update'])->name('admin.orders.update');
    Route::post('/delete', [App\Http\Controllers\Admin\OrderController::class, 'delete'])->name('admin.orders.delete');
  });
});

// Cron Routes
Route::prefix('/schedule')->group(function () {
  Route::get('/deposit/check', [App\Http\Controllers\Cron\DepositController::class, 'check'])->name('cron.deposit.check');
  Route::match(['post', 'get'], '/deposit/card-callback', [App\Http\Controllers\Cron\DepositController::class, 'cardCallback'])->name('cron.deposit.card-callback');
  Route::get('/deposit/fpayment-callback', [App\Http\Controllers\Cron\DepositController::class, 'fpaymentCallback'])->name('cron.deposit.fpayment-callback');
  Route::get('/deposit/pm-callback', [App\Http\Controllers\Cron\DepositController::class, 'pmCallback'])->name('cron.deposit.pm-callback');

  // Place order to provider
  Route::get('/orders/place-order', [App\Http\Controllers\Cron\OrderController::class, 'placeOrder']);
  Route::get('/orders/update-order', [App\Http\Controllers\Cron\OrderController::class, 'updateOrder']);

  // Sync provider services
  Route::get('/services/sync', [App\Http\Controllers\Cron\ServiceController::class, 'sync']);

  //
  Route::get('/restore-data', [App\Http\Controllers\Cron\OrderController::class, 'restore']);
});

// artisan
Route::get('/arts/init-setup', function () {
  echo 'Init setup...<br />';
  // clear cache
  Artisan::call('cache:clear');
  echo Artisan::output();
  echo '<br />';
  // clear config
  Artisan::call('config:clear');
  echo Artisan::output();
  echo '<br />';
  // clear view
  Artisan::call('view:clear');
  echo Artisan::output();
  echo '<br />';
  // clear route
  Artisan::call('route:clear');
  echo Artisan::output();
  echo '<br />';
  // clear optimize
  Artisan::call('optimize:clear');
  echo Artisan::output();
  echo '<br />';
  // regenrate app key
  Artisan::call('key:generate');
  echo Artisan::output();
  echo '<br />';
  // databases migrate
  Artisan::call('migrate', [
    '--force' => true,
  ]);
  echo Artisan::output();
  echo '<br />';
});

Route::get('/arts/clear-cache', function () {
  Artisan::call('cache:clear');
  echo Artisan::output();
  echo '<br />';
  Artisan::call('config:clear');
  echo Artisan::output();
  echo '<br />';
  Artisan::call('view:clear');
  echo Artisan::output();
  echo '<br />';
  Artisan::call('route:clear');
  echo Artisan::output();
  echo '<br />';
  Artisan::call('optimize:clear');
  echo Artisan::output();
  echo '<br />';
});


// artisan
Route::get('/arts/fix-update', function () {
  echo 'Fixing update...<br />';
  $update = Update::runUpdate();

  if ($update) {
    echo '--- CLEAR CACHE ---';
    Artisan::call('cache:clear');
    echo Artisan::output();
    echo '<br />';
    echo '--- CLEAR CONFIG ---';
    Artisan::call('config:clear');
    echo Artisan::output();
    echo '<br />';
    echo '--- CLEAR VIEW ---';
    Artisan::call('view:clear');
    echo Artisan::output();
    echo '<br />';
    echo '--- CLEAR ROUTE ---';
    Artisan::call('route:clear');
    echo Artisan::output();
    echo 'Update thành công';
  } else {
    echo 'Update thất bại';
  }
});
