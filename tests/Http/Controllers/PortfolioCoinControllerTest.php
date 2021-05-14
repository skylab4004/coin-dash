<?php namespace Tests\Http\Controllers;

use App\Http\Controllers\PortfolioCoinController;
use PHPUnit\Framework\TestCase;

class PortfolioCoinControllerTest extends TestCase {

	private static $coinController;

	public static function setUpBeforeClass(): void {
		self::$coinController = new PortfolioCoinController();
	}

	public function testAddNewCoin() {
		$coin = $this::$coinController->addNewCoin("BNB");
		self::assertTrue($coin);
	}

	public function testAddNewCoinByCoinGeckoId() {
		$coin = $this::$coinController->addNewCoinByCoinGeckoId("rubic");
		self::assertTrue($coin);
	}

}
