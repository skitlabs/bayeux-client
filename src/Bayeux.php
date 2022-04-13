<?php declare(strict_types=1);

namespace Skitlabs\Bayeux;

use Skitlabs\Bayeux\Http\HttpClient;
use Skitlabs\Bayeux\Message\Message;
use Skitlabs\Bayeux\State\State;
use Skitlabs\Bayeux\State\StateDisconnected;
use Skitlabs\Bayeux\State\StateHandshake;

class Bayeux
{
    private HttpClient $http;
    private State $state;
    private array $channels = [];
    private string $clientId = '';

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
        $this->state = new StateHandshake();
    }

    public function subscribe(string ... $channels) : self
    {
        $this->channels = array_unique(array_merge($this->channels, $channels));

        return $this;
    }

    /** @return array<array-key, string> */
    public function subscriptions() : array
    {
        return $this->channels;
    }

    public function setClientId(string $clientId) : void
    {
        $this->clientId = $clientId;
    }

    public function start() : void
    {
        do {
            try {
                dump(date("[Y-m-d H:i:s]") . ' ' . ($this->state::class));

                $this->state = $this->state->process($this);
            } catch (\Throwable $e) {
                dump($e);
                $this->state = new StateDisconnected($e->getMessage());
            }
        } while (! $this->state->isDisconnected());
    }

    public function send(string $url, Message ... $messages) : array
    {
        $messages = array_map(fn (Message $message) => $message->setClientId($this->clientId)->asArray(), $messages);

        dump(date("[Y-m-d H:i:s]") . ' ' . $url . ' ' . json_encode($messages, JSON_THROW_ON_ERROR));

        $response = $this->http->post($url, $messages);

        dump(date("[Y-m-d H:i:s]") . ' ' . $url . ' ' . json_encode($response, JSON_THROW_ON_ERROR));

        return $response;
    }
}
