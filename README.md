# Laravel Base Exceptions

A set of base [PHP exceptions](http://php.net/manual/en/language.exceptions.php) for Laravel applications.

## Installing

Require this package in your `composer.json` file:

```json
{
	"require": {
		"antarctica/laravel-base-exceptions": "0.*"
	}
}
```

Run `composer update`.

## Usage

### `HttpException`

Extends the Symfony [HttpException](http://api.symfony.com/2.3/Symfony/Component/HttpKernel/Exception/HttpException.html).

This exception is designed for use with APIs or other situations where an error occurs during the processing of HTTP requests.

It extends the Symfony [HttpException](http://api.symfony.com/2.3/Symfony/Component/HttpKernel/Exception/HttpException.html) to ensure easy integration/compatibility with the [JSON Exception Formatter](https://github.com/Radweb/JSON-Exception-Formatter) package for Laravel.

This exception also adds a number of custom properties used to make using exceptions as errors easier.

#### Properties

Note: the standard `message` property is also supported by this exception but not documented here.

##### `statusCode` (int)

Sets the HTTP status code to be used in the response, as [defined in the parent exception](http://api.symfony.com/2.3/Symfony/Component/HttpKernel/Exception/HttpException.html#method_getStatusCode).

##### `headers` (int)

Array of headers to be returned in the response, as [defined in the parent exception](http://api.symfony.com/2.3/Symfony/Component/HttpKernel/Exception/HttpException.html#method_getHeaders)

##### `kind` (string)

Human/machine readable error 'type', usually similar to the exception class as this isn't included in errors in a production environment.

##### `details` (array)

Free form, but structured 'catch all' for error content. This may be designed for machine or human reading as needed.

For example if you need to include some data that another service should consume or for describing specific validation errors (which wouldn't be suitable to cover in the *kind* or *message* properties).

##### `resolution` (string)

Human readable, terse description of how to fix the error, if detailed information is needed to achieve this host this externally and include its location in the *resolutionURLs* property.

##### `resolutionURLs` (array)

Human/machine readable list of URLs/URIs relevant to how to fix the error. These may be consumed automatically by a service (i.e. point to another API resource or service) or links to relevant documentation etc. describing how to fix the error in more detail.

#### Basic usage (Laravel)

```php
<?php

use Antarctica\LaravelBaseExceptions\Exception\HttpException;

class SomeException extends HttpException {

	protected $statusCode = 501; // Not implemented - used for demonstration purposes only.

	protected $kind = 'some_fault';

	protected $details = [
		"something" => [
			"Something went wrong."
		]
	];

	protected $resolution = 'Please don\'t do that again.';

	protected $resolutionURLs = ['http://www.example.com'];
}
```

### `InvalidArgumentTypeException`

Extends this packages's `HttpException` exception.

This exception is designed for use with methods where a value used as as a method argument or parameter is determined not to be of the correct data type.

E.g. An argument must be of a data type *string* but a data type of *array* is given.

Note: This exception does not require you to determine data types, rather you provide an example of valid value and the given value and there respective types will be determined automatically.

Note: If you need an exception for the actual value being invalid use the `InvalidArgumentValueException` exception instead.

Note: If you need an exception for ensuring a value is of a particular _class_ (or its parents) do not use this class alone. This class will only confirm the value is an object, it does check its class as well. See [BASWEB-157](https://jira.ceh.ac.uk/browse/BASWEB-157) for details of which exceptions you can use for this purpose.

This exception also adds a number of custom properties used to make using exceptions for this type of error easier.

#### Properties

Note: the properties used by the `HttpException` are  also supported by this exception but not documented here.

##### `argumentName` (string)

**Note: This property is required as part of the exception constructor**

Used in output messages, the name of whatever argument or parameter in question.

E.g. For a method that requires an argument _position_ to be an integer, the argument name could be `Position`.

Note: This value is used for constructing display messages within the exception only, therefore its value does not need to match the name of the argument. However as users may use the this value (if used as an API method argument for example) then it is strongly advised to ensure this value _does_ match the argument name.

##### `validArgumentValue` (mixed)

**Note: This property is required as part of the exception constructor**

A known good value, which is of the correct data type for the argument.

E.g. For a method that requires an argument _position_ to be an integer, this value could be any integer value such as `3`.

For more complex types such as a method that requires an argument _dataProvider_ to be an object, this value could be any variable that is an object.

##### `givenArgumentType` (mixed)

**Note: This property is required as part of the exception constructor**

The value that was used for the argument. Usually you can simply pass this value through to the exception from the method constructor.

E.g. If the argument is _position_ you can likely use `$position` for this property.

#### Basic usage (Laravel)

```php
<?php

use Antarctica\LaravelBaseExceptions\Exception\InvalidArgumentTypeException;

/**
 * Determines if the value for an argument is an integer
 *
 * @param string $argument name of the argument
 * @param mixed $var value given for the argument
 * @return int
 * @throws InvalidArgumentTypeException
 * @throws InvalidArgumentValueException
 */
private function validateInt($argument, $var)
{
    if (is_numeric($var) === false)
    {
        throw new InvalidArgumentTypeException(
            $argumentName = $argument,
            $valueOfCorrectArgumentType = 0,
            $argumentValue = $var
        );
    }
    
    // ...
    
    return $var;
}
```

### `InvalidArgumentValueException`

Extends this packages's `HttpException` exception.

This exception is designed for use with methods where a value used as as a method argument or parameter is determined not to be of a correct value.

E.g. An argument must be a in a list of three colours `Red, Green, Blue` and a value of `Yellow` is given.

Note: If you need an exception for the data type of the value being invalid use the `InvalidArgumentTypeException` exception instead.

Note: If you need an exception for ensuring a value is of a particular _class_ (or its parents) do not use this class alone. This class will only confirm the value is an object, it does check its class as well. See [BASWEB-157](https://jira.ceh.ac.uk/browse/BASWEB-157) for details of which exceptions you can use for this purpose.

This exception also adds a number of custom properties used to make using exceptions for this type of error easier.

#### Properties

Note: the properties used by the `HttpException` are  also supported by this exception but not documented here.

##### `argumentName` (string)

**Note: This property is required as part of the exception constructor**

Used in output messages, the name of whatever argument or parameter in question.

E.g. For a method that requires an argument _position_ to be an integer, the argument name could be `Position`.

Note: This value is used for constructing display messages within the exception only, therefore its value does not need to match the name of the argument. However as users may use the this value (if used as an API method argument for example) then it is strongly recommended to ensure this value _does_ match the argument name.

##### `details` (array)

**Note: This property is required as part of the exception constructor**

Human or machine readable reasons why a value is invalid. Structured as an array to cater to different audiences and purposes.

You may wish to provide well structured information for clients to interpret errors and automatically resolve them or show them to users where the service generating this exception is used as a backing service.

You may also wish to display a descriptive message for use in debugging or other human interactions with the service generating this exception.

Note: It is up to you how complex you make these methods:
 
* For simple validation type situations (value is not a list for example) simply stating the value given was not in the list would likely be enough detail.
* For more complex situations (e.g. a date cannot be used where at least one of seven managers is away and not on a tuesday or when the wind is blowing due East)you may wish to structure errors.
* You may want to provide dynamic information (e.g. you cannot use a valid over 10 degrees Kelvin higher than the current temperature).

##### `resolution` (string)

**Note: This property is required as part of the exception constructor**

This property is inherited from the `HttpException` but is mandatory for this exception.

A human readable description of how to provide a permitted value. These typically work in tandem with the _details_ parameter.

For example where a method requires an argument to be in a list the _details_ would state that the value given (and display this) is not in the list of valid terms (and list these). The resolution in this case could simply state to retry the previous action using a term from the list of valid terms.

Links to documentation, or other sources/URLs may also be provided through the inherited properties of the `HttpException`, specifically the `resolutionURLs` property.

#### Basic usage (Laravel)

```php
<?php

use Antarctica\LaravelBaseExceptions\Exception\InvalidArgumentValueException;

/**
 * Determines if the value for an argument is an integer
 *
 * @param string $argument name of the argument
 * @param mixed $var value given for the argument
 * @return int
 * @throws InvalidArgumentTypeException
 * @throws InvalidArgumentValueException
 */
private function validateInt($argument, $var)
{
    // ...

    if ($var <= 0)
    {
        throw new InvalidArgumentValueException(
            $argumentName = $argument,
            $reasons = ['Value must not be equal to or less than 0, ' . $var . ' given.'],
            $resolution = 'Ensure you are providing a value greater than, and not including 0.'
       );
    }

    return $var;
}
```

## Contributing

This project welcomes contributions, see `CONTRIBUTING` for our general policy.

## Developing

To aid development and keep your local computer clean, a VM (managed by Vagrant) is used to create an isolated environment with all necessary tools/libraries available.

### Requirements

* Mac OS X
* Ansible `brew install ansible`
* [VMware Fusion](http://vmware.com/fusion)
* [Vagrant](http://vagrantup.com) `brew cask install vmware-fusion vagrant`
* [Host manager](https://github.com/smdahlen/vagrant-hostmanager) and [Vagrant VMware](http://www.vagrantup.com/vmware) plugins `vagrant plugin install vagrant-hostmanager && vagrant plugin install vagrant-vmware-fusion`
* You have a private key `id_rsa` and public key `id_rsa.pub` in `~/.ssh/`
* You have an entry like [1] in your `~/.ssh/config`

[1] SSH config entry

```shell
Host bslweb-*
    ForwardAgent yes
    User app
    IdentityFile ~/.ssh/id_rsa
    Port 22
```

### Provisioning development VM

VMs are managed using Vagrant and configured by Ansible.

```shell
$ git clone ssh://git@stash.ceh.ac.uk:7999/basweb/laravel-base-exceptions.git
$ cp ~/.ssh/id_rsa.pub laravel-base-exceptions/provisioning/public_keys/
$ cd laravel-base-exceptions
$ ./armadillo_standin.sh

$ vagrant up

$ ssh bslweb-laravel-base-exceptions-dev-node1
$ cd /app

$ composer install

$ logout
```

### Committing changes

The [Git flow](https://github.com/fzaninotto/Faker#formatters) workflow is used to manage development of this package.

Discrete changes should be made within *feature* branches, created from and merged back into *develop* (where small one-line changes may be made directly).

When ready to release a set of features/changes create a *release* branch from *develop*, update documentation as required and merge into *master* with a tagged, [semantic version](http://semver.org/) (e.g. `v1.2.3`).

After releases the *master* branch should be merged with *develop* to restart the process. High impact bugs can be addressed in *hotfix* branches, created from and merged into *master* directly (and then into *develop*).

### Issue tracking

Issues, bugs, improvements, questions, suggestions and other tasks related to this package are managed through the BAS Web & Applications Team Jira project ([BASWEB](https://jira.ceh.ac.uk/browse/BASWEB)).

### Clean up

To remove the development VM:

```shell
vagrant halt
vagrant destroy
```

The `laravel-base-exceptions` directory can then be safely deleted as normal.

## License

Copyright 2014 NERC BAS. Licensed under the MIT license, see `LICENSE` for details.

