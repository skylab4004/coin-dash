<?php namespace Tests\Http\Controllers;

use App\Http\Controllers\StaticPortfolioCoinController;
use PHPUnit\Framework\TestCase;

class StaticPortfolioCoinControllerTest extends TestCase {


	private static $staticCoinsController;

	public static function setUpBeforeClass(): void {
		self::$staticCoinsController = new StaticPortfolioCoinController();
	}

	public function testFavoriteCoinPrices() {
		$coins = $this::$staticCoinsController->getStaticPortfolioCoins();
		self::assertIsArray($coins);
	}

}
