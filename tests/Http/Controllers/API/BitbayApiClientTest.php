<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\BitbayApiClient;
use PHPUnit\Framework\TestCase;

class BitbayApiClientTest extends TestCase {

	private static $apiClient;

	public static function setUpBeforeClass(): void {
		self::$apiClient = new BitbayApiClient();
	}

	public function testGetBalances() {
		$balances = $this::$apiClient->getBalances();
		print_r(array_column($balances, 'asset'));
		self::assertIsArray($balances);
	}

}
