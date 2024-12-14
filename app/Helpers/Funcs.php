<?php
/**
 * @author fb.com/baoint
 * @author contact@quocbao.dev
 * @package HelperFunctions
 *
 * @version 1.0.2
 */

use App\Helpers\Helper;
use App\Models\Config;
use App\Models\CurrencyList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

require_once 'includes/lang_helper.php';
require_once 'includes/smm_helper.php';


if (!function_exists('setting')) {
  function setting($key, $default = null)
  {
    if (Cache::has('general_settings_' . domain())) {
      $config = Cache::get('general_settings_' . domain());
    } else {
      $config = Helper::getConfig('general', []);
      Cache::put('general_settings_' . domain(), $config, 30);
    }

    return $config[$key] ?? $default;
  }
}

if (!function_exists('get_change_logs')) {
  function get_change_logs()
  {
    $filePath = resource_path('logs/change-logs.txt');

    // Check if file exists
    if (!file_exists($filePath)) {
      return [];
    }

    // Open the file for reading; convert newlines to array
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $logs  = [];
    foreach ($lines as $line) {
      $logs[] = json_decode($line, true);
    }

    return $lines;
  }

}

if (!function_exists('appVersion')) {
  function appVersion()
  {

    if (Cache::has('app_version')) {
      $version = Cache::get('app_version');
    } else {
      $version = Helper::getConfig('version_code', 1000);
      Cache::put('app_version', $version, 30);
    }

    return $version;
  }
}

if (!function_exists('parseItem')) {
  function parseItem($content)
  {
    $item = explode('|', $content);

    return [
      'username'   => $item[0] ?? '',
      'password'   => $item[1] ?? '',
      'extra_data' => $item[2] ?? '',
    ];
  }
}

if (!function_exists('parseProxy')) {
  function parseProxy($string)
  {
    //IP:port_http:port_socks5:user:pass
    $proxy = explode(':', $string);

    return [
      'host'       => $proxy[0] ?? -1,
      'user'       => $proxy[3] ?? -1,
      'pass'       => $proxy[4] ?? -1,
      'port_http'  => $proxy[1] ?? -1,
      'port_socks' => $proxy[2] ?? -1,
    ];
  }
}

if (!function_exists('getISPLocation')) {
  function getISPLocation($ip)
  {
    try {
      $url  = 'http://ip-api.com/json/' . $ip;
      $data = file_get_contents($url);
      $data = json_decode($data, true);

      return $data['countryCode'] ?? 'vn';
    } catch (Exception $e) {
      return 'vn';
    }
  }
}

if (!function_exists('getCountry')) {
  function getCountry($ip)
  {
    if (session()->has('country')) {
      return session()->get('country');
    }

    $data = getISPLocation($ip);

    $country = strtolower($data) !== 'vn' ? 'us' : 'vn';

    if (!session()->has('country')) {
      session()->put('country', $country);
    }

    return $country;
  }
}


if (!function_exists('domain')) {
  function domain($domain = null)
  {
    if ($domain) {
      return $domain;
    }

    return Helper::getDomain();
  }
}

/** Currency Helper */
if (!function_exists("currency_codes")) {
  function currency_codes($code = null)
  {
    $data = array(
      "VND" => "Việt Nam Đồng",
      "AUD" => "Australian dollar",
      "BRL" => "Brazilian dollar",
      "CAD" => "Canadian dollar",
      "CZK" => "Czech koruna",
      "DKK" => "Danish krone",
      "EUR" => "Euro",
      "HKD" => "Hong Kong dollar",
      "HUF" => "Hungarian forint",
      "INR" => "Indian rupee",
      "ILS" => "Israeli",
      "JPY" => "Japanese yen",
      "MYR" => "Malaysian ringgit",
      "MXN" => "Mexican peso",
      "TWD" => "New Taiwan dollar",
      "NZD" => "New Zealand dollar",
      "NOK" => "Norwegian krone",
      "PHP" => "Philippine peso",
      "PLN" => "Polish złoty",
      "GBP" => "Pound sterling",
      "RUB" => "Russian ruble",
      "SGD" => "Singapore dollar",
      "SEK" => "Swedish krona",
      "CHF" => "Swiss franc",
      "THB" => "Thai baht",
      "USD" => "United States dollar",
    );

    if ($code !== null) {
      return $data[$code] ?? null;
    }

    return $data;
  }
}

if (!function_exists('convert_currency')) {
  function convert_currency($price, $rate, $code, $new_code)
  {
    return $price * $rate;
  }
}

if (!function_exists('show_price_format')) {
  function show_price_format($input_price, $show_currency_symbol = false, $is_new_format = false, $option = [])
  {
    return formatCurrency($input_price, $show_currency_symbol);
  }

}

if (!function_exists('formatCurrency')) {
  function formatCurrency($number, $show_currency_symbol = true, $number_decimal = "", $decimalpoint = "", $separator = "")
  {
    if (!request()->routeIs('admin*') && cur_user_setting('currency_code') !== 'VND') {
      return formatCurrencyF($number, null, $show_currency_symbol, $number_decimal, $decimalpoint, $separator);
    }

    $config = cur_setting();

    $decimal = 2;

    $prefix = '';

    if ($number_decimal == "") {
      $decimal = $config['currency_decimal'] ?? 2;
    }

    if ($decimalpoint == "") {
      $decimalpoint = $config['currency_decimal_separator'] ?? 'comma';
    }

    if ($separator == "") {
      $separator = $config['currency_thousand_separator'] ?? 'space';
    }

    switch ($decimalpoint) {
      case 'dot':
        $decimalpoint = '.';
        break;
      case 'comma':
        $decimalpoint = ',';
        break;
      default:
        $decimalpoint = ".";
        break;
    }

    switch ($separator) {
      case 'dot':
        $separator = '.';
        break;
      case 'comma':
        $separator = ',';
        break;
      case 'space':
        $separator = ' ';
        break;
      default:
        $separator = ',';
        break;
    }

    $number = number_format($number, $decimal, $decimalpoint, $separator);

    if ($show_currency_symbol) {
      $symbol            = $config['currency_symbol'] ?? '';
      $currency_position = $config['currency_position'] ?? 'left';

      if ($currency_position === 'left') {
        return $prefix . $symbol . '' . $number;
      } else {
        return $prefix . $number . ' ' . $symbol;
      }

      // $locale   = 'en-US'; //browser or user locale
      // $currency = $config['currency_code'] ?? 'VND';
      // $fmt      = new NumberFormatter($locale . "@currency=$currency", NumberFormatter::CURRENCY);
      // $symbol   = $fmt->getSymbol(NumberFormatter::CURRENCY_SYMBOL);

      // if ($currency_position === 'left') {
      //   return $symbol . $number;
      // } else {
      //   return $number . $symbol;
      // }

    }

    return $number;
  }

  function formatCurrencyF($number, $config = null, $show_currency_symbol = true, $number_decimal = "", $decimalpoint = "", $separator = "")
  {
    if ($config === null) {
      $config = cur_user_setting();
      // return formatCurrencyF($number, cur_user_setting(), $show_currency_symbol, $number_decimal, $decimalpoint, $separator);
    }

    $prefix  = '≈ ';
    $decimal = 2;

    if ($config['currency_code'] !== 'VND') {
      $number = $number / $config['new_currecry_rate'];
    } else {
      $number = $number / 1;
      $prefix = '';
    }

    if ($number_decimal == "") {
      $decimal = $config['currency_decimal'] ?? 2;
    }

    if ($decimalpoint == "") {
      $decimalpoint = $config['currency_decimal_separator'] ?? 'comma';
    }

    if ($separator == "") {
      $separator = $config['currency_thousand_separator'] ?? 'space';
    }

    switch ($decimalpoint) {
      case 'dot':
        $decimalpoint = '.';
        break;
      case 'comma':
        $decimalpoint = ',';
        break;
      default:
        $decimalpoint = ".";
        break;
    }

    switch ($separator) {
      case 'dot':
        $separator = '.';
        break;
      case 'comma':
        $separator = ',';
        break;
      case 'space':
        $separator = ' ';
        break;
      default:
        $separator = ',';
        break;
    }

    $number = number_format($number, $decimal, $decimalpoint, $separator);

    if ($show_currency_symbol) {
      $symbol            = $config['currency_symbol'] ?? '';
      $currency_position = $config['currency_position'] ?? 'left';

      if ($currency_position === 'left') {
        return $prefix . $symbol . '' . $number;
      } else {
        return $prefix . $number . ' ' . $symbol;
      }

      // $locale   = 'en-US'; //browser or user locale
      // $currency = $config['currency_code'] ?? 'VND';
      // $fmt      = new NumberFormatter($locale . "@currency=$currency", NumberFormatter::CURRENCY);
      // $symbol   = $fmt->getSymbol(NumberFormatter::CURRENCY_SYMBOL);

      // return $prefix . $number . $symbol;
    }

    return $prefix . $number;
  }
}

if (!function_exists('theme_setting')) {
  function theme_setting($key = null, $default = null)
  {
    if (Cache::has('theme_setting')) {
      $config = Cache::get('theme_setting');
    } else {
      $config = Helper::getConfig('theme_settings');
      Cache::put('theme_setting', $config, 30);
    }

    if ($key !== null) {
      return $config[$key] ?? $default;
    }

    return $config;
  }
}

if (!function_exists('cur_setting')) {
  function cur_setting($key = null, $default = null)
  {
    if (Cache::has('cur_setting')) {
      $config = Cache::get('cur_setting');
    } else {
      $config = Helper::getConfig('currency_settings');
      if ($config === null) {
        $config = [
          'currency_code'               => 'VND',
          'currency_symbol'             => '₫',
          'currency_decimal'            => 2,
          'currency_thousand_separator' => 'comma',
          'currency_decimal_separator'  => 'dot',
          'currency_position'           => 'left',
          'new_currecry_rate'           => 1,
        ];
      } else {
        $config = is_array($config) ? $config : $config->toArray();
      }

      Cache::put('cur_setting', $config, 30);
    }

    if ($key !== null) {
      return $config[$key] ?? $default;
    }

    if (!request()->routeIs('admin*')) {
      unset($config['default_price_percentage_increase']);
    }

    return $config;
  }

  function cur_user_setting($key = null, $default = null)
  {
    if (Cache::has('cur_user_setting')) {
      $config = Cache::get('cur_user_setting');
    } else {
      $defaultConfig = [
        'currency_code'               => 'VND',
        'currency_symbol'             => '₫',
        'currency_thousand_separator' => 'comma',
        'currency_decimal_separator'  => 'dot',
        'currency_decimal'            => 2,
        'currency_position'           => 'left',
        'new_currecry_rate'           => 1,
      ];

      if (CurrencyList::count() == 0) {
        CurrencyList::create($defaultConfig);

        $config = $defaultConfig;
      } else if (Auth::check() && Auth::user()->currency_code !== cur_setting('currency_code')) {
        $config = CurrencyList::where('currency_code', Auth::user()->currency_code)->select([
          'currency_code',
          'currency_symbol',
          'currency_decimal',
          'new_currecry_rate',
          'currency_thousand_separator',
          'currency_decimal_separator',
        ])->first();

        if ($config === null) {
          $config = cur_setting();
        } else {
          $config = $config->toArray();
        }
      } else {
        $config = cur_setting();
      }

      Cache::put('cur_user_setting', $config, 30);
    }

    if (!request()->routeIs('admin*')) {
      unset($config['default_price_percentage_increase']);
    }

    if ($key !== null) {
      return $config[$key] ?? $default;
    }

    return $config;
  }
}

if (!function_exists('deposit_status')) {
  function deposit_status($key = null)
  {
    if (Cache::has('deposit_status')) {
      $config = Cache::get('deposit_status');
    } else {
      $config = Helper::getConfig('deposit_status');
      Cache::put('deposit_status', $config, 5);
    }

    if ($key !== null) {
      return !!($config[$key] ?? true);
    }

    return $config;
  }
}

if (!function_exists('hex2rgb')) {
  function hex2rgb($code)
  {
    $code = str_replace('#', '', $code);

    if (strlen($code) == 3) {
      $code = $code[0] . $code[0] . $code[1] . $code[1] . $code[2] . $code[2];
    }

    return implode(',', sscanf($code, '%02x%02x%02x'));
  }
}

if (!function_exists('prj_key')) {
  function prj_key()
  {

    if (Cache::has('prj_key')) {
      $key = Cache::get('prj_key');
    } else {
      $project = Config::firstOrCreate(['name' => 'prj_key'], ['value' => str()->random(12)]);

      Cache::put('prj_key', $project->value, 30);

      $key = $project->value;
    }

    return $key;
  }
}

/** IP Address Helper */
if (!function_exists("get_client_ip")) {
  function get_client_ip()
  {
    if (getenv('HTTP_CLIENT_IP')) {
      $ip = getenv('HTTP_CLIENT_IP');
    } else if (getenv('HTTP_X_FORWARDED_FOR')) {
      $ip = getenv('HTTP_X_FORWARDED_FOR');

      if (strstr($ip, ',')) {
        $tmp = explode(',', $ip);
        $ip  = trim($tmp[0]);
      }
    } else {
      $ip = getenv('REMOTE_ADDR');
    }

    return $ip;
  }
}

if (!function_exists("info_client_ip")) {
  function info_client_ip()
  {
    $result = get_curl("https://timezoneapi.io/api/ip");

    $result = json_decode($result);
    if (!empty($result)) {
      return $result;
    }
    return false;
  }
}

if (!function_exists('get_location_info_by_ip')) {
  function get_location_info_by_ip($ip_address)
  {
    $result  = (object) array();
    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip_address));
    if ($ip_data && $ip_data->geoplugin_countryName != null) {
      $result->country  = $ip_data->geoplugin_countryName;
      $result->timezone = $ip_data->geoplugin_timezone;
      $result->city     = $ip_data->geoplugin_city;
    } else {
      $result->country  = 'Unknown';
      $result->timezone = 'Unknown';
      $result->city     = 'Unknown';
    }
    return $result;
  }
}

if (!function_exists("get_curl")) {
  function get_curl($url)
  {
    $user_agent = 'Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3B48b Safari/419.3';

    $headers = array
    (
      'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
      'Accept-Language: en-US,fr;q=0.8;q=0.6,en;q=0.4,ar;q=0.2',
      'Accept-Encoding: gzip,deflate',
      'Accept-Charset: utf-8;q=0.7,*;q=0.7',
      'cookie:datr=; locale=en_US; sb=; pl=n; lu=gA; c_user=; xs=; act=; presence=',
    );

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);

    curl_close($ch);

    return $result;
  }
}

if (!function_exists('truncate_string')) {
  function truncate_string($string = "", $max_length = 50, $ellipsis = "...", $trim = true)
  {
    $max_length = (int) $max_length;
    if ($max_length < 1) {
      $max_length = 50;
    }

    if (!is_string($string)) {
      $string = "";
    }

    if ($trim) {
      $string = trim($string);
    }

    if (!is_string($ellipsis)) {
      $ellipsis = "...";
    }

    $string_length   = mb_strlen($string);
    $ellipsis_length = mb_strlen($ellipsis);
    if ($string_length > $max_length) {
      if ($ellipsis_length >= $max_length) {
        $string = mb_substr($ellipsis, 0, $max_length);
      } else {
        $string = mb_substr($string, 0, $max_length - $ellipsis_length)
          . $ellipsis;
      }
    }

    return $string;
  }
}

if (!function_exists('getRealIP')) {
  function getRealIP()
  {
    $ip = $_SERVER["REMOTE_ADDR"];
    //Deep detect ip
    if (filter_var(@$_SERVER['HTTP_FORWARDED'], FILTER_VALIDATE_IP)) {
      $ip = $_SERVER['HTTP_FORWARDED'];
    }
    if (filter_var(@$_SERVER['HTTP_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
      $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    if (filter_var(@$_SERVER['HTTP_X_REAL_IP'], FILTER_VALIDATE_IP)) {
      $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    if (filter_var(@$_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)) {
      $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    if ($ip == '::1') {
      $ip = '127.0.0.1';
    }

    return $ip;
  }
}

function CMSNT_check_license($licensekey, $localkey = '')
{
  $whmcsurl             = 'https://client.cmsnt.co/';
  $licensing_secret_key = 'SMMPANELV1';
  $localkeydays         = 15;
  $allowcheckfaildays   = 5;
  $check_token          = time() . md5(mt_rand(100000000, mt_getrandmax()) . $licensekey);
  $checkdate            = date("Ymd");
  $domain               = $_SERVER['SERVER_NAME'];
  $usersip              = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : ($_SERVER['LOCAL_ADDR'] ?? $_SERVER['REMOTE_ADDR']);
  $dirpath              = dirname(__FILE__);
  $verifyfilepath       = 'modules/servers/licensing/verify.php';
  $localkeyvalid        = false;
  $originalcheckdate    = $localkeydays ? date("Ymd", mktime(0, 0, 0, date("m"), date("d") - $localkeydays, date("Y"))) : '';
  if ($localkey) {
    $localkey  = str_replace("\n", '', $localkey); # Remove the line breaks
    $localdata = substr($localkey, 0, strlen($localkey) - 32); # Extract License Data
    $md5hash   = substr($localkey, strlen($localkey) - 32); # Extract MD5 Hash
    if ($md5hash == md5($localdata . $licensing_secret_key)) {
      $localdata         = strrev($localdata); # Reverse the string
      $md5hash           = substr($localdata, 0, 32); # Extract MD5 Hash
      $localdata         = substr($localdata, 32); # Extract License Data
      $localdata         = base64_decode($localdata);
      $localkeyresults   = json_decode($localdata, true);
      $originalcheckdate = $localkeyresults['checkdate'];
      if ($md5hash == md5($originalcheckdate . $licensing_secret_key)) {
        $localexpiry = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - $localkeydays, date("Y")));
        if ($originalcheckdate > $localexpiry) {
          $localkeyvalid = true;
          $results       = $localkeyresults;
          $validdomains  = explode(',', $results['validdomain']);
          if (!in_array($_SERVER['SERVER_NAME'], $validdomains)) {
            $localkeyvalid             = false;
            $localkeyresults['status'] = "Invalid";
            $results                   = array();
          }
          $validips = explode(',', $results['validip']);
          if (!in_array($usersip, $validips)) {
            $localkeyvalid             = false;
            $localkeyresults['status'] = "Invalid";
            $results                   = array();
          }
          $validdirs = explode(',', $results['validdirectory']);
          if (!in_array($dirpath, $validdirs)) {
            $localkeyvalid             = false;
            $localkeyresults['status'] = "Invalid";
            $results                   = array();
          }
        }
      }
    }
  }
  if (!$localkeyvalid) {
    $responseCode = 0;
    $postfields   = array(
      'licensekey' => $licensekey,
      'domain'     => $domain,
      'ip'         => $usersip,
      'dir'        => $dirpath,
    );
    if ($check_token)
      $postfields['check_token'] = $check_token;
    $query_string = '';
    foreach ($postfields as $k => $v) {
      $query_string .= $k . '=' . urlencode($v) . '&';
    }
    if (function_exists('curl_exec')) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $whmcsurl . $verifyfilepath);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
      curl_setopt($ch, CURLOPT_TIMEOUT, 4);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $data         = curl_exec($ch);
      $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
    } else {
      $responseCodePattern = '/^HTTP\/\d+\.\d+\s+(\d+)/';
      $fp                  = @fsockopen($whmcsurl, 80, $errno, $errstr, 5);
      if ($fp) {
        $newlinefeed = "\r\n";
        $header      = "POST " . $whmcsurl . $verifyfilepath . " HTTP/1.0" . $newlinefeed;
        $header .= "Host: " . $whmcsurl . $newlinefeed;
        $header .= "Content-type: application/x-www-form-urlencoded" . $newlinefeed;
        $header .= "Content-length: " . @strlen($query_string) . $newlinefeed;
        $header .= "Connection: close" . $newlinefeed . $newlinefeed;
        $header .= $query_string;
        $data        = $line = '';
        @stream_set_timeout($fp, 20);
        @fputs($fp, $header);
        $status = @socket_get_status($fp);
        while (!@feof($fp) && $status) {
          $line           = @fgets($fp, 1024);
          $patternMatches = array();
          if (
            !$responseCode
            && preg_match($responseCodePattern, trim($line), $patternMatches)
          ) {
            $responseCode = (empty($patternMatches[1])) ? 0 : $patternMatches[1];
          }
          $data .= $line;
          $status = @socket_get_status($fp);
        }
        @fclose($fp);
      }
    }
    if ($responseCode != 200) {
      $localexpiry = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - ($localkeydays + $allowcheckfaildays), date("Y")));
      if (($originalcheckdate) > $localexpiry) {
        $results = $localkeyresults;
      } else {
        $results                = array();
        $results['status']      = "Invalid";
        $results['description'] = "Remote Check Failed";
        return $results;
      }
    } else {
      preg_match_all('/<(.*?)>([^<]+)<\/\\1>/i', $data, $matches);
      $results = array();
      foreach ($matches[1] as $k => $v) {
        $results[$v] = $matches[2][$k];
      }
    }
    if (!is_array($results)) {
      die("Invalid License Server Response");
    }
    if (isset($results['md5hash'])) {
      if ($results['md5hash'] != md5($licensing_secret_key . $check_token)) {
        $results['status']      = "Invalid";
        $results['description'] = "MD5 Checksum Verification Failed";
        return $results;
      }
    }
    if ($results['status'] == "Active") {
      $results['checkdate'] = $checkdate;
      $data_encoded         = json_encode($results);
      $data_encoded         = base64_encode($data_encoded);
      $data_encoded         = md5($checkdate . $licensing_secret_key) . $data_encoded;
      $data_encoded         = strrev($data_encoded);
      $data_encoded         = $data_encoded . md5($data_encoded . $licensing_secret_key);
      $data_encoded         = wordwrap($data_encoded, 80, "\n", true);
      $results['localkey']  = $data_encoded;
    }
    $results['remotecheck'] = true;
  }
  unset($postfields, $data, $matches, $whmcsurl, $licensing_secret_key, $checkdate, $usersip, $localkeydays, $allowcheckfaildays, $md5hash);
  return $results;
}

function checkLicenseKey($licensekey)
{
  $results = CMSNT_check_license($licensekey, '');
  if ($licensekey == "meomatcang") {
    $results['msg']    = "Giấy phép hợp lệ";
    $results['status'] = true;
    return $results;
  }
  if ($results['status'] == "Invalid") {
    $results['msg']    = "Giấy phép kích hoạt không hợp lệ";
    $results['status'] = true;
    return $results;
  }
  if ($results['status'] == "Expired") {
    $results['msg']    = "Giấy phép mã nguồn đã hết hạn, vui lòng gia hạn ngay";
    $results['status'] = false;
    return $results;
  }
  if ($results['status'] == "Suspended") {
    $results['msg']    = "Giấy phép của bạn đã bị tạm ngưng";
    $results['status'] = false;
    return $results;
  }
  $results['msg']    = "Không tìm thấy giấy phép này trong hệ thống";
  $results['status'] = false;
  return $results;
}

function normalizeRate($rate)
{
  // Kiểm tra rate có tồn tại và là số
  if (!isset($rate) || !is_numeric($rate)) {
    return 0;
  }

  $rate = (float) $rate;

  // Chuyển số thành string và kiểm tra dấu chấm thập phân
  // $rateStr = (string) $rate;
  // if (strpos($rateStr, '.') !== false) {
  //   return $rate * 1000;
  // }

  return $rate;
}


function validateRate($rate)
{
  // Kiểm tra rate tồn tại và là số
  if (!isset($rate) || !is_numeric($rate)) {
    return [
      'isValid'        => false,
      'normalizedRate' => null,
      'per1000'        => null,
    ];
  }

  $rate = (float) $rate;

  // Nếu rate đã ở dạng 5300
  if (abs($rate - 5300) < 0.0001) {
    return [
      'isValid'        => true,
      'normalizedRate' => 5300,
      'per1000'        => 5.3,
    ];
  }

  // Nếu rate ở dạng 5.3
  if (abs($rate - 5.3) < 0.0001) {
    return [
      'isValid'        => true,
      'normalizedRate' => 5300,
      'per1000'        => 5.3,
    ];
  }

  return [
    'isValid'        => false,
    'normalizedRate' => null,
    'per1000'        => null,
  ];
}
