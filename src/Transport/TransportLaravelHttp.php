<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Transport;

use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Http\Client\PendingRequest;
use Skitlabs\Bayeux\Authentication\Authentication;
use Skitlabs\Bayeux\Message\Message;

final class TransportLaravelHttp implements Transport
{
    private string $baseUrl;
    private Authentication $authentication;
    private CookieJar $cookieJar;

    public function __construct(string $baseUrl, Authentication $authentication)
    {
        $this->baseUrl = $baseUrl;
        $this->authentication = $authentication;
        $this->cookieJar = new CookieJar();
    }

    public function send(string $url, Message ... $messages): array
    {
        return $this->client()
            ->post(
                $url,
                array_map(static fn (Message $message) => $message->asArray(), $messages)
            )->json();
    }

    private function client() : PendingRequest
    {
        return (new PendingRequest)
            ->acceptJson()
            ->withUserAgent('skitlabs/bayeux')
            ->baseUrl($this->baseUrl)
            ->timeout(120)
            ->withToken(
                $this->authentication->token(),
                $this->authentication->tokenType()
            )
            ->withOptions([
                'cookies' => $this->cookieJar,
            ])
            ->asJson();
    }
}
