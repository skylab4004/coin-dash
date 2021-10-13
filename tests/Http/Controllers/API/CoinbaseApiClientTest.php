<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\CoinbaseApiClient;
use PHPUnit\Framework\TestCase;

class CoinbaseApiClientTest extends TestCase {

	private static $apiClient;

	public static function setUpBeforeClass(): void {
		self::$apiClient = new CoinbaseApiClient();
	}

	public function testGetAccounts() {
		$info = $this::$apiClient->getAccounts();
		print_r($info);
		self::assertIsArray($info);
	}

}
