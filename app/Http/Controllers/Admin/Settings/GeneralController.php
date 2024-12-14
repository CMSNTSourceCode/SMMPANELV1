<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Notification;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GeneralController extends Controller
{
  public function index(Request $request)
  {
    return view('admin.settings.general');
  }

  public function update(Request $request)
  {
    $type = $request->input('type', null);

    if ($type === 'general') {
      $payload = $request->validate([
        'title'               => 'nullable|string|max:255',
        'favicon'             => 'nullable|file|mimes:png,jpg,jpeg,svg|max:20000',
        'ex_rate'             => 'nullable|numeric',
        'keywords'            => 'nullable|string|max:255',
        'thumbnail'           => 'nullable|file|mimes:png,jpg,jpeg,svg,gif|max:20000',
        'logo_dark'           => 'nullable|file|mimes:png,jpg,jpeg,svg,gif|max:20000',
        'logo_light'          => 'nullable|file|mimes:png,jpg,jpeg,svg,gif|max:20000',
        'auto_refund'         => 'nullable|boolean',
        'avatar_post'         => 'nullable|file|mimes:png,jpg,jpeg,svg,gif|max:20000',
        'avatar_user'         => 'nullable|file|mimes:png,jpg,jpeg,svg,gif|max:20000',
        'description'         => 'nullable|string|max:255',
        'footer_text'         => 'nullable|string',
        'footer_link'         => 'nullable|string',
        'comm_percent'        => 'nullable|numeric',
        'primary_lang'        => 'nullable|string|max:10',
        'color_primary'       => 'nullable|string|max:255',
        'color_primary_hover' => 'nullable|string|max:255',
      ]);

      $config = Config::firstOrCreate(['name' => $type], ['value' => []]);

      if ($request->hasFile('logo_dark')) {
        $payload['logo_dark'] = Helper::uploadFile($request->file('logo_dark'), 'public');
      } else {
        $payload['logo_dark'] = $config->value['logo_dark'] ?? null;
      }

      if ($request->hasFile('logo_light')) {
        $payload['logo_light'] = Helper::uploadFile($request->file('logo_light'), 'public');
      } else {
        $payload['logo_light'] = $config->value['logo_light'] ?? null;
      }

      if ($request->hasFile('favicon')) {
        $payload['favicon'] = Helper::uploadFile($request->file('favicon'), 'public');
      } else {
        $payload['favicon'] = $config->value['favicon'] ?? null;
      }

      if ($request->hasFile('thumbnail')) {
        $payload['thumbnail'] = Helper::uploadFile($request->file('thumbnail'), 'public');
      } else {
        $payload['thumbnail'] = $config->value['thumbnail'] ?? null;
      }

      if ($request->hasFile('avatar_post')) {
        $payload['avatar_post'] = Helper::uploadFile($request->file('avatar_post'), 'public');
      } else {
        $payload['avatar_post'] = $config->value['avatar_post'] ?? null;
      }

      if ($request->hasFile('avatar_user')) {
        $payload['avatar_user'] = Helper::uploadFile($request->file('avatar_user'), 'public');
      } else {
        $payload['avatar_user'] = $config->value['avatar_user'] ?? null;
      }

      $config->update([
        'value' => $payload,
      ]);

      Cache::forget('general_settings_' . domain());

      return redirect()->back()->with('success', 'Cập nhật cài đặt chung thành công.');
    } elseif ($type === 'theme_settings') {
      $payload = $request->validate([
        'auth_bg'         => 'nullable|file|mimes:png,jpg,jpeg,svg|max:20000',
        'ladi_name'       => 'nullable|string',
        'order_form_type' => 'nullable|string|in:default,form_csr',
      ]);

      if ($request->hasFile('auth_bg')) {
        $payload['auth_bg'] = Helper::uploadFile($request->file('auth_bg'), 'public');
      }

      $config = Config::firstOrCreate(['name' => $type], ['value' => []]);

      $config->update([
        'value' => $payload,
      ]);

      // return response()->json([
      //   'status'  => 200,
      //   'message' => __t('Cập nhật cài đặt giao diện thành công.'),
      // ]);
      return redirect()->back()->with('success', __t('Cập nhật cài đặt giao diện thành công.'));
    } elseif ($type === 'deposit_status') {
      $payload = $request->validate([
        'card'          => 'nullable|boolean',
        'bank'          => 'nullable|boolean',
        'paypal'        => 'nullable|boolean',
        'crypto'        => 'nullable|boolean',
        'perfect_money' => 'nullable|boolean',
      ]);

      $config = Config::firstOrCreate(['name' => $type], ['value' => []]);

      $config->update([
        'value' => $payload,
      ]);

      return response()->json([
        'status'  => 200,
        'message' => __t('Cập nhật cài đặt giao diện thành công.'),
      ]);
    } elseif ($type === 'contact_info') {
      $payload = $request->validate([
        'email'    => 'nullable|string',
        'facebook' => 'nullable|string',
        'telegram' => 'nullable|string',
        'phone_no' => 'nullable|string',
      ]);

      $config = Config::firstOrCreate(['name' => $type], ['value' => $payload]);

      $config->update([
        'value' => $payload,
      ]);

      // return redirect()->back()->with('success', 'Cập nhật thông tin liên hệ thành công.');
      return response()->json([
        'status'  => 200,
        'message' => __t('Cập nhật thông tin liên hệ thành công.'),
      ]);
    } elseif ($type === 'deposit_info') {
      $payload = [
        'prefix'     => $_POST['prefix'] ?? 'hello ',
        'discount'   => (int) ($_POST['discount'] ?? 0),
        'min_amount' => (int) ($_POST['min_amount'] ?? 0),
      ];

      $config = Config::firstOrCreate(['name' => $type], ['value' => $payload]);

      $config->update([
        'value' => $payload,
      ]);

      // return redirect()->back()->with('success', 'Cập nhật thông tin nạp tiền thành công.');
      return response()->json([
        'status'  => 200,
        'message' => __t('Cập nhật thông tin nạp tiền thành công'),
      ]);
    } elseif ($type === 'header_script') {
      $payload = $request->validate([
        'code' => 'nullable|string',
      ]);

      $config = Notification::firstOrCreate(['name' => $type], ['value' => $payload['code']]);

      $config->update([
        'value' => $payload['code'],
      ]);

      return redirect()->back()->with('success', 'Cập nhật mã script thành công');
    } elseif ($type === 'footer_script') {
      $payload = $request->validate([
        'code' => 'nullable|string',
      ]);

      $config = Notification::firstOrCreate(['name' => $type], ['value' => $payload['code']]);

      $config->update([
        'value' => $payload['code'],
      ]);

      return redirect()->back()->with('success', 'Cập nhật mã script thành công');
    } elseif ($type === 'rank_discount') {

      $config = Config::firstOrCreate(['name' => $type], ['value' => $request->all()]);

      $config->update([
        'value' => $request->all(),
      ]);

      // return redirect()->back()->with('success', 'Cập nhật % giảm giá theo rank thành công.');

      return response()->json([
        'status'  => 200,
        'message' => __t('Cập nhật % giảm giá theo rank thành công'),
      ]);
    } elseif ($type === 'rank_level') {
      $config = Config::firstOrCreate(['name' => $type], ['value' => $request->all()]);

      $config->update([
        'value' => $request->only([
          'bronze',
          'silver',
          'gold',
          'platinum',
          'diamond',
          'titanium',
          'features',
        ]),
      ]);

      // return redirect()->back()->with('success', 'Cập nhật cấp độ thành công');
      return response()->json([
        'status'  => 200,
        'message' => __t('Cập nhật cấp độ thành công'),
      ]);
    } elseif ($type === 'affiliate_config') {

      $config = Config::firstOrCreate(['name' => $type], ['value' => $request->all()]);

      $config->update([
        'value' => $request->all(),
      ]);

      // return redirect()->back()->with('success', 'Cập nhật cấu hình cộng tác viên thành công');
      return response()->json([
        'status'  => 200,
        'message' => __t('Cập nhật cấu hình cộng tác viên thành công'),
      ]);
    }

  }
}
