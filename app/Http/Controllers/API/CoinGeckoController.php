<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PortfolioCoinController;
use App\Models\GeckoDump;
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

	public function saveMarkets(int $startPage = 1, int $endPage = 1, int $pageSize = 100, string $vsCurrency = "USD") {
		$api = new CoinGeckoClient();

		for ($i = $startPage; $i <= $endPage; $i++) {
			$params = [
				"page"                    => "{$i}",
				"order"                   => "market_cap_desc",
				"per_page"                => "{$pageSize}",
				"price_change_percentage" => "1h,24h,7d,30d,200d"
			];

			$coinMarkets = $api->coins()->getMarkets("USD", $params);
			foreach ($coinMarkets as $coinMarketData) {

				echo "page {$i}\tmarket_cap_rank#{$coinMarketData['market_cap_rank']}:\tAdding {$coinMarketData['id']}:\t";
				try {
					$row = new GeckoDump();
					$row->id = $coinMarketData['id'];
					$row->symbol = $coinMarketData['symbol'];
					$row->name = $coinMarketData['name'];
					$row->image = $coinMarketData['image'];
					$row->current_price = $coinMarketData['current_price'];
					$row->market_cap = $coinMarketData['market_cap'];
					$row->market_cap_rank = $coinMarketData['market_cap_rank'];
					$row->fully_diluted_valuation = $coinMarketData['fully_diluted_valuation'];
					$row->total_volume = $coinMarketData['total_volume'];
					$row->high_24h = $coinMarketData['high_24h'];
					$row->low_24h = $coinMarketData['low_24h'];
					$row->price_change_24h = $coinMarketData['price_change_24h'];
					$row->price_change_percentage_24h = $coinMarketData['price_change_percentage_24h'];
					$row->market_cap_change_24h = $coinMarketData['market_cap_change_24h'];
					$row->market_cap_change_percentage_24h = $coinMarketData['market_cap_change_percentage_24h'];
					$row->circulating_supply = $coinMarketData['circulating_supply'];
					$row->total_supply = $coinMarketData['total_supply'];
					$row->max_supply = $coinMarketData['max_supply'];
					$row->ath = $coinMarketData['ath'];
					$row->ath_change_percentage = $coinMarketData['ath_change_percentage'];
					$row->ath_date = Utils::removeIsoTimestampMarks($coinMarketData['ath_date']);
					$row->atl = $coinMarketData['atl'];
					$row->atl_change_percentage = $coinMarketData['atl_change_percentage'];
					$row->atl_date = Utils::removeIsoTimestampMarks($coinMarketData['atl_date']);
					$row->roi = Utils::extractRoiPercentage($coinMarketData);
					$row->last_updated = Utils::removeIsoTimestampMarks($coinMarketData['last_updated']);
					$row->save();
					echo "OK\r\n";
				} catch (Exception $e) {
					echo "ERROR\r\n";
					echo $e->getMessage();
				}
			}

		}

	}


	public function getCoins() {
		$api = new CoinGeckoClient();
		for ($i = 1; $i <= 20; $i++) {
			$params = [
				"page"                    => "{$i}",
				"order"                   => "market_cap_desc",
				"per_page"                => "100",
				"price_change_percentage" => "1h,24h,7d,30d,200d"
			];
			$coinMarkets = $api->coins()->getMarkets("USD", $params);

			$j = 0;
			foreach ($coinMarkets as $coinMarketData) {
				$j++;
//              if ($j <= 210) {
//                  continue;
//              }
				try {

					echo "page {$i} coin# {$j}. Adding {$coinMarketData['id']}: ";

					$params2 = [
						"tickers"        => "false",
						"market_data"    => "false",
						"community_data" => "true",
						"developer_data" => "true",
						"sparkline"      => "false",
						"localization"   => "false"
					];
					$coin2 = $api->coins()->getCoin($coinMarketData['id'], $params2);

					$row = new CoinMarketData();
					$row->id = $coinMarketData['id'];
					$row->symbol = $coinMarketData['symbol'];
					$row->name = $coinMarketData['name'];
					$row->image = $coinMarketData['image'];
					$row->current_price = $coinMarketData['current_price'];
					$row->market_cap = $coinMarketData['market_cap'];
					$row->market_cap_rank = $coinMarketData['market_cap_rank'];
					$row->fully_diluted_valuation = $coinMarketData['fully_diluted_valuation'];
					$row->total_volume = $coinMarketData['total_volume'];
					$row->high_24h = $coinMarketData['high_24h'];
					$row->low_24h = $coinMarketData['low_24h'];
					$row->price_change_24h = $coinMarketData['price_change_24h'];
					$row->price_change_percentage_24h = $coinMarketData['price_change_percentage_24h'];
					$row->market_cap_change_24h = $coinMarketData['market_cap_change_24h'];
					$row->market_cap_change_percentage_24h = $coinMarketData['market_cap_change_percentage_24h'];
					$row->circulating_supply = $coinMarketData['circulating_supply'];
					$row->total_supply = $coinMarketData['total_supply'];
					$row->max_supply = $coinMarketData['max_supply'];
					$row->ath = $coinMarketData['ath'];
					$row->ath_change_percentage = $coinMarketData['ath_change_percentage'];
					$row->ath_date = Utils::removeIsoTimestampMarks($coinMarketData['ath_date']);
					$row->atl = $coinMarketData['atl'];
					$row->atl_change_percentage = $coinMarketData['atl_change_percentage'];
					$row->atl_date = Utils::removeIsoTimestampMarks($coinMarketData['atl_date']);
					$row->roi = Utils::extractRoiPercentage($coinMarketData);
					$row->last_updated = Utils::removeIsoTimestampMarks($coinMarketData['last_updated']);

					$row->asset_platform_id = $coin2['asset_platform_id'];
					$row->block_time_in_minutes = $coin2['block_time_in_minutes'];
					$row->public_notice = $coin2['public_notice'];
					$row->additional_notices = substr(implode($coin2['additional_notices']), 0, 4096);
					$row->description = substr(implode($coin2['description']), 0, 4096);
					$row->genesis_date = $coin2['genesis_date'];
					$row->sentiment_votes_up_percentage = $coin2['sentiment_votes_up_percentage'];
					$row->sentiment_votes_down_percentage = $coin2['sentiment_votes_down_percentage'];
					$row->coingecko_rank = $coin2['coingecko_rank'];
					$row->coingecko_score = $coin2['coingecko_score'];
					$row->developer_score = $coin2['developer_score'];
					$row->community_score = $coin2['community_score'];
					$row->liquidity_score = $coin2['liquidity_score'];
					$row->public_interest_score = $coin2['public_interest_score'];
					$row->save();
					echo "OK\r\n";
				} catch (Exception $e) {
					echo $e->getMessage();
				}
				sleep(1);
			}
		}

		return "FIISHED";
	}

}