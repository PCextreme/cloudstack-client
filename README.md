**This package is deprecated and will be replaced in the near future**

# Cloudstack Client

The ```cloudstack-client``` is a PHP based Cloudstack API ORM.

The Cloudstack Client utilises the [kevindierkx/elicit](https://github.com/kevindierkx/elicit) API ORM and supports all major functionality.

## Installation

**Currently the Cloudstack Client only supports [Laravel](http://laravel.com) based applications, support for native PHP will be added in the future.**

To install this package you will need:

- Laravel >= 4.2
- PHP >= 5.4

You must then modify your composer.json file and run composer update to include the latest version of the package in your project:

```JSON
"require": {
	"pcextreme/cloudstack-client": "~0.1"
}
```

Or you can run the composer require command from your terminal:

```PHP
composer require pcextreme/cloudstack-client:~0.1
```

Once the package is installed you need to open ```app/config/app.php``` and register the required service provider:

```PHP
'providers' => [
    'PCextreme\CloudstackClient\Providers\LaravelServiceProvider'
]
```

Optionaly you can add the following line to your aliases to utilise the provided facade:

```PHP
'aliases' => [
    'Cloudstack' => 'PCextreme\CloudstackClient\Providers\Facades\Cloudstack',
]
```

## Configuration

Run the following command to publish the package configuration:

```PHP
php artisan config:publish pcextreme/cloudstack-client
```

This will add the package configuration to your packages folder ```app/config/packages/pcextreme/cloudstack-client/config.php```, here you will be providing the required connection details.

*SSO keys are generally only for Cloudstack administrators and are not required.*

## Usage

When using the Cloudstack facade you can call all commands available to your Cloudstack user directly.

For example calling ```listVirtualMachines``` with the ```listall``` parameter would look like the following:

```PHP
Cloudstack::listVirtualMachines(['listall' => 'true']);
```

The above would return ```null``` when the return count is 0. This happens when you don't have any instances, the same would happen for other ```list``` calls.
When Cloudstack returns multiple results the Cloudstack Client would return an instance of ```KevinDierkx\Elicit\Elicit\Collection``` you can use the methods provided within this instance to manipulate the resulted data.

**Please note that the ```api-list.php``` is required for every direct request. The Cloudstack Client provides this file by default and is located in the ```cache/``` folder withing this package.**

## Credits

- [Kevin Dierkx](https://github.com/kevindierkx)

## License

The MIT License (MIT). Please see [License File](https://github.com/PCextreme/cloudstack-client/blob/master/LICENSE) for more information.
