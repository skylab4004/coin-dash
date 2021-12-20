<?php namespace Tests\Models;

use App\Models\PortfolioSnapshot;
use PHPUnit\Framework\TestCase;

class PortfolioSnapshotTest extends TestCase {

	public function testSources() {
		$string = array_search(3, PortfolioSnapshot::SOURCES);
		self::assertTrue(strcmp($string, "mexc") == 0);
	}

}
