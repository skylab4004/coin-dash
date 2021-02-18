<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortfolioSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolio_snapshots', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('snapshot_time');
			$table->string('asset', 12);
			$table->decimal('quantity', 30, 10);
			$table->decimal('value_in_btc', 30, 10);
			$table->decimal('value_in_eth', 30, 10);
			$table->decimal('value_in_usd', 30, 10);
			$table->decimal('value_in_pln', 30, 10)->nullable();
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
        Schema::dropIfExists('portfolio_snapshots');
    }
}
