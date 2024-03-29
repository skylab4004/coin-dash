<?php namespace App\Http\Controllers\API;

use Exception;
use Log;

class EthplorerApiClient {

	private static $apiUrl = "https://api.ethplorer.io/";

	public function getAddressInfo($address, $apiKey = "freekey") {
		$ret = [];
		try {
			$response = file_get_contents("https://api.ethplorer.io/getAddressInfo/{$address}?apiKey={$apiKey}");
			$ret = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
		} catch (Exception $e) {
			Log::error($e);
		}
		return $ret;
	}

	public function erc20Balances($addressInfo) {
		$balances[] = ["asset" => "ETH", "name" => "Ethereum", "qty" => $addressInfo["ETH"]["balance"]];
		$tokens = $addressInfo["tokens"];
		foreach ($tokens as $asset => $tokenInfo) {
			$name = $tokenInfo["tokenInfo"]["name"];
			$symbol = $tokenInfo["tokenInfo"]["symbol"];
			$qty = $tokenInfo["balance"] / (10 ** $tokenInfo["tokenInfo"]["decimals"]);
			$balances[] = ["asset" => $symbol, "name" => $name, "qty" => $qty];
		}

		return $balances;
	}


	// Method: POST, PUT, GET etc
	// Data: array(");param" => "value") ==> index.php?param=value
	private function CallAPI($method, $url, $data = false) {
		$curl = curl_init();

		switch ($method) {
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);

				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_PUT, 1);
				break;
			default:
				if ($data)
					$url = sprintf("%s?%s", $url, http_build_query($data));
		}

		// Optional Authentication:
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, "username:password");

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);

		curl_close($curl);

		return $result;
	}

}