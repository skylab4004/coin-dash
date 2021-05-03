<?php namespace App\Http\Controllers;

use App\Models\DailyPortfolioValue;
use App\Models\HourlyPortfolioValue;
use App\Models\PortfolioSnapshot;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\DB;

class ProvisionCharts {

	public function show() {

		// CURRENT portfolio value and totals in PLN and USD0
		$lastSnapshotTime = PortfolioSnapshot::max('snapshot_time');
		$lastSnapshotDateTime = new DateTime($lastSnapshotTime);
		$nextUpdate = $lastSnapshotDateTime;
		$nextUpdate->add(new DateInterval('P5M'));

		$currentPortfolioSnapshot = PortfolioSnapshot::selectRaw('asset, source, sum(quantity) as quantity, sum(value_in_pln) as value_in_pln, sum(value_in_usd) as value_in_usd')
			->where('snapshot_time', $lastSnapshotTime)
			->groupBy('asset', 'source')
			->OrderBy("value_in_pln", 'desc')
			->get();

		// PIE CHART
		$pieChartLabels = $currentPortfolioSnapshot->pluck('asset');
		$pieChartValues = $currentPortfolioSnapshot->pluck(Constants::KEY_VALUE_IN_PLN);
		$pieChart = ['labels' => $pieChartLabels, 'data' => $pieChartValues];

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

		$retData = [
			'lastSnapshotTime'              => $lastSnapshotDateTime->format('Y-m-d H:i:s'),
			'pieChart'                      => $pieChart,
			'last7DaysSixHoursStackedChart' => $last7DaysSixHoursStackedChart,
			'last30DaysStackedChart'        => $last30DaysStackedChart,
		];

		return view('pages.charts', $retData);
	}

	private static function extractChartsLabelsAndDatasets($portfolioValues) {
		$assetNames = $portfolioValues->unique('asset')->pluck('asset');
		$labels = $portfolioValues->unique('snapshot_time')->sort()->pluck('snapshot_time');
		$datasets = [];
		foreach ($assetNames as $assetName) {
			$datasetForAsset = $portfolioValues->where('asset', $assetName)->pluck(Constants::KEY_VALUE_IN_PLN);
			$datasets[] = ['label' => $assetName, 'data' => $datasetForAsset->toArray()];
		}

		return [
			'labels'   => $labels,
			'datasets' => $datasets,
		];
	}

}