<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Client;

use Closure;
use Skitlabs\Bayeux\Context;
use Skitlabs\Bayeux\Extension\Extension;
use Skitlabs\Bayeux\State\State;
use Skitlabs\Bayeux\State\StateDisconnected;
use Skitlabs\Bayeux\State\StateHandshake;
use Skitlabs\Bayeux\Transport\Transport;
use Throwable;

class Bayeux
{
    private Context $context;
    private Transport $transport;
    private State $state;

    public function __construct(Transport $transport)
    {
        $this->transport = $transport;
        $this->context = new Context();
        $this->state = new StateHandshake();
    }

    public function subscribe(string $channel, Closure $closure) : self
    {
        $this->context->subscribe($channel, $closure);

        return $this;
    }

    public function extend(Extension ... $extensions) : self
    {
        foreach ($extensions as $extension) {
            $this->context->extend($extension);
        }

        return $this;
    }

    public function start() : void
    {
        do {
            try {
                $this->state = $this->state->process($this->transport, $this->context);
            } catch (Throwable $e) {
                dd($e);
                $this->state = new StateDisconnected($e->getMessage());
            }
        } while (! $this->state->isDisconnected());
    }
}
