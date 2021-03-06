<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\BscscanApiClient;
use PHPUnit\Framework\TestCase;

class BscscanApiClientTest extends TestCase {


	public function testGetBalances() {
		$client = new BscscanApiClient();
		$balance = $client->getBnbBalance();
		self::assertIsNumeric($balance);
	}

	public function testGetEthBalance() {
		$client = new BscscanApiClient();
		$balance = $client->getBnbBalance();
		print($balance);
		self::assertIsNumeric($balance);
	}

}
