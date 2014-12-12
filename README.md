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

