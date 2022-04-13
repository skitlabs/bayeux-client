<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Bayeux;
use Skitlabs\Bayeux\Context;
use Skitlabs\Bayeux\Message\MessageHandshake;

class StateHandshake extends State
{
    public function process(Bayeux $client, Context $context) : State
    {
        $response = $client->send('/meta/handshake', new MessageHandshake());

        $successful = $response['0']['successful'] ?? false;
        $clientId = (string) ($response['0']['clientId'] ?? '');
        if ($successful !== true || ! $clientId) {
            return new StateDisconnected('Failed to retrieve clientId');
        }

        $context->setClientId($clientId);

        return new StateSubscribe();
    }
}
