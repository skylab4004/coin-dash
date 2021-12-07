<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\BscscanApiClient;
use PHPUnit\Framework\TestCase;

class BscscanApiClientTest extends TestCase {


	public function testGetBnbBalance() {
		$client = new BscscanApiClient();
		$balance = $client->getBnbBalance();
		self::assertIsNumeric($balance);
	}

	public function testGetEthBalance() {
		$client = new BscscanApiClient();
		$balance = $client->getTokenBalance("0x68e374f856bf25468d365e539b700b648bf94b67");
		print($balance);
		self::assertIsNumeric($balance);
	}

}
