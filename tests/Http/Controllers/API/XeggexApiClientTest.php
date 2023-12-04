<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\XeggexApiClient;
use PHPUnit\Framework\TestCase;

class XeggexApiClientTest extends TestCase {

	private static $apiClient;

	public static function setUpBeforeClass(): void {
		self::$apiClient = new XeggexApiClient();
	}

	public function testGetInfo() {
		$info = $this::$apiClient->info();
		print_r($info);
		self::assertIsArray($info);
	}

	public function testBalances() {
		$info = $this::$apiClient->balances();
		self::assertIsArray($info);
	}

	public function testGetBalances() {
		$balances = $this::$apiClient->getBalances();
		print_r($balances);
		self::assertIsArray($balances);
	}
}