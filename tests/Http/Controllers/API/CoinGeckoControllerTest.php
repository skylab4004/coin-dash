<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\CoinGeckoController;
use PHPUnit\Framework\TestCase;

class CoinGeckoControllerTest extends TestCase {

	private static $geckoController;

	public static function setUpBeforeClass(): void {
		self::$geckoController = new CoinGeckoController();
	}

	public function testFavoriteCoinPrices() {
		$favoriteCoinPrices = $this::$geckoController->favoriteCoinPrices();
		self::assertIsArray($favoriteCoinPrices);
	}

	public function testAddNewCoin() {
		$coin = $this::$geckoController->addNewCoin("BNB");
		self::assertTrue($coin);
	}

}
