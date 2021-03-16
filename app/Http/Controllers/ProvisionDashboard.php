<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\Utils;
use App\Models\DailyPortfolioValue;
use App\Models\HourlyPortfolioValue;
use App\Models\PortfolioSnapshot;
use App\Models\PortfolioValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProvisionDashboard extends Controller {

	private static function loadValuesForTiles(Collection $portfolioSnapshot) {
		$tilesValues["value_in_pln"] = 0;
		$tilesValues["value_in_usd"] = 0;
		$tilesValues["binance_value_in_pln"] = 0;
		$tilesValues["binance_value_in_usd"] = 0;
		$tilesValues["metamask_value_in_pln"] = 0;
		$tilesValues["metamask_value_in_usd"] = 0;
		$tilesValues["mxc_value_in_pln"] = 0;
		$tilesValues["mxc_value_in_usd"] = 0;

		foreach ($portfolioSnapshot as $assetSnapshot) {
			$tilesValues["value_in_pln"] += $assetSnapshot['value_in_pln'];
			$tilesValues["value_in_usd"] += $assetSnapshot['value_in_usd'];
			if ($assetSnapshot['source'] == 1) { // todo: pobieranie na podstawie slownika dla source w db
				$tilesValues["binance_value_in_pln"] += $assetSnapshot['value_in_pln'];
				$tilesValues["binance_value_in_usd"] += $assetSnapshot['value_in_usd'];
			} else if ($assetSnapshot['source'] == 2) {
				$tilesValues["metamask_value_in_pln"] += $assetSnapshot['value_in_pln'];
				$tilesValues["metamask_value_in_usd"] += $assetSnapshot['value_in_usd'];
			} else if ($assetSnapshot['source'] == 3) {
				$tilesValues["mxc_value_in_pln"] += $assetSnapshot['value_in_pln'];
				$tilesValues["mxc_value_in_usd"] += $assetSnapshot['value_in_usd'];
			}
		}
		unset($assetSnapshot);

		return $tilesValues;
	}

	public function show() {

		// CURRENT portfolio value and totals in PLN and USD
		$lastSnapshotTime = PortfolioSnapshot::max('snapshot_time');


		$currentPortfolioSnapshotTiles = PortfolioSnapshot::where('snapshot_time', $lastSnapshotTime)
			->OrderBy('value_in_pln', 'desc')
			->get();
		$lastSnapshot = self::loadValuesForTiles($currentPortfolioSnapshotTiles);
		unset($currentPortfolioSnapshotTiles);


		// select asset, sum(quantity) as quantity, sum(value_in_pln) as value_in_pln, sum(value_in_usd) as value_in_usd
		// from `portfolio_snapshots` where `snapshot_time` = 1615888801168 group by asset order by value_in_pln desc

		$currentPortfolioSnapshot = PortfolioSnapshot::selectRaw('asset, sum(quantity) as quantity, sum(value_in_pln) as value_in_pln, sum(value_in_usd) as value_in_usd')
			->where('snapshot_time', $lastSnapshotTime)
			->groupBy('asset')
			->OrderBy('value_in_pln', 'desc')
			->get();

		// YESTERDAY's portfolio value and totals in PLN and USD
		$lastSnapshotTimeYesterday = DB::table('portfolio_snapshots')
			->whereRaw('CAST(FROM_UNIXTIME(snapshot_time/1000) AS DATE) = DATE(NOW()-INTERVAL 1 DAY)')
			->max('snapshot_time');
		$yesterdaysLastPortfolioSnapshot = PortfolioSnapshot::where('snapshot_time', $lastSnapshotTimeYesterday)
			->OrderBy('value_in_pln', 'desc')
			->get();
		$yesterdaysSnapshot = self::loadValuesForTiles($yesterdaysLastPortfolioSnapshot);

		// PIE CHART
		$pieChartLabels = $currentPortfolioSnapshot->pluck('asset');
		$pieChartValues = $currentPortfolioSnapshot->pluck('value_in_pln');
		$pieChart = ['labels' => $pieChartLabels, 'data' => $pieChartValues];

		// last hour stacked chart data with 5 minutes interval
		$firstSnapshotOneHourAgo = DB::table('portfolio_snapshots')->whereRaw('FROM_UNIXTIME(snapshot_time/1000) >= NOW()-INTERVAL 2 hour')->min('snapshot_time');
		// todo: show "N/A" if Snapshot wasnt found
		$lastOneHourPortfolioValues = PortfolioValue::whereRaw("snapshot_time>=from_unixtime({$firstSnapshotOneHourAgo}/1000)")->get();
		$lastHourStackedChart = self::extractChartsLabelsAndDatasets($lastOneHourPortfolioValues);
		unset($lastOneHourPortfolioValues);

		// LAST 24 HOURS STACKED CHART - 1 h interval
		$firstSnapshotTime24HoursAgo = DB::table('portfolio_snapshots')->whereRaw('FROM_UNIXTIME(snapshot_time/1000) >= NOW()-INTERVAL 48 hour')->min('snapshot_time');
		$last24HoursPortfolioValues = HourlyPortfolioValue::whereRaw("snapshot_time>=from_unixtime({$firstSnapshotTime24HoursAgo}/1000)")->get();
		$last24HoursStackedChart = self::extractChartsLabelsAndDatasets($last24HoursPortfolioValues);
		unset($last24HoursPortfolioValues);

		// LAST 7 DAYS HOURLY STACKED CHART - todo zmienic na co 6h
		$firstSnapshotTime7DaysAgo = DB::table('portfolio_snapshots')->whereRaw('CAST(FROM_UNIXTIME(snapshot_time/1000) AS DATE) >= DATE(NOW()-INTERVAL 7 DAY)')->min('snapshot_time');
		$last7DaysSnapshots = HourlyPortfolioValue::whereRaw("snapshot_time>=from_unixtime({$firstSnapshotTime7DaysAgo}/1000)")->get();
		$last7DaysSixHoursStackedChart = self::extractChartsLabelsAndDatasets($last7DaysSnapshots);
		unset($last7DaysSnapshots);

		// DAILY STACKED CHART - last 30 days
		$firstSnapshotTime30DaysAgo = DB::table('portfolio_snapshots')->whereRaw('CAST(FROM_UNIXTIME(snapshot_time/1000) AS DATE) >= DATE(NOW()-INTERVAL 30 DAY)')->min('snapshot_time');
		$last30DailySnapshots = DailyPortfolioValue::whereRaw("snapshot_time>=from_unixtime({$firstSnapshotTime30DaysAgo}/1000)")->get();
		$last30DaysStackedChart = self::extractChartsLabelsAndDatasets($last30DailySnapshots);
		unset($last30DailySnapshots);

		// in PLN
		$todaysTotalPNLinPln = $lastSnapshot["value_in_pln"] - $yesterdaysSnapshot["value_in_pln"];
		$todaysTotalDeltaPercentsFromPln = ProvisionDashboard::getDelta($lastSnapshot["value_in_pln"], $yesterdaysSnapshot["value_in_pln"]);
		$todaysBinancePNLinPln = $lastSnapshot["binance_value_in_pln"] - $yesterdaysSnapshot["binance_value_in_pln"];
		$todaysBinanceDeltaPercentsFromPln = ProvisionDashboard::getDelta($lastSnapshot["binance_value_in_pln"], $yesterdaysSnapshot["binance_value_in_pln"]);
		$todaysMetamaskPNLinPln = $lastSnapshot["metamask_value_in_pln"] - $yesterdaysSnapshot["metamask_value_in_pln"];
		$todaysMetamaskDeltaPercentsFromPln = ProvisionDashboard::getDelta($lastSnapshot["metamask_value_in_pln"], $yesterdaysSnapshot["metamask_value_in_pln"]);
		$todaysMxcPNLinPln = $lastSnapshot["mxc_value_in_pln"] - $yesterdaysSnapshot["mxc_value_in_pln"];
		$todaysMxcDeltaPercentsFromPln = ProvisionDashboard::getDelta($lastSnapshot["mxc_value_in_pln"], $yesterdaysSnapshot["mxc_value_in_pln"]);


		// in USD
		$todaysTotalPNLinUsd = $lastSnapshot["value_in_usd"] - $yesterdaysSnapshot["value_in_usd"];
		$todaysTotalDeltaPercentsFromUsd = ProvisionDashboard::getDelta($lastSnapshot["value_in_usd"], $yesterdaysSnapshot["value_in_usd"]);
		$todaysBinancePNLinUsd = $lastSnapshot["binance_value_in_usd"] - $yesterdaysSnapshot["binance_value_in_usd"];
		$todaysBinanceDeltaPercentsFromUsd = ProvisionDashboard::getDelta($lastSnapshot["binance_value_in_usd"], $yesterdaysSnapshot["binance_value_in_usd"]);
		$todaysMetamaskPNLinUsd = $lastSnapshot["metamask_value_in_usd"] - $yesterdaysSnapshot["metamask_value_in_usd"];
		$todaysMetamaskDeltaPercentsFromUsd = ProvisionDashboard::getDelta($lastSnapshot["metamask_value_in_usd"], $yesterdaysSnapshot["metamask_value_in_usd"]);
		$todaysMxcPNLinUsd = $lastSnapshot["mxc_value_in_usd"] - $yesterdaysSnapshot["mxc_value_in_usd"];
		$todaysMxcDeltaPercentsFromUsd = ProvisionDashboard::getDelta($lastSnapshot["mxc_value_in_usd"], $yesterdaysSnapshot["mxc_value_in_usd"]);


		$retData = [
			'lastSnapshotTime'                   => Utils::millisToShortTimestamp($lastSnapshotTime),
			'lastSnapshotValueInPln'             => Utils::formattedNumber($lastSnapshot["value_in_pln"], 2),
			'lastSnapshotValueInUsd'             => Utils::formattedNumber($lastSnapshot["value_in_usd"], 2),
			'lastSnapshotBinanceValueInPln'      => Utils::formattedNumber($lastSnapshot["binance_value_in_pln"], 2),
			'lastSnapshotBinanceValueInUsd'      => Utils::formattedNumber($lastSnapshot["binance_value_in_usd"], 2),
			'lastSnapshotMetamaskValueInPln'     => Utils::formattedNumber($lastSnapshot["metamask_value_in_pln"], 2),
			'lastSnapshotMetamaskValueInUsd'     => Utils::formattedNumber($lastSnapshot["metamask_value_in_usd"], 2),
			'lastSnapshotMxcValueInPln'          => Utils::formattedNumber($lastSnapshot["mxc_value_in_pln"], 2),
			'lastSnapshotMxcValueInUsd'          => Utils::formattedNumber($lastSnapshot["mxc_value_in_usd"], 2),
			'yesterdaysValueInPln'               => Utils::formattedNumber($yesterdaysSnapshot["value_in_pln"], 2),
			'yesterdaysValueInUsd'               => Utils::formattedNumber($yesterdaysSnapshot["value_in_usd"], 2),
			'yesterdaysBinanceValueInPln'        => Utils::formattedNumber($yesterdaysSnapshot["binance_value_in_pln"], 2),
			'yesterdaysBinanceValueInUsd'        => Utils::formattedNumber($yesterdaysSnapshot["binance_value_in_usd"], 2),
			'yesterdaysMetamaskValueInPln'       => Utils::formattedNumber($yesterdaysSnapshot["metamask_value_in_pln"], 2),
			'yesterdaysMetamaskValueInUsd'       => Utils::formattedNumber($yesterdaysSnapshot["metamask_value_in_usd"], 2),
			'todaysTotalPNLinPln'                => Utils::formattedNumber($todaysTotalPNLinPln, 2),
			'todaysTotalDeltaPercentsFromPln'    => Utils::formattedNumber($todaysTotalDeltaPercentsFromPln, 2),
			'todaysBinancePNLinPln'              => Utils::formattedNumber($todaysBinancePNLinPln, 2),
			'todaysBinanceDeltaPercentsFromPln'  => Utils::formattedNumber($todaysBinanceDeltaPercentsFromPln, 2),
			'todaysMetamaskPNLinPln'             => Utils::formattedNumber($todaysMetamaskPNLinPln, 2),
			'todaysMetamaskDeltaPercentsFromPln' => Utils::formattedNumber($todaysMetamaskDeltaPercentsFromPln, 2),
			'todaysMxcPNLinPln'                  => Utils::formattedNumber($todaysMxcPNLinPln, 2),
			'todaysMxcDeltaPercentsFromPln'      => Utils::formattedNumber($todaysMxcDeltaPercentsFromPln, 2),
			'todaysTotalPNLinUsd'                => Utils::formattedNumber($todaysTotalPNLinUsd, 2),
			'todaysTotalDeltaPercentsFromUsd'    => Utils::formattedNumber($todaysTotalDeltaPercentsFromUsd, 2),
			'todaysBinancePNLinUsd'              => Utils::formattedNumber($todaysBinancePNLinUsd, 2),
			'todaysBinanceDeltaPercentsFromUsd'  => Utils::formattedNumber($todaysBinanceDeltaPercentsFromUsd, 2),
			'todaysMetamaskPNLinUsd'             => Utils::formattedNumber($todaysMetamaskPNLinUsd, 2),
			'todaysMetamaskDeltaPercentsFromUsd' => Utils::formattedNumber($todaysMetamaskDeltaPercentsFromUsd, 2),
			'todaysMxcPNLinUsd'                  => Utils::formattedNumber($todaysMxcPNLinUsd, 2),
			'todaysMxcDeltaPercentsFromUsd'      => Utils::formattedNumber($todaysMxcDeltaPercentsFromUsd, 2),
			'currentPortfolioSnapshot'           => $currentPortfolioSnapshot,
			'pieChart'                           => $pieChart,
			'lastHourStackedChart'               => $lastHourStackedChart,
			'last24HoursStackedChart'            => $last24HoursStackedChart,
			'last7DaysSixHoursStackedChart'      => $last7DaysSixHoursStackedChart,
			'last30DaysStackedChart'             => $last30DaysStackedChart,
		];


		return view('pages.dashboard', $retData);

	}

	private static function extractChartsLabelsAndDatasets($portfolioValues) {
		$assetNames = $portfolioValues->unique('asset')->pluck('asset');
		$labels = $portfolioValues->unique('snapshot_time')->sort()->pluck('snapshot_time');
		$datasets = [];
		foreach ($assetNames as $assetName) {
			$datasetForAsset = $portfolioValues->where('asset', $assetName)->pluck('value_in_pln');
			$datasets[] = ['label' => $assetName, 'data' => $datasetForAsset->toArray()];
		}

		return [
			'labels'   => $labels,
			'datasets' => $datasets,
		];
	}

	/**
	 * @param $currentValue
	 * @param $previousValue
	 * @return float|int
	 */
	private static function getDelta($currentValue, $previousValue) {
		if ($previousValue == null || $previousValue == 0) {
			return "NaN";
		}
		return (($currentValue - $previousValue) / $previousValue) * 100;
	}
}
