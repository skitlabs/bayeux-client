<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Bayeux;
use Skitlabs\Bayeux\Message\MessageDisconnect;
use Skitlabs\Bayeux\Message\MessageUnSubscribe;

class StateDisconnecting extends State
{
    private string $reason;

    public function __construct(string $reason)
    {
        $this->reason = $reason;
    }

    public function process(Bayeux $client) : State
    {
        $messages = [];

        foreach ($client->subscriptions() as $channel) {
            $messages[] = new MessageUnsubscribe($channel);
        }

        $client->send('/meta/unsubscribe', ... $messages);

        $client->send('/meta/disconnect', new MessageDisconnect());

        return new StateDisconnected($this->reason);
    }

    public function isDisconnected() : bool
    {
        return true;
    }
}
