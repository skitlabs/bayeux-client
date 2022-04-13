<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Message;

use Skitlabs\Bayeux\Context;

class MessageConnect extends Message
{
    public function __construct($context)
    {
        parent::__construct([
            'channel' => '/meta/connect',
            'connectionType' => 'long-polling',
        ], $context);
    }
}
