<?php namespace PCextreme\CloudstackClient\Providers;

use Illuminate\Support\ServiceProvider;
use PCextreme\CloudstackClient\Cloudstack;
use PCextreme\CloudstackClient\Connections\CloudstackConnection;
use PCextreme\CloudstackClient\Connectors\CloudstackConnector;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        $this->package('pcextreme/cloudstack-client', 'cloudstack-client', __DIR__ . '/..');

        $this->setupConnectionBindings();
        $this->setupConnection();
    }

    /**
     * Setup connection bindings.
     */
    protected function setupConnectionBindings()
    {
        $this->app->bind(
            'elicit.connection.cloudstack',
            'PCextreme\CloudstackClient\Connections\CloudstackConnection'
        );

        $this->app->bind(
            'elicit.connector.cloudstack-api',
            'PCextreme\CloudstackClient\Connectors\CloudstackConnector'
        );
    }

    /**
     * Setup the Cloudstack Client connection.
     */
    protected function setupConnection()
    {
        $config = array_merge(
            $this->app['config']['cloudstack-client::connection'],
            [
                'driver' => 'cloudstack',
                'auth'   => 'cloudstack-api',
            ]
        );

        $this->app['config']['elicit::connections.cloudstack-client'] = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->registerElicit();
        $this->registerClient();
    }

    /**
     * Make sure Elicit is registered.
     */
    protected function registerElicit()
    {
        if (! $this->app->offsetExists('elicit')) {
            $this->app->register('Kevindierkx\Elicit\Provider\ElicitServiceProvider');
        }
    }

    /**
     * Register the Cloudstack Client.
     */
    protected function registerClient()
    {
        $this->app->bindShared('cloudstack.client', function ($app) {
            return new Cloudstack(
                $app['files']
            );
        });
    }

    /**
     * {@inheritDoc}
     */
    public function provides()
    {
        return [
            'cloudstack.client',
        ];
    }
}
