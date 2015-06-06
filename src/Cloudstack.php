<?php namespace PCextreme\CloudstackClient;

class Cloudstack
{
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var array
     */
    protected $paths;

    /**
     * Cloudstack model used during requests.
     *
     * @var string
     */
    protected $model = 'PCextreme\CloudstackClient\Entities\Cloudstack';

    /**
     * Bind instances to class.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     */
    public function __construct($files)
    {
        $this->files = $files;
    }

    /**
     * Get paths from the model.
     *
     * @return array
     */
    public function getPaths()
    {
        // When no paths are specified we will try to load
        // them from the cache. The cached paths are composed
        // using the ApiList command from Cloudstack.
        if (empty($this->paths)) {
            $listPath = __DIR__ . '/cache/api_list.php';

            if (! $this->files->exists($listPath)) {
                // Without the API list we can't determine the available
                // Cloudstack API commands. This file needs to be generated
                // before we can use the client.
                throw new \RuntimeException("Cloudstack Client API list not found. This file needs to be generated before using the client.");
            }

            $paths = $this->files->getRequire($listPath);

            $this->paths = $paths;
        }

        return $this->paths;
    }

    /**
     * Set paths for the model.
     *
     * @return array
     */
    public function setPaths(array $paths)
    {
        $this->paths = $paths;
    }

    /**
     * Create a new instance of the model.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function createModel(array $attributes = [])
    {
        $model = '\\'.ltrim($this->model, '\\');

        return new $model($attributes);
    }

    /**
     * Returns the model.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Runtime override of the model.
     *
     * @param  string  $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Dynamically pass missing methods to the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $model = $this->createModel();

        // We inject the paths for new instances of the
        // Cloudstack model here. Utilising the getPaths method
        // we validate the cache.
        $model->setPaths($this->getPaths());

        return call_user_func_array([$model, $method], $parameters);
    }
}
