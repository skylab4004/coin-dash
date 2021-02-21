<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PortfolioSnapshotCollection extends ResourceCollection {

	/**
	 * Transform the resource collection into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function toArray($request) {
		return parent::toArray($request);
	}
	
	// data = {
	//        labels: generateLabels(), // snapshot_times
	//
	//        datasets: [{
	//                // coin
	//                data: generateData(), // asset values
	//                label: 'D0'
	//            },
}
