<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\AscendexApiClient;
use PHPUnit\Framework\TestCase;

class AscendexApiClientTest extends TestCase {

	private static $apiClient;

	public static function setUpBeforeClass(): void {
		self::$apiClient = new AscendexApiClient();
	}

	public function testGetInfo() {
		$info = $this::$apiClient->getInfo();
//		print_r($info);
		self::assertIsArray($info);
	}

	public function testGetBalances() {
		$balances = $this::$apiClient->getBalances();
//		print_r($balances);
		self::assertIsArray($balances);
	}

}
