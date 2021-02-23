<?php

use App\Http\Resources\PortfolioSnapshotCollection;
use App\Http\Resources\PortfolioSnapshotResource;
use App\Models\PortfolioSnapshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function(Request $request) {
	return $request->user();
});

Route::get('/portfolio-snapshot/{id}', function($id) {
	return new PortfolioSnapshotResource(PortfolioSnapshot::findOrFail($id));
});

Route::get('/portfolio-snapshots', function() {
	return new PortfolioSnapshotCollection(PortfolioSnapshot::all());
});

# Dashboard
Route::get('/portfolio-snapshot-current', function() {
	$lastSnapshotTime = PortfolioSnapshot::all()->max('snapshot_time');

	return new PortfolioSnapshotCollection(PortfolioSnapshot::where('snapshot_time', $lastSnapshotTime)
		->OrderBy('value_in_pln', 'desc')
		->get());
});

Route::get('/portfolio-snapshot-yesterday', function() {
	$lastSnapshotTimeYesterday = DB::table('portfolio_snapshots')
		->whereRaw('CAST(FROM_UNIXTIME(snapshot_time/1000) AS DATE) = DATE(NOW()-INTERVAL 1 DAY)')
		->max('snapshot_time');

	return new PortfolioSnapshotCollection(PortfolioSnapshot::where('snapshot_time', $lastSnapshotTimeYesterday)
		->OrderBy('value_in_pln', 'desc')
		->get());
});

Route::get('/portfolio-snapshot-daily/{date}', function($date) {
	$lastSnapshotTimeThatDay = DB::table('portfolio_snapshots')
		->whereRaw('CAST(FROM_UNIXTIME(snapshot_time/1000) AS DATE) = DATE('.$date.')')
		->max('snapshot_time');

	return new PortfolioSnapshotCollection(PortfolioSnapshot::where('snapshot_time', $lastSnapshotTimeThatDay)
		->OrderBy('value_in_pln', 'desc')
		->get());
});
