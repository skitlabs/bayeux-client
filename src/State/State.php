<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Bayeux;
use Skitlabs\Bayeux\Http\HttpClient;

abstract class State
{
    abstract public function process(Bayeux $client) : State;

    public function isDisconnected() : bool
    {
        return false;
    }
}
