<?php namespace App\Utils;

use GraphQL\Client;

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

	public function uniswapPrice2() {
		$client = new Client(
			'https://api.thegraph.com/subgraphs/name/uniswap/uniswap-v2',
			[],
			[],
		);

		$gql = <<<QUERY
  query bundles {
    bundles(where: { id: "1" }) {
      ethPrice
    }
  }
QUERY;

		return $client->runRawQuery($gql);
	}

	public function uniswapPrice($tokenAddress) {

		$client = new Client(
			'https://api.thegraph.com/subgraphs/name/uniswap/uniswap-v2',
			[],
			[],
		);

		$gql = <<<QUERY
  query tokens {
    tokens(where: { id: "{$tokenAddress}" }) {
      symbol
      derivedETH
      totalLiquidity
    }
  }
QUERY;

		$results = $client->runRawQuery($gql);
		$derivedETH = (float) $results->getData()->tokens[0]->derivedETH;

		$gql = <<<QUERY
  query bundles {
    bundles(where: { id: "1" }) {
      ethPrice
    }
  }
QUERY;
		$results = $client->runRawQuery($gql);
		$ethPrice = (float) $results->getData()->bundles[0]->ethPrice;

		return $derivedETH * $ethPrice;
	}
}