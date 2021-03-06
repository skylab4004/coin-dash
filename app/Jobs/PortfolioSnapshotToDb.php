<?php

namespace App\Jobs;

use App\Http\Controllers\API\BilaxyApiClient;
use App\Http\Controllers\API\BinanceController;
use App\Http\Controllers\API\BitbayApiClient;
use App\Http\Controllers\API\BscscanApiClient;
use App\Http\Controllers\API\CoinGeckoController;
use App\Http\Controllers\API\EthplorerApiClient;
use App\Http\Controllers\API\MexcApiClient;
use App\Http\Controllers\API\Secret;
use App\Http\Controllers\API\Utils;
use App\Http\Controllers\PortfolioCoinController;
use App\Http\Controllers\StaticPortfolioCoinController;
use App\Models\PortfolioSnapshot;
use DateTime;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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

		$updateTime = Utils::snapshotTimestamp(new DateTime());

		$coinGeckoApi = new CoinGeckoController();
		$favoriteCoinPrices = $coinGeckoApi->portfolioCoinsPrices();

		$portfolioCoinController = new PortfolioCoinController();
		$coinToSymbolMapping = $portfolioCoinController->getSymbolToCoinGeckoIdMapping();


		// HANDLE STATIC PORTFOLIO COINS
		// TODO
//		$staticCoins = new StaticPortfolioCoinController();
//		$staticPortfolioCoins = $staticCoins->getStaticPortfolioCoins();
//		foreach ($staticPortfolioCoins as $staticPortfolioCoin) {
//			try {
//				$snapshot = new PortfolioSnapshot();
//				$snapshot->snapshot_time = $updateTime;
//				$snapshot->source = 0; // 0 = STATIC COIN
//				$snapshot->asset = $staticPortfolioCoin['symbol'];
//				$snapshot->quantity = $staticPortfolioCoin['quantity'];
//				$snapshot->value_in_btc = $staticPortfolioCoin["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($staticPortfolioCoin["asset"])]]["btc"];
//				$snapshot->value_in_eth = $staticPortfolioCoin["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($staticPortfolioCoin["asset"])]]["eth"];
//				$snapshot->value_in_usd = $staticPortfolioCoin["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($staticPortfolioCoin["asset"])]]["usd"];
//				$snapshot->value_in_pln = $staticPortfolioCoin["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($staticPortfolioCoin["asset"])]]["pln"];
//				$snapshot->save();
//			} catch (Exception $e) {
//				Log::error($e);
//			}
//			unset($staticPortfolioCoin);
//		}


		// HANDLE BINANCE PORTFOLIO
		$binanceApi = new BinanceController();
		$binanceBalances = $binanceApi->balances();

		$coinsMissingInDb = $portfolioCoinController->returnCoinsMissingInDb(array_column($binanceBalances, 'asset'));
		$portfolioCoinController->addMissingCoinsToDb($coinsMissingInDb);

		foreach ($binanceBalances as $binanceAsset) {
			try {
				$snapshot = new PortfolioSnapshot();
				$snapshot->snapshot_time = $updateTime;
				$snapshot->source = 1; // 1 = BINANCE
				$snapshot->asset = $binanceAsset['asset'];
				$snapshot->quantity = $binanceAsset['qty'];
				if (strcasecmp($binanceAsset['asset'], "pln") == 0) {
					$snapshot->value_in_btc = 0;
					$snapshot->value_in_eth = 0;
					$snapshot->value_in_usd = 0;
					$snapshot->value_in_pln = $binanceAsset['qty'];
				} else {
					$snapshot->value_in_btc = $binanceAsset['assetValueInBtc'];
					$snapshot->value_in_eth = $binanceAsset['assetValueInBtc'] * $favoriteCoinPrices["bitcoin"]["eth"];
					$snapshot->value_in_usd = $binanceAsset['assetValueInBtc'] * $favoriteCoinPrices["bitcoin"]["usd"];
					$snapshot->value_in_pln = $binanceAsset['assetValueInBtc'] * $favoriteCoinPrices["bitcoin"]["pln"];

				}
				$snapshot->save();
			} catch (Exception $e) {
				Log::error($e);
			}
			unset($binanceAsset);
		}
		unset($binanceBalances);
		unset($binanceApi);

		// HANDLE ERC20 PORTFOLIO


		$ethplorerClient = new EthplorerApiClient();
		$addressInfo = $ethplorerClient->getAddressInfo(Secret::$ERC_WALLET_ADDRESS);
		$erc20Balances = $ethplorerClient->erc20Balances($addressInfo);

		$coinsMissingInDb = $portfolioCoinController->returnCoinsMissingInDb(array_column($erc20Balances, 'asset'));
		$portfolioCoinController->addMissingCoinsToDb($coinsMissingInDb);

		foreach ($erc20Balances as $erc20Asset) {
			try {
				$snapshot = new PortfolioSnapshot();
				$snapshot->snapshot_time = $updateTime;
				$snapshot->source = 2; // 2 = ERC20 WALLET
				$snapshot->asset = $erc20Asset["asset"];
				$snapshot->quantity = $erc20Asset["qty"];
				$snapshot->value_in_btc = $erc20Asset["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($erc20Asset["asset"])]]["btc"];
				$snapshot->value_in_eth = $erc20Asset["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($erc20Asset["asset"])]]["eth"];
				$snapshot->value_in_usd = $erc20Asset["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($erc20Asset["asset"])]]["usd"];
				$snapshot->value_in_pln = $erc20Asset["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($erc20Asset["asset"])]]["pln"];
				$snapshot->save();
			} catch (Exception $e) {
				Log::error($e);
			}
			unset($erc20Asset);
		}
		unset($erc20Balances);
		unset($addressInfo);
		unset($ethplorerClient);
		unset($coinGeckoApi);

		$mexcClient = new MexcApiClient();
		$mexcBalances = $mexcClient->getBalances();

		$coinsMissingInDb = $portfolioCoinController->returnCoinsMissingInDb(array_column($mexcBalances, 'asset'));
		$portfolioCoinController->addMissingCoinsToDb($coinsMissingInDb);

		foreach ($mexcBalances as $assetBalance) {
			try {
				$snapshot = new PortfolioSnapshot();
				$snapshot->snapshot_time = $updateTime;
				$snapshot->source = 3; // 3 = MEXC
				$snapshot->asset = $assetBalance["asset"];
				$snapshot->quantity = $assetBalance["qty"];
				$snapshot->value_in_btc = $assetBalance["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($assetBalance["asset"])]]["btc"];
				$snapshot->value_in_eth = $assetBalance["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($assetBalance["asset"])]]["eth"];
				$snapshot->value_in_usd = $assetBalance["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($assetBalance["asset"])]]["usd"];
				$snapshot->value_in_pln = $assetBalance["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($assetBalance["asset"])]]["pln"];
				$snapshot->save();
			} catch (Exception $e) {
				Log::error($e);
			}
			unset($assetBalance);
		}

		$bilaxyClient = new BilaxyApiClient();
		$bilaxyBalances = $bilaxyClient->getBalances();

		$coinsMissingInDb = $portfolioCoinController->returnCoinsMissingInDb(array_column($bilaxyBalances, 'asset'));
		$portfolioCoinController->addMissingCoinsToDb($coinsMissingInDb);

		foreach ($bilaxyBalances as $bilaxyBalance) {
			try {
				$snapshot = new PortfolioSnapshot();
				$snapshot->snapshot_time = $updateTime;
				$snapshot->source = 4; // 4 = BILAXY
				$snapshot->asset = $bilaxyBalance["asset"];
				$snapshot->quantity = $bilaxyBalance["qty"];
				$snapshot->value_in_btc = $bilaxyBalance["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($bilaxyBalance["asset"])]]["btc"];
				$snapshot->value_in_eth = $bilaxyBalance["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($bilaxyBalance["asset"])]]["eth"];
				$snapshot->value_in_usd = $bilaxyBalance["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($bilaxyBalance["asset"])]]["usd"];
				$snapshot->value_in_pln = $bilaxyBalance["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($bilaxyBalance["asset"])]]["pln"];
				$snapshot->save();
			} catch (Exception $e) {
				Log::error($e);
			}
			unset($bilaxyBalance);
		}

		$bscClient = new BscscanApiClient();
		$bnbBalance = $bscClient->getBnbBalance();

		$coinsMissingInDb = $portfolioCoinController->returnCoinsMissingInDb(['bnb']);
		$portfolioCoinController->addMissingCoinsToDb($coinsMissingInDb);

		try {
			$snapshot = new PortfolioSnapshot();
			$snapshot->snapshot_time = $updateTime;
			$snapshot->source = 5; // 5 = BINANCE SMART CHAIN
			$snapshot->asset = "BNB";
			$snapshot->quantity = $bnbBalance;
			$snapshot->value_in_btc = $bnbBalance * $favoriteCoinPrices[$coinToSymbolMapping[strtolower("bnb")]]["btc"];
			$snapshot->value_in_eth = $bnbBalance * $favoriteCoinPrices[$coinToSymbolMapping[strtolower("bnb")]]["eth"];
			$snapshot->value_in_usd = $bnbBalance * $favoriteCoinPrices[$coinToSymbolMapping[strtolower("bnb")]]["usd"];
			$snapshot->value_in_pln = $bnbBalance * $favoriteCoinPrices[$coinToSymbolMapping[strtolower("bnb")]]["pln"];
			$snapshot->save();
		} catch (Exception $e) {
			Log::error($e);
		}
		unset($bnbBalance);
		unset($snapshot);

		$bscTokens = [
			"kpad" => "0xcfefa64b0ddd611b125157c41cd3827f2e8e8615",
			"ork"  => "0xced0ce92f4bdc3c2201e255faf12f05cf8206da8",
			"mist" => "0x68e374f856bf25468d365e539b700b648bf94b67",
			"octi" => "0x6c1de9907263f0c12261d88b65ca18f31163f29d",
			"sota" => "0x0742b62efb5f2eabbc14567dfc0860ce0565bcf4"
		];

		$coinsMissingInDb = $portfolioCoinController->returnCoinsMissingInDb(array_keys($bscTokens));
		$portfolioCoinController->addMissingCoinsToDb($coinsMissingInDb);

		foreach ($bscTokens as $assetSymbol => $contract) {
			try {
				$tokenBalance = $bscClient->getTokenBalance($contract);
				$snapshot = new PortfolioSnapshot();
				$snapshot->snapshot_time = $updateTime;
				$snapshot->source = 5; // 5 = BINANCE SMART CHAIN
				$snapshot->asset = strtoupper($assetSymbol);
				$snapshot->quantity = $tokenBalance;

				$snapshot->value_in_btc = $tokenBalance * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($assetSymbol)]]["btc"];
				$snapshot->value_in_eth = $tokenBalance * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($assetSymbol)]]["eth"];
				$snapshot->value_in_usd = $tokenBalance * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($assetSymbol)]]["usd"];
				$snapshot->value_in_pln = $tokenBalance * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($assetSymbol)]]["pln"];
				$snapshot->save();
			} catch (Exception $e) {
				Log::error($e);
			}
			unset($tokenBalance);
		}
		unset($snapshot);

		$bitbayApi = new BitbayApiClient();
		$bitbayBalances = $bitbayApi->getBalances();
		foreach ($bitbayBalances as $bitbayAsset) {
			try {
				$snapshot = new PortfolioSnapshot();
				$snapshot->snapshot_time = $updateTime;
				$snapshot->source = PortfolioSnapshot::SOURCES['bitbay'];
				$snapshot->asset = $bitbayAsset['asset'];
				$snapshot->quantity = $bitbayAsset['qty'];
				if (strcasecmp($bitbayAsset['asset'], 'pln')==0) {
					$snapshot->value_in_btc = 0;
					$snapshot->value_in_eth = 0;
					$snapshot->value_in_usd = 0;
					$snapshot->value_in_pln = $bitbayAsset["qty"];
				} else {
					$snapshot->value_in_btc = $bitbayAsset["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($bitbayAsset["asset"])]]["btc"];
					$snapshot->value_in_eth = $bitbayAsset["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($bitbayAsset["asset"])]]["eth"];
					$snapshot->value_in_usd = $bitbayAsset["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($bitbayAsset["asset"])]]["usd"];
					$snapshot->value_in_pln = $bitbayAsset["qty"] * $favoriteCoinPrices[$coinToSymbolMapping[strtolower($bitbayAsset["asset"])]]["pln"];
				}
				$snapshot->save();
			} catch (Exception $e) {
				Log::error($e);
			}
			unset($bitbayAsset);
		}
		unset($binanceBalances);
		unset($binanceApi);


		unset($favoriteCoinPrices);
		unset($updateTime);
	}
}
