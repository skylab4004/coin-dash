<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PortfolioCoinController;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Exception;

class CoinGeckoController extends Controller {

	private static $favoriteCoins = "bitcoin,ethereum,rubic,deficliq,cosmos,lto-network,morpheus-labs,thorchain,polkacover,ferrum-network,apy-finance,chartex,vidya,yeld-finance,ethverse,nftlootbox,azuki,alpaca,pylon-finance,kylin-network,chainx,tether,unslashed-finance,utrin,trustswap,ripple,superfarm,bilaxy-token,auric-network,swipe,siacoin,the-sandbox,sentinel-group,antimatter,binancecoin,kickpad,orakuru,mist,revv,illuvium,oction,matic-network,kusama,civic,komodo,elrond-erd-2,crypto-com-chain,dent,ethereum-classic,reef-finance,nexus,steem,sota-finance";
	private static $vsCurrencies = "btc,eth,usd,pln";

	public function portfolioCoinsPrices() {
		$portfolioCoinsSymbols = (new PortfolioCoinController())->getCoinGeckoIdsForPortfolioCoins();
		$api = new CoinGeckoClient();
		return $api->simple()->getPrice($portfolioCoinsSymbols, $this::$vsCurrencies);
//		return $api->simple()->getPrice($this::$favoriteCoins, $this::$vsCurrencies);
	}


}