<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Context;
use Skitlabs\Bayeux\Transport\Transport;

class StateProcessing extends State
{
    private array $messages;

    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }

    public function process(Transport $transport, Context $context) : State
    {
        foreach ($this->messages as $message) {
            try {
                $channel = (string) ($message['channel'] ?? '');

                $context->subscriber($channel)($message);
            } catch (\Throwable) {
                // Keep processing other messages
            }
        }

        return new StateConnect();
    }
}
