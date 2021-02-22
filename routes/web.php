<?php

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

Route::get('/', function () {
//    return view('welcome');
    return view('home');
});

Route::get('/portfolio-piechart', function () {
    return view('portfolio_piechart');
});

Route::get('/portfolio-snapshots', function() {
	return view('portfolio_snapshots');
});

Route::get('/portfolio-totals-chart', function() {
	return view('portfolio_totals_chart', ["assetValues" => "[3, 2, 1]", "labelValues" => "['abc', 'def', 'ghi']"]);
});
