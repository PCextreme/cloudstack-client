<?php namespace PCextreme\CloudstackClient\Query\Grammars;

use Kevindierkx\Elicit\Query\Builder;
use Kevindierkx\Elicit\Query\Grammars\Grammar;

class CloudstackGrammar extends Grammar
{
    /**
     * Compile the "where" portions of the query.
     *
     * @param  \Kevindierkx\Elicit\Query\Builder  $query
     * @return string
     */
    protected function compileWheres(Builder $query)
    {
        $connection = $query->getConnection();

        $wheres = [
            'apiKey' => $connection->getConfig('identifier'),
            'timestamp' => round(microtime(true) * 1000),
            'response' => 'json',
        ];

        foreach ($query->wheres as $where) {
            $wheres[$where['column']] = $where['value'];
        }

        // The secret used for signing the request. Depending on the request this
        // is either the user API secret or the SSO key.
        $secret = $connection->getConfig('secret');

        ksort($wheres);

        $parsedWheres = http_build_query($wheres, false, '&', PHP_QUERY_RFC3986);

        $signature = rawurlencode(
            base64_encode(
                hash_hmac('SHA1', strtolower($parsedWheres), $secret, true)
            )
        );

        return $parsedWheres . '&signature=' . $signature;
    }
}
