<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Affiliate;
use App\Models\Category;
use App\Models\ListPlatform;
use App\Models\Order;
use App\Models\Post;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{

  public function __construct()
  {
    // $this->middleware('auth');
  }

  public function index(Request $request)
  {
    if ($request->has('utm_source')) {
      Cookie::queue('utm_source', $request->input('utm_source'), 10080);
    }

    $landing = theme_setting('ladi_name', 'default');

    if ($landing === 'modern') {
      return view('landing.modern.index', [
        'pageTitle' => setting('title'),
      ]);
    } else if ($landing === 'classic') {
      return view('landing.classic.index', [
        'pageTitle' => setting('title'),
      ]);
    } else if ($landing === 'default') {
      return view('landing.default.index', [
        'pageTitle' => setting('title'),
      ]);
    } else {
      return redirect()->route('home');
    }

  }

  /**
   * Show the application dashboard.
   *
   * @return Renderable
   */
  public function dashboard(Request $request)
  {
    if ($request->has('utm_source')) {
      Cookie::queue('utm_source', $request->input('utm_source'), 10080);
    }

    $posts = Post::where('status', true)->limit(6)->get();

    $themes = Helper::getConfig('theme_settings');

    if (isset($themes['order_form_type']) ? $themes['order_form_type'] === 'form_csr' : true) {
      return view('services.index-client', [
        'pageTitle' => __t('Tạo đơn hàng mới'),
      ], compact('posts'));
    } else {
      if (Cache::has('preset_platforms')) {
        $platforms = Cache::get('preset_platforms');
      } else {
        $platforms = ListPlatform::where('status', true)->orderBy('priority', 'desc')->orderBy('id', 'desc')->get();
      }

      if (Cache::has('preset_services')) {
        $services = Cache::get('preset_services');
      } else {
        $services = Service::where('status', true)->orderBy('id', 'desc')->get();
        Cache::put('preset_services', $services, 60);
      }

      if (Cache::has('categories')) {
        $categories = Cache::get('categories');
      } else {
        $categories = Category::where('status', true)->orderBy('priority', 'desc')->orderBy('id', 'desc')->get();
        Cache::put('categories', $categories, 60);
      }

      return view('services.index', [
        'pageTitle' => __t('Tạo đơn hàng mới'),
      ], compact('posts', 'services', 'categories', 'platforms'));
    }

  }

  public function ref($ref = null)
  {

    if (Auth::check()) {
      return redirect()->route('home')->with('error', __t('Bạn đã đăng nhập, không thể nhập mã giới thiệu.'));
    }

    if (is_null($ref)) {
      return redirect()->route('home')->with('error', __t('Mã giới thiệu không tồn tại.'));
    }

    $affiliate = Affiliate::where('code', $ref)->first();

    if (is_null($affiliate)) {
      return redirect()->route('home')->with('error', __t('Mã giới thiệu không tồn tại.'));
    }

    if (Cookie::has('ref_id') && Cookie::get('ref_id') === $affiliate->code) {
      return redirect()->route('home')->with('error', __t('Bạn đã nhập mã giới thiệu này rồi.'));
    }

    // set cookie for ref, expire after 7 days
    Cookie::queue('ref_id', $affiliate->code, 10080);

    // +1 click
    $affiliate->update([
      'clicks' => $affiliate->clicks + 1
    ]);


    return redirect()->route('home')->with('success', __t('Bạn đã nhập mã giới thiệu thành công, hãy đăng ký trong :day ngày.', ['day' => 7]));
  }
}
