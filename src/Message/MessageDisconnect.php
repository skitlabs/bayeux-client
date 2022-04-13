<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Message;

class MessageDisconnect extends Message
{
    protected ?string $clientId;
    protected string $channel = '/meta/disconnect';

    public function __construct(string $clientId)
    {
        parent::__construct();

        $this->clientId = $clientId;
    }
}
