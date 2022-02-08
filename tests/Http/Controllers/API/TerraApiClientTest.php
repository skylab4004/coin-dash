<?php namespace Tests\Http\Controllers\API;

use App\Http\Controllers\API\TerraApiClient;
use PHPUnit\Framework\TestCase;

class TerraApiClientTest extends TestCase {

	private static $apiClient;

	public static function setUpBeforeClass(): void {
		self::$apiClient = new TerraApiClient();
	}

	public function testGetAccounts() {
		$info = $this::$apiClient->getBalancesRaw();
		print_r($info);
		self::assertIsArray($info);
	}


	public function testGetBalances() {
		$info = $this::$apiClient->getBalances();
		print_r($info);
		self::assertIsArray($info);
	}

	public function testGetDenoms() {
		$info = $this::$apiClient->getDenoms();
		print_r($info);
		self::assertIsArray($info);
	}

	public function testGetTokenBalanceRaw() {
		$contractAddress = "terra1hzh9vpxhsk8253se0vv5jj6etdvxu3nv8z07zu"; // aUST
//		$contractAddress = "terra14z56l0fp2lsf86zy3hty2z47ezkhnthtr9yq76"; // ANC
		$info = $this::$apiClient->getTokenBalanceRaw($contractAddress);
		print_r($info);
		self::assertIsArray($info);
	}

	public function testGetTokenInfoRaw() {
		$contractAddress = "terra1hzh9vpxhsk8253se0vv5jj6etdvxu3nv8z07zu"; // aUST
//		$contractAddress = "terra14z56l0fp2lsf86zy3hty2z47ezkhnthtr9yq76"; // ANC
		$info = $this::$apiClient->getTokenInfoRaw($contractAddress);
		print_r($info);
		self::assertIsArray($info);
	}

	public function testGetTokenBalance() {
		$contractAddress1 = "terra1hzh9vpxhsk8253se0vv5jj6etdvxu3nv8z07zu"; // aUST
		$contractAddress2 = "terra14z56l0fp2lsf86zy3hty2z47ezkhnthtr9yq76"; // ANC
		$info = $this::$apiClient->getTokenBalance([$contractAddress1, $contractAddress2]);
//		$info = $this::$apiClient->getTokenBalance($contractAddress1);
		print_r($info);
		self::assertIsArray($info);
	}


}
