<?php namespace PCextreme\CloudstackClient\Entities;

use Kevindierkx\Elicit\Elicit\Model;

class Cloudstack extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $connection = 'cloudstack-client';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey;

    /**
     * {@inheritDoc}
     */
    protected $defaults = [
        '*' => [
            'path'   => '/',
            'method' => 'GET',
        ],

        // A few commands only support POST HTTP methods.
        // The deployVirtualMachine command supports both,
        // but it can handle more userdata using the POST method.
        'deployVirtualMachine' => ['method' => 'POST'],
        'login' => ['method' => 'POST'],
    ];

    /**
     * Handle dynamic method calls into the method.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $path = $this->getPath($method);

        $queryParameters = array_shift($parameters);

        // When the path could not be determined form the API list
        // the command most likely does not exist or is not available
        // for the user.
        if (is_null($path)) {
            $className = get_class($this);

            throw new \BadMethodCallException("Call to {$method}() is not available in the API list.");
        }

        // Here we fetch the query instance bound to the model.
        // We use this instance to add the path and query parameters
        // to the request.
        $query = $this->newQuery();

        $query->from($path);

        $query->where('command', $method);

        // We make sure the provided query parameters are of
        // the type array before adding it to the query.
        if (is_array($queryParameters) && ! empty($queryParameters)) {
            $query->where($queryParameters);
        }

        return $query->get();
    }
}
