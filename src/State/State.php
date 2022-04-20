<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Context;
use Skitlabs\Bayeux\Message\Message;
use Skitlabs\Bayeux\Transport\Transport;

abstract class State
{
    /** Process the current state, and return the next state transition */
    abstract public function process(Transport $transport, Context $context) : State;

    public function send(Transport $transport, Context $context, string $url, Message ... $messages) : array
    {
        $responses = [];
        foreach ($messages as $message) {
            foreach ($context->extensions() as $extension) {
                $extension->processOutgoing($message);
            }

            $response = $transport->send($url, $message);

            foreach ($context->extensions() as $extension) {
                $response = $extension->processIncoming($response);
            }

            $responses[] = $response;
        }

        if (count($messages) === 1) {
            return array_pop($responses);
        }

        return $responses;
    }

    public function isDisconnected() : bool
    {
        return false;
    }
}
