<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\PolygonscanApiClient;

use PHPUnit\Framework\TestCase;

class PolygonscanApiClientTest extends TestCase {

	public function testGetPolygonBalance() {
		$client = new PolygonscanApiClient();
		$balance = $client->getMaticBalance();
		print("Matic: $balance");
		self::assertIsNumeric($balance);
	}

	public function testGetTokenBalance() {
		$client = new PolygonscanApiClient();
		$balance = $client->getTokenBalance('0xa4eed63db85311e22df4473f87ccfc3dadcfa3e3');
		print($balance);
		self::assertIsNumeric($balance);
	}

	public function testGetTransactions() {
		$client = new PolygonscanApiClient();
		$txList = $client->getTxList();
		dd($txList);
		self::assertIsNumeric($txList);
	}

}
