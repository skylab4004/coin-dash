<?php

namespace App\Jobs;

use App\Http\Controllers\API\BinanceController;
use App\Http\Controllers\API\CoinGeckoController;
use App\Http\Controllers\API\EthplorerApiClient;
use App\Http\Controllers\API\Secret;
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

		// HANDLE BINANCE PORTFOLIO
		$binanceApi = new BinanceController();
		$binanceBalances = $binanceApi->balances();

		foreach ($binanceBalances as $binanceAsset) {
			$snapshot = new PortfolioSnapshot();
			$snapshot->snapshot_time = $updateTime;
			$snapshot->source = 1; // 1 = BINANCE
			$snapshot->asset = $binanceAsset['asset'];
			$snapshot->quantity = $binanceAsset['qty'];
			$snapshot->value_in_btc = $binanceAsset['assetValueInBtc'];
			$snapshot->value_in_eth = $binanceAsset['assetValueInBtc'] * $favoriteCoinPrices["bitcoin"]["eth"];
			$snapshot->value_in_usd = $binanceAsset['assetValueInBtc'] * $favoriteCoinPrices["bitcoin"]["usd"];
			$snapshot->value_in_pln = $binanceAsset['assetValueInBtc'] * $favoriteCoinPrices["bitcoin"]["pln"];
			$snapshot->save();
			unset($binanceAsset);
		}
		unset($binanceBalances);
		unset($binanceApi);

		// HANDLE ERC20 PORTFOLIO
		$coinToSymbolMapping = ["btc"   => "bitcoin", "eth" => "ethereum", "rbc" => "rubic", "cliq" => "deficliq",
								"atom"  => "cosmos", "lto" => "lto-network", "mitx" => "morpheus-labs",
								"rune"  => "thorchain", "cvr" => "polkacover", "frm" => "ferrum-network",
								"apy"   => "apy-finance", "chart" => "chartex", "vidya" => "vidya",
								"yeld"  => "yeld-finance", "ethv" => "ethverse", "loot" => "nftlootbox",
								"azuki" => "azuki", "alpa" => "alpaca", "pylon" => "pylon-finance", "kyl" => "kylin-network"];

		$ethplorerClient = new EthplorerApiClient();
		$addressInfo = $ethplorerClient->getAddressInfo(Secret::$ERC_WALLET_ADDRESS);
		$erc20Balances = $ethplorerClient->erc20Balances($addressInfo);
		foreach ($erc20Balances as $erc20Asset) {
			$snapshot = new PortfolioSnapshot();
			$snapshot->snapshot_time = $updateTime;
			$snapshot->source = 2; // 2 = ERC20 WALLET
			$snapshot->asset = $erc20Asset['asset'];
			$snapshot->quantity = $erc20Asset['qty'];
			$snapshot->value_in_btc = $erc20Asset["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($erc20Asset["asset"])]]["btc"];
			$snapshot->value_in_eth = $erc20Asset["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($erc20Asset["asset"])]]["eth"];
			$snapshot->value_in_usd = $erc20Asset["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($erc20Asset["asset"])]]["usd"];
			$snapshot->value_in_pln = $erc20Asset["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($erc20Asset["asset"])]]["pln"];
			$snapshot->save();
			unset($erc20Asset);
		}
		unset($erc20Balances);
		unset($addressInfo);
		unset($ethplorerClient);

		unset($favoriteCoinPrices);
		unset($coinGeckoApi);

		unset($updateTime);
	}
}
