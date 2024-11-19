<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */

  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
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
    $this->middleware('guest')->except('logout');
  }

  public function showLoginForm()
  {
    return view('auth.login');
  }

  public function login(Request $request)
  {
    $attributes = [
      'username' => __t('Tên tài khoản'),
      'password' => __t('Mật khẩu'),
    ];
    $messages   = [
      'required' => __t(':attribute không được để trống'),
      'string'   => __t(':attribute phải là chuỗi'),
    ];
    $this->validate($request, [
      'username' => 'required|string',
      'password' => 'required',
      'remember' => 'nullable',
    ], $messages, $attributes);

    $remember = true;//$request->remember === 'on' ? true : false;

    $phase1 = Auth::attempt(['username' => $request->username, 'password' => $request->password], $remember);
    $phase2 = false;
    $phase3 = false;

    if (!$phase1) {
      $phase2 = Auth::attempt(['email' => $request->username, 'password' => $request->password], $remember);
    }

    // if (!$phase2) {
    //   $phase3 = Auth::attempt(['phone' => $request->username, 'password' => $request->password], $remember);
    // }

    if ($phase1 || $phase2 || $phase3) {
      Cookie::forget('ref_id');

      $user = User::find(Auth::id());

      if ($user->status !== 'active') {
        Auth::logout();
        // return redirect()->back()->withInput($request->only('username', 'remember'))->withErrors([
        //   'username' => __t('Tài khoản của bạn đã bị khóa')
        // ]);

        return response()->json([
          'status'  => 401,
          'message' => __t('Tài khoản của bạn đã bị khóa'),
        ], 401);
      }

      if (!$user->access_token) {
        $user->update([
          'access_token' => explode('|', $user->createToken('access_token')->plainTextToken)[1],
        ]);
      }

      $user->update([
        'last_login_ip' => $request->ip(),
        'last_login_at' => now(),
      ]);
      session(['last_login_at' => $user->last_login_at]);
      session(['last_login_ip' => $user->last_login_ip]);

      $user->histories()->create([
        'role'       => 'user',
        'data'       => [],
        'content'    => __t('Đăng nhập thành công; Số dư hiện tại :balance', ['balance' => formatCurrency($user->balance)]),
        'user_id'    => $user->id,
        'username'   => $user->username,
        'ip_address' => $request->ip(),
        'domain'     => Helper::getDomain(),
      ]);

      $this->updateRank($user->id);

      // if successful, then redirect to their intended location
      // return redirect()->intended(route('home'));

      return response()->json([
        'status'  => 200,
        'message' => __t('Đăng nhập thành công, đang chuyển hướng...'),
        'data'    => [
          'user'         => $user,
          'access_token' => $user->access_token,
          'redirect'     => redirect()->intended(route('home'))->getTargetUrl(),
        ],
      ], 200);
    }

    // if unsuccessful, then redirect back to the login with the form data
    // return redirect()->back()->withInput($request->only('username', 'remember'))->withErrors([
    //   'username' => __t('Thông tin đăng nhập không chính xác')
    // ]);

    return response()->json([
      'status'  => 400,
      'message' => __t('Thông tin đăng nhập không chính xác'),
    ], 400);
  }

  private function updateRank($id)
  {
    $user = User::find($id);

    if ($user === null) {
      return null;
    }

    $newRank = Helper::getRankByDeposit($user->total_deposit, $user->rank);

    if ($newRank === $user->rank) {
      return null;
    }

    return $user->update([
      'rank' => $newRank,
    ]);
  }

  public function logout(Request $request)
  {
    Auth::logout();

    return redirect()->route('login');
  }
}
