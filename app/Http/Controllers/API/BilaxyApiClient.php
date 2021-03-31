<?php namespace App\Http\Controllers\API;
class BilaxyApiClient {

	private static $apiUrl = "https://newapi.bilaxy.com";

	private $api;

	public function getBalances() {
		$uri = "/v1/accounts/balances";
		$req_time = (int) round(microtime(true) * 1000);
		$paramsArray = ["apikey" => Secret::$BILAXY_API_KEY, "timestamp" => $req_time];
		$queryParams = http_build_query($paramsArray);
		$signature = hash_hmac('sha256', $queryParams, Secret::$BILAXY_SECRET_KEY);
		$url = "{$this::$apiUrl}{$uri}?{$queryParams}&signature={$signature}";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,
			[
				"Upgrade-Insecure-Requests: 1",
				"User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36",
				"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3",
				"Accept-Language: en-US,en;q=0.9"
			]);

		$response = curl_exec($curl);
		$json_decode = json_decode($response, true);
		curl_close($curl);

		$balances = [];
		foreach ($json_decode as $assetName => $balance) {
			$sum = $balance['available'] + $balance['used'];
			if($sum<=0) {
				continue;
			}
			$assetTotal = $sum;
			$balances[] = ['asset' => $assetName, 'qty' => $assetTotal];
		}
		return $balances;
	}

}