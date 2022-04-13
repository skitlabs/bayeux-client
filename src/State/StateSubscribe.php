<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Context;
use Skitlabs\Bayeux\Message\MessageSubscribe;
use Skitlabs\Bayeux\Transport\Transport;

class StateSubscribe extends State
{
    public function process(Transport $transport, Context $context) : State
    {
        $messages = [];

        foreach ($context->channels() as $channel) {
            $messages[] = new MessageSubscribe($channel, $context);
        }

        $transport->send('/meta/subscribe', ... $messages);

        return new StateConnect();
    }
}
