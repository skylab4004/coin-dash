<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;

class CoinGeckoController extends Controller {

	private static $favoriteCoins = "bitcoin,ethereum,rubic,deficliq,cosmos,lto-network,morpheus-labs,thorchain,polkacover,ferrum-network,apy-finance,chartex,vidya,yeld-finance,ethverse,nftlootbox,azuki,alpaca,pylon-finance,kylin-network,chainx,tether,unslashed-finance,utrin,trustswap,ripple,superfarm,bilaxy-token,auric-network,swipe,siacoin,the-sandbox,sentinel-group,antimatter,binancecoin,kickpad,orakuru,mist,revv,illuvium";
	private static $vsCurrencies = "btc,eth,usd,pln";

	public function favoriteCoinPrices() {
		$api = new CoinGeckoClient();
		return $api->simple()->getPrice($this::$favoriteCoins, $this::$vsCurrencies);
	}

}