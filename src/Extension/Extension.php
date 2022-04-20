<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Extension;

use Skitlabs\Bayeux\Message\Message;

interface Extension
{
    /** Determines the unique identifier/key on the message */
    public function getName() : string;

    /** Mutate a message before it is transmitted */
    public function processOutgoing(Message $message) : void;

    /** Mutate an incoming message */
    public function processIncoming(array $message) : array;
}
