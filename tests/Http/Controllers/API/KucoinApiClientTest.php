<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\KucoinApiClient;
use PHPUnit\Framework\TestCase;

class KucoinApiClientTest extends TestCase {


	private static $apiClient;

	public static function setUpBeforeClass(): void {
		self::$apiClient = new KucoinApiClient();
	}

	public function testGetAccounts() {
		$info = $this::$apiClient->getAccounts();
		print_r($info);
		self::assertIsArray($info);
	}

	public function testGetBalances() {
		$info = $this::$apiClient->getBalances();
		print_r($info);
		self::assertIsArray($info);
	}

}
