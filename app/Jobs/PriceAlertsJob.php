<?php

namespace App\Jobs;

use App\Http\Controllers\API\PancakeSwapApiClient;
use App\Models\PriceAlert;
use App\Utils\UniswapPriceGetter;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PriceAlertsJob implements ShouldQueue {

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
		$alerts = PriceAlert::all();

		$coinPrices = [];
		foreach ($alerts as &$alert) {
			$symbol = $alert['symbol'];
			$contract_address = $alert['contract_address'];
			$threshold = $alert['threshold'];
			$condition = $alert['condition'];
			$last_price = $alert['last_price'];
			$triggered = $alert['triggered'];
			$price_source = $alert['price_source'];

			// get token price from the right source if not already retrieved in this run
			if ( ! array_key_exists($symbol, $coinPrices)) {
				switch ($price_source) {
					case PriceAlert::PRICE_SOURCES['uniswap']:
						$uniswap = new UniswapPriceGetter();
						$tokenPrice = $uniswap->uniswapPrice($contract_address);
						$coinPrices[] = [$symbol => $tokenPrice];
						// Log::debug("Price for {$symbol} retrieved from UniSwap: \${$tokenPrice}. coinPrices[]={$debugArray}");
						break;
					case PriceAlert::PRICE_SOURCES['pancakeswap']:
						// code goes here
						$uniswap = new PancakeSwapApiClient();
						$tokenPrice = $uniswap->getTokenPrice($contract_address);
						$coinPrices[] = [$symbol => $tokenPrice];
						$debugArray = json_encode($coinPrices);
						// Log::debug("Price for {$symbol} retrieved from PancakeSwap: \${$tokenPrice}. coinPrices[]={$debugArray}");
						break;
				}
			}

			// HANDLE THE ALERT
			$currentTokenPrice = array_column($coinPrices, $symbol)[0];
			if ($triggered) {
				// if already triggered and price reversed set to not triggered
				if (($condition === PriceAlert::CONDITIONS['greater'] && $currentTokenPrice <= $threshold) ||
					($condition === PriceAlert::CONDITIONS['lesser'] && $currentTokenPrice >= $threshold)) {
					// set triggered to false
					// Log::debug("Price reversal. Setting alert of ({$symbol} price {$condition} than \${$threshold}) as not triggered");
					$alert->triggered = false;
				}
			} else {
				// send an alert if necessary and mark it as triggered
				// array_column($coinPrices, $symbol)[0]
				if ($condition === PriceAlert::CONDITIONS['greater'] && $currentTokenPrice >= $threshold) {
					Log::alert("{$symbol} price is now above \${$threshold} and is \${$currentTokenPrice}");
					// Log::debug("Alert for {$symbol} and price above \${$threshold} is triggered.");
					$alert->triggered = true;
				} else if ($condition === PriceAlert::CONDITIONS['lesser'] && $currentTokenPrice <= $threshold) {
					Log::alert("{$symbol} price is now below \${$threshold} and is \${$currentTokenPrice}");
					// Log::debug("Alert for {$symbol} and price below \${$threshold} is triggered.");
					$alert->triggered = true;
				}
			}
			$alert->last_price = $currentTokenPrice;
			$alert->last_price_time = Carbon::now();
			// Log::debug("Setting last price of {$symbol} to \${$last_price}");
			$alert->save();
		}
	}
}
