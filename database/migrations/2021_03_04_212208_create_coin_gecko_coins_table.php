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
    public function up()
    {
        Schema::create('coin_gecko_coins', function (Blueprint $table) {
            $table->id();
            // "id": "dexfin",
			//    "symbol": "dxf",
			//    "name": "Dexfin"
			$table->string('gecko_id');
			$table->string('gecko_symbol');
			$table->string('gecko_name');
			$table->string('eth_contract_id')->nullable();
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
