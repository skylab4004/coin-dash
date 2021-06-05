<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortfolioCoinsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('portfolio_coins', function(Blueprint $table) {
			$table->id();
			$table->string('gecko_id')->unique(); // "id": "dexfin",
			$table->string('symbol')->unique(); // "symbol": "dxf",
			$table->string('gecko_name'); //    "name": "Dexfin"
			$table->json('platforms')->nullable();
			$table->string('cg_url', 2048)->nullable();
			$table->string('trade_url', 2048)->nullable();
			$table->string('img_url', 2048)->nullable();
			$table->string('chart_url', 2048)->nullable();
			$table->integer('price_source')->nullable(); // null,0 -> coingecko; 1 -> uniswap;
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('portfolio_coins');
	}
}
