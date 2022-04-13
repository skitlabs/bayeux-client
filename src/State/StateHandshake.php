<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Context;
use Skitlabs\Bayeux\Message\MessageHandshake;
use Skitlabs\Bayeux\Transport\Transport;

class StateHandshake extends State
{
    private int $attempts = 0;
    private int $maxAttempts = 5;

    public function process(Transport $transport, Context $context) : State
    {
        $response = $transport->send('/meta/handshake', new MessageHandshake($context));

        $successful = $response['0']['successful'] ?? false;
        $clientId = (string) ($response['0']['clientId'] ?? '');
        if ($successful !== true || ! $clientId) {
            if ($this->attempts >= $this->maxAttempts) {
                return new StateDisconnected('Failed to retrieve clientId');
            }

            $transport->forceAuthenticate();
            $this->attempts++;

            return $this;
        }

        $context->setClientId($clientId);

        return new StateSubscribe();
    }
}
