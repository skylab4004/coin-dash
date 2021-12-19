<?php namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Support\Facades\Log;

class PancakeSwapApiClient {

	// https://bsc.api.0x.org/swap/v1/quote?buyToken=BUSD&sellToken=0x68e374f856bf25468d365e539b700b648bf94b67&sellAmount=1000000000000000000&excludedSources=BakerySwap,Belt,DODO,DODO_V2,Ellipsis,Mooniswap,MultiHop,Nerve,SushiSwap,Smoothy,ApeSwap,CafeSwap,CheeseSwap,JulSwap,LiquidityProvider&slippagePercentage=0&gasPrice=0

	public function getTokenPrice(string $tokenAddress) {
		try {
			$apiUrl = "https://bsc.api.0x.org/swap/v1/quote?buyToken=BUSD&sellToken={$tokenAddress}&sellAmount=1000000000000000000&excludedSources=BakerySwap,Belt,DODO,DODO_V2,Ellipsis,Mooniswap,MultiHop,Nerve,SushiSwap,Smoothy,ApeSwap,CafeSwap,CheeseSwap,JulSwap,LiquidityProvider&slippagePercentage=0&gasPrice=0";
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_URL, $apiUrl);
			$ret = curl_exec($curl);

			$json_decode = json_decode($ret, true, 512, JSON_THROW_ON_ERROR);

			if (array_key_exists("guaranteedPrice", $json_decode)) {
				return $json_decode["guaranteedPrice"];
			}
		} catch (Exception $ex) {
			Log::error($ex, array('json' => $ret, 'json_decode' => $json_decode));
		}
		return -1;
	}

}