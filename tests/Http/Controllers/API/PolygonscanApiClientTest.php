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
		$balance = $client->getTokenBalance('0x7ceB23fD6bC0adD59E62ac25578270cFf1b9f619');
		print($balance);
		self::assertIsNumeric($balance);
	}

	public function testGetTransactions() {
		$client = new PolygonscanApiClient();
		$txList = $client->getTxList();
		self::assertIsNumeric($txList);
	}

	public function testMergeArrays() {
		$arr1 = ["a", "b", "c"];
		$arr2 = ["c", "1", "2"];

		$arr1 = array_merge($arr1, $arr2);

		self::assertEquals(["a", "b", "c", "c", "1", "2"], $arr1);

	}

}
