<?php namespace PCextreme\CloudstackClient\Connectors;

use GuzzleHttp\Message\Response;
use Kevindierkx\Elicit\Connector\Connector;
use Kevindierkx\Elicit\Connector\ConnectorInterface;

class CloudstackConnector extends Connector implements ConnectorInterface {

	/**
	 * {@inheritdoc}
	 */
	public function connect(array $config)
	{
		$connection = $this->createConnection($config);

		return $connection;
	}

	/**
	 * Parse the returned response.
	 *
	 * @param  \GuzzleHttp\Message\Response  $response
	 * @return array
	 *
	 * @throws \RuntimeException
	 */
	protected function parseResponse(Response $response)
	{
		$contentType = explode(';', $response->getHeader('content-type'))[0];

		switch ($contentType) {
			case 'text/javascript':
			case 'application/json':
				return $response->json();

			case 'application/xml':
				return $response->xml();
		}

		throw new \RuntimeException("Unsupported returned content-type [$contentType]");
	}

}
