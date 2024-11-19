<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckLastLogin
{
  public function handle(Request $request, Closure $next)
  {
    // if (Auth::check()) {
    //   $user        = Auth::user();
    //   $lastLoginAt = $user->last_login_ip;

    //   if (session('last_login_ip') != $lastLoginAt && $lastLoginAt != null) {
    //     Auth::logout();
    //     return redirect('/login')->with('message', 'Your account was logged in from another location.');
    //   }
    // }

    return $next($request);
  }
}
