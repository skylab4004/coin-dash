<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\Utils;
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
		unset($yesterdaysLastPortfolioSnapshot);
		unset($yesterdaysAssetSnapshot);

		// PIE CHART
		$pieChartLabels = $currentPortfolioSnapshot->pluck('asset');
		$pieChartValues = $currentPortfolioSnapshot->pluck('value_in_pln');
		$pieChart = ['labels' => $pieChartLabels, 'data' => $pieChartValues];

		// TOTALS CHART
		$portfolioTotals = PortfolioSnapshot::groupBy('snapshot_time')
			->selectRaw('FROM_UNIXTIME(snapshot_time/1000) as snapshot_time, sum(value_in_pln) as sum')
			->pluck('sum', 'snapshot_time');

		$totalsChart = ['labels' => $portfolioTotals->keys(), 'data' => $portfolioTotals->values()];

		// STACKED CHART
		$portfolioValues = PortfolioValue::all();
		$assetNames = $portfolioValues->unique('asset')->pluck('asset');
		$labels = $portfolioValues->unique('snapshot_time')->sort()->pluck('snapshot_time');

		$datasets = [];
		foreach ($assetNames as $assetName) {
			$datasetForAsset = $portfolioValues->where('asset', $assetName)->pluck('value_in_pln');
			array_push($datasets, ['label' => $assetName, 'data' => $datasetForAsset->toArray()]);
		}

		$stackedChart = [
			'labels'   => $labels,
			'datasets' => $datasets,
		];

		// in PLN
		$todaysTotalPNLinPln = $lastSnapshotValueInPln - $yesterdaysValueInPln;
		$todaysTotalDeltaPercentsFromPln = (($lastSnapshotValueInPln - $yesterdaysValueInPln) / $lastSnapshotValueInPln) * 100;
		$todaysBinancePNLinPln = $lastSnapshotBinanceValueInPln - $yesterdaysBinanceValueInPln;
		$todaysBinanceDeltaPercentsFromPln = (($lastSnapshotBinanceValueInPln - $yesterdaysBinanceValueInPln) / $lastSnapshotBinanceValueInPln) * 100;
		$todaysMetamaskPNLinPln = $lastSnapshotMetamaskValueInPln - $yesterdaysMetamaskValueInPln;
		$todaysMetamaskDeltaPercentsFromPln = (($lastSnapshotMetamaskValueInPln - $yesterdaysMetamaskValueInPln) / $lastSnapshotMetamaskValueInPln) * 100;

		// in USD
		$todaysTotalPNLinUsd = $lastSnapshotValueInUsd - $yesterdaysValueInUsd;
		$todaysTotalDeltaPercentsFromUsd = (($lastSnapshotValueInUsd - $yesterdaysValueInUsd) / $lastSnapshotValueInUsd) * 100;
		$todaysBinancePNLinUsd = $lastSnapshotBinanceValueInUsd - $yesterdaysBinanceValueInUsd;
		$todaysBinanceDeltaPercentsFromUsd = (($lastSnapshotBinanceValueInUsd - $yesterdaysBinanceValueInUsd) / $lastSnapshotBinanceValueInUsd) * 100;
		$todaysMetamaskPNLinUsd = $lastSnapshotMetamaskValueInUsd - $yesterdaysMetamaskValueInUsd;
		$todaysMetamaskDeltaPercentsFromUsd = (($lastSnapshotMetamaskValueInUsd - $yesterdaysMetamaskValueInUsd) / $lastSnapshotMetamaskValueInUsd) * 100;

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
			'totalsChart'                        => $totalsChart,
			'stackedChart'                       => $stackedChart
		];


		return view('pages.dashboard', $retData);

	}
}
