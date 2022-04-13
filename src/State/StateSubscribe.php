<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Bayeux;
use Skitlabs\Bayeux\Message\MessageSubscribe;

class StateSubscribe extends State
{
    private array $channels;

    public function __construct(array $channels)
    {
        $this->channels = $channels;
    }

    public function process(Bayeux $client) : State
    {
        $messages = [];

        foreach ($this->channels as $channel) {
            $messages[] = new MessageSubscribe($channel);
        }

        $client->send('/meta/subscribe', ... $messages);

        return new StateConnect();
    }
}
