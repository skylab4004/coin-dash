<?php namespace App\Http\Controllers\API\Polygonscan;
class PolygonscanAPIConf {

	const API_URL = "https://api.polygonscan.com/api";

	/**
	 * Returns API basic URL.
	 *
	 * @param string $net Mainnet - if null, or testnet if provided.
	 *
	 * @return string
	 */
	public static function getAPIUrl($net = null) {
		if (is_null($net)) {
			return self::API_URL;
		}

		return "https://{$net}.polygonscan.com/api";
	}

	const TAG_LATEST = "latest";

	const BLOCK_TYPE_BLOCKS = "blocks";
	const BLOCK_TYPE_UNCLES = "uncles";

	public static $blockTypes = [
		self::BLOCK_TYPE_BLOCKS, self::BLOCK_TYPE_UNCLES
	];

}
