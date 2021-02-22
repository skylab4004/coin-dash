<?php

namespace App\Http\Resources;

use App\Models\PortfolioSnapshot;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DailyPortfolioSnapshotCollection extends ResourceCollection {

	public $collects = PortfolioSnapshot::class;

	/**
	 * Transform the resource collection into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
//	public function toArray($request) {
//		return parent::toArray($request);
//	}
}
