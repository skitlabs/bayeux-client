<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Transport\Decorator;

use Psr\Log\LoggerInterface;
use Skitlabs\Bayeux\Message\Message;
use Skitlabs\Bayeux\Transport\Transport;

final class TransportLogging implements Transport
{
    private Transport $original;
    private LoggerInterface $logger;

    private function __construct(Transport $original, LoggerInterface $logger)
    {
        $this->original = $original;
        $this->logger = $logger;
    }

    public static function decorate(Transport $original, LoggerInterface $logger) : self
    {
        return new self($original, $logger);
    }

    public function send(string $url, Message ...$messages): array
    {
        $this->logger->debug($url, array_map(static fn (Message $message) => $message->asArray(), $messages));

        $response = $this->original->send($url, ... $messages);

        $this->logger->debug($url, $response);

        return $response;
    }
}
