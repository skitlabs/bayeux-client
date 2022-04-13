<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Bayeux;
use Skitlabs\Bayeux\Context;

abstract class State
{
    /** Process the current state, and return the next state transition */
    abstract public function process(Bayeux $client, Context $context) : State;

    public function isDisconnected() : bool
    {
        return false;
    }
}
