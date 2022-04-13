<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Message;

class MessageDisconnect extends Message
{
    public function __construct()
    {
        parent::__construct([
            'channel' => '/meta/disconnect',
        ]);
    }
}
