<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\CoinGeckoController;
use PHPUnit\Framework\TestCase;

class CoinGeckoControllerTest extends TestCase {

	private static $geckoController;

	public static function setUpBeforeClass(): void {
		self::$geckoController = new CoinGeckoController();
	}

	public function testPing() {
		$favoriteCoinPrices = $this::$geckoController->favoriteCoinPrices();
		print_r($favoriteCoinPrices);
		self::assertIsArray($favoriteCoinPrices);
	}

}
