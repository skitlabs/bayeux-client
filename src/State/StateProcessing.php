<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Bayeux;
use Skitlabs\Bayeux\Context;

class StateProcessing extends State
{
    private array $messages;

    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }

    public function process(Bayeux $client, Context $context) : State
    {
        foreach ($this->messages as $message) {
            try {
                $channel = (string) ($message['channel'] ?? '');

                $context->subscriber($channel)($message);
            } catch (\Throwable $e) {
                // Keep processing other messages
            }
        }

        return new StateConnect();
    }
}
