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
		$balance = $client->getTokenBalance("0x2170Ed0880ac9A755fd29B2688956BD959F933F8");
		print($balance);
		self::assertIsNumeric($balance);
	}

}
