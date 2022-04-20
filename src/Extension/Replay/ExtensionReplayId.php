<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Extension\Replay;

use Skitlabs\Bayeux\Extension\Extension;
use Skitlabs\Bayeux\Extension\Replay\Persistence\InMemoryPersistence;
use Skitlabs\Bayeux\Extension\Replay\Persistence\Persistence;
use Skitlabs\Bayeux\Message\Message;
use function str_starts_with;

class ExtensionReplayId implements Extension
{
    public const REPLAY_NEWEST = -1;

    private Persistence $persistence;

    public function __construct(Persistence $persistence = new InMemoryPersistence())
    {
        $this->persistence = $persistence;
    }

    public function getName(): string
    {
        return 'replay';
    }

    public function processOutgoing(Message $message) : void
    {
        if (! $message->isMeta('subscribe')) {
            return;
        }

        $channel = $message->get('subscription');
        $message->extend($this->getName(), [
            $channel => $this->getReplayId($channel),
        ]);
    }

    public function processIncoming(array $message) : array
    {
        $channel = $message[0]['channel'] ?? '';
        if (str_starts_with('/meta/', $channel)) {
            return $message;
        }

        $replayId = $message[0]['data']['event']['replayId'] ?? null;
        if (! $replayId || ! is_int($replayId)) {
            return $message;
        }

        $this->setReplayId($channel, $replayId);

        return $message;
    }

    public function setReplayId(string $channel, int $replayId) : self
    {
        $this->persistence->write($channel, $replayId);

        return $this;
    }

    public function getReplayId(string $channel) : int
    {
        return $this->persistence->read($channel, self::REPLAY_NEWEST);
    }
}
