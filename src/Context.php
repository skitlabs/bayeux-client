<?php declare(strict_types=1);

namespace Skitlabs\Bayeux;

use Closure;

class Context
{
    private string $clientId = '';
    /** @var array<string, Closure> */
    private array $subscriptions = [];

    public function subscribe(string $channel, Closure $closure) : void
    {
        $this->subscriptions[$channel] = $closure;
    }

    public function subscriber(string $channel) : Closure
    {
        return $this->subscriptions[$channel] ?? static function () : void {};
    }

    /** @return array<array-key, string> */
    public function channels() : array
    {
        return array_keys($this->subscriptions);
    }

    public function setClientId(string $clientId) : void
    {
        $this->clientId = $clientId;
    }

    public function clientId() : string
    {
        return $this->clientId;
    }
}
