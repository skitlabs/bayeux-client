<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Context;
use Skitlabs\Bayeux\Transport\Transport;

class StateDisconnected extends State
{
    public readonly string $reason;

    public function __construct(string $reason)
    {
        $this->reason = $reason;
    }

    public function process(Transport $transport, Context $context) : State
    {
        return new StateHandshake();
    }

    public function isDisconnected() : bool
    {
        return true;
    }
}
