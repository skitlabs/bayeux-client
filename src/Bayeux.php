<?php declare(strict_types=1);

namespace Skitlabs\Bayeux;

use Closure;
use Psr\Log\LoggerInterface;
use Skitlabs\Bayeux\Http\HttpClient;
use Skitlabs\Bayeux\Message\Message;
use Skitlabs\Bayeux\State\State;
use Skitlabs\Bayeux\State\StateDisconnected;
use Skitlabs\Bayeux\State\StateHandshake;

class Bayeux
{
    private HttpClient $http;
    private ?LoggerInterface $logger;
    private Context $context;
    private State $state;

    public function __construct(HttpClient $http, ?LoggerInterface $logger = null)
    {
        $this->http = $http;
        $this->logger = $logger;
        $this->context = new Context();
        $this->state = new StateHandshake();
    }

    /** Subscribe to a channel, and handle all events through the given callback. */
    public function subscribe(string $channel, Closure $closure) : self
    {
        $this->context->subscribe($channel, $closure);

        return $this;
    }

    /**
     * Start the main event loop; handshake, wait for message, process, and repeat.
     * Note that this method is _blocking_ and will only return once disconnected
     * from the remote server.
     */
    public function start() : void
    {
        do {
            try {
                $this->logger?->debug($this->state::class);

                $this->state = $this->state->process($this, $this->context);
            } catch (\Throwable $e) {
                $this->logger?->critical($e->getMessage());

                $this->state = new StateDisconnected($e->getMessage());
            }
        } while (! $this->state->isDisconnected());
    }

    public function send(string $url, Message ... $messages) : array
    {
        $messages = array_map(fn (Message $message) => $message->withContext($this->context)->asArray(), $messages);

        $this->logger?->debug($url, $messages);

        $response = $this->http->post($url, $messages);

        $this->logger?->debug($url, $response);

        return $response;
    }
}
