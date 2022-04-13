<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\State;

use Skitlabs\Bayeux\Bayeux;

class StateSleep extends State
{
    private int $seconds;
    private State $next;

    public function __construct(int $seconds, State $next)
    {
        $this->seconds = min($seconds, 120);
        $this->next = $next;
    }

    public function process(Bayeux $client) : State
    {
        sleep($this->seconds);

        return $this->next;
    }
}
