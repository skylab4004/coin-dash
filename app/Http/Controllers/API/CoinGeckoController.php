<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;

class CoinGeckoController extends Controller {

	private static $favoriteCoins = "bitcoin,ethereum,rubic,deficliq,cosmos,lto-network,morpheus-labs,thorchain,polkacover,ferrum-network,apy-finance,chartex,vidya,yeld-finance,ethverse,nftlootbox,azuki,alpaca,pylon-finance";
	private static $vsCurrencies = "btc,eth,usd,pln";

	public function favoriteCoinPrices() {
		$api = new CoinGeckoClient();
		$data = $api->simple()->getPrice($this::$favoriteCoins, $this::$vsCurrencies);
		return $data;
	}

}