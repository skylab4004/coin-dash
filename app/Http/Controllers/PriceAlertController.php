<?php

namespace App\Http\Controllers;

use App\Models\PriceAlert;
use Carbon\Carbon;

class PriceAlertController extends Controller {

	//$table->id();
	//$table->string('symbol');
	//$table->decimal('threshold', 30, 10);
	//$table->tinyInteger('condition');
	//$table->decimal('last_price', 30, 10);
	//$table->dateTimeTz('last_price_time')->useCurrent();
	//$table->boolean('triggered')->default(0);
	//$table->tinyInteger('price_source');
	//$table->timestamps();

	public function addPriceAlert($symbol, $threshold, $condition, $price_source) {
		$alert = new PriceAlert();
		$alert->symbol = $symbol;
		$alert->threshold = $threshold;
		$alert->condition = $condition;
		$alert->price_source = $price_source;
		$alert->save();
	}

	public function updateLastPrice($symbol, $price) {
		return PriceAlert::where('symbol', $symbol)
			->update(['last_price' => $price, 'last_price_time' => Carbon::now()]);
	}

	public function getSymbols() {
		$symbols = PriceAlert::select('symbol')->distinct()->get()->toArray();
		return array_column($symbols, 'symbol');
	}
}
