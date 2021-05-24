<?php

use App\Http\Controllers\PriceAlertController;
use App\Http\Controllers\ProvisionCharts;
use App\Http\Controllers\ProvisionDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProvisionDashboard::class, 'show'])->name('dashboard');

Route::get('/charts', [ProvisionCharts::class, 'show'])->name('charts');

Route::get('about', function () {
	return view('pages.about');
})->name('about');


Route::resource('price-alerts', PriceAlertController::class);

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');