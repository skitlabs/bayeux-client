<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Context;
use Skitlabs\Bayeux\Transport\Transport;

abstract class State
{
    /** Process the current state, and return the next state transition */
    abstract public function process(Transport $transport, Context $context) : State;

    public function isDisconnected() : bool
    {
        return false;
    }
}
