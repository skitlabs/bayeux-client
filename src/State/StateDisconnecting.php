<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Context;
use Skitlabs\Bayeux\Message\MessageDisconnect;
use Skitlabs\Bayeux\Message\MessageUnSubscribe;
use Skitlabs\Bayeux\Transport\Transport;

class StateDisconnecting extends State
{
    private string $reason;

    public function __construct(string $reason)
    {
        $this->reason = $reason;
    }

    public function process(Transport $transport, Context $context) : State
    {
        $messages = [];

        foreach ($context->channels() as $channel) {
            $messages[] = new MessageUnsubscribe($channel, $context);
        }

        $transport->send('/meta/unsubscribe', ... $messages);

        $transport->send('/meta/disconnect', new MessageDisconnect($context));

        return new StateDisconnected($this->reason);
    }

    public function isDisconnected() : bool
    {
        return true;
    }
}
