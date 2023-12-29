<?php namespace App\Http\Controllers\API;

use Exception;

class BitbayApiClient {

	private static $apiUrl = "https://api.zondacrypto.exchange";

	function GetUUID($data) {
		assert(strlen($data) == 16);
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80);
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}

	public function getBalances() {

		$pubkey = Secret::$BITBAY_API_KEY;
		$privkey = Secret::$BITBAY_SECRET_KEY;

		$body    = ""; // json_encode($body);
		$time    = time();
		$data = $pubkey . $time . $body;
		$sign    = hash_hmac("sha512", $data, $privkey);

		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => $this::$apiUrl."/rest/balances/BITBAY/balance",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => [
				"API-Key: " . $pubkey,
				"API-Hash: " . $sign,
				"operation-id: " . $this->GetUUID(random_bytes(16)),
				"Request-Timestamp: " . $time,
				"Content-Type: application/json",
				"accept: application/json"

			],
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			echo $response;
		}

		try {
			$json_decode = json_decode($response, true)['balances'];
		} catch (Exception $ex) {
			throw new Exception("array doesn't contain 'balances' key: $response");
		}

		$balances = [];
		foreach ($json_decode as $asset) {
			$totalFunds = Utils::formattedNumber($asset['totalFunds']);
			if ($totalFunds<=0) {
				continue;
			}
			$balances[] = ['asset' => $asset['currency'], 'qty' => $totalFunds];
		}

		return $balances;
	}

}