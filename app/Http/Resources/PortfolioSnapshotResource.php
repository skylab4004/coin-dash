<?php

namespace App\Http\Resources;

use App\Http\Controllers\API\Utils;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioSnapshotResource extends JsonResource {

	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function toArray($request) {
		$ret = ['snapshot_time' => Utils::millisToShortTimestamp($this->snapshot_time),
				'asset'         => $this->asset,
				'source'        => $this->source,
				'quantity'      => $this->quantity,
				'value_in_pln'  => $this->value_in_pln,
				'value_in_usd'  => $this->value_in_usd];

		return $ret;
	}
}
