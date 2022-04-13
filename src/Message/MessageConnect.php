<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Message;

class MessageConnect extends Message
{
    protected ?string $clientId;
    protected string $channel = '/meta/connect';
    protected string $connectionType = 'long-polling';

    public function __construct(string $clientId)
    {
        parent::__construct();

        $this->clientId = $clientId;
    }
}
