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
    private State $state;

    /** @var array<array-key, Closure> */
    private array $subscriptions = [];
    private string $clientId = '';

    public function __construct(HttpClient $http, ?LoggerInterface $logger = null)
    {
        $this->http = $http;
        $this->logger = $logger;
        $this->state = new StateHandshake();
    }

    /** Subscribe to a channel, and handle all events through the given callback. */
    public function subscribe(string $channel, Closure $closure) : self
    {
        $this->subscriptions[$channel] = $closure;

        return $this;
    }

    /** @return array<array-key, Closure> */
    public function subscriptions() : array
    {
        return $this->subscriptions;
    }

    /** @return array<array-key, string> */
    public function channels() : array
    {
        return array_keys($this->subscriptions);
    }

    public function setClientId(string $clientId) : void
    {
        $this->clientId = $clientId;
    }

    public function start() : void
    {
        do {
            try {
                $this->logger?->debug($this->state::class);

                $this->state = $this->state->process($this);
            } catch (\Throwable $e) {
                $this->logger?->critical($e->getMessage());

                $this->state = new StateDisconnected($e->getMessage());
            }
        } while (! $this->state->isDisconnected());
    }

    public function send(string $url, Message ... $messages) : array
    {
        $messages = array_map(fn (Message $message) => $message->setClientId($this->clientId)->asArray(), $messages);

        $this->logger?->debug($url, $messages);

        $response = $this->http->post($url, $messages);

        $this->logger?->debug($url, $response);

        return $response;
    }
}
