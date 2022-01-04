<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Polygonscan\Polygonscan;
use etherscan\api\Etherscan;
use Illuminate\Support\Facades\Log;

class PolygonscanApiClient {

	private $api;

	public function getMaticBalance() {
		$api = new Polygonscan(Secret::$POLYGONSCAN_API_KEY);
		$balance = $api->balance(Secret::$POLYGON_WALLET_ADDRESS);

		if (array_key_exists("result", $balance)) {
			return Etherscan::convertEtherAmount($balance["result"]);
		}

		Log::error("[PolygonScan] no result found in: ".print_r($balance, TRUE));

		return 0;
	}

	public function getTokenBalance($tokenIdentifier) {
		$api = new Polygonscan(Secret::$POLYGONSCAN_API_KEY);
		$balance = $api->tokenBalance($tokenIdentifier, Secret::$POLYGON_WALLET_ADDRESS);

		if (array_key_exists("result", $balance)) {
			return Etherscan::convertEtherAmount($balance["result"]);
		}

		Log::error("[PolygonScan] no result found in: ".print_r($balance, TRUE));

		return 0;
	}

	public function getTxList() {
		$api = new Polygonscan(Secret::$POLYGONSCAN_API_KEY);
		$transactionListInternalByAddress = $api->transactionList(Secret::$POLYGON_WALLET_ADDRESS);

		return $transactionListInternalByAddress;
	}


}