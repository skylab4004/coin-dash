<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\TerraApiClient;
use PHPUnit\Framework\TestCase;

class TerraApiClientTest extends TestCase {

	private static $apiClient;

	public static function setUpBeforeClass(): void {
		self::$apiClient = new TerraApiClient();
	}

	public function testGetAccounts() {
		$info = $this::$apiClient->getBalancesRaw();
		print_r($info);
		self::assertIsArray($info);
	}


	public function testGetBalances() {
		$info = $this::$apiClient->getBalances();
		print_r($info);
		self::assertIsArray($info);
	}

	public function testGetDenoms() {
		$info = $this::$apiClient->getDenoms();
		print_r($info);
		self::assertIsArray($info);
	}



}
