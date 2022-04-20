<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Extension\SalesForce;

use Psr\Log\LoggerInterface;
use Skitlabs\Bayeux\Extension\Extension;
use Skitlabs\Bayeux\Message\Message;

class SalesForceErrorLoggingExtension implements Extension
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getName() : string
    {
        return 'sfdc';
    }

    public function processOutgoing(Message $message) : void
    {
        // Nothing to do for outgoing messages
    }

    public function processIncoming(array $message) : array
    {
        $successful = (bool) ($message[0]['successful'] ?? false);
        $ext = (array) ($message[0]['ext'][$this->getName()] ?? []);
        $failureReason = $ext['failureReason'] ?? null;

        if (! $successful && $failureReason) {
            $this->logger->error("SFDC error occurred", [
                'error' => $failureReason,
            ]);
        }

        return $message;
    }
}
