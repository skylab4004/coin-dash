<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\Utils;
use Decimal\Decimal;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase {

	public function testSafeQuotedString() {
		$safeString = Utils::safeQuotedString(array("     a\nbc  ", "dupa"));
		self::assertSame('"a<br> bc dupa"', $safeString);
	}

	public function testSafeQuotedString2() {
		$safeString = Utils::safeQuotedString(array("     a\nbc  ", "dupa"),2);
		self::assertSame('""', $safeString);
	}

	public function testSafeFloat() {
		$safeNumber = Utils::safeFloat("4.3971564709746E+41");
		self::assertEquals(0, $safeNumber);
	}

	public function testSafeNumber2() {
		// echo sprintf('%f', floatval('-1.0E-5'));//default 6 decimal places
		//echo sprintf('%.8f', floatval('-1.0E-5'));//force 8 decimal places
		//echo rtrim(sprintf('%f',floatval(-1.0E-5)),'0');//remove trailing zeros

		$x = Decimal::avg(4.3971564709746E+41);
//		echo sprintf('%.8f', floatval('"4.3971564709746E+41"'));

//		self::assertEquals(4.3971564709746E+41, $safeNumber);
	}
}
