<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Extension\Replay;

use Closure;
use Skitlabs\Bayeux\Extension\Extension;
use Skitlabs\Bayeux\Message\Message;

class ExtensionInvalidReplayId implements Extension
{
    private Closure $closure;

    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    public function getName() : string
    {
        return __CLASS__;
    }

    public function processOutgoing(Message $message) : void
    {
        // Nothing to do on outgoing messages
    }

    /**
     * Example message;
     * {
     *  "clientId": "i9lforkm2jvg2z19w4a0vngqcao",
     *  "channel": "/meta/subscribe",
     *  "id": "79017b98-a09a-40f8-93d4-8c47a63c0a6a",
     *  "subscription": "/data/ChangeEvents",
     *  "error": "400::The replayId {1} you provided was invalid.  Please provide a valid ID, -2 to replay all events, or -1 to replay only new events.",
     *  "successful": false
     * }
     */
    public function processIncoming(array $message) : array
    {
        $error = (string) ($message['error'] ?? '');
        $pattern = '/^400::The replayId \{.*\} you provided was invalid/s';

        preg_match_all($pattern, $error, $matches, PREG_SET_ORDER, 0);

        return count($message) < 1 ? $message : call_user_func($this->closure, $message);
    }
}
