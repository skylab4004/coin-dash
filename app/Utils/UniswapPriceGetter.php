<?php namespace App\Utils;
class UniswapPriceGetter {

	public function getCliqPrice() {
		$graph = new GraphqlClient();
		$endpoint = 'https://api.thegraph.com/subgraphs/name/uniswap/uniswap-v2';
		$query = <<<GRAPHQL
	tokens {
		symbol
		derivedETH
		totalLiquidity
	}
GRAPHQL;

		return $graph->graphql_query($endpoint, $query);
	}

}