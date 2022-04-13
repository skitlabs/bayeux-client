<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Bayeux;
use Skitlabs\Bayeux\Message\MessageDisconnect;
use Skitlabs\Bayeux\Message\MessageUnSubscribe;

class StateDisconnecting extends State
{
    public readonly string $clientId;
    private string $reason;

    public function __construct(string $clientId, string $reason)
    {
        $this->clientId = $clientId;
        $this->reason = $reason;
    }

    public function process(Bayeux $client) : State
    {
        $messages = [];

        foreach ($client->subscriptions() as $channel) {
            $messages[] = new MessageUnsubscribe($this->clientId, $channel);
        }

        $client->send('/meta/unsubscribe', ... $messages);

        $client->send('/meta/disconnect', new MessageDisconnect($this->clientId));

        return new StateDisconnected($this->reason);
    }

    public function isDisconnected() : bool
    {
        return true;
    }
}
