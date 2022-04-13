<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Bayeux;
use Skitlabs\Bayeux\Http\HttpClient;
use Skitlabs\Bayeux\Message\MessageHandshake;

class StateDisconnected extends State
{
    public readonly string $reason;

    public function __construct(string $reason)
    {
        $this->reason = $reason;
    }

    public function process(Bayeux $client) : State
    {
        return new StateHandshake();
    }

    public function isDisconnected() : bool
    {
        return true;
    }
}
