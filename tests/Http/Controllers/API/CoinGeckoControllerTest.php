<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\CoinGeckoController;
use PHPUnit\Framework\TestCase;

class CoinGeckoControllerTest extends TestCase {

	private static $geckoController;

	public static function setUpBeforeClass(): void {
		self::$geckoController = new CoinGeckoController();
	}

	public function testFavoriteCoinPrices() {
		$favoriteCoinPrices = $this::$geckoController->portfolioCoinsPrices();
		dd($favoriteCoinPrices);
		self::assertIsArray($favoriteCoinPrices);
	}

	public function testGetPrice() {
		$price = $this::$geckoController->getPrice('smooth-love-potion');
		dd($price);
		self::assertIsArray($price);
	}

}
