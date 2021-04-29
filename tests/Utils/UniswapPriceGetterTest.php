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
		$cliqPrice = $uniswap->getUsers('skylab4004');
		self::assertNull($cliqPrice);

	}

}
