<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioSnapshot extends Model {

	public const SOURCES = [
		'binance' => 1,
		'erc20' => 2,
		'mexc' => 3,
		'bilaxy' => 4,
		'bsc20' => 5,
	];
	use HasFactory;
}
