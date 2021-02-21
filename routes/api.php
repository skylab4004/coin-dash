<?php

use App\Http\Resources\PortfolioSnapshotCollection;
use App\Http\Resources\PortfolioSnapshotResource;
use App\Models\PortfolioSnapshot;
use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/portolio-snapshot/{id}', function ($id) {
	return new PortfolioSnapshotResource(PortfolioSnapshot::findOrFail($id));
});

Route::get('/portolio-snapshots', function () {
	return new PortfolioSnapshotCollection(PortfolioSnapshot::all());
});
