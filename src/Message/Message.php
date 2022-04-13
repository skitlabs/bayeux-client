<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Message;

use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\Uuid;

class Message
{
    protected array $properties = [];

    public function __construct(array $properties = [])
    {
        $this->properties = array_merge([
            'channel' => '/meta/connect',
            'version' => '1.0',
            'minimumVersion' => '1.0',
            'clientId' => '',
            'id' => Uuid::uuid4()->toString(),
            'timestamp' => (new DateTimeImmutable())->format(DateTimeInterface::ATOM),
        ], $properties);
    }

    public function asArray() : array
    {
        return $this->properties;
    }
}
