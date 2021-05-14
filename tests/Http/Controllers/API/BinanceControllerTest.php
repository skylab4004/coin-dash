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
		self::assertIsArray($ticker);
	}

	public function testBalances() {
		$balances = $this::$binanceController->balances();
		$array_search = array_search('0', array_column($balances, 'qty'));
		self::assertFalse($array_search);
	}

	public function testBalancesAssetNames() {
		$balances = $this::$binanceController->balances();
		$assetSymbols = array_column($balances, 'asset');
		self::assertIsArray($assetSymbols);
	}

}
