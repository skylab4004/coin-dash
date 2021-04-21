<?php namespace App\Http\Controllers\API;

use etherscan\api\Etherscan;

class BscscanApiClient {

	private $api;

	public function getBnbBalance() {
		$api = new Etherscan(Secret::$BSCSCAN_API_KEY);
		$balance = $api->balance(Secret::$BSC_WALLET_ADDRESS);
		return Etherscan::convertEtherAmount($balance["result"]);
	}

	public function getTokenBalance($tokenIdentifier) {
		$api = new Etherscan(Secret::$BSCSCAN_API_KEY);
		$tokenBalance = $api->tokenBalance($tokenIdentifier, Secret::$BSC_WALLET_ADDRESS);
		return Etherscan::convertEtherAmount($tokenBalance["result"]);
	}


}