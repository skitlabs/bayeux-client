<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Context;
use Skitlabs\Bayeux\Message\MessageConnect;
use Skitlabs\Bayeux\Transport\Transport;

class StateConnect extends State
{
    public function process(Transport $transport, Context $context) : State
    {
        $response = $this->send($transport, $context, '/meta/connect', new MessageConnect($context));

        $interval = (int) ($response['0']['advice']['interval'] ?? 0);
        $advisedState = (string) ($response['0']['advice']['reconnect'] ?? 'processing');

        return match ($advisedState) {
            'handshake' => new StateHandshake(),
            'retry' => new StateSleep($interval, new StateConnect()),
            'processing' => new StateProcessing($response),
            default => new StateDisconnecting('Failed to handle advisedState: ' . $advisedState),
        };
    }
}
