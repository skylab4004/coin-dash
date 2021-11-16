<?php namespace Tests\Http\Controllers\Bot;

use App\Http\Controllers\Bot\ZeroXApiClient;
use PHPUnit\Framework\TestCase;

class ZeroXApiClientTest extends TestCase {

	private static $apiClient;
	private static $CONTRACT_MIST = '0x68e374f856bf25468d365e539b700b648bf94b67';
	private static $CONTRACT_WBNB = '0xbb4cdb9cbd36b01bd1cbaebf2de08d9173bc095c';

	public static function setUpBeforeClass(): void {
		self::$apiClient = new ZeroXApiClient();
	}

	public function testSwapQuote() {

		$sellTokenAddress = ''; // self::$CONTRACT_WBNB;
		$buyTokeAddress = self::$CONTRACT_MIST;
		$buyAmount = '';
		$sellAmount = '1';
		$swapQuote = self::$apiClient->swapQuote($sellTokenAddress, $buyTokeAddress, $buyAmount, $sellAmount);
		print($swapQuote);
		self::assertIsNumeric($swapQuote);
	}



}
