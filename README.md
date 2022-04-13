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
1. Create a client by setting up a (HTTP) transport, and an Authentication provider.
2. Subscribe to any number of channels
3. Call start, and wait for the messages to roll in

```php
<?php declare(strict_types=1);

require_once './vendor/autoload.php';

$bayeux = new \Skitlabs\Bayeux\Client\Bayeux(
    new \Skitlabs\Bayeux\Transport\TransportGuzzle(
        'https://organization.my.salesforce.com/cometd/54.0/',
        new \Skitlabs\Bayeux\Authentication\AuthenticationOAuthTokenGuzzle(
            'https://organization.my.salesforce.com',
            'clientId',
            'clientSecret',
            'username',
            'password',
        ),
    ),
);

$bayeux->subscribe('/data/ChangeEvents', static function (array $message) : void {
    var_dump($message);
})->start();
```

### Logging
If you want to log all out- and incoming messages, wrap the `Transport` in the included `TransportLogging`-decorator.

```php
<?php declare(strict_types=1);

require_once './vendor/autoload.php';

/** @var \Skitlabs\Bayeux\Transport\Transport $transport */
/** @var \Psr\Log\LoggerInterface $logging */

\Skitlabs\Bayeux\Transport\Decorator\TransportLogging::decorate($transport, $logging)
```

### Laravel
Using Laravel, and don't want to depend on Guzzle directly?   
Simply replace `\Skitlabs\Bayeux\Transport\TransportGuzzle` with `\Skitlabs\Bayeux\Transport\TransportLaravelHttp` and `\Skitlabs\Bayeux\Authentication\AuthenticationOAuthTokenGuzzle` with `\Skitlabs\Bayeux\Authentication\AuthenticationOAuthTokenLaravelHttp`.

### Blocking
Note that, once start has been called, the script will continuously loop; until disconnected by the remote server.
This means that the entire process is waiting for messages, and further execution is halted.   

> It is **strongly** recommended running `start` in a separate thread.

#### Resuming after disconnects
Once a disconnect occurs, script execution will continue beyond `start`. This client will never attempt to reconnect.   
If you wish to automatically restart after a disconnect, consider setting up a watchman/supervisord process.

