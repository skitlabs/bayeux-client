<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Bayeux;
use Skitlabs\Bayeux\Context;
use Skitlabs\Bayeux\Message\MessageConnect;

class StateConnect extends State
{
    public function process(Bayeux $client, Context $context) : State
    {
        $response = $client->send('/meta/connect', new MessageConnect());

        $interval = (int) ($response['0']['advice']['interval'] ?? 0);
        $advisedState = $response['0']['advice']['reconnect'] ?? 'retry';
        if ($interval > 0) {
            return new StateSleep(
                $interval,
                match ($advisedState) {
                    'handshake' => new StateHandshake(),
                    'retry' => new StateConnect(),
                    default => new StateDisconnecting('Failed to handle advisedState: ' . $advisedState),
                },
            );
        }

        return new StateProcessing($response);
    }
}
