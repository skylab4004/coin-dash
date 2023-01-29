<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\MexcApiClient;
use PHPUnit\Framework\TestCase;

class MexcApiClientTest extends TestCase {

	private static $mexcApi;

	public static function setUpBeforeClass(): void {
		self::$mexcApi = new MexcApiClient();
	}

	public function testGetBalances() {
		$balances = $this::$mexcApi->getBalances();
		foreach ($balances  as $assetBalance) {
			$assetName = $assetBalance['asset'];
			$assetBalance = $assetBalance['qty'];
		}
		print_r($balances);
		self::assertIsArray($balances);
	}

	public function testGetAccountInfo() {
		$accountIn = $this::$mexcApi->getAccountInfo();
		dd($accountIn);
		self::assertJson($accountIn);
	}

	public function testSignature() {
		$string = "GET\n/api/v2/order/open_orders\napi_key=mxcV9JCC8iu8zpaiWC&limit=1000&req_time=1572936251&startTime=1572076703000&symbol=MX_ETH&tradeType=BID";
		$hash = hash_hmac('sha256', $string, "", false);
		self::assertEquals('cc15d92f616b5832b1308e95bf3fadaf2cf7cdf8e3ac8e8c40f076be1c8d1ff7', $hash);
	}

}
