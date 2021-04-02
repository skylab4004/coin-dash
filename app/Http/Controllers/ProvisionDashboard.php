<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\Utils;
use App\Models\DailyPortfolioValue;
use App\Models\HourlyPortfolioValue;
use App\Models\PortfolioSnapshot;
use Illuminate\Support\Facades\DB;

class ProvisionDashboard extends Controller {

	const KEY_VALUE_IN_PLN = "value_in_pln";
	const KEY_BINANCE_VALUE_IN_PLN = "binance_value_in_pln";
	const KEY_METAMASK_VALUE_IN_PLN = "metamask_value_in_pln";
	const KEY_MXC_VALUE_IN_PLN = "mxc_value_in_pln";
	const KEY_VALUE_IN_USD = "value_in_usd";
	const KEY_BINANCE_VALUE_IN_USD = "binance_value_in_usd";
	const KEY_METAMASK_VALUE_IN_USD = "metamask_value_in_usd";
	const KEY_MXC_VALUE_IN_USD = "mxc_value_in_usd";
	const TILE_TOTAL_BALANCE = 'total_balance';
	const TILE_TOTAL_PNL_TODAY = 'total_pnl_today';
	const TILE_TOTAL_PNL_DELTA_TODAY = 'total_pnl_delta_today';
	const TILE_BINANCE_BALANCE = 'binance_balance';
	const TILE_BINANCE_PNL_TODAY = 'binance_pnl_today';
	const TILE_BINANCE_PNL_DELTA_TODAY = 'binance_pnl_delta_today';
	const TILE_METAMASK_BALANCE = 'metamask_balance';
	const TILE_METAMASK_PNL_TODAY = 'metamask_pnl_today';
	const TILE_METAMASK_PNL_DELTA_TODAY = 'metamask_pnl_delta_today';
	const TILE_MXC_BALANCE = 'mxc_balance';
	const TILE_MXC_PNL_TODAY = 'mxc_pnl_today';
	const TILE_MXC_PNL_DELTA_TODAY = 'mxc_pnl_delta_today';
	const TILE_YESTERDAY_TOTAL_BALANCE = 'yesterday_total_balance';

	private static function addCurrentSnapshotValues($currentPortfolioSnapshotTable) {
	}

	public function show() {
		// CURRENT portfolio value and totals in PLN and USD
		$lastSnapshotTime = PortfolioSnapshot::max('snapshot_time');

//		$currentPortfolioSnapshotTiles = PortfolioSnapshot::where('snapshot_time', $lastSnapshotTime)
//			->OrderBy("value_in_pln", 'desc')
//			->get()
//			->toArray();

		$currentPortfolioSnapshot = PortfolioSnapshot::selectRaw('asset, source, sum(quantity) as quantity, sum(value_in_pln) as value_in_pln, sum(value_in_usd) as value_in_usd')
			->where('snapshot_time', $lastSnapshotTime)
			->groupBy('asset', 'source')
			->OrderBy("value_in_pln", 'desc')
			->get();


		$lastSnapshot = self::loadValuesForTiles($currentPortfolioSnapshot);
//		unset($currentPortfolioSnapshotTiles);


		// select asset, sum(quantity) as quantity, sum(value_in_pln) as value_in_pln, sum(value_in_usd) as value_in_usd
		// from `portfolio_snapshots` where `snapshot_time` = 1615888801168 group by asset order by value_in_pln desc

		$currentPortfolioSnapshotTable = PortfolioSnapshot::selectRaw('asset, sum(quantity) as quantity, sum(value_in_pln) as value_in_pln, sum(value_in_usd) as value_in_usd')
			->where('snapshot_time', $lastSnapshotTime)
			->groupBy('asset')
			->OrderBy("value_in_pln", 'desc')
			->get();

		// YESTERDAY's portfolio value and totals in PLN and USD
		$lastSnapshotTimeYesterday = DB::table('portfolio_snapshots')
			->whereRaw('CAST(snapshot_time AS DATE) = DATE(NOW()-INTERVAL 1 DAY)')
			->max('snapshot_time');
		$yesterdaysLastPortfolioSnapshot = PortfolioSnapshot::where('snapshot_time', $lastSnapshotTimeYesterday)
			->OrderBy("value_in_pln", 'desc')
			->get();
		$yesterdaysSnapshot = self::loadValuesForTiles($yesterdaysLastPortfolioSnapshot);

		$profitAndLosses = DB::select("select current.asset, current.value_in_pln, (current.value_in_pln-5minago.value_in_pln) as pnl_5_min" .
			", (current.value_in_pln-1hago.value_in_pln) as pnl_1h, (current.value_in_pln-3hago.value_in_pln) as pnl_3h, (current.value_in_pln-midnight.value_in_pln) as pnl_midnight ".
			" from " .
			"(select asset, sum(quantity) as quantity, sum(value_in_pln) as value_in_pln " .
			"from `portfolio_snapshots` where snapshot_time = '{$lastSnapshotTime}' group by asset ) as current, " .
			"(select asset, sum(quantity) as quantity, sum(value_in_pln) as value_in_pln " .
			"from `portfolio_snapshots` where snapshot_time = '{$lastSnapshotTime}'-interval 5 minute group by asset ) as 5minago, " .
			"(select asset, sum(quantity) as quantity, sum(value_in_pln) as value_in_pln ".
			"from `portfolio_snapshots` where snapshot_time = '{$lastSnapshotTime}'-interval 1 hour group by asset ) as 1hago, ".
			"(select asset, sum(quantity) as quantity, sum(value_in_pln) as value_in_pln ".
			"from `portfolio_snapshots` where snapshot_time = '{$lastSnapshotTime}'-interval 3 hour group by asset) as 3hago, ".
			"(select asset, sum(quantity) as quantity, sum(value_in_pln) as value_in_pln ".
			"from `portfolio_snapshots` where snapshot_time = cast(cast('{$lastSnapshotTime}' as date) as datetime) group by asset) as midnight ".
			"where current.asset=5minago.asset " .
			"and current.asset=1hago.asset and current.asset=3hago.asset and current.asset=midnight.asset ".
			"order by 6 desc");

		// PIE CHART
		$pieChartLabels = $currentPortfolioSnapshotTable->pluck('asset');
		$pieChartValues = $currentPortfolioSnapshot->pluck(self::KEY_VALUE_IN_PLN);
		$pieChart = ['labels' => $pieChartLabels, 'data' => $pieChartValues];

		// last hour stacked chart data with 5 minutes interval
//		$firstSnapshotOneHourAgo = DB::table('portfolio_snapshots')->whereRaw('snapshot_time >= NOW()-INTERVAL 2 hour')->min('snapshot_time');
//		 todo: show "N/A" if Snapshot wasnt found
//		$lastOneHourPortfolioValues = PortfolioValue::where("snapshot_time", ">=", $firstSnapshotOneHourAgo)->get();
//		$lastHourStackedChart = self::extractChartsLabelsAndDatasets($lastOneHourPortfolioValues);
//		unset($lastOneHourPortfolioValues);

		// LAST 24 HOURS STACKED CHART - 1 h interval
//		$firstSnapshotTime24HoursAgo = DB::table('portfolio_snapshots')->whereRaw('snapshot_time>= NOW()-INTERVAL 48 hour')->min('snapshot_time');
//		$last24HoursPortfolioValues = HourlyPortfolioValue::where("snapshot_time", ">=", $firstSnapshotTime24HoursAgo)->get();
//		$last24HoursStackedChart = self::extractChartsLabelsAndDatasets($last24HoursPortfolioValues);
//		unset($last24HoursPortfolioValues);

		// LAST 7 DAYS HOURLY STACKED CHART - todo zmienic na co 6h
		$firstSnapshotTime7DaysAgo = DB::table('portfolio_snapshots')->whereRaw('CAST(snapshot_time AS DATE) >= DATE(NOW()-INTERVAL 7 DAY)')->min('snapshot_time');
		$last7DaysSnapshots = HourlyPortfolioValue::where("snapshot_time", ">=", $firstSnapshotTime7DaysAgo)->get();
		$last7DaysSixHoursStackedChart = self::extractChartsLabelsAndDatasets($last7DaysSnapshots);
		unset($last7DaysSnapshots);

		// DAILY STACKED CHART - last 30 days
		$firstSnapshotTime30DaysAgo = DB::table('portfolio_snapshots')->whereRaw('CAST(snapshot_time AS DATE) >= DATE(NOW()-INTERVAL 30 DAY)')->min('snapshot_time');
		$last30DailySnapshots = DailyPortfolioValue::where("snapshot_time", ">=", $firstSnapshotTime30DaysAgo)->get();
		$last30DaysStackedChart = self::extractChartsLabelsAndDatasets($last30DailySnapshots);
		unset($last30DailySnapshots);

		// in PLN
		$todaysTotalPNLinPln = ProvisionDashboard::safeDiff($lastSnapshot, $yesterdaysSnapshot, self::KEY_VALUE_IN_PLN);
		$todaysTotalDeltaPercentsFromPln = ProvisionDashboard::safeDelta($lastSnapshot, $yesterdaysSnapshot, self::KEY_VALUE_IN_PLN);
		$todaysBinancePNLinPln = ProvisionDashboard::safeDiff($lastSnapshot, $yesterdaysSnapshot, self::KEY_BINANCE_VALUE_IN_PLN);
		$todaysBinanceDeltaPercentsFromPln = ProvisionDashboard::safeDelta($lastSnapshot, $yesterdaysSnapshot, self::KEY_BINANCE_VALUE_IN_PLN);
		$todaysMetamaskPNLinPln = ProvisionDashboard::safeDiff($lastSnapshot, $yesterdaysSnapshot, self::KEY_METAMASK_VALUE_IN_PLN);
		$todaysMetamaskDeltaPercentsFromPln = ProvisionDashboard::safeDelta($lastSnapshot, $yesterdaysSnapshot, self::KEY_METAMASK_VALUE_IN_PLN);
		$todaysMxcPNLinPln = ProvisionDashboard::safeDiff($lastSnapshot, $yesterdaysSnapshot, self::KEY_MXC_VALUE_IN_PLN);
		$todaysMxcDeltaPercentsFromPln = ProvisionDashboard::safeDelta($lastSnapshot, $yesterdaysSnapshot, self::KEY_MXC_VALUE_IN_PLN);

		$tiles = [
			self::TILE_TOTAL_BALANCE            => Utils::formattedNumber($lastSnapshot[self::KEY_VALUE_IN_PLN], 2),
			self::TILE_TOTAL_PNL_TODAY          => Utils::formattedNumber($todaysTotalPNLinPln, 2),
			self::TILE_TOTAL_PNL_DELTA_TODAY    => Utils::formattedNumber($todaysTotalDeltaPercentsFromPln, 2),
			self::TILE_BINANCE_BALANCE          => Utils::formattedNumber($lastSnapshot[self::KEY_BINANCE_VALUE_IN_PLN], 2),
			self::TILE_BINANCE_PNL_TODAY        => Utils::formattedNumber($todaysBinancePNLinPln, 2),
			self::TILE_BINANCE_PNL_DELTA_TODAY  => Utils::formattedNumber($todaysBinanceDeltaPercentsFromPln, 2),
			self::TILE_METAMASK_BALANCE         => Utils::formattedNumber($lastSnapshot[self::KEY_METAMASK_VALUE_IN_PLN], 2),
			self::TILE_METAMASK_PNL_TODAY       => Utils::formattedNumber($todaysMetamaskPNLinPln, 2),
			self::TILE_METAMASK_PNL_DELTA_TODAY => Utils::formattedNumber($todaysMetamaskDeltaPercentsFromPln, 2),
			self::TILE_MXC_BALANCE              => Utils::formattedNumber($lastSnapshot[self::KEY_MXC_VALUE_IN_PLN], 2),
			self::TILE_MXC_PNL_TODAY            => Utils::formattedNumber($todaysMxcPNLinPln, 2),
			self::TILE_MXC_PNL_DELTA_TODAY      => Utils::formattedNumber($todaysMxcDeltaPercentsFromPln, 2),
			self::TILE_YESTERDAY_TOTAL_BALANCE  => Utils::formattedNumber($yesterdaysSnapshot[self::KEY_VALUE_IN_PLN], 2)
		];


		$retData = [
			'tiles'                         => $tiles,
			'lastSnapshotTime'              => $lastSnapshotTime,
			'currentPortfolioSnapshot'      => $currentPortfolioSnapshotTable,
			'pieChart'                      => $pieChart,
//			'lastHourStackedChart'          => $lastHourStackedChart,
//			'last24HoursStackedChart'       => $last24HoursStackedChart,
			'last7DaysSixHoursStackedChart' => $last7DaysSixHoursStackedChart,
			'last30DaysStackedChart'        => $last30DaysStackedChart,
			'profitAndLosses'               => $profitAndLosses,
		];


		return view('pages.dashboard', $retData);

	}

	private static function loadValuesForTiles($portfolioSnapshot) {
		$tilesValues[self::KEY_VALUE_IN_PLN] = 0;
		$tilesValues[self::KEY_VALUE_IN_USD] = 0;
		$tilesValues[self::KEY_BINANCE_VALUE_IN_PLN] = 0;
		$tilesValues[self::KEY_BINANCE_VALUE_IN_USD] = 0;
		$tilesValues[self::KEY_METAMASK_VALUE_IN_PLN] = 0;
		$tilesValues[self::KEY_METAMASK_VALUE_IN_USD] = 0;
		$tilesValues[self::KEY_MXC_VALUE_IN_PLN] = 0;
		$tilesValues[self::KEY_MXC_VALUE_IN_USD] = 0;

		if ( ! isset($portfolioSnapshot) || ($portfolioSnapshot == null)) {
			return [];
		}

		foreach ($portfolioSnapshot as $assetSnapshot) {
			$tilesValues[self::KEY_VALUE_IN_PLN] += $assetSnapshot[self::KEY_VALUE_IN_PLN];
			$tilesValues[self::KEY_VALUE_IN_USD] += $assetSnapshot[self::KEY_VALUE_IN_USD];
			if ($assetSnapshot['source'] == 1) { // todo: pobieranie na podstawie slownika dla source w db
				$tilesValues[self::KEY_BINANCE_VALUE_IN_PLN] += $assetSnapshot[self::KEY_VALUE_IN_PLN];
				$tilesValues[self::KEY_BINANCE_VALUE_IN_USD] += $assetSnapshot[self::KEY_VALUE_IN_USD];
			} else if ($assetSnapshot['source'] == 2) {
				$tilesValues[self::KEY_METAMASK_VALUE_IN_PLN] += $assetSnapshot[self::KEY_VALUE_IN_PLN];
				$tilesValues[self::KEY_METAMASK_VALUE_IN_USD] += $assetSnapshot[self::KEY_VALUE_IN_USD];
			} else if ($assetSnapshot['source'] == 3) {
				$tilesValues[self::KEY_MXC_VALUE_IN_PLN] += $assetSnapshot[self::KEY_VALUE_IN_PLN];
				$tilesValues[self::KEY_MXC_VALUE_IN_USD] += $assetSnapshot[self::KEY_VALUE_IN_USD];
			}
		}
		unset($assetSnapshot);

		return $tilesValues;
	}

	private static function extractChartsLabelsAndDatasets($portfolioValues) {
		$assetNames = $portfolioValues->unique('asset')->pluck('asset');
		$labels = $portfolioValues->unique('snapshot_time')->sort()->pluck('snapshot_time');
		$datasets = [];
		foreach ($assetNames as $assetName) {
			$datasetForAsset = $portfolioValues->where('asset', $assetName)->pluck(self::KEY_VALUE_IN_PLN);
			$datasets[] = ['label' => $assetName, 'data' => $datasetForAsset->toArray()];
		}

		return [
			'labels'   => $labels,
			'datasets' => $datasets,
		];
	}

	private static function safeDiff($formerSnapshot, $latterSnapshot, string $key) {
		$diffVal = 0;
		$nextVal = 0;
		$prevVal = 0;
		if (is_array($formerSnapshot) && array_key_exists($key, $formerSnapshot)) {
			$nextVal = $formerSnapshot[$key];
		}

		if (is_array($latterSnapshot) && array_key_exists($key, $latterSnapshot)) {
			$prevVal = $latterSnapshot[$key];
		}

		if (is_numeric($nextVal) && is_numeric($prevVal)) {
			$diffVal = $nextVal - $prevVal;
		}

		return $diffVal;
	}

	private static function safeDelta($formerSnapshot, $latterSnapshot, string $key) {
		$deltaVal = 0;
		$nextVal = 0;
		$prevVal = 0;
		if (is_array($formerSnapshot) && array_key_exists($key, $formerSnapshot)) {
			$nextVal = $formerSnapshot[$key];
		}

		if (is_array($latterSnapshot) && array_key_exists($key, $latterSnapshot)) {
			$prevVal = $latterSnapshot[$key];
		}

		if (is_numeric($nextVal) && is_numeric($prevVal)) {
			if ($prevVal == 0) {
				return "NaN";
			}
			$deltaVal = (($nextVal - $prevVal) / $prevVal) * 100;
		}

		return $deltaVal;
	}

}
