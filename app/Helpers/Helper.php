<?php
/**
 * @author fb.com/baoint
 * @author contact@quocbao.dev
 * @package HelperClasses
 *
 * @version 1.0.3
 */

namespace App\Helpers;

use App\Models\History;
use App\Models\ListDomain;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

use HTMLPurifier as HTMLPurifier;
use HTMLPurifier_Config as HTMLPurifier_Config;
use Illuminate\Support\Facades\Cache;

class Helper
{
  /**
   * Create a new class instance.
   */
  public function __construct()
  {
    //
  }

  // function for laravel models
  public static function getConfig($name, $default = null, $type = 'config')
  {
    switch ($type) {
      case 'config':
        $config = \App\Models\Config::where('name', $name)->first();
        if ($config) {
          return $config->value;
        } else {
          \App\Models\Config::create(['name' => $name, 'value' => $default]);
        }

        return $default;
      case 'api':
        $config = \App\Models\ApiConfig::where('name', $name)->first();
        if ($config) {
          return $config->value;
        } else {
          \App\Models\ApiConfig::create(['name' => $name, 'value' => $default]);
        }

        return $default;
      default:
        return $default;
    }
  }

  public static function setConfig($name, $data = [], $type = 'config')
  {
    switch ($type) {
      case 'config':
        $config = \App\Models\Config::firstOrCreate(['name' => $name], ['value' => $data]);

        return $config->update(['value' => $data]);
      case 'api':
        $config = \App\Models\ApiConfig::firstOrCreate(['name' => $name], ['value' => $data]);

        return $config->update(['value' => $data]);
      default:
        return null;
    }
  }

  public static function getNotice($name, $default = '')
  {
    $notice = \App\Models\Notification::where('name', $name)->first();
    if ($notice) {
      return $notice->value;
    } else {
      \App\Models\Notification::create(['name' => $name, 'value' => $default]);
    }

    return $default;
  }

  public static function getApiConfig($name, $key = null, $default = '')
  {
    $config = \App\Models\ApiConfig::where('name', $name)->first();
    if ($config) {
      if ($key) {
        return $config->value[$key] ?? $config->value;
      }
      return $config->value;
    } else {
      \App\Models\ApiConfig::create(['name' => $name, 'value' => $default]);
    }

    return $default;
  }

  public static function getTotalComm($username, $username1)
  {
    // username la nguoi gioi thieu
    // username 1 la nguoi duoc gioi thieu

    $total = \App\Models\WalletLog::where('username', $username)
      ->where('sys_note', $username1)
      ->where('type', 'commission')->sum('amount');

    return $total;
  }

  public static function addBankLog($data = [])
  {
    try {
      if (\App\Models\BankLog::where('code', $data['code'])->first() === null)
        return \App\Models\BankLog::create($data);

      return false;
    } catch (\Throwable $th) {
      return false;
    }
  }

  public static function addHistory($content, $data = [])
  {
    return History::create([
      'role'       => auth()->user()->role,
      'data'       => $data,
      'content'    => $content,
      'user_id'    => auth()->id(),
      'username'   => auth()->user()->username,
      'ip_address' => request()->ip(),
    ]);
  }

  public static function getDiscountByRank($rank, $amount = null)
  {
    $config = self::getConfig('rank_discount', []);

    $discount = (int) ($config[$rank] ?? 0);

    if ($amount) {
      return $amount - ($amount * $discount) / 100;
    }

    return $discount;
  }

  public static function checkVoucher($code, $user, $type)
  {
    $voucher = \App\Models\Voucher::where('code', $code)->first();

    if (!$voucher) {
      return [
        'error'   => true,
        'message' => 'Mã giảm giá không tồn tại',
      ];
    }

    // start_date
    if ($voucher->start_date->isFuture()) {
      return [
        'error'   => true,
        'message' => 'Mã giảm giá chưa được kích hoạt',
      ];
    }
    // expired_date
    if ($voucher->expire_date->isPast()) {
      return [
        'error'   => true,
        'message' => 'Mã giảm giá đã hết hạn',
      ];
    }
    // username
    if ($voucher->username && $voucher->username !== $user->username) {
      return [
        'error'   => true,
        'message' => 'Mã giảm giá không hợp lệ cho tài khoản này',
      ];
    }
    // type
    if ($voucher->type !== 'all' && $voucher->type !== $type) {
      return [
        'error'   => true,
        'message' => 'Mã giảm giá không hợp lệ cho gói dịch vụ này',
      ];
    }
    //
    $discount = (float) $voucher->value;

    return [
      'error'    => false,
      'discount' => $discount,
    ];
  }
  // function for string
  public static function text2array($string)
  {
    $array = explode("\n", $string);
    $array = array_filter($array, function ($value) {
      return $value !== '';
    });

    return $array;
  }

  public static function validStatusCode($statusCode)
  {
    $statusCode = intval($statusCode);

    if ($statusCode < 100 || $statusCode > 599) {
      $statusCode = 422;
    }

    return $statusCode;
  }

  public static function getRankByDeposit($amount, $rank)
  {
    $config  = self::getConfig('rank_level', []);
    $newRank = $rank;


    try {
      if (isset($config['silver']) && $config['silver'] !== 0 && $amount >= $config['silver']) {
        $newRank = 'silver';
      } elseif (isset($config['gold']) && $amount >= $config['gold']) {
        $newRank = 'gold';
      } elseif (isset($config['bronze']) && $config['bronze'] !== 0 && $amount >= $config['bronze']) {
        $newRank = 'bronze';
      } elseif (isset($config['diamond']) && $config['diamond'] !== 0 && $amount >= $config['diamond']) {
        $newRank = 'diamond';
      } elseif (isset($config['platinum']) && $config['platinum'] !== 0 && $amount >= $config['platinum']) {
        $newRank = 'platinum';
      } elseif (isset($config['titanium']) && $config['titanium'] !== 0 && $amount >= $config['titanium']) {
        $newRank = 'titanium';
      }
    } catch (\Throwable $th) {
      return $rank;
    }

    return $newRank;
  }

  public static function formatRank($rank)
  {
    return ucfirst($rank);
  }

  public static function getUserRankName($rank)
  {
    $ranks = [
      'bronze'   => 'Đồng',
      'silver'   => 'Bạc',
      'gold'     => 'Vàng',
      'platinum' => 'Bạch Kim',
      'diamond'  => 'Kim Cương',
    ];

    return $ranks[$rank] ?? $rank;
  }

  public static function formatStatus($status, $type = 'html')
  {
    switch (strtolower($status)) {
      case 'paid':
        return $type == 'html' ? '<span class="badge bg-success">Đã thanh toán</span>' : 'Đã thanh toán';
      case 'unpaid':
        return $type == 'html' ? '<span class="badge bg-danger">Chưa thanh toán</span>' : 'Chưa thanh toán';
      case 'pending':
        return $type == 'html' ? '<span class="badge bg-warning">Chờ xử lý</span>' : 'Chờ xử lý';
      case 'processing':
        return $type == 'html' ? '<span class="badge bg-primary">Đang xử lý</span>' : 'Đang xử lý';
      case 'completed':
        return $type == 'html' ? '<span class="badge bg-success">Hoàn thành</span>' : 'Hoàn thành';
      case 'cancelled':
        return $type == 'html' ? '<span class="badge bg-danger">Đã bị hủy</span>' : 'Đã bị hủy';
      case 'active':
        return $type == 'html' ? '<span class="badge bg-success">Đang hoạt động</span>' : 'Đang hoạt động';
      case 'inactive':
        return $type == 'html' ? '<span class="badge bg-danger">Đã khóa</span>' : 'Đã khóa';
      case 'expired':
        return $type == 'html' ? '<span class="badge bg-danger">Đã hết hạn</span>' : 'Đã hết hạn';
      case 'error':
        return $type == 'html' ? '<span class="badge bg-danger">Không hợp lệ</span>' : 'Không hợp lệ';
      case 'refund':
        return $type == 'html' ? '<span class="badge bg-danger">Đã hoàn tiền</span>' : 'Đã hoàn tiền';
      default:
        return $type == 'html' ? '<span class="badge bg-secondary">' . $status . '</span>' : $status;
    }
  }

  public static function formatOrderStatus($status, $type = 'html')
  {
    switch (strtolower($status)) {
      case 'running':
        return $type == 'html' ? '<span class="badge bg-info">' . __t('Đang chạy') . '</span>' : __t('Đang chạy');
      case 'pending':
        return $type == 'html' ? '<span class="badge bg-warning">' . __t('Đang chờ') . '</span>' : __t('Đang chờ');
      case 'processing':
        return $type == 'html' ? '<span class="badge bg-primary">' . __t('Đang hoạt động') . '</span>' : __t('Đang hoạt động');
      case 'completed':
        return $type == 'html' ? '<span class="badge bg-success">' . __t('Hoàn thành') . '</span>' : __t('Hoàn thành');
      case 'cancelled':
        return $type == 'html' ? '<span class="badge bg-danger">' . __t('Đã huỷ') . '</span>' : __t('Đã huỷ');
      case 'error':
        return $type == 'html' ? '<span class="badge bg-danger">' . __t('Đơn lỗi') . '</span>' : __t('Đơn lỗi');
      case 'refund':
        return $type == 'html' ? '<span class="badge bg-danger">' . __t('Hoàn tiền') . '</span>' : __t('Hoàn tiền');
      case 'partial':
        return $type == 'html' ? '<span class="badge bg-warning">' . __t('Hoàn một phần') . '</span>' : __t('Hoàn một phần');
      default:
        return $type == 'html' ? '<span class="badge bg-secondary">' . $status . '</span>' : $status;
    }
  }

  public static function formatPrice($price, $currency = 'đ')
  {
    return number_format($price, 0, ',', '.') . ' ' . $currency;
  }

  public static function formatNumber($number)
  {
    return number_format($number, 0, ',', '.');
  }

  public static function formatTime($time, $format = 'd/m/Y H:i:s')
  {
    return date($format, strtotime($time));
  }

  public static function formatDate($time, $format = 'd/m/Y')
  {
    return date($format, strtotime($time));
  }

  public static function formatTimeAgo($time)
  {
    $time = strtotime($time);
    $diff = time() - $time;

    if ($diff < 60) {
      // if zero
      if ($diff < 0) {
        return 'vừa xong';
      } else {
        return $diff . ' giây trước';
      }
    }
    $diff = round($diff / 60);
    if ($diff < 60) {
      return $diff . ' phút trước';
    }
    $diff = round($diff / 60);
    if ($diff < 24) {
      return $diff . ' giờ trước';
    }
    $diff = round($diff / 24);
    if ($diff < 7) {
      return $diff . ' ngày trước';
    }
    $diff = round($diff / 7);
    if ($diff < 4) {
      return $diff . ' tuần trước';
    }

    return date('d/m/Y H:i:s', $time);
  }

  public static function formatTransType($type)
  {
    switch (strtolower($type)) {
      case 'deposit':
        return 'Nạp tiền';
      default:
        return strtoupper($type);
    }
  }

  public static function vnToStr($str)
  {

    $unicode = array(

      'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

      'd' => 'đ',

      'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

      'i' => 'í|ì|ỉ|ĩ|ị',

      'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

      'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

      'y' => 'ý|ỳ|ỷ|ỹ|ỵ',

      'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

      'D' => 'Đ',

      'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

      'I' => 'Í|Ì|Ỉ|Ĩ|Ị',

      'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

      'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

      'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',

    );

    foreach ($unicode as $nonUnicode => $uni) {

      $str = preg_replace("/($uni)/i", $nonUnicode, $str);

    }
    $str = str_replace(' ', '_', $str);

    return $str;

  }

  public static function getCountryName($code, $lng = 'en')
  {
    return \Locale::getDisplayRegion('-' . strtoupper($code), $lng);
  }

  public static function randomString($length = 10, $uppercase = false)
  {
    $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $uppercase ? strtoupper($randomString) : $randomString;
  }

  public static function randomNumber($length = 10)
  {
    $characters       = '0123456789';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
  }

  public static function parseOrderId($string, $prefix)
  {
    $re = '/' . $prefix . '\w+/im';
    preg_match_all($re, $string, $matches, PREG_SET_ORDER, 0);
    if (count($matches) == 0) {
      return null;
    }

    // Print the entire match result
    $orderCode    = $matches[0][0];
    $prefixLength = strlen($prefix);
    $orderId      = intval(substr($orderCode, $prefixLength));

    return $orderId;
  }

  public static function parseOrderName($string, $prefix)
  {
    $re = '/' . $prefix . '\w+/im';
    preg_match_all($re, $string, $matches, PREG_SET_ORDER, 0);
    if (count($matches) == 0) {
      return null;
    }

    // Print the entire match result
    $orderCode    = $matches[0][0];
    $prefixLength = strlen($prefix);
    $orderId      = substr($orderCode, $prefixLength);

    return $orderId;
  }

  public static function hideUsername($string, $length = 3)
  {
    $string = substr($string, 0, $length) . str_repeat('*', strlen($string) - $length);

    return $string;
  }

  public static function hideEmail($string, $length = 3)
  {
    $email = explode('@', $string);
    $email = substr($email[0], 0, $length) . str_repeat('*', strlen($email[0]) - $length) . '@' . $email[1];

    return $email;
  }

  public static function htmlPurifier($dirty_html)
  {
    $config   = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    // Cho phép sử dụng các đường dẫn ảnh dạng data URI (base64)
    $config->set('URI.AllowedSchemes', ['data' => true, 'http' => true, 'https' => true]);

    // Khởi tạo đối tượng HTMLPurifier với cấu hình đã tạo
    $purifier = new HTMLPurifier($config);

    // Sử dụng HTMLPurifier để làm sạch mã HTML
    $clean_html = $purifier->purify($dirty_html);

    return $clean_html;
  }

  // function for datetime
  public static function getRemainingHours($end, $format = '%h giờ')
  {
    $end = !strtotime($end) ? date('Y-m-d H:i:s', $end) : $end;

    $startDate = new \DateTime();
    $endDate   = new \DateTime($end);

    if ($startDate > $endDate) {
      return sprintf($format, 0, 0, 0);
    }

    $diff    = $endDate->diff($startDate);
    $days    = $diff->days;
    $hours   = $diff->h;
    $minutes = $diff->i;
    $seconds = $diff->s;

    $totalSeconds = $days * 86400 + $hours * 3600 + $minutes * 60 + $seconds;
    $diffDays     = floor($totalSeconds / 86400);
    $diffHours    = floor(($totalSeconds - $diffDays * 86400) / 3600);
    $diffMinutes  = floor(($totalSeconds - $diffDays * 86400 - $diffHours * 3600) / 60);

    return str_replace(['%d', '%h', '%m', '%s'], [$diffDays, $diffHours, $diffMinutes, $seconds], $format);
  }

  public static function getRemainingDays($end, $format = '%dd %hh')
  {
    $end = !strtotime($end) ? date('Y-m-d H:i:s', $end) : $end;

    $startDate = new \DateTime();
    $endDate   = new \DateTime($end);

    if ($startDate > $endDate) {
      return sprintf($format, 0, 0, 0);
    }

    $diff    = $endDate->diff($startDate);
    $days    = $diff->days;
    $hours   = $diff->h;
    $minutes = $diff->i;
    $seconds = $diff->s;

    $totalSeconds = $days * 86400 + $hours * 3600 + $minutes * 60 + $seconds;
    $diffDays     = floor($totalSeconds / 86400);
    $diffHours    = floor(($totalSeconds - $diffDays * 86400) / 3600);
    $diffMinutes  = floor(($totalSeconds - $diffDays * 86400 - $diffHours * 3600) / 60);

    return sprintf($format, $diffDays, $diffHours, $diffMinutes);
  }

  public static function getTimeAgo($timestamp)
  {
    $time = strtotime($timestamp) ? strtotime($timestamp) : $timestamp;
    // $time  = time() - $time_ago;

    $time_difference = time() - $time;

    if ($time_difference < 1) {
      return 'vài giây trước';
    }
    $condition = [
      12 * 30 * 24 * 60 * 60 => 'năm',
      30 * 24 * 60 * 60 => 'tháng',
      24 * 60 * 60 => 'ngày',
      60 * 60 => 'giờ',
      60                     => 'phút',
      1                      => 'giây',
    ];

    foreach ($condition as $secs => $str) {
      $d = $time_difference / $secs;

      if ($d >= 1) {
        $t = round($d);

        return $t . ' ' . $str . ' trước';
      }
    }
  }

  // function convert timezone to new timezone
  public static function convertTimezone($time, $from = 'UTC', $timezone = 'Asia/Ho_Chi_Minh')
  {
    $date = new \DateTime($time, new \DateTimeZone($from));
    $date->setTimezone(new \DateTimeZone($timezone));

    return $date->format('Y-m-d H:i:s');
  }

  // function convert number to currency
  public static function formatCurrency($number, $currency = 'VND')
  {
    $currency = strtoupper($currency);
    switch ($currency) {
      case 'VND':
        return number_format($number, 0, '.', ',') . ' ₫';
      case 'USD':
        return '$' . number_format($number, 2, '.', ',');
      default:
        return number_format($number, 0, ',', '.') . ' ₫';
    }
  }

  // function for server
  public static function getDomain()
  {
    return $_SERVER['HTTP_HOST'] ?? '';
  }

  public static function getHostname()
  {
    return $_SERVER['HTTP_HOST'] ?? '';
  }

  public static function getIp()
  {
    $ip = request()->ip();

    if (request()->header('CF-Connecting-IP')) {
      $ip = request()->header('CF-Connecting-IP');
    }

    return $ip;
  }

  public static function getBrowser()
  {
    return request()->header('User-Agent');
  }

  // function for http request
  public static function curlGet($url)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
  }

  public static function curlPost($url, $data = [])
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close($ch);

    return $server_output;
  }


  public static function sendMessageTelegramAuto($message)
  {
    $config = self::getApiConfig('telegram');

    if (!isset($config['status']) || !isset($config['chat_id']) || !isset($config['bot_token'])) {
      return false;
    }

    if ($config['status'] === false) {
      return false;
    }

    return self::sendMessageTelegram($message, $config['chat_id'], $config['bot_token']);
  }

  public static function sendMessageTelegram($message, $chat_id, $token)
  {
    $url     = 'https://api.telegram.org/bot' . $token . '/sendMessage';
    $data    = [
      'chat_id' => $chat_id,
      'text'    => $message,
    ];
    $options = [
      'http' => [
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'method'  => 'POST',
        'content' => http_build_query($data),
      ],
    ];
    $context = stream_context_create($options);
    $result  = file_get_contents($url, false, $context);
    if ($result === false) {
      return false;
    } else {
      $json = json_decode($result);
      if ($json->ok) {
        return true;
      } else {
        return false;
      }
    }
  }

  public static function getListBank($code = null)
  {
    try {
      if (Cache::has('list_bank')) {
        $data = Cache::get('list_bank');
      } else {
        $response = Http::get('https://api.vieqr.com/list-banks/');

        if ($response->failed()) {
          return [];
        }

        $result = $response->json();

        if (isset($result['code']) && $result['code'] != '00') {
          return [];
        }

        $data = collect($result['data']);

        Cache::put('list_bank', $data, 60 * 5);
      }

      if ($code)
        return $data->where('code', $code)->first();

      return $data;
    } catch (\Throwable $th) {
      return [];
    }
  }

  // function for upload
  public static function uploadFile($file, $provider = 'public', $path = null, $name = null)
  {
    switch ($provider) {
      case 'imgur':
        return self::uploadImgur($file->getContent());
      case 'public':
        return self::uploadPublic($file, $path, $name);
      default:
        return null;
    }
  }

  public static function uploadPublic($file, $path = null, $name = null)
  {
    if ($file->isValid()) {
      // Store the image
      $fileExt  = $file->extension();
      $filePath = 'uploads/' . date('d-m-Y');
      $fileName = ($name !== null ? $name : str()->uuid()) . '.' . $fileExt;

      if ($path) {
        $filePath = $filePath . '/' . $path;
      }

      $file->move($filePath, $fileName);

      return '/' . ($filePath . '/' . $fileName);
    }

    return null;
  }

  public static function uploadImgur($file)
  {
    $client_id     = '86e171e4f20f914';
    $client_secret = 'cd9540ff7140fe4210350816a44db7b4ab95fd95';

    $result = Http::withHeaders([
      'Authorization' => 'Client-ID ' . $client_id,
    ])
      ->post('https://api.imgur.com/3/image', ['image' => base64_encode($file)])
      ->json();

    if ($result['success'] === true) {

      return $result['data']['link'];
    }

    return null;
  }

  // function send mail
  public static function sendMail($data)
  {
    $to          = $data['to'] ?? '';
    $subject     = $data['subject'] ?? '';
    $body        = $data['body'] ?? $data['content'] ?? '';
    $from        = $data['from'] ?? '';
    $fromName    = $data['fromName'] ?? '';
    $cc          = $data['cc'] ?? null;
    $bcc         = $data['bcc'] ?? '';
    $replyTo     = $data['replyTo'] ?? '';
    $replyToName = $data['replyToName'] ?? '';
    $attachments = $data['attachments'] ?? [];
    $headers     = $data['headers'] ?? [];

    try {
      self::sendMailNow($to, $subject, $body, $from, $fromName, $cc, $bcc, $replyTo, $replyToName, $attachments, $headers);

      return true;
    } catch (\Throwable $th) {
      // throw $th;
      return false;
    }
  }

  private static function sendMailNow($to, $subject, $body, $from = null, $fromName = null, $cc = null, $bcc = null, $replyTo = null, $replyToName = null, $attachments = null, $headers = null)
  {

    $smtp = self::getApiConfig('smtp_server');

    if ($smtp) {
      config([
        'mail.mailers.smtp.host'       => $smtp['host'],
        'mail.mailers.smtp.port'       => $smtp['port'],
        'mail.mailers.smtp.encryption' => 'tls',
        'mail.mailers.smtp.username'   => $smtp['user'],
        'mail.mailers.smtp.password'   => $smtp['pass'],
        'mail.from.address'            => $smtp['user'],
        'mail.from.name'               => $smtp['name'] ?? strtoupper(self::getDomain()),
      ]);
    }

    return Mail::send([], [], function ($message) use ($to, $subject, $body, $from, $fromName, $cc, $bcc, $replyTo, $replyToName, $attachments, $headers) {
      $message->to($to);
      $message->subject($subject);
      $message->html($body);
      // $message->setContent($body);
      // $message->text(strip_tags($body));

      if ($from) {
        $message->from($from, $fromName);
      }
      if ($cc) {
        $message->cc($cc);
      }
      if ($bcc) {
        $message->bcc($bcc);
      }
      if ($replyTo) {
        $message->replyTo($replyTo, $replyToName);
      }
      if ($attachments) {
        foreach ($attachments as $attachment) {
          $message->attach($attachment);
        }
      }
      if ($headers) {
        foreach ($headers as $key => $value) {
          $message->getHeaders()->addTextHeader($key, $value);
        }
      }
    });
  }

  // External Function
  public static function getDomainFromLink($link)
  {
    $url    = parse_url($link);
    $domain = $url['host'] ?? '';
    $domain = str_replace('www.', '', $domain);
    return $domain;
  }

  public static function getCountryFlag($code)
  {
    if (strtolower($code) == 'uk') {
      $code = 'gb';
    }

    return 'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.3/flags/4x3/' . strtolower($code) . '.svg';
  }

  public static function CheckDomain($domain = null)
  {
    $domain = $domain ?? self::getDomain();

    $domainInfo = ListDomain::where('domain', $domain)->first();

    if ($domainInfo) {
      return $domainInfo;
    }

    return null;
  }

  public static function getPriceConfig($name, $key = null, $default = '')
  {
    $config = \App\Models\ListPrice::where('name', $name)->first();
    if ($config) {
      if ($key) {
        return $config->value[$key] ?? $config->value;
      }
      return $config->value;
    } else {
      \App\Models\ListPrice::create(['name' => $name, 'value' => $default]);
    }

    return $default;
  }

  public static function getCurrencySetting($key, $default = null)
  {
    $config = self::getConfig('currency_settings');

    if ($config) {
      return $config->value[$key] ?? $config->value;
    }

    return $default;
  }
}
