<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinGeckoCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('coin_gecko_coins', function (Blueprint $table) {
            $table->id();
			$table->string('gecko_id')->unique(); // "id": "dexfin",
			$table->string('gecko_symbol')->unique(); // "symbol": "dxf",
			$table->string('gecko_name'); //    "name": "Dexfin"
			$table->string('platform')->nullable();
			$table->string('contract_address')->nullable();
			$table->string('cg_url')->nullable();
			$table->string('trade_url')->nullable();
			$table->string('img_url')->nullable();
			$table->string('chart_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coin_gecko_coins');
    }
}
