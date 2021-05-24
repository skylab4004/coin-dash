<?php namespace Tests\Utils;

use App\Utils\UniswapPriceGetter;
use PHPUnit\Framework\TestCase;

class UniswapPriceGetterTest extends TestCase {

	public function testUniswapPrice() {
		$uniswap = new UniswapPriceGetter();
		$results = $uniswap->uniswapPrice("0x3431f91b3a388115f00c5ba9fdb899851d005fb5");
		var_dump($results);
		self::assertIsNumeric($results);
	}

}
