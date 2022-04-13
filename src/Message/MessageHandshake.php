<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Message;

class MessageHandshake extends Message
{
    protected string $channel = '/meta/handshake';
    protected array $supportedConnectionTypes = ['long-polling'];
}
