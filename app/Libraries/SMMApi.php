<?php
namespace App\Libraries;

use App\Libraries\Smms\Standard;

class SMMApi
{
  protected $provider_services_dir;

  public function __construct()
  {
    $this->provider_services_dir = public_path('provider_services/');
  }

  public function services($api_params = [])
  {
    $data_services = null;
    $data_services = $this->get_services_from_json_file(['api' => $api_params]);
    if (!empty($data_services)) {
      return $data_services;
    }
    $api    = new Standard($api_params);
    $result = $api->services();
    $this->save_services_to_json_file(['data_services' => $result, 'api' => $api_params]);
    return $result;
  }

  public function order($api_params = [], $data_post = [])
  {
    $api    = new Standard($api_params);
    $result = $api->order($data_post);
    return $result;
  }

  public function status($api_params = [], $order_id)
  {
    $api    = new Standard($api_params);
    $result = $api->status($order_id);
    return $result;
  }

  public function multiStatus($api_params = [], $order_ids)
  {
    $api    = new Standard($api_params);
    $result = $api->multiStatus($order_ids);
    return $result;
  }

  public function balance($api_params = [])
  {
    $api    = new Standard($api_params);
    $result = $api->balance();
    return $result;
  }

  /*----------  API Services data  ----------*/
  private function get_services_from_json_file($params = [])
  {
    $path_file = $this->provider_services_dir . $this->provider_json_file_name($params['api']);
    if (!file_exists($path_file)) {
      return false;
    }
    $data_api = json_decode(file_get_contents($path_file), true);
    if (!isset($data_api['data'])) {
      return false;
    }
    $last_time = strtotime(now()) - (15 * 60);
    if (strtotime($data_api['time']) > $last_time) {
      return $data_api['data'];
    }
    return false;
  }

  private function save_services_to_json_file($params = [])
  {
    $file_name = $this->provider_services_dir . $this->provider_json_file_name($params['api']);
    $mode      = (isset($params['mode'])) ? $params['mode'] : 'w';
    $content   = json_encode(['time' => now(), 'data' => $params['data_services']], JSON_PRETTY_PRINT);
    $handle    = fopen($file_name, $mode);
    if (is_writable($file_name)) {
      fwrite($handle, $content);
    }
    fclose($handle);
  }

  private function provider_json_file_name($api_params = [])
  {
    $name = 'unknown_' . time();
    if (isset($api_params['id']) && isset($api_params['name'])) {
      $name = trim(str_replace(' ', '_', strtolower($api_params['name'])));
      return $api_params['id'] . '-' . $name . '.json';
    } else {
      return $name . '.json';
    }
  }
}
