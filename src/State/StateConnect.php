<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Context;
use Skitlabs\Bayeux\Message\MessageConnect;
use Skitlabs\Bayeux\Transport\Transport;

class StateConnect extends State
{
    public function process(Transport $transport, Context $context) : State
    {
        $response = $transport->send('/meta/connect', new MessageConnect($context));

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
