<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Message;

use Skitlabs\Bayeux\Context;

class MessageSubscribe extends Message
{
    public function __construct(string $subscription, Context $context)
    {
        parent::__construct([
            'channel' => '/meta/subscribe',
            'subscription' => $subscription,
        ], $context);
    }
}
