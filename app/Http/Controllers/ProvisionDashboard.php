<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\Utils;
use App\Models\PortfolioSnapshot;
use App\Models\PortfolioTotal;
use Illuminate\Support\Facades\DB;

class ProvisionDashboard extends Controller {

	private $stableCoins = ['pln', 'usdt', 'usdc', 'ust']; // todo - needs to be retrieved from database + crud

	public function show() {
		// CURRENT portfolio value and totals in PLN and USD0
		$lastSnapshotTime = PortfolioSnapshot::max('snapshot_time');
//		$lastSnapshotDateTime = new DateTime($lastSnapshotTime);
//		$nextUpdate = $lastSnapshotDateTime;
//		$nextUpdate->add(new DateInterval('P5M'));

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
			->get()->toArray();

		$totalPortfolioValueInPln = 0;
		$totalPortfolioValueInUsd = 0;
		foreach ($currentPortfolioSnapshotTable as $snap) {
			$totalPortfolioValueInPln += $snap['value_in_pln'];
			$totalPortfolioValueInUsd += $snap['value_in_usd'];
		}


		foreach ($currentPortfolioSnapshotTable as &$snap) {
			$snap['percentage'] = Utils::dashboardNumber(($snap['value_in_pln'] / $totalPortfolioValueInPln) * 100);
			$snap['quantity'] = Utils::dashboardNumber($snap['quantity'], 8);
			$snap['value_in_pln'] = Utils::dashboardNumber($snap['value_in_pln']);
			$snap['value_in_usd'] = Utils::dashboardNumber($snap['value_in_usd']);
		}
		unset($snap);

		// YESTERDAY's portfolio value and totals in PLN and USD
		$lastSnapshotTimeYesterday = PortfolioTotal::selectRaw('max(snapshot_time) as snapshot_time')
			->whereRaw('CAST(snapshot_time AS DATE) = DATE(NOW()-INTERVAL 1 DAY)')->first()->toArray()['snapshot_time'];

		$yesterdaysLastPortfolioSnapshot = PortfolioSnapshot::where('snapshot_time', $lastSnapshotTimeYesterday)
			->OrderBy("value_in_pln", 'desc')
			->get();
		$yesterdaysSnapshot = self::loadValuesForTiles($yesterdaysLastPortfolioSnapshot);

		$query = <<<SQL
			select 
			       now.asset as asset, 
			       now.quantity as quantity, 
			       now.val as value_in_pln, 
			       now.quantity-5min.quantity as qty_delta_5min, 
			       now.val-5min.val as pnl_5min,
				   now.quantity-1h.quantity as qty_delta_1h, 
			       now.val-1h.val as pnl_1h,
				   now.quantity-3h.quantity as qty_delta_3h, 
			       now.val-3h.val as pnl_3h,
			       now.quantity-midnight.quantity as qty_delta_midnight, 
			       now.val-midnight.val as pnl_midnight
			from
			( select asset, sum(quantity) as quantity, sum(value_in_pln) as val from portfolio_snapshots where snapshot_time = '$lastSnapshotTime' group by asset ) as now
				left join
					( select asset, sum(quantity) as quantity, sum(value_in_pln) as val from portfolio_snapshots where snapshot_time = '$lastSnapshotTime'-interval 5 minute group by asset) as 5min
				on
					now.asset=5min.asset
				left join
					( select asset, sum(quantity) as quantity, sum(value_in_pln) as val from portfolio_snapshots where snapshot_time = '$lastSnapshotTime'-interval 1 hour group by asset) as 1h
				on now.asset=1h.asset
				left join
					( select asset, sum(quantity) as quantity, sum(value_in_pln) as val from portfolio_snapshots where snapshot_time = '$lastSnapshotTime'-interval 3 hour group by asset) as 3h
				on now.asset=3h.asset
				left join
					( select asset, sum(quantity) as quantity, sum(value_in_pln) as val from portfolio_snapshots where snapshot_time = cast(cast('$lastSnapshotTime' as date) as datetime) group by asset) as midnight
				on now.asset=midnight.asset
			order by pnl_midnight desc
			SQL;

		$profitAndLosses = DB::select($query);

		foreach ($profitAndLosses as &$profitAndLoss) {
			$profitAndLoss->value_in_pln = Utils::dashboardNumber($profitAndLoss->value_in_pln);
			$profitAndLoss->pnl_5_min = Utils::dashboardNumber($profitAndLoss->pnl_5min);
			$profitAndLoss->pnl_1h = Utils::dashboardNumber($profitAndLoss->pnl_1h);
			$profitAndLoss->pnl_3h = Utils::dashboardNumber($profitAndLoss->pnl_3h);
			$profitAndLoss->pnl_midnight = Utils::dashboardNumber($profitAndLoss->pnl_midnight);
		}
		unset($profitAndLoss);

		$topGainers = collect($profitAndLosses);
		$topLosers = $topGainers->splice(5);
		$topLosers = $topLosers->reverse(); //->splice(5);
		$topLosers->splice(5);

		$topGainers = $topGainers->toArray();
		$topLosers = $topLosers->toArray();

		// in PLN
		$todaysTotalPNLinPln = ProvisionDashboard::safeDiff($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_VALUE_IN_PLN);
		$todaysTotalDeltaPercentsFromPln = ProvisionDashboard::safeDelta($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_VALUE_IN_PLN);
		$todaysBinancePNLinPln = ProvisionDashboard::safeDiff($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_BINANCE_VALUE_IN_PLN);
		$todaysBinanceDeltaPercentsFromPln = ProvisionDashboard::safeDelta($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_BINANCE_VALUE_IN_PLN);
		$todaysMetamaskPNLinPln = ProvisionDashboard::safeDiff($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_METAMASK_VALUE_IN_PLN);
		$todaysMetamaskDeltaPercentsFromPln = ProvisionDashboard::safeDelta($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_METAMASK_VALUE_IN_PLN);
		$todaysMxcPNLinPln = ProvisionDashboard::safeDiff($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_MXC_VALUE_IN_PLN);
		$todaysMxcDeltaPercentsFromPln = ProvisionDashboard::safeDelta($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_MXC_VALUE_IN_PLN);
		$todaysBitbayPNLinPln = ProvisionDashboard::safeDiff($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_BITBAY_VALUE_IN_PLN);
		$todaysBitbayDeltaPercentsFromPln = ProvisionDashboard::safeDelta($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_BITBAY_VALUE_IN_PLN);
		$todaysBscPNLinPln = ProvisionDashboard::safeDiff($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_BEP20_VALUE_IN_PLN);
		$todaysBscDeltaPercentsFromPln = ProvisionDashboard::safeDelta($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_BEP20_VALUE_IN_PLN);
		$todaysPolygonPNLinPln = ProvisionDashboard::safeDiff($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_POLYGON_VALUE_IN_PLN);;
		$todaysPolygonDeltaPercentsFromPln = ProvisionDashboard::safeDelta($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_POLYGON_VALUE_IN_PLN);
		$todaysAscendexPNLinPln = ProvisionDashboard::safeDiff($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_ASCENDEX_VALUE_IN_PLN);;
		$todaysAscendexDeltaPercentsFromPln = ProvisionDashboard::safeDelta($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_ASCENDEX_VALUE_IN_PLN);
		$todaysCoinbasePNLinPln = ProvisionDashboard::safeDiff($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_COINBASE_VALUE_IN_PLN);;
		$todaysCoinbaseDeltaPercentsFromPln = ProvisionDashboard::safeDelta($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_COINBASE_VALUE_IN_PLN);
		$todaysKucoinPNLinPln = ProvisionDashboard::safeDiff($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_KUCOIN_VALUE_IN_PLN);;
		$todaysKucoinDeltaPercentsFromPln = ProvisionDashboard::safeDelta($lastSnapshot, $yesterdaysSnapshot, Constants::KEY_KUCOIN_VALUE_IN_PLN);


		// get ROI
		$investment_in_pln = 50000;
		$roiInPln = $lastSnapshot[Constants::KEY_VALUE_IN_PLN] - $investment_in_pln;
		$roiInPercents = (($lastSnapshot[Constants::KEY_VALUE_IN_PLN] - $investment_in_pln) / $investment_in_pln) * 100;

		$tiles = [
			Constants::TILE_TOTAL_BALANCE            => Utils::formattedNumber($lastSnapshot[Constants::KEY_VALUE_IN_PLN], 0, ' '),
			Constants::TILE_TOTAL_PNL_TODAY          => Utils::formattedNumber($todaysTotalPNLinPln, 0, ' '),
			Constants::TILE_TOTAL_PNL_DELTA_TODAY    => Utils::formattedNumber($todaysTotalDeltaPercentsFromPln, 2),
			Constants::TILE_BINANCE_BALANCE          => Utils::formattedNumber($lastSnapshot[Constants::KEY_BINANCE_VALUE_IN_PLN], 0, ' '),
			Constants::TILE_BINANCE_PNL_TODAY        => Utils::formattedNumber($todaysBinancePNLinPln, 0, ' '),
			Constants::TILE_BINANCE_PNL_DELTA_TODAY  => Utils::formattedNumber($todaysBinanceDeltaPercentsFromPln, 2),
			Constants::TILE_METAMASK_BALANCE         => Utils::formattedNumber($lastSnapshot[Constants::KEY_METAMASK_VALUE_IN_PLN], 0, ' '),
			Constants::TILE_METAMASK_PNL_TODAY       => Utils::formattedNumber($todaysMetamaskPNLinPln, 0, ' '),
			Constants::TILE_METAMASK_PNL_DELTA_TODAY => Utils::formattedNumber($todaysMetamaskDeltaPercentsFromPln, 2),
			Constants::TILE_BEP20_BALANCE            => Utils::formattedNumber($lastSnapshot[Constants::KEY_BEP20_VALUE_IN_PLN], 0, ' '),
			Constants::TILE_BEP20_PNL_TODAY          => Utils::formattedNumber($todaysBscPNLinPln, 0, ' '),
			Constants::TILE_BEP20_PNL_DELTA_TODAY    => Utils::formattedNumber($todaysBscDeltaPercentsFromPln, 2),
			Constants::TILE_MXC_BALANCE              => Utils::formattedNumber($lastSnapshot[Constants::KEY_MXC_VALUE_IN_PLN], 0, ' '),
			Constants::TILE_MXC_PNL_TODAY            => Utils::formattedNumber($todaysMxcPNLinPln, 0, ' '),
			Constants::TILE_MXC_PNL_DELTA_TODAY      => Utils::formattedNumber($todaysMxcDeltaPercentsFromPln, 2),
			Constants::TILE_BITBAY_BALANCE           => Utils::formattedNumber($lastSnapshot[Constants::KEY_BITBAY_VALUE_IN_PLN], 0, ' '),
			Constants::TILE_BITBAY_PNL_TODAY         => Utils::formattedNumber($todaysBitbayPNLinPln, 0, ' '),
			Constants::TILE_BITBAY_PNL_DELTA_TODAY   => Utils::formattedNumber($todaysBitbayDeltaPercentsFromPln, 2),

			Constants::TILE_POLYGON_BALANCE         => Utils::formattedNumber($lastSnapshot[Constants::KEY_POLYGON_VALUE_IN_PLN], 0, ' '),
			Constants::TILE_POLYGON_PNL_TODAY       => Utils::formattedNumber($todaysPolygonPNLinPln, 0, ' '),
			Constants::TILE_POLYGON_PNL_DELTA_TODAY => Utils::formattedNumber($todaysPolygonDeltaPercentsFromPln, 2),

			Constants::TILE_ASCENDEX_BALANCE         => Utils::formattedNumber($lastSnapshot[Constants::KEY_ASCENDEX_VALUE_IN_PLN], 0, ' '),
			Constants::TILE_ASCENDEX_PNL_TODAY       => Utils::formattedNumber($todaysAscendexPNLinPln, 0, ' '),
			Constants::TILE_ASCENDEX_PNL_DELTA_TODAY => Utils::formattedNumber($todaysAscendexDeltaPercentsFromPln, 2),

			Constants::TILE_COINBASE_BALANCE         => Utils::formattedNumber($lastSnapshot[Constants::KEY_COINBASE_VALUE_IN_PLN], 0, ' '),
			Constants::TILE_COINBASE_PNL_TODAY       => Utils::formattedNumber($todaysCoinbasePNLinPln, 0, ' '),
			Constants::TILE_COINBASE_PNL_DELTA_TODAY => Utils::formattedNumber($todaysCoinbaseDeltaPercentsFromPln, 2),

			Constants::TILE_KUCOIN_BALANCE         => Utils::formattedNumber($lastSnapshot[Constants::KEY_KUCOIN_VALUE_IN_PLN], 0, ' '),
			Constants::TILE_KUCOIN_PNL_TODAY       => Utils::formattedNumber($todaysKucoinPNLinPln, 0, ' '),
			Constants::TILE_KUCOIN_PNL_DELTA_TODAY => Utils::formattedNumber($todaysKucoinDeltaPercentsFromPln, 2),


			Constants::TILE_YESTERDAY_TOTAL_BALANCE => Utils::formattedNumber($yesterdaysSnapshot[Constants::KEY_VALUE_IN_PLN], 0, ' '),

			Constants::KEY_ROI_IN_PLN      => Utils::formattedNumber($roiInPln, 0, ' '),
			Constants::KEY_ROI_IN_PERCENTS => Utils::formattedNumber($roiInPercents, 2, ' '),

		];

		$portfolioTotals = PortfolioTotal::selectRaw('cast(snapshot_time as date) as snapshot_time, cast(value_in_pln as integer) as value_in_pln, value_in_btc')
			->whereRaw('HOUR(snapshot_time)=0 and minute(snapshot_time)=0')->get();
		$totalsInPln = $portfolioTotals->pluck('value_in_pln', 'snapshot_time')->toArray();
		$lineChart = $this->extractChartsLabelsAndDatasets($totalsInPln);

		$retData = [
			'lastSnapshotTime'         => $lastSnapshotTime, // DateTime->format('Y-m-d H:i:s'),
			'tiles'                    => $tiles,
			'topGainers'               => $topGainers,
			'topLosers'                => $topLosers,
//			'nextUpdate'               => $nextUpdate->format('Y-m-d H:i:s'),
			'currentPortfolioSnapshot' => self::optimize($currentPortfolioSnapshotTable),
			'profitAndLosses'          => $profitAndLosses,
			'lineChart'                => $lineChart,
		];

		return view('pages.dashboard', $retData);

	}

	private static function optimize(array $a): array {
		$ret = [];
		foreach ($a as $snapshot) {
			if ($snapshot['value_in_pln'] > 5) {
				$ret[] = $snapshot;
			}
		}

		return $ret;
	}

	private static function loadValuesForTiles($portfolioSnapshot) {
		$tilesValues[Constants::KEY_VALUE_IN_PLN] = 0;
		$tilesValues[Constants::KEY_VALUE_IN_USD] = 0;
		$tilesValues[Constants::KEY_BINANCE_VALUE_IN_PLN] = 0;
		$tilesValues[Constants::KEY_BINANCE_VALUE_IN_USD] = 0;
		$tilesValues[Constants::KEY_METAMASK_VALUE_IN_PLN] = 0;
		$tilesValues[Constants::KEY_METAMASK_VALUE_IN_USD] = 0;
		$tilesValues[Constants::KEY_BEP20_VALUE_IN_PLN] = 0;
		$tilesValues[Constants::KEY_BEP20_VALUE_IN_USD] = 0;
		$tilesValues[Constants::KEY_MXC_VALUE_IN_PLN] = 0;
		$tilesValues[Constants::KEY_MXC_VALUE_IN_USD] = 0;
		$tilesValues[Constants::KEY_BITBAY_VALUE_IN_PLN] = 0;
		$tilesValues[Constants::KEY_BITBAY_VALUE_IN_USD] = 0;
		$tilesValues[Constants::KEY_POLYGON_VALUE_IN_PLN] = 0;
		$tilesValues[Constants::KEY_POLYGON_VALUE_IN_USD] = 0;
		$tilesValues[Constants::KEY_ASCENDEX_VALUE_IN_PLN] = 0;
		$tilesValues[Constants::KEY_ASCENDEX_VALUE_IN_USD] = 0;
		$tilesValues[Constants::KEY_COINBASE_VALUE_IN_PLN] = 0;
		$tilesValues[Constants::KEY_COINBASE_VALUE_IN_USD] = 0;
		$tilesValues[Constants::KEY_KUCOIN_VALUE_IN_PLN] = 0;
		$tilesValues[Constants::KEY_KUCOIN_VALUE_IN_USD] = 0;
		$tilesValues[Constants::KEY_ROI_IN_PLN] = 0;
		$tilesValues[Constants::KEY_ROI_IN_PERCENTS] = 0;
		$tilesValues[Constants::TILE_STABLECOINS_BALANCE] = 0;
		$tilesValues[Constants::TILE_STABLECOINS_IN_PERCENTS] = 0;

		if ( ! isset($portfolioSnapshot) || ($portfolioSnapshot == null)) {
			return [];
		}

		foreach ($portfolioSnapshot as $assetSnapshot) {
			$tilesValues[Constants::KEY_VALUE_IN_PLN] += $assetSnapshot[Constants::KEY_VALUE_IN_PLN];
			$tilesValues[Constants::KEY_VALUE_IN_USD] += $assetSnapshot[Constants::KEY_VALUE_IN_USD];
			if ($assetSnapshot['source'] == 1) { // todo: pobieranie na podstawie slownika dla source w db
				$tilesValues[Constants::KEY_BINANCE_VALUE_IN_PLN] += $assetSnapshot[Constants::KEY_VALUE_IN_PLN];
				$tilesValues[Constants::KEY_BINANCE_VALUE_IN_USD] += $assetSnapshot[Constants::KEY_VALUE_IN_USD];
			} else if ($assetSnapshot['source'] == 2) {
				$tilesValues[Constants::KEY_METAMASK_VALUE_IN_PLN] += $assetSnapshot[Constants::KEY_VALUE_IN_PLN];
				$tilesValues[Constants::KEY_METAMASK_VALUE_IN_USD] += $assetSnapshot[Constants::KEY_VALUE_IN_USD];
			} else if ($assetSnapshot['source'] == 3) {
				$tilesValues[Constants::KEY_MXC_VALUE_IN_PLN] += $assetSnapshot[Constants::KEY_VALUE_IN_PLN];
				$tilesValues[Constants::KEY_MXC_VALUE_IN_USD] += $assetSnapshot[Constants::KEY_VALUE_IN_USD];
			} else if ($assetSnapshot['source'] == 6) {
				$tilesValues[Constants::KEY_BITBAY_VALUE_IN_PLN] += $assetSnapshot[Constants::KEY_VALUE_IN_PLN];
				$tilesValues[Constants::KEY_BITBAY_VALUE_IN_USD] += $assetSnapshot[Constants::KEY_VALUE_IN_USD];
			} else if ($assetSnapshot['source'] == 5) {
				$tilesValues[Constants::KEY_BEP20_VALUE_IN_PLN] += $assetSnapshot[Constants::KEY_VALUE_IN_PLN];
				$tilesValues[Constants::KEY_BEP20_VALUE_IN_USD] += $assetSnapshot[Constants::KEY_VALUE_IN_USD];
			} else if ($assetSnapshot['source'] == PortfolioSnapshot::SOURCES['polygon']) {
				$tilesValues[Constants::KEY_POLYGON_VALUE_IN_PLN] += $assetSnapshot[Constants::KEY_VALUE_IN_PLN];
				$tilesValues[Constants::KEY_POLYGON_VALUE_IN_USD] += $assetSnapshot[Constants::KEY_VALUE_IN_USD];
			} else if ($assetSnapshot['source'] == PortfolioSnapshot::SOURCES['ascendex']) {
				$tilesValues[Constants::KEY_ASCENDEX_VALUE_IN_PLN] += $assetSnapshot[Constants::KEY_VALUE_IN_PLN];
				$tilesValues[Constants::KEY_ASCENDEX_VALUE_IN_USD] += $assetSnapshot[Constants::KEY_VALUE_IN_USD];
			} else if ($assetSnapshot['source'] == PortfolioSnapshot::SOURCES['coinbase']) {
				$tilesValues[Constants::KEY_COINBASE_VALUE_IN_PLN] += $assetSnapshot[Constants::KEY_VALUE_IN_PLN];
				$tilesValues[Constants::KEY_COINBASE_VALUE_IN_USD] += $assetSnapshot[Constants::KEY_VALUE_IN_USD];
			} else if ($assetSnapshot['source'] == PortfolioSnapshot::SOURCES['kucoin']) {
				$tilesValues[Constants::KEY_KUCOIN_VALUE_IN_PLN] += $assetSnapshot[Constants::KEY_VALUE_IN_PLN];
				$tilesValues[Constants::KEY_KUCOIN_VALUE_IN_USD] += $assetSnapshot[Constants::KEY_VALUE_IN_USD];
			}

			// STABLECOINS TILE

		}
		unset($assetSnapshot);


		return $tilesValues;
	}

	private static function safeDiff(array $formerSnapshot, array $latterSnapshot, string $key) {
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
