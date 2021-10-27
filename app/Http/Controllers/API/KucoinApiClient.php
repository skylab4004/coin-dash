<?php namespace App\Http\Controllers\API;

use Exception;

class KucoinApiClient {

	const HOST = 'https://api.kucoin.com';

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

	function signature($request_path = '', $body = '', $timestamp = false, $method = 'GET') {

		$body = is_array($body) ? json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : $body;
		$timestamp = $timestamp ? $timestamp : time();

		$what = $timestamp . $method . $request_path . $body;

		return base64_encode(hash_hmac("sha256", $what, Secret::$KUCOIN_API_SECRET, true));
	}

	function getAccounts() {

		$endpoint = '/api/v1/accounts';
		$method = 'GET';
		$body = '';
		$timestamp = (int) (microtime(true) * 1000);

		$sign = $this->signature($endpoint, $body, $timestamp, $method);

		$headers = [];
		$headers[] = "Content-Type:application/json";
		$headers[] = "KC-API-KEY:" . Secret::$KUCOIN_API_KEY;
		$headers[] = "KC-API-TIMESTAMP:" . $timestamp;
		$headers[] = "KC-API-PASSPHRASE:" . Secret::$KUCOIN_API_PASSPHRASE;
		$headers[] = "KC-API-SIGN:" . $sign;

		$requestPath = self::HOST . $endpoint;

		$response = $this->http_request($method, $requestPath, $headers, $body);

		try {
			$json_decode = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
		} catch (Exception $ex) {
			throw new Exception("Response from Coinbase couldn't be parsed as array: $response");
		}

		return $json_decode;

	}

	public function getBalances() {
		$accounts = $this->getAccounts();

		$balances = [];
		$data = $accounts['data'];
		foreach ($data as $account) {
			if ($account['balance'] > 0) {
				$balances[] = ['asset' => $account['currency'], 'qty' => $account['balance']];
			}
		}

		$sum = array_reduce($balances, function ($a, $b) {
			isset($a[$b['asset']]) ? $a[$b['asset']]['qty'] += $b['qty'] : $a[$b['asset']] = $b;
			return $a;
		});

		return $sum;
	}

}
