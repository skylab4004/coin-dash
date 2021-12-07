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


		$snapshot = PortfolioSnapshot::selectRaw('asset, source, sum(quantity) as quantity, sum(value_in_pln) as value_in_pln, sum(value_in_usd) as value_in_usd')
			->where('snapshot_time', $lastSnapshotTime)
			->groupBy('asset', 'source')
			->OrderBy("asset", 'asc')
			->OrderBy("source", 'asc')
			->get()->toArray();


		$last30DailySnapshots = DailyPortfolioValue::groupBy("snapshot_time")
			->selectRaw('cast(snapshot_time as date) as snapshot_time, cast(sum(value_in_pln) as integer) as sum')
			->pluck('sum', 'snapshot_time')->toArray();
		$lineChart = $this->extractChartsLabelsAndDatasets($last30DailySnapshots);
		unset($last30DailySnapshots);

		$retData = [
			'pieChart'  => $pieChart,
			'lineChart' => $lineChart,
			'snapshot'    => $snapshot,
		];

		return view('pages.portfolio-overview', $retData);

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
