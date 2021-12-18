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

	public function getNormalTransactionsHistory($address) {

		$api = new Etherscan(Secret::$BSCSCAN_API_KEY);

//		https://api.bscscan.com/api
//		?module=account
//		&action=txlist
//		&address=0xF426a8d0A94bf039A35CEE66dBf0227A7a12D11e
//		&startblock=0
//		&endblock=99999999
//		&page=1
//		&offset=10
//		&sort=asc
//		&apikey=YourApiKeyToken
		$transactionList = $api->transactionList($address);

		return $transactionList;

	}


}