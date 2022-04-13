<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Bayeux;
use Skitlabs\Bayeux\Message\MessageConnect;

class StateConnect extends State
{
    private readonly string $clientId;

    public function __construct(string $clientId)
    {
        $this->clientId = $clientId;
    }

    public function process(Bayeux $client) : State
    {
        $response = $client->send('/meta/connect', new MessageConnect($this->clientId));

        $interval = (int) ($response['0']['advice']['interval'] ?? 0);
        $advisedState = $response['0']['advice']['reconnect'] ?? 'retry';
        if ($interval > 0) {
            return new StateSleep(
                $interval,
                match ($advisedState) {
                    'handshake' => new StateHandshake(),
                    'retry' => new StateConnect($this->clientId),
                    default => new StateDisconnecting($this->clientId, 'Failed to handle advisedState: ' . $advisedState),
                },
            );
        }

        return new StateConnect($this->clientId);
    }
}
