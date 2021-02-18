<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\CoinGeckoController;
use PHPUnit\Framework\TestCase;

class CoinGeckoControllerTest extends TestCase {

	private static $geckoController;

	public static function setUpBeforeClass(): void {
		self::$geckoController = new CoinGeckoController();
	}

	public function testPing() {
		$btcPriceInPln = $this::$geckoController->btcPriceInPln();
		print($btcPriceInPln);
		self::assertIsInt($btcPriceInPln);
	}

	public function testPricesOfFavoriteCoins() {
		$pricesOfFavoriteCoins = $this::$geckoController->pricesOfFavoriteCoins();
		print_r($pricesOfFavoriteCoins);
		self::assertIsArray($pricesOfFavoriteCoins);
	}


}
