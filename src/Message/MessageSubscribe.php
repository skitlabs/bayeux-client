<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Message;

class MessageSubscribe extends Message
{
    public function __construct(string $subscription)
    {
        parent::__construct([
            'channel' => '/meta/subscribe',
            'subscription' => $subscription,
        ]);
    }
}
