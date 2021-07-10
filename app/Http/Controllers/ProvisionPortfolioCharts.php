<?php

namespace App\Http\Controllers;

use App\Models\DailyPortfolioValue;
use App\Models\PortfolioSnapshot;

class ProvisionPortfolioCharts extends Controller {

	public function show() {
		$lastSnapshotTime = PortfolioSnapshot::max('snapshot_time');

		$currentPortfolioSnapshot = PortfolioSnapshot::selectRaw('source, round(sum(value_in_pln), 0) as value_in_pln, sum(value_in_usd) as value_in_usd')
			->where('snapshot_time', $lastSnapshotTime)
			->groupBy('source')
			->OrderBy("value_in_pln", 'desc')
			->get();

		// PIE CHART
		$sourceIds = $currentPortfolioSnapshot->pluck('source');
		$pieChartLabels = [];
		foreach ($sourceIds as $sourceId) {
			$pieChartLabels[] = ucfirst(array_search($sourceId, PortfolioSnapshot::SOURCES));
		}
		$pieChartValues = $currentPortfolioSnapshot->pluck(Constants::KEY_VALUE_IN_PLN)->toArray();
		$pieChart = [
			'labels' => $this->arrayToDataset($pieChartLabels, true),
			'data'   => $this->arrayToDataset($pieChartValues)
		];

		// binance
		$currentPortfolioSnapshot = PortfolioSnapshot::selectRaw('asset, round(sum(value_in_pln), 0) as value_in_pln, sum(value_in_usd) as value_in_usd')
			->where([
				['snapshot_time', $lastSnapshotTime],
				['source', PortfolioSnapshot::SOURCES['binance']]
			])
			->groupBy('asset')
			->OrderBy("value_in_pln", 'desc')
			->get();
		$pieChartLabels = $currentPortfolioSnapshot->pluck('asset')->toArray();
		$pieChartValues = $currentPortfolioSnapshot->pluck(Constants::KEY_VALUE_IN_PLN)->toArray();
		$binanceChart = [
			'labels' => $this->arrayToDataset($pieChartLabels, true),
			'data'   => $this->arrayToDataset($pieChartValues)
		];

		// erc20
		$currentPortfolioSnapshot = PortfolioSnapshot::selectRaw('asset, round(sum(value_in_pln), 0) as value_in_pln, sum(value_in_usd) as value_in_usd')
			->where([
				['snapshot_time', $lastSnapshotTime],
				['source', PortfolioSnapshot::SOURCES['erc20']]
			])
			->groupBy('asset')
			->OrderBy("value_in_pln", 'desc')
			->get();
		$pieChartLabels = $currentPortfolioSnapshot->pluck('asset')->toArray();
		$pieChartValues = $currentPortfolioSnapshot->pluck(Constants::KEY_VALUE_IN_PLN)->toArray();
		$erc20Chart = [
			'labels' => $this->arrayToDataset($pieChartLabels, true),
			'data'   => $this->arrayToDataset($pieChartValues)
		];

		// mexc
		$currentPortfolioSnapshot = PortfolioSnapshot::selectRaw('asset, round(sum(value_in_pln), 0) as value_in_pln, sum(value_in_usd) as value_in_usd')
			->where([
				['snapshot_time', $lastSnapshotTime],
				['source', PortfolioSnapshot::SOURCES['mexc']]
			])
			->groupBy('asset')
			->OrderBy("value_in_pln", 'desc')
			->get();
		$pieChartLabels = $currentPortfolioSnapshot->pluck('asset')->toArray();
		$pieChartValues = $currentPortfolioSnapshot->pluck(Constants::KEY_VALUE_IN_PLN)->toArray();
		$mexcChart = [
			'labels' => $this->arrayToDataset($pieChartLabels, true),
			'data'   => $this->arrayToDataset($pieChartValues)
		];

		// bsc20
		$currentPortfolioSnapshot = PortfolioSnapshot::selectRaw('asset, round(sum(value_in_pln), 0) as value_in_pln, sum(value_in_usd) as value_in_usd')
			->where([
				['snapshot_time', $lastSnapshotTime],
				['source', PortfolioSnapshot::SOURCES['bsc20']]
			])
			->groupBy('asset')
			->OrderBy("value_in_pln", 'desc')
			->get();
		$pieChartLabels = $currentPortfolioSnapshot->pluck('asset')->toArray();
		$pieChartValues = $currentPortfolioSnapshot->pluck(Constants::KEY_VALUE_IN_PLN)->toArray();
		$bsc20Chart = [
			'labels' => $this->arrayToDataset($pieChartLabels, true),
			'data'   => $this->arrayToDataset($pieChartValues)
		];

		// bitbay
		$currentPortfolioSnapshot = PortfolioSnapshot::selectRaw('asset, round(sum(value_in_pln), 0) as value_in_pln, sum(value_in_usd) as value_in_usd')
			->where([
				['snapshot_time', $lastSnapshotTime],
				['source', PortfolioSnapshot::SOURCES['bitbay']]
			])
			->groupBy('asset')
			->OrderBy("value_in_pln", 'desc')
			->get();
		$pieChartLabels = $currentPortfolioSnapshot->pluck('asset')->toArray();
		$pieChartValues = $currentPortfolioSnapshot->pluck(Constants::KEY_VALUE_IN_PLN)->toArray();
		$bitbayChart = [
			'labels' => $this->arrayToDataset($pieChartLabels, true),
			'data'   => $this->arrayToDataset($pieChartValues)
		];

//		$firstSnapshotTime30DaysAgo = DB::table('portfolio_snapshots')->whereRaw('CAST(snapshot_time AS DATE) >= DATE(NOW()-INTERVAL 30 DAY)')->min('snapshot_time');
		$last30DailySnapshots = DailyPortfolioValue::groupBy("snapshot_time")
			->selectRaw('cast(snapshot_time as date) as snapshot_time, cast(sum(value_in_pln) as integer) as sum')
			->pluck('sum', 'snapshot_time')->toArray();
		$lineChart = $this->extractChartsLabelsAndDatasets($last30DailySnapshots);
		unset($last30DailySnapshots);


		$retData = [
			'pieChart'     => $pieChart,
			'binanceChart' => $binanceChart,
			'erc20Chart'   => $erc20Chart,
			'mexcChart'    => $mexcChart,
			'bitbayChart'  => $bitbayChart,
			'bsc20Chart'   => $bsc20Chart,
			'lineChart'    => $lineChart,
		];

		return view('pages.portfolio', $retData);

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
