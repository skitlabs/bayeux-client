<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Bayeux;
use Skitlabs\Bayeux\Http\HttpClient;
use Skitlabs\Bayeux\Message\MessageHandshake;
use Skitlabs\Bayeux\Message\MessageSubscribe;

class StateSubscribe extends State
{
    private readonly string $clientId;
    private array $channels;

    public function __construct(string $clientId, array $channels)
    {
        $this->clientId = $clientId;
        $this->channels = $channels;
    }

    public function process(Bayeux $client) : State
    {
        $messages = [];

        foreach ($this->channels as $channel) {
            $messages[] = new MessageSubscribe($this->clientId, $channel);
        }

        $client->send('/meta/subscribe', ... $messages);

        return new StateConnect($this->clientId);
    }
}
