<?php namespace PCextreme\CloudstackClient\Providers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \PCextreme\CloudstackClient\Cloudstack
 */
class Cloudstack extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'cloudstack.client';
    }
}
