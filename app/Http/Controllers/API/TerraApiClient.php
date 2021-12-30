<?php namespace App\Http\Controllers\API;

use Exception;
use GuzzleHttp\Client;

class TerraApiClient {

	const BASE_URI = 'https://fcd.terra.dev';

	private function getBalancesRaw() {

		$method = "GET";
		$uri = "/cosmos/bank/v1beta1/balances/";

		$client = new Client(['base_uri' => self::BASE_URI]);
		$response = $client->request($method, "{$uri}".Secret::$TERRA_WALLET_ADDRESS, [
			'headers' => [
				'accept'         => 'application/json',
			],
		]);

		$stream = $response->getBody();
		$contents = $stream->getContents();

		try {
			$json_decode = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
		} catch (Exception $ex) {
			throw new Exception("Response from Terra's Balances couldn't be parsed as array: $contents");
		}

		return $json_decode;
	}

	private function getDenomsRaw() {

		$method = "GET";
		$uri = "/cosmos/bank/v1beta1/denoms_metadata";

		$client = new Client(['base_uri' => self::BASE_URI]);
		$response = $client->request($method, $uri, [
			'headers' => [
				'accept'         => 'application/json',
			],
		]);

		$stream = $response->getBody();
		$contents = $stream->getContents();

		try {
			$json_decode = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
		} catch (Exception $ex) {
			throw new Exception("Response from Terra couldn't be parsed as array: $contents");
		}

		return $json_decode;
	}

	public function getDenoms() {
		$denomsRaw = $this->getDenomsRaw();
		$tmpDenoms = $denomsRaw['metadatas'];
		$denoms = [];
		foreach ($tmpDenoms as $tmpDenom) {
			$base = $tmpDenom['base'];
			$symbol = $tmpDenom['symbol'];
			$denoms[$base] = $symbol;
		}

		return $denoms;
	}

	public function getBalances() {
		$denoms =  $this->getDenoms();
		$balances = [];
		$balancesRaw = $this->getBalancesRaw();
		$accounts = $balancesRaw['balances'];
		foreach ($accounts as $account) {
			$accountDenom = $account['denom'];
			$accountAmount = $account['amount'];

			$symbol = $denoms[$accountDenom];
			$balances[] = ['asset' => $symbol, 'qty' => sprintf('%f', $accountAmount*10**-6) ];
		}

		return $balances;
	}

}
