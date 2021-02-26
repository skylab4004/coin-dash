<?php

use App\Http\Controllers\ProvisionDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProvisionDashboard::class, 'show']);

Route::get('/portfolio-piechart', function() {
	return view('portfolio_piechart');
});

Route::get('/portfolio-snapshots', function() {
	return view('portfolio_snapshots');
});

Route::get('/portfolio-totals-chart', function() {
	return view('portfolio_totals_chart', ["assetValues" => "[3, 2, 1]", "labelValues" => "['abc', 'def', 'ghi']"]);
});

Route::get('/portfolio-snapshot-current', function() {
	return view('portfolio_snapshot_current');
});