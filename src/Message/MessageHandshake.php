<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Message;

use Skitlabs\Bayeux\Context;

class MessageHandshake extends Message
{
    public function __construct(Context $context)
    {
        parent::__construct([
            'channel' => '/meta/handshake',
            'supportedConnectionTypes' => ['long-polling'],
        ], $context);
    }
}
