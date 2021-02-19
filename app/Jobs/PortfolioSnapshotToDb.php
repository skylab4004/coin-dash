<?php

namespace App\Jobs;

use App\Http\Controllers\API\BinanceController;
use App\Http\Controllers\API\CoinGeckoController;
use App\Http\Controllers\API\Utils;
use App\Models\PortfolioSnapshot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PortfolioSnapshotToDb implements ShouldQueue {

	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct() {
		//
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle() {

		$updateTime = Utils::currentTimeInMillis();

		$coinGeckoApi = new CoinGeckoController();
		$favoriteCoinPrices = $coinGeckoApi->favoriteCoinPrices();

		$btcPriceInPln = $favoriteCoinPrices["btcPriceInPln"];
		$ethPriceInPln = $favoriteCoinPrices["ethPriceInPln"];
		$btcPriceInEth = $favoriteCoinPrices["btcPriceInEth"];
		$ethPriceInBtc = $favoriteCoinPrices["ethPriceInBtc"];
		$btcPriceInUsd = $favoriteCoinPrices["btcPriceInUsd"];
		$ethPriceInUsd = $favoriteCoinPrices["ethPriceInUsd"];

		$binanceApi = new BinanceController();
		$assetSnapshots = $binanceApi->balances();

		foreach ($assetSnapshots as $assetSnapshot) {
			$snapshot = new PortfolioSnapshot();
			$snapshot->snapshot_time = $updateTime;
			$snapshot->source = 1; // 1 = BINANCE
			$snapshot->asset = $assetSnapshot['asset'];
			$snapshot->quantity = $assetSnapshot['qty'];
			$snapshot->value_in_btc = $assetSnapshot['assetValueInBtc'];
			$snapshot->value_in_eth = $assetSnapshot['assetValueInBtc'] * $btcPriceInEth;
			$snapshot->value_in_usd = $assetSnapshot['assetValueInBtc'] * $btcPriceInUsd;
			$snapshot->value_in_pln = $assetSnapshot['assetValueInBtc'] * $btcPriceInPln;
			$snapshot->save();
		}

	}
}
