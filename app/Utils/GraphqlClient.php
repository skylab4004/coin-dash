<?php namespace App\Utils;

use ErrorException;

class GraphqlClient {

	function graphql_query(string $endpoint, string $query, array $variables = [], ?string $token = null): array {
		$headers = ['Content-Type: application/json', 'User-Agent: Dunglas\'s minimal GraphQL client'];
		if (null !== $token) {
			$headers[] = "Authorization: Bearer $token";
		}

		$stream_context_create = stream_context_create([
			'https' => [
				'method'  => 'POST',
				'header'  => $headers,
				'content' => json_encode(['query' => $query, 'variables' => $variables]),
			]
		]);

//		dd($stream_context_create);

		if (false === $data = @file_get_contents($endpoint, false, $stream_context_create)) {
			$error = error_get_last();
			throw new ErrorException($error['message'], $error['type']);
		}

		return json_decode($data, true);
	}
}