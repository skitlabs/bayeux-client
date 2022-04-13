<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Message;

use Skitlabs\Bayeux\Context;

class MessageDisconnect extends Message
{
    public function __construct(Context $context)
    {
        parent::__construct([
            'channel' => '/meta/disconnect',
        ], $context);
    }
}
