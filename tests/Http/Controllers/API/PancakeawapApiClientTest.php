<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\PancakeSwapApiClient;
use PHPUnit\Framework\TestCase;

class PancakeawapApiClientTest extends TestCase {

	private static $apiClient;

	public static function setUpBeforeClass(): void {
		self::$apiClient = new PancakeSwapApiClient();
	}

	public function testGetTokenPrice() {
		$price = $this::$apiClient->getTokenPrice("0x68e374f856bf25468d365e539b700b648bf94b67");
		print($price);
		self::assertIsNumeric($price);
	}

}
