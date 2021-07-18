<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\PancakeawapApiClient;
use PHPUnit\Framework\TestCase;

class PancakeawapApiClientTest extends TestCase {

	private static $apiClient;

	public static function setUpBeforeClass(): void {
		self::$apiClient = new PancakeawapApiClient();
	}

	public function testGetTokenPrice() {
		$price = $this::$apiClient->getTokenPrice("0x68e374f856bf25468d365e539b700b648bf94b67");
		self::assertIsNumeric($price);
	}

}
