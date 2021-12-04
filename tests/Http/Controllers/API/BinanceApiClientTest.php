<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\BinanceApiClient;
use Config;
use PHPUnit\Framework\TestCase;

class BinanceApiClientTest extends TestCase {

	private static $binanceController;

	public static function setUpBeforeClass(): void {
		self::$binanceController = new BinanceApiClient();
	}

	public function testTicker() {
		$ticker = $this::$binanceController->ticker();
		self::assertIsArray($ticker);
	}

	public function testgetPurchaseHistory() {
		$purchaseHistory = $this::$binanceController->getPurchaseHistory("");
		var_dump($purchaseHistory);

	}

	public function testPricesInUsdt() {
		$ticker = $this::$binanceController->pricesInUsdt(['btc', 'eth', 'rune', 'luna', 'null']);
		dd($ticker);
		self::assertIsArray($ticker);
	}

	public function testCurrentPrice() {
		$price = $this::$binanceController->currentPrice("BTCUSDT");
		print($price);
		self::assertIsNumeric($price);
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
