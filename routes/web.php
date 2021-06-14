<?php

use App\Http\Controllers\PortfolioCoinController;
use App\Http\Controllers\PriceAlertController;
use App\Http\Controllers\ProvisionCharts;
use App\Http\Controllers\ProvisionDashboard;
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

Route::get('/charts', [ProvisionCharts::class, 'show'])->name('charts');

Route::get('about', function () {
	return view('pages.about');
})->name('about');

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
