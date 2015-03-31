<?php namespace PCextreme\CloudstackClient\Query\Processors;

use Kevindierkx\Elicit\Query\Builder;
use Kevindierkx\Elicit\Query\Processors\Processor;

class CloudstackProcessor extends Processor {

	/**
	 * Process the results of an API request.
	 *
	 * @param  \Kevindierkx\Elicit\Query\Builder  $query
	 * @param  array  $results
	 * @return array
	 */
	protected function processRequest(Builder $query, $results)
	{
		// We assume that the first where is always the request method.
		// This could change in the future and should be changed.
		$method = reset($query->wheres)['value'];

		$resultsResourceName = strtolower($method . 'response');

		if ( starts_with($method, 'list') && empty($results[$resultsResourceName]) ) {
			return [];
		}

		if ( isset($results[$resultsResourceName]) && count($results[$resultsResourceName]) >= 2 ) {
			return array_pop($results[$resultsResourceName]);
		}
	}

}
