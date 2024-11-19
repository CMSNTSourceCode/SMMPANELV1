<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (Auth::check() === false) {
      return redirect()->route('login');
    }

    if (Auth::user()->role !== 'admin') {
      return abort(403);
    }

    if (env('PRJ_DEMO_MODE', false) === true && ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete'))) {
      return response()->json(['status' => 403, 'message' => 'This action is disabled in the demo.'], 403);
      // return redirect()->back()->with('error', 'This action is disabled in the demo.');
    }

    if ($request->routeIs('admin.settings.*') || $request->routeIs('admin.update') || $request->getMethod() === 'post') {

      $check = checkLicenseKey(env('PRJ_CLIENT_KEY'));

      if ($check['status'] !== true) {
        return redirect()->back()->with('error', '[POST][License Error]: ' . $check['msg']);
      }

    }

    return $next($request);

  }
}
