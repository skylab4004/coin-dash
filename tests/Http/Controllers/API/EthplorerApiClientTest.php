<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\EthplorerApiClient;
use PHPUnit\Framework\TestCase;

class EthplorerApiClientTest extends TestCase {

	private static $api;

	public static function setUpBeforeClass(): void {
		self::$api = new EthplorerApiClient();
	}

	public function testGetAddressInfo() {
		$addressInfo = $this::$api->getAddressInfo("0x8B0fbDCEb542788D32a2834596de09d8DaeaBd2F");
		print_r($addressInfo);
		self::assertIsArray($addressInfo);
	}

}
