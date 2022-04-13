<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\RequestOptions;
use Skitlabs\Bayeux\Authentication\Authentication;
use Skitlabs\Bayeux\Message\Message;

final class TransportGuzzle implements Transport
{
    private string $baseUrl;
    private Authentication $authentication;
    private ?Client $client = null;
    private CookieJar $cookieJar;

    public function __construct(string $baseUrl, Authentication $authentication)
    {
        $this->baseUrl = $baseUrl;
        $this->authentication = $authentication;
        $this->cookieJar = new CookieJar();
    }

    public function send(string $url, Message ... $messages): array
    {
        $response = $this->client()
            ->request(
                'POST',
                ltrim($url, '/'),
                [
                    RequestOptions::HTTP_ERRORS => false,
                    RequestOptions::TIMEOUT => 120,
                    RequestOptions::COOKIES => $this->cookieJar,
                    RequestOptions::HEADERS => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json;charset=UTF-8',
                        'User-Agent' => 'skitlabs/bayeux',
                        'Authorization' => $this->authentication->tokenType() . ' ' . $this->authentication->token(),
                    ],
                    RequestOptions::JSON => array_map(static fn (Message $message) => $message->asArray(), $messages),
                ],
            );

        return json_decode($response->getBody()->getContents(), true, JSON_THROW_ON_ERROR);
    }

    public function forceAuthenticate() : void
    {
        $this->authentication->reset();
    }

    private function client() : Client
    {
        if (! $this->client) {
            $this->client = new Client([
                'base_uri' => $this->baseUrl,
            ]);
        }

        return $this->client;
    }
}
