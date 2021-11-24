<?php

namespace App\Http\Controllers;

use App\Models\PortfolioCoin;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Session;
use View;

/**
 * Class PortfolioCoinController
 * @package App\Http\Controllers
 */
class PortfolioCoinController extends Controller {

	private array $coinsToSkip;

	function __construct() {
		$this->coinsToSkip = [];
	}

	//$table->id();
	//$table->string('gecko_id')->unique(); // "id": "dexfin",
	//$table->string('symbol')->unique(); // "symbol": "dxf",
	//$table->string('gecko_name'); //    "name": "Dexfin"
	//$table->json('platforms')->nullable();
	//$table->string('cg_url', 2048)->nullable();
	//$table->string('trade_url', 2048)->nullable();
	//$table->string('img_url', 2048)->nullable();
	//$table->string('chart_url', 2048)->nullable();
	//$table->integer('price_source')->nullable(); // null,0 -> coingecko; 1 -> uniswap;
	//$table->timestamps();

	public function index() {
		$portfolioCoins = PortfolioCoin::all();

		return View::make('portfolio-coins.index')->with('portfolioCoins', $portfolioCoins);
	}

	public function create() {
		return view('portfolio-coins.create');
	}

	public function store(Request $request) {
		$request->validate([
			'gecko_id' => 'required',
		]);

		$geckoId = $request->input('gecko_id');

		$this->addNewCoinByCoinGeckoId($geckoId);

		return redirect()->route('portfolio-coins.index')
			->with('success', 'Portfolio Coin created successfully.');
	}

	public function show($id) {
		$coin = PortfolioCoin::find($id);

		return View::make('portfolio-coins.show')
			->with('coin', $coin);
	}

	public function edit(PortfolioCoin  $portfolio_coin) {
//		$coin = PortfolioCoin::find($id);

		return view('portfolio-coins.edit', compact('portfolio_coin'));
//		return View::make('portfolio-coins.edit')
//			->with('coin', $coin);
	}

	public function update(Request $request, $id) {
		$request->validate([
			'gecko_id'   => 'required',
			'symbol'     => 'required',
			'gecko_name' => 'required',
		]);
		$coin = PortfolioCoin::find($id);
		$coin->gecko_id = $request->input('gecko_id');
		$coin->symbol = $request->input('symbol');
		$coin->gecko_name = $request->input('gecko_name');
		$coin->platforms = $request->input('platforms');
		$coin->cg_url = $request->input('cg_url');
		$coin->trade_url = $request->input('trade_url');
		$coin->img_url = $request->input('img_url');
		$coin->chart_url = $request->input('chart_url');
		$coin->price_source = $request->input('price_source');
		$coin->save();
		return redirect()->route('portfolio-coins.index')
			->with('success', "Project updated successfully");
	}

	public function destroy($id) {
		$coin = PortfolioCoin::find($id);
		$coin->delete();

		// redirect
		Session::flash('message', 'Successfully deleted the portfolio coin!');

		return Redirect::to('portfolio-coins');
		//
	}

	// ---------------

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
		$coinEntry->price_source = 1;

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

		return strtolower(implode(",", $coinsSymbols));
	}

	public function getSymbolToCoinGeckoIdMapping() {
		$symbolsAndIdsFromDb = PortfolioCoin::select('symbol', 'gecko_id')->get()->toArray();

		return array_column($symbolsAndIdsFromDb, 'gecko_id', 'symbol');
	}


}
