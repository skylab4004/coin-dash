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
		$btcTotal = 0.0;
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


	public function getPurchaseHistory($symbol) {
		$history = $this->api->history($symbol);

		usort($history, function($a, $b) {
			return $b['time'] <=> $a['time'];
		});

		return $history;
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
