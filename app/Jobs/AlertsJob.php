<?php

namespace App\Jobs;

use App\Http\Controllers\API\BilaxyApiClient;
use App\Http\Controllers\API\BinanceController;
use App\Http\Controllers\API\BscscanApiClient;
use App\Http\Controllers\API\CoinGeckoController;
use App\Http\Controllers\API\EthplorerApiClient;
use App\Http\Controllers\API\MexcApiClient;
use App\Http\Controllers\API\Secret;
use App\Http\Controllers\API\Utils;
use App\Http\Controllers\PortfolioCoinController;
use App\Http\Controllers\PriceAlertController;
use App\Models\PortfolioSnapshot;
use App\Models\PriceAlert;
use DateTime;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AlertsJob implements ShouldQueue {

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
		$alertController = new PriceAlertController();
		$symbols = $alertController->getSymbols();
		$currentPrices =

		$alerts = PriceAlert::all();
		foreach ($alerts as $alert) {

		}
	}
}
