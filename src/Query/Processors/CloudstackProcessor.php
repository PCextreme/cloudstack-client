<?php namespace PCextreme\CloudstackClient\Query\Processors;

use Illuminate\Support\Pluralizer;
use Kevindierkx\Elicit\Query\Builder;
use Kevindierkx\Elicit\Query\Processors\Processor;

class CloudstackProcessor extends Processor
{
    /**
     * @var array
     */
    protected $trimable = [
        'add',
        'authorize',
        'create',
        'delete',
        'get',
        'list',
        'register',
        'remove',
        'revoke',
        'update',
    ];

    /**
     * @var array
     */
    protected $pluralResponseNames = [
        'userkeys',
    ];

    /**
     * @var array
     */
    protected $resourceResponseNames = [
        'changeServiceForVirtualMachine' => 'virtualmachine',
        'getVirtualMachineUserData' => 'virtualmachineuserdata',

        'login' => null,

        'startVirtualMachine' => null,
        'stopVirtualMachine' => null,
        'rebootVirtualMachine' => null,
        'restoreVirtualMachine' => null,
        'destroyVirtualMachine' => null,

        'queryAsyncJobResult' => null,
    ];

    /**
     * Process the results of an API request.
     *
     * @param  \Kevindierkx\Elicit\Query\Builder  $query
     * @param  array  $results
     * @return array
     */
    protected function processRequest(Builder $query, $results)
    {
        $method = reset($query->wheres)['value'];

        $responseName = strtolower($method . 'response');
        $response = $results[$responseName];

        $resourceName = $this->parseResourceName($method);
        $resources = ! is_null($resourceName) && isset($response[$resourceName]) ? $response[$resourceName] : $response;

        // When we don't have any resources we assume we have a 404 like reponse.
        if (! empty($resources)) {
            // We wrap the resources in an additional array when the
            // response doesn't contain 2 items. This way the model parses
            // the attributes as one model.
            if (count($response) < 2 || is_null($resourceName)) {
                return [$resources];
            }

            // When we have 2 items in the reponse we assume we have
            // a collection. Returning the multidimensional array Cloudstack
            // gave us results in a Elicit collection.
            return $resources;
        }

        return [];
    }

    /**
     * Parse the resource name from the called method.
     *
     * @param  string  $method
     * @return string
     */
    protected function parseResourceName($method)
    {
        // Here we check if the method is returned with
        // a different resource name.
        if (array_key_exists($method, $this->resourceResponseNames)) {
            return $this->resourceResponseNames[$method];
        }

        $plural = strtolower(
            str_replace($this->trimable, null, $method)
        );

        // Here we check if the method is returned with
        // a plural resource name.
        if (in_array($plural, $this->pluralResponseNames)) {
            return $plural;
        }

        // Lastly we create a singular resource name.
        return Pluralizer::singular($plural);
    }
}
