<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Extension\Replay\Persistence;

use Redis;

class RedisPersistence implements Persistence
{
    private Redis $redis;
    private string $prefix;

    public function __construct(Redis $redis, string $prefix = 'REPLAY_EXT__')
    {
        $this->redis = $redis;
        $this->prefix = $prefix;
    }

    public function write(string $channel, int $replayId) : void
    {
        $this->redis->set($this->cacheKey($channel), $replayId);
    }

    public function read(string $channel, int $default) : int
    {
        $cached = $this->redis->get($this->cacheKey($channel));

        if (! $cached) {
            return $default;
        }

        return (int) $cached;
    }

    private function cacheKey(string $channel) : string
    {
        return $this->prefix . md5($channel);
    }
}
