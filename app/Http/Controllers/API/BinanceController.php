<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Binance\API;

class BinanceController extends Controller {

	private $api;
	protected $apiKey = null;
	protected $apiSecret = null;

	public function __construct() {
		$this->apiKey = Secret::$BINANCE_API_KEY;
		$this->apiSecret = Secret::$BINANCE_API_SECRET;
		$this->api = new API($this->apiKey, $this->apiSecret);
	}

	public function show() {
		$ticker = $this->api->prices(); // Make sure you have an updated ticker object for this to work
		$balances = $this->api->balances($ticker);

		return $balances;
	}

	public function currentPrices() {
		return $this->api->prices();
	}

	public function currentPrice($symbol) {
		return $this->api->price($symbol);
	}

	public function balances() {
		$ticker = $this->api->prices();
		$balances = $this->api->balances($ticker);
		$btcTotal = BinanceController::totalAssetsValueInBtc($balances);
		$ret = array();

		foreach ($balances as $asset => $balance) {
			$qty = Utils::formattedNumber($balance['available'] + $balance['onOrder']);
			if ($qty == 0) continue;
			$assetValueInBtc = $balance['btcTotal'];
			$assetAvailable = $balance['available'];
			$assetOnOrder = $balance['onOrder'];
			$assetValueInUsdt = Utils::formattedNumber($assetValueInBtc * $ticker['BTCUSDT']);
			$assetPercent = Utils::formattedNumber($assetValueInBtc / $btcTotal, 6);

			array_push($ret, ['asset' => $asset, 'qty' => $qty, 'assetValueInBtc' => $assetValueInBtc, 'assetValueInUsdt' => $assetValueInUsdt, 'assetPercent' => $assetPercent, 'available' => $assetAvailable, 'onOrder' => $assetOnOrder]);
		}

		uasort($ret, function($opA, $opB) {
			return $opB['assetPercent'] <=> $opA['assetPercent'];
		});

		return $ret;
	}

	public function getAllCoins() {
		return $this->api->allCoins();
	}

	public function getTodaysProfitAndLossInUsd() {
		$yesterdayInMillis = Utils::nowMillis() - 1000 * 60 * 60 * 24;
		$currentTimeInMillis = Utils::nowMillis();
		$yesterdayAccountSnapShot = $this->getAccountSnapshotsSpot($yesterdayInMillis, $currentTimeInMillis);
		$yesterdayAssetValueInUsdOnClosing = $yesterdayAccountSnapShot["snapshots"][0]["assetsValueInUsdt"]; // "assetsValueInUsdt"
		$portfolioPieChartData = $this->getPortfolioPieChartData();
		$currentAssetValueInBtc = Utils::formattedNumber($portfolioPieChartData["total"]["btcTotal"]);
		$currentPriceOfBtcInUsd = Utils::formattedNumber($this->currentPrice("BTCUSDT"));
		$currentAssetValueInUsd = $currentAssetValueInBtc * $currentPriceOfBtcInUsd;
		$todaysPNL = $currentAssetValueInUsd - $yesterdayAssetValueInUsdOnClosing;
		print("yesterdayAssetValueInUsdOnClosing=" . $yesterdayAssetValueInUsdOnClosing . "\tcurrentAssetValueInBtc=" . $currentAssetValueInBtc . "\tcurrentPriceOfBtcInUsd=" . $currentPriceOfBtcInUsd . "\tcurrentAssetValueInUsd=" . $currentAssetValueInUsd . "\ttodaysPNL=" . $todaysPNL);

		return $todaysPNL;
	}

	public function getYesterdaysPortfolioSnapshot() {
		$_24hoursInMillis = 1000 * 60 * 60 * 24;
		$now = round(microtime(true) * 1000);
		$yesterday = $now - $_24hoursInMillis - 1000 * 60 * 60 * 2;
		return $this->getAccountSnapshotsSpot($yesterday, $now);
	}

	public function getAccountSnapshotsSpot(string $startDate = null, string $endDate = null) {
//		print("startDate=" . Utils::millisToTimestamp($startDate) . "\tendDate=" . Utils::millisToTimestamp($endDate)."\r\n");

		$accountSnapshotsSpot = $this->api->accountSnapshotsSpot($startDate, $endDate);

		foreach ($accountSnapshotsSpot as $accountSnapshot) {
			$day = Utils::formattedNumber($accountSnapshot['updateTime'], 0);
			$totalAssetOfBtc = $accountSnapshot['data']['totalAssetOfBtc'];
			$millis = $accountSnapshot['updateTime'];
			$assetsValueInUsdt = $totalAssetOfBtc * Utils::formattedNumber($this->getHistoricalPrice("BTCUSDT", $millis), 2);
			$snapshots[] = ['updateTime' => $day, 'totalAssetOfBtc' => $totalAssetOfBtc, 'assetsValueInUsdt' => $assetsValueInUsdt];
		}

		return ['snapshots' => $snapshots];
	}

	public function exchangeInfo() {
		$exchangeInfo = $this->api->exchangeInfo();

		return $exchangeInfo;
	}

	public function getAccountData() {
		return $this->api->account();
	}

	public function getPortfolioSnapshot () {
		return $this->api->portfolioSnapshot();
	}

	/**
	 * getPortfolioPieChartData returns the data for Binance portfolio pie chart
	 * @returns array like [['coinSymbol'=>'ETH', 'percent' => 50.0, 'btcValue' => '0.211', 'available' => '1.22'], [...]]
	 * @throws \Exception
	 */
	public function getPortfolioPieChartData() {

		$balances = $this->balances();

		$allAssetsValueInBtc = 0;

		foreach ($balances as $coin => $balance) {
			$allAssetsValueInBtc += $balance['assetValueInBtc'];
		}
		unset($coin);
		unset($balance);

		$ret = [];
		$btcTotal = 0;
		$percentTotal = 0;

		foreach ($balances as $coin => $balance) {
			$coinQty = Utils::formattedNumber($balance['qty']);
			$coinAvailable = Utils::formattedNumber($balance['available']);
			$coinOnOrder = Utils::formattedNumber($balance['onOrder']);
			$coinBtcTotal = Utils::formattedNumber($balance['assetValueInBtc']);
			$coinPercent = Utils::formattedNumber($coinBtcTotal / $allAssetsValueInBtc, 2);


			$ret[] = ['coin' => $coin, 'qty' => $coinQty, 'available' => $coinAvailable, 'onOrder' => $coinOnOrder, 'btcTotal' => $coinBtcTotal, 'percent' => $coinPercent];
			$btcTotal += $coinBtcTotal;
			$percentTotal += $coinPercent;
		}
		unset($coin);
		unset($balance);

		usort($ret, function($item1, $item2) {
			return $item2['btcTotal'] <=> $item1['btcTotal'];
		});

		$total = ['coin' => "TOTAL", 'qty' => "N/A", 'available' => "N/A", 'onOrder' => "N/A", 'btcTotal' => $btcTotal, 'percent' => Utils::formattedNumber($percentTotal, 2)];

		return ['balances' => $ret, 'total' => $total];
	}

	public function getPortfolioPieChartTotals() {
		$portfolioPieChartData = $this->getPortfolioPieChartData();

		$percent = 0.0;
		$btcValue = 0.0;

		$balances = $portfolioPieChartData['balances'];
//		dd($balances);
		foreach ($balances as $balance) {
			$percent += $balance['percent'];
			$btcValue += $balance['btcTotal'];
		}

		return ['coin' => 'TOTAL', 'percent' => $percent, 'btcValue' => $btcValue];
	}

	/**
	 * averagePurchasePrice Gets an average purchase price of given currency.
	 * Eg. for "BTC" symbol - average purchase price of BTC is returned.
	 * (how many USDTs were spent for 1 BTC)
	 *
	 * @param $symbol eg. "coin"
	 * @return array TODO
	 * @throws \Exception
	 */
	public function getAveragePurchasePrice($coin, $baseCoin) {
		$coinTradingHistory = $this->getCoinTradingHistory($coin);

		$quoteQtySum = 0.0;
		$qtySum = 0.0;

		foreach ($coinTradingHistory as $key => $trade) {
			$isBuyTrade = (boolean) $trade['isBuyer'] == 1;
			$tradeSymbol = $trade['symbol'];
			$secondCoinNameFromTrade = $this->getSecondCoinNameFromSymbol($tradeSymbol, $coin);
			$price = (float) $trade['price'];
			$qty = (float) $trade['qty'];
			$quoteQty = (float) $trade['quoteQty'];
			$commission = (float) $trade['commission'];
			$commissionAsset = $trade['commissionAsset'];
			$time = $trade['time'];

//			print(($isBuyTrade ? "BUY\t" : "SELL\t") . " " . $qty . " " . $coin . " for " . $quoteQty . $secondCoinNameFromTrade . "\r\n");
//			print("trade details: " . json_encode($trade) . "\r\n");


			$boolean = ($tradeSymbol == $coin . $baseCoin) ? true : false;

			print("$tradeSymbol=" . $tradeSymbol . "\t coin=" . $coin . "\tbaseCoin=" . $baseCoin . "\tboolean=" . $boolean . "\r\n");

			if ($boolean) {

				if ($isBuyTrade) {
					$quoteQtySum += (float) $trade['quoteQty']; // po ile kupiono
					$qtySum += (float) $trade['qty']; // ile kupiono
				} else {
					$quoteQtySum -= (float) $trade['quoteQty']; // po ile sprzedano
					$qtySum -= (float) $trade['qty']; // ile sprzedano
				}
			} else {
				// non native trade
//				print("NON NATIVE TRADE!\r\n");

				// tu trzeba sprawdzić ile (qtySum) można było kupić $coina za $secondCoinNameFromTrade
				// w tym momencie 1 $coin kosztował $price $secondCoina
				// w tym momencie kupiłem $qty $coinów
				// za $qty $coinów musiałem zapłacić w momencie $time $price*$qty secondCoina
				// równowartość $price*$qty secondCoina w $baseCoin w momencie $time to:
				$historicalPrice = $this->getHistoricalPrice($coin . $secondCoinNameFromTrade, $time);
				$f = $qty * $historicalPrice;
				print("zaplacono " . $f . " " . $secondCoinNameFromTrade . " za " . $qty . " " . $coin . " (historicalPrice=" . $historicalPrice . ")\r\n");

				if ($isBuyTrade) {
					$quoteQtySum += (float) $f; // po ile kupiono
					$qtySum += (float) $trade['qty']; // ile kupiono

				} else {

					$quoteQtySum -= (float) $f; // po ile sprzedano
					$qtySum -= (float) $trade['qty']; // ile sprzedano
				}

			}

		}
		unset($trade); // break the reference with the last element

		return $quoteQtySum / $qtySum;
	}

	public function getSecondCoinNameFromSymbol($coinPair, $coin) {
		return str_replace($coin, "", $coinPair);
	}


	public function getCoinTradingHistory($coin) {
		$symbolsForCoin = $this->getSymbolsForCoin($coin);
		$merged = [];
		foreach ($symbolsForCoin as $symbol) {
			$purchaseHistory = $this->getPurchaseHistory($symbol);
			if ( ! empty($purchaseHistory)) {
				$merged = array_merge($merged, $purchaseHistory);
			}
		}

		return $merged;
	}

	public function getPurchaseHistory($symbol) {
		$history = $this->api->history($symbol);

		usort($history, function($a, $b) {
			return $b['time'] <=> $a['time'];
		});

		return $history;
	}

	public function getSymbolsForCoin($coin) {
		$allSymbols = $this->getAllSymbols();
		$array_filtered = array_filter($allSymbols, function($var) use ($coin) {
			return preg_match("/$coin/i", $var);
		});
		$array_values = array_values($array_filtered);

		return $array_values;
	}

	public function getHistoricalPrice($symbol, $timeInMillis) {
//		print("timeInMillis before changes=" . date("d-m-Y H:i:s", $timeInMillis / 1000) . "\t");
		$timeInMillis -= 3600000;
		$candlesticks = $this->api->candlesticks($symbol, "1m", 1, $timeInMillis - 60000, $timeInMillis);
		$close = (float) array_column($candlesticks, 'close')[0];
		$open = (float) array_column($candlesticks, 'open')[0];
		$closeTime = (float) array_column($candlesticks, 'closeTime')[0];

		$ret = $close; //($open + $close) / 2;
//		print("getHistoricalPrice: " . $symbol . " at " . date("d-m-Y H:i:s", $timeInMillis/1000) . ": " . $ret." (closeTime=".date("d-m-Y H:i:s", $closeTime/1000)."<br/>");

		return Utils::formattedNumber($ret, 16);
	}

	public function getAllSymbols() {
		$exchangeInfo = $this->api->exchangeInfo();
		$symbols_array = $exchangeInfo["symbols"];

		return array_keys($symbols_array);
	}

	public function myTrades() {
		$ticker = $this->api->prices();
		$this->api->history();
	}

	/**
	 * @param array $balances
	 * @param string $btcTotal
	 * @return mixed
	 */
	public static function totalAssetsValueInBtc(array $balances) {
		$btcTotal = 0.00;
		foreach ($balances as $asset => $balance) {
			$coinBtcTotal = Utils::formattedNumber($balance['btcTotal']);
			$btcTotal += $coinBtcTotal;
		}

		return $btcTotal;
	}

}
