<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CoinGeckoCoin;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Exception;

class CoinGeckoController extends Controller {

	private static $favoriteCoins = "bitcoin,ethereum,rubic,deficliq,cosmos,lto-network,morpheus-labs,thorchain,polkacover,ferrum-network,apy-finance,chartex,vidya,yeld-finance,ethverse,nftlootbox,azuki,alpaca,pylon-finance,kylin-network,chainx,tether,unslashed-finance,utrin,trustswap,ripple,superfarm,bilaxy-token,auric-network,swipe,siacoin,the-sandbox,sentinel-group,antimatter,binancecoin,kickpad,orakuru,mist,revv,illuvium,oction,matic-network,kusama,civic,komodo,elrond-erd-2,crypto-com-chain,dent,ethereum-classic,reef-finance,nexus,steem,sota-finance";
	private static $vsCurrencies = "btc,eth,usd,pln";

	public function favoriteCoinPrices() {
		$api = new CoinGeckoClient();
		return $api->simple()->getPrice($this::$favoriteCoins, $this::$vsCurrencies);
	}

	public function addNewCoin(string $tickerSymbol) {
		$tickerSymbol = strtolower($tickerSymbol);

		$api = new CoinGeckoClient();
		$coinsList = $api->coins()->getList();

		$matchingCoins = [];
		foreach ($coinsList as $coin) {
			if (strcasecmp($coin['symbol'], $tickerSymbol) == 0) {
				$matchingCoins[] = $coin;
			}
		}

		switch (count($matchingCoins)) {
			case 0:
				throw new Exception("Can't find matching coin with symbol {$tickerSymbol} on CoinGecko!");
			case 1:
				$params = [];
				$coinDetails = $api->coins()->getCoin($matchingCoins[0]['id'], $params);

				//			$table->string('trade_url')->nullable();
				//			$table->string('img_url')->nullable();
				//			$table->string('chart_url')->nullable();
				$coinEntry = new CoinGeckoCoin();
				$coinEntry->gecko_id = $matchingCoins[0]['id'];
				$coinEntry->gecko_symbol = $matchingCoins[0]['symbol'];
				$coinEntry->gecko_name = $matchingCoins[0]['name'];
				$coinEntry->platform =  array_key_first($coinDetails['platforms']); // todo
				$coinEntry->contract_address = null; // todo
				$coinEntry->cg_url = "https://www.coingecko.com/en/coins/{$matchingCoins[0]['symbol']}";
				$coinEntry->trade_url = null; // todo
				$coinEntry->img_url = $matchingCoins[0]['image']['thumb'];
				$coinEntry->chart_url = null; // todo
				return $coinEntry->save();
			default:
				throw new Exception("Found more than one matching coin with symbol {$tickerSymbol} on CoinGecko. Manual help is necessary!");

		}

	}

}