<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeckoDumpsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('gecko_dumps', function(Blueprint $table) {
			$table->id()->nullable();
			$table->text('gecko_id', 255);
			$table->text('symbol', 255);
			$table->text('name', 255);
			$table->text('image', 1024)->nullable();
			$table->decimal('current_price', 40, 18)->nullable();
			$table->bigInteger('market_cap')->nullable();
			$table->integer('market_cap_rank')->nullable();
			$table->bigInteger('fully_diluted_valuation')->nullable();
			$table->bigInteger('total_volume')->nullable();
			$table->decimal('high_24h', 40, 18)->nullable();
			$table->decimal('low_24h', 40, 18)->nullable();
			$table->decimal('price_change_24h', 40, 18)->nullable();
			$table->decimal('price_change_percentage_24h', 40, 18)->nullable();
			$table->decimal('market_cap_change_24h', 40, 18)->nullable();
			$table->decimal('market_cap_change_percentage_24h', 40, 18)->nullable();
			$table->bigInteger('circulating_supply')->nullable();
			$table->bigInteger('total_supply')->nullable();
			$table->bigInteger('max_supply')->nullable();
			$table->decimal('ath', 40, 18)->nullable();
			$table->decimal('ath_change_percentage', 40, 18)->nullable();
			$table->timestamp('ath_date')->nullable();
			$table->decimal('atl', 40, 18)->nullable();
			$table->decimal('atl_change_percentage', 40, 18)->nullable();
			$table->timestamp('atl_date')->nullable();
			$table->decimal('roi', 40, 18)->nullable();
			$table->timestamp('last_updated')->nullable();
			$table->decimal('price_change_percentage_1h_in_currency', 40, 18)->nullable();
			$table->decimal('price_change_percentage_200d_in_currency', 40, 18)->nullable();
			$table->decimal('price_change_percentage_24h_in_currency', 40, 18)->nullable();
			$table->decimal('price_change_percentage_30d_in_currency', 40, 18)->nullable();
			$table->decimal('price_change_percentage_7d_in_currency', 40, 18)->nullable();
			$table->text('asset_platform_id', 255)->nullable();
			$table->integer('block_time_in_minutes')->nullable();
			$table->text('public_notice', 255)->nullable();
			$table->text('additional_notices', 4096)->nullable();
			$table->text('description', 4096)->nullable();
			$table->date('genesis_date')->nullable();
			$table->integer('sentiment_votes_up_percentage')->nullable();
			$table->integer('sentiment_votes_down_percentage')->nullable();
			$table->integer('coingecko_rank')->nullable();
			$table->double('coingecko_score')->nullable();
			$table->double('developer_score')->nullable();
			$table->double('community_score')->nullable();
			$table->double('liquidity_score')->nullable();
			$table->double('public_interest_score')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('gecko_dumps');
	}
}
