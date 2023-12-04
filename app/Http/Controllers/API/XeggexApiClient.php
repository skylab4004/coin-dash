<?php namespace App\Http\Controllers\API;
class XeggexApiClient {

	const HOST = "https://api.xeggex.com";

	function http_request($method, $url, $headers = [], $data = null) {

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

		if ($method === 'POST') {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}

		if ($method === 'DELETE') {
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		}

		if ( ! empty($headers)) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		}

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($curl);
		curl_close($curl);

		return $output;
	}

	function signature($param) {
		return hash_hmac("sha256", $param, Secret::$XEGGEX_API_SECRET, false);
	}

	public function balances() {
		$endpoint = '/api/v2/balances';
		$method = 'GET';
		$body = '';

		$requestPath = self::HOST . $endpoint;
		$timestamp = (int) (microtime(true) * 1000);

		$signature = $this->signature(Secret::$XEGGEX_API_KEY.$requestPath.$timestamp);

		$headers = [];
		$headers[] = "Content-Type: application/json";
		$headers[] = "X-API-KEY:" . Secret::$XEGGEX_API_KEY;
		$headers[] = "X-API-NONCE:" . $timestamp;
		$headers[] = "X-API-SIGN:" . $signature;

		$response = $this->http_request($method, $requestPath, $headers, $body);

		try {
				$json_decode = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
		} catch (Exception $ex) {
			throw new Exception("Response from Xeggex couldn't be parsed as array: $response");
		}

		return $json_decode;

	}

	public function getBalances() {
		$data = $this->balances();

		$balances = [];

		foreach ($data as $assetBalance) {
			$b = $assetBalance['available']+$assetBalance['pending']+$assetBalance['held'];
			if ($b>0) {
				$balances[] = ['asset' => $assetBalance['asset'], 'qty' => $b];
			}
		}

		return $balances;
	}

	public function info() {
		$endpoint = '/api/v2/info';
		$method = 'GET';
		$body = '';

		$headers = [];
		$headers[] = "accept: application/json";

		$requestPath = self::HOST . $endpoint;

		$response = $this->http_request($method, $requestPath, $headers, $body);

		try {
			$json_decode = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
		} catch (Exception $ex) {
			throw new Exception("Response from Xeggex couldn't be parsed as array: $response");
		}

		return $json_decode;

	}


}