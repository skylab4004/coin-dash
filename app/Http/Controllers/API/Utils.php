<?php namespace App\Http\Controllers\API;

class Utils {

	public static function millisToTimestamp($millis) {
		return date("Y-m-d H:i:s", $millis / 1000);
	}

	public static function millisToShortTimestamp($millis) {
		return date("Y-m-d H:i", $millis / 1000);
	}


	public static function millisToDate($millis) {
		return date("Y-m-d", $millis / 1000);
	}

	/**
	 * @param $numeric
	 * @return string
	 */
	public static function formattedNumber($numeric, $decimals = 8): string {
		return number_format($numeric, $decimals, '.', '');
	}

	// number_format($num, 2, '.', '')

	/**
	 * @return float
	 */
	public static function nowMillis(): float {
		return round(microtime(true) * 1000);
	}

	public static function removeElementWithValue($array, $key, $value) {
		foreach ($array as $subKey => $subArray) {
			if ($subArray[$key] == $value) {
				unset($array[$subKey]);
			}
		}

		return $array;
	}

	public static function currentTimeInMillis() {
		return (integer) (microtime(true) * 1000);
	}
}