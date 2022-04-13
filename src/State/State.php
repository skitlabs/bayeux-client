<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Bayeux;

abstract class State
{
    /** Process the current state, and return the next state transition */
    abstract public function process(Bayeux $client) : State;

    public function isDisconnected() : bool
    {
        return false;
    }
}
