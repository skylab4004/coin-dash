<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\Utils;
use App\Models\DailyPortfolioValue;
use App\Models\HourlyPortfolioValue;
use App\Models\PortfolioSnapshot;
use App\Models\PortfolioValue;
use Illuminate\Support\Facades\DB;

class ProvisionDashboard extends Controller {

	public function show() {

		// CURRENT portfolio value and totals in PLN and USD
		$lastSnapshotTime = PortfolioSnapshot::max('snapshot_time');
		$currentPortfolioSnapshot = PortfolioSnapshot::where('snapshot_time', $lastSnapshotTime)
			->OrderBy('value_in_pln', 'desc')
			->get();

		$lastSnapshotValueInPln = 0;
		$lastSnapshotValueInUsd = 0;
		$lastSnapshotBinanceValueInPln = 0;
		$lastSnapshotBinanceValueInUsd = 0;
		$lastSnapshotMetamaskValueInPln = 0;
		$lastSnapshotMetamaskValueInUsd = 0;
		foreach ($currentPortfolioSnapshot as $assetSnapshot) {
			$lastSnapshotValueInPln += $assetSnapshot['value_in_pln'];
			$lastSnapshotValueInUsd += $assetSnapshot['value_in_usd'];
			if ($assetSnapshot['source'] == 1) { // todo: pobieranie na podstawie slownika dla source w db
				$lastSnapshotBinanceValueInPln += $assetSnapshot['value_in_pln'];
				$lastSnapshotBinanceValueInUsd += $assetSnapshot['value_in_usd'];
			} else if ($assetSnapshot['source'] == 2) {
				$lastSnapshotMetamaskValueInPln += $assetSnapshot['value_in_pln'];
				$lastSnapshotMetamaskValueInUsd += $assetSnapshot['value_in_usd'];
			}
		}
		unset($assetSnapshot);
//		unset($currentPortfolioSnapshot);

		// YESTERDAY's portfolio value and totals in PLN and USD
		$lastSnapshotTimeYesterday = DB::table('portfolio_snapshots')
			->whereRaw('CAST(FROM_UNIXTIME(snapshot_time/1000) AS DATE) = DATE(NOW()-INTERVAL 1 DAY)')
			->max('snapshot_time');
		$yesterdaysLastPortfolioSnapshot = PortfolioSnapshot::where('snapshot_time', $lastSnapshotTimeYesterday)
			->OrderBy('value_in_pln', 'desc')
			->get();

		$yesterdaysValueInPln = 0;
		$yesterdaysValueInUsd = 0;
		$yesterdaysBinanceValueInPln = 0;
		$yesterdaysBinanceValueInUsd = 0;
		$yesterdaysMetamaskValueInPln = 0;
		$yesterdaysMetamaskValueInUsd = 0;
		foreach ($yesterdaysLastPortfolioSnapshot as $yesterdaysAssetSnapshot) {
			$yesterdaysValueInPln += $yesterdaysAssetSnapshot['value_in_pln'];
			$yesterdaysValueInUsd += $yesterdaysAssetSnapshot['value_in_usd'];
			if ($yesterdaysAssetSnapshot['source'] == 1) { // todo: pobieranie na podstawie slownika dla source w db
				$yesterdaysBinanceValueInPln += $yesterdaysAssetSnapshot['value_in_pln'];
				$yesterdaysBinanceValueInUsd += $yesterdaysAssetSnapshot['value_in_usd'];
			} else if ($yesterdaysAssetSnapshot['source'] == 2) {
				$yesterdaysMetamaskValueInPln += $yesterdaysAssetSnapshot['value_in_pln'];
				$yesterdaysMetamaskValueInUsd += $yesterdaysAssetSnapshot['value_in_usd'];
			}
		}
		unset($yesterdaysAssetSnapshot);
		unset($yesterdaysLastPortfolioSnapshot);
		unset($lastSnapshotTimeYesterday);

		// PIE CHART
		$pieChartLabels = $currentPortfolioSnapshot->pluck('asset');
		$pieChartValues = $currentPortfolioSnapshot->pluck('value_in_pln');
		$pieChart = ['labels' => $pieChartLabels, 'data' => $pieChartValues];

		// last hour stacked chart data with 5 minutes interval
		$firstSnapshotOneHourAgo = DB::table('portfolio_snapshots')->whereRaw('FROM_UNIXTIME(snapshot_time/1000) >= NOW()-INTERVAL 2 hour')->min('snapshot_time');
		$lastOneHourPortfolioValues = PortfolioValue::whereRaw('snapshot_time>=from_unixtime(' . $firstSnapshotOneHourAgo . '/1000)')->get();
		$lastHourStackedChart = self::extractChartsLabelsAndDatasets($lastOneHourPortfolioValues);
		unset($lastOneHourPortfolioValues);

		// LAST 24 HOURS STACKED CHART - 1 h interval
		$firstSnapshotTime24HoursAgo = DB::table('portfolio_snapshots')->whereRaw('FROM_UNIXTIME(snapshot_time/1000) >= NOW()-INTERVAL 48 hour')->min('snapshot_time');
		$last24HoursPortfolioValues = HourlyPortfolioValue::whereRaw('snapshot_time>=from_unixtime(' . $firstSnapshotTime24HoursAgo . '/1000)')->get();
		$last24HoursStackedChart = self::extractChartsLabelsAndDatasets($last24HoursPortfolioValues);
		unset($last24HoursPortfolioValues);

		// LAST 7 DAYS HOURLY STACKED CHART - todo zmienic na co 6h
		$firstSnapshotTime7DaysAgo = DB::table('portfolio_snapshots')->whereRaw('CAST(FROM_UNIXTIME(snapshot_time/1000) AS DATE) >= DATE(NOW()-INTERVAL 7 DAY)')->min('snapshot_time');
		$last7DaysSnapshots = HourlyPortfolioValue::whereRaw('snapshot_time>=from_unixtime(' . $firstSnapshotTime7DaysAgo . '/1000)')->get();
		$last7DaysSixHoursStackedChart = self::extractChartsLabelsAndDatasets($last7DaysSnapshots);
		unset($last7DaysSnapshots);

		// DAILY STACKED CHART - last 30 days
		$firstSnapshotTime30DaysAgo = DB::table('portfolio_snapshots')->whereRaw('CAST(FROM_UNIXTIME(snapshot_time/1000) AS DATE) >= DATE(NOW()-INTERVAL 30 DAY)')->min('snapshot_time');
		$last30DailySnapshots = DailyPortfolioValue::whereRaw('snapshot_time>=from_unixtime(' . $firstSnapshotTime30DaysAgo . '/1000)')->get();
		$last30DaysStackedChart = self::extractChartsLabelsAndDatasets($last30DailySnapshots);
		unset($last30DailySnapshots);

		// in PLN
		$todaysTotalPNLinPln = $lastSnapshotValueInPln - $yesterdaysValueInPln;
		$todaysTotalDeltaPercentsFromPln = (($lastSnapshotValueInPln - $yesterdaysValueInPln) / $yesterdaysValueInPln) * 100;
		$todaysBinancePNLinPln = $lastSnapshotBinanceValueInPln - $yesterdaysBinanceValueInPln;
		$todaysBinanceDeltaPercentsFromPln = (($lastSnapshotBinanceValueInPln - $yesterdaysBinanceValueInPln) / $yesterdaysBinanceValueInPln) * 100;
		$todaysMetamaskPNLinPln = $lastSnapshotMetamaskValueInPln - $yesterdaysMetamaskValueInPln;
		$todaysMetamaskDeltaPercentsFromPln = (($lastSnapshotMetamaskValueInPln - $yesterdaysMetamaskValueInPln) / $yesterdaysMetamaskValueInPln) * 100;

		// in USD
		$todaysTotalPNLinUsd = $lastSnapshotValueInUsd - $yesterdaysValueInUsd;
		$todaysTotalDeltaPercentsFromUsd = (($lastSnapshotValueInUsd - $yesterdaysValueInUsd) / $yesterdaysValueInUsd) * 100;
		$todaysBinancePNLinUsd = $lastSnapshotBinanceValueInUsd - $yesterdaysBinanceValueInUsd;
		$todaysBinanceDeltaPercentsFromUsd = (($lastSnapshotBinanceValueInUsd - $yesterdaysBinanceValueInUsd) / $yesterdaysBinanceValueInUsd) * 100;
		$todaysMetamaskPNLinUsd = $lastSnapshotMetamaskValueInUsd - $yesterdaysMetamaskValueInUsd;
		$todaysMetamaskDeltaPercentsFromUsd = (($lastSnapshotMetamaskValueInUsd - $yesterdaysMetamaskValueInUsd) / $yesterdaysMetamaskValueInUsd) * 100;

		$retData = [
			'lastSnapshotTime'                   => Utils::millisToShortTimestamp($lastSnapshotTime),
			'lastSnapshotValueInPln'             => Utils::formattedNumber($lastSnapshotValueInPln, 2),
			'lastSnapshotValueInUsd'             => Utils::formattedNumber($lastSnapshotValueInUsd, 2),
			'lastSnapshotBinanceValueInPln'      => Utils::formattedNumber($lastSnapshotBinanceValueInPln, 2),
			'lastSnapshotBinanceValueInUsd'      => Utils::formattedNumber($lastSnapshotBinanceValueInUsd, 2),
			'lastSnapshotMetamaskValueInPln'     => Utils::formattedNumber($lastSnapshotMetamaskValueInPln, 2),
			'lastSnapshotMetamaskValueInUsd'     => Utils::formattedNumber($lastSnapshotMetamaskValueInUsd, 2),
			'yesterdaysValueInPln'               => Utils::formattedNumber($yesterdaysValueInPln, 2),
			'yesterdaysValueInUsd'               => Utils::formattedNumber($yesterdaysValueInUsd, 2),
			'yesterdaysBinanceValueInPln'        => Utils::formattedNumber($yesterdaysBinanceValueInPln, 2),
			'yesterdaysBinanceValueInUsd'        => Utils::formattedNumber($yesterdaysBinanceValueInUsd, 2),
			'yesterdaysMetamaskValueInPln'       => Utils::formattedNumber($yesterdaysMetamaskValueInPln, 2),
			'yesterdaysMetamaskValueInUsd'       => Utils::formattedNumber($yesterdaysMetamaskValueInUsd, 2),
			'todaysTotalPNLinPln'                => Utils::formattedNumber($todaysTotalPNLinPln, 2),
			'todaysTotalDeltaPercentsFromPln'    => Utils::formattedNumber($todaysTotalDeltaPercentsFromPln, 2),
			'todaysBinancePNLinPln'              => Utils::formattedNumber($todaysBinancePNLinPln, 2),
			'todaysBinanceDeltaPercentsFromPln'  => Utils::formattedNumber($todaysBinanceDeltaPercentsFromPln, 2),
			'todaysMetamaskPNLinPln'             => Utils::formattedNumber($todaysMetamaskPNLinPln, 2),
			'todaysMetamaskDeltaPercentsFromPln' => Utils::formattedNumber($todaysMetamaskDeltaPercentsFromPln, 2),
			'todaysTotalPNLinUsd'                => Utils::formattedNumber($todaysTotalPNLinUsd, 2),
			'todaysTotalDeltaPercentsFromUsd'    => Utils::formattedNumber($todaysTotalDeltaPercentsFromUsd, 2),
			'todaysBinancePNLinUsd'              => Utils::formattedNumber($todaysBinancePNLinUsd, 2),
			'todaysBinanceDeltaPercentsFromUsd'  => Utils::formattedNumber($todaysBinanceDeltaPercentsFromUsd, 2),
			'todaysMetamaskPNLinUsd'             => Utils::formattedNumber($todaysMetamaskPNLinUsd, 2),
			'todaysMetamaskDeltaPercentsFromUsd' => Utils::formattedNumber($todaysMetamaskDeltaPercentsFromUsd, 2),
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
			array_push($datasets, ['label' => $assetName, 'data' => $datasetForAsset->toArray()]);
		}

		return [
			'labels'   => $labels,
			'datasets' => $datasets,
		];
	}
}
