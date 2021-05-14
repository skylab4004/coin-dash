<?php

namespace App\Http\Controllers;

use App\Models\PortfolioCoin;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Class PortfolioCoinController
 * @package App\Http\Controllers
 */
class PortfolioCoinController extends Controller {

	private array $coinsToSkip;

	function __construct() {
		$this->coinsToSkip = [];
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
				Log::error("Can't find matching coin with symbol {$tickerSymbol} on CoinGecko!");
				return false;
			case 1:
				$success = $this->addNewCoinByCoinGeckoId($matchingCoins[0]['id']);
				if ($success) {
					Log::info("Coin '{$matchingCoins[0]['id']}' added successfully");
				}

				return $success;
			default:
				$array_column = array_column($matchingCoins, 'id');
				$array_column = implode(", ", $array_column);
				Log::error("Found more than one matching coin with symbol '{$tickerSymbol}' on CoinGecko with IDs: {$array_column}. Manual help is necessary! Execute: (new App\\Http\Controllers\\PortfolioCoinController())->addNewCoinByCoinGeckoId('COINGECKO_ID');");
				return false;

		}
	}

	public function addNewCoinByCoinGeckoId(string $coinGeckoId) {
		$coinGeckoId = strtolower($coinGeckoId);
		$api = new CoinGeckoClient();
		$params = [
			'localization'   => 'false',
			'tickers'        => 'false',
			'market_data'    => 'false',
			'sparkline'      => 'false',
			'community_data' => 'false',
			'developer_data' => 'false'
		];
		$coinDetails = $api->coins()->getCoin($coinGeckoId, $params);

		return $this->saveCoinToDb($coinDetails);
	}

	/**
	 * @param array $coinDetails
	 * @return bool
	 */
	private function saveCoinToDb(array $coinDetails): bool {
		$coinEntry = new PortfolioCoin();
		$coinEntry->gecko_id = $coinDetails['id'];
		$coinEntry->symbol = $coinDetails['symbol'];
		$coinEntry->gecko_name = $coinDetails['name'];
		$coinEntry->platforms = json_encode($coinDetails['platforms']);
		$coinEntry->cg_url = "https://www.coingecko.com/en/coins/{$coinDetails['symbol']}";
		$coinEntry->trade_url = null; // todo
		$coinEntry->img_url = $coinDetails['image']['thumb'];
		$coinEntry->chart_url = null; // todo
		$coinEntry->price_source = 0;

		return $coinEntry->save();
	}

	public function returnCoinsMissingInDb(array $symbols) {
		$symbols = array_map('strtolower', $symbols);
		$symbolsFromDb = PortfolioCoin::select('symbol')->distinct()->get()->toArray();
		$symbolsFromDb = array_column($symbolsFromDb, 'symbol');
		$symbolsFromDb = array_map('strtolower', $symbolsFromDb);

		return array_diff($symbols, $symbolsFromDb);
	}


	/**
	 * @param array $coinsMissingInDb
	 * @return bool
	 * @throws Exception
	 */
	public function addMissingCoinsToDb(array $coinsMissingInDb): bool {
		$coinsAdded = 0;
		foreach ($coinsMissingInDb as $coinToAdd) {
			if ( ! in_array($coinToAdd, $this->coinsToSkip)) {
				$addedSuccessful = $this->addNewCoin($coinToAdd);
				if ( ! $addedSuccessful) {
					$this->coinsToSkip[] = $coinToAdd;
				} else {
					$coinsAdded++;
				}
			}
		}

		return $coinsAdded > 0;
	}

	public function getCoinGeckoIdsForPortfolioCoins() {
		$coinsSymbols = PortfolioCoin::select('gecko_id')->distinct()->get()->toArray();
		$coinsSymbols = array_column($coinsSymbols, 'gecko_id');
		return strtolower(implode (",", $coinsSymbols));
	}

	public function getSymbolToCoinGeckoIdMapping() {
		$symbolsAndIdsFromDb = PortfolioCoin::select('symbol', 'gecko_id')->get()->toArray();
		return array_column($symbolsAndIdsFromDb, 'gecko_id', 'symbol');
	}


}
