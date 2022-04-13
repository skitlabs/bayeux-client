<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Closure;
use Skitlabs\Bayeux\Bayeux;

class StateProcessing extends State
{
    private array $messages;

    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }

    public function process(Bayeux $client) : State
    {
        foreach ($this->messages as $message) {
            try {
                $channel = (string) ($message['channel'] ?? '');
                $subscriber = $client->subscriptions()[$channel] ?? static function () {};
                if ($subscriber instanceof Closure) {
                    $subscriber($message);
                }
            } catch (\Throwable $e) {
                // Keep processing other messages
            }
        }

        return new StateConnect();
    }
}
