<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceAlert extends Model {

	protected $fillable = ['symbol', 'contract_address', 'threshold', 'condition', 'price_source'];

	use HasFactory;

	public const PRICE_SOURCES = [
		'uniswap' => 1,
	];

	public const CONDITIONS = [
		'greater' => 0,
		'lesser'  => 1,
	];

}
