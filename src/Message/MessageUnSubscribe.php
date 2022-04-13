<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Message;

class MessageUnSubscribe extends Message
{
    public function __construct(string $subscription)
    {
        parent::__construct([
            'channel' => '/meta/unsubscribe',
            'subscription' => $subscription,
        ]);
    }
}
