<?php
//

use App\Helpers\Helper;

require_once 'lang_helper.php';

if (!function_exists('apis_list')) {
  function apis_list($type = "")
  {
    $apis = array(
      'standard'    => "Standard (JAP, Perfectpanel, Smartpanel)",
      'indusrabbit' => "Type 2 (indusrabbit, Indiansmartpanel)",
      'yoyomedia'   => "Type 3 (Yoyomedia)",
      'instasmm'    => "Type 4 (Instasmm)",
      'realfans'    => "Type 5 (realfans)",
    );
    return $apis;
  }
}

if (!function_exists('all_services_type')) {
  function all_services_type()
  {
    $all_services_type = array(
      'default'                 => lang('Default'),
      'subscriptions'           => lang('Subscriptions'),
      'custom_comments'         => lang('custom_comments'),
      'custom_comments_package' => lang('custom_comments_package'),
      'mentions_with_hashtags'  => lang('mentions_with_hashtags'),
      'mentions_custom_list'    => lang('mentions_custom_list'),
      'mentions_hashtag'        => lang('mentions_hashtag'),
      'mentions_user_followers' => lang('mentions_user_followers'),
      'mentions_media_likers'   => lang('mentions_media_likers'),
      'package'                 => lang('package'),
      'comment_likes'           => lang('comment_likes'),
    );

    return $all_services_type;
  }
}

if (!function_exists('api_connect')) {
  function api_connect($url, $post = array(""))
  {
    $_post = array();
    if (is_array($post)) {
      foreach ($post as $name => $value) {
        $_post[] = $name . '=' . urlencode($value);
      }
    }
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if (is_array($post)) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
    }
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    $result = curl_exec($ch);
    if (curl_errno($ch) != 0 && empty($result)) {
      $result = false;
    }
    curl_close($ch);
    return $result;
  }
}

/*----------  hide_api_key  ----------*/
if (!function_exists('hide_api_key')) {
  function hide_api_key($api_key = '')
  {
    if ($api_key) {
      $len     = strlen($api_key);
      $api_key = substr($api_key, 0, 10) . str_repeat('*', $len - 20) . substr($api_key, $len - 10, 10);
    }
    return $api_key;
  }
}

/**
 * From Ver3.6
 * @param input $rate
 * @param input $price_percentage_increase
 * @param input $rate_in_new_currency
 * @return new Rate
 * @author Joe Nguyen
 */
if (!function_exists('import_new_rate')) {
  function import_new_rate($rate, $price_percentage_increase = '', $rate_in_new_currency = '')
  {
    $config = Helper::getConfig('currency_settings');

    $price_percentage_increase = ($price_percentage_increase) ? $price_percentage_increase : ($config['default_price_percentage_increase'] ?? 30);
    $rate_in_new_currency      = ($rate_in_new_currency) ? $rate_in_new_currency : 1;
    $decimal_places            = $config['auto_rounding_x_decimal_places'] ?? 2;
    $new_rate                  = round($rate + (($rate * $price_percentage_increase) / 100), $decimal_places);
    if ($new_rate <= $rate) {
      $new_rate = round($rate + (($rate * $price_percentage_increase) / 100), 6);
    }
    $new_rate = $new_rate * $rate_in_new_currency;
    return $new_rate;
  }
}

/**
 * From Ver3.6
 * @param input $type
 * @return str new format Service Type like: default, comments, custom_comments etc
 * @author Joe Nguyen
 */
if (!function_exists('service_type_format')) {
  function service_type_format($type)
  {
    $type = strtolower(str_replace(" ", "_", $type));
    return $type;
  }
}

/**
 * From Ver3.6
 * @param array $order data formal_charge, profit, charge, quantity,
 * @param array $params data order $status, $remain
 * @return array real_charge, refund_money, formal_chagre, profit
 * @author Joe Nguyen
 */
if (!function_exists('calculate_order_by_status')) {
  function calculate_order_by_status($data_order = [], $params = [])
  {
    $result  = [];
    $remains = $params['remains'];

    $charge_back = $real_charge = $formal_charge = $profit = 0;
    if ($params['status'] == 'partial') {
      $real_charge   = $data_order['charge'] * (1 - ((int) $remains / (int) $data_order['quantity']));
      $formal_charge = $data_order['formal_charge'] * (1 - ((int) $remains / (int) $data_order['quantity']));
      $profit        = $data_order['profit'] * (1 - ((int) $remains / (int) $data_order['quantity']));
    }
    $refund_money = $data_order['charge'] - $real_charge;
    $result       = [
      'real_charge'   => $real_charge,
      'profit'        => $profit,
      'formal_chagre' => $formal_charge,
      'refund_money'  => $refund_money,
    ];
    return $result;
  }
}

/**
 * From Ver3.6
 * @param string $input_status order status
 * @return string $status new status format
 * @author Joe Nguyen
 */
if (!function_exists('order_status_format')) {
  function order_status_format($input_status, $task = 'default')
  {
    $status = $input_status;
    switch ($task) {
      case 'subscriptions':
        if ($status == "Completed") {
          $status = strtolower($status);
        }
        if ($status == "Canceled") {
          $status = 'canceled';
        }
        break;

      default:
        if (!in_array($status, array('Completed', 'Processing', 'In progress', 'Partial', 'Canceled', 'Refunded', 'Completed'))) {
          $status = 'Pending';
        }
        if ($status == 'In progress') {
          $status = 'Inprogress';
        }
        break;
    }
    return strtolower($status);
  }
}

/**
 * From Ver3.6
 * @param string $input_remains
 * @return string $remains
 * @author Joe Nguyen
 */
if (!function_exists('order_remains_format')) {
  function order_remains_format($input_remains)
  {
    $remains = $input_remains;
    if ($remains < 0) {
      $remains = abs($remains);
      $remains = "+" . $remains;
    }
    return $remains;
  }
}

if (!function_exists("order_status_array")) {
  function order_status_array()
  {
    $data = array('pending', 'processing', 'inprogress', 'completed', 'partial', 'canceled', 'refunded', 'awaiting', 'error');
    return $data;
  }
}

if (!function_exists("order_subscriptions_status_array")) {
  function order_subscriptions_status_array()
  {
    $data = array('Active', 'Paused', 'Completed', 'Expired', 'Canceled');
    return $data;
  }
}

if (!function_exists("order_dripfeed_status_array")) {
  function order_dripfeed_status_array()
  {
    $data = array('inprogress', 'completed', 'canceled');
    return $data;
  }
}


if (!function_exists("order_status_title")) {
  function order_status_title($key)
  {
    switch ($key) {
      case 'completed':
        return lang("Completed");
      case 'processing':
        return lang("Processing");
      case 'inprogress':
        return lang("In_progress");
      case 'pending':
        return lang('Pending');
      case 'partial':
        return lang("Partial");
      case 'canceled':
        return lang("Canceled");

      case 'refunded':
        return lang("Refunded");

      case 'active':
        return lang("Active");

      case 'awaiting':
        return lang("Awaiting");

      /*----------  subscriptions  ----------*/

      case 'paused':
        return lang("Paused");

      case 'expired':
        return lang("Expired");

      case 'fail':
        return lang("Fail");

      case 'error':
        return lang("Error");
    }
  }
}
