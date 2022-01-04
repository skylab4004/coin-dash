<?php namespace App\Jobs;

use App\Models\PortfolioTotal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PortfolioTotalValueAlertsJob implements ShouldQueue{

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

		$lastSnapshotTime = PortfolioTotal::max('snapshot_time');

		$portfolioTotal = PortfolioTotal::selectRaw('snapshot_time, cast(value_in_pln as integer) as value_in_pln, value_in_btc')->where('snapshot_time', $lastSnapshotTime)->get();





	}
}