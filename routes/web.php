<?php

use App\Http\Controllers\PortfolioCoinController;
use App\Http\Controllers\PriceAlertController;
use App\Http\Controllers\ProvisionCharts;
use App\Http\Controllers\ProvisionDashboard;
use App\Http\Controllers\ProvisionPortfolioWallet;
use App\Http\Controllers\ProvisionPortfolioOverview;
use App\Models\PortfolioSnapshot;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ProvisionDashboard::class, 'show'])->name('dashboard');

Route::get('/portfolio/overview', [ProvisionPortfolioOverview::class, 'show'])->name('portfolio-overview');

Route::get('/portfolio/static', function() {
	return App::call('App\Http\Controllers\ProvisionPortfolioWallet@show',
		['sourceId' => PortfolioSnapshot::SOURCES['static']]);
})->name('portfolio-static');

Route::get('/portfolio/binance', function() {
	return App::call('App\Http\Controllers\ProvisionPortfolioWallet@show',
		['sourceId' => PortfolioSnapshot::SOURCES['binance']]);
})->name('portfolio-binance');

Route::get('/portfolio/ethereum', function() {
	return App::call('App\Http\Controllers\ProvisionPortfolioWallet@show',
		['sourceId' => PortfolioSnapshot::SOURCES['erc20']]);
})->name('portfolio-ethereum');

Route::get('/portfolio/mexc', function() {
	return App::call('App\Http\Controllers\ProvisionPortfolioWallet@show',
		['sourceId' => PortfolioSnapshot::SOURCES['mexc']]);
})->name('portfolio-mexc');

Route::get('/portfolio/bsc', function() {
	return App::call('App\Http\Controllers\ProvisionPortfolioWallet@show',
		['sourceId' => PortfolioSnapshot::SOURCES['bsc20']]);
})->name('portfolio-bsc');

Route::get('/portfolio/bitbay', function() {
	return App::call('App\Http\Controllers\ProvisionPortfolioWallet@show',
		['sourceId' => PortfolioSnapshot::SOURCES['bitbay']]);
})->name('portfolio-bitbay');

Route::get('/portfolio/polygon', function() {
	return App::call('App\Http\Controllers\ProvisionPortfolioWallet@show',
		['sourceId' => PortfolioSnapshot::SOURCES['polygon']]);
})->name('portfolio-polygon');

Route::get('/portfolio/ascendex', function() {
	return App::call('App\Http\Controllers\ProvisionPortfolioWallet@show',
		['sourceId' => PortfolioSnapshot::SOURCES['ascendex']]);
})->name('portfolio-ascendex');

Route::get('/portfolio/coinbase', function() {
	return App::call('App\Http\Controllers\ProvisionPortfolioWallet@show',
		['sourceId' => PortfolioSnapshot::SOURCES['coinbase']]);
})->name('portfolio-coinbase');

Route::get('/portfolio/kucoin', function() {
	return App::call('App\Http\Controllers\ProvisionPortfolioWallet@show',
		['sourceId' => PortfolioSnapshot::SOURCES['kucoin']]);
})->name('portfolio-kucoin');

Route::get('/portfolio/terra', function() {
	return App::call('App\Http\Controllers\ProvisionPortfolioWallet@show',
		['sourceId' => PortfolioSnapshot::SOURCES['terra']]);
})->name('portfolio-terra');

Route::get('/charts', [ProvisionCharts::class, 'show'])->name('charts');

Route::get('/links', function () {
	return view('pages.links');
})->name('links');


Route::resource('price-alerts', PriceAlertController::class)->middleware(['auth']);

Route::resource('portfolio-coins', PortfolioCoinController::class)->middleware(['auth']);

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

//Route::get('/', function () {
//    return view('welcome');
//});

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
