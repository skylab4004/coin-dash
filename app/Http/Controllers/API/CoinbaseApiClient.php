<?php namespace App\Http\Controllers\API;

use Exception;
use GuzzleHttp\Client;

class CoinbaseApiClient {

	const BASE_URI = 'https://api.exchange.coinbase.com';

	public function getAccounts() {

		$secret = base64_decode(Secret::$COINBASE_API_SECRET);
		$timestamp = time();

		$requestPath = '/accounts';
		$body = "";
		$method = "GET";
		$message = $timestamp . $method . $requestPath . $body;

		$hmac = hash_hmac('sha256', $message, $secret, true);
		$signature = base64_encode($hmac);

		$client = new Client(['base_uri' => self::BASE_URI]);
		$response = $client->request($method, '/accounts', [
			'headers' => [
				"CB-ACCESS-KEY"        => Secret::$COINBASE_API_KEY,
				"CB-ACCESS-SIGN"       => $signature,
				"CB-ACCESS-TIMESTAMP"  => $timestamp,
				"CB-ACCESS-PASSPHRASE" => Secret::$COINBASE_PASSPHRASE,
				'Content-Type'         => 'application/json',
			],
		]);

		$stream = $response->getBody();
		$contents = $stream->getContents();

		try {
			$json_decode = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
		} catch (Exception $ex) {
			throw new Exception("Response from Coinbase couldn't be parsed as array: $contents");
		}

		return $json_decode;
	}

}
