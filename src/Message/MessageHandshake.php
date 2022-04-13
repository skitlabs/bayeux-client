<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Message;

class MessageHandshake extends Message
{
    public function __construct()
    {
        parent::__construct([
            'channel' => '/meta/handshake',
            'supportedConnectionTypes' => ['long-polling'],
        ]);
    }
}
