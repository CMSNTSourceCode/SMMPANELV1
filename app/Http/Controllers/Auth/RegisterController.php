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
      // 'ref'      => 'Mã giới thiệu',
      'email'    => 'Email',
      'username' => 'Số điện thoại',
      'password' => 'Mật khẩu',
    ];

    $messages = [
      // 'ref.integer'        => 'Trường mã giới thiệu phải là một số nguyên.',
      // 'ref.exists'         => 'Mã giới thiệu không tồn tại.',
      'phone.required'     => 'Trường số điện thoại là bắt buộc.',
      'phone.string'       => 'Trường số điện thoại phải là một chuỗi.',
      'phone.regex'        => 'Trường số điện thoại không hợp lệ.',
      'phone.min'          => 'Trường số điện thoại không hợp lệ.',
      'phone.unique'       => 'Số điện thoại đã được sử dụng.',

      'email.required'     => 'Trường email là bắt buộc.',
      'email.string'       => 'Trường email phải là một chuỗi.',
      'email.email'        => 'Trường email phải là một địa chỉ email hợp lệ.',
      'email.max'          => 'Trường email không được dài quá 255 ký tự.',
      'email.unique'       => 'Địa chỉ email đã được sử dụng.',
      'username.required'  => 'Trường số điện thoại là bắt buộc.',
      'username.string'    => 'Trường số điện thoại phải là một chuỗi.',
      'username.max'       => 'Trường số điện thoại không được dài quá 255 ký tự.',
      'username.unique'    => 'Trường số điện thoại đã được sử dụng.',
      'password.required'  => 'Trường mật khẩu là bắt buộc.',
      'username.regex'     => 'Trường số điện thoại không hợp lệ.',
      'password.string'    => 'Trường mật khẩu phải là một chuỗi.',
      'password.min'       => 'Mật khẩu phải dài ít nhất 6 ký tự.',
      'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
    ];

    $validate = Validator::make($request->all(), [
      'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
      // 'phone'    => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'unique:users'],
      // 'username' => ['required', 'alpha_num', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:9', 'max:11', 'unique:users'],
      'username' => ['required', 'alpha_num', 'min:6', 'max:12', 'unique:users'],
      'password' => ['required', 'string', 'min:6', 'max:32'],
    ], $messages, $attributes);

    if ($validate->fails()) {
      return response()->json([
        'errors'  => $validate->errors(),
        'status'  => 400,
        'message' => $validate->errors()->first(),
      ], 400);
    }

    $data = $validate->validated();

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
