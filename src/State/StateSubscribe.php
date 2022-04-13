<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Bayeux;
use Skitlabs\Bayeux\Message\MessageSubscribe;

class StateSubscribe extends State
{
    public function process(Bayeux $client) : State
    {
        $messages = [];

        foreach ($client->channels() as $channel) {
            $messages[] = new MessageSubscribe($channel);
        }

        $client->send('/meta/subscribe', ... $messages);

        return new StateConnect();
    }
}
