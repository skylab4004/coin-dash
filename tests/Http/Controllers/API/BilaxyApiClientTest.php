<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\BilaxyApiClient;
use PHPUnit\Framework\TestCase;

class BilaxyApiClientTest extends TestCase {

	private static $apiClient;

	public static function setUpBeforeClass(): void {
		self::$apiClient = new BilaxyApiClient();
	}


	public function testGetBalances() {
		$balances = $this::$apiClient->getBalances();
		self::assertIsArray($balances);
	}


}
