<?php

namespace App\Http\Controllers;

use App\Models\PriceAlert;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;
use View;

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

	public function index() {
		$alerts = PriceAlert::all();
		return View::make('price-alerts.index')->with('priceAlerts', $alerts);
	}

	public function create() {
		return view('price-alerts.create');
	}

	public function store(Request $request) {
		$request->validate([
			'symbol' => 'required',
			'contract_address' => 'required',
			'threshold' => 'required',
			'condition' => 'required',
			'price_source' => 'required',
		]);

		PriceAlert::create($request->all());

		return redirect()->route('price-alerts.index')
			->with('success', 'Price alert created successfully.');
	}

	public function show($id) {
		$alert = PriceAlert::find($id);

		return View::make('price-alerts.show')->with('alert', $alert);
	}

	public function edit($id) {
		$alert = PriceAlert::find($id);
		return view('price-alerts.edit')->with('alert', $alert);
	}

	public function update($id) {
		//
	}

	public function destroy($id) {
		$priceAlert = PriceAlert::find($id);
		$priceAlert->delete();

		// redirect
		Session::flash('message', 'Successfully deleted the price alert!');
		return Redirect::to('price-alerts');
		//
	}

	// -------------------

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
