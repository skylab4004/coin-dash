<?php

namespace App\Http\Controllers;

use App\Models\StaticPortfolioCoin;

class StaticPortfolioCoinController extends Controller {

	public function getStaticPortfolioCoins() {
		$staticPortfolioCoins = StaticPortfolioCoin::select(['symbol', 'quantity'])->get()->toArray();
		return $staticPortfolioCoins;
	}
}
