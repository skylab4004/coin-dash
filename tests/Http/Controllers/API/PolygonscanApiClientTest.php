<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\PolygonscanApiClient;
use PHPUnit\Framework\TestCase;

class PolygonscanApiClientTest extends TestCase {

	public function testGetBalances() {
		$client = new PolygonscanApiClient();
		$balance = $client->getPolygonBalance();
		dd($balance);
		self::assertIsNumeric($balance);
	}

	public function testGetEthBalance() {
		$client = new PolygonscanApiClient();
		$balance = $client->getTokenBalance('0xa4eed63db85311e22df4473f87ccfc3dadcfa3e3');
		print($balance);
		self::assertIsNumeric($balance);
	}

}
