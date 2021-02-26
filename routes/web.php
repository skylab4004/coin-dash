<?php

use App\Http\Controllers\API\Utils;
use App\Models\PortfolioSnapshot;
use App\Models\PortfolioValue;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function() {


	// CURRENT portfolio value and totals in PLN and USD
	$lastSnapshotTime = PortfolioSnapshot::max('snapshot_time');
	$currentPortfolioSnapshot = PortfolioSnapshot::where('snapshot_time', $lastSnapshotTime)
		->OrderBy('value_in_pln', 'desc')
		->get();

	$currentValueInPln = 0;
	$currentValueInUsd = 0;
	$currentBinanceValueInPln = 0;
	$currentBinanceValueInUsd = 0;
	$currentMetamaskValueInPln = 0;
	$currentMetamaskValueInUsd = 0;
	foreach ($currentPortfolioSnapshot as $assetSnapshot) {
		$currentValueInPln += $assetSnapshot['value_in_pln'];
		$currentValueInUsd += $assetSnapshot['value_in_usd'];
		if ($assetSnapshot['source'] == 1) { // todo: pobieranie na podstawie slownika dla source w db
			$currentBinanceValueInPln += $assetSnapshot['value_in_pln'];
			$currentBinanceValueInUsd += $assetSnapshot['value_in_usd'];
		} else if ($assetSnapshot['source'] == 2) {
			$currentMetamaskValueInPln += $assetSnapshot['value_in_pln'];
			$currentMetamaskValueInUsd += $assetSnapshot['value_in_usd'];
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

	$retData = [
		'lastSnapshotTime'             => Utils::millisToShortTimestamp($lastSnapshotTime),
		'currentValueInPln'            => Utils::formattedNumber($currentValueInPln, 2),
		'currentValueInUsd'            => Utils::formattedNumber($currentValueInUsd, 2),
		'currentBinanceValueInPln'     => Utils::formattedNumber($currentBinanceValueInPln, 2),
		'currentBinanceValueInUsd'     => Utils::formattedNumber($currentBinanceValueInUsd, 2),
		'currentMetamaskValueInPln'    => Utils::formattedNumber($currentMetamaskValueInPln, 2),
		'currentMetamaskValueInUsd'    => Utils::formattedNumber($currentMetamaskValueInUsd, 2),
		'yesterdaysValueInPln'         => Utils::formattedNumber($yesterdaysValueInPln, 2),
		'yesterdaysValueInUsd'         => Utils::formattedNumber($yesterdaysValueInUsd, 2),
		'yesterdaysBinanceValueInPln'  => Utils::formattedNumber($yesterdaysBinanceValueInPln, 2),
		'yesterdaysBinanceValueInUsd'  => Utils::formattedNumber($yesterdaysBinanceValueInUsd, 2),
		'yesterdaysMetamaskValueInPln' => Utils::formattedNumber($yesterdaysMetamaskValueInPln, 2),
		'yesterdaysMetamaskValueInUsd' => Utils::formattedNumber($yesterdaysMetamaskValueInUsd, 2),
		'currentPortfolioSnapshot'     => $currentPortfolioSnapshot,
		'pieChart'                     => $pieChart,
		'totalsChart'                  => $totalsChart,
		'stackedChart'                 => $stackedChart
	];

	return view('home', $retData);
});

Route::get('/portfolio-piechart', function() {

	return view('portfolio_piechart');
});

Route::get('/portfolio-snapshots', function() {
	return view('portfolio_snapshots');
});

Route::get('/portfolio-totals-chart', function() {
	return view('portfolio_totals_chart', ["assetValues" => "[3, 2, 1]", "labelValues" => "['abc', 'def', 'ghi']"]);
});

Route::get('/portfolio-snapshot-current', function() {
	return view('portfolio_snapshot_current');
});