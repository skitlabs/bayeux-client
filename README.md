# Bayeux Client
This package provides a limited [Bayeux](https://docs.cometd.org/current7/reference/#_bayeux) client. Subscribe to any number of channels, wait for messages, dispatch a callback, rinse and repeat.

## Installation
This package is installed through composer;

```shell
$  composer install skitlabs/bayeux-client
```

### Requirements
* PHP 8.1

## Usage

1. Create a client by setting up an HTTP-client, and an Authentication provider.
2. Subscribe to any number of channels
3. Call the **blocking** start method
4. Wait for messages to come in, and your callback to be triggered

### Blocking
Note that, once start has been called, the script will continuously loop; until disconnected by the remote server.
This means that the entire process is waiting for messages, and further execution is halted.   

> It is **strongly** recommended running `start` in a separate thread.

#### Resuming after disconnects
Once a disconnect occurs, script execution will continue beyond `start`. This client will never attempt to reconnect.   
If you wish to automatically restart after a disconnect, consider setting up a watchman/supervisord process.

```php
<?php declare(strict_types=1);

require_once './vendor/autoload.php';

$logger = new \Monolog\Logger("stdout");
$logger->pushHandler(new \Monolog\Handler\ErrorLogHandler());

$bayeux = new \Skitlabs\Bayeux\Bayeux(
    new \Skitlabs\Bayeux\Http\HttpClientLaravel(
        'https://organization.my.salesforce.com/cometd/54.0/',
        new \Skitlabs\Bayeux\Authentication\AuthenticationOAuthTokenLaravelHttp(
            'https://organization.my.salesforce.com',
            'clientId',
            'clientSecret',
            'username',
            'password',
        ),
    ),
    $logger,
);

$bayeux->subscribe('/data/ChangeEvents', static function (array $message) : void {
    var_dump($message);
})->start();
```
