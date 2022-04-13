<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Message;

class MessageConnect extends Message
{
    public function __construct()
    {
        parent::__construct([
            'channel' => '/meta/connect',
            'connectionType' => 'long-polling',
        ]);
    }
}
