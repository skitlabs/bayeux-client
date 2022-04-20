<?php declare(strict_types=1);

namespace Skitlabs\Bayeux;

use Closure;
use Skitlabs\Bayeux\Extension\Extension;

class Context
{
    private string $clientId = '';
    /** @var array<string, Closure> */
    private array $subscriptions = [];
    /** @var array<array-key, Extension> */
    private array $extensions = [];

    public function subscribe(string $channel, Closure $closure) : void
    {
        $this->subscriptions[$channel] = $closure;
    }

    public function subscriber(string $channel) : Closure
    {
        return $this->subscriptions[$channel] ?? static function () : void {};
    }

    public function extend(Extension $extension) : void
    {
        $this->extensions[] = $extension;
    }

    public function extensions() : array
    {
        return $this->extensions;
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
