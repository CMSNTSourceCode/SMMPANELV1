<?php
namespace App\Libraries;

use App\Libraries\Smms\BaseAPI;

class SMMApis {
  protected object $api;
  protected string $provider_services_dir;


	function __construct($api = ""){
		$this->api = $api;
		$apis_list = apis_list();
		foreach ($apis_list as $key => $api_name) {
			if(file_exists("./Smms/".$key.".php")){
		        require_once './Smm/'.$key.'.php';
		    }
		}
    $this->provider_services_dir = public_path('provider_services/');
	}

	function services(){
		$api_name 	= trim(str_replace(' ', '_', $this->api->name));
		// $api_json_path = FCPATH. "public/smm_api/". $api_name .".json";
    $api_json_path = public_path('smm_api/'.$api_name.'.json');
		$data_services = [];
		if ( file_exists($api_json_path) ) {
			$get_api_services = [
				'path' => $api_json_path
			];
			$data_services = get_api_services_from_json_file($get_api_services);
		}

		if($data_services){
			return $data_services;
		}
		switch ($this->api->type) {
			case 'instasmm':
				$api 	  = new smm_instasmm($this->api);
				$result_tmp = $api->services();
				$result_tmp = (array)$result_tmp;
				$result = NULL;
				if (!empty($result_tmp)  && is_array($result_tmp)){
					$i = 0;
					foreach ($result_tmp as $key => $row) {
						$result[$i] = (object)array();
						$result[$i]->service       = $row->service_id;
						$result[$i]->name          = $row->name;
						$result[$i]->category      = $row->category;
						$result[$i]->rate          = $row->price;
						$result[$i]->type          = 'default';
						$result[$i]->min           = $row->min_amount;
						$result[$i]->max           = $row->max_amount;
						$result[$i]->desc          = $row->description;
						$result[$i]->type          = 'default';
						$result[$i]->dripfeed      = 0;
						$i++;
					}
				}
				break;

			case 'indusrabbit':
				$api 	  = new smm_indusrabbit($this->api);
				$result = $api->packages();
				if (!empty($result)  && is_array($result)){
					foreach ($result as $key => $row) {
						$result[$key]->category = $row->service;
						$result[$key]->service  = $row->id;

						if ($row->type == "custom_data") {
							$result[$key]->type = "custom_comments";
						}
						unset($result[$key]->id);
						unset($result[$key]->service_id);
					}
				}
				break;

			case 'realfans':
				$result = [];

				$api 	  = new smm_realfans($this->api);
				$cates   = $api->services();


				if (!empty($cates)  && is_array($cates)){
					foreach ($cates as $key => $cate) {

						$services_tmp = $api->products($cate->service_id);
						if(!$services_tmp){
							continue;
						}
						foreach ($services_tmp as $key => $row) {
							$service = (object)[];
							$service->category      = $cate->service_name;
							$service->name          = $row->name;
							$service->service       = $row->api_key;
							$service->rate          = ( (double)$row->price_unit == 100) ? (double)$row->price * 1000 : $row->price;
							$service->min           = $row->order_min;
							$service->max           = $row->order_max;
							$service->desc          = trim(strip_tags($row->description));
							$service->type          = 'default';
							$service->dripfeed      = 0;
							$result[] = $service;
						}
					}

				}

				break;

			case 'yoyomedia':
				$api 	  = new smm_yoyomedia($this->api);
				$result   = $api->services();
				if (!empty($result)  && is_array($result)){
					foreach ($result as $key => $row) {
						$result[$key]->service       = $row->type;
						$result[$key]->rate          = $row->price_per_1000;
						$result[$key]->min           = $row->order_min;
						$result[$key]->max           = $row->order_max;
						$result[$key]->desc          = $row->description;
						$result[$key]->type          = 'default';
						$result[$key]->dripfeed      = 0;

						unset($result[$key]->price_per_1000);
						unset($result[$key]->order_min);
						unset($result[$key]->order_max);
						unset($result[$key]->description);
					}
				}
				break;

			default:
				$api      = new smm_standard($this->api);
				$result   = $api->services();
				break;
		}
		// create API service DATA and save json file
		$data_file = [
			'path' 		=> $api_json_path,
			'content' 	=> json_encode( [ 'time' => NOW , 'data' =>  $result ], JSON_PRETTY_PRINT ),
		];
		create_file($data_file);
		return $result;

	}

	function order($data_post){
		switch ($this->api->type) {

			case 'instasmm':
				$api 	  	= new smm_instasmm($this->api);
				$result     = $api->addOrder($data_post);
				if (isset($result->orderID)) {
					$result->order = $result->orderID;
					unset($result->orderID);
				}
				break;

			case 'indusrabbit':
				$api 	  = new smm_indusrabbit($this->api);
				$result   = $api->order($data_post);
				if (isset($result->errors)) {
					$result->error = $result->errors[0];
					unset($result->errors);
				}

				break;

			case 'yoyomedia':
				$api 	  = new smm_yoyomedia($this->api);
				$result   = $api->order($data_post);

				if (isset($result->status)) {
					switch ($result->status) {
						case 'ok':
							unset($result->status);
							unset($result->message);
							break;

						case 'fail':
							$result->error = $result->message;
							unset($result->status);
							unset($result->message);
							break;
					}
				}

				break;

			case 'realfans':
				$api 	  = new smm_realfans($this->api);
				$result   = $api->order($data_post);

				if (isset($result->status)) {
					switch ($result->status) {
						case 'ok':
							unset($result->status);
							unset($result->message);
							break;

						case 'fail':
							$result->error = $result->message;
							unset($result->status);
							unset($result->message);
							break;
					}
				}

				break;

			default:
				$api      = new smm_standard($this->api);
				$result   = $api->order($data_post);
				break;
		}

		return $result;
	}


	/**
	 * Respone data Standard
	 * {
		    "charge": "0.27819",
		    "start_count": "3572",
		    "status": "Partial",
		    "remains": "157",
		    "currency": "USD"
		}
	 *
	 */

	function status($order_id){
		switch ($this->api->type) {

			case 'instasmm':
				/**
				 *
				 * {
					"orderQuantity": 10000,
					"startCount": "55",
					"remaining_amount": "1000.0000000000",
					"refunded_amount": "1000.0000000000",
					"orderStatus": "Partially Completed",
					"orderDate": "2016-10-15 12:47:53",
					"orderPrice": "2.0700000000"
				}
				 *
				 */

				$api 	  = new smm_instasmm($this->api);
				$result = $api->orderStatus($order_id);
				if (isset($result->orderQuantity)) {
					$result->charge       = $result->orderPrice;
					$result->remains      = $result->orderStatus;
					$result->start_count  = $result->startCount;
					$result->status       = $result->orderStatus;
					if (strrpos($result->status, 'Partially')) {
						$result->status       = 'Partial';
					}

					unset($result->orderQuantity);
					unset($result->startCount);
					unset($result->remaining_amount);
					unset($result->refunded_amount);
					unset($result->orderStatus);
					unset($result->orderDate);
					unset($result->orderPrice);
				}

				break;

			case 'indusrabbit':
				/**
				 *
				 * {
					  "status": "Completed",
					  "start_counter": "600",
					  "remains": "600"
					}
				 *
				 */

				$api 	  = new smm_indusrabbit($this->api);
				$result = $api->status($order_id);
				if (isset($result->errors)) {
					$result->error = $result->errors[0];
					unset($result->errors);
				}

				if (isset($result->status)) {
					$result->start_count = $result->start_counter;
					unset($result->start_counter);
					if ($result->status == "Cancelled") {
						$result->status = "Canceled";
					}
				}
				break;

			case 'yoyomedia':

				/**
				 *
				 * {
					    "status": "ok",
					    "type": "instagram_real_active_s1",
					    "time": "1503642755",
					    "amount": 1000,
					    "price": "0.9000",
					    "link": "instagram",
					    "order_status": "Processing",
					    "count_start": "100",
					    "count_current": "100",
					    "count_remain": "100",
					    "count_finish": "200"
					}
				 *
				 */

				$api 	  = new smm_yoyomedia($this->api);
				$result   = $api->status($order_id);

				if (isset($result->status)) {
					switch ($result->status) {
						case 'ok':
							$result->status      = $result->order_status;
							$result->charge      = $result->price;
							$result->start_count = $result->count_start;
							$result->remains     = $result->count_remain;

							unset($result->type);
							unset($result->time);
							unset($result->amount);
							unset($result->price);
							unset($result->link);
							unset($result->order_status);
							unset($result->count_start);
							unset($result->count_remain);
							unset($result->count_finish);
							break;

						case 'fail':
							$result->error = $result->message;
							unset($result->status);
							unset($result->message);
							break;
					}
				}

				break;
			case 'realfans':

				/**
				 *
				 *  "status": "ok",
					"type": "instagram_likes_germany_super_real",
					"time": "1619183654",
					"amount": "100",
					"price": "15.0000000",
					"link": "https://www.instagram.com/p/COAnKQeBbMK/?igshid=l1jx4rlm1lan",
					"order_status": "Completed",
					"order_status_error": "Order was inserted! Please wait 1 minute to get it started!",
					"count_start": "6",
					"count_current": "6",
					"count_remain": "100",
					"count_finish": "106"

					"status": "ok",
					"type": "facebook_post_likes_germany",
					"time": "1619009023",
					"amount": "10",
					"price": "2.5000000",
					"link": "https://www.facebook.com/100009083395064/posts/2835821836730568/?d=n&substory_index=0",
					"order_status": "Refunded",
					"order_status_error": "Refunded : 2.5000000",
					"count_start": "0",
					"count_current": "0",
					"count_remain": "10",
					"count_finish": "10"
				 *
				 */

				$api 	  = new smm_realfans($this->api);
				$result   = $api->status($order_id);

				if (isset($result->status)) {
					switch ($result->status) {
						case 'ok':
							$result->status      = $result->order_status;
							$result->charge      = $result->price;
							$result->start_count = $result->count_start;
							$result->remains     = $result->count_remain;

							unset($result->type);
							unset($result->time);
							unset($result->amount);
							unset($result->price);
							unset($result->link);
							unset($result->order_status);
							unset($result->count_start);
							unset($result->count_remain);
							unset($result->count_finish);
							break;

						case 'fail':
							$result->error = $result->message;
							unset($result->status);
							unset($result->message);
							break;
					}
				}

				break;

			default:
				$api      = new smm_standard($this->api);
				$result   = $api->status($order_id);
				break;
		}

		return $result;
	}



	function balance(){
		switch ($this->api->type) {

			case 'instasmm':
				$api 	  = new smm_instasmm($this->api);
				$result = $api->balance();
				break;

			case 'indusrabbit':
				$api 	  = new smm_indusrabbit($this->api);
				$result   = $api->balance();
				// pr($result, 1);
				break;

			case 'yoyomedia':
				$api 	  = new smm_yoyomedia($this->api);
				$result   = $api->balance();
				if (isset($result->balance)) {
					$result->currency = "USD";
				}
				break;

			case 'realfans':
				$api 	  = new smm_realfans($this->api);
				$result   = $api->balance();
				if (isset($result->balance)) {
					$result->currency = "USD";
				}
				break;

			default:
				$api      = new smm_standard($this->api);
				$result   = $api->balance();
				break;
		}
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
