<?php namespace PCextreme\CloudstackClient\Connections;

use Kevindierkx\Elicit\Connection\Connection;
use PCextreme\CloudstackClient\Query\Grammars\CloudstackGrammar;
use PCextreme\CloudstackClient\Query\Processors\CloudstackProcessor;

class CloudstackConnection extends Connection
{
    /**
     * {@inheritDoc}
     */
    protected function getDefaultQueryGrammar()
    {
        return new CloudstackGrammar;
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultPostProcessor()
    {
        return new CloudstackProcessor;
    }
}
