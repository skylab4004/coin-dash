<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ViewDailyPortfolioValues extends Migration {


	public function up() {
		DB::statement($this->createView());
	}

	public function down() {
		DB::statement($this->dropView());
	}

	private function createView(): string {
		return <<<SQL
			CREATE OR REPLACE VIEW daily_portfolio_values AS (
			SELECT
				snapshot_time,
				asset,
				sum(sum_pln) AS value_in_pln,
				sum(sum_usd) AS value_in_usd,
				sum(sum_btc) AS value_in_btc,
				sum(sum_eth) AS value_in_eth
			FROM (
				SELECT
				snapshot_time,
				asset,
				sum(value_in_pln) AS sum_pln,
				sum(value_in_usd) AS sum_usd,
				sum(value_in_btc) AS sum_btc,
				sum(value_in_eth) AS sum_eth
			FROM portfolio_snapshots  GROUP BY 1, 2
			UNION
			SELECT
				snapshot_time,
				asset,
				0, 0, 0, 0
			FROM
			(
				SELECT DISTINCT snapshot_time FROM portfolio_snapshots
			) AS snapshot_times,
			(
				SELECT DISTINCT asset FROM `portfolio_snapshots`) AS assets
				GROUP BY 1, 2
			) AS foo
			WHERE HOUR(snapshot_time) = 0 AND MINUTE(snapshot_time) = 0
			GROUP BY 1, 2
			ORDER BY 1, 2 ASC
			)
		SQL;
	}

	private function dropView(): string {
		return <<<SQL

            DROP VIEW IF EXISTS `daily_portfolio_values`;
            SQL;
	}
}
