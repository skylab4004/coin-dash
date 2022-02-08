<?php namespace App\Http\Controllers\API;

use Exception;
use GuzzleHttp\Client;

class TerraApiClient {

	const BASE_URI = 'https://fcd.terra.dev';

	public function getBalancesRaw() {

		$method = "GET";
		$uri = "/cosmos/bank/v1beta1/balances/";

		$client = new Client(['base_uri' => self::BASE_URI]);
		$response = $client->request($method, "{$uri}" . Secret::$TERRA_WALLET_ADDRESS, [
			'headers' => [
				'accept' => 'application/json',
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
				'accept' => 'application/json',
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

	public function getTokenInfoRaw($contractAddress) {

		$method = "GET";
		$uri = "/terra/wasm/v1beta1/contracts/{$contractAddress}/store";
		$client = new Client(['base_uri' => self::BASE_URI]);
		$base64Query = base64_encode("{\"token_info\":{}}");
		$requestPath = "{$uri}?query_msg={$base64Query}";
		$response = $client->request($method, $requestPath, [
			'headers' => [
				'accept' => 'application/json',
			],
		]);

		$stream = $response->getBody();
		$contents = $stream->getContents();

		try {
			$json_decode = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
		} catch (Exception $ex) {
			throw new Exception("[Terra] TokenInfo response couldn't be parsed as array: $contents");
		}

		return $json_decode;
	}


	public function getTokenBalanceRaw($contractAddress) {

		$method = "GET";
		$uri = "/terra/wasm/v1beta1/contracts/{$contractAddress}/store";

		$client = new Client(['base_uri' => self::BASE_URI]);
		$walletAddress = Secret::$TERRA_WALLET_ADDRESS;
		$query = "{\"balance\":{\"address\":\"{$walletAddress}\"}}";
		$base64Query = base64_encode($query);
		$requestPath = "{$uri}?query_msg={$base64Query}";
		$response = $client->request($method, $requestPath, [
			'headers' => [
				'accept' => 'application/json',
			],
		]);

		$stream = $response->getBody();
		$contents = $stream->getContents();

		try {
			$json_decode = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
		} catch (Exception $ex) {
			throw new Exception("[Terra] Token balance response couldn't be parsed as array: $contents");
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
		$denoms = $this->getDenoms();
		$balances = [];
		$balancesRaw = $this->getBalancesRaw();
		$accounts = $balancesRaw['balances'];
		foreach ($accounts as $account) {
			$accountDenom = $account['denom'];
			$accountAmount = $account['amount'];

			$symbol = $denoms[$accountDenom];
			$balances[] = ['asset' => $symbol, 'qty' => sprintf('%f', $accountAmount * 10 ** -6)];
		}

		return $balances;
	}

	public function getSingleTokenBalance($contractAddress) {
		$tokenBalanceRaw = $this->getTokenBalanceRaw($contractAddress);
		$balanceValue = $tokenBalanceRaw["query_result"]["balance"];

		$tokenInfoRaw = $this->getTokenInfoRaw($contractAddress);
		$symbol = $tokenInfoRaw["query_result"]["symbol"];
		$decimals = $tokenInfoRaw["query_result"]["decimals"];


		$qty = $balanceValue * 10 ** -$decimals;

		return ["asset" => strtoupper($symbol), "qty" => $qty];

	}

	public function getTokenBalance($contractAddress) {
		$ret = [];
		if (is_array($contractAddress)) {
			foreach ($contractAddress as $address) {
				$ret[] = $this->getSingleTokenBalance($address);
			}
		} else {
			$ret = $this->getSingleTokenBalance($contractAddress);
		}
		return $ret;
	}

}
