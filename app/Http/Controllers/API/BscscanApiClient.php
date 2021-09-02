<?php namespace App\Http\Controllers\API;

use etherscan\api\Etherscan;
use Exception;
use Log;

class BscscanApiClient {

	private $api;

	public function getBnbBalance() {
		$balance = [];
		try {
			$api = new Etherscan(Secret::$BSCSCAN_API_KEY);
			$balance = $api->balance(Secret::$BSC_WALLET_ADDRESS);
		} catch (Exception $e) {
			Log::error($e);
		}
		return Etherscan::convertEtherAmount($balance["result"]);
	}

	public function getTokenBalance($tokenIdentifier) {
		$tokenBalance = [];
		try {
			$api = new Etherscan(Secret::$BSCSCAN_API_KEY);
			$tokenBalance = $api->tokenBalance($tokenIdentifier, Secret::$BSC_WALLET_ADDRESS);
		} catch (Exception $e) {
			Log::error($e);
		}
		return Etherscan::convertEtherAmount($tokenBalance["result"]);
	}


}