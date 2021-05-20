<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaticPortfolioCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('static_portfolio_coins', function (Blueprint $table) {
            $table->id();
			$table->string('symbol')->unique(); // "symbol": "dxf",
			$table->decimal('quantity', 30, 10);
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
        Schema::dropIfExists('static_portfolio_coins');
    }
}
