<?php

use App\Http\Controllers\ProvisionCharts;
use App\Http\Controllers\ProvisionDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProvisionDashboard::class, 'show'])->name('dashboard');

Route::get('/charts', [ProvisionCharts::class, 'show'])->name('charts');

Route::get('about', function () {
	return view('pages.about');
})->name('about');

Route::get('/metamask', function() {
	return view('pages.metamask');
})->name('metamask');

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

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');