<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\BinanceController;
use Config;
use PHPUnit\Framework\TestCase;

class BinanceControllerTest extends TestCase {

	private static $binanceController;

	public static function setUpBeforeClass(): void {
		self::$binanceController = new BinanceController();
	}

	public function testTicker() {
		$ticker = $this::$binanceController->currentPrices();
		print_r($ticker);
		$this->assertIsArray($ticker);
	}

	public function testBalancesArePositive() {
		$balances = $this::$binanceController->balances();
		$array_search = array_search('0', array_column($balances, 'qty'));
		print_r($balances);
		$this->assertFalse($array_search);
	}

}
