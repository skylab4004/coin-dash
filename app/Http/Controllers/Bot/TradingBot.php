<?php namespace App\Http\Controllers\Bot;

class TradingBot {

	private static function buy($token1, $token2, int $amount) {
	}

	private static function getPrice(string $token1, string $token2) {
		return 0;
	}

	public function startBot(float $maxBuyPrice, $token1, $token2) {


		$currentPrice = $this::getPrice($token1, $token2);
		if ($currentPrice<=$maxBuyPrice) {
			$this::buy($token1, $token2, 1000);
		}


	}

}