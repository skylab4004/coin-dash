<?php namespace Tests\Utils;

use App\Utils\UniswapPriceGetter;
use PHPUnit\Framework\TestCase;

class UniswapPriceGetterTest extends TestCase {

	public function testGetCliqPrice() {
		$uniswap = new UniswapPriceGetter();
		$cliqPrice = $uniswap->getCliqPrice();
		print_r($cliqPrice);
		self::assertNull($cliqPrice);
	}


	public function testGetUsers() {
		$uniswap = new UniswapPriceGetter();
		$cliqPrice = $uniswap->get2021_05_19_121405_create_static_portfolio_coins_table.phpUsers('skylab4004');

		self::assertNull($cliqPrice);
	}

	public function testUniswapPrice() {
		$uniswap = new UniswapPriceGetter();
		$results = $uniswap->uniswapPrice("0x3431f91b3a388115f00c5ba9fdb899851d005fb5");
		var_dump($results);
		self::assertIsNumeric($results);
	}

	public function testUniswapPrice2() {
		$uniswap = new UniswapPriceGetter();
		$results = $uniswap->uniswapPrice2();
		dd($results);
		self::assertNull(null);
	}
}
