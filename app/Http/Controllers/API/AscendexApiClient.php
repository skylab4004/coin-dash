<?php namespace App\Http\Controllers\API;
use Exception;

class AscendexApiClient {

	private static $apiUrl = "https://ascendex.com/1/api/pro/v1/";

	private $api;

	public function getInfo() {
		$uri = "info";
		$req_time = (int) round(microtime(true) * 1000); // 1634062975799
		$message = $req_time.$uri;
		$signature = base64_encode(hash_hmac('sha256', $message, Secret::$ASCENDEX_SECRET_KEY, true));

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://ascendex.com/api/pro/v1/".$uri);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);
		$apiKey = Secret::$ASCENDEX_API_KEY;
		curl_setopt($curl, CURLOPT_HTTPHEADER,
			[
				"Accept: application/json",
				"Content-Type: application/json",
				"x-auth-key: {$apiKey}",
				"x-auth-signature: {$signature}",
				"x-auth-timestamp: {$req_time}",
			]);

		$response = curl_exec($curl);
		try {
			$json_decode = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
		} catch (Exception $ex) {
			throw new Exception("response from AsdendEx couldn't be parsed as array: $response");
		}
		curl_close($curl);

		return $json_decode;
	}

	public function getBalances() {
		$uri = "cash/balance";
		$req_time = (int) round(microtime(true) * 1000); // 1634062975799
		$message = $req_time."balance";
		$signature = base64_encode(hash_hmac('sha256', $message, Secret::$ASCENDEX_SECRET_KEY, true));
		$apiKey = Secret::$ASCENDEX_API_KEY;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://ascendex.com/1/api/pro/v1/".$uri);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,
			[
				"Accept: application/json",
				"Content-Type: application/json",
				"x-auth-key: {$apiKey}",
				"x-auth-signature: {$signature}",
				"x-auth-timestamp: {$req_time}",
			]);

		$response = curl_exec($curl);
		try {
			$json_decode = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
		} catch (Exception $ex) {
			throw new Exception("response from AscendEx couldn't be parsed as array: $response");
		}
		curl_close($curl);

		$balances = [];
		foreach ($json_decode['data'] as $assetBalance) {
			$balances[] = ['asset' => $assetBalance['asset'], 'qty' => $assetBalance['totalBalance']];
		}
		return $balances;
	}

}