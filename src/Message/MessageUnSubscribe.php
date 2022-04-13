<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Message;

class MessageUnSubscribe extends Message
{
    protected ?string $clientId;
    protected string $channel = '/meta/unsubscribe';
    protected string $subscription;

    public function __construct(string $clientId, string $subscription)
    {
        parent::__construct();

        $this->clientId = $clientId;
        $this->subscription = $subscription;
    }
}
