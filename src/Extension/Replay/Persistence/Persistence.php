<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Extension\Replay\Persistence;

interface Persistence
{
    public function write(string $channel, int $replayId) : void;

    public function read(string $channel, int $default) : int;
}
