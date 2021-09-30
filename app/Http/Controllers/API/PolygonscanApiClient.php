<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Polygonscan\Polygonscan;
use etherscan\api\Etherscan;

class PolygonscanApiClient {

	private $api;

	public function getMaticBalance() {
		$api = new Polygonscan(Secret::$POLYGONSCAN_API_KEY);
		$balance = $api->balance(Secret::$POLYGON_WALLET_ADDRESS);

		return Etherscan::convertEtherAmount($balance["result"]);
	}

	public function getTokenBalance($tokenIdentifier) {
		$api = new Polygonscan(Secret::$POLYGONSCAN_API_KEY);
		$tokenBalance = $api->tokenBalance($tokenIdentifier, Secret::$POLYGON_WALLET_ADDRESS);

		return Etherscan::convertEtherAmount($tokenBalance["result"]);
	}

	public function getTxList() {
		$api = new Polygonscan(Secret::$POLYGONSCAN_API_KEY);
		$transactionListInternalByAddress = $api->transactionList(Secret::$POLYGON_WALLET_ADDRESS);

		return $transactionListInternalByAddress;
	}


}