<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Bayeux;
use Skitlabs\Bayeux\Context;
use Skitlabs\Bayeux\Message\MessageSubscribe;

class StateSubscribe extends State
{
    public function process(Bayeux $client, Context $context) : State
    {
        $messages = [];

        foreach ($context->channels() as $channel) {
            $messages[] = new MessageSubscribe($channel);
        }

        $client->send('/meta/subscribe', ... $messages);

        return new StateConnect();
    }
}
