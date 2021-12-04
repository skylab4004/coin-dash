<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\Utils;
use App\Models\PortfolioSnapshot;
use Illuminate\Support\Facades\DB;

class ProvisionPortfolioWallet extends Controller {

	public function show($sourceId) {
		$lastSnapshotTime = PortfolioSnapshot::max('snapshot_time');

		$currentPortfolioSnapshot = PortfolioSnapshot::selectRaw('asset, quantity, value_in_pln, value_in_usd')
			->where('snapshot_time', $lastSnapshotTime)
			->where('source', $sourceId)
			->OrderBy("value_in_pln", 'desc')
			->get();

		// PIE CHART for Binance
		$pieChartLabels = $currentPortfolioSnapshot->pluck('asset');
		$pieChartValues = $currentPortfolioSnapshot->pluck(Constants::KEY_VALUE_IN_PLN);
		$pieChart = ['labels' => $pieChartLabels, 'data' => $pieChartValues];


		$firstSnapshotTime7DaysAgo = DB::table('portfolio_snapshots')->whereRaw('CAST(snapshot_time AS DATE) >= DATE(NOW()-INTERVAL 7 DAY)')->min('snapshot_time');
		$lastSnapshotTime = PortfolioSnapshot::max('snapshot_time');

		$start = $firstSnapshotTime7DaysAgo;
		$end = $lastSnapshotTime;

		// HOURLY STACKED CHART
		// query for hourly stacked chart by source id and date range
		$query = <<<SQL
			select d.snapshot_time, d.asset, coalesce(b.source, 1), coalesce(b.quantity, 0) as quantity, coalesce(b.value_in_pln, 0) as value_in_pln, coalesce(b.value_in_usd, 0) as value_in_usd, coalesce(b.value_in_btc, 0) as value_in_btc, coalesce(b.value_in_eth, 0) as value_in_eth from 
			(
			    -- snapshot times x assets
				select t.snapshot_time, a.asset from  
				(
				    -- distinct snapshot times
					select distinct snapshot_time from portfolio_snapshots WHERE HOUR(snapshot_time) MOD 4 = 0 AND MINUTE(snapshot_time) = 0 and snapshot_time between '$start' and '$end'
				) as t,
				(
				-- distinct assets of given wallet
				select distinct asset from portfolio_snapshots where source=$sourceId and snapshot_time between '$start' and '$end'
				) as a
			) d 
			left join 
			( 
				-- portfolio_snapshots
				select snapshot_time, source, asset, quantity, value_in_pln, value_in_usd, value_in_btc, value_in_eth from portfolio_snapshots where source=$sourceId and snapshot_time between '$start' and '$end'
			) b
			on b.snapshot_time=d.snapshot_time and b.asset=d.asset
			order by 1, 2
			SQL;

		$last7DaysSnapshots = collect(DB::select($query));
		$last7DaysSixHoursStackedChart = Utils::extractChartsLabelsAndDatasets($last7DaysSnapshots);
		unset($last7DaysSnapshots);

		$firstSnapshotTime30DaysAgo = DB::table('portfolio_snapshots')->whereRaw('CAST(snapshot_time AS DATE) >= DATE(NOW()-INTERVAL 30 DAY)')->min('snapshot_time');

		$start = $firstSnapshotTime30DaysAgo;
		$end = $lastSnapshotTime;

		// DAILY STACKED CHART
		// query for daily stacked chart by source id and date range
		$query = <<<SQL
			select d.snapshot_time, d.asset, coalesce(b.source, $sourceId), coalesce(b.quantity, 0) as quantity, coalesce(b.value_in_pln, 0) as value_in_pln, coalesce(b.value_in_usd, 0) as value_in_usd, coalesce(b.value_in_btc, 0) as value_in_btc, coalesce(b.value_in_eth, 0) as value_in_eth from 
			(
			    -- snapshot times x assets
				select t.snapshot_time, a.asset from  
				(
				    -- distinct snapshot times
					select distinct snapshot_time from portfolio_snapshots WHERE HOUR(snapshot_time) = 0 AND MINUTE(snapshot_time) = 0 and snapshot_time between '$start' and '$end'
				) as t,
				(
				-- distinct assets of given wallet
				select distinct asset from portfolio_snapshots where source=$sourceId and snapshot_time between '$start' and '$end'
				) as a
			) d 
			left join 
			( 
				-- portfolio_snapshots
				select snapshot_time, source, asset, quantity, value_in_pln, value_in_usd, value_in_btc, value_in_eth from portfolio_snapshots where source=$sourceId and snapshot_time between '$start' and '$end'
			) b
			on b.snapshot_time=d.snapshot_time and b.asset=d.asset
			order by 1, 2
			SQL;

		$stackedChart = collect(DB::select($query));
		$last30DaysStackedChart = Utils::extractChartsLabelsAndDatasets($stackedChart); //  Utils::extractChartsLabelsAndDatasets($last30DailySnapshots);
		unset($stackedChart);

		$retData = [
			'walletName'                    => ucfirst(array_search($sourceId, PortfolioSnapshot::SOURCES)),
			'lastSnapshotTime'              => $lastSnapshotTime,
			'pieChart'                      => $pieChart,
			'last7DaysSixHoursStackedChart' => $last7DaysSixHoursStackedChart,
			'last30DaysStackedChart'        => $last30DaysStackedChart,
			'snapshot'                      => $currentPortfolioSnapshot,
		];

		return view('pages.portfolio-wallet', $retData);

	}

	private function arrayToDataset(array $array, bool $quotes = false) {
		if ($quotes) {
			return "['" . implode("','", $array) . "']";
		}

		return "[" . implode(",", $array) . "]";
	}

	private function extractChartsLabelsAndDatasets($portfolioValues) {
		return [
			'labels' => $this->arrayToDataset(array_keys($portfolioValues), true),
			'data'   => $this->arrayToDataset(array_values($portfolioValues))
		];
	}

}
