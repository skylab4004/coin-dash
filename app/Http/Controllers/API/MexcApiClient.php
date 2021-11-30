<?php namespace App\Http\Controllers\API;

use Exception;
use Log;

class MexcApiClient {

	private static $apiUrl = "https://www.mexc.com/";

	private $api;

	public function getBalances() {
		$balances = [];
		try {
			$accountInfoArray = json_decode($this->getAccountInfo(), true);
			$assets = $accountInfoArray['data'];
			foreach ($assets as $assetName => $balance) {
				$assetTotal = $balance['frozen']+$balance['available'];
				$balances[] = ['asset' => $assetName, 'qty' => $assetTotal];
			}
		} catch (Exception $e) {
			Log::error($e);
		}
		return $balances;
	}

	public function getAccountInfo() {
		$uri = "/open/api/v2/account/info";
		$req_time = (int) round(microtime(true));
		$params = ["api_key" => Secret::$MEXC_API_KEY, "req_time" => $req_time];
		$params["sign"] = self::getSignature("GET", $uri, http_build_query($params));
		$url = sprintf("%s?%s", self::$apiUrl.$uri, http_build_query($params));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($curl);
		curl_close($curl);

		return $response;
	}

	private static function getSignature($method, $requestUri, $params) {
		$string = "{$method}\n{$requestUri}\n{$params}";
		return hash_hmac('sha256', $string, Secret::$MEXC_SECRET_KEY);
	}


}