<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{

  public function __construct()
  {
    $smtp = Helper::getApiConfig('smtp_server');

    if ($smtp) {
      config([
        'mail.mailers.smtp.host'       => $smtp['host'],
        'mail.mailers.smtp.port'       => $smtp['port'],
        'mail.mailers.smtp.encryption' => 'tls',
        'mail.mailers.smtp.username'   => $smtp['user'],
        'mail.mailers.smtp.password'   => $smtp['pass'],
        'mail.from.address'            => $smtp['user'],
        'mail.from.name'               => $smtp['name'] ?? strtoupper(Helper::getDomain()),
      ]);
    }
  }

  /*
  |--------------------------------------------------------------------------
  | Password Reset Controller
  |--------------------------------------------------------------------------
  |
  | This controller is responsible for handling password reset emails and
  | includes a trait which assists in sending these notifications from
  | your application to your users. Feel free to explore this trait.
  |
  */

  use SendsPasswordResetEmails;
}
