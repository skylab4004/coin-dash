<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\Utils;
use App\Models\PortfolioSnapshot;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\DB;

class ProvisionDashboard extends Controller {

	public function show() {
		// CURRENT portfolio value and totals in PLN and USD0
		$lastSnapshotTime = PortfolioSnapshot::max('snapshot_time');
		$lastSnapshotDateTime = new DateTime($lastSnapshotTime);
		$nextUpdate = $lastSnapshotDateTime;
		$nextUpdate->add(new DateInterval('P5M'));

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
		$lastSnapshotTimeYesterday = DB::table('portfolio_snapshots')
			->whereRaw('CAST(snapshot_time AS DATE) = DATE(NOW()-INTERVAL 1 DAY)')
			->max('snapshot_time');
		$yesterdaysLastPortfolioSnapshot = PortfolioSnapshot::where('snapshot_time', $lastSnapshotTimeYesterday)
			->OrderBy("value_in_pln", 'desc')
			->get();
		$yesterdaysSnapshot = self::loadValuesForTiles($yesterdaysLastPortfolioSnapshot);

		$query = <<<SQL
			select 
			       now.asset as asset, 
			       now.quantity as quantity, 
			       now.val as value_in_pln, 
			       5min.quantity-now.quantity as qty_delta_5min, 
			       5min.val-now.val as pnl_5min,
				   1h.quantity-now.quantity as qty_delta_1h, 
			       1h.val-now.val as pnl_1h,
				   3h.quantity-now.quantity as qty_delta_3h, 
			       3h.val-now.val as pnl_3h,
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
			$profitAndLoss->val = Utils::dashboardNumber($profitAndLoss->value_in_pln);
			$profitAndLoss->pnl_5_min = Utils::dashboardNumber($profitAndLoss->pnl_5min);
			$profitAndLoss->pnl_1h = Utils::dashboardNumber($profitAndLoss->pnl_1h);
			$profitAndLoss->pnl_3h = Utils::dashboardNumber($profitAndLoss->pnl_3h);
			$profitAndLoss->pnl_midnight = Utils::dashboardNumber($profitAndLoss->pnl_midnight);
		}
		unset($profitAndLoss);

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
			Constants::TILE_YESTERDAY_TOTAL_BALANCE  => Utils::formattedNumber($yesterdaysSnapshot[Constants::KEY_VALUE_IN_PLN], 0, ' ')
		];

		$retData = [
			'tiles'                    => $tiles,
			'lastSnapshotTime'         => $lastSnapshotDateTime->format('Y-m-d H:i:s'),
			'nextUpdate'               => $nextUpdate->format('Y-m-d H:i:s'),
			'currentPortfolioSnapshot' => self::optimize($currentPortfolioSnapshotTable),
			'profitAndLosses'          => $profitAndLosses,
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
			}
		}
		unset($assetSnapshot);

		return $tilesValues;
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
