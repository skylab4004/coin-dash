<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
			$table->string('contract_address')->nullable();
			$table->decimal('threshold', 30, 10);
			$table->tinyInteger('condition');
			$table->decimal('last_price', 30, 10)->nullable();
			$table->dateTimeTz('last_price_time')->nullable();
			$table->boolean('triggered')->default(0);
			$table->tinyInteger('price_source');
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
        Schema::dropIfExists('price_alerts');
    }
}
