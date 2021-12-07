<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortfolioTotalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolio_totals', function (Blueprint $table) {
            $table->id();
			$table->timestamp('snapshot_time');
			$table->decimal('value_in_btc', 30, 10);
			$table->decimal('value_in_eth', 30, 10);
			$table->decimal('value_in_usd', 30, 10);
			$table->decimal('value_in_pln', 30, 10);
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
        Schema::dropIfExists('portfolio_totals');
    }

//insert into portfolio_totals (snapshot_time, value_in_btc, value_in_eth, value_in_usd, value_in_pln, created_at, updated_at)
//select snapshot_time, sum(value_in_btc), sum(value_in_eth), sum(value_in_usd), sum(value_in_pln), now(), now() from portfolio_snapshots
//group by snapshot_time

}
