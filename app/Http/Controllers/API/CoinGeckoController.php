<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;

class CoinGeckoController extends Controller {

	private static $favoriteCoins = "bitcoin,ethereum,rubic,deficliq,cosmos,lto-network,morpheus-labs,thorchain,polkacover,ferrum-network,apy-finance";
	private static $vsCurrencies = "btc,eth,usd,pln";

	public function favoriteCoinPrices() {
		$api = new CoinGeckoClient();
		$data = $api->simple()->getPrice($this::$favoriteCoins, $this::$vsCurrencies);
		$btcPriceInPln = $data["bitcoin"]["pln"];
		$ethPriceInPln = $data["ethereum"]["pln"];
		$btcPriceInEth = $data["bitcoin"]["eth"];
		$ethPriceInBtc = $data["ethereum"]["btc"];
		$btcPriceInUsd = $data["bitcoin"]["usd"];
		$ethPriceInUsd = $data["ethereum"]["usd"];

		return ["btcPriceInPln" => $btcPriceInPln, "ethPriceInPln" => $ethPriceInPln,
				"btcPriceInEth" => $btcPriceInEth, "ethPriceInBtc" => $ethPriceInBtc,
				"btcPriceInUsd" => $btcPriceInUsd, "ethPriceInUsd" => $ethPriceInUsd];
	}

}