<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Register Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles the registration of new users as well as their
  | validation and creation. By default this controller uses a trait to
  | provide this functionality without requiring any additional code.
  |
  */
  use RegistersUsers;

  /**
   * Where to redirect users after registration.
   *
   * @var string
   */
  protected $redirectTo = '/home';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest');
  }

  public function showRegistrationForm()
  {
    return view('auth.register');
  }

  public function register(Request $request)
  {
    $attributes = [
      'email'    => 'Email',
      'username' => 'Tài khoản',
      'password' => 'Mật khẩu',
    ];

    $messages = [
      'email.required'                 => 'Vui lòng nhập :attribute',
      'email.email'                    => ':attribute không đúng định dạng',
      'email.max'                      => ':attribute không được vượt quá :max ký tự',
      'email.unique'                   => ':attribute đã tồn tại',

      'username.required'              => 'Vui lòng nhập :attribute',
      'username.alpha_num'             => ':attribute chỉ chứa ký tự và số',
      'username.min'                   => ':attribute phải có ít nhất :min ký tự',
      'username.max'                   => ':attribute không được vượt quá :max ký tự',
      'username.unique'                => ':attribute đã tồn tại',

      'password.required'              => 'Vui lòng nhập :attribute',
      'password.min'                   => ':attribute phải có ít nhất :min ký tự',
      'password.max'                   => ':attribute không được vượt quá :max ký tự',

      'cf-turnstile-response.required' => 'Vui lòng xác minh bạn không phải là robot.',
    ];

    $validate = Validator::make($request->all(), [
      'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
      'username'              => ['required', 'alpha_num', 'min:6', 'max:16', 'unique:users'],
      'password'              => ['required', 'string', 'min:6', 'max:32'],

      'cf-turnstile-response' => 'nullable|string',
    ], $messages, $attributes);

    if ($validate->fails()) {
      return response()->json([
        'errors'  => $validate->errors(),
        'status'  => 400,
        'message' => $validate->errors()->first(),
      ], 400);
    }

    $data = $validate->validated();


    if (setting('captcha_status') && setting('captcha_siteKey') && setting('captcha_secretKey')) {
      if (!isset($data['cf-turnstile-response'])) {
        return response()->json([
          'status'  => 400,
          'message' => 'Vui lòng xác minh bạn không phải là robot.',
        ], 400);
      }

      $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
        'secret'   => setting('captcha_secretKey'), // secret key của bạn
        'response' => $data['cf-turnstile-response'],
      ]);

      $result = $response->json();

      if (!$result['success']) {
        return response()->json([
          'data'    => $result,
          'status'  => 400,
          'message' => 'Xác minh không thành công, vui lòng thử lại.',
        ], 400);
      }
    }

    if (Cookie::has('ref_id')) {
      $cref = Affiliate::where('code', Cookie::get('ref_id'))->first();
      if ($cref) {
        $refId = $cref->username;
      } else {
        $refId = null;
      }
    } else {
      $refId = null;
    }
    $utm_source = Cookie::get('utm_source', 'WEB');
    $user       = User::create([
      'email'         => $data['email'] ?? time() . '@host.local',
      'language'      => setting('primary_lang', 'vn'),
      'username'      => $data['username'],
      'password'      => Hash::make($data['password']),
      'fullname'      => $data['fullname'] ?? null,
      'ip_address'    => request()->ip(),
      'register_by'   => $utm_source,
      'referral_by'   => $refId ?? null,
      'referral_code' => str()->random(12),
    ]);

    if ($user->id === 1 && User::count() === 1) {
      $user->update([
        'role' => 'admin',
      ]);
    }

    if ($refId) {
      $cref->users()->create([
        'code'             => $cref->code,
        'username'         => $cref->username,
        'to_username'      => $user->username,
        'total_deposit'    => 0,
        'total_commission' => 0,
      ]);
      $cref->update([
        'signups' => $cref->signups + 1
      ]);
    }

    // clear cookie for ref
    Cookie::forget('ref_id');

    $user->update([
      'access_token' => explode('|', $user->createToken('access_token')->plainTextToken)[1],
    ]);

    Auth::login($user);

    return response()->json([
      'data'    => [
        'user_id'  => $user->id,
        'username' => $user->username,
      ],
      'status'  => 201,
      'message' => __t('Tài khoản :username đã được khởi tạo', ['username' => $user->username]),
    ], 201);
  }

}
