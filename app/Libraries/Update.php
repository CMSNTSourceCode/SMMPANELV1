<?php
/**
 * @author baodev@cmsnt.co
 *
 * @version 1.0.1
 */

namespace App\Libraries;

use App\Helpers\Helper;
use Error;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class Update
{
  private static $api_url;
  private static $hash_key;
  private static $base_path;
  private static $client_key;

  public function __construct()
  {
    self::$api_url    = 'https://github.com/CMSNTSourceCode/SMMPANELV1';
    self::$hash_key   = 'https://github.com/CMSNTSourceCode/SMMPANELV1';
    self::$base_path  = base_path('devonly');
    self::$client_key = env('PRJ_CLIENT_KEY', 'meomatcang');
  }


  public static function enableUpdate()
  {
    return env('PRJ_AUTO_UPDATE', false);
  }

  public static function currentVersion()
  {
    $version = Helper::getConfig('version_code', 1000);

    return $version;
  }

  public static function latestVersion()
  {
    try {
      $response = Http::get(self::$api_url, ['route' => 'check-update', 'hash' => self::$hash_key, 'secret' => self::$client_key]);

      if ($response->successful()) {
        $data = $response->json();

        return $data['data']['version_code'];
      }

      return self::currentVersion();
    } catch (\Throwable $th) {
      return self::currentVersion();
    }
  }

  public static function checkUpdate()
  {
    $latestVersion = self::latestVersion();

    if ($latestVersion > self::currentVersion()) {
      return $latestVersion;
    }

    return 0;
  }

  public static function downloadUpdate()
  {
    if (!self::enableUpdate()) {
      return false;
    }

    if (self::checkUpdate() === null) {
      return false;
    }

    if (!is_dir(self::$base_path)) {
      mkdir(self::$base_path);
    }

    $filename = md5(time() . rand(0, 9999)) . '.zip';

    $response = Http::get(self::$api_url, [
      'hash'      => self::$hash_key,
      'route'     => 'download-update',
      'secret'    => self::$client_key,
      'server_ip' => request()->ip(),
    ]);

    if ($response->successful()) {

      $data = $response->json();

      if (isset($data['status']) && $data['status'] === 403) {
        return false;
      }

      $file = self::$base_path . '/' . $filename;

      file_put_contents($file, $response->body());

      return $file;
    }

    return false;
  }

  public static function extractUpdate($file)
  {
    if (!self::enableUpdate()) {
      return false;
    }

    if (!is_file($file)) {
      return false;
    }

    $zip = new \ZipArchive();

    if ($zip->open($file) === true) {
      $zip->extractTo(base_path());
      $zip->close();

      return true;
    }

    return false;
  }

  public static function cleanUpdate()
  {
    if (!self::enableUpdate()) {
      return false;
    }

    if (!is_dir(self::$base_path)) {
      return false;
    }

    $files = glob(self::$base_path . '/*');

    foreach ($files as $file) {
      if (is_file($file)) {
        unlink($file);
      }
    }

    return true;
  }

  public static function runUpdate()
  {
    try {
      // command for update
      $config = \App\Models\Config::where(['name' => 'version_code'])->firstOrNew(['name' => 'version_code']);

      $config->value = self::latestVersion();

      // clear cache
      Artisan::call('cache:clear');
      // clear config
      Artisan::call('config:clear');
      // clear view
      Artisan::call('view:clear');
      // clear route
      Artisan::call('route:clear');
      // clear optimize
      Artisan::call('optimize:clear');
      // regenrate app key
      Artisan::call('key:generate');
      // databases migrate
      Artisan::call('migrate', [
        '--force' => true,
      ]);

      // edit .env file, replace MAIL_MAILER=log to MAIL_MAILER=smtp
      // $env = file_get_contents(base_path('.env'));
      // $env = str_replace('MAIL_MAILER=log', 'MAIL_MAILER=smtp', $env);
      // file_put_contents(base_path('.env'), $env);



      return $config->save();
    } catch (\Exception $th) {
      throw new Error($th->getMessage());
    } finally {
      self::cleanUpdate();
    }
  }
}
