<?php namespace App\Http\Controllers\Bot;

class ZeroXApiClient {

	public function swapQuote(string $sellTokenAddress, string $buyTokenAddress, $buyAmount, $sellAmount) {
		$apiUrl = "https://bsc.api.0x.org/swap/v1/quote?buyToken={$buyTokenAddress}&sellToken={$sellTokenAddress}&buyAmount={$buyAmount}&sellAmount={$sellAmount}&excludedSources=BakerySwap,Belt,DODO,DODO_V2,Ellipsis,Mooniswap,MultiHop,Nerve,SushiSwap,Smoothy,ApeSwap,CafeSwap,CheeseSwap,JulSwap,LiquidityProvider&slippagePercentage=0&gasPrice=0";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_URL, $apiUrl);
		$ret = curl_exec($curl);
		$json_decode = json_decode($ret, true);

		return $json_decode["guaranteedPrice"];
	}

}