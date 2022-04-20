<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Extension\Replay\Persistence;

class InMemoryPersistence implements Persistence
{
    /** @var array<string, int> */
    private array $map = [];

    public function write(string $channel, int $replayId) : void
    {
        $this->map[$channel] = $replayId;
    }

    public function read(string $channel, int $default) : int
    {
        return $this->map[$channel] ?? $default;
    }
}
