<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;

class CoinGeckoController extends Controller {

	private $favoriteCoins = "bitcoin,ethereum,rubic,deficliq,cosmos,lto-network,morpheus-labs,thorchain,polkacover,ferrum-network,apy-finance";
	private $vsCurrencies = "btc,eth,usd,pln";

	public function btcPriceInPln() {
		$api = new CoinGeckoClient();
		$data = $api->simple()->getPrice("bitcoin", "pln");
		return $data["bitcoin"]["pln"];
	}

	public function pricesOfFavoriteCoins() {
		$api = new CoinGeckoClient();
		$prices = $api->simple()->getPrice($this->favoriteCoins, $this->vsCurrencies);

		foreach ($prices as $asset => &$price) {
			$price["btc"] = Utils::formattedNumber($price["btc"]);
			$price["usd"] = Utils::formattedNumber($price["usd"]);
			$price["pln"] = Utils::formattedNumber($price["pln"]);
		}

		return $prices;
	}

}