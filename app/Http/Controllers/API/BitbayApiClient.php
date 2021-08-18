<?php namespace App\Http\Controllers\API;

use Exception;

class BitbayApiClient {

	private static $apiUrl = "https://bitbay.net/API/Trading/tradingApi.php";

	function executeMethod($method, $params = []) {
		$params["method"] = $method;
		$params["moment"] = time();

		$post = http_build_query($params, "", "&");
		$sign = hash_hmac("sha512", $post, Secret::$BITBAY_SECRET_KEY);
		$headers = [
			"API-Key: " . Secret::$BITBAY_API_KEY,
			"API-Hash: " . $sign,
		];
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_URL, self::$apiUrl);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		$ret = curl_exec($curl);
		curl_close($curl);

		return $ret;
	}

	public function getBalances() {
		$info = $this->executeMethod("info");
		try {
			$json_decode = json_decode($info, true)['balances'];
		} catch (Exception $ex) {
			throw new Exception("array doesn't contain 'balances' key: $info");
		}

		$balances = [];
		foreach ($json_decode as $assetName => $balance) {
			$sum = $balance['available'] + $balance['locked'];
			if ($sum <= 0) {
				continue;
			}
			$assetTotal = $sum;
			$balances[] = ['asset' => $assetName, 'qty' => $assetTotal];
		}

		return $balances;
	}

}