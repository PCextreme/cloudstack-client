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

        $ssoSigned = false;

        $wheres = [
            'timestamp' => round(microtime(true) * 1000),
            'response'  => 'json',
        ];

        foreach ($query->wheres as $where) {
            $wheres[$where['column']] = $where['value'];
        }

        if (isset($wheres['sso_signed'])) {
            $ssoSigned = (bool) $wheres['sso_signed'];

            unset($wheres['sso_signed']);
        }

        if ($ssoSigned) {
            return $this->signSsoRequest($wheres, $connection);
        }

        return $this->signDefaultRequest($wheres, $connection);
    }

    /**
     * Sign a defualt request using the APIs 'identifier' and 'secret'.
     *
     * @param   array                                    $wheres
     * @param   \Kevindierkx\Elicit\ConnectionInterface  $connection
     * @return  string
     */
    protected function signDefaultRequest(array $wheres, $connection)
    {
        $wheres['apiKey'] = $connection->getConfig('identifier');

        return $this->signRequest($wheres, $connection->getConfig('secret'));
    }

    /**
     * Sign a 'single sign on' request using the APIs 'sso_key'.
     *
     * @param   array                                    $wheres
     * @param   \Kevindierkx\Elicit\ConnectionInterface  $connection
     * @return  string
     */
    protected function signSsoRequest(array $wheres, $connection)
    {
        return $this->signRequest($wheres, $connection->getConfig('sso_key'));
    }

    /**
     * Sign the request with the provided secret.
     *
     * @param  array   $wheres
     * @param  string  $secret
     * @return string
     */
    protected function signRequest(array $wheres, $secret)
    {
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
