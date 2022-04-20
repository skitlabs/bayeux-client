# Bayeux Client
This package provides a limited [Bayeux](https://docs.cometd.org/current7/reference/#_bayeux) client. Subscribe to any number of channels, wait for messages, dispatch a callback, rinse and repeat.

## Installation
This package is installed through composer;

```shell
$  composer install skitlabs/bayeux-client
```

### Requirements
* PHP 8.1
* guzzlehttp/guzzle
* psr/log
* ramsey/uuid

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
If you want to log all outgoing and incoming messages, wrap the `Transport` in the included `TransportLogging`-decorator.

```php
<?php declare(strict_types=1);

require_once './vendor/autoload.php';

/** @var \Skitlabs\Bayeux\Transport\Transport $transport */
/** @var \Psr\Log\LoggerInterface $logging */

\Skitlabs\Bayeux\Transport\Decorator\TransportLogging::decorate($transport, $logging)
```

### Blocking
Note that, once start has been called, the script will continuously loop; until disconnected by the remote server.
This means that the entire process is waiting for messages, and further execution is halted.   

> It is **strongly** recommended running `start` in a separate thread.

#### Resuming after disconnects
Once a disconnect occurs, script execution will continue beyond `start`. This client will never attempt to reconnect.   
If you wish to automatically restart after a disconnect, consider setting up a watchman/supervisord process.

### Extensions
After creating a client, any number of extensions may be added. This package includes;

#### ReplayId
The `replayId`-property may be used to track the last processed message for a channel.   
This value can later be used to retrieve messages that occurred while te client wasn't listening.

```php
<?php declare(strict_types=1);

/** 
 * @var \Skitlabs\Bayeux\Client\Bayeux $client
 * @var \Skitlabs\Bayeux\Extension\Replay\Persistence\Persistence $persistence 
 */
$client->extend(new \Skitlabs\Bayeux\Extension\Replay\ExtensionReplayId($persistence));
```

##### Persistence
By default, the `replayId` for each channel will only be tracked in (volatile) memory. If the script terminates, so does the cache.   
Use one of the following modules to persist, or create your own;

###### File based
Using this module, the `replayId` for each channel will be tracked in a `json`-document on the local filesystem.

```php
<?php declare(strict_types=1);

// Keep track of replayIds in the file `replayId.json` in the current directory.
new \Skitlabs\Bayeux\Extension\Replay\Persistence\FilePersistence('replayId.json');
```

###### Redis
Using this module, the `replayId` for each channel will be tracked in Redis.

```php
<?php declare(strict_types=1);

$client = new \Redis();
$client->connect('127.0.0.1', 6379);

new \Skitlabs\Bayeux\Extension\Replay\Persistence\RedisPersistence($client);
```

###### Make your own
Add your own module, by implementing the `Skitlabs\Bayeux\Extension\Replay\Persistence\Persistence` interface.

#### SalesForce Error Logger 
Use this extension to track SalesForce errors, communicated through the `sfdc`-key.

```php
<?php declare(strict_types=1);

/** 
 * @var \Skitlabs\Bayeux\Client\Bayeux $client
 * @var \Psr\Log\LoggerInterface $logger 
 */
$client->extend(new \Skitlabs\Bayeux\Extension\SalesForce\SalesForceErrorLoggingExtension($logger));
```
